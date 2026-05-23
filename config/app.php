<?php

use Illuminate\Support\Facades\Facade;

return [

    'name' => env('APP_NAME', 'Barangay San Jose IS'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    'timezone' => 'Asia/Manila',

    'locale' => 'en',

    'fallback_locale' => 'en',

    'faker_locale' => 'en_PH',

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => 'file',
    ],

];
