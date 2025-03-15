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
            'model' => 'required|string|max:255',
            'connection_type' => 'nullable|string',
            'ip_address' => 'nullable|string|max:45',
            'is_default' => 'boolean',
            'dpi' => 'nullable|numeric',
            'type' => 'nullable|string|in:thermal,label,standard',
        ];

        // Ajouter des règles conditionnelles pour les dimensions
        if ($request->input('label_format') === 'custom') {
            $validationRules['label_width'] = 'required|numeric|min:1';
            $validationRules['label_height'] = 'required|numeric|min:1';
        } else {
            $validationRules['label_width'] = 'nullable|numeric';
            $validationRules['label_height'] = 'nullable|numeric';
        }

        // Effectuer la validation
        $validatedData = $request->validate($validationRules);

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
        $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'ip_address' => 'nullable|string|max:45',
            'port' => 'nullable|numeric',
            'label_width' => 'required|numeric',
            'label_height' => 'required|numeric',
            'is_default' => 'boolean',
            'type' => 'nullable|string|in:thermal,label,standard',
        ]);

        // Si cette imprimante est définie comme par défaut, retirer le statut par défaut des autres
        if ($request->has('is_default') && $request->is_default) {
            Printer::where('id', '!=', $printer->id)
                  ->where('is_default', true)
                  ->update(['is_default' => false]);
        }

        $printer->update($request->all());

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
        
        return view('printers.test', [
            'printers' => $printers,
            'selectedPrinter' => $selectedPrinter
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
        switch ($printer->type) {
            case 'thermal':
                // Utiliser la largeur spécifique si disponible, sinon valeur par défaut
                return $printer->label_width ? "{$printer->label_width}mm" : '57mm';
                
            case 'label':
                // Format pour étiquettes
                return $printer->label_width && $printer->label_height 
                       ? "{$printer->label_width}mm x {$printer->label_height}mm" 
                       : '62mm x 29mm';
                
            default:
                // Format standard pour imprimantes normales
                return 'A4';
        }
    }
}