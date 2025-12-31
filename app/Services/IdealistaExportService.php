<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class IdealistaExportService extends AbstractIdealistaService
{
    /**
     * Cria um novo imóvel (Fase 1 - Create)
     */
    public function createProperty($localProperty)
    {
        // Usa token 'write' para permissão de escrita
        $headers = $this->getHeaders('write');

        // 1. Resolve o Contato (Obrigatório ter ID antes de criar imóvel)
        $contactId = $this->getOrCreateDefaultContact($headers);
        
        if (!$contactId) {
            throw new Exception("Não foi possível obter um ID de contato válido.");
        }

        // 2. Monta Payload
        $payload = $this->mapToIdealistaPayload($localProperty, $contactId);

        Log::info("Enviando Imóvel ID {$localProperty->id}...", $payload);

        $response = Http::withHeaders($headers)
            ->post("{$this->baseUrl}/v1/properties", $payload);

        if ($response->failed()) {
            Log::error('Erro API Idealista (Create): ' . $response->body());
            throw new Exception('Erro ao criar imóvel: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Atualiza um imóvel existente (Fase 2 - Update)
     */
    public function updateProperty($localProperty, $idealistaId)
    {
        // Usa token 'write'
        $headers = $this->getHeaders('write');

        // Garante que temos o contato (igual ao create)
        $contactId = $this->getOrCreateDefaultContact($headers);

        // Reaproveita a mesma montagem de payload
        $payload = $this->mapToIdealistaPayload($localProperty, $contactId);

        Log::info("Atualizando Imóvel Idealista ID {$idealistaId}...", $payload);

        // Endpoint PUT: /v1/properties/{id}
        $response = Http::withHeaders($headers)
            ->put("{$this->baseUrl}/v1/properties/{$idealistaId}", $payload);

        if ($response->failed()) {
            Log::error('Erro API Idealista (Update): ' . $response->body());
            throw new Exception('Erro ao atualizar imóvel: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Envia as imagens do imóvel
     */
    public function uploadImages($idealistaId, $localProperty)
    {
        $headers = $this->getHeaders('write');
        
        if (!$localProperty->relationLoaded('images')) $localProperty->load('images');
        $images = $localProperty->images; 

        if ($images->isEmpty()) return null;

        $imagesPayload = [];
        foreach ($images as $img) {
            // OBS: Em localhost o Idealista não consegue baixar a imagem, mas enviamos a URL mesmo assim.
            $fullUrl = asset('storage/' . $img->path);
            
            $imagesPayload[] = [
                'url' => $fullUrl,
                'label' => 'unknown' // Label genérico seguro para evitar validações chatas
            ];
            
            if (count($imagesPayload) >= 200) break;
        }

        $payload = ['images' => $imagesPayload];

        $response = Http::withHeaders($headers)
            ->put("{$this->baseUrl}/v1/properties/{$idealistaId}/images", $payload);

        if ($response->failed()) {
            throw new Exception('Erro upload imagens: ' . $response->body());
        }

        return $response->json();
    }

    // --- Helpers Privados ---

    private function getOrCreateDefaultContact($headers)
    {
        // Tenta listar primeiro
        $response = Http::withHeaders($headers)->get("{$this->baseUrl}/v1/contacts");

        if ($response->successful()) {
            $contacts = $response->json();
            $list = $contacts['contacts'] ?? $contacts ?? [];
            if (!empty($list)) {
                return $list[0]['contactId'] ?? $list[0]['code'] ?? $list[0]['id'];
            }
        }

        // Se não existir, cria
        $newContact = [
            'name' => 'Agente House Team',
            'email' => 'admin@houseteam.pt',
            'primaryPhoneNumber' => '910000000',
        ];

        $createParams = Http::withHeaders($headers)->post("{$this->baseUrl}/v1/contacts", $newContact);
        $data = $createParams->json();
        
        return $data['contactId'] ?? $data['code'] ?? null;
    }

    protected function mapToIdealistaPayload($property, $contactId)
    {
        $type = $this->mapType($property->type);
        
        // --- Proteção de Área Mínima ---
        $area = (int) $property->area_gross;
        if ($area < 20) $area = 60; // Garante que nunca envie menos que 20m²

        $rooms = (int) $property->bedrooms;
        if ($rooms < 0) $rooms = 1; 

        $baths = (int) $property->bathrooms;
        if ($baths < 1) $baths = 1;

        $price = (float) $property->price;
        if ($price < 1) $price = 100000.00;

        // Monta features base
        $features = [
            'rooms' => $rooms,            
            'bathroomNumber' => $baths,   
            'areaConstructed' => $area,   
            'energyCertificateRating' => 'unknown',
            'conservation' => 'good',
        ];

        // Adiciona elevador apenas se for apartamento
        if ($type === 'flat') {
            $features['liftAvailable'] = (bool) $property->has_lift;
        }

        // Se for terreno, remove campos incompatíveis
        if ($type === 'land') {
            unset($features['rooms'], $features['bathroomNumber'], $features['energyCertificateRating'], $features['conservation']);
            if (isset($features['liftAvailable'])) unset($features['liftAvailable']);
            $features['areaPlot'] = $area;
            unset($features['areaConstructed']);
        }

        $payload = [
            'type' => $type,
            'reference' => (string) $property->id,
            
            // --- Endereço Rigoroso ---
            'address' => [
                'visibility' => 'hidden',
                'precision'  => 'exact',
                'country'    => 'Portugal',
                'streetName' => $property->address ?? 'Rua Principal',
                'streetNumber' => '1',
                'postalCode' => $this->formatPostalCode($property->postal_code),
                'town' => $property->city ?? 'Lisboa',
            ],

            'operation' => [
                'type' => $this->mapOperation($property->status),
                'price' => $price
            ],

            'features' => $features,

            'descriptions' => [
                ['language' => 'pt', 'text' => substr(strip_tags($property->description ?? 'Imóvel disponível para venda.'), 0, 3000)]
            ],

            'contactId' => (int) $contactId
        ];

        return $payload;
    }

    protected function formatPostalCode($code)
    {
        if (!$code) return '1000-001';
        $code = preg_replace('/[^0-9]/', '', $code);
        if (strlen($code) >= 7) {
            return substr($code, 0, 4) . '-' . substr($code, 4, 3);
        }
        return '1000-001';
    }

    protected function mapType($type)
    {
        return match (strtolower($type)) {
            'apartamento' => 'flat',
            'moradia' => 'chalet',
            'terreno' => 'land',
            'comercial' => 'office',
            'garagem' => 'garage',
            'prédio' => 'building',
            default => 'flat',
        };
    }

    protected function mapOperation($status)
    {
        return match (strtolower($status)) {
            'arrendamento' => 'rent',
            default => 'sale',
        };
    }
}