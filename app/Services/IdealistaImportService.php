<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IdealistaImportService extends AbstractIdealistaService
{
    /**
     * Busca lista de imóveis.
     * Tenta buscar com filtros padrão se nada for passado.
     */
    public function getProperties($page = 1, $size = 50)
    {
        // Nota: Algumas APIs consideram page 1 como 1, outras como 0. 
        // O Idealista v1 geralmente é 1-based, mas verifique.
        
        $params = [
            'page' => $page,
            'size' => $size,
            // 'status' => 'all', // DICA: Se a API suportar, tente descomentar isso para ver inativos
        ];

        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/v1/properties", $params);

        if ($response->failed()) {
            // Log para debug rápido sem precisar parar o código
            \Illuminate\Support\Facades\Log::error("Erro Import Idealista [{$response->status()}]: " . $response->body());
            return [];
        }

        return $response->json();
    }

    public function getPropertyImages($propertyId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/v1/properties/{$propertyId}/images");

        if ($response->failed()) return [];
        $json = $response->json();
        return $json['images'] ?? $json ?? [];
    }
}