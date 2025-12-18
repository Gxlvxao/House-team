<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IdealistaService;
use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TestIdealistaImage extends Command
{
    protected $signature = 'idealista:test-image';
    protected $description = 'Testa headers avançados para baixar imagens';

    public function handle(IdealistaService $service)
    {
        $property = Property::latest()->first();

        if (!$property) {
            $this->error('Nenhum imóvel encontrado.');
            return;
        }

        $images = $service->getPropertyImages($property->idealista_id);
        if (empty($images)) {
            $this->error("Sem imagens na API.");
            return;
        }

        $url = $images[0]['url'];
        $this->info("Testando URL: {$url}");

        // --- TENTATIVA 5: IMITAR NAVEGADOR (HEADERS COMPLETOS) ---
        $this->info("\n--- TENTATIVA 5: Headers de Navegador (Chrome) ---");
        
        try {
            $response = Http::withHeaders([
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept'          => 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
                'Accept-Language' => 'pt-PT,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                'Referer'         => 'https://www.idealista.pt/', // <--- O TRUQUE ESTÁ AQUI
                'Sec-Fetch-Dest'  => 'image',
                'Sec-Fetch-Mode'  => 'no-cors',
                'Sec-Fetch-Site'  => 'cross-site',
                'Pragma'          => 'no-cache',
                'Cache-Control'   => 'no-cache',
            ])->get($url);
            
            $size = strlen($response->body());
            $this->info("Status: " . $response->status());
            $this->info("Tamanho: {$size} bytes");

            if ($response->successful() && $size > 2000) {
                $this->info("RESULTADO: SUCESSO! Enganamos o firewall.");
                Storage::disk('public')->put('teste_browser.jpg', $response->body());
            } else {
                $this->warn("RESULTADO: FALHA. O Firewall é esperto.");
                // Tenta limpar a URL do /blur/ como último recurso nesse contexto
                $cleanUrl = str_replace('/blur/1500_80_mq', '', $url);
                if ($cleanUrl !== $url) {
                    $this->info("Tentando URL limpa com headers...");
                    $res2 = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'Referer'    => 'https://www.idealista.pt/'
                    ])->get($cleanUrl);
                    if ($res2->successful() && strlen($res2->body()) > 2000) {
                        $this->info("SUCESSO NA URL LIMPA!");
                        Storage::disk('public')->put('teste_browser_clean.jpg', $res2->body());
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}