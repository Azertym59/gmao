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
 * 
 * @param int $printerId Identifiant de l'imprimante
 * @param string|null $content Contenu à imprimer
 * @param array $options Options d'impression
 * @return array Résultat de l'impression
 */
public static function brotherPrint($printerId, $content = null, $options = [])
{
    try {
        // Récupérer l'imprimante
        $printer = Printer::findOrFail($printerId);
        
        // Vérifier si on doit utiliser b-PAC SDK
        if ($printer->shouldUseBpac()) {
            return self::printWithBpac($printer, $content, $options);
        }
        
        // Récupérer le mode d'impression (par défaut: raster)
        $printMode = $printer->options['print_mode'] ?? $options['mode'] ?? 'raster';
        
        Log::info('Tentative d\'impression Brother pour ' . $printer->name . ' (' . $printer->ip_address . ') en mode ' . $printMode);
        
        // Pour les imprimantes Brother, nous allons utiliser une approche différente
        // Nous allons créer un fichier temporaire et l'envoyer via une commande système
        
        // Créer un fichier temporaire avec le contenu
        $tempfile = tempnam(sys_get_temp_dir(), 'bro_print_');
        
        // Déterminer le contenu à imprimer
        $printContent = $content ?: self::generateTestContent($printer);
        
        // Selon le mode d'impression, ajuster le contenu
        if ($printMode === 'template') {
            // Format P-touch Template - pas utilisé dans votre cas
            $header = ""; // En-tête P-touch Template
            $footer = ""; // Pied de page
            $printContent = $header . $printContent . $footer;
        } elseif ($printMode === 'escp') {
            // Format ESC/P - pas utilisé dans votre cas
            $initCmd = chr(27) . "@"; // ESC @ - initialiser l'imprimante
            $endCmd = chr(27) . "J" . chr(0); // ESC J 0 - Avance papier et coupe
            $printContent = $initCmd . $printContent . $endCmd;
        } else {
            // Mode Raster (utilisé dans votre cas)
            // Pour le mode raster, nous utilisons un format brut sans modification
            // Mais on peut ajouter un en-tête pour les imprimantes Brother qui le nécessitent
            $rasterHeader = chr(27) . chr(105) . chr(65) . chr(0); // ESC i A 0 - Mode raster
            
            // Vérifier si le contenu semble déjà être du HTML
            if (strpos($printContent, '<html') !== false || strpos($printContent, '<!DOCTYPE') !== false) {
                // Ne pas ajouter d'en-tête au HTML
            } else {
                // Pour un contenu binaire, on peut ajouter l'en-tête raster
                $printContent = $rasterHeader . $printContent;
            }
        }
        
        // Écrire le contenu dans le fichier temporaire
        file_put_contents($tempfile, $printContent);
        
        // Journaliser le contenu pour débogage (attention aux fichiers volumineux)
        Log::debug('Type de contenu à imprimer: ' . (is_string($printContent) ? 'Texte/HTML' : 'Binaire/Image'));
        Log::debug('Taille du contenu: ' . strlen($printContent) . ' octets');
        
        // Créer un script temporaire pour l'impression
        $scriptFile = tempnam(sys_get_temp_dir(), 'print_cmd_');
        chmod($scriptFile, 0755);
        
        // Commande d'impression selon le système d'exploitation et le mode d'impression
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows - utiliser la commande print avec des paramètres spécifiques selon le mode
            $modeParam = "";
            if ($printMode === 'template') {
                $modeParam = " -template";
            } elseif ($printMode === 'escp') {
                $modeParam = " -escp";
            } // Par défaut, raster ne nécessite pas de paramètre spécifique
            
            $command = 'print' . $modeParam . ' /D:"\\\\' . $printer->ip_address . '\\' . ($printer->share_name ?: 'Brother_QL_820NWB') . '" "' . $tempfile . '"';
            
            // Écrire la commande dans le script
            file_put_contents($scriptFile, "@echo off\r\n" . $command);
        } else {
            // Linux/Unix - utiliser des commandes CUPS/Brother
            $modeParam = "";
            if ($printMode === 'template') {
                $modeParam = "--template";
            } elseif ($printMode === 'escp') {
                $modeParam = "--escp";
            } else {
                $modeParam = "--raster"; // Mode raster explicite
            }
            
            // Tester différentes méthodes d'impression pour Brother
            $portParam = "";
            if (!empty($printer->port)) {
                $portParam = " --port=" . $printer->port;
            }
            
            // Créer un script shell
            $scriptContent = "#!/bin/bash\n";
            
            // Journaliser les informations pour le débogage
            $scriptContent .= "echo \"Mode d'impression: $printMode\"\n";
            $scriptContent .= "echo \"Fichier à imprimer: $tempfile\"\n";
            $scriptContent .= "echo \"Imprimante IP: " . $printer->ip_address . "\"\n\n";
            
            // Préparer une page de test simple
            $testPage = tempnam(sys_get_temp_dir(), 'test_');
            $scriptContent .= "# Créer une page de test simple pour l'imprimante Brother\n";
            $scriptContent .= "cat > $testPage << 'EOT'\n";
            $scriptContent .= "<!DOCTYPE html>\n";
            $scriptContent .= "<html><body style='font-family: sans-serif; margin: 0; padding: 0;'>\n";
            $scriptContent .= "<h1 style='font-size: 12pt; text-align: center;'>TEST BROTHER RASTER</h1>\n";
            $scriptContent .= "<p style='text-align: center;'>Date: " . date('Y-m-d H:i:s') . "</p>\n";
            $scriptContent .= "<p style='text-align: center;'>Imprimante: " . $printer->name . "</p>\n";
            $scriptContent .= "</body></html>\n";
            $scriptContent .= "EOT\n\n";
            
            // Essayer brother_ql si disponible (solution Python)
            $scriptContent .= "# Méthode 1: Utiliser brother_ql (outil Python)\n";
            $scriptContent .= "if command -v brother_ql > /dev/null 2>&1; then\n";
            $scriptContent .= "  echo \"Tentative d'impression avec brother_ql...\"\n";
            // Utiliser brother_ql pour imprimer en mode raster
            $labelSize = "62"; // 62mm est le rouleau DK-22205
            $scriptContent .= "  brother_ql --backend network --printer " . $printer->ip_address . ":" . ($printer->port ?: '9100') . " print --label " . $labelSize . " --rotate 0 \"$tempfile\"\n";
            $scriptContent .= "  BRO_QL_STATUS=$?\n";
            $scriptContent .= "  echo \"Résultat brother_ql: $BRO_QL_STATUS\"\n";
            $scriptContent .= "  if [ $BRO_QL_STATUS -eq 0 ]; then\n";
            $scriptContent .= "    echo \"Impression réussie avec brother_ql\"\n";
            $scriptContent .= "    exit 0\n";
            $scriptContent .= "  fi\n";
            $scriptContent .= "fi\n\n";
            
            // Essayer une connexion directe avec un fichier simplifié d'abord
            $scriptContent .= "# Méthode 2: Connexion socket directe (mode RAW)\n";
            $scriptContent .= "if command -v nc > /dev/null 2>&1; then\n";
            $scriptContent .= "  echo \"Tentative de connexion directe RAW via netcat...\"\n";
            
            // Pour le mode raster, on envoie juste le fichier tel quel (fichier test simple)
            $scriptContent .= "  # D'abord essayer avec un fichier test simplifié\n";
            $scriptContent .= "  cat \"$testPage\" | nc -w 5 " . $printer->ip_address . " " . ($printer->port ?: '9100') . "\n";
            $scriptContent .= "  NC_TEST_STATUS=$?\n";
            $scriptContent .= "  if [ $NC_TEST_STATUS -eq 0 ]; then\n";
            $scriptContent .= "    echo \"Page de test envoyée avec succès\"\n";
            $scriptContent .= "    # Si le test fonctionne, essayer le vrai contenu\n";
            $scriptContent .= "    sleep 1\n";
            $scriptContent .= "    echo \"Envoi du contenu principal...\"\n";
            $scriptContent .= "    cat \"$tempfile\" | nc -w 5 " . $printer->ip_address . " " . ($printer->port ?: '9100') . "\n";
            $scriptContent .= "    NC_MAIN_STATUS=$?\n";
            $scriptContent .= "    if [ $NC_MAIN_STATUS -eq 0 ]; then\n";
            $scriptContent .= "      echo \"Contenu principal envoyé avec succès\"\n";
            $scriptContent .= "      exit 0\n";
            $scriptContent .= "    else\n";
            $scriptContent .= "      echo \"Échec de l'envoi du contenu principal (code: $NC_MAIN_STATUS)\"\n";
            $scriptContent .= "    fi\n";
            $scriptContent .= "  else\n";
            $scriptContent .= "    echo \"Échec de l'envoi de la page test (code: $NC_TEST_STATUS)\"\n";
            $scriptContent .= "  fi\n";
            $scriptContent .= "fi\n\n";
            
            // Essayer la commande Brother si disponible
            $scriptContent .= "# Méthode 2: Utiliser brother_ql_print si disponible\n";
            $scriptContent .= "if command -v brother_ql_print > /dev/null 2>&1; then\n";
            $scriptContent .= "  echo \"Tentative d'impression avec brother_ql_print...\"\n";
            $scriptContent .= "  brother_ql_print --printer=" . $printer->ip_address . $portParam . " $modeParam \"$tempfile\"\n";
            $scriptContent .= "  BRO_STATUS=$?\n";
            $scriptContent .= "  echo \"Résultat brother_ql_print: $BRO_STATUS\"\n";
            $scriptContent .= "  if [ $BRO_STATUS -eq 0 ]; then\n";
            $scriptContent .= "    exit 0\n";
            $scriptContent .= "  fi\n";
            $scriptContent .= "fi\n\n";
            
            // Essayer lpr comme fallback
            $scriptContent .= "# Méthode 3: Utiliser lpr si disponible\n";
            $scriptContent .= "if command -v lpr > /dev/null 2>&1; then\n";
            $scriptContent .= "  echo \"Tentative d'impression avec lpr...\"\n";
            
            if ($printMode === 'template') {
                $modeOption = "-o Brother-PT=template";
            } elseif ($printMode === 'escp') {
                $modeOption = "-o Brother-PT=escp";
            } else {
                $modeOption = "-o Brother-PT=raster";
            }
            
            $scriptContent .= "  lpr -H " . $printer->ip_address . " -P " . ($printer->queue_name ?: 'Brother_QL_820NWB') . " $modeOption \"$tempfile\"\n";
            $scriptContent .= "  LPR_STATUS=$?\n";
            $scriptContent .= "  echo \"Résultat lpr: $LPR_STATUS\"\n";
            $scriptContent .= "  if [ $LPR_STATUS -eq 0 ]; then\n";
            $scriptContent .= "    exit 0\n";
            $scriptContent .= "  fi\n";
            $scriptContent .= "fi\n\n";
            
            // Essayer la commande CUPS lpd si disponible
            $scriptContent .= "# Méthode 4: Utiliser lpd/lpdl si disponible\n";
            $scriptContent .= "if command -v lpd > /dev/null 2>&1 || command -v lp > /dev/null 2>&1; then\n";
            $scriptContent .= "  echo \"Tentative d'impression avec lpd/lp...\"\n";
            if ($printMode === 'raster') {
                $scriptContent .= "  if command -v lp > /dev/null 2>&1; then\n";
                $scriptContent .= "    lp -d \"" . ($printer->queue_name ?: 'BROTHER_QL_SERIES') . "\" -o raw \"$tempfile\"\n";
                $scriptContent .= "  else\n";
                $scriptContent .= "    lpd \"" . $printer->ip_address . "\" \"" . ($printer->queue_name ?: 'BINARY_P1') . "\" \"$tempfile\"\n";
                $scriptContent .= "  fi\n";
            } else {
                $scriptContent .= "  if command -v lp > /dev/null 2>&1; then\n";
                $scriptContent .= "    lp -d \"" . ($printer->queue_name ?: 'BROTHER_QL_SERIES') . "\" \"$tempfile\"\n";
                $scriptContent .= "  else\n";
                $scriptContent .= "    lpd \"" . $printer->ip_address . "\" \"" . ($printer->queue_name ?: 'BINARY_P1') . "\" \"$tempfile\"\n";
                $scriptContent .= "  fi\n";
            }
            $scriptContent .= "  LP_STATUS=$?\n";
            $scriptContent .= "  echo \"Résultat lp/lpd: $LP_STATUS\"\n";
            $scriptContent .= "  if [ $LP_STATUS -eq 0 ]; then\n";
            $scriptContent .= "    exit 0\n";
            $scriptContent .= "  fi\n";
            $scriptContent .= "fi\n\n";

            // Essayer une connexion directe avec une séquence d'initialisation
            $scriptContent .= "# Méthode 5: Tentative de connexion socket directe avec séquence d'initialisation\n";
            $scriptContent .= "if command -v nc > /dev/null 2>&1; then\n";
            $scriptContent .= "  echo \"Tentative de connexion directe avec séquence d'initialisation...\"\n";
            $scriptContent .= "  (printf \"\\x1B\\x40\" && cat \"$tempfile\" && printf \"\\x1B\\x69\\x52\\x01\\x0C\") | nc -w 5 " . $printer->ip_address . " " . ($printer->port ?: '9100') . "\n";
            $scriptContent .= "  NC_INIT_STATUS=$?\n";
            $scriptContent .= "  echo \"Résultat nc avec init: $NC_INIT_STATUS\"\n";
            $scriptContent .= "  if [ $NC_INIT_STATUS -eq 0 ]; then\n";
            $scriptContent .= "    exit 0\n";
            $scriptContent .= "  fi\n";
            $scriptContent .= "fi\n\n";
            
            // Si tout échoue, essayer une commande curl directe
            $scriptContent .= "# Méthode 6: Utiliser curl si disponible\n";
            $scriptContent .= "if command -v curl > /dev/null 2>&1; then\n";
            $scriptContent .= "  echo \"Tentative d'impression avec curl...\"\n";
            $scriptContent .= "  curl -v -s -T \"$tempfile\" -H \"Content-Type: application/octet-stream\" http://" . $printer->ip_address . ":631/ipp/print\n";
            $scriptContent .= "  CURL_STATUS=$?\n";
            $scriptContent .= "  echo \"Résultat curl: $CURL_STATUS\"\n";
            $scriptContent .= "  if [ $CURL_STATUS -eq 0 ]; then\n";
            $scriptContent .= "    exit 0\n";
            $scriptContent .= "  fi\n";
            $scriptContent .= "fi\n\n";
            
            $scriptContent .= "echo \"ERREUR: Aucune méthode d'impression n'a réussi\"\n";
            $scriptContent .= "exit 1\n";
            
            file_put_contents($scriptFile, $scriptContent);
            $command = $scriptFile;
        
        // Journaliser la commande pour débogage
        Log::debug('Commande d\'impression: ' . $command);
        
        // Exécuter la commande
        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);
        
        // Nettoyer les fichiers temporaires
        @unlink($tempfile);
        @unlink($scriptFile);
        @unlink($testPage); // Supprimer aussi la page de test
        
        // Journaliser la sortie
        Log::debug('Résultat de la commande d\'impression:', [
            'output' => $output,
            'return_code' => $returnCode
        ]);
        
        // Vérifier le résultat
        if ($returnCode === 0) {
            Log::info('Impression Brother réussie en mode ' . $printMode);
            return [
                'success' => true,
                'message' => 'Impression envoyée avec succès (mode ' . $printMode . ')',
                'output' => implode("\n", $output)
            ];
        } else {
            Log::error('Échec de l\'impression Brother en mode ' . $printMode . ': ' . implode("\n", $output));
            return [
                'success' => false,
                'message' => 'Échec de l\'impression (mode ' . $printMode . '): ' . implode("\n", $output)
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
 * Impression avec Brother b-PAC SDK (pour Windows)
 * 
 * @param Printer $printer L'imprimante à utiliser
 * @param string|null $content Le contenu à imprimer (HTML ou texte)
 * @param array $options Options d'impression supplémentaires
 * @return array Résultat de l'opération
 */
private static function printWithBpac($printer, $content = null, $options = [])
{
    try {
        // Vérifier que nous sommes sur Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            Log::error('Impression b-PAC: Ce mode n\'est supporté que sous Windows');
            return [
                'success' => false,
                'message' => 'L\'impression avec Brother b-PAC n\'est supportée que sous Windows'
            ];
        }
        
        Log::info('Tentative d\'impression avec b-PAC SDK pour ' . $printer->name);
        
        // Créer un fichier temporaire avec le contenu à imprimer
        $tempfile = tempnam(sys_get_temp_dir(), 'bpac_print_');
        $tempfile .= '.html'; // Ajouter l'extension .html
        
        // Déterminer le contenu à imprimer
        $printContent = $content ?: self::generateTestContent($printer);
        
        // Sauvegarder le contenu HTML dans le fichier temporaire
        file_put_contents($tempfile, $printContent);
        
        // Créer un script PowerShell pour l'impression
        $scriptFile = tempnam(sys_get_temp_dir(), 'bpac_script_');
        $scriptFile .= '.ps1'; // Ajouter l'extension PowerShell
        
        // Récupérer les paramètres d'impression
        $printerName = $printer->name;
        $tapeType = $printer->options['bpac_tape_type'] ?? $printer->options['brother_roll'] ?? 'DK22205';
        $labelWidth = $printer->options['label_width'] ?? 62;
        $labelHeight = $printer->options['label_height'] ?? 100;
        
        // Générer le script PowerShell pour utiliser b-PAC SDK
        $psScript = <<<EOT
# Script PowerShell pour impression avec Brother b-PAC SDK
Write-Host "Démarrage de l'impression Brother avec b-PAC SDK..."

try {
    # Charger l'assemblage b-PAC
    Add-Type -Path "C:\Program Files (x86)\Brother bPAC SDK\Components\bpac.dll"
    
    # Créer un objet document
    \$doc = New-Object bpac.Document
    
    # Ouvrir le fichier temporaire HTML
    Write-Host "Ouverture du fichier HTML: $tempfile"
    \$content = Get-Content -Path "$tempfile" -Raw
    
    # Initialiser l'imprimante
    Write-Host "Initialisation de l'imprimante: $printerName"
    \$doc.SetPrinter("$printerName", \$true)
    
    # Configurer le type d'étiquette
    Write-Host "Configuration du type d'étiquette: $tapeType"
    \$doc.SetMediaById("$tapeType")
    
    # Paramétrer les dimensions (pour formats continus)
    \$doc.SetPrintAreaWidth($labelWidth)
    \$doc.SetPrintAreaLength($labelHeight)
    
    # Imprimer directement HTML
    Write-Host "Impression du contenu HTML..."
    \$doc.StartPrint("", 0)
    \$doc.PrintHTML(\$content)
    \$doc.EndPrint()
    
    Write-Host "Impression terminée avec succès"
    exit 0
}
catch {
    Write-Host "Erreur d'impression b-PAC: \$_"
    exit 1
}
finally {
    # Nettoyer les ressources
    if (\$doc -ne \$null) {
        \$doc.Close()
    }
}
EOT;
        
        // Écrire le script PowerShell dans le fichier temporaire
        file_put_contents($scriptFile, $psScript);
        
        // Exécuter le script PowerShell
        $command = "powershell -ExecutionPolicy Bypass -File \"$scriptFile\"";
        
        Log::debug('Commande PowerShell d\'impression b-PAC: ' . $command);
        
        // Exécuter la commande
        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);
        
        // Nettoyer les fichiers temporaires
        @unlink($tempfile);
        @unlink($scriptFile);
        
        // Journaliser la sortie
        Log::debug('Résultat de la commande d\'impression b-PAC:', [
            'output' => $output,
            'return_code' => $returnCode
        ]);
        
        // Vérifier le résultat
        if ($returnCode === 0) {
            Log::info('Impression Brother b-PAC réussie');
            return [
                'success' => true,
                'message' => 'Impression envoyée avec succès via b-PAC SDK',
                'output' => implode("\n", $output)
            ];
        } else {
            Log::error('Échec de l\'impression Brother b-PAC: ' . implode("\n", $output));
            return [
                'success' => false,
                'message' => 'Échec de l\'impression via b-PAC SDK: ' . implode("\n", $output)
            ];
        }
    } catch (\Exception $e) {
        Log::error('Erreur d\'impression Brother b-PAC: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erreur b-PAC: ' . $e->getMessage()
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