<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Property;
use App\Jobs\SendPropertyToIdealistaJob;
use App\Services\IdealistaExportService;

class SendPropertyToIdealistaJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_processes_property_and_images_successfully()
    {
        // 0. Impedir que o Observer dispare o job real durante o setup
        \Illuminate\Support\Facades\Queue::fake();

        // 1. Preparar o cenário (Banco de Dados)
        $property = \App\Models\Property::create([
            'title' => 'Casa Teste Unitário',
            'slug' => 'casa-teste-unitario',
            'type' => 'flat',
            'status' => 'sale',
            'is_visible' => true,
        ]);
        
        // Simular imagem
        $property->images()->create(['path' => 'properties/test.jpg']);

        // 2. Mock do Serviço usando Mockery
        $mockService = \Mockery::mock(IdealistaExportService::class);

        // Expectativa: createProperty deve ser chamado 1 vez
        $mockService->shouldReceive('createProperty')
            ->once()
            ->with(\Mockery::on(function ($prop) use ($property) {
                return $prop->id === $property->id;
            }))
            ->andReturn(['propertyCode' => '12345']);

        // Expectativa: uploadImages deve ser chamado 1 vez
        $mockService->shouldReceive('uploadImages')
            ->once()
            ->with('12345', \Mockery::on(function ($prop) use ($property) {
                return $prop->id === $property->id;
            }));

        // 3. Executar o Job Manualmente
        $job = new SendPropertyToIdealistaJob($property->id);
        $job->handle($mockService);

        // 4. Asserções no Banco (O job deve ter atualizado o ID)
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'idealista_id' => '12345',
            'idealista_url' => 'https://www.idealista.pt/imovel/12345',
        ]);
    }
}
