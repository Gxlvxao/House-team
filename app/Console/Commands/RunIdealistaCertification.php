<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\IdealistaExportService;

class RunIdealistaCertification extends Command
{
    protected $signature = 'idealista:certification';
    protected $description = 'Executa bateria de testes para certificação do Idealista';

    protected $service;
    protected $headers;
    protected $baseUrl;
    
    // Estado para guardar IDs criados durante o teste
    protected $lastContactId = null;
    protected $lastPropertyCode = null;

    public function handle(IdealistaExportService $service)
    {
        $this->service = $service;
        // Reutiliza a lógica de Auth do service
        $this->headers = $service->getHeaders('write'); 
        $this->baseUrl = config('services.idealista.base_url'); 

        $this->info("Iniciando bateria de testes Idealista...");
        
        // --- GRUPO: CONTACTOS ---
        $this->runContactTests();

        // --- GRUPO: IMÓVEIS (PROPERTIES) ---
        $this->runPropertyTests();

        // --- GRUPO: IMAGENS ---
        $this->runImageTests();

        $this->info("Testes finalizados. Verifique os logs no terminal para preencher a planilha.");
    }

    private function runContactTests()
    {
        $this->warn('--- TESTES DE CONTACTO ---');

        // Contact01: New contact (Sucesso)
        $payload = [
            'name' => 'Teste Certificacao',
            'email' => 'teste.cert@houseteam.pt',
            'primaryPhoneNumber' => '910000000'
        ];
        
        $this->executeTest('Contact01', 'POST', '/v1/contacts', $payload, 201, function($resp) {
            $this->lastContactId = $resp['contactId'] ?? $resp['code'] ?? null;
            $this->info("   -> Contact Created ID: " . $this->lastContactId);
        });

        // Contact02: Error - email missing
        $payloadNoEmail = $payload;
        unset($payloadNoEmail['email']);
        $this->executeTest('Contact02', 'POST', '/v1/contacts', $payloadNoEmail, 400);

        // Contact03: Error - email format
        $payloadBadEmail = $payload;
        $payloadBadEmail['email'] = 'test@test';
        $this->executeTest('Contact03', 'POST', '/v1/contacts', $payloadBadEmail, 400);

        // Contact05: Find contact (Usa o ID criado no Contact01)
        if ($this->lastContactId) {
            $this->executeTest('Contact05', 'GET', "/v1/contacts/{$this->lastContactId}", [], 200);
        } else {
            $this->error("Pulei Contact05 pois Contact01 falhou.");
        }
    }

