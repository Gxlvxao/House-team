<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class IdealistaDeepDiagnostics extends Command
{
    protected $signature = 'idealista:deep-diag';
    protected $description = 'Executa diagnóstico profundo de conectividade e integridade da API Idealista (Raw cURL)';

    private $baseUrl;
    private $apiKey;
    private $secret;
    private $feedKey;
    private $accessToken;

    public function handle()
    {
        // 1. Carregar Configurações
        $this->baseUrl = config('services.idealista.base_url') ?? env('IDEALISTA_BASE_URL');
        $this->apiKey = config('services.idealista.key') ?? env('IDEALISTA_API_KEY');
        $this->secret = config('services.idealista.secret') ?? env('IDEALISTA_SECRET');
        $this->feedKey = config('services.idealista.feed_key') ?? env('IDEALISTA_FEED_KEY');

        $this->printBanner();

        // 2. Teste de Autenticação (OAuth)
        if (!$this->stepAuth()) {
            return 1;
        }

        // 3. Executar Bateria de Testes
        $this->info("\n🚀 INICIANDO BATERIA DE TESTES DE INTEGRIDADE...\n");

        $results = [];

        // TESTE A: Request inválido intencional (Sem Headers Obrigatórios)
        // Objetivo: Provar que o servidor morre (500) antes mesmo de validar a requisição (esperado 400/401)
        $results['A'] = $this->runCurlTest(
            'TEST A: Sanity Check (Missing Headers)',
            'GET',
            '/v1/contacts?numPage=1',
            [], // Sem FeedKey
            null,
            [400, 401] // Status esperados num servidor saudável
        );

        // TESTE B: Leitura Simples (GET)
        // Objetivo: Verificar se a leitura de dados está corrompida
        $results['B'] = $this->runCurlTest(
            'TEST B: Read Resource (GET Contacts)',
            'GET',
            '/v1/contacts?numPage=1&maxItems=1',
            ['feedKey: ' . $this->feedKey],
            null,
            [200]
        );

        // TESTE C: Escrita Simples (POST)
        // Objetivo: Verificar se a escrita está corrompida
        $results['C'] = $this->runCurlTest(
            'TEST C: Write Resource (POST Contact)',
            'POST',
            '/v1/contacts',
            ['feedKey: ' . $this->feedKey],
            json_encode([
                'name' => 'Diag Test ' . time(),
                'primaryPhoneNumber' => '910000000'
            ]),
            [201]
        );

        $this->printSummary($results);
    }

    private function stepAuth()
    {
        $this->line("1️⃣  Etapa: Autenticação (OAuth2)");
        
        $ch = curl_init($this->baseUrl . '/oauth/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'client_credentials',
            'scope' => 'write'
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode("{$this->apiKey}:{$this->secret}"),
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignora SSL local para focar na resposta do servidor

        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code === 200) {
            $data = json_decode($response, true);
            $this->accessToken = $data['access_token'] ?? null;
            $this->info("   ✅ Token Gerado com Sucesso.");
            return true;
        }

        $this->error("   ❌ Falha na Autenticação. HTTP: $code");
        $this->line("   Resp: $response");
        return false;
    }

    private function runCurlTest($title, $method, $endpoint, $customHeaders, $body, $expectedCodes)
    {
        $this->warn(">>> $title");
        
        $url = $this->baseUrl . $endpoint;
        $headers = array_merge([
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json',
            'Accept: application/json'
        ], $customHeaders);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true); // Retorna headers
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $rawResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        
        $responseHeaders = substr($rawResponse, 0, $headerSize);
        $responseBody = substr($rawResponse, $headerSize);
        
        curl_close($ch);

        // Extração do CF-RAY (ID de Rastreio Cloudflare)
        preg_match('/cf-ray:\s*(.*)/i', $responseHeaders, $matches);
        $cfRay = trim($matches[1] ?? 'N/A');

        $isSuccess = in_array($httpCode, $expectedCodes);
        $statusTag = $isSuccess ? 'PASS' : 'FAIL';
        $statusColor = $isSuccess ? 'info' : 'error';

        $this->line("   URL: $url");
        $this->$statusColor("   HTTP Status: $httpCode (Esperado: " . implode('/', $expectedCodes) . ")");
        $this->line("   CF-RAY ID: $cfRay"); // Crucial para o suporte
        
        if ($httpCode >= 500) {
            $this->error("   CRITICAL SERVER ERROR DETECTED");
            $this->line("   Response Body Length: " . strlen($responseBody) . " bytes");
            $this->line("   Server Header: " . $this->getHeaderValue($responseHeaders, 'server'));
        } elseif (!$isSuccess) {
            $this->comment("   Response: " . substr($responseBody, 0, 100) . "...");
        }

        return [
            'test' => $title,
            'code' => $httpCode,
            'cf_ray' => $cfRay,
            'pass' => $isSuccess
        ];
    }

    private function getHeaderValue($headers, $key) {
        preg_match('/' . $key . ':\s*(.*)/i', $headers, $matches);
        return trim($matches[1] ?? 'Unknown');
    }

    private function printBanner() {
        $this->info("========================================");
        $this->info("   DIAGNÓSTICO TÉCNICO IDEALISTA API    ");
        $this->info("   Data: " . date('Y-m-d H:i:s T'));
        $this->info("   PHP: " . phpversion());
        $this->info("   User Key: " . $this->apiKey);
        $this->info("========================================\n");
    }

    private function printSummary($results) {
        $this->newLine();
        $this->info("----------- RESUMO EXECUTIVO -----------");
        $headers = ['Test Name', 'HTTP Code', 'Cloudflare ID', 'Result'];
        $data = [];
        foreach($results as $r) {
            $data[] = [
                $r['test'], 
                $r['code'], 
                $r['cf_ray'], 
                $r['pass'] ? '✅ PASS' : '❌ FAIL (INFRA ERROR)'
            ];
        }
        $this->table($headers, $data);
        
        $failed500 = collect($results)->where('code', '>=', 500)->count();
        if ($failed500 > 0) {
            $this->error("\nCONCLUSÃO: O ambiente Sandbox está instável.");
            $this->error("Foram detectados $failed500 erros 500 (Internal Server Error).");
            $this->line("Por favor, envie este output para o suporte técnico.");
        }
    }
}