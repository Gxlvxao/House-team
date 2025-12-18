<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class AbstractIdealistaService
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

    protected function getToken()
    {
        if (Cache::has('idealista_token')) {
            return Cache::get('idealista_token');
        }

        $credentials = base64_encode("{$this->key}:{$this->secret}");

        try {
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
        } catch (\Exception $e) {
            Log::critical('Exceção na Auth Idealista: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getHeaders()
    {
        $token = $this->getToken();
        return [
            'Authorization' => "Bearer {$token}",
            'feedKey' => $this->feedKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json', // Boa prática adicionar Accept
        ];
    }
}