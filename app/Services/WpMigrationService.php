<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class WpMigrationService
{
    // Mapeamento de Status
    protected $statusMap = [
        'for-sale' => 'Venda',
        'para-venda' => 'Venda',
        'venda' => 'Venda',
        'for-rent' => 'Arrendamento',
        'arrendamento' => 'Arrendamento',
        'trespasse' => 'Venda',
    ];

    // Mapeamento de Tipos
    protected $typeMap = [
        'apartment' => 'Apartamento',
        'apartamento' => 'Apartamento',
        'villa' => 'Moradia',
        'moradia' => 'Moradia',
        'house' => 'Moradia',
        'land' => 'Terreno',
        'terreno' => 'Terreno',
        'commercial' => 'Comercial',
        'comercial' => 'Comercial',
        'shop' => 'Comercial',
        'store' => 'Comercial',
    ];

    public function importProperty($wpPost)
    {
        // 1. Verifica duplicidade (se já rodou antes)
        $legacySlug = Str::slug($wpPost->post_title) . '-' . $wpPost->ID;
        if (Property::where('slug', $legacySlug)->exists()) {
            return 'skipped';
        }

        // 2. Busca Todos os Metadados
        $meta = DB::connection('wordpress')
            ->table('postmeta')
            ->where('post_id', $wpPost->ID)
            ->pluck('meta_value', 'meta_key');

        // 3. Resolve Taxonomias
        $statusTerm = $this->resolveTaxonomy($wpPost->ID, 'property-status');
        $typeTerm = $this->resolveTaxonomy($wpPost->ID, 'property-type');
        $cityTerm = $this->resolveTaxonomy($wpPost->ID, 'property-city');

        // 4. Criação do Objeto
        $property = new Property();
        $property->user_id = 1; // Admin
        $property->title = $wpPost->post_title;
        $property->slug = $legacySlug;
        
        // Descrição limpa
        $desc = strip_tags($wpPost->post_content, '<p><br><ul><li>');
        $property->description = preg_replace('/\[.*?\]/', '', $desc); // Remove shortcodes

        // Status e Tipo
        $property->status = $this->statusMap[$statusTerm] ?? 'Venda';
        $property->type = $this->typeMap[$typeTerm] ?? 'Apartamento';

        // Tratamento especial para "Trespasse" ou Labels (Vendido)
        $label = $meta->get('inspiry_property_label');
        if ($statusTerm === 'trespasse') {
            $property->title .= ' (Trespasse)';
            $property->type = 'Comercial';
        }
        if ($label && stripos($label, 'vendi') !== false) {
            $property->title = '[VENDIDO] ' . $property->title;
        }

        // Valores Numéricos
        $property->price = $this->cleanNumber($meta->get('REAL_HOMES_property_price'));
        $property->area_gross = $this->cleanNumber($meta->get('REAL_HOMES_property_size') ?? $meta->get('REAL_HOMES_property_area'));
        $property->bedrooms = (int) $meta->get('REAL_HOMES_property_bedrooms');
        $property->bathrooms = (int) $meta->get('REAL_HOMES_property_bathrooms');
        $property->garages = (int) $meta->get('REAL_HOMES_property_garage');
        $property->built_year = $meta->get('REAL_HOMES_property_year_built');
        $property->energy_rating = $meta->get('REAL_HOMES_property_energy_class');
        $property->crm_code = $meta->get('REAL_HOMES_property_id');

        // Endereço
        $address = $meta->get('REAL_HOMES_property_address');
        $property->address = $address;
        $property->location = $cityTerm ? ucfirst($cityTerm) : ($address ?? 'Importado');

        // Coordenadas
        $loc = $meta->get('REAL_HOMES_property_location');
        if ($loc && str_contains($loc, ',')) {
            [$lat, $lng] = explode(',', $loc);
            $property->latitude = trim($lat);
            $property->longitude = trim($lng);
        }

        // Features (Booleanos)
        $property->has_pool = $this->hasFeature($wpPost->ID, ['pool', 'piscina']);
        $property->has_garden = $this->hasFeature($wpPost->ID, ['garden', 'jardim']);
        $property->has_lift = $this->hasFeature($wpPost->ID, ['elevador', 'lift']);
        $property->has_air_conditioning = $this->hasFeature($wpPost->ID, ['ar condicionado', 'air']);
        $property->is_furnished = $this->hasFeature($wpPost->ID, ['mobilado', 'furnished']);

        $property->is_visible = ($wpPost->post_status === 'publish');
        $property->order = 9999;

        $property->save();

        // ---------------------------------------------------------
        // 5. MIGRAÇÃO DE IMAGENS
        // ---------------------------------------------------------
        
        // A. Imagem de Capa
        $thumbId = $meta->get('_thumbnail_id');
        if ($thumbId) {
            $newPath = $this->migrateAttachment($thumbId, 'covers');
            if ($newPath) {
                $property->cover_image = $newPath;
                $property->save();
            }
        }

        // B. Galeria
        $galleryIds = $meta->get('REAL_HOMES_property_images');
        if ($galleryIds) {
            $ids = is_string($galleryIds) ? explode(',', $galleryIds) : maybe_unserialize($galleryIds);
            
            if (is_array($ids)) {
                foreach ($ids as $imgId) {
                    $galleryPath = $this->migrateAttachment($imgId, 'gallery');
                    if ($galleryPath) {
                        PropertyImage::create([
                            'property_id' => $property->id,
                            'path' => $galleryPath,
                            'order' => 0
                        ]);
                    }
                }
            }
        }

        return 'imported';
    }

    private function migrateAttachment($attachmentId, $subfolder)
    {
        if (!$attachmentId) return null;

        $wpPath = DB::connection('wordpress')
            ->table('postmeta')
            ->where('post_id', $attachmentId)
            ->where('meta_key', '_wp_attached_file')
            ->value('meta_value');

        if (!$wpPath) return null;

        // Tenta encontrar o arquivo na estrutura extraída
        $sourcePath = storage_path('app/legacy_uploads/' . $wpPath);
        
        if (!file_exists($sourcePath)) {
            $filename = basename($wpPath);
            $sourcePath = storage_path('app/legacy_uploads/' . $filename);
        }

        if (!file_exists($sourcePath)) {
            return null;
        }

        $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);
        $newFilename = 'imp_' . Str::random(10) . '.' . $extension;
        $destPath = "properties/{$subfolder}/{$newFilename}";

        Storage::disk('public')->put($destPath, file_get_contents($sourcePath));

        return $destPath;
    }

    private function resolveTaxonomy($postId, $taxonomy)
    {
        return DB::connection('wordpress')
            ->table('term_relationships as tr')
            ->join('term_taxonomy as tt', 'tr.term_taxonomy_id', '=', 'tt.term_taxonomy_id')
            ->join('terms as t', 'tt.term_id', '=', 't.term_id')
            ->where('tr.object_id', $postId)
            ->where('tt.taxonomy', $taxonomy)
            ->value('t.slug');
    }

    private function hasFeature($postId, array $keywords)
    {
        $features = DB::connection('wordpress')
            ->table('term_relationships as tr')
            ->join('term_taxonomy as tt', 'tr.term_taxonomy_id', '=', 'tt.term_taxonomy_id')
            ->join('terms as t', 'tt.term_id', '=', 't.term_id')
            ->where('tr.object_id', $postId)
            ->where('tt.taxonomy', 'property-feature')
            ->pluck('t.slug')
            ->toArray();

        foreach ($keywords as $k) {
            foreach ($features as $f) {
                if (str_contains($f, $k)) return true;
            }
        }
        return false;
    }

    private function cleanNumber($val) {
        return (float) preg_replace('/[^0-9.]/', '', $val ?? 0);
    }
}

// Helpers fora da classe para lidar com serialização do WP
if (!function_exists('maybe_unserialize')) {
    function maybe_unserialize($data) {
        if (is_serialized($data)) return @unserialize($data);
        return $data;
    }

    function is_serialized($data, $strict = true) {
        if (!is_string($data)) return false;
        $data = trim($data);
        if ('N;' == $data) return true;
        if (strlen($data) < 4) return false;
        if (':' !== $data[1]) return false; // <--- CORRIGIDO AQUI (Adicionei parênteses)
        
        if ($strict) {
            $lastc = substr($data, -1);
            if (';' !== $lastc && '}' !== $lastc) return false;
        } else {
            $semicolon = strpos($data, ';');
            $brace     = strpos($data, '}');
            if (false === $semicolon && false === $brace) return false;
            if (false !== $semicolon && $semicolon < 3) return false;
            if (false !== $brace && $brace < 4) return false;
        }
        $token = $data[0];
        switch ($token) {
            case 's':
                if ($strict) {
                    if ('"' !== substr($data, -2, 1)) return false;
                } elseif (false === strpos($data, '"')) {
                    return false;
                }
            case 'a':
            case 'O':
                return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';
                return (bool) preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
        }
        return false;
    }
}