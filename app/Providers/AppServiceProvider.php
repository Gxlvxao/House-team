<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- Importante: Adicionei esta importação

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. FORÇAR HTTPS (Resolve o problema de "Não Seguro")
        // Isso garante que assets() e route() gerem links https://
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // 2. SEUS OBSERVERS (Mantidos)
        \App\Models\Property::observe(\App\Observers\PropertyObserver::class);
    }
}