<?php

return [
    // Token API pour le client d'impression
    'client_api_token' => env('PRINT_CLIENT_API_TOKEN', 'generate-a-secure-token-here'),
    
    // URL de base de l'API
    'api_base_url' => env('APP_URL') . '/api/print',
    
    // Intervalle de polling en secondes
    'polling_interval' => env('PRINT_POLLING_INTERVAL', 5),
    
    // PrintNode Configuration
    'printnode_api_key' => env('PRINTNODE_API_KEY', null),
    'printnode_printer_id' => env('PRINTNODE_PRINTER_ID', null),
    
    // Configuration pour Brother b-PAC SDK
    'bpac' => [
        'enabled' => env('BPAC_ENABLED', true),
        'dll_path' => env('BPAC_DLL_PATH', null),
        'template_path' => env('BPAC_TEMPLATE_PATH', storage_path('app/templates/brother')),
        // Correspondance entre modèles et ID de template
        'models' => [
            'QL-820NWB' => [
                'DK22205' => '62mm_continuous',
                'DK22210' => '29mm_continuous',
                'DK22214' => '12mm_continuous',
                'DK11201' => '29x90mm_address',
                'DK11202' => '62x100mm_shipping',
                'DK11208' => '38x90mm_label',
                'DK11209' => '62x29mm_address_small',
            ]
        ],
    ],
    
    // Paramètres pour QZ Tray
    'qz_tray' => [
        // Activer ou désactiver QZ Tray
        'enabled' => env('QZ_TRAY_ENABLED', true),
        
        // Utiliser des certificats pour QZ Tray en production
        'use_certificates' => env('QZ_TRAY_USE_CERTIFICATES', false),
        
        // Chemin vers le certificat (en production)
        'certificate_path' => env('QZ_TRAY_CERTIFICATE_PATH', null),
        
        // Délai de reconnexion en millisecondes
        'reconnect_delay' => env('QZ_TRAY_RECONNECT_DELAY', 1000),
        
        // Nombre de tentatives de reconnexion
        'reconnect_attempts' => env('QZ_TRAY_RECONNECT_ATTEMPTS', 3),
    ],
    
    // Paramètres par défaut pour les différents types d'imprimantes
    'printer_defaults' => [
        'thermal' => [
            'dpi' => 203,
            'width' => 57,
            'height' => 25,
            'orientation' => 'portrait',
        ],
        'label' => [
            'dpi' => 300,
            'width' => 62,
            'height' => 29,
            'orientation' => 'landscape',
        ],
        'standard' => [
            'dpi' => 300,
            'paper_size' => 'A4',
            'orientation' => 'portrait',
        ],
        'brother_label' => [
            'dpi' => 300,
            'width' => 62,
            'height' => 100,
            'orientation' => 'portrait',
            // Simplifier les options de PrintNode pour éviter les erreurs
            'media_type' => 'na_letter',
            'paper_size' => 'A4',
            'use_bpac' => true,
            'bpac_model' => 'QL-820NWB',
            'bpac_tape_type' => 'DK22205',
            'bpac_format' => 'raster'
        ]
    ],
    
    // Paramètres de file d'attente d'impression
    'queue' => [
        // Délai avant de considérer un job comme bloqué (en minutes)
        'stalled_threshold' => 10,
        
        // Nombre maximum de tentatives avant d'abandonner l'impression
        'max_attempts' => 3,
        
        // Activer le stockage des jobs d'impression complétés
        'store_completed' => true,
        
        // Durée de conservation des jobs complétés (en jours, 0 = infini)
        'retention_days' => 3,
    ],
];