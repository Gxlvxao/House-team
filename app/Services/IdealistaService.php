<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class IdealistaService
{
    protected $key;
    protected $secret;
    protected $feedKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->key = config('services.idealista.key');
        $this->secret = config('services.idealista.secret');
        $this->feedKey = config('services.idealista.feed_key');
        $this->baseUrl = config('services.idealista.base_url');
    }

    // --- AUTENTICAÇÃO ---

    protected function getToken()
    {
        if (Cache::has('idealista_token')) {
            return Cache::get('idealista_token');
        }

        $credentials = base64_encode("{$this->key}:{$this->secret}");

        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => "Basic {$credentials}",
                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
            ])
            ->post("{$this->baseUrl}/oauth/token", [
                'grant_type' => 'client_credentials',
                'scope' => 'read write' 
            ]);

        if ($response->failed()) {
            Log::error('Erro Auth Idealista: ' . $response->body());
            throw new Exception('Falha na autenticação com Idealista: ' . $response->status());
        }

        $data = $response->json();
        $expiresIn = isset($data['expires_in']) ? (int)$data['expires_in'] - 60 : 200;
        if ($expiresIn <= 0) $expiresIn = 60;

        Cache::put('idealista_token', $data['access_token'], $expiresIn);

        return $data['access_token'];
    }

    public function getHeaders()
    {
        $token = $this->getToken();
        return [
            'Authorization' => "Bearer {$token}",
            'feedKey' => $this->feedKey,
            'Content-Type' => 'application/json',
        ];
    }

    // --- MÉTODOS DE LEITURA (IMPORTAÇÃO) ---

    public function getProperties($page = 1, $size = 50)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/v1/properties", [
                'page' => $page,
                'size' => $size
            ]);

        return $response->failed() ? [] : $response->json();
    }

    public function getPropertyImages($propertyId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/v1/properties/{$propertyId}/images");

        if ($response->failed()) return [];
        $json = $response->json();
        return $json['images'] ?? $json ?? [];
    }

    // --- MÉTODOS DE ESCRITA (EXPORTAÇÃO) ---

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
        // Garante que carrega as imagens do banco
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
            // Gera a URL pública completa para o Idealista baixar
            // OBS: Se estiver em Localhost, o Idealista não conseguirá acessar essa URL.
            $fullUrl = asset('storage/' . $img->path);

            $imagesPayload[] = [
                'url' => $fullUrl,
                'label' => 'unknown' // O Idealista tenta detectar o tipo automaticamente se for unknown
            ];
            
            // Limite de segurança de 200 imagens
            if (count($imagesPayload) >= 200) break;
        }

        $payload = ['images' => $imagesPayload];

        Log::info("Enviando imagens para imóvel Idealista {$idealistaId}", $payload);

        // O endpoint é PUT /v1/properties/{id}/images
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
        // 1. Tenta listar contatos existentes
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/v1/contacts");

        if ($response->successful()) {
            $contacts = $response->json();
            $list = $contacts['contacts'] ?? $contacts ?? [];
            if (!empty($list)) {
                // Retorna ID buscando todas as chaves possíveis
                return $list[0]['contactId'] ?? $list[0]['code'] ?? $list[0]['id'];
            }
        }

        // 2. Cria contato se não existir
        $newContactPayload = [
            'name' => 'Agente House Team',
            'email' => 'admin@houseteam.pt',
            'primaryPhoneNumber' => '910000000', // Formato aceito: Apenas números
        ];

        $createResponse = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/v1/contacts", $newContactPayload);

        if ($createResponse->failed()) {
            throw new Exception("Falha ao criar contato automático: " . $createResponse->body());
        }

        $data = $createResponse->json();
        
        $id = $data['contactId'] ?? $data['code'] ?? $data['id'] ?? null;

        if (!$id) {
            Log::error("Contato criado mas ID não encontrado na resposta:", $data);
            throw new Exception("Contato criado, mas a API não retornou um ID válido.");
        }

        return $id;
    }

    protected function mapToIdealistaPayload($property, $contactId)
    {
        $type = $this->mapType($property->type);
        
        // --- PROTEÇÃO CONTRA VALORES ZERADOS ---
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
                // CAMPOS CORRIGIDOS CONFORME VALIDAÇÃO
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

        // Regras Específicas
        if ($type === 'flat') {
            $payload['features']['liftAvailable'] = (bool) $property->has_lift;
        }

        if ($type === 'land') {
            unset($payload['features']['rooms']);
            unset($payload['features']['bathroomNumber']); 
            unset($payload['features']['energyCertificateRating']);
            unset($payload['features']['conservation']);
            unset($payload['features']['liftAvailable']);
            
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