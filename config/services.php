<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // --- INTEGRAÇÃO IDEALISTA ---
    'idealista' => [
        'key' => env('IDEALISTA_API_KEY'),
        'secret' => env('IDEALISTA_SECRET'),
        'base_url' => env('IDEALISTA_BASE_URL', 'https://api.idealista.com'),
        'feed_key' => env('IDEALISTA_FEED_KEY'),
    ],

    // --- INTEGRAÇÃO CRM HIGHLEVEL (GO HIGH LEVEL) ---
    'ghl' => [
        'api_key'     => env('GHL_API_KEY'),
        'location_id' => env('GHL_LOCATION_ID'), // Mantido caso precise no futuro
        
        // MAPA DE PIPELINES (Funis) - IDs FIXOS extraídos do Debug
        'pipelines' => [
            'sellers'     => 'YbkGFQfX4kxvsQaN6JTg', // 1 - Vendedores - Angariação
            'buyers'      => 'qDFhaKgEI0HW6Yj7gCtT', // 2 - Compradores
            'recruitment' => 'iAi2kqsBqTRtQpc5DHGP', // 7 - Recrutamento
            'credit'      => 'y5wz3084omNZ2WuhrjPn', // 3 - Créditos
        ],
        
        // MAPA DE STAGES (Etapa Inicial: "Contactar" ou "Entrar em Contato")
        'stages' => [
            'sellers_new'     => 'c5ea0034-f90f-46a2-ad76-8037f7602d2e', // Contactar (Vendedores)
            'buyers_new'      => 'f68fd383-affd-412c-96f2-881cfe1363f7', // Contactar (Compradores)
            'recruitment_new' => 'b148333a-bef6-495b-809e-e5f79ea05a91', // Contactar (Recrutamento)
            'credit_new'      => '8d9d7bf6-4874-45a6-93b4-a03962d1659d', // Entrar em Contato (Créditos)
        ],
    ],

];