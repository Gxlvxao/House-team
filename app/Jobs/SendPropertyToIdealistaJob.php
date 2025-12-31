<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Property;
use App\Services\IdealistaExportService;
use Illuminate\Support\Facades\Log;

class SendPropertyToIdealistaJob implements ShouldQueue
{
    use Queueable;

    protected $propertyId;

    public $tries = 3;
    public $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct($propertyId)
    {
        $this->propertyId = $propertyId;
    }

    /**
     * Execute the job.
     */
    public function handle(IdealistaExportService $service): void
    {
        $property = Property::with('images')->find($this->propertyId);

        if (!$property) {
            Log::error("Job Idealista: Imóvel ID {$this->propertyId} não encontrado.");
            return;
        }

        Log::info("Job Idealista: Processando imóvel #{$property->id} - Status: {$property->status}");

        try {
            // Verificamos se já existe um ID do Idealista salvo na base de dados
            $idealistaId = $property->idealista_id;

            if ($idealistaId) {
                // --- CENÁRIO 1: UPDATE (Já existe) ---
                Log::info("Imóvel já existe no Idealista (ID: {$idealistaId}). Iniciando atualização...");
                
                // Chama o novo método de update
                $service->updateProperty($property, $idealistaId);
                
                // Atualiza apenas o timestamp de sincronização
                $property->update(['last_synced_at' => now()]);
                
                Log::info("Imóvel #{$property->id} atualizado com sucesso no Idealista.");

            } else {
                // --- CENÁRIO 2: CREATE (Novo) ---
                Log::info("Imóvel novo (sem ID Idealista). Iniciando criação...");
                
                $result = $service->createProperty($property);
                
                // O Idealista pode retornar o ID em campos diferentes dependendo da versão/resposta
                $newId = $result['propertyCode'] ?? $result['propertyId'] ?? $result['code'] ?? null;

                if ($newId) {
                    $property->update([
                        'idealista_id' => $newId,
                        'idealista_url' => "https://www.idealista.pt/imovel/" . $newId,
                        'last_synced_at' => now(),
                    ]);
                    $idealistaId = $newId; // Define o ID para usar no upload de imagens logo abaixo
                    Log::info("Imóvel criado com sucesso! ID Idealista: {$newId}");
                } else {
                    Log::warning("Imóvel criado na API, mas ID não encontrado na resposta: " . json_encode($result));
                }
            }

            // --- 3. ENVIAR IMAGENS (Comum para Create e Update) ---
            if ($property->images->count() > 0 && $idealistaId) {
                try {
                    $service->uploadImages($idealistaId, $property);
                    Log::info("Imagens sincronizadas para imóvel #{$property->id}");
                } catch (\Exception $e) {
                    Log::warning("Aviso: Imóvel processado, mas falha no upload de imagens: " . $e->getMessage());
                    // Não lançamos erro (throw) aqui para não re-executar o job todo só por causa de uma imagem
                }
            }

        } catch (\Exception $e) {
            Log::error("Job Idealista: Falha crítica #{$property->id}: " . $e->getMessage());
            throw $e; // Faz o job tentar novamente (Retry)
        }
    }
}