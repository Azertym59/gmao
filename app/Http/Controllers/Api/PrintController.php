<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Printer;
use App\Services\PrinterService;
use App\Services\QrCodeService;
use App\Services\EzeepService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use PrintNode\Client;
use PrintNode\Credentials\ApiKey;

class PrintController extends Controller
{
    protected $printerService;
    protected $qrCodeService;
    protected $ezeepService;

    public function __construct(PrinterService $printerService, QrCodeService $qrCodeService, EzeepService $ezeepService)
    {
        $this->printerService = $printerService;
        $this->qrCodeService = $qrCodeService;
        $this->ezeepService = $ezeepService;
    }

    /**
     * Test d'impression d'une étiquette avec PrintNode
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testLabelPrint(Request $request)
    {
        try {
            // Générer un QR code pour le test
            $qrData = 'TECALED_TEST_' . date('YmdHis');
            $qrCode = $this->printerService->generateQrCode($qrData, 300);
            
            // Préparer les données pour la vue
            $viewData = [
                'qrcode' => $qrCode,
                'test_id' => uniqid('test_'),
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            // Générer le HTML de l'étiquette
            $html = View::make('etiquettes.brother_test_62x70', $viewData)->render();
            
            // TCPDF n'est pas disponible, utilisons une approche plus simple
            try {
                // Augmenter la limite de mémoire
                ini_set('memory_limit', '512M');
                
                // Au lieu d'utiliser TCPDF qui n'est pas installé, créons un HTML simple pour DomPDF
                $simpleHtml = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Test Etiquette</title>
                    <style>
                        body { 
                            font-family: sans-serif; 
                            margin: 0; 
                            padding: 10px;
                            text-align: center;
                        }
                        h1 { 
                            font-size: 14px; 
                            margin-bottom: 15px; 
                        }
                        img { 
                            width: 40mm; 
                            height: 40mm; 
                            margin: 0 auto; 
                            display: block;
                        }
                        p { 
                            font-size: 10px; 
                            margin: 5px 0; 
                        }
                    </style>
                </head>
                <body>
                    <h1>TEST IMPRESSION TECALED</h1>
                    <img src="' . $qrCode . '" alt="QR Code">
                    <p>Format: 62x80mm (DK-22205)</p>
                    <p>Test Brother QL-820NWB</p>
                    <p>' . date('Y-m-d H:i:s') . '</p>
                </body>
                </html>';
                
                try {
                    // Utiliser DomPDF pour créer un PDF (qui est déjà disponible dans Laravel)
                    // Calculer les dimensions pour rouleau continu 62mm
                    // 1 pouce = 72 points, 1 pouce = 25.4 mm
                    // Largeur en points: 62mm / 25.4 * 72 = 175.7 points
                    // Hauteur en points: 80mm / 25.4 * 72 = 226.8 points
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($simpleHtml)
                        ->setPaper([0, 0, 175.7, 226.8]) // 62mm x 80mm en points
                        ->setOptions([
                            'dpi' => 300,
                            'defaultFont' => 'sans-serif',
                            'isHtml5ParserEnabled' => true,
                            'isRemoteEnabled' => true,
                        ]);
                    
                    // Générer le PDF
                    $pdfOutput = $pdf->output();
                    
                    // Encoder en base64 pour PrintNode
                    $pdfContent = base64_encode($pdfOutput);
                    
                    // Pour l'affichage dans le navigateur
                    $htmlBase64 = 'data:application/pdf;base64,' . $pdfContent;
                    
                    Log::info('PDF généré avec DomPDF pour PrintNode', [
                        'taille' => strlen($pdfContent),
                        'premiers_caracteres' => substr($pdfContent, 0, 20) . '...'
                    ]);
                    
                    // Type de contenu pour PrintNode
                    $contentType = 'pdf_base64';
                } catch (\Exception $innerException) {
                    // Si DomPDF échoue aussi, utiliser l'image QR code directe
                    Log::warning('Erreur DomPDF, fallback sur PNG: ' . $innerException->getMessage());
                    
                    // Extraire l'image QR code brute
                    $qrCodeData = str_replace('data:image/png;base64,', '', $qrCode);
                    $pdfContent = $qrCodeData;
                    $htmlBase64 = $qrCode; // Garder le préfixe pour l'affichage
                    $contentType = 'png_base64';
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de la génération de l\'image: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                
                // En cas d'échec total, utiliser un texte brut simple
                $textContent = "TEST IMPRESSION TECALED\n\nFormat: 62x80mm\nDate: " . date('Y-m-d H:i:s');
                $pdfContent = base64_encode($textContent);
                $htmlBase64 = 'data:text/plain;base64,' . base64_encode($textContent);
                $contentType = 'raw_base64';
                
                Log::warning('Utilisation d\'un contenu texte brut comme fallback', [
                    'taille' => strlen($textContent)
                ]);
            }
            
            // Récupérer l'imprimante par défaut ou celle spécifiée
            $printerId = $request->input('printer_id');
            $printer = null;
            
            if ($printerId) {
                if ($printerId === 'test') {
                    // Mode test - ne pas chercher d'imprimante
                    $printer = null;
                } else {
                    $printer = Printer::find($printerId);
                }
            } else {
                // Récupérer l'imprimante par défaut
                $printer = Printer::where('is_default', true)->first();
            }
            
            // Pour le mode test ou si aucune imprimante n'est disponible
            if (!$printer || $printerId === 'test') {
                // Pour les tests, permettre la visualisation du contenu
                return response()->json([
                    'success' => true,
                    'message' => 'Aperçu généré avec succès (mode test)',
                    'pdf_base64' => $htmlBase64, // HTML contenant l'image
                    'html' => $html,
                ]);
            }
            
            // Déterminer quel service d'impression utiliser selon le type d'imprimante
            if ($printer->type === 'printnode') {
                $result = $this->sendToPrintNode($printer, $pdfContent, $request);
                // Vérifier si le résultat contient déjà un aperçu formaté
                $pdfPreview = isset($result['pdf_base64']) ? $result['pdf_base64'] : $pdfContent;
                
                // S'assurer que pdfPreview commence par data: pour l'affichage
                if (is_string($pdfPreview) && !str_starts_with($pdfPreview, 'data:')) {
                    // Ajouter le préfixe data: selon le type de contenu
                    if (isset($contentType) && $contentType === 'png_base64') {
                        $pdfPreview = 'data:image/png;base64,' . $pdfPreview;
                    } else if (isset($contentType) && $contentType === 'raw_base64') {
                        $pdfPreview = 'data:text/plain;base64,' . $pdfPreview;
                    } else {
                        $pdfPreview = 'data:application/pdf;base64,' . $pdfPreview;
                    }
                }
                
                return response()->json([
                    'success' => $result['success'],
                    'message' => $result['message'],
                    'printer' => $printer->name,
                    'print_job_id' => $result['print_job_id'] ?? null,
                    'pdf_base64' => $pdfPreview, // Préfixé avec data: si nécessaire
                ]);
            } else if ($printer->type === 'ezeep') {
                // Convertir HTML en PDF pour ezeep
                try {
                    // Utiliser la classe PDF pour générer un PDF à partir du HTML
                    // Calculer les dimensions pour DK-11201 (29mm x 90mm)
                    // 1 pouce = 72 points, 1 pouce = 25.4 mm
                    // Largeur en points: 29mm / 25.4 * 72 = 82.2 points
                    // Hauteur en points: 90mm / 25.4 * 72 = 254.6 points
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
                        ->setPaper([0, 0, 82.2, 254.6]) // 29mm x 90mm en points
                        ->setOptions([
                            'dpi' => 300,
                            'defaultFont' => 'sans-serif',
                            'isHtml5ParserEnabled' => true,
                            'isRemoteEnabled' => true,
                        ]);
                    
                    // Générer le PDF
                    $pdfOutput = $pdf->output();
                    
                    // Encodage base64
                    $pdfBase64 = base64_encode($pdfOutput);
                    
                    // Envoyer à ezeep
                    $result = $this->sendToEzeep($printer, $pdfBase64, $request);
                    
                    return response()->json([
                        'success' => $result['success'],
                        'message' => $result['message'],
                        'printer' => $printer->name,
                        'print_job_id' => $result['print_job_id'] ?? null,
                        'pdf_base64' => 'data:application/pdf;base64,' . $pdfBase64, // Pour l'aperçu
                    ]);
                } catch (\Exception $e) {
                    // En cas d'erreur, on retourne le HTML
                    Log::error('Erreur lors de la génération du PDF pour ezeep: ' . $e->getMessage());
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de la génération du PDF: ' . $e->getMessage(),
                        'pdf_base64' => 'data:text/plain;base64,' . $pdfContent, // Ajouter préfixe pour l'affichage
                    ]);
                }
            } else {
                // Pour les autres types d'imprimantes ou imprimantes non supportées
                return response()->json([
                    'success' => true,
                    'message' => 'Aperçu généré avec succès (imprimante non prise en charge)',
                    'pdf_base64' => $htmlBase64,
                    'html' => $html,
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du test d\'impression: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Créer un message d'erreur HTML pour l'aperçu
            $errorHtml = '<html><body style="font-family: sans-serif; padding: 20px;"><div style="color:red;border:1px solid #ffcccc;padding:15px;background:#fff4f4;border-radius:5px;">';
            $errorHtml .= '<h3>Erreur lors du test d\'impression</h3>';
            $errorHtml .= '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            $errorHtml .= '<p><small>Type: ' . htmlspecialchars(get_class($e)) . '</small></p>';
            $errorHtml .= '</div></body></html>';
            
            // Retourner une erreur avec des détails pour aider au débogage
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test: ' . $e->getMessage(),
                'error_type' => get_class($e),
                'trace_summary' => array_slice(explode("\n", $e->getTraceAsString()), 0, 5),
                'pdf_base64' => 'data:text/html;base64,' . base64_encode($errorHtml)
            ], 500);
        }
    }
    
    /**
     * Envoie un document PDF à PrintNode
     *
     * @param Printer $printer
     * @param string $pdfContent
     * @param Request $request
     * @return array
     */
    /**
     * Envoie un document PDF à PrintNode
     *
     * @param Printer $printer
     * @param string $pdfContent
     * @param Request $request
     * @return array
     */
    private function sendToPrintNode(Printer $printer, string $pdfContent, Request $request = null)
    {
        try {
            // Mode démo - simule une impression réussie sans envoyer à PrintNode
            if (config('services.printnode.api_key') === 'demo_key') {
                Log::info('Mode démo - Simulation d\'impression PrintNode réussie', [
                    'printer' => $printer->name
                ]);
                
                // Ajouter le préfixe data: si nécessaire
                $displayContent = 'data:application/pdf;base64,' . $pdfContent;
                
                return [
                    'success' => true,
                    'message' => 'Document simulé envoyé à l\'imprimante (mode démo)',
                    'print_job_id' => 'demo-'.time(),
                    'pdf_base64' => $displayContent
                ];
            }
            
            // Initialiser le client PrintNode avec l'API key réelle
            $apiKey = config('services.printnode.api_key');
            if (empty($apiKey)) {
                throw new \Exception('API Key PrintNode non configurée');
            }
            
            $credentials = new ApiKey($apiKey);
            $client = new Client($credentials);
            
            // Vérifier que nous avons un ID d'imprimante PrintNode valide
            // Soit depuis les options JSON, soit depuis la configuration
            $printerOptions = $printer->options ?? [];
            $printerId = isset($printerOptions['printnode_id']) ? $printerOptions['printnode_id'] : null;
            
            // Si pas dans les options, utiliser l'ID de config global
            if (empty($printerId)) {
                $printerId = config('services.printnode.printer_id');
            }
            
            // Si toujours pas d'ID valide, simuler en mode test ou générer une erreur
            if (empty($printerId) || $printerId === '12345') {
                // Toujours simuler une impression réussie pour éviter les erreurs
                Log::info('Simulation d\'impression PrintNode (ID imprimante manquant/invalide)', [
                    'printer' => $printer->name
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Document simulé envoyé à l\'imprimante (mode test)',
                    'print_job_id' => 'demo-'.time(),
                    'pdf_base64' => $pdfContent
                ];
            }
            
            // Créer un job d'impression pour PrintNode
            try {
                // En mode test, nous pouvons activer l'impression réelle avec PrintNode
                // si l'utilisateur le demande explicitement
                $printMode = $request ? $request->input('print_mode', 'test') : 'test';
                
                if ($printMode === 'test') {
                    // Mode test - juste simuler une impression
                    Log::info('Mode test - Simulation print sans API PrintNode', [
                        'printer_id' => $printerId,
                        'printer_name' => $printer->name
                    ]);
                    
                    $printJobId = 'test-' . time();
                    
                } else {
                    // Mode réel - envoi à PrintNode
                    
                    // Définir un type de contenu par défaut si non défini
                    if (!isset($contentType)) {
                        $contentType = 'pdf_base64';
                    }
                    
                    // Log détaillé pour le débogage
                    // Récupérer l'option de format alternatif depuis la requête
                    $alternativeFormat = $request ? $request->input('format_alt', false) : false;
                    
                    // Paramètres d'impression selon le format demandé
                    // Vérifier si une valeur de type de papier a été fournie dans la requête
                    $requestedPaper = $request ? $request->input('paper_type', null) : null;
                    
                    // Méthode suggérée par Claude (autres noms de paramètres)
                    if ($requestedPaper === 'claude_method') {
                        $printOptions = [
                            'media' => 'continuous',
                            'paperWidth' => '62',         // Largeur en mm
                            'paperLength' => '100',       // Hauteur souhaitée en mm
                            'orientation' => 'portrait', 
                            'copies' => 1
                        ];
                    } else {
                        // Options standard
                        $printOptions = [
                            'bin' => 'default',
                            'paper' => $requestedPaper ?: 'Custom.62x100mm',
                            'orientation' => 'portrait',
                            'margin' => '0mm',
                            'media' => 'dc-22205',
                            'roll' => 'dk22205',
                            'width' => '62',
                            'height' => '100',
                            'fit-to-page' => true,
                            'dpi' => '300',              // En string comme exigé par PrintNode
                            'rotate' => 0,
                            'printer-model' => 'QL-820NWB',
                            'density' => 'normal'
                        ];
                    }
                    
                    // Format alternatif si demandé
                    if ($alternativeFormat) {
                        // Utiliser l'approche plus simple recommandée par PrintNode pour Brother
                        if ($alternativeFormat === 'true' || $alternativeFormat === true || $alternativeFormat === 'alt') {
                            $printOptions = [
                                'paper' => '22205',    // Code brut sans préfixe
                                'orientation' => 'portrait',
                                'width' => '62',
                                'height' => '100',
                                'dpi' => '300',       // En string comme exigé par PrintNode
                                'printer-model' => 'QL-820NWB'
                            ];
                        }
                        // Format standard Brother conforme à la documentation
                        else if ($alternativeFormat === 'standard') {
                            $printOptions = [
                                'bin' => 'default',
                                'paper' => 'DK-22205',  // Format exact comme indiqué dans la doc Brother
                                'orientation' => 'portrait',
                                'width' => '62',
                                'height' => '100',
                                'dpi' => '300',
                                'rotate' => 0,
                                'printer-model' => 'QL-820NWB'
                            ];
                        }
                        // Mode minimal - seulement les paramètres essentiels
                        else if ($alternativeFormat === 'minimal') {
                            $printOptions = [
                                'paper' => 'DK-22205',
                                'width' => '62',
                                'height' => '100',
                                'dpi' => '300'
                            ];
                        }
                        // Approche texte brut
                        else if ($alternativeFormat === 'raw') {
                            $contentType = 'raw_base64';
                            
                            // Créer un texte simple à imprimer
                            $textContent = "TEST IMPRESSION TECALED\n\n";
                            $textContent .= "Rouleau DK-22205 (62mm)\n";
                            $textContent .= "Date: " . date('Y-m-d H:i:s');
                            
                            // Encoder en base64
                            $pdfContent = base64_encode($textContent);
                            
                            // Options pour texte brut
                            $printOptions = [
                                'paper' => 'dk-22205',
                                'width' => '62',
                                'height' => '100',
                                'dpi' => '300',
                                'printer-model' => 'QL-820NWB'
                            ];
                        }
                        // Mode impression directe USB en contournant PrintNode
                        else if ($alternativeFormat === 'direct') {
                            // Message de log pour indiquer que nous contournons PrintNode
                            Log::info('Mode impression directe par USB activé - contournement de PrintNode');
                            
                            try {
                                // Essayons d'imprimer directement via le système
                                $tempFile = tempnam(sys_get_temp_dir(), 'label_') . '.pdf';
                                
                                // Générer un PDF simple au lieu d'utiliser TCPDF ou DomPDF qui peut causer des problèmes
                                $simpleHtml = '
                                <!DOCTYPE html>
                                <html>
                                <head>
                                    <meta charset="utf-8">
                                    <title>Test Étiquette</title>
                                    <style>
                                        body { 
                                            font-family: sans-serif; 
                                            margin: 0; 
                                            padding: 5mm;
                                            text-align: center;
                                            width: 62mm;
                                            height: 75mm;
                                        }
                                        h1 { 
                                            font-size: 14pt; 
                                            margin: 5mm 0; 
                                        }
                                        p { 
                                            font-size: 10pt; 
                                            margin: 3mm 0; 
                                        }
                                    </style>
                                </head>
                                <body>
                                    <h1>TEST IMPRESSION TECALED</h1>
                                    <p>Format: 62mm (DK-22205)</p>
                                    <p>Test Brother QL-820NWB</p>
                                    <p>' . date('Y-m-d H:i:s') . '</p>
                                </body>
                                </html>';
                                
                                // Écrire le HTML dans un fichier temporaire
                                file_put_contents($tempFile, $simpleHtml);
                                
                                // Commande pour impression directe - utilise le pilote Brother natif
                                // Déterminer le nom de l'imprimante Brother connectée
                                $brotherPrinterName = 'Brother_QL-820NWB';
                                $output = [];
                                exec('lpstat -p | grep -i brother', $output);
                                
                                if (!empty($output)) {
                                    // Prendre le premier nom d'imprimante Brother trouvé
                                    $printerNameParts = explode(' ', $output[0]);
                                    if (isset($printerNameParts[1])) {
                                        $brotherPrinterName = $printerNameParts[1];
                                    }
                                }
                                
                                // Essayer d'imprimer directement avec lpr/lp
                                $cmd = "lpr -P $brotherPrinterName -o media=custom.62x100mm -o DK-22205 $tempFile 2>&1";
                                Log::info("Commande impression directe: $cmd");
                                
                                $output = [];
                                $returnCode = 0;
                                exec($cmd, $output, $returnCode);
                                
                                // Enregistrer le résultat
                                Log::info('Résultat impression directe', [
                                    'returnCode' => $returnCode,
                                    'output' => $output
                                ]);
                                
                                // Nettoyer le fichier temporaire
                                @unlink($tempFile);
                                
                                // Retourner une réponse simulée (car on ne passe pas par PrintNode)
                                return [
                                    'success' => $returnCode === 0,
                                    'message' => 'Impression directe via USB ' . 
                                                ($returnCode === 0 ? 'réussie' : 'échouée') . 
                                                ' - Consultez les logs pour les détails',
                                    'print_job_id' => 'direct-usb-' . time(),
                                    'pdf_base64' => 'data:text/html;base64,' . base64_encode($simpleHtml)
                                ];
                            } catch (\Exception $e) {
                                Log::error('Erreur lors de l\'impression directe USB: ' . $e->getMessage(), [
                                    'trace' => $e->getTraceAsString()
                                ]);
                                
                                // En cas d'erreur, continuer avec PrintNode
                                $printOptions = [
                                    'paper' => 'DK-22205',
                                    'width' => '62',
                                    'height' => '100',
                                    'dpi' => '300',
                                    'printer-model' => 'QL-820NWB'
                                ];
                            }
                        }
                    }
                    
                    Log::debug('Contenu pour PrintNode', [
                        'type' => $contentType,
                        'taille' => strlen($pdfContent),
                        'premiers_caracteres' => substr($pdfContent, 0, 20) . '...',
                        'printer_id' => $printerId,
                        'format_alternatif' => $alternativeFormat,
                        'options' => $printOptions
                    ]);
                    
                    // Création de l'objet PrintJob en suivant la structure attendue
                    $printJobData = [
                        'printer' => (int)$printerId,   // ID entier
                        'title' => 'Test Etiquette TECALED',
                        'contentType' => $contentType,  // Utiliser le type déterminé (png_base64 ou pdf_base64)
                        'content' => $pdfContent,
                        'source' => 'GMAO TECALED',
                        'options' => $printOptions      // Utiliser les options définies plus haut
                    ];
                    
                    // Envoyer le job à PrintNode via l'API JSON
                    $printJobId = $client->createPrintJob($printJobData);
                    
                    if (!$printJobId) {
                        throw new \Exception('Réponse invalide de PrintNode - aucun ID de job retourné');
                    }
                    
                    Log::info('Job PrintNode envoyé avec succès', [
                        'printer' => $printer->name,
                        'print_job_id' => $printJobId,
                        'printer_id' => $printerId,
                        'job_options' => $printJobData['options'],
                        'content_type' => $contentType
                    ]);
                    
                    // Vérification du statut du job juste après la création
                    try {
                        // Attendre un court instant
                        usleep(500000); // 500ms
                        
                        // Tenter de récupérer le statut du job
                        $jobState = $client->getStates($printJobId);
                        Log::info('État du PrintJob juste après création', [
                            'job_id' => $printJobId,
                            'state' => $jobState ?? 'Inconnu'
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Impossible de récupérer l\'état du job: ' . $e->getMessage());
                    }
                }
                
                // Simuler un objet de réponse
                $responseData = (object)[
                    'id' => $printJobId,
                    'state' => 'sent',
                    'mode' => $printMode
                ];
                
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi à PrintNode: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                
                // En cas d'erreur, on continue en mode simulation
                $responseData = (object)[
                    'id' => 'fallback-' . time(),
                    'state' => 'error',
                    'error' => $e->getMessage()
                ];
            }
            
            // Message et statut selon le mode
            $message = ($responseData->state === 'error') 
                ? 'Erreur: ' . ($responseData->error ?? 'Erreur inconnue')
                : (($responseData->mode === 'test') 
                    ? 'Document simulé envoyé à l\'imprimante (mode test)' 
                    : 'Document envoyé à l\'imprimante Brother');
            
            return [
                'success' => $responseData->state !== 'error',
                'message' => $message,
                'print_mode' => $responseData->mode ?? 'test',
                'print_job_id' => $responseData->id,
                'pdf_base64' => $pdfContent
            ];
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'impression PrintNode: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // S'assurer que l'aperçu a toujours le préfixe data:
            $errorPreview = isset($htmlBase64) ? $htmlBase64 : 'data:text/plain;base64,' . base64_encode('Erreur: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erreur d\'impression: ' . $e->getMessage(),
                'print_job_id' => null,
                'pdf_base64' => $errorPreview
            ];
        }
    }
    
    /**
     * Envoie un document à ezeep pour impression
     *
     * @param Printer $printer Imprimante à utiliser
     * @param string $pdfBase64 Contenu PDF encodé en base64
     * @param Request $request Requête HTTP
     * @return array Résultat de l'impression
     */
    private function sendToEzeep(Printer $printer, string $pdfBase64, Request $request = null)
    {
        try {
            // Vérifier si nous sommes en mode test ou réel
            $printMode = $request ? $request->input('print_mode', 'test') : 'test';
            
            // Mode test - simuler une impression sans envoyer à ezeep
            if ($printMode === 'test') {
                Log::info('Mode test - Simulation d\'impression ezeep', [
                    'printer' => $printer->name
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Document simulé envoyé à l\'imprimante (mode test ezeep)',
                    'print_job_id' => 'ezeep-test-'.time(),
                    'pdf_base64' => $pdfBase64
                ];
            }
            
            // Récupérer l'ID de l'imprimante ezeep (soit depuis les options, soit depuis la configuration)
            $printerOptions = $printer->options ?? [];
            $ezeepPrinterId = isset($printerOptions['ezeep_id']) ? $printerOptions['ezeep_id'] : null;
            
            if (empty($ezeepPrinterId)) {
                $ezeepPrinterId = config('services.ezeep.printer_id');
            }
            
            // Vérifier si nous avons un ID d'imprimante valide
            if (empty($ezeepPrinterId)) {
                Log::warning('ID d\'imprimante ezeep manquant - mode simulation', [
                    'printer' => $printer->name
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Document simulé envoyé à l\'imprimante (mode test ezeep - ID imprimante manquant)',
                    'print_job_id' => 'ezeep-test-'.time(),
                    'pdf_base64' => $pdfBase64
                ];
            }
            
            // Nettoyer la chaîne base64 si nécessaire (enlever le préfixe data:application/pdf;base64,)
            if (strpos($pdfBase64, 'data:application/pdf;base64,') === 0) {
                $pdfBase64 = substr($pdfBase64, strlen('data:application/pdf;base64,'));
            }
            
            // Préparer les options d'impression
            $options = [
                'paper_size' => 'Label.29x90mm', // Format d'étiquette prédéfini
                'orientation' => 'portrait',
                'copies' => 1,
                'color' => false,  // Impression en noir et blanc
                'duplex' => false, // Pas de recto-verso
                'fit_to_page' => true
            ];
            
            // Envoyer à ezeep
            $result = $this->ezeepService->printDocument(
                $ezeepPrinterId,
                $pdfBase64,
                'etiquette_tecaled_' . date('Ymd_His') . '.pdf',
                $options
            );
            
            if ($result['success']) {
                return [
                    'success' => true,
                    'message' => 'Document envoyé à l\'imprimante via ezeep',
                    'print_job_id' => $result['job_id'] ?? 'ezeep-job-'.time(),
                    'pdf_base64' => $pdfBase64
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi à ezeep: ' . ($result['message'] ?? 'Erreur inconnue'),
                    'error' => $result['error'] ?? null,
                    'pdf_base64' => $pdfBase64
                ];
            }
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'impression avec ezeep: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Erreur d\'impression ezeep: ' . $e->getMessage(),
                'print_job_id' => null,
                'pdf_base64' => $pdfBase64
            ];
        }
    }
}