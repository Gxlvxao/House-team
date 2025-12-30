<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IdealistaQueueTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_creating_visible_property_dispatches_job()
    {
        \Illuminate\Support\Facades\Queue::fake();

        \App\Models\Property::create([
            'title' => 'Teste Idealista',
            'slug' => 'teste-idealista',
            'type' => 'flat',
            'status' => 'sale',
            'is_visible' => true,
        ]);

        \Illuminate\Support\Facades\Queue::assertPushed(\App\Jobs\SendPropertyToIdealistaJob::class);
    }

    public function test_creating_invisible_property_does_not_dispatch_job()
    {
        \Illuminate\Support\Facades\Queue::fake();

        \App\Models\Property::create([
            'title' => 'Teste Invisível',
            'slug' => 'teste-invisivel',
            'type' => 'flat',
            'status' => 'sale',
            'is_visible' => false,
        ]);

        \Illuminate\Support\Facades\Queue::assertNotPushed(\App\Jobs\SendPropertyToIdealistaJob::class);
    }

    public function test_updating_property_dispatches_job()
    {
        \Illuminate\Support\Facades\Queue::fake();

        $property = \App\Models\Property::create([
            'title' => 'Teste Update',
            'slug' => 'teste-update',
            'type' => 'flat',
            'status' => 'sale',
            'is_visible' => true, // Dispara job 1
        ]);

        // Reseta o fake para testar o update
        // (Na prática o Queue::fake() captura tudo desde o início, então verificamos se foi chamado 2 vezes ou usamos assertPushed com callback)
        
        $property->update(['price' => 200000]);

        \Illuminate\Support\Facades\Queue::assertPushed(\App\Jobs\SendPropertyToIdealistaJob::class, 2);
    }
}
