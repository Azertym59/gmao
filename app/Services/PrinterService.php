<?php

namespace App\Services;

use App\Models\Printer;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class PrinterService
{
    /**
     * Récupère l'imprimante à utiliser
     */
    public static function getPrinter()
    {
        // Vérifier s'il y a une imprimante en session (pour les tests)
        if (Session::has('test_printer')) {
            return (object) Session::get('test_printer');
        }
        
        // Sinon, récupérer l'imprimante par défaut
        return Printer::getDefault();
    }
    
    /**
     * Génère le JavaScript nécessaire pour imprimer sur l'imprimante spécifiée
     */
    public static function generatePrintScript($containerId = 'label-content')
    {
        $printer = self::getPrinter();
        
        if (!$printer) {
            return "
                alert('Aucune imprimante configurée. Veuillez configurer une imprimante dans les paramètres.');
                console.error('Aucune imprimante configurée');
            ";
        }
        
        $script = "
            function printLabel() {
                // Récupérer le contenu à imprimer
                const labelContent = document.getElementById('{$containerId}');
                
                if (!labelContent) {
                    console.error('Élément à imprimer non trouvé');
                    return;
                }
                
                // Créer une fenêtre d'impression
                const printWindow = window.open('', '_blank', 'width=600,height=600');
                
                if (!printWindow) {
                    alert('Veuillez autoriser les fenêtres pop-up pour cette page afin d\\'imprimer.');
                    return;
                }
                
                // Configuration de l'imprimante
                const printerConfig = {
                    name: '" . addslashes($printer->name) . "',
                    model: '" . addslashes($printer->model) . "',
                    labelWidth: " . floatval($printer->label_width) . ",
                    labelHeight: " . floatval($printer->label_height) . "
                };
                
                // Contenu HTML à imprimer
                const htmlContent = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset=\"utf-8\">
                        <title>Impression d'étiquette</title>
                        <style>
                            @page {
                                size: ${printerConfig.labelWidth}mm ${printerConfig.labelHeight}mm;
                                margin: 0;
                            }
                            body {
                                margin: 0;
                                padding: 0;
                                width: ${printerConfig.labelWidth}mm;
                                height: ${printerConfig.labelHeight}mm;
                                font-family: Arial, sans-serif;
                            }
                            .print-red { color: #ff0000; }
                            .text-black { color: #000000; }
                            /* Reproduire les styles essentiels */
                            ${getComputedStyleSheets(labelContent)}
                            
                            /* Optimisations pour Brother QL-820NWBc */
                            .qr-container {
                                border: 1px solid black !important;
                                background-color: white !important;
                            }
                            .qr-container svg {
                                max-width: 80px;
                            }
                        </style>
                    </head>
                    <body>
                        ${labelContent.outerHTML}
                        <script>
                            // Imprimer puis fermer la fenêtre automatiquement
                            window.onload = function() {
                                setTimeout(function() {
                                    window.print();
                                    setTimeout(function() {
                                        window.close();
                                    }, 500);
                                }, 300);
                            };
                        <\/script>
                    </body>
                    </html>
                `;
                
                // Fonction pour extraire les styles importants
                function getComputedStyleSheets(element) {
                    try {
                        // Simplement retourner une chaîne vide pour l'instant
                        // Dans un système plus avancé, nous pourrions extraire les styles calculés
                        return '';
                    } catch (e) {
                        console.error('Erreur lors de l\\'extraction des styles:', e);
                        return '';
                    }
                }
                
                // Écrire le contenu
                printWindow.document.open();
                printWindow.document.write(htmlContent);
                printWindow.document.close();
            }
        ";
        
        return $script;
    }
    
    /**
     * Génère le bouton d'impression
     */
    public static function printButton($label = 'Imprimer l\'étiquette', $classes = 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md shadow')
    {
        $printer = self::getPrinter();
        
        if (!$printer) {
            return '<a href="' . route('printers.index') . '" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-md shadow">
                <i class="fas fa-exclamation-triangle mr-2"></i> Configurer une imprimante
            </a>';
        }
        
        return '<button onclick="printLabel()" class="' . $classes . '">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            ' . $label . '
        </button>';
    }
}