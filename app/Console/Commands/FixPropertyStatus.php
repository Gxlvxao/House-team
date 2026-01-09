<?php

namespace App\Console\Commands;

use App\Models\Property;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FixPropertyStatus extends Command
{
    // Usa o mesmo arquivo padrão do seu importador original
    protected $signature = 'properties:fix-status {file=Properties-Export-2026-January-07-2241_only-publish_UPDATED-agents-names.csv}';
    protected $description = 'Corrige o status (Venda/Arrendamento) dos imóveis importados lendo o CSV novamente.';

    public function handle()
    {
        $fileName = $this->argument('file');
        $path = storage_path('app/' . $fileName);

        if (!file_exists($path)) {
            $this->error("❌ Arquivo CSV não encontrado: {$path}");
            return 1;
        }

        $this->info("🔄 Iniciando correção de status...");

        $file = fopen($path, 'r');
        $headers = fgetcsv($file);
        
        // Remove caracteres invisíveis (BOM) que as vezes vêm no CSV
        $headers = array_map(function($header) {
            return trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header));
        }, $headers);

        $headerMap = array_flip($headers);

        // Função auxiliar para pegar valor do CSV com segurança
        $getVal = function($key) use ($headerMap, &$row) {
            return isset($headerMap[$key]) ? ($row[$headerMap[$key]] ?? null) : null;
        };

        // CORREÇÃO AQUI: Adicionado 'Property Statuses' na verificação
        $statusKey = null;
        if (isset($headerMap['Property Statuses'])) {
            $statusKey = 'Property Statuses';
        } elseif (isset($headerMap['Property Status'])) {
            $statusKey = 'Property Status';
        } elseif (isset($headerMap['REAL_HOMES_property_status'])) {
            $statusKey = 'REAL_HOMES_property_status';
        }

        if (!$statusKey) {
            $this->error("❌ Coluna de Status não encontrada.");
            $this->info("Headers limpos: " . implode(', ', $headers));
            return 1;
        }

        $this->info("✅ Coluna de status identificada: {$statusKey}");

        $count = 0;
        $updated = 0;

        while (($row = fgetcsv($file)) !== false) {
            $reference = $getVal('REAL_HOMES_property_id');
            $csvStatus = trim($getVal($statusKey));

            if (empty($reference)) continue;

            // Busca o imóvel pelo código CRM
            $property = Property::where('crm_code', $reference)->first();

            if ($property) {
                // Lógica de Tradução
                $newStatus = 'Venda'; // Default seguro

                // Verifica termos comuns em inglês ou português
                $lowerStatus = Str::lower($csvStatus);
                
                if (Str::contains($lowerStatus, ['rent', 'arrendamento', 'aluguer', 'rental'])) {
                    $newStatus = 'Arrendamento';
                } elseif (Str::contains($lowerStatus, ['sale', 'venda', 'comprar', 'sold'])) {
                    $newStatus = 'Venda';
                }

                // Só atualiza se for diferente para poupar banco
                if ($property->status !== $newStatus) {
                    $property->update(['status' => $newStatus]);
                    $this->line("✅ {$reference}: '{$property->status}' -> '{$newStatus}' (CSV: {$csvStatus})");
                    $updated++;
                }
            }
            $count++;
        }

        fclose($file);
        $this->info("🚀 Processo concluído!");
        $this->info("📊 Total processado: {$count}");
        $this->info("✨ Total corrigido: {$updated}");

        return 0;
    }
}