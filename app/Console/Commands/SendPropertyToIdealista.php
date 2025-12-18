<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IdealistaExportService;
use App\Models\Property;

class SendPropertyToIdealista extends Command
{
    protected $signature = 'idealista:export {id : ID do imóvel no seu banco local}';
    protected $description = 'Envia um imóvel local para o Idealista (Criação + Imagens)';

    public function handle(IdealistaExportService $service)
    {
        $localId = $this->argument('id');
        $property = Property::with('images')->find($localId); // Carrega imagens junto

        if (!$property) {
            $this->error("Imóvel ID {$localId} não encontrado.");
            return;
        }

        $this->info("=== EXPORTANDO IMÓVEL ID {$localId} ===");
        
        try {
            // 1. Criar/Atualizar Dados do Imóvel
            $this->info("1. Enviando dados do imóvel...");
            // Se já tiver ID idealista, devíamos fazer update, mas no Sandbox vamos tentar criar de novo ou tratar erro
            // Para simplificar o teste, vamos assumir criação.
            $result = $service->createProperty($property);

            $idealistaId = $result['propertyCode'] ?? $result['propertyId'];
            $this->info("SUCESSO! ID Idealista: {$idealistaId}");

            // Atualiza banco local
            $property->idealista_id = $idealistaId;
            $property->idealista_url = "https://www.idealista.pt/imovel/" . $idealistaId;
            $property->save();

            // 2. Enviar Imagens
            $this->info("2. Enviando imagens...");
            if ($property->images->count() > 0) {
                try {
                    $imgResult = $service->uploadImages($idealistaId, $property);
                    $this->info("Imagens enviadas com sucesso!");
                } catch (\Exception $e) {
                    $this->warn("Aviso: Imóvel criado, mas erro nas imagens: " . $e->getMessage());
                    $this->line("Dica: Se estiver em localhost, o Idealista não consegue baixar as fotos.");
                }
            } else {
                $this->warn("Nenhuma imagem encontrada no banco local para este imóvel.");
            }

        } catch (\Exception $e) {
            $this->error("ERRO CRÍTICO: " . $e->getMessage());
        }
    }
}