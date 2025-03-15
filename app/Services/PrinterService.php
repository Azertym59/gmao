<?php

namespace App\Services;

use App\Models\Printer;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class PrinterService
{
    /**
     * Récupère l'imprimante à utiliser
     * 
     * @param int|null $printerId ID spécifique de l'imprimante ou null pour l'imprimante par défaut
     * @return Printer|null
     */
    public static function getPrinter($printerId = null)
    {
        // Si un ID est fourni, utiliser cette imprimante
        if ($printerId) {
            return Printer::find($printerId);
        }
        
        // Vérifier s'il y a une imprimante en session (pour les tests)
        if (Session::has('test_printer')) {
            $testPrinter = Session::get('test_printer');
            if (is_array($testPrinter)) {
                return (object) $testPrinter;
            }
            return $testPrinter;
        }
        
        // Sinon, récupérer l'imprimante par défaut
        return Printer::getDefault();
    }
    
    /**
     * Crée un job d'impression dans la file d'attente
     * 
     * @param int $printerId ID de l'imprimante
     * @param string $content Contenu à imprimer
     * @param string $contentType Type de contenu (html, image, qrcode)
     * @param array $options Options d'impression
     * @return PrintJob
     */
    public static function queuePrintJob($printerId, $content, $contentType = 'html', $options = [])
    {
        // Créer un token unique pour ce job
        $jobToken = uniqid('print_');
        
        // Créer l'entrée dans la base de données
        $printJob = PrintJob::create([
            'printer_id' => $printerId,
            'content' => $content,
            'content_type' => $contentType,
            'name' => $options['name'] ?? 'Print Job ' . date('Y-m-d H:i:s'),
            'status' => PrintJob::STATUS_PENDING,
            'options' => $options,
            'job_token' => $jobToken,
            'user_id' => auth()->id(),
            'attempts' => 0
        ]);
        
        // Journaliser la création du job
        \Log::info("Job d'impression créé", [
            'job_id' => $printJob->id,
            'printer_id' => $printerId,
            'content_type' => $contentType
        ]);
        
        return $printJob;
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
                size: \${printerConfig.labelWidth}mm \${printerConfig.labelHeight}mm;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                width: \${printerConfig.labelWidth}mm;
                height: \${printerConfig.labelHeight}mm;
                font-family: Arial, sans-serif;
            }
            .print-red { color: #ff0000; }
            .text-black { color: #000000; }
            /* Reproduire les styles essentiels */
            \${getComputedStyleSheets(labelContent)}
            
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
        \${labelContent.outerHTML}
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

    public static function testPrint($printerId)
{
    try {
        // Récupérer l'imprimante
        $printer = Printer::findOrFail($printerId);
        
        // Stocker l'imprimante en session pour le test
        Session::put('test_printer', $printer);
        
        // Journaliser le test
        Log::info('Test d\'impression pour l\'imprimante : ' . $printer->name);
        
        // Rediriger vers la vue de test
        return view('printers.test', ['printer' => $printer]);
    } catch (\Exception $e) {
        Log::error('Erreur lors du test d\'impression : ' . $e->getMessage());
        return back()->with('error', 'Erreur lors du test d\'impression : ' . $e->getMessage());
    }
}

public static function directPrint($printerId, $content = null)
{
    try {
        // Récupérer l'imprimante
        $printer = Printer::findOrFail($printerId);
        
        // Si aucun contenu n'est fourni, créer un contenu de test
        if (!$content) {
            $content = '<html><body>';
            $content .= '<h1 style="font-size: 14pt; text-align: center; font-weight: bold;">TEST IMPRESSION</h1>';
            $content .= '<p style="text-align: center;">Imprimante : ' . $printer->name . '</p>';
            $content .= '<p style="text-align: center;">Date : ' . date('d/m/Y H:i:s') . '</p>';
            
            // Générer un QR code si possible
            if (class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode')) {
                $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate('Test QR Code - ' . $printer->name);
                $content .= '<div style="text-align: center; margin-top: 10px; border: 1px solid black; display: inline-block; padding: 5px;">' . $qrCode . '</div>';
            }
            
            $content .= '</body></html>';
        }
        
        // Journaliser l'intention d'impression
        Log::info('Tentative d\'impression directe sur ' . $printer->name . ' (' . $printer->ip_address . ':' . $printer->port . ')');
        
        // Vérifier que l'imprimante a une adresse IP et un port
        if (empty($printer->ip_address) || empty($printer->port)) {
            Log::error('Configuration d\'imprimante incomplète : adresse IP ou port manquant');
            return [
                'success' => false, 
                'message' => 'Configuration d\'imprimante incomplète : adresse IP ou port manquant'
            ];
        }
        
        // Tentative de connexion à l'imprimante
        $socket = @fsockopen($printer->ip_address, $printer->port, $errno, $errstr, 10);
        
        if (!$socket) {
            Log::error('Impossible de se connecter à l\'imprimante : ' . $errstr);
            return [
                'success' => false, 
                'message' => 'Impossible de se connecter à l\'imprimante : ' . $errstr
            ];
        }
        
        // Préparer les en-têtes HTTP
        $headers = "POST /cgi-bin/epos/service.cgi?devid=" . ($printer->printer_id ?? '1') . "&timeout=10000 HTTP/1.1\r\n";
        $headers .= "Host: " . $printer->ip_address . "\r\n";
        $headers .= "Content-Type: text/html\r\n";
        $headers .= "Content-Length: " . strlen($content) . "\r\n";
        $headers .= "Connection: close\r\n\r\n";
        
        // Envoyer les données
        fwrite($socket, $headers . $content);
        
        // Lire la réponse
        $response = '';
        while (!feof($socket)) {
            $response .= fgets($socket, 128);
        }
        fclose($socket);
        
        // Journaliser la réponse
        Log::info('Réponse de l\'imprimante : ' . $response);
        
        return [
            'success' => true,
            'message' => 'Impression envoyée avec succès',
            'response' => $response
        ];
    } catch (\Exception $e) {
        Log::error('Erreur d\'impression directe : ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erreur : ' . $e->getMessage()
        ];
    }
}

/**
 * Impression spécifique pour imprimantes Brother
 */
public static function brotherPrint($printerId, $content = null)
{
    try {
        // Récupérer l'imprimante
        $printer = Printer::findOrFail($printerId);
        
        Log::info('Tentative d\'impression Brother pour ' . $printer->name . ' (' . $printer->ip_address . ')');
        
        // Pour les imprimantes Brother, nous allons utiliser une approche différente
        // Nous allons créer un fichier temporaire et l'envoyer via une commande système
        
        // Créer un fichier temporaire avec le contenu
        $tempfile = tempnam(sys_get_temp_dir(), 'bro_print_');
        file_put_contents($tempfile, $content ?: self::generateTestContent($printer));
        
        // Commande d'impression selon le système d'exploitation
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows - utiliser la commande print
            $command = 'print /D:"\\\\' . $printer->ip_address . '\\' . ($printer->share_name ?: 'Brother_QL_820NWB') . '" "' . $tempfile . '"';
        } else {
            // Linux/Unix - utiliser lpr
            $command = 'lpr -H ' . $printer->ip_address . ' -P ' . ($printer->queue_name ?: 'Brother_QL_820NWB') . ' "' . $tempfile . '"';
        }
        
        // Exécuter la commande
        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);
        
        // Supprimer le fichier temporaire
        @unlink($tempfile);
        
        // Vérifier le résultat
        if ($returnCode === 0) {
            Log::info('Impression Brother réussie');
            return [
                'success' => true,
                'message' => 'Impression envoyée avec succès',
                'output' => implode("\n", $output)
            ];
        } else {
            Log::error('Échec de l\'impression Brother: ' . implode("\n", $output));
            return [
                'success' => false,
                'message' => 'Échec de l\'impression: ' . implode("\n", $output)
            ];
        }
    } catch (\Exception $e) {
        Log::error('Erreur d\'impression Brother: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
}

/**
 * Génère un contenu de test pour l'impression
 */
private static function generateTestContent($printer)
{
    $content = '<html><body>';
    $content .= '<h1 style="font-size: 14pt; text-align: center; font-weight: bold;">TEST IMPRESSION</h1>';
    $content .= '<p style="text-align: center;">Imprimante : ' . $printer->name . '</p>';
    $content .= '<p style="text-align: center;">Date : ' . date('d/m/Y H:i:s') . '</p>';
    
    // Générer un QR code si possible
    if (class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode')) {
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate('Test QR Code - ' . $printer->name);
        $content .= '<div style="text-align: center; margin-top: 10px; border: 1px solid black; display: inline-block; padding: 5px;">' . $qrCode . '</div>';
    }
    
    $content .= '</body></html>';
    return $content;
}

/**
 * Vérifier si l'imprimante Brother est accessible
 */
public static function checkBrotherPrinter($printerId)
{
    try {
        $printer = Printer::findOrFail($printerId);
        
        // Tester la connexion ping
        $ping = exec("ping -c 1 -W 1 " . escapeshellarg($printer->ip_address), $pingOutput, $pingStatus);
        
        if ($pingStatus !== 0) {
            return [
                'success' => false,
                'message' => 'L\'imprimante ne répond pas au ping',
                'output' => implode("\n", $pingOutput)
            ];
        }
        
        // Tester le port standard des imprimantes Brother (9100 ou 515)
        $ports = [9100, 515];
        $connectedPort = null;
        
        foreach ($ports as $port) {
            $socket = @fsockopen($printer->ip_address, $port, $errno, $errstr, 2);
            if ($socket) {
                fclose($socket);
                $connectedPort = $port;
                break;
            }
        }
        
        if ($connectedPort) {
            return [
                'success' => true,
                'message' => 'L\'imprimante est accessible sur le port ' . $connectedPort,
                'port' => $connectedPort
            ];
        } else {
            return [
                'success' => false,
                'message' => 'L\'imprimante n\'est pas accessible sur les ports standards Brother'
            ];
        }
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
}

}