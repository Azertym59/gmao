<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChantierController;
use App\Http\Controllers\DalleController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProduitCatalogueController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\QrCode\ChantierQrCodeController;
use App\Http\Controllers\QrCode\DalleQrCodeController;
use App\Http\Controllers\QrCode\ModuleQrCodeController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\TechnicienDashboardController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::get('/', function () {
    return view('welcome');
});

// Routes pour la configuration initiale
Route::get('/setup/admin', [SetupController::class, 'showAdminSetup'])->name('setup.admin');
Route::post('/setup/admin', [SetupController::class, 'createAdmin'])->name('setup.admin.store');

// Routes protégées par authentification
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Gestion des clients
    Route::resource('clients', ClientController::class);
    
    // Gestion des chantiers
    Route::resource('chantiers', ChantierController::class);
    Route::get('/chantiers/create/step1', [ChantierController::class, 'createStep1'])->name('chantiers.create.step1');
    Route::post('/chantiers/create/step1', [ChantierController::class, 'storeStep1'])->name('chantiers.store.step1');
    Route::get('/chantiers/create/step2', [ChantierController::class, 'createStep2'])->name('chantiers.create.step2');
    Route::post('/chantiers/create/step2', [ChantierController::class, 'storeStep2'])->name('chantiers.store.step2');
    Route::get('/chantiers/create/step3', [ChantierController::class, 'createStep3'])->name('chantiers.create.step3');
    Route::post('/chantiers/create/step3', [ChantierController::class, 'storeStep3'])->name('chantiers.store.step3');
    
    // Gestion des produits
    Route::resource('produits', ProduitController::class);
    Route::resource('produits-catalogue', ProduitCatalogueController::class);
    
    // Gestion des dalles et modules
    Route::resource('dalles', DalleController::class);
    Route::resource('modules', ModuleController::class);
    Route::get('/produits/{produit}/modules/mass-create', [ModuleController::class, 'showMassCreateForm'])->name('modules.mass-create.form');
    Route::post('/produits/{produit}/modules/mass-create', [ModuleController::class, 'processMassCreate'])->name('modules.mass-create.process');
    
    // Gestion des interventions
    Route::resource('interventions', InterventionController::class);
    Route::post('/interventions/{intervention}/pause', [InterventionController::class, 'pause'])->name('interventions.pause');
    Route::post('/interventions/{intervention}/resume', [InterventionController::class, 'resume'])->name('interventions.resume');
    
    // Routes pour les rapports
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/chantier/{chantier}', [RapportController::class, 'genererRapportChantier'])->name('rapports.chantier');
    Route::get('/rapports/intervention/{intervention}', [RapportController::class, 'genererFicheIntervention'])->name('rapports.intervention');
    Route::get('/rapports/performance', [RapportController::class, 'genererRapportPerformance'])->name('rapports.performance');
    Route::get('/rapports/inventaire', [RapportController::class, 'genererRapportInventaire'])->name('rapports.inventaire');
    Route::get('/rapports/statistiques', [RapportController::class, 'genererRapportStatistiques'])->name('rapports.statistiques');
    
    // Routes pour les QR codes
    Route::prefix('qrcode')->name('qrcode.')->group(function () {
        // Routes pour les chantiers
        Route::get('/chantier/{id}/print', [ChantierQrCodeController::class, 'printLabel'])->name('chantier.print');
        Route::get('/chantier/{id}', [ChantierQrCodeController::class, 'show'])->name('chantier.show');
        
        // Routes pour les dalles
        Route::get('/dalle/{id}/print', [DalleQrCodeController::class, 'printLabel'])->name('dalle.print');
        Route::get('/dalle/{id}', [DalleQrCodeController::class, 'show'])->name('dalle.show');
        
        // Routes pour les modules
        Route::get('/module/{id}/print', [ModuleQrCodeController::class, 'printLabel'])->name('module.print');
        Route::get('/module/{id}', [ModuleQrCodeController::class, 'show'])->name('module.show');
        
        // Route pour la page de scan
        Route::get('/scan', function () {
            return view('qrcodes.scan');
        })->name('scan');
    });
    
    // Routes pour les techniciens
    Route::get('/technicien/dashboard', [TechnicienDashboardController::class, 'index'])->name('technicien.dashboard');
    
    // Routes pour les notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications'])->name('notifications.unread');
    
    // Routes pour les administrateurs seulement
    Route::middleware([AdminMiddleware::class])->group(function () {
        // Gestion des imprimantes (admin uniquement)
        Route::resource('printers', PrinterController::class);
        Route::get('/printers/{id}/test', [App\Http\Controllers\PrinterController::class, 'testPrint'])->name('printers.test');
        Route::get('/printers/{id}/direct-print', [App\Http\Controllers\PrinterController::class, 'directPrint'])->name('printers.direct-print');
        Route::get('/printers/{id}/test-http', [App\Http\Controllers\PrinterController::class, 'testHttp'])->name('printers.test_http');
        Route::get('/printers/{id}/test-brother', [App\Http\Controllers\PrinterController::class, 'testBrotherPrint'])->name('printers.test-brother');
        
        // Routes d'administration
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
            
            // Gestion des utilisateurs
            Route::get('/users', [AdminController::class, 'users'])->name('users');
            Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
            Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
            Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
            Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
            Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        });
    });
});

require __DIR__.'/auth.php';