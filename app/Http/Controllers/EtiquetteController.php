<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Dalle;
use App\Models\Module;
use App\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;

class EtiquetteController extends Controller
{
    /**
     * Télécharger une étiquette flightcase au format PDF
     *
     * @param int $chantier_id ID du chantier
     * @param int $flightcase_number Numéro du flightcase
     * @return \Illuminate\Http\Response
     */
    public function downloadFlightcasePdf($chantier_id, $flightcase_number)
    {
        // Récupérer le chemin du fichier temporaire depuis la session
        $tempFile = session('pdf_temp_file_' . $chantier_id . '_' . $flightcase_number);
        
        if (!$tempFile || !file_exists($tempFile)) {
            // Si le fichier n'existe pas, générer un nouveau PDF
            $chantier = Chantier::with(['client', 'produits', 'produits.dalles'])->findOrFail($chantier_id);
            
            // Récupérer toutes les dalles du chantier pour ce flightcase
            $dalles = [];
            $totalFlightcases = 0;
            
            // Compter le nombre total de flightcases
            foreach ($chantier->produits as $produit) {
                foreach ($produit->dalles as $dalle) {
                    if (isset($dalle->flightcase_number) && !empty($dalle->flightcase_number)) {
                        if (!in_array($dalle->flightcase_number, $dalles)) {
                            $totalFlightcases++;
                        }
                    }
                }
            }
            
            // Récupérer les dalles du flightcase spécifié
            foreach ($chantier->produits as $produit) {
                foreach ($produit->dalles as $dalle) {
                    if (isset($dalle->flightcase_number) && $dalle->flightcase_number == $flightcase_number) {
                        $dalles[] = $dalle;
                    }
                }
            }
            
            // Générer le QR code pour le suivi du chantier
            $qrValue = route('suivi.chantier', $chantier->getTokenSuivi());
            $qrCode = base64_encode(QrCode::format('png')->size(300)->errorCorrection('H')->margin(1)->generate($qrValue));
            $qrBase64 = 'data:image/png;base64,' . $qrCode;
            
            // Générer le contenu HTML pour le PDF en utilisant le même style que flightcase.blade.php
            $html = '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>Étiquette Flightcase</title>
                <style>
                    @page {
                        size: 62mm 90mm;
                        margin: 0;
                        padding: 0;
                    }
                    body {
                        margin: 0;
                        padding: 0;
                        font-family: Arial, sans-serif;
                        width: 62mm;
                        height: 90mm;
                    }
                    .etiquette {
                        width: 62mm;
                        height: 90mm;
                        padding: 2mm;
                        box-sizing: border-box;
                        overflow: hidden;
                        position: relative;
                    }
                    .header {
                        text-align: center;
                        padding-bottom: 1mm;
                        border-bottom: 1pt solid #000;
                        margin-bottom: 2mm;
                    }
                    .logo {
                        height: 8mm;
                        margin-bottom: 1mm;
                    }
                    .company {
                        font-size: 10pt;
                        font-weight: bold;
                        margin: 0;
                    }
                    .reference {
                        font-size: 12pt;
                        font-weight: bold;
                        color: #cc0000;
                        margin: 1mm 0;
                    }
                    .client {
                        font-size: 10pt;
                        margin: 1mm 0;
                    }
                    .flightcase-info {
                        border: 1pt solid #000;
                        padding: 1mm;
                        margin: 2mm 0;
                        font-size: 10pt;
                        font-weight: bold;
                        text-align: center;
                    }
                    .qrcode-container {
                        text-align: center;
                        margin: 3mm 0;
                    }
                    .qrcode {
                        width: 28mm;
                        height: 28mm;
                    }
                    .remarque {
                        border: 1pt solid #000;
                        padding: 1mm;
                        margin-top: 2mm;
                        min-height: 15mm;
                    }
                    .remarque-label {
                        font-size: 8pt;
                        font-weight: bold;
                        margin-bottom: 1mm;
                    }
                    .dalles-info {
                        font-size: 9pt;
                        margin: 2mm 0;
                    }
                    .footer {
                        position: absolute;
                        bottom: 2mm;
                        left: 2mm;
                        right: 2mm;
                        font-size: 7pt;
                        text-align: center;
                        color: #666;
                    }
                </style>
            </head>
            <body>
                <div class="etiquette">
                    <div class="header">
                        <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair%20new.png" alt="TecaLED" class="logo">
                        <p class="company">TECALED GMAO</p>
                    </div>
                    
                    <p class="reference">' . $chantier->reference . '</p>
                    <p class="client">' . $chantier->client->societe . '</p>
                    
                    <div class="flightcase-info">
                        FLIGHTCASE ' . $flightcase_number . '/' . $totalFlightcases . '
                    </div>
                    
                    <p class="dalles-info">Contient ' . count($dalles) . ' dalle(s)</p>
                    
                    <div class="qrcode-container">
                        <img src="' . $qrBase64 . '" alt="QR Code" class="qrcode">
                    </div>
                    
                    <div class="remarque">
                        <div class="remarque-label">REMARQUES:</div>
                    </div>
                    
                    <div class="footer">
                        Scan QR code pour accès au chantier - ' . date('d/m/Y') . '
                    </div>
                </div>
            </body>
            </html>';
            
            // Générer le PDF avec DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper([0, 0, 175.75, 254.65], 'portrait'); // 62mm x 90mm en points (1pt = 0.352778mm)
            $pdf->setOptions([
                'dpi' => 300,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);
            
            // Créer un nom de fichier temporaire
            $tempFile = tempnam(sys_get_temp_dir(), 'label_') . '.pdf';
            file_put_contents($tempFile, $pdf->output());
            
            // Sauvegarder le chemin pour les futurs téléchargements
            session(['pdf_temp_file_' . $chantier_id . '_' . $flightcase_number => $tempFile]);
            
            // Log de l'action
            Log::info('PDF flightcase généré', [
                'chantier_id' => $chantier_id,
                'flightcase_number' => $flightcase_number,
                'temp_file' => $tempFile,
                'file_size' => filesize($tempFile)
            ]);
        }
        
        // Préparer la réponse avec le fichier PDF
        $filename = 'flightcase_' . $flightcase_number . '_' . date('Ymd') . '.pdf';
        
        return response()->file($tempFile, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
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
     * @param int $chantier ID du chantier
     * @return \Illuminate\Http\Response
     */
    public function chantierPTouchTemplate($chantier)
    {
        $chantier = Chantier::with(['client', 'produits', 'produits.dalles', 'produits.dalles.modules'])->findOrFail($chantier);
        
        // Générer le QR code pour le modèle
        $qrValue = route('chantiers.show', $chantier->id);
        $qrData = QrCode::format('png')->size(300)->errorCorrection('H')->margin(1)->generate($qrValue);
        
        // Convertir l'image en base64 pour l'envoyer à PrintNode
        $qrBase64 = base64_encode($qrData);
        
        // Création directe d'une image
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
        
        // Sauvegarder l'image dans un buffer
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        
        // Libérer la mémoire
        imagedestroy($image);
        
        // Convertir en base64 pour PrintNode
        $imageBase64 = base64_encode($imageData);
        
        // PrintNode API credentials
        $apiKey = 'JTDVu0eHs2bXjVOb_HDUKVimF_WechuZM2XuwQAiE04';
        $printerId = 74188357; // Brother QL-820NWB (Copie 1)
        
        try {
            // Log de l'opération
            Log::info('Test impression chantier', [
                'chantier' => $chantier->reference,
                'taille_base64' => strlen($imageBase64)
            ]);
            
            // Créer le payload pour PrintNode
            $payload = [
                "printerId" => (int)$printerId,
                "title" => "Étiquette Chantier: " . $chantier->reference,
                "contentType" => "raw_base64",
                "content" => $imageBase64,
                "source" => "GMAO TecaLED",
                "options" => [
                    "paper" => "Custom.62x100mm",
                    "bin" => "dk22205",
                    "media" => "continuous",
                    "fit-to-page" => true,
                    "orientation" => "portrait"
                ]
            ];
            
            // Envoyer la requête à l'API PrintNode
            $ch = curl_init("https://api.printnode.com/printjobs");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($apiKey . ':')
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            
            // Exécuter la requête
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            Log::info('Réponse PrintNode', [
                'http_code' => $httpCode,
                'response' => $response
            ]);
            
            curl_close($ch);
            
            if ($httpCode >= 200 && $httpCode < 300) {
                $printJobId = json_decode($response);
                return response()->json([
                    'success' => true,
                    'message' => 'Étiquette envoyée à l\'impression avec succès',
                    'printer' => 'Brother QL-820NWB (Copie 1)',
                    'print_job_id' => $printJobId
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur API PrintNode: ' . $response
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception lors de l\'impression: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
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
    
    /**
     * Générer les étiquettes pour les flightcases d'un chantier
     *
     * @param int $chantier_id ID du chantier
     * @return \Illuminate\View\View
     */
    public function flightcaseEtiquettes($chantier_id)
    {
        // Charger le chantier avec ses relations
        $chantier = Chantier::with(['client', 'produits', 'produits.dalles'])
                        ->findOrFail($chantier_id);
        
        // Générer le QR code pour le suivi du chantier
        $qrValue = route('suivi.chantier', $chantier->getTokenSuivi());
        $qrCode = base64_encode(QrCode::format('png')->size(300)->errorCorrection('H')->margin(1)->generate($qrValue));
        $qrBase64 = 'data:image/png;base64,' . $qrCode;
        
        // Récupérer toutes les dalles du chantier groupées par flightcase
        $dallesByFlightcase = [];
        $totalFlightcases = 0;
        
        foreach ($chantier->produits as $produit) {
            foreach ($produit->dalles as $dalle) {
                // Si la dalle a un numéro de flightcase
                if (isset($dalle->flightcase_number) && !empty($dalle->flightcase_number)) {
                    $flightcaseNumber = $dalle->flightcase_number;
                    
                    if (!isset($dallesByFlightcase[$flightcaseNumber])) {
                        $dallesByFlightcase[$flightcaseNumber] = [];
                        $totalFlightcases++;
                    }
                    
                    $dallesByFlightcase[$flightcaseNumber][] = $dalle;
                }
            }
        }
        
        // Si aucun flightcase n'est défini, montrer un message d'erreur ou créer un flightcase par défaut
        if (empty($dallesByFlightcase)) {
            // Créer un flightcase par défaut avec toutes les dalles
            $flightcaseNumber = 1;
            $dallesByFlightcase[$flightcaseNumber] = [];
            
            foreach ($chantier->produits as $produit) {
                foreach ($produit->dalles as $dalle) {
                    $dallesByFlightcase[$flightcaseNumber][] = $dalle;
                }
            }
            
            $totalFlightcases = 1;
        }
        
        // Préparer les données pour les étiquettes
        $flightcases = [];
        
        foreach ($dallesByFlightcase as $flightcaseNumber => $dalles) {
            $flightcases[] = [
                'flightcaseNumber' => $flightcaseNumber,
                'totalFlightcases' => $totalFlightcases,
                'nbDalles' => count($dalles),
                'chantier' => $chantier,
                'qrCode' => $qrBase64
            ];
        }
        
        // Retourner la vue avec les données
        return view('etiquettes.flightcase_batch', [
            'flightcases' => $flightcases,
            'chantier' => $chantier,
            'qrCode' => $qrBase64
        ]);
    }
    
    /**
     * Imprimer directement une étiquette de flightcase
     *
     * @param int $chantier_id ID du chantier
     * @param int $flightcase_number Numéro du flightcase
     * @return \Illuminate\Http\Response
     */
    public function printFlightcaseEtiquette($chantier_id, $flightcase_number)
    {
        // Charger le chantier avec ses relations
        $chantier = Chantier::with(['client', 'produits', 'produits.dalles'])
                        ->findOrFail($chantier_id);
        
        // Générer le QR code pour le suivi du chantier
        $qrValue = route('suivi.chantier', $chantier->getTokenSuivi());
        $qrCode = base64_encode(QrCode::format('png')->size(300)->errorCorrection('H')->margin(1)->generate($qrValue));
        
        // Récupérer toutes les dalles du chantier pour ce flightcase
        $dalles = [];
        $totalFlightcases = 0;
        
        // Compter le nombre total de flightcases
        foreach ($chantier->produits as $produit) {
            foreach ($produit->dalles as $dalle) {
                if (isset($dalle->flightcase_number) && !empty($dalle->flightcase_number)) {
                    if (!in_array($dalle->flightcase_number, $dalles)) {
                        $totalFlightcases++;
                    }
                }
            }
        }
        
        // Récupérer les dalles du flightcase spécifié
        foreach ($chantier->produits as $produit) {
            foreach ($produit->dalles as $dalle) {
                if (isset($dalle->flightcase_number) && $dalle->flightcase_number == $flightcase_number) {
                    $dalles[] = $dalle;
                }
            }
        }
        
        // Si aucune dalle n'est trouvée pour ce flightcase, renvoyer une erreur
        if (empty($dalles)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune dalle trouvée pour ce flightcase'
            ], 404);
        }
        
        // NOUVELLE APPROCHE: Direct PrintNode API sans dépendances
        try {
            // Ajoutons des logs pour déboguer
            Log::debug('Entrée dans la fonction printFlightcaseEtiquette', [
                'chantier_id' => $chantier_id,
                'flightcase_number' => $flightcase_number
            ]);
            
            // Récupérer les informations d'imprimante depuis la configuration et la base de données
            $apiKey = config('services.printnode.api_key');
            
            // TENTATIVE ALTERNATIVE : Essayer d'utiliser une méthode directe Windows
            // PrintNode rencontre une erreur interne avec le moteur d'impression
            $printerId = 74188357; // ID de votre Brother QL-820NWB dans PrintNode
            $printerName = 'Brother QL-820NWB Printer'; // Nom exact dans Windows
            
            // Log pour débogage
            Log::info('Utilisation directe de l\'imprimante', [
                'printnode_id' => $printerId,
                'printer_name' => $printerName
            ]);
            
            // Logging pour le diagnostic
            Log::info('Configuration PrintNode récupérée', [
                'api_key_set' => !empty($apiKey),
                'printer_id' => $printerId,
                'printer_name' => $printerName
            ]);
            
            // Log des informations initiales
            Log::info('Début impression flightcase', [
                'chantier' => $chantier->reference,
                'flightcase' => $flightcase_number,
                'nb_dalles' => count($dalles)
            ]);
            
            // Création d'une image simple pour l'impression
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
            
            // Ajouter le titre et informations
            imagestring($image, 5, 20, 20, "TECALED GMAO", $black);
            imagestring($image, 5, 20, 60, "Chantier: ".$chantier->reference, $red);
            imagestring($image, 4, 20, 100, "Client: ".$chantier->client->societe, $black);
            imagestring($image, 4, 20, 140, "FLIGHTCASE {$flightcase_number}/{$totalFlightcases}", $black);
            imagestring($image, 4, 20, 170, "Contient " . count($dalles) . " dalle(s)", $black);
            
            // Ajouter le QR code (déjà généré plus haut)
            $qrImage = imagecreatefromstring(base64_decode($qrCode));
            if ($qrImage) {
                $qrWidth = imagesx($qrImage);
                $qrHeight = imagesy($qrImage);
                
                imagecopyresampled(
                    $image, $qrImage,
                    ($width - $qrWidth) / 2, 220,
                    0, 0,
                    $qrWidth, $qrHeight,
                    $qrWidth, $qrHeight
                );
                
                imagedestroy($qrImage);
            }
            
            // Ajouter un texte en bas
            imagestring($image, 3, 20, $height - 40, "Scannez pour details - " . date('d/m/Y'), $black);
            
            // Convertir l'image en PNG puis en base64 pour l'intégrer dans un HTML
            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            imagedestroy($image);
            
            $imageBase64InHtml = 'data:image/png;base64,' . base64_encode($imageData);
            
            // Utiliser DomPDF (déjà disponible dans Laravel) pour créer un PDF
            $html = '<!DOCTYPE html>
                <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                    <style>
                        @page {
                            margin: 0;
                            padding: 0;
                            size: 62mm 100mm;
                        }
                        body {
                            margin: 0;
                            padding: 0;
                            width: 62mm;
                            height: 100mm;
                        }
                        img {
                            width: 62mm;
                            height: 100mm;
                            object-fit: cover;
                        }
                    </style>
                </head>
                <body>
                    <img src="' . $imageBase64InHtml . '" alt="Flightcase Label" />
                </body>
                </html>';
            
            // Générer le PDF avec DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper([0, 0, 175.75, 283.46], 'portrait'); // 62mm x 100mm en points
            $pdf->setOptions([
                'dpi' => 300,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);
            
            // Récupérer le contenu du PDF
            $pdfContent = $pdf->output();
            
            // Encodage en base64 pour PrintNode
            $imageBase64 = base64_encode($pdfContent);
            
            Log::debug('PDF généré pour l\'impression', [
                'taille_pdf' => strlen($pdfContent),
                'taille_base64' => strlen($imageBase64)
            ]);
            
            Log::info('Image générée pour flightcase', [
                'taille_base64' => strlen($imageBase64)
            ]);
            
            // Essayer avec du texte brut comme alternative
            $rawText = "TECALED GMAO\n";
            $rawText .= "Chantier: " . $chantier->reference . "\n";
            $rawText .= "Client: " . $chantier->client->societe . "\n";
            $rawText .= "FLIGHTCASE {$flightcase_number}/{$totalFlightcases}\n";
            $rawText .= "Contient " . count($dalles) . " dalle(s)\n";
            $rawText .= "Date: " . date('d/m/Y') . "\n";
            
            // Créer le payload PrintNode pour format texte brut
            $payload = [
                "printerId" => (int)$printerId,
                "title" => "Étiquette Flightcase #{$flightcase_number} - Texte simple",
                "contentType" => "raw_base64",
                "content" => base64_encode($rawText),
                "source" => "GMAO TecaLED"
            ];
            
            // Alternatif: essayer avec PNG direct
            if (isset($_GET['png'])) {
                // Utiliser le PNG directement comme alternative
                $payload = [
                    "printerId" => (int)$printerId,
                    "title" => "Étiquette Flightcase #{$flightcase_number} - PNG",
                    "contentType" => "png_base64",
                    "content" => base64_encode($imageData),
                    "source" => "GMAO TecaLED"
                ];
            }
            
            // Log du payload pour débogage
            Log::debug('Payload PrintNode', [
                'printerId' => (int)$printerId, 
                'content_size' => isset($imageBase64) ? strlen($imageBase64) : 0,
                'content_type' => $payload['contentType'],
                'raw_text_length' => isset($rawText) ? strlen($rawText) : 0
            ]);
            
            // Méthode alternative : Créer un fichier PDF local et l'ouvrir avec Acrobat Reader
            try {
                // Créer un nom de fichier temporaire pour le PDF
                $tempFile = tempnam(sys_get_temp_dir(), 'label_') . '.pdf';
                
                // Générer le contenu HTML pour le PDF
                $html = '<!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Étiquette Flightcase</title>
                    <style>
                        @page { size: 62mm 100mm; margin: 0; }
                        body { font-family: Arial, sans-serif; font-size: 10pt; margin: 2mm; }
                        .title { font-size: 12pt; font-weight: bold; text-align: center; margin-bottom: 3mm; }
                        .info { margin: 2mm 0; }
                        .reference { font-size: 14pt; font-weight: bold; color: #cc0000; }
                        .box { border: 1px solid #000; padding: 2mm; margin: 2mm 0; text-align: center; }
                    </style>
                </head>
                <body>
                    <div class="title">TECALED GMAO</div>
                    <div class="reference">' . $chantier->reference . '</div>
                    <div class="info">Client: ' . $chantier->client->societe . '</div>
                    <div class="box">FLIGHTCASE ' . $flightcase_number . '/' . $totalFlightcases . '</div>
                    <div class="info">Contient ' . count($dalles) . ' dalle(s)</div>
                    <div>Date: ' . date('d/m/Y') . '</div>
                </body>
                </html>';
                
                // Générer le PDF avec DomPDF
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
                $pdf->setPaper([0, 0, 175.75, 283.46], 'portrait'); // 62mm x 100mm en points
                $pdf->setOptions(['dpi' => 300]);
                
                // Enregistrer le PDF dans un fichier temporaire
                file_put_contents($tempFile, $pdf->output());
                
                // Log de l'action
                Log::info('Fichier PDF créé', [
                    'tempFile' => $tempFile,
                    'filesize' => filesize($tempFile)
                ]);
                
                // Sauvegarder le chemin du fichier pour le téléchargement
                session(['pdf_temp_file_' . $chantier->id . '_' . $flightcase_number => $tempFile]);
                
                // Demande de lancer le processus d'impression via le navigateur
                return response()->json([
                    'success' => true,
                    'message' => 'Étiquette flightcase générée - Utilisez l\'option d\'impression manuelle',
                    'action' => 'print_pdf',
                    'pdf_url' => url('/chantiers/' . $chantier->id . '/flightcase-etiquettes/' . $flightcase_number . '/download'),
                    'tempFile' => basename($tempFile)
                ]);
            } catch (Exception $e) {
                Log::error('Erreur lors de la création du PDF', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                throw $e;
            }
            
        } catch (Exception $e) {
            Log::error('Exception lors de l\'impression d\'étiquette flightcase', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'impression: ' . $e->getMessage()
            ], 500);
        }
    }
}