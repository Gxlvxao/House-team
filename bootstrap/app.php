<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\SetLocale; // <--- 1. Importámos o Middleware aqui

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 2. Registamos o SetLocale para correr em todas as rotas Web
        $middleware->web(append: [
            SetLocale::class,
        ]);

        // 3. Mantivemos a tua configuração de redirecionamento de login
        $middleware->redirectGuestsTo(fn (Request $request) => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();