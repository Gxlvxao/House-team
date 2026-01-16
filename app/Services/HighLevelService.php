<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HighLevelService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://rest.gohighlevel.com/v1';
        $this->apiKey = config('services.ghl.api_key');
    }

    protected function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    /**
     * Cria ou Recupera um contacto (Evita Duplicados)
     */
    public function createContact(array $data)
    {
        // 1. Tenta encontrar primeiro pelo email para evitar erros
        if (!empty($data['email'])) {
            $existing = $this->lookupContact($data['email']);
            if ($existing) {
                return $existing; // Retorna o existente para continuar o processo
            }
        }

        // 2. Se não existe, prepara os dados para criar
        $names = $this->splitName($data['name'] ?? '');

        $payload = [
            'firstName' => $names['firstName'],
            'lastName'  => $names['lastName'],
            'email'     => $data['email'] ?? null,
            'phone'     => $data['phone'] ?? null,
            'tags'      => $data['tags'] ?? [],
            'source'    => 'Site House Team - Whatsapp',
        ];

        // Remove campos vazios para limpar o payload
        $payload = array_filter($payload, fn($value) => !empty($value));

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/contacts/", $payload);

            if ($response->successful()) {
                $contact = $response->json('contact');
                Log::info('GHL: Contato criado com sucesso. ID: ' . ($contact['id'] ?? 'N/A'));
                return $contact;
            }

            // Fallback: Se der erro (ex: api lenta na sincronia), tenta buscar novamente
            Log::warning('GHL: Erro ao criar (possível duplicado), tentando recuperar...', ['body' => $response->body()]);
            return $this->lookupContact($data['email']);

        } catch (\Exception $e) {
            Log::error('GHL: Exceção ao Criar Contato', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Procura um contacto existente pelo Email
     */
    public function lookupContact($email)
    {
        if (empty($email)) return null;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/contacts/lookup", ['email' => $email]);
            
            if ($response->successful()) {
                $contacts = $response->json('contacts');
                // Retorna o primeiro da lista, se houver
                return $contacts[0] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('GHL: Erro no Lookup de contato', ['msg' => $e->getMessage()]);
        }
        return null;
    }

    /**
     * Atribui o Contacto a um Consultor (User do GHL)
     */
    public function assignUser($contactId, $userId)
    {
        if (!$contactId || !$userId) return;

        try {
            Http::withHeaders($this->getHeaders())
                ->put("{$this->baseUrl}/contacts/{$contactId}", [
                    'assignedTo' => $userId
                ]);
            Log::info("GHL: Contacto {$contactId} atribuído ao user {$userId}");
        } catch (\Exception $e) {
            Log::error('GHL: Erro ao atribuir user', ['msg' => $e->getMessage()]);
        }
    }

    public function addNote(string $contactId, string $noteContent)
    {
        if (empty($noteContent)) return;

        try {
            Http::withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/contacts/{$contactId}/notes", [
                    'body' => $noteContent
                ]);
        } catch (\Exception $e) {
            Log::error('GHL: Erro ao adicionar nota', ['msg' => $e->getMessage()]);
        }
    }

    /**
     * Cria Oportunidade no Funil Correto
     */
    public function createOpportunity(string $contactId, array $data, string $type = 'lead')
    {
        // Mapeamento dos tipos para as configurações do services.php
        $configMap = [
            'credit'  => ['pipe' => 'credit_id',  'stage' => 'credit_new_id',  'suffix' => ' - Crédito Habitação'],
            'listing' => ['pipe' => 'listing_id', 'stage' => 'listing_new_id', 'suffix' => ' - Angariação (Mais-Valias)'],
            'buyer'   => ['pipe' => 'buyers_id',  'stage' => 'buyers_new_id',  'suffix' => ' - Comprador (IMT)'],
            'lead'    => ['pipe' => 'leads_id',   'stage' => 'leads_new_id',   'suffix' => ' - Lead Site'],
        ];

        // Se o tipo não existir, usa 'lead' (Geral) como padrão
        $conf = $configMap[$type] ?? $configMap['lead'];

        $pipelineId = config("services.ghl.pipelines.{$conf['pipe']}");
        $stageId    = config("services.ghl.stages.{$conf['stage']}");

        if (!$pipelineId || !$stageId) {
            Log::warning("GHL: Configuração de Pipeline/Stage ausente para o tipo: $type");
            return false;
        }

        $payload = [
            'pipelineId' => $pipelineId,
            'stageId'    => $stageId,
            'contactId'  => $contactId,
            'title'      => ($data['name'] ?? 'Cliente') . $conf['suffix'],
            'status'     => 'open',
            'source'     => 'Site House Team - Whatsapp',
        ];

        if (!empty($data['property_price'])) {
            $payload['monetaryValue'] = (float) $data['property_price'];
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/pipelines/{$pipelineId}/opportunities", $payload);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('GHL: Exceção ao criar Oportunidade', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Busca pipelines para DEBUG (Ajuda a encontrar os IDs)
     */
    public function getPipelines()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get("{$this->baseUrl}/pipelines/");
            return $response->json('pipelines');
        } catch (\Exception $e) { return []; }
    }

    private function splitName($fullname)
    {
        $parts = explode(' ', trim($fullname), 2);
        return [
            'firstName' => $parts[0] ?? '',
            'lastName'  => $parts[1] ?? ''
        ];
    }
}