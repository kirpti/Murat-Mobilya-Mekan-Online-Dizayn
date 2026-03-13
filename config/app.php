<?php

return [
    'name'            => env('APP_NAME', 'MEKÂN'),
    'env'             => env('APP_ENV', 'production'),
    'debug'           => (bool) env('APP_DEBUG', false),
    'url'             => env('APP_URL', 'http://localhost'),
    'timezone'        => 'Europe/Istanbul',
    'locale'          => 'tr',
    'fallback_locale' => 'en',
    'faker_locale'    => 'tr_TR',
    'cipher'          => 'AES-256-CBC',
    'key'             => env('APP_KEY'),
    'previous_keys'   => [],
    'maintenance'     => ['driver' => 'file'],
    'providers'       => [],
    'aliases'         => [],
];
