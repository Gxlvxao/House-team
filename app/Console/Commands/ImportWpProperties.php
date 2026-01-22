<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WpMigrationService;
use Illuminate\Support\Facades\DB;

class ImportWpProperties extends Command
{
    protected $signature = 'import:wp-properties {--limit= : Quantidade para teste}';
    protected $description = 'Importa imóveis do Real Homes WP Legacy';

    protected $service;

    public function __construct(WpMigrationService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle()
    {
        $this->info("🔌 Conectando ao banco legado...");

        try {
            DB::connection('wordpress')->getPdo();
        } catch (\Exception $e) {
            $this->error("Erro de conexão: " . $e->getMessage());
            $this->warn("Verifique o config/database.php e se o banco 'house_legacy_wp' existe.");
            return 1;
        }

        // 1. Diagnóstico: Conta quantos existem no total vs quantos estão publicados
        $totalGeral = DB::connection('wordpress')
            ->table('posts')
            ->where('post_type', 'property')
            ->count();
            
        $totalPublicados = DB::connection('wordpress')
            ->table('posts')
            ->where('post_type', 'property')
            ->where('post_status', 'publish')
            ->count();

        $this->info("📊 Diagnóstico do Banco Legado:");
        $this->line("   - Total de Imóveis (Tudo): $totalGeral");
        $this->line("   - Apenas Publicados: $totalPublicados");
        
        // 2. Pergunta qual estratégia seguir
        $importarTudo = $this->confirm("Deseja importar TODOS os $totalGeral imóveis (incluindo rascunhos, vendidos e lixeira)?", true);

        $query = DB::connection('wordpress')
            ->table('posts')
            ->where('post_type', 'property');

        // Se NÃO quiser tudo, mantém o filtro de 'publish'
        if (!$importarTudo) {
            $query->where('post_status', 'publish');
        }
        // Se quiser tudo, removemos o filtro de status e ele trará tudo

        $query->orderBy('ID', 'desc');

        if ($this->option('limit')) {
            $query->limit($this->option('limit'));
        }

        $totalParaImportar = $query->count();
        $this->info("🚀 Iniciando importação de {$totalParaImportar} imóveis...");

        $bar = $this->output->createProgressBar($totalParaImportar);
        $bar->start();

        $stats = ['imported' => 0, 'skipped' => 0, 'error' => 0];

        foreach ($query->cursor() as $post) {
            try {
                $result = $this->service->importProperty($post);
                
                if ($result === 'imported') $stats['imported']++;
                elseif ($result === 'skipped') $stats['skipped']++;
                
            } catch (\Exception $e) {
                $stats['error']++;
                // Descomente abaixo para ver erros específicos no terminal
                // $this->error("Erro no ID {$post->ID}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->table(
            ['Status', 'Quantidade'],
            [
                ['Importados', $stats['imported']],
                ['Pulados (Já existiam)', $stats['skipped']],
                ['Erros', $stats['error']],
            ]
        );
    }
}