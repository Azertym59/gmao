<?php

return [
    // Paramètres pour Brother QL 820 NWbc
    'brother' => [
        // Activer ou désactiver l'impression Brother
        'enabled' => env('BROTHER_ENABLED', true),
        
        // Type d'imprimante
        'printer_type' => 'QL-820NWB',
        
        // Paramètres par défaut pour les rouleaux continus
        'default_settings' => [
            'width' => 62,           // Largeur en mm (DK-22205) - en nombre
            'height' => 100,         // Hauteur en mm - en nombre 
            'margin' => '0mm',       // Aucune marge
            'dpi' => 300,            // Résolution d'impression (en nombre)
            'rotate' => 0,           // Rotation (en integer)
            'cut' => true,           // Couper automatiquement (boolean)
            'roll' => 'dk22205',     // Type de rouleau spécifique
            'paper' => 'Custom.62x100mm', // Format personnalisé
            'printer-model' => 'QL-820NWB', // Modèle d'imprimante Brother
            'media' => 'dc-22205',   // Type de média spécifique au rouleau
            'bin' => 'default',      // Bac par défaut
            'density' => 'normal',   // Densité d'impression normale
            'fit-to-page' => true    // Ajuster à la page
        ],
        
        // Types d'étiquettes disponibles
        'media_types' => [
            'DK22205' => [
                'name' => 'Ruban continu 62mm',
                'width' => 62,
                'continuous' => true,
                'paper' => 'dk-22205',
                'rollType' => 'continuous',
                'media' => 'continuous',
                'printer-model' => 'QL-820NWB',
            ],
            'DK22210' => [
                'name' => 'Ruban continu 29mm',
                'width' => 29,
                'continuous' => true,
                'paper' => 'dk-22210',
                'rollType' => 'continuous',
                'media' => 'continuous',
                'printer-model' => 'QL-820NWB',
            ],
            'DK22214' => [
                'name' => 'Ruban continu 12mm',
                'width' => 12,
                'continuous' => true,
                'paper' => 'dk-22214',
                'rollType' => 'continuous',
                'media' => 'continuous',
                'printer-model' => 'QL-820NWB',
            ],
            'DK11201' => [
                'name' => 'Étiquettes adresse standard 29x90mm',
                'width' => 29,
                'height' => 90,
                'continuous' => false,
                'paper' => 'dk-11201',
                'media' => 'die-cut',
                'printer-model' => 'QL-820NWB',
            ],
            'DK11202' => [
                'name' => 'Étiquettes expédition 62x100mm',
                'width' => 62,
                'height' => 100,
                'continuous' => false,
                'paper' => 'dk-11202',
                'media' => 'die-cut',
                'printer-model' => 'QL-820NWB',
            ],
        ],
    ],
    
    // Paramètres pour DomPDF
    'dompdf' => [
        'options' => [
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'defaultMediaType' => 'screen',
            'dpi' => 300,
        ],
    ],
    
    // Types d'étiquettes prédéfinis
    'label_templates' => [
        'module' => [
            'width' => 62,
            'height' => 29,
            'template' => 'etiquettes.module',
        ],
        'dalle' => [
            'width' => 62,
            'height' => 50,
            'template' => 'etiquettes.dalle',
        ],
        'chantier' => [
            'width' => 62,
            'height' => 100,
            'template' => 'etiquettes.chantier',
        ],
    ],
    
    // Paramètres de file d'attente d'impression
    'queue' => [
        // Nombre maximum de tentatives avant d'abandonner l'impression
        'max_attempts' => 3,
        
        // Activer le stockage des jobs d'impression complétés
        'store_completed' => true,
        
        // Durée de conservation des jobs complétés (en jours, 0 = infini)
        'retention_days' => 3,
    ],
];