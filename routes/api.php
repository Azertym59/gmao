<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DalleController;
use App\Http\Controllers\Api\PrintController;
use App\Http\Controllers\Api\ProduitController;
use App\Http\Controllers\Api\CartesReceptionController;

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

// Routes existantes
Route::post('/print/job', [PrintController::class, 'createPrintJob']);
Route::get('/print/job/{id}', [PrintController::class, 'getPrintJob']);
Route::put('/print/job/{id}', [PrintController::class, 'updatePrintJob']);

Route::prefix('print-jobs')->group(function () {
    // Gestion des jobs d'impression
    Route::post('/create', [PrintController::class, 'createJob']);
    Route::get('/pending', [PrintController::class, 'getPendingJobs']);
    Route::post('/{id}/status', [PrintController::class, 'updateJobStatus']);
    Route::get('/history', [PrintController::class, 'getHistory']);
    
    // Tests et fonctionnalités avancées
    Route::post('/test', [PrintController::class, 'testPrint']);
    Route::post('/qrcode', [PrintController::class, 'createQrCodePrintJob']);
});

Route::prefix('printers')->group(function () {
    // Récupération des imprimantes disponibles
    Route::get('/status', function(Request $request) {
        $printers = \App\Models\Printer::all()->map(function($printer) {
            return [
                'id' => $printer->id,
                'name' => $printer->name,
                'type' => $printer->type,
                'is_available' => $printer->is_online,
                'is_default' => $printer->is_default,
                'last_checked_at' => $printer->last_checked_at ? $printer->last_checked_at->toIso8601String() : null
            ];
        });
        
        return response()->json([
            'printers' => $printers,
            'timestamp' => now()->toIso8601String()
        ]);
    });
    
    // Test de connectivité avec une imprimante spécifique
    Route::get('/{id}/test-connection', function(Request $request, $id) {
        $printer = \App\Models\Printer::findOrFail($id);
        $qzTrayService = app(\App\Services\QzTrayService::class);
        
        $status = $qzTrayService->checkPrinterStatus($printer);
        
        return response()->json([
            'printer' => $printer->name,
            'status' => $status
        ]);
    });
});

// Routes API pour les produits
Route::get('/produits', [ProduitController::class, 'index']);
Route::get('/produits/catalogue', [ProduitController::class, 'catalogue']);

// Routes API pour les cartes de réception
Route::get('/cartes-reception', [CartesReceptionController::class, 'getCartesForElectronique']);

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