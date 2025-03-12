<?php

return [
    // Token API pour le client d'impression
    'client_api_token' => env('PRINT_CLIENT_API_TOKEN', 'generate-a-secure-token-here'),
    
    // URL de base de l'API
    'api_base_url' => env('APP_URL') . '/api/print',
    
    // Intervalle de polling en secondes
    'polling_interval' => env('PRINT_POLLING_INTERVAL', 5),
];