    private function runPropertyTests()
    {
        $this->warn('--- TESTES DE PROPRIEDADE ---');

        // DADOS CORRIGIDOS
        $baseFeature = [
            'rooms' => 2, 
            'bathroomNumber' => 1, 
            'areaConstructed' => 80, 
            'conservation' => 'good', 
            'energyCertificateRating' => 'A',
            'liftAvailable' => false // Obrigatório para apartamentos (flat)
        ];

        $baseAddress = [
            'visibility' => 'hidden', 
            'precision' => 'exact', 
            'country' => 'Portugal', // Obrigatório ser o nome completo, não 'PT'
            'streetName' => 'Rua de Teste', 
            'streetNumber' => '10', 
            'postalCode' => '1000-001', 
            'town' => 'Lisboa'
        ];

        // Property01: Auth error (Token inválido)
        $badAuthHeaders = $this->headers;
        $badAuthHeaders['Authorization'] = 'Bearer invalid_token_123';
        $this->executeTest('Property01', 'POST', '/v1/properties', [], 401, null, $badAuthHeaders);

        // Property03: New property Operation sale (Sucesso)
        $ref = 'CERT-' . time();
        $payload = [
            'type' => 'flat',
            'reference' => $ref,
            'address' => $baseAddress,
            'operation' => ['type' => 'sale', 'price' => 150000],
            'features' => $baseFeature,
            'descriptions' => [['language' => 'pt', 'text' => 'Descricao de teste certificacao idealista']],
            'contactId' => (int) $this->lastContactId
        ];
        
        // Proteção caso os testes de contato tenham falhado
        if (!$payload['contactId']) {
            $this->warn('Aviso: ContactId não encontrado (é null). O teste Property03 provavelmente falhará.');
            // Tenta usar um ID dummy apenas para o payload não ir vazio, se necessário
            $payload['contactId'] = 12345; 
        }

        $this->executeTest('Property03', 'POST', '/v1/properties', $payload, 201, function($resp) {
            // Tenta capturar propertyId ou code
            $this->lastPropertyCode = $resp['propertyId'] ?? $resp['code'] ?? null;
            $this->info("   -> Property Created ID: " . $this->lastPropertyCode);
        });

        // --- NOVO TESTE DE UPDATE (Property13) ---
        if ($this->lastPropertyCode) {
            $this->line("Preparando teste de Update (Property13)...");
            
            // Clonamos o payload de criação e mudamos o preço
            $updatePayload = $payload;
            $updatePayload['operation']['price'] = 160000; // Aumentou 10k
            
            // O endpoint de update é PUT /v1/properties/{id}
            $this->executeTest('Property13', 'PUT', "/v1/properties/{$this->lastPropertyCode}", $updatePayload, 200);
        } else {
            $this->error("Pulei Property13 pois não temos ID do imóvel criado.");
        }

        // Flat02: Error - area < 10
        // Clona o payload válido e força o erro apenas na área
        $badPayload = $payload;
        $badPayload['reference'] .= '-ERR1';
        $badPayload['features']['areaConstructed'] = 5; 
        $this->executeTest('Flat02', 'POST', '/v1/properties', $badPayload, 400);

        // Flat04: Error - conservation/bathroomNumber mismatch
        // Clona o payload válido e força o erro de consistência
        $badPayload2 = $payload;
        $badPayload2['reference'] .= '-ERR2';
        $badPayload2['features']['bathroomNumber'] = 0;
        $badPayload2['features']['conservation'] = 'good';
        $this->executeTest('Flat04', 'POST', '/v1/properties', $badPayload2, 400);

        // Property10: Find Property
        if ($this->lastPropertyCode) {
             $this->executeTest('Property10', 'GET', "/v1/properties/{$this->lastPropertyCode}", [], 200);
        } else {
            $this->error('Pulei Property10 pois Property03 falhou ou não retornou ID.');
        }
    }

    private function runImageTests()
    {
        $this->warn('--- TESTES DE IMAGEM ---');
        
        if (!$this->lastPropertyCode) {
            $this->error("Pulei testes de imagem pois não criamos propriedade no passo anterior.");
            return;
        }

        // Image01: New images (2 images)
        $payload = [
            'images' => [
                ['url' => 'https://www.idealista.com/static/common/icons/logo-idealista-200.png', 'label' => 'facade'],
                ['url' => 'https://www.idealista.com/static/common/icons/logo-idealista-200.png', 'label' => 'unknown']
            ]
        ];
        
        // O endpoint espera 202 (Accepted) - Mesmo em localhost, ele deve aceitar o pedido
        $this->executeTest('Image01', 'PUT', "/v1/properties/{$this->lastPropertyCode}/images", $payload, 202);
    }

    /**
     * Helper genérico para execução e log
     */
    private function executeTest($testId, $method, $uri, $data, $expectedStatus, $callback = null, $customHeaders = null)
    {
        $headers = $customHeaders ?? $this->headers;
        $url = "{$this->baseUrl}{$uri}";

        $this->line("Executando: $testId ($method $uri)...");
        
        try {
            if ($method === 'GET') {
                $response = Http::withHeaders($headers)->get($url);
            } else {
                $response = Http::withHeaders($headers)->$method($url, $data);
            }

            $status = $response->status();
            $body = $response->json();

            // Log formatado
            if ($status == $expectedStatus) {
                $this->info("✅ $testId: PASSOU ($status).");
            } else {
                $this->error("❌ $testId: FALHOU. Esperado $expectedStatus, recebeu $status.");
            }
            
            // Output para a planilha
            $this->line("   -> Output JSON: " . json_encode($body)); 
            $this->newLine();

            if ($callback && $response->successful()) {
                $callback($body);
            }

        } catch (\Exception $e) {
            $this->error("❌ $testId: EXCEPTION - " . $e->getMessage());
        }
    }
}