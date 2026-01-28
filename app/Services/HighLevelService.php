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
        // 1. Tenta encontrar primeiro pelo email
        if (!empty($data['email'])) {
            $existing = $this->lookupContact($data['email']);
            if ($existing) {
                return $existing; 
            }
        }

        // 2. Se não existe, prepara para criar
        $names = $this->splitName($data['name'] ?? '');

        $payload = [
            'firstName' => $names['firstName'],
            'lastName'  => $names['lastName'],
            'email'     => $data['email'] ?? null,
            'phone'     => $data['phone'] ?? null,
            'tags'      => $data['tags'] ?? [],
            'source'    => $data['source'] ?? 'Site House Team',
        ];

        // Limpa campos vazios
        $payload = array_filter($payload, fn($value) => !empty($value));

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/contacts/", $payload);

            if ($response->successful()) {
                $contact = $response->json('contact');
                Log::info('GHL: Contato criado. ID: ' . ($contact['id'] ?? 'N/A'));
                return $contact;
            }

            // Fallback para duplicados não detectados antes
            Log::warning('GHL: Erro criação (possível duplicado), recuperando...', ['body' => $response->body()]);
            return $this->lookupContact($data['email']);

        } catch (\Exception $e) {
            Log::error('GHL: Exceção CreateContact', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Procura contato pelo Email
     */
    public function lookupContact($email)
    {
        if (empty($email)) return null;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/contacts/lookup", ['email' => $email]);
            
            if ($response->successful()) {
                $contacts = $response->json('contacts');
                return $contacts[0] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('GHL: Erro Lookup', ['msg' => $e->getMessage()]);
        }
        return null;
    }

    /**
     * Atribui o Contacto a um Consultor
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
            Log::error('GHL: Erro assignUser', ['msg' => $e->getMessage()]);
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
            Log::error('GHL: Erro addNote', ['msg' => $e->getMessage()]);
        }
    }

    /**
     * Cria Oportunidade no Funil Correto (Lógica Atualizada)
     */
    public function createOpportunity(string $contactId, array $data, string $type = 'lead')
    {
        // 1. Mapeamento Inteligente (Tipo -> Configuração no services.php)
        $configMap = [
            // Compradores
            'buyer'       => ['pipe' => 'buyers',      'stage' => 'buyers_new',      'suffix' => ' - Comprador'],
            
            // Vendedores / Proprietários
            'seller'      => ['pipe' => 'sellers',     'stage' => 'sellers_new',     'suffix' => ' - Venda de Imóvel'],
            'valuation'   => ['pipe' => 'sellers',     'stage' => 'sellers_new',     'suffix' => ' - Pedido de Avaliação'],
            
            // Recrutamento
            'recruitment' => ['pipe' => 'recruitment', 'stage' => 'recruitment_new', 'suffix' => ' - Candidatura Espontânea'],
            
            // Crédito
            'credit'      => ['pipe' => 'credit',      'stage' => 'credit_new',      'suffix' => ' - Crédito Habitação'],
            
            // Padrão (Lead Geral)
            'lead'        => ['pipe' => 'buyers',      'stage' => 'buyers_new',      'suffix' => ' - Lead Site'],
        ];

        // 2. Define a configuração a usar
        $conf = $configMap[$type] ?? $configMap['lead'];

        // 3. Busca os IDs HARDCODED no config/services.php
        $pipelineId = config("services.ghl.pipelines.{$conf['pipe']}");
        $stageId    = config("services.ghl.stages.{$conf['stage']}");

        if (!$pipelineId || !$stageId) {
            Log::critical("GHL: Configuração CRÍTICA ausente para: {$type}. Verifique config/services.php");
            return false;
        }

        // 4. Monta o payload
        $payload = [
            'pipelineId' => $pipelineId,
            'stageId'    => $stageId,
            'contactId'  => $contactId,
            'title'      => ($data['name'] ?? 'Lead') . $conf['suffix'],
            'status'     => 'open',
            'source'     => $data['source'] ?? 'Site House Team',
        ];

        if (!empty($data['property_price'])) {
            $payload['monetaryValue'] = (float) $data['property_price'];
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/pipelines/{$pipelineId}/opportunities", $payload);

            if ($response->successful()) {
                Log::info("GHL: Oportunidade criada no pipeline [{$conf['pipe']}]");
                return true;
            }

            Log::error('GHL: Falha API Opportunity', ['body' => $response->body()]);
            return false;

        } catch (\Exception $e) {
            Log::error('GHL: Exceção CreateOpportunity', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Busca pipelines para DEBUG
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