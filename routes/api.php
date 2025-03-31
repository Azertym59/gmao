<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DalleController;
use App\Http\Controllers\Api\ProduitController;
use App\Http\Controllers\Api\CartesReceptionController;
use App\Http\Controllers\Api\PrintController;
use App\Http\Controllers\Api\PrintNodeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes d'API pour l'autocomplétion
Route::get('/clients/search', [ClientController::class, 'search'])->name('api.clients.search');

// Mise à jour rapide du numéro de dalle
Route::post('/dalles/{id}/update-numero', [DalleController::class, 'updateNumero'])->name('dalles.update.numero');

// Routes API pour les produits
Route::get('/produits', [ProduitController::class, 'index']);
Route::get('/produits/catalogue', [ProduitController::class, 'catalogue']);
Route::get('/produits/sync-catalogue', [ProduitController::class, 'syncProduitsCatalogue']);

// Routes API pour les cartes de réception
Route::get('/cartes-reception', [CartesReceptionController::class, 'getCartesForElectronique']);

// Routes API pour les imprimantes
Route::get('/printers', function() {
    $printers = \App\Models\Printer::all()->map(function($printer) {
        return [
            'id' => $printer->id,
            'name' => $printer->name,
            'type' => $printer->type,
            'default' => $printer->is_default,
        ];
    });
    
    return response()->json([
        'success' => true,
        'printers' => $printers
    ]);
});

// Route pour récupérer les imprimantes ezeep disponibles
Route::get('/ezeep/printers', function(Request $request) {
    $ezeepService = app(\App\Services\EzeepService::class);
    
    try {
        // Vérifier la configuration ezeep
        $ezeepConfig = [
            'api_url' => config('services.ezeep.api_url'),
            'client_id_set' => !empty(config('services.ezeep.client_id')),
            'client_secret_set' => !empty(config('services.ezeep.client_secret')),
            'printer_id_set' => !empty(config('services.ezeep.printer_id')),
        ];
        
        // Si la configuration n'est pas complète, retourner une erreur
        if (!$ezeepConfig['client_id_set'] || !$ezeepConfig['client_secret_set']) {
            return response()->json([
                'success' => false,
                'message' => 'Configuration ezeep incomplète. Veuillez définir EZEEP_CLIENT_ID et EZEEP_CLIENT_SECRET dans le fichier .env',
                'config' => $ezeepConfig
            ], 400);
        }
        
        // Récupérer les imprimantes
        $printers = $ezeepService->getPrinters();
        return response()->json([
            'success' => true,
            'printers' => $printers
        ]);
    } catch (\Exception $e) {
        // Journaliser l'erreur pour le débogage
        \Illuminate\Support\Facades\Log::error('Erreur ezeep/printers', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la récupération des imprimantes ezeep: ' . $e->getMessage(),
            'api_url' => config('services.ezeep.api_url')
        ], 500);
    }
});

// Route API pour récupérer un datasheet LED par ID
Route::get('/led-datasheet/{id}', function (Request $request, $id) {
    $datasheet = \App\Models\LedDatasheet::findOrFail($id);
    return response()->json([
        'id' => $datasheet->id,
        'type' => $datasheet->type,
        'color' => $datasheet->color,
        'reference' => $datasheet->reference,
        'nb_poles' => $datasheet->nb_poles,
        'disposition' => $datasheet->disposition,
        'position_chanfrein' => $datasheet->position_chanfrein,
        'notes' => $datasheet->notes,
        'image_data' => $datasheet->image_data
    ]);
});

// API pour l'autocomplétion des marques et modèles
Route::get('/marques', function(Request $request) {
    $term = $request->input('term', '');
    
    $marques = \App\Models\ProduitCatalogue::select('marque')
        ->where('marque', 'like', "%{$term}%")
        ->distinct()
        ->orderBy('marque')
        ->limit(10)
        ->get()
        ->map(function($item) {
            return [
                'id' => $item->marque,
                'text' => $item->marque
            ];
        });
    
    return response()->json($marques);
});

Route::get('/modeles', function(Request $request) {
    $term = $request->input('term', '');
    $marque = $request->input('marque', '');
    
    $query = \App\Models\ProduitCatalogue::select('modele', 'pitch', 'utilisation')
        ->where('modele', 'like', "%{$term}%")
        ->distinct();
    
    if (!empty($marque)) {
        $query->where('marque', $marque);
    }
    
    $modeles = $query->orderBy('modele')
        ->limit(10)
        ->get()
        ->map(function($item) {
            return [
                'id' => $item->modele,
                'text' => $item->modele,
                'pitch' => $item->pitch,
                'utilisation' => $item->utilisation
            ];
        });
    
    return response()->json($modeles);
});

// Routes pour l'impression d'étiquettes
Route::post('/print/test-label', [PrintController::class, 'testLabelPrint'])->name('api.print.test-label');

// Routes PrintNode
Route::prefix('printnode')->group(function () {
    Route::get('/printers', [PrintNodeController::class, 'listPrinters']);
    Route::post('/test', [PrintNodeController::class, 'testPrint']);
    Route::get('/job/{jobId}', [PrintNodeController::class, 'checkPrintJobStatus']);
    Route::post('/label', [PrintNodeController::class, 'printLabel']);
});

// Route pour enregistrer un heartbeat de présence sur une intervention
Route::post('/intervention-heartbeat', function (Request $request) {
    $interventionId = $request->input('intervention_id');
    $moduleId = $request->input('module_id');
    
    if (!$interventionId || !$moduleId) {
        return response()->json([
            'success' => false,
            'message' => 'ID d\'intervention et ID de module requis'
        ], 400);
    }
    
    // Vérifier que l'intervention existe
    $intervention = \App\Models\Intervention::find($interventionId);
    if (!$intervention) {
        return response()->json([
            'success' => false,
            'message' => 'Intervention non trouvée'
        ], 404);
    }
    
    // Vérifier que le module correspond bien à l'intervention
    if ($intervention->module_id != $moduleId) {
        return response()->json([
            'success' => false,
            'message' => 'Le module ne correspond pas à cette intervention'
        ], 400);
    }
    
    // Mettre à jour le verrou avec un heartbeat
    $lockFile = storage_path('app/locks/module_' . $moduleId . '.lock');
    $lockDir = dirname($lockFile);
    
    // Créer le dossier des verrous s'il n'existe pas
    if (!file_exists($lockDir)) {
        mkdir($lockDir, 0777, true);
    }
    
    // Écrire la date actuelle dans le fichier pour mettre à jour son timestamp
    file_put_contents($lockFile, json_encode([
        'intervention_id' => $interventionId,
        'user_id' => auth()->id(),
        'timestamp' => time(),
        'is_heartbeat' => true
    ]));
    
    return response()->json([
        'success' => true,
        'message' => 'Heartbeat enregistré',
        'timestamp' => time()
    ]);
})->name('api.intervention-heartbeat');

// Route pour vérifier l'état des modules (occupés ou non)
Route::get('/check-modules-status', function (Request $request) {
    // Récupérer les IDs des modules à vérifier
    $moduleIds = explode(',', $request->input('ids', ''));
    
    // Si aucun ID n'est fourni, retourner une erreur
    if (empty($moduleIds)) {
        return response()->json([
            'success' => false,
            'message' => 'Aucun ID de module fourni'
        ], 400);
    }
    
    // Récupérer les modules demandés
    $modules = \App\Models\Module::whereIn('id', $moduleIds)->get();
    
    // Créer un tableau de résultats avec l'état d'occupation de chaque module
    $results = [];
    foreach ($modules as $module) {
        $isOccupied = $module->isReallyOccupied();
        $activeIntervention = $module->getActiveIntervention();
        
        $results[$module->id] = [
            'id' => $module->id,
            'is_occupied' => $isOccupied,
            'active_intervention_id' => $activeIntervention ? $activeIntervention->id : null,
            'technician_id' => $activeIntervention ? $activeIntervention->technicien_id : null,
            'technician_name' => $activeIntervention ? ($activeIntervention->technicien ? $activeIntervention->technicien->name : 'Inconnu') : null,
            'state' => $module->etat
        ];
    }
    
    return response()->json([
        'success' => true,
        'modules' => $results
    ]);
})->name('api.check-modules-status');

// Route de diagnostic d'impression
Route::get('/print/diagnostic', function() {
    // Configuration PrintNode
    $printNodeApiKey = config('services.printnode.api_key');
    $printNodePrinterId = config('services.printnode.printer_id');
    
    // Configuration ezeep
    $ezeepClientId = config('services.ezeep.client_id');
    $ezeepClientSecret = config('services.ezeep.client_secret');
    $ezeepPrinterId = config('services.ezeep.printer_id');
    
    // Masquer partiellement les clés API pour des raisons de sécurité
    $maskedPrintNodeKey = !empty($printNodeApiKey) ? substr($printNodeApiKey, 0, 4) . '...' . substr($printNodeApiKey, -4) : '';
    $maskedEzeepClientId = !empty($ezeepClientId) ? substr($ezeepClientId, 0, 4) . '...' . substr($ezeepClientId, -4) : '';
    $maskedEzeepClientSecret = !empty($ezeepClientSecret) ? substr($ezeepClientSecret, 0, 4) . '...' . substr($ezeepClientSecret, -4) : '';
    
    // Récupérer toutes les imprimantes avec leurs options
    $printers = \App\Models\Printer::all()->map(function($printer) {
        $options = $printer->options ?? [];
        return [
            'id' => $printer->id,
            'name' => $printer->name,
            'type' => $printer->type,
            'is_default' => $printer->is_default,
            'options' => $options,
            'printnode_id' => $options['printnode_id'] ?? null,
            'ezeep_id' => $options['ezeep_id'] ?? null,
        ];
    });
    
    return response()->json([
        'success' => true,
        'printnode' => [
            'api_key_configured' => !empty($printNodeApiKey),
            'api_key_masked' => $maskedPrintNodeKey,
            'printer_id_configured' => !empty($printNodePrinterId) && $printNodePrinterId !== '12345',
            'printer_id' => $printNodePrinterId
        ],
        'ezeep' => [
            'client_id_configured' => !empty($ezeepClientId),
            'client_id_masked' => $maskedEzeepClientId,
            'client_secret_configured' => !empty($ezeepClientSecret),
            'client_secret_masked' => $maskedEzeepClientSecret,
            'printer_id_configured' => !empty($ezeepPrinterId),
            'printer_id' => $ezeepPrinterId
        ],
        'local_printers' => $printers
    ]);
})->name('api.print.diagnostic');