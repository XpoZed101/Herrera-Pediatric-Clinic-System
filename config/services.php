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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
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
            'channel' => env('SLACK_CHANNEL'),
        ],
    ],

    'paymongo' => [
        'public_key' => env('PAYMONGO_PUBLIC_API_KEY'),
        'secret_key' => env('PAYMONGO_SECRET_API_KEY'),
        // Default to official PayMongo API base for checkout sessions
        'checkout_base_url' => env('PAYMONGO_CHECKOUT_BASE', 'https://api.paymongo.com/v1/checkout_sessions'),
        // Restrict to methods requested: bank transfer and GCash
        'allowed_methods' => ['gcash', 'bank_transfer'],
    ],
];
