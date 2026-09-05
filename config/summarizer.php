<?php

return [
    /*
    |--------------------------------------------------------------------------
    | LLM Provider
    |--------------------------------------------------------------------------
    |
    | Supported: "openai", "fallback"
    | Default: "fallback"
    |
    | Fallback simply returns the original title instead of hitting an API.
    */
    'provider' => env('SUMMARIZER_PROVIDER', 'fallback'),

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
    ],
];
