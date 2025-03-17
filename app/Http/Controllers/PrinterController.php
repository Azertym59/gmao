<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Services\PrinterService;
use App\Services\QzTrayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PrinterController extends Controller
{
    protected $qzTrayService;

    /**
     * Create a new controller instance.
     *
     * @param QzTrayService $qzTrayService
     */
    public function __construct(QzTrayService $qzTrayService)
    {
        $this->qzTrayService = $qzTrayService;
    }

    /**
     * Afficher la liste des imprimantes
     */
    public function index()
    {
        file_put_contents(storage_path('logs/printer_debug.log'), date('Y-m-d H:i:s') . " - Printer index method\n", FILE_APPEND);
        file_put_contents(storage_path('logs/printer_debug.log'), "User connected: " . (auth()->check() ? auth()->user()->name : 'Not logged in') . "\n", FILE_APPEND);
        file_put_contents(storage_path('logs/printer_debug.log'), "User role: " . (auth()->check() ? auth()->user()->role : 'No role') . "\n", FILE_APPEND);

        $printers = Printer::all();
        return view('printers.index', compact('printers'));
    }

    /**
     * Afficher le formulaire de création d'une imprimante
     */
    public function create()
    {
        return view('printers.create');
    }

    /**
     * Enregistrer une nouvelle imprimante
     */
    public function store(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|in:thermal,label,standard,brother_label',
            'is_default' => 'boolean',
        ];

        // Effectuer la validation
        $validatedData = $request->validate($validationRules);
        
        // S'assurer que le type a une valeur par défaut
        if (!isset($validatedData['type']) || empty($validatedData['type'])) {
            $validatedData['type'] = 'thermal'; // On utilise thermal comme valeur par défaut pour les imprimantes d'étiquettes
        }
        
        // Stocker les autres informations comme options JSON
        $options = [
            'model' => $request->input('model'),
            'connection_type' => $request->input('connection_type'),
            'ip_address' => $request->input('ip_address'),
            'dpi' => $request->input('dpi'),
            'label_width' => $request->input('label_width'),
            'label_height' => $request->input('label_height'),
            'label_format' => $request->input('label_format'),
            'brother_roll' => $request->input('brother_roll'), // Stocker la référence du rouleau Brother
            'printnode_id' => $request->input('printnode_id'), // ID de l'imprimante dans PrintNode
            'print_mode' => $request->input('print_mode'), // Mode d'impression (raster, template, escp)
            'use_bpac' => $request->has('use_bpac'), // Utiliser le SDK Brother b-PAC
            'bpac_model' => $request->input('model'), // Modèle pour b-PAC
            'bpac_tape_type' => $request->input('brother_roll'), // Type de ruban pour b-PAC
        ];
        
        $validatedData['options'] = json_encode($options);

        // Si cette imprimante est définie comme par défaut, retirer le statut par défaut des autres
        if ($request->has('is_default') && $request->is_default) {
            Printer::where('is_default', true)->update(['is_default' => false]);
        }

        // Créer l'imprimante
        $printer = Printer::create($validatedData);

        return redirect()->route('printers.index')
            ->with('success', 'Imprimante ajoutée avec succès');
    }

    /**
     * Afficher une imprimante spécifique
     */
    public function show(Printer $printer)
    {
        return view('printers.show', compact('printer'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Printer $printer)
    {
        return view('printers.edit', compact('printer'));
    }

    /**
     * Mettre à jour une imprimante
     */
    public function update(Request $request, Printer $printer)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|in:thermal,label,standard,brother_label',
            'is_default' => 'boolean',
        ];

        // Effectuer la validation
        $validatedData = $request->validate($validationRules);
        
        // S'assurer que le type a une valeur par défaut
        if (!isset($validatedData['type']) || empty($validatedData['type'])) {
            $validatedData['type'] = 'thermal'; // On utilise thermal comme valeur par défaut pour les imprimantes d'étiquettes
        }
        
        // Stocker les autres informations comme options JSON
        $options = [
            'model' => $request->input('model'),
            'connection_type' => $request->input('connection_type'),
            'ip_address' => $request->input('ip_address'),
            'port' => $request->input('port'),
            'dpi' => $request->input('dpi'),
            'label_width' => $request->input('label_width'),
            'label_height' => $request->input('label_height'),
            'label_format' => $request->input('label_format'),
            'brother_roll' => $request->input('brother_roll'), // Stocker la référence du rouleau Brother
            'printnode_id' => $request->input('printnode_id'), // ID de l'imprimante dans PrintNode
            'print_mode' => $request->input('print_mode'), // Mode d'impression (raster, template, escp)
            'use_bpac' => $request->has('use_bpac'), // Utiliser le SDK Brother b-PAC
            'bpac_model' => $request->input('model'), // Modèle pour b-PAC
            'bpac_tape_type' => $request->input('brother_roll'), // Type de ruban pour b-PAC
        ];
        
        $validatedData['options'] = json_encode($options);

        // Si cette imprimante est définie comme par défaut, retirer le statut par défaut des autres
        if ($request->has('is_default') && $request->is_default) {
            Printer::where('id', '!=', $printer->id)
                  ->where('is_default', true)
                  ->update(['is_default' => false]);
        }

        $printer->update($validatedData);

        return redirect()->route('printers.index')
            ->with('success', 'Imprimante mise à jour avec succès');
    }

    /**
     * Supprimer une imprimante
     */
    public function destroy(Printer $printer)
    {
        $printer->delete();

        return redirect()->route('printers.index')
            ->with('success', 'Imprimante supprimée avec succès');
    }

    /**
     * Tester l'impression
     */
    public function testPrint($id)
    {
        try {
            $printer = Printer::findOrFail($id);
            
            // Vérifier si l'imprimante est disponible
            $isAvailable = $printer->isAvailable();
            
            // Journaliser
            \Log::info('Test d\'impression demandé pour ' . $printer->name, [
                'printer_id' => $printer->id,
                'printer_name' => $printer->name,
                'printer_type' => $printer->type,
                'is_available' => $isAvailable
            ]);
            
            // Afficher la vue de test avec un bouton pour l'impression directe
            return view('printers.test', [
                'printer' => $printer,
                'directPrintUrl' => route('printers.direct-print', $id),
                'isAvailable' => $isAvailable
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la préparation du test d\'impression: ' . $e->getMessage(), [
                'printer_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('printers.index')
                ->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Exécuter l'impression directe
     */
    public function directPrint($id)
    {
        $result = PrinterService::directPrint($id);
        
        if ($result['success']) {
            return redirect()->route('printers.test', $id)
                ->with('success', 'Test d\'impression envoyé avec succès : ' . $result['message']);
        } else {
            return redirect()->route('printers.test', $id)
                ->with('error', 'Échec du test d\'impression : ' . $result['message']);
        }
    }

    /**
     * Définir une imprimante comme par défaut
     */
    public function setDefault(Printer $printer)
    {
        Printer::where('is_default', true)->update(['is_default' => false]);
        $printer->update(['is_default' => true]);

        return redirect()->route('printers.index')
            ->with('success', 'Imprimante "' . $printer->name . '" définie comme imprimante par défaut');
    }

    /**
     * Mettre à jour le statut des imprimantes
     */
    public function updatePrinterStatus()
    {
        $printers = Printer::all();
        
        foreach ($printers as $printer) {
            $status = $printer->isAvailable() ? 'online' : 'offline';
            $printer->update(['status' => $status]);
        }

        return redirect()->route('printers.index')
            ->with('success', 'Statut des imprimantes mis à jour');
    }

    /**
     * Tester la connexion HTTP avec l'imprimante
     */
    public function testHttp($id)
    {
        $printer = Printer::findOrFail($id);
        $results = [];
        
        // Tester les deux ports
        $ports = [80, 443];
        
        foreach ($ports as $port) {
            $protocol = ($port == 443) ? 'https' : 'http';
            $url = $protocol . '://' . $printer->ip_address . ':' . $port;
            
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                
                if ($protocol === 'https') {
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                }
                
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $error = curl_error($ch);
                curl_close($ch);
                
                $results[$port] = [
                    'success' => ($httpCode > 0),
                    'http_code' => $httpCode,
                    'error' => $error,
                    'url' => $url
                ];
            } catch (\Exception $e) {
                $results[$port] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'url' => $url
                ];
            }
        }
        
        // Tester des endpoints d'impression courants
        $endpoints = [
            '/print',
            '/ipp/print',
            '/printer/print',
            '/cgi-bin/epos/service.cgi',
            '/cgi-bin/printer/printer.cgi',
            '/api/printer/print'
        ];
        
        $endpointResults = [];
        
        // Utiliser le port qui a réussi
        $workingPort = null;
        if (isset($results[80]) && $results[80]['success']) {
            $workingPort = 80;
            $protocol = 'http';
        } elseif (isset($results[443]) && $results[443]['success']) {
            $workingPort = 443;
            $protocol = 'https';
        }
        
        if ($workingPort) {
            foreach ($endpoints as $endpoint) {
                $url = $protocol . '://' . $printer->ip_address . ':' . $workingPort . $endpoint;
                
                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLOPT_NOBODY, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                    
                    if ($protocol === 'https') {
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                    }
                    
                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $error = curl_error($ch);
                    curl_close($ch);
                    
                    $endpointResults[$endpoint] = [
                        'success' => ($httpCode > 0 && $httpCode != 404),
                        'http_code' => $httpCode,
                        'error' => $error,
                        'url' => $url
                    ];
                } catch (\Exception $e) {
                    $endpointResults[$endpoint] = [
                        'success' => false,
                        'error' => $e->getMessage(),
                        'url' => $url
                    ];
                }
            }
        }
        
        return view('printers.test_http', [
            'printer' => $printer,
            'results' => $results,
            'endpoints' => $endpointResults
        ]);
    }

    /**
     * Tester l'impression Brother
     */
    public function testBrotherPrint($id)
    {
        $printer = Printer::findOrFail($id);
        
        // Vérifier d'abord si l'imprimante est accessible
        $check = PrinterService::checkBrotherPrinter($id);
        
        if (!$check['success']) {
            return redirect()->back()->with('error', 'Imprimante inaccessible: ' . $check['message']);
        }
        
        // Tester l'impression
        $result = PrinterService::brotherPrint($id);
        
        if ($result['success']) {
            return redirect()->back()->with('success', 'Test d\'impression Brother envoyé avec succès');
        } else {
            return redirect()->back()->with('error', 'Échec du test d\'impression Brother: ' . $result['message']);
        }
    }

    /*
     * NOUVELLES MÉTHODES POUR QZ TRAY
     */

    /**
     * Test connection to QZ Tray
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function testQzTray(Request $request)
    {
        $printers = Printer::all();
        $selectedPrinter = null;
        
        // Si un ID d'imprimante est fourni, le préselectionner
        if ($request->has('printer_id')) {
            $selectedPrinter = Printer::find($request->printer_id);
        }
        
        // Si aucune imprimante n'est sélectionnée, prendre celle par défaut
        if (!$selectedPrinter) {
            $selectedPrinter = Printer::getDefault();
        }
        
        return view('printers.test', [
            'printers' => $printers,
            'selectedPrinter' => $selectedPrinter,
            'qzTrayService' => $this->qzTrayService
        ]);
    }

    /**
     * Print a test QR code using QZ Tray
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function printTestQr(Request $request)
    {
        $request->validate([
            'printer_id' => 'required|exists:printers,id'
        ]);

        try {
            $printer = Printer::findOrFail($request->printer_id);
            
            // Log détaillé
            \Log::info('QZ Tray Test Print', [
                'printer_id' => $printer->id,
                'printer_name' => $printer->name,
                'printer_type' => $printer->type,
                'printer_ip' => $printer->ip_address ?? 'N/A',
            ]);

            // Récupérer ou calculer la DPI pour la qualité d'impression
            $dpi = $printer->dpi ?? ($printer->type === 'thermal' ? 203 : 300);
            
            // Calculer la taille de papier appropriée
            $paperSize = $this->getPaperSizeByPrinterType($printer);
            
            // Générer un QR code de test avec plus de données utiles
            $testData = json_encode([
                'test' => true,
                'printer' => $printer->name,
                'timestamp' => now()->toDateTimeString(),
                'id' => uniqid()
            ]);
            
            // Préparer les options d'impression avec plus de paramètres
            $printData = $this->qzTrayService->prepareQrCodePrint(
                $printer->name,  // Nom exact de l'imprimante
                $testData,
                [
                    'size' => 250,
                    'errorCorrection' => 'H'  // Haute correction d'erreur pour meilleure lisibilité
                ],
                [
                    'copies' => 1,
                    'size' => $paperSize,
                    'dpi' => $dpi,
                    'orientation' => 'portrait',
                    'margins' => [
                        'top' => $printer->type === 'thermal' ? 1 : 5,
                        'right' => $printer->type === 'thermal' ? 1 : 5,
                        'bottom' => $printer->type === 'thermal' ? 1 : 5,
                        'left' => $printer->type === 'thermal' ? 1 : 5
                    ]
                ]
            );

            return view('printers.test_result', [
                'printer' => $printer,
                'printData' => $printData,
                'qzTrayService' => $this->qzTrayService
            ]);
            
        } catch (\Exception $e) {
            // Log de l'erreur avec plus de détails
            \Log::error('QZ Tray test error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'printer_id' => $request->printer_id ?? 'unknown',
                'user_id' => auth()->id() ?? 'guest',
                'user_agent' => $request->userAgent()
            ]);
            
            return back()->with('error', 'Erreur lors du test d\'impression: ' . $e->getMessage());
        }
    }
    
    /**
     * Déterminer la taille de papier appropriée selon le type d'imprimante
     *
     * @param Printer $printer L'imprimante
     * @return string La taille de papier pour QZ Tray
     */
    private function getPaperSizeByPrinterType(Printer $printer)
    {
        // Récupérer les options si disponibles
        $options = $printer->options ?? [];
        $labelWidth = $options['label_width'] ?? null;
        $labelHeight = $options['label_height'] ?? null;
        
        switch ($printer->type) {
            case 'thermal':
                // Utiliser la largeur spécifique si disponible, sinon valeur par défaut
                return $labelWidth ? "{$labelWidth}mm" : '57mm';
                
            case 'label':
                // Format pour étiquettes
                return $labelWidth && $labelHeight 
                       ? "{$labelWidth}mm x {$labelHeight}mm" 
                       : '62mm x 29mm';
                
            default:
                // Format standard pour imprimantes normales
                return 'A4';
        }
    }
    
    /**
     * Afficher la page de test d'étiquettes
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function testLabels(Request $request)
    {
        try {
            \Log::info('Démarrage du test d\'étiquettes', [
                'printer_id' => $request->printer_id ?? 'default',
                'user_id' => auth()->id() ?? 'guest',
                'user_agent' => $request->userAgent()
            ]);
            
            $printer = null;
            
            // Liste toutes les imprimantes disponibles pour vérification
            $allPrinters = Printer::all();
            \Log::info('Imprimantes disponibles:', [
                'count' => $allPrinters->count(),
                'ids' => $allPrinters->pluck('id')->toArray(),
                'names' => $allPrinters->pluck('name')->toArray()
            ]);
            
            // Si un ID d'imprimante est fourni, récupérer l'imprimante
            if ($request->has('printer_id')) {
                $printerId = $request->printer_id;
                \Log::info('Recherche d\'imprimante par ID', ['printer_id' => $printerId]);
                
                // Utiliser first() au lieu de findOrFail pour éviter une exception
                $printer = Printer::where('id', $printerId)->first();
                
                if (!$printer) {
                    \Log::warning('Imprimante non trouvée', ['printer_id' => $printerId]);
                    
                    // Utiliser l'imprimante par défaut si celle demandée n'existe pas
                    $printer = Printer::where('is_default', true)->first();
                    if ($printer) {
                        \Log::info('Utilisation de l\'imprimante par défaut à la place', ['default_id' => $printer->id]);
                    }
                }
            } else {
                // Sinon, récupérer l'imprimante par défaut
                \Log::info('Aucun ID d\'imprimante fourni, recherche de l\'imprimante par défaut');
                $printer = Printer::where('is_default', true)->first();
            }
            
            // Si aucune imprimante n'est trouvée, utiliser la première disponible
            if (!$printer && $allPrinters->count() > 0) {
                $printer = $allPrinters->first();
                \Log::info('Aucune imprimante par défaut, utilisation de la première disponible', ['printer_id' => $printer->id]);
            }
            
            // Si toujours aucune imprimante trouvée, rediriger vers la page de gestion
            if (!$printer) {
                \Log::warning('Aucune imprimante disponible');
                return redirect()->route('printers.index')
                    ->with('error', 'Aucune imprimante trouvée. Veuillez en configurer une.');
            }
            
            \Log::info('Imprimante sélectionnée pour le test:', [
                'id' => $printer->id,
                'name' => $printer->name,
                'is_default' => $printer->is_default
            ]);
            
            // Générer les données pour les trois formats d'étiquettes
            $testData = [
                'module' => "Test d'étiquette Module - " . now()->format('Y-m-d H:i:s'),
                'dalle' => "Test d'étiquette Dalle - " . now()->format('Y-m-d H:i:s'),
                'flightcase' => "Test d'étiquette FlightCase - " . now()->format('Y-m-d H:i:s')
            ];
            
            \Log::info('Génération des QR codes');
            
            // Générer les QR codes
            $moduleQrBase64 = base64_encode($this->qzTrayService->generateQrCode($testData['module'], 200, 'H')->getData());
            $dalleQrBase64 = base64_encode($this->qzTrayService->generateQrCode($testData['dalle'], 250, 'H')->getData());
            $flightcaseQrBase64 = base64_encode($this->qzTrayService->generateQrCode($testData['flightcase'], 300, 'H')->getData());
            
            \Log::info('Préparation des données d\'impression');
            
            // Préparer les données d'impression pour chaque format
            $modulePrintData = $this->qzTrayService->prepareQrCodePrint(
                $printer->name,
                $testData['module'],
                ['size' => 200, 'errorCorrection' => 'H'],
                [
                    'size' => '29mm',
                    'height' => '90mm',
                    'copies' => 1,
                    'dpi' => $printer->options['dpi'] ?? 300
                ]
            );
            
            $dallePrintData = $this->qzTrayService->prepareQrCodePrint(
                $printer->name,
                $testData['dalle'],
                ['size' => 250, 'errorCorrection' => 'H'],
                [
                    'size' => '38mm',
                    'height' => '90mm',
                    'copies' => 1,
                    'dpi' => $printer->options['dpi'] ?? 300
                ]
            );
            
            $flightcasePrintData = $this->qzTrayService->prepareQrCodePrint(
                $printer->name,
                $testData['flightcase'],
                ['size' => 300, 'errorCorrection' => 'H'],
                [
                    'size' => '62mm',
                    'height' => '100mm',
                    'copies' => 1,
                    'dpi' => $printer->options['dpi'] ?? 300
                ]
            );
            
            \Log::info('Rendu de la vue');
            
            // Vérifier si le fichier de vue existe pour aider au débogage
            $viewPath = resource_path('views/printers/label_test.blade.php');
            if (!file_exists($viewPath)) {
                \Log::error('Le fichier de vue n\'existe pas', ['path' => $viewPath]);
                throw new \Exception("Le fichier de vue printers.label_test n'existe pas à l'emplacement attendu.");
            }
            
            return view('printers.label_test', [
                'printer' => $printer,
                'moduleQrBase64' => $moduleQrBase64,
                'dalleQrBase64' => $dalleQrBase64,
                'flightcaseQrBase64' => $flightcaseQrBase64,
                'modulePrintData' => $modulePrintData,
                'dallePrintData' => $dallePrintData,
                'flightcasePrintData' => $flightcasePrintData,
                'qzTrayService' => $this->qzTrayService
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement de la page de test d\'étiquettes: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'printer_id' => $request->printer_id ?? 'default'
            ]);
            
            return redirect()->route('printers.index')
                ->with('error', 'Erreur lors du chargement de la page de test d\'étiquettes: ' . $e->getMessage());
        }
    }
    
    /**
     * Traiter la demande d'impression de test d'étiquettes
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function processTestLabels(Request $request)
    {
        $request->validate([
            'printer_id' => 'required|exists:printers,id',
            'test_formats' => 'required|array',
            'test_formats.*' => 'in:module,dalle,flightcase'
        ]);
        
        $printer = Printer::findOrFail($request->printer_id);
        
        // Rediriger vers la page de test d'étiquettes
        return redirect()->route('printers.test-labels', ['printer_id' => $printer->id])
            ->with('formats', $request->test_formats);
    }
}