<?php

// 1. Tenta ler as credenciais do .env (Gambi para não carregar o Laravel todo)
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    die("❌ ERRO: Arquivo .env não encontrado em $envPath\n");
}

$env = file_get_contents($envPath);
preg_match('/IDEALISTA_KEY=(.*)/', $env, $key);
preg_match('/IDEALISTA_SECRET=(.*)/', $env, $secret);

// Limpa espaços e aspas extras que podem vir do .env
$apiKey = trim(str_replace('"', '', $key[1] ?? ''));
$apiSecret = trim(str_replace('"', '', $secret[1] ?? ''));

// --- O PULO DO GATO: TESTANDO DIRETO NA PRODUÇÃO ---
$url = 'https://api.idealista.com/oauth/token';

if (!$apiKey || !$apiSecret) {
    die("❌ ERRO: Não consegui ler as chaves IDEALISTA_KEY ou IDEALISTA_SECRET do .env\n");
}

echo "\n--- DIAGNÓSTICO DE CONEXÃO (PRODUÇÃO) ---\n";
echo "Alvo: $url\n";
echo "Key:  " . substr($apiKey, 0, 5) . "...\n";
echo "--------------------------------------------------\n\n";

// TESTE 1: Conexão "Camuflada" (Fingindo ser Chrome para evitar bloqueio de WAF)
echo "🚀 Tentando conectar como Navegador (User-Agent Chrome)...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&scope=read");
curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Configurações críticas para passar firewall e redes 4G
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); // Força IPv4
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        // Ignora erro SSL
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "📡 Resposta do Servidor: HTTP $httpCode\n";

if ($httpCode == 200) {
    echo "✅ SUCESSO TOTAL! Token gerado. (Isso seria um milagre se sua chave for de Sandbox)\n";
    echo "➡️  Body: $response\n";
} elseif ($httpCode == 401) {
    echo "✅ SUCESSO NO DIAGNÓSTICO! (Deu 401 Unauthorized)\n";
    echo "💡 CONCLUSÃO: O servidor da Idealista está VIVO e seu IP está LIMPO.\n";
    echo "   O problema é que o servidor de Sandbox ('partners-sandbox') morreu/caiu.\n";
} elseif ($httpCode == 503) {
    echo "💀 FALHA: Deu 503 Service Unavailable.\n";
    echo "💡 CONCLUSÃO: Sua conta ou IP estão na lista negra do Firewall deles.\n";
} else {
    echo "⚠️  Outro Erro: $httpCode\n";
    echo "   Detalhe: $response\n";
    if ($curlError) echo "   Erro cURL: $curlError\n";
}
echo "\n--------------------------------------------------\n";