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

        Log::info("Job Idealista: Iniciando exportação do imóvel #{$property->id}");

        try {
            // 1. Enviar Dados
            $result = $service->createProperty($property);
            $idealistaId = $result['propertyCode'] ?? $result['propertyId'] ?? null;

            if ($idealistaId) {
                $property->update([
                    'idealista_id' => $idealistaId,
                    'idealista_url' => "https://www.idealista.pt/imovel/" . $idealistaId,
                    'last_synced_at' => now(),
                ]);
                Log::info("Job Idealista: Imóvel #{$property->id} atualizado com ID Idealista {$idealistaId}");
            }

            // 2. Enviar Imagens
            if ($property->images->count() > 0 && $idealistaId) {
                try {
                    $service->uploadImages($idealistaId, $property);
                    Log::info("Job Idealista: Imagens do imóvel #{$property->id} enviadas.");
                } catch (\Exception $e) {
                    Log::warning("Job Idealista: Falha no upload de imagens #{$property->id}: " . $e->getMessage());
                    // Não falhamos o job todo só por causa das imagens, mas logamos
                }
            }

        } catch (\Exception $e) {
            Log::error("Job Idealista: Falha crítica #{$property->id}: " . $e->getMessage());
            throw $e; // Faz o job tentar novamente (Retry)
        }
    }
}
