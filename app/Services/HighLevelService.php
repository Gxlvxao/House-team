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

    public function createContact(array $data)
    {
        $names = $this->splitName($data['name'] ?? '');

        $payload = [
            'firstName' => $names['firstName'],
            'lastName'  => $names['lastName'],
            'email'     => $data['email'] ?? null,
            'phone'     => $data['phone'] ?? null,
            'tags'      => $data['tags'] ?? [],
            'source'    => 'Site House Team', // <--- VOLTAMOS PARA O NOME CLARO
        ];

        $payload = array_filter($payload, fn($value) => !empty($value));

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/contacts/", $payload);

            if ($response->successful()) {
                $contactId = $response->json('contact.id');
                Log::info('GHL: Contato OK. ID: ' . $contactId);
                return $response->json('contact');
            }

            Log::error('GHL: Erro ao criar contato', ['body' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::error('GHL: Exceção Contato', ['msg' => $e->getMessage()]);
            return null;
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

    public function createOpportunity(string $contactId, array $data, string $type = 'lead')
    {
        if ($type === 'credit') {
            $pipelineId = config('services.ghl.pipelines.credit_id');
            $stageId = config('services.ghl.stages.credit_new_id');
            $titleSuffix = ' - Crédito Habitação';
        } else {
            $pipelineId = config('services.ghl.pipelines.leads_id');
            $stageId = config('services.ghl.stages.leads_new_id');
            $titleSuffix = ' - Lead Site';
        }

        if (!$pipelineId || !$stageId) return false;

        $payload = [
            'pipelineId' => $pipelineId,
            'stageId'    => $stageId,
            'contactId'  => $contactId,
            'title'      => ($data['name'] ?? 'Cliente') . $titleSuffix,
            'status'     => 'open',
            'source'     => 'Site House Team', // <--- AQUI TAMBÉM
        ];

        if (!empty($data['property_price'])) {
            $payload['monetaryValue'] = (float) $data['property_price'];
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/pipelines/{$pipelineId}/opportunities", $payload);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('GHL: Exceção Oportunidade', ['msg' => $e->getMessage()]);
            return false;
        }
    }

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