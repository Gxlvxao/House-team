<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Property;
use Illuminate\Support\Str;

class ImportWpProperties extends Command
{
    protected $signature = 'import:wp {url}';
    protected $description = 'Importa imóveis de um site WordPress via REST API';

    public function handle()
    {
        $url = $this->argument('url');
        // Garante que a URL não tem barra no final para evitar //wp-json
        $endpoint = rtrim($url, '/') . '/wp-json/wp/v2/posts?per_page=100';

        $this->info("Conectando em: $endpoint");

        try {
            $response = Http::get($endpoint);
        } catch (\Exception $e) {
            $this->error('Erro de conexão: ' . $e->getMessage());
            return;
        }

        if ($response->failed()) {
            $this->error('Falha ao conectar na API do WP. Status: ' . $response->status());
            return;
        }

        $posts = $response->json();

        if (empty($posts)) {
            $this->warn('Nenhum imóvel encontrado nessa URL.');
            return;
        }

        $bar = $this->output->createProgressBar(count($posts));

        foreach ($posts as $post) {
            $title = $post['title']['rendered'] ?? 'Sem Título';
            $slug = $post['slug'] ?? Str::slug($title);
            $content = strip_tags($post['content']['rendered'] ?? '');

            // CORREÇÃO:
            // 1. Usamos 'slug' no updateOrCreate pois 'external_id' não existe na sua tabela.
            // 2. 'venda' vai para o campo 'status', e definimos um 'type' padrão.
            
            Property::updateOrCreate(
                ['slug' => $slug], 
                [
                    'title'       => $title,
                    'description' => $content,
                    'price'       => 0, 
                    'status'      => 'Venda',        // Correção: Status é Venda/Arrendamento
                    'type'        => 'Apartamento',  // Valor padrão obrigatório (ajuste se o WP fornecer isso)
                    'is_visible'  => false           // Sugestão: Importar como oculto para você revisar depois
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Importação concluída com sucesso!');
    }
}