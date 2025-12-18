<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class IdealistaExportService extends AbstractIdealistaService
{
    /**
     * Cria ou Atualiza o imóvel (Fase 1)
     */
    public function createProperty($localProperty)
    {
        $contactId = $this->getOrCreateDefaultContact();
        
        if (!$contactId || $contactId < 1) {
            throw new Exception("ID do contato inválido ({$contactId}). Não é possível criar imóvel.");
        }

        $payload = $this->mapToIdealistaPayload($localProperty, $contactId);

        Log::info("Enviando Imóvel ID {$localProperty->id} para Idealista:", $payload);

        $response = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/v1/properties", $payload);

        if ($response->failed()) {
            throw new Exception('Erro ao criar imóvel no Idealista: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Envia as imagens do imóvel (Fase 2)
     */
    public function uploadImages($idealistaId, $localProperty)
    {
        if (!$localProperty->relationLoaded('images')) {
            $localProperty->load('images');
        }

        $images = $localProperty->images; 

        if ($images->isEmpty()) {
            Log::warning("Imóvel {$localProperty->id} não tem imagens locais para enviar.");
            return null;
        }

        $imagesPayload = [];
        foreach ($images as $img) {
            $fullUrl = asset('storage/' . $img->path);
            
            $imagesPayload[] = [
                'url' => $fullUrl,
                'label' => 'unknown' 
            ];
            
            if (count($imagesPayload) >= 200) break;
        }

        $payload = ['images' => $imagesPayload];

        Log::info("Enviando imagens para imóvel Idealista {$idealistaId}", $payload);

        $response = Http::withHeaders($this->getHeaders())
            ->put("{$this->baseUrl}/v1/properties/{$idealistaId}/images", $payload);

        if ($response->failed()) {
            throw new Exception('Erro ao enviar imagens: ' . $response->body());
        }

        return $response->json();
    }

    // --- HELPERS E MAPEAMENTOS ---

    protected function getOrCreateDefaultContact()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/v1/contacts");

        if ($response->successful()) {
            $contacts = $response->json();
            $list = $contacts['contacts'] ?? $contacts ?? [];
            if (!empty($list)) {
                return $list[0]['contactId'] ?? $list[0]['code'] ?? $list[0]['id'];
            }
        }

        $newContactPayload = [
            'name' => 'Agente House Team',
            'email' => 'admin@houseteam.pt',
            'primaryPhoneNumber' => '910000000', 
        ];

        $createResponse = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/v1/contacts", $newContactPayload);

        if ($createResponse->failed()) {
            throw new Exception("Falha ao criar contato: " . $createResponse->body());
        }

        $data = $createResponse->json();
        $id = $data['contactId'] ?? $data['code'] ?? $data['id'] ?? null;

        if (!$id) {
            throw new Exception("Contato criado, mas ID não retornado.");
        }

        return $id;
    }

    protected function mapToIdealistaPayload($property, $contactId)
    {
        $type = $this->mapType($property->type);
        
        $area = (int) $property->area_gross;
        if ($area < 1) $area = 50; 

        $rooms = (int) $property->bedrooms;
        if ($rooms < 1) $rooms = 1; 

        $baths = (int) $property->bathrooms;
        if ($baths < 1) $baths = 1;

        $price = (float) $property->price;
        if ($price < 1) $price = 1000.00;

        $payload = [
            'type' => $type,
            'reference' => (string) $property->id,
            
            'address' => [
                'visibility' => 'hidden',
                'streetName' => $property->address ?? 'Rua Principal',
                'streetNumber' => '1',
                'postalCode' => $this->formatPostalCode($property->postal_code),
                'town' => $property->city ?? 'Lisboa',
                'country' => 'Portugal'
            ],

            'operation' => [
                'type' => $this->mapOperation($property->status),
                'price' => $price
            ],

            'features' => [
                'rooms' => $rooms,            
                'bathroomNumber' => $baths,   
                'areaConstructed' => $area,   
                'energyCertificateRating' => 'unknown',
                'conservation' => 'good',
            ],

            'descriptions' => [
                ['language' => 'pt', 'text' => substr(strip_tags($property->description ?? 'Imóvel disponível.'), 0, 3000)]
            ],

            'contactId' => (int) $contactId
        ];

        if ($type === 'flat') {
            $payload['features']['liftAvailable'] = (bool) $property->has_lift;
        }

        if ($type === 'land') {
            unset($payload['features']['rooms'], $payload['features']['bathroomNumber'], $payload['features']['energyCertificateRating'], $payload['features']['conservation'], $payload['features']['liftAvailable']);
            $payload['features']['areaPlot'] = $area;
            unset($payload['features']['areaConstructed']);
        }

        return $payload;
    }

    protected function formatPostalCode($code)
    {
        if (!$code) return '1000-001';
        if (preg_match('/^\d{7}$/', $code)) {
            return substr($code, 0, 4) . '-' . substr($code, 4, 3);
        }
        return $code;
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