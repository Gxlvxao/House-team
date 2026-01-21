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

        $query = DB::connection('wordpress')
            ->table('posts')
            ->where('post_type', 'property') // O post_type que descobrimos
            ->where('post_status', 'publish')
            ->orderBy('ID', 'desc');

        if ($this->option('limit')) {
            $query->limit($this->option('limit'));
        }

        $total = $query->count();
        $this->info("🏠 Encontrados {$total} imóveis ativos.");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $stats = ['imported' => 0, 'skipped' => 0, 'error' => 0];

        foreach ($query->cursor() as $post) {
            try {
                $result = $this->service->importProperty($post);
                
                if ($result === 'imported') $stats['imported']++;
                elseif ($result === 'skipped') $stats['skipped']++;
                
            } catch (\Exception $e) {
                $stats['error']++;
                // $this->error("Erro no ID {$post->ID}: " . $e->getMessage()); // Descomente para debug
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