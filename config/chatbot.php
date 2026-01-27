<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Python Chatbot Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Python-based chatbot service integration
    |
    */

    'python' => [
        'url' => env('PYTHON_CHATBOT_URL', 'http://localhost:8001'),
        'api_key' => env('PYTHON_CHATBOT_API_KEY'),
        'timeout' => env('PYTHON_CHATBOT_TIMEOUT', 30),
        'enabled' => env('PYTHON_CHATBOT_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Configuration
    |--------------------------------------------------------------------------
    |
    | If Python service is unavailable, fallback to PHP implementation
    |
    */

    'fallback_enabled' => env('CHATBOT_FALLBACK_ENABLED', true),
];

