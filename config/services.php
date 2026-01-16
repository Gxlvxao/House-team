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
        'feed_key' => env('IDEALISTA_FEED_KEY'), // Código do cliente para exportação
    ],

    // --- INTEGRAÇÃO CRM HIGHLEVEL (GO HIGH LEVEL) ---
    'ghl' => [
        'api_key'     => env('GHL_API_KEY'),
        'location_id' => env('GHL_LOCATION_ID'),
        'api_version' => env('GHL_API_VERSION', '2021-07-28'),
        
        'pipelines' => [
            'leads_id'   => env('GHL_PIPELINE_LEADS_ID'),   // Funil Geral
            'credit_id'  => env('GHL_PIPELINE_CREDIT_ID'),  // Funil Crédito
            'listing_id' => env('GHL_PIPELINE_LISTING_ID'), // Funil Angariação (Mais-Valias)
            'buyers_id'  => env('GHL_PIPELINE_BUYERS_ID'),  // Funil Compradores (IMT)
        ],
        
        'stages' => [
            'leads_new_id'   => env('GHL_STAGE_LEADS_NEW_ID'),
            'credit_new_id'  => env('GHL_STAGE_CREDIT_NEW_ID'),
            'listing_new_id' => env('GHL_STAGE_LISTING_NEW_ID'), // Etapa Angariação
            'buyers_new_id'  => env('GHL_STAGE_BUYERS_NEW_ID'),  // Etapa Compradores
        ],
    ],

];