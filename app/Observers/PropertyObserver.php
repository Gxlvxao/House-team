<?php

namespace App\Observers;

use App\Models\Property;

class PropertyObserver
{
    /**
     * Handle the Property "created" event.
     */
    public function created(Property $property): void
    {
        // Ao criar, se estiver visível, envia para o Idealista (fila)
        if ($property->is_visible) {
            \App\Jobs\SendPropertyToIdealistaJob::dispatch($property->id);
        }
    }

    /**
     * Handle the Property "updated" event.
     */
    public function updated(Property $property): void
    {
        // Evita loop infinito: se o update foi só para salvar o idealista_id ou last_synced_at, ignoramos.
        if ($property->wasChanged(['idealista_id', 'idealista_url', 'last_synced_at'])) {
            return;
        }

        // Se mudou algo relevante e está visível
        if ($property->is_visible) {
            \App\Jobs\SendPropertyToIdealistaJob::dispatch($property->id);
        }
    }

    /**
     * Handle the Property "deleted" event.
     */
    public function deleted(Property $property): void
    {
        // Futuro: Implementar desativação no Idealista
    }

    /**
     * Handle the Property "restored" event.
     */
    public function restored(Property $property): void
    {
        //
    }

    /**
     * Handle the Property "force deleted" event.
     */
    public function forceDeleted(Property $property): void
    {
        //
    }
}
