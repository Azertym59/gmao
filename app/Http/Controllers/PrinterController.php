<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printer;
use App\Services\PrinterService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class PrinterController extends Controller
{
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
            'is_default' => 'boolean'
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
        
        // Afficher la vue de test avec un bouton pour l'impression directe
        return view('printers.test', [
            'printer' => $printer,
            'directPrintUrl' => route('printers.direct-print', $id)
        ]);
    } catch (\Exception $e) {
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
}