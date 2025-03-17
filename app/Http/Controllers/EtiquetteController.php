<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Dalle;
use App\Models\Module;
use App\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EtiquetteController extends Controller
{
    /**
     * Afficher la page d'impression d'étiquette pour un chantier
     *
     * @param int $id ID du chantier
     * @return \Illuminate\View\View
     */
    public function chantierEtiquette($id)
    {
        $chantier = Chantier::with(['client', 'produits', 'produits.dalles', 'produits.dalles.modules'])->findOrFail($id);
        
        // Générer le QR code directement dans la vue
        $qrValue = route('chantiers.show', $chantier->id);
        $qrCode = base64_encode(QrCode::format('png')->size(300)->errorCorrection('H')->margin(1)->generate($qrValue));
        $qrBase64 = 'data:image/png;base64,' . $qrCode;
        
        // Nous allons utiliser le QR code base64 directement sans sauvegarder de fichier
        // pour éviter les problèmes de permissions
        $qrCodeUrl = $qrBase64;
        
        return view('etiquettes.chantier', [
            'chantier' => $chantier,
            'qrCode' => $qrBase64,
            'qrCodeUrl' => $qrCodeUrl
        ]);
    }
    
    /**
     * Imprimer directement sur une imprimante Brother via PrintNode
     *
     * @param int $id ID du chantier
     * @return \Illuminate\Http\Response
     */
    public function chantierPTouchTemplate($id)
    {
        $chantier = Chantier::with(['client', 'produits', 'produits.dalles', 'produits.dalles.modules'])->findOrFail($id);
        
        // Générer le QR code pour le modèle
        $qrValue = route('chantiers.show', $chantier->id);
        $qrData = QrCode::format('png')->size(300)->errorCorrection('H')->margin(1)->generate($qrValue);
        
        // Convertir l'image en base64 pour l'envoyer à PrintNode
        $qrBase64 = base64_encode($qrData);
        
        try {
            // Obtenir l'imprimante par défaut ou spécifiée
            $printer = Printer::where('is_default', true)->first();
            $printerName = $printer ? $printer->name : 'Brother QL-820NWB';
            
            // Vérifier si on utilise le mode d'impression spécifié dans les options de l'imprimante
            $printMode = $printer->options['print_mode'] ?? 'raster';
            Log::info('Mode d\'impression configuré pour la Brother: ' . $printMode, [
                'printer_name' => $printerName,
                'printer_id' => $printer->id ?? 'default'
            ]);
            
            // Utilisation directe de brother_ql si disponible
            if (file_exists('/usr/local/bin/brother_ql') || file_exists('/usr/bin/brother_ql')) {
                \Log::info('Tentative d\'utilisation directe de brother_ql');
                
                // Générer l'image à imprimer
                $tempImageFile = tempnam(sys_get_temp_dir(), 'brother_ql_') . '.png';
                
                // Créer une image pour l'étiquette
                $width = 696;   // 62mm à 300 DPI
                $height = 1181; // 100mm à 300 DPI
                
                $image = imagecreatetruecolor($width, $height);
                $white = imagecolorallocate($image, 255, 255, 255);
                $black = imagecolorallocate($image, 0, 0, 0);
                $red = imagecolorallocate($image, 255, 0, 0);
                
                // Remplir l'arrière-plan en blanc
                imagefill($image, 0, 0, $white);
                
                // Dessiner un cadre
                imagerectangle($image, 0, 0, $width-1, $height-1, $black);
                
                // Ajouter le titre
                imagestring($image, 5, 20, 20, "TECALED GMAO", $black);
                imagestring($image, 5, 20, 60, "Chantier: " . $chantier->reference, $red);
                imagestring($image, 4, 20, 100, "Client: " . $chantier->client->societe, $black);
                
                // Ajouter le QR code
                $qrImage = imagecreatefromstring(base64_decode($qrBase64));
                if ($qrImage) {
                    $qrWidth = imagesx($qrImage);
                    $qrHeight = imagesy($qrImage);
                    
                    imagecopyresampled(
                        $image, $qrImage,
                        ($width - $qrWidth) / 2, 180,
                        0, 0,
                        $qrWidth, $qrHeight,
                        $qrWidth, $qrHeight
                    );
                    
                    imagedestroy($qrImage);
                }
                
                // Ajouter un texte en bas
                imagestring($image, 3, 20, $height - 40, "Scannez pour details - " . date('d/m/Y'), $black);
                
                // Sauvegarder l'image
                imagepng($image, $tempImageFile);
                imagedestroy($image);
                
                // Utilisation de la méthode directe pour Brother QL-800 en mode raster
                // Conformément à la documentation Brother: P-touch Template Command Reference

                // Créer un fichier de commandes raster
                $rasterCommandFile = tempnam(sys_get_temp_dir(), 'brother_cmd_');
                
                // En-tête de commande raster (initialisation) pour QL-800 selon la documentation
                // ESC @ - Initialize
                // ESC i a - Switch to raster mode (Mode Selection) - 1 = raster mode
                // ESC i M - Various mode settings - 0 = default settings
                // ESC i R - Raster transfer mode setting - 0 = default
                // ESC i K - Advanced raster command setting - 0 = default
                // ESC i Q - Page margins (LTRB) - chaque valeur = 0 pour utiliser les marges par défaut
                $rasterInit = chr(27) . '@' 
                           . chr(27) . 'i' . 'a' . chr(1)  // Raster Mode
                           . chr(27) . 'i' . 'M' . chr(0)  // Default Settings
                           . chr(27) . 'i' . 'R' . chr(0)  // Raster Transfer Mode
                           . chr(27) . 'i' . 'K' . chr(8)  // Advanced Raster Command (8 = adjusts the print density)
                           . chr(27) . 'i' . 'Q' . chr(0) . chr(0) . chr(0) . chr(0); // No margins
                file_put_contents($rasterCommandFile, $rasterInit, FILE_BINARY);
                
                // Préparation d'une image binaire spécifique au format Brother
                // Chargement de l'image originale
                $originalImage = imagecreatefrompng($tempImageFile);
                
                // Récupérer les dimensions
                $width = imagesx($originalImage);
                $height = imagesy($originalImage);
                
                // Calculer la taille en octets de chaque ligne raster (chaque bit représente un pixel)
                // Pour une largeur de 696 pixels, cela donne 696/8 = 87 octets par ligne
                $bytesPerLine = ceil($width / 8);
                
                // Créer un buffer pour stocker les données raster
                $rasterData = "";
                
                // Commande pour spécifier les informations de raster
                // ESC i z - Specify the raster data information
                // 1st parameter: 0x01 (raster image, 1bpp black/white)
                // 2-3: Width in bytes (little-endian)
                // 4-5: Height in pixels (little-endian)
                $rasterData .= chr(27) . 'i' . 'z' . chr(1) . chr($bytesPerLine & 0xFF) . chr(($bytesPerLine >> 8) & 0xFF) . chr($height & 0xFF) . chr(($height >> 8) & 0xFF);
                
                // Convertir l'image en données raster binaires (noir et blanc)
                for ($y = 0; $y < $height; $y++) {
                    $lineBits = '';
                    for ($x = 0; $x < $width; $x += 8) {
                        $byte = 0;
                        for ($bit = 0; $bit < 8; $bit++) {
                            if ($x + $bit < $width) {
                                $color = imagecolorat($originalImage, $x + $bit, $y);
                                $r = ($color >> 16) & 0xFF;
                                $g = ($color >> 8) & 0xFF;
                                $b = $color & 0xFF;
                                
                                // Convertir en noir et blanc (seuil à 127)
                                $gray = ($r + $g + $b) / 3;
                                if ($gray < 127) {
                                    // Pixel noir (1)
                                    $byte |= (1 << (7 - $bit));
                                }
                                // Pixel blanc (0) - déjà 0 par défaut
                            }
                        }
                        $lineBits .= chr($byte);
                    }
                    $rasterData .= $lineBits;
                }
                
                // Libérer la mémoire
                imagedestroy($originalImage);
                
                // Ajouter les données raster au fichier de commande
                file_put_contents($rasterCommandFile, $rasterData, FILE_APPEND | FILE_BINARY);
                
                // Commande de coupe (si nécessaire)
                $cutCommand = chr(27) . 'i' . 'C' . chr(1);
                file_put_contents($rasterCommandFile, $cutCommand, FILE_APPEND | FILE_BINARY);
                
                // Créer un script pour l'envoi des données avec différentes méthodes
                $scriptFile = tempnam(sys_get_temp_dir(), 'brother_script_');
                $scriptContent = "#!/bin/bash\n\n";
                
                // Informations de débogage
                $scriptContent .= "echo \"Tentative d'impression QL-800 en mode Raster\"\n";
                $scriptContent .= "echo \"Taille du fichier: $(wc -c \"$rasterCommandFile\" | awk '{print $1}') octets\"\n\n";
                
                // Méthode 1: Connexion directe via socket
                $scriptContent .= "echo \"Méthode 1: Envoi direct via socket TCP...\"\n";
                $scriptContent .= "cat \"$rasterCommandFile\" | nc -w 10 " . $printer->ip_address . " " . ($printer->port ?: '9100') . " 2>&1\n";
                $scriptContent .= "NC_STATUS=$?\n";
                $scriptContent .= "if [ $NC_STATUS -eq 0 ]; then\n";
                $scriptContent .= "  echo \"Envoi socket TCP réussi\"\n";
                $scriptContent .= "  exit 0\n";
                $scriptContent .= "else\n";
                $scriptContent .= "  echo \"Échec de l'envoi via socket TCP\"\n";
                $scriptContent .= "fi\n\n";
                
                // Méthode 2: Utiliser lpr avec options raw
                $scriptContent .= "echo \"Méthode 2: Utilisation de lpr...\"\n";
                $scriptContent .= "if command -v lpr > /dev/null 2>&1; then\n";
                $scriptContent .= "  lpr -l -P " . ($printer->queue_name ?: 'BROTHER_QL_SERIES') . " -o raw \"$rasterCommandFile\"\n";
                $scriptContent .= "  LPR_STATUS=$?\n";
                $scriptContent .= "  if [ $LPR_STATUS -eq 0 ]; then\n";
                $scriptContent .= "    echo \"Envoi lpr réussi\"\n";
                $scriptContent .= "    exit 0\n";
                $scriptContent .= "  else\n";
                $scriptContent .= "    echo \"Échec de l'envoi via lpr\"\n";
                $scriptContent .= "  fi\n";
                $scriptContent .= "else\n";
                $scriptContent .= "  echo \"Commande lpr non disponible\"\n";
                $scriptContent .= "fi\n\n";
                
                // Méthode 3: Utiliser curl pour envoyer à l'API REST de l'imprimante
                $scriptContent .= "echo \"Méthode 3: Tentative via API HTTP...\"\n";
                $scriptContent .= "if command -v curl > /dev/null 2>&1; then\n";
                $scriptContent .= "  curl -v --data-binary @\"$rasterCommandFile\" -H \"Content-Type: application/octet-stream\" http://" . $printer->ip_address . "/print\n";
                $scriptContent .= "  CURL_STATUS=$?\n";
                $scriptContent .= "  if [ $CURL_STATUS -eq 0 ]; then\n";
                $scriptContent .= "    echo \"Envoi HTTP réussi\"\n";
                $scriptContent .= "    exit 0\n";
                $scriptContent .= "  else\n";
                $scriptContent .= "    echo \"Échec de l'envoi via HTTP\"\n";
                $scriptContent .= "  fi\n";
                $scriptContent .= "else\n";
                $scriptContent .= "  echo \"Commande curl non disponible\"\n";
                $scriptContent .= "fi\n\n";
                
                // Finalisation du script
                $scriptContent .= "echo \"Toutes les méthodes ont échoué\"\n";
                $scriptContent .= "exit 1\n";
                
                // Écrire et rendre exécutable le script
                file_put_contents($scriptFile, $scriptContent);
                chmod($scriptFile, 0755);
                
                // Commande d'exécution du script
                $command = "$scriptFile";
                \Log::info('Tentative directe avec données raster spécifiques en mode binaire');
                
                // En alternative, essayer aussi brother_ql si disponible
                $brother_ql_command = "brother_ql --backend network --printer " . $printer->ip_address . ":" . ($printer->port ?: '9100') . " print --label 62 --rotate 0 \"$tempImageFile\" 2>&1";
                \Log::info('Commande brother_ql alternative: ' . $brother_ql_command);
                
                \Log::info('Exécution de la commande directe raster', [
                    'command' => $command
                ]);
                
                // Exécuter la commande directe
                exec($command, $output, $returnCode);
                
                // Si la commande directe échoue, essayer brother_ql
                if ($returnCode !== 0 && (file_exists('/usr/local/bin/brother_ql') || file_exists('/usr/bin/brother_ql'))) {
                    \Log::info('Tentative avec brother_ql après échec direct');
                    exec($brother_ql_command, $output_brother_ql, $returnCode_brother_ql);
                    
                    if ($returnCode_brother_ql === 0) {
                        $output = $output_brother_ql;
                        $returnCode = 0; // Considérer comme réussi
                    }
                }
                
                // Nettoyage
                @unlink($rasterCommandFile);
                @unlink($scriptFile);
                @unlink($tempImageFile);
                
                \Log::info('Résultat de brother_ql', [
                    'output' => $output,
                    'return_code' => $returnCode
                ]);
                
                if ($returnCode === 0) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Étiquette envoyée à l\'impression avec succès (brother_ql)',
                        'printer' => $printerName
                    ]);
                }
            }
            
            // Si brother_ql n'est pas disponible ou a échoué, on continue avec PrintNode
            Log::info('Utilisation de PrintNode comme fallback');
            
            // Configuration de PrintNode
            $printNodeApiKey = config('printing.printnode_api_key', env('PRINTNODE_API_KEY'));
            
            if (!$printNodeApiKey) {
                throw new \Exception("Clé API PrintNode non configurée. Veuillez ajouter PRINTNODE_API_KEY dans votre fichier .env");
            }
            
            // ID de l'imprimante PrintNode (à configurer dans l'interface de PrintNode)
            $printNodePrinterId = $printer->printnode_id ?? config('printing.printnode_printer_id', env('PRINTNODE_PRINTER_ID'));
            
            if (!$printNodePrinterId) {
                throw new \Exception("ID de l'imprimante PrintNode non configuré. Veuillez configurer l'imprimante dans PrintNode et ajouter son ID.");
            }
            
            // Préparer l'URL de l'API PrintNode
            $apiUrl = "https://api.printnode.com/printjobs";
            
            // Construction d'un contenu PDF pour l'étiquette Brother DK-22205 (62mm x longueur continue)
            // Utilisation du HTML existant, converti en PDF compatible avec l'impression d'étiquettes
            
            // Créer un PDF réel avec DomPDF
            $html = view('etiquettes.brother_print', [
                'chantier' => $chantier,
                'qrCode' => 'data:image/png;base64,' . $qrBase64
            ])->render();
            
            // Utilisons la méthode "raw_base64" pour envoyer une image simple
            try {
                // Générer une image PNG simple
                $width = 696;   // 62mm à 300 DPI (62 * 11.81 pixels par mm à 300 DPI)
                $height = 1181; // 100mm à 300 DPI (100 * 11.81)
                
                $image = imagecreatetruecolor($width, $height);
                $white = imagecolorallocate($image, 255, 255, 255);
                $black = imagecolorallocate($image, 0, 0, 0);
                $red = imagecolorallocate($image, 255, 0, 0);
                
                // Remplir l'arrière-plan en blanc
                imagefill($image, 0, 0, $white);
                
                // Dessiner un cadre noir fin
                imagerectangle($image, 0, 0, $width-1, $height-1, $black);
                
                // Ajouter les informations du chantier
                $reference = isset($chantier->reference) ? $chantier->reference : 'GMAO-' . str_pad($chantier->id, 3, '0', STR_PAD_LEFT);
                $clientName = isset($chantier->client) && isset($chantier->client->societe) ? $chantier->client->societe : 'Client';
                
                // Ajouter du texte
                imagestring($image, 5, 20, 20, "TECALED GMAO", $black);
                imagestring($image, 5, 20, 60, "Chantier: ".$reference, $red);
                imagestring($image, 4, 20, 100, "Client: ".$clientName, $black);
                
                // Convertir la chaine base64 du QR code en image
                $qrImage = imagecreatefromstring(base64_decode($qrBase64));
                if ($qrImage) {
                    // Obtenir les dimensions de l'image QR
                    $qrWidth = imagesx($qrImage);
                    $qrHeight = imagesy($qrImage);
                    
                    // Copier l'image QR sur l'étiquette (centré horizontalement, aligné en haut)
                    imagecopyresampled(
                        $image, $qrImage,
                        ($width - $qrWidth) / 2, 180,  // Destination x, y (centré)
                        0, 0,                          // Source x, y (coin supérieur gauche)
                        $qrWidth, $qrHeight,           // Destination width, height
                        $qrWidth, $qrHeight            // Source width, height
                    );
                    
                    // Libérer la mémoire
                    imagedestroy($qrImage);
                }
                
                // Ajouter un texte en bas
                imagestring($image, 3, 20, $height - 40, "Scannez pour details - " . date('d/m/Y'), $black);
                
                // Sauvegarder l'image dans un buffer
                ob_start();
                imagepng($image);
                $imageData = ob_get_clean();
                
                // Libérer la mémoire
                imagedestroy($image);
                
                // Sauvegarder l'image dans un fichier temporaire pour débogage
                $tempImageFile = tempnam(sys_get_temp_dir(), 'debug_img_') . '.png';
                file_put_contents($tempImageFile, $imageData);
                \Log::info('Image temporaire générée pour débogage:', ['file' => $tempImageFile]);
                
                // Convertir l'image en "raw" (base64) - en utilisant "raw_base64" comme format
                $rawBase64 = base64_encode($imageData);
                $contentType = "raw_base64";
                $pdfBase64 = $rawBase64;
                
            } catch (\Exception $e) {
                // En cas d'erreur, utiliser un PDF minimal
                \Log::error('Erreur lors de la génération de l\'image:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // PDF minimal valide
                $pdfContent = "%PDF-1.4\n1 0 obj\n<</Type /Catalog /Pages 2 0 R>>\nendobj\n";
                $pdfContent .= "2 0 obj\n<</Type /Pages /Kids [3 0 R] /Count 1>>\nendobj\n";
                $pdfContent .= "3 0 obj\n<</Type /Page /Parent 2 0 R /Resources 4 0 R /MediaBox [0 0 234.8 377.9] /Contents 5 0 R>>\nendobj\n";
                $pdfContent .= "4 0 obj\n<</Font <</F1 <<>>>>>>\nendobj\n";
                $pdfContent .= "5 0 obj\n<</Length 48>>\nstream\nBT /F1 12 Tf 10 360 Td (Etiquette chantier) Tj ET\nendstream\nendobj\n";
                $pdfContent .= "xref\n0 6\n0000000000 65535 f\n0000000010 00000 n\n0000000056 00000 n\n0000000111 00000 n\n";
                $pdfContent .= "0000000212 00000 n\n0000000254 00000 n\ntrailer\n<</Size 6 /Root 1 0 R>>\nstartxref\n352\n%%EOF";
                $pdfBase64 = base64_encode($pdfContent);
                $contentType = "pdf_base64";
                
                // Écrire le PDF dans un fichier temporaire pour débogage
                $tempPdfFile = tempnam(sys_get_temp_dir(), 'debug_pdf_') . '.pdf';
                file_put_contents($tempPdfFile, $pdfContent);
                \Log::info('PDF de fallback généré pour débogage:', ['file' => $tempPdfFile]);
            }
            
            // Préparer les données pour PrintNode
            // Créer un job d'impression avec des options encore plus minimales
            $data = [
                "printerId" => (int) $printNodePrinterId,
                "title" => "Étiquette Chantier #" . $chantier->id,
                "contentType" => $contentType, // "raw_base64" ou "pdf_base64"
                "content" => $pdfBase64,
                "source" => "GMAO TecaLED",
                // Options minimales pour les imprimantes Brother
                "options" => [
                    "paper" => "A4", // Format standard A4
                    "bin" => null,   // Pas de bac spécifique
                    "media" => null  // Pas de type de média spécifique
                ]
            ];
            
            // Effectuer la requête à l'API PrintNode
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($printNodeApiKey . ':')
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            
            // Exécuter la requête
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            // Journaliser la réponse
            \Log::info('Réponse PrintNode', [
                'response' => $response,
                'http_code' => $httpCode,
                'chantier_id' => $id
            ]);
            
            // Traiter la réponse
            if ($httpCode >= 200 && $httpCode < 300) {
                $printJobId = json_decode($response);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Étiquette envoyée à l\'impression avec succès',
                    'printer' => $printerName,
                    'print_job_id' => $printJobId
                ]);
            } else {
                // En cas d'erreur
                throw new \Exception("Erreur API PrintNode: " . $response);
            }
            
        } catch (\Exception $e) {
            \Log::error('Exception lors de l\'impression PrintNode', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'chantier_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'impression: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Afficher la page d'impression d'étiquette pour une dalle
     *
     * @param int $id ID de la dalle
     * @return \Illuminate\View\View
     */
    public function dalleEtiquette($id)
    {
        $dalle = Dalle::with(['produit', 'produit.chantier', 'produit.chantier.client', 'modules'])->findOrFail($id);
        
        // Générer le QR code directement dans la vue
        $qrValue = route('dalles.show', $dalle->id);
        $qrCode = base64_encode(QrCode::format('png')->size(250)->errorCorrection('H')->margin(1)->generate($qrValue));
        $qrBase64 = 'data:image/png;base64,' . $qrCode;
        
        return view('etiquettes.dalle', [
            'dalle' => $dalle,
            'qrCode' => $qrBase64
        ]);
    }
    
    /**
     * Afficher la page d'impression d'étiquette pour un module
     *
     * @param int $id ID du module
     * @return \Illuminate\View\View
     */
    public function moduleEtiquette($id)
    {
        $module = Module::with(['dalle', 'dalle.produit', 'dalle.produit.chantier', 'dalle.produit.chantier.client'])->findOrFail($id);
        
        // Générer le QR code directement dans la vue
        $qrValue = route('modules.show', $module->id);
        $qrCode = base64_encode(QrCode::format('png')->size(200)->errorCorrection('H')->margin(1)->generate($qrValue));
        $qrBase64 = 'data:image/png;base64,' . $qrCode;
        
        return view('etiquettes.module', [
            'module' => $module,
            'qrCode' => $qrBase64
        ]);
    }
    
    /**
     * Impression par lots d'étiquettes de modules
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function moduleBatchEtiquettes(Request $request)
    {
        $moduleIds = $request->input('module_ids', []);
        $modules = Module::with(['dalle', 'dalle.produit', 'dalle.produit.chantier'])
                        ->whereIn('id', $moduleIds)
                        ->get();
        
        // Générer les QR codes pour chaque module
        $modulesWithQR = $modules->map(function($module) {
            $qrValue = route('modules.show', $module->id);
            $qrCode = base64_encode(QrCode::format('png')->size(200)->errorCorrection('H')->margin(1)->generate($qrValue));
            $module->qrCode = 'data:image/png;base64,' . $qrCode;
            return $module;
        });
        
        return view('etiquettes.module_batch', [
            'modules' => $modulesWithQR
        ]);
    }
    
    /**
     * Afficher la page de test d'étiquettes
     *
     * @return \Illuminate\View\View
     */
    public function testEtiquettes()
    {
        // Générer des QR codes de test
        $moduleQR = 'data:image/png;base64,' . base64_encode(
            QrCode::format('png')->size(200)->errorCorrection('H')->margin(1)
                  ->generate('https://gmao.tecaled.fr/test-module-qr')
        );
        
        $dalleQR = 'data:image/png;base64,' . base64_encode(
            QrCode::format('png')->size(250)->errorCorrection('H')->margin(1)
                  ->generate('https://gmao.tecaled.fr/test-dalle-qr')
        );
        
        $chantierQR = 'data:image/png;base64,' . base64_encode(
            QrCode::format('png')->size(300)->errorCorrection('H')->margin(1)
                  ->generate('https://gmao.tecaled.fr/test-chantier-qr')
        );
        
        return view('etiquettes.test', [
            'moduleQR' => $moduleQR,
            'dalleQR' => $dalleQR,
            'chantierQR' => $chantierQR
        ]);
    }
}
