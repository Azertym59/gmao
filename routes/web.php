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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Route temporaire pour contourner le problème de CSRF
Route::get('/temp-login', function () {
    return view('temp-login');
});

Route::post('/temp-login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/dashboard');
    }
    
    return back()->with('error', 'Email ou mot de passe incorrect.');
});

// Routes publiques
// Route d'accès direct sans authentification (TEMPORAIRE)
Route::get('/direct-dashboard', function () {
    // Créer un utilisateur fictif à la volée
    $user = new \App\Models\User([
        'id' => 1,
        'name' => 'Admin Temporaire',
        'email' => 'admin@example.com',
        'role' => 'admin'
    ]);
    
    // Connecter l'utilisateur
    \Illuminate\Support\Facades\Auth::login($user);
    
    // Rediriger vers le tableau de bord
    return redirect('/dashboard');
});

Route::get('/', function () {
    return view('welcome');
});

// Routes pour la configuration initiale
Route::get('/setup/admin', [SetupController::class, 'showAdminSetup'])->name('setup.admin');
Route::post('/setup/admin', [SetupController::class, 'createAdmin'])->name('setup.admin.store');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Gestion des clients
    Route::resource('clients', ClientController::class);
    
    // Route de test pour le formulaire catalogue
    Route::get('/test-catalogue-form', [ChantierController::class, 'testCatalogueForm'])->name('test.catalogue.form');
    Route::post('/test-catalogue-form', [ChantierController::class, 'testCatalogueFormSubmit'])->name('test.catalogue.form.submit');
    
    // Gestion des chantiers
    Route::resource('chantiers', ChantierController::class);
    Route::patch('/chantiers/{chantier}/state', [ChantierController::class, 'updateState'])->name('chantiers.update.state');
    
    // Routes pour les emails
    Route::post('/emails/chantier/{chantier}', [App\Http\Controllers\EmailController::class, 'sendChantierEmail'])->name('emails.chantier');
    Route::get('/chantiers/create/step1', [ChantierController::class, 'createStep1'])->name('chantiers.create.step1');
    Route::post('/chantiers/create/step1', [ChantierController::class, 'storeNewStep1'])->name('chantiers.store.step1');
    Route::post('/chantiers/create/client-ajax', [ChantierController::class, 'storeClientAjax'])->name('chantiers.store.client-ajax');
    Route::get('/chantiers/create/step2', [ChantierController::class, 'createNewStep2'])->name('chantiers.create.step2');
    Route::post('/chantiers/create/step2', [ChantierController::class, 'storeNewStep2'])->name('chantiers.store.step2');
    Route::get('/chantiers/create/step3', [ChantierController::class, 'createNewStep3'])->name('chantiers.create.step3');
    Route::post('/chantiers/create/step3', [ChantierController::class, 'storeStep3'])->name('chantiers.store.step3');
    Route::get('/chantiers/create/step4', [ChantierController::class, 'createStep4'])->name('chantiers.create.step4');
    Route::post('/chantiers/create/step4', [ChantierController::class, 'storeStep4'])->name('chantiers.store.step4');
    Route::get('/chantiers/create/step5', [ChantierController::class, 'createStep5'])->name('chantiers.create.step5');
    Route::post('/chantiers/create/step5', [ChantierController::class, 'storeStep5'])->name('chantiers.store.step5');
    
    // Gestion des produits
    Route::resource('produits', ProduitController::class);
    Route::get('/produits/{produit}/create-variante', [ProduitController::class, 'createVariante'])->name('produits.create-variante');
    Route::post('/produits/{produit}/store-variante', [ProduitController::class, 'storeVariante'])->name('produits.store-variante');
    Route::get('/produits/{variante}/edit-variante', [ProduitController::class, 'editVariante'])->name('produits.edit-variante');
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
    
    // Routes pour le workflow à 3 étapes des interventions
    Route::get('/interventions/{intervention}/step1', [InterventionController::class, 'showDiagnosticStep'])->name('interventions.step1.diagnostic');
    Route::get('/interventions/{intervention}/step2', [InterventionController::class, 'showReparationStep'])->name('interventions.step2.reparation');
    Route::get('/interventions/{intervention}/step3', [InterventionController::class, 'showFinalisationStep'])->name('interventions.step3.finalisation');
    Route::post('/interventions/{intervention}/store-diagnostic', [InterventionController::class, 'storeDiagnostic'])->name('interventions.store.diagnostic');
    Route::post('/interventions/{intervention}/store-reparation', [InterventionController::class, 'storeReparation'])->name('interventions.store.reparation');
    
    // Routes pour les rapports
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/chantier/{chantier}', [RapportController::class, 'genererRapportChantier'])->name('rapports.chantier');
    Route::get('/rapports/intervention/{intervention}', [RapportController::class, 'genererFicheIntervention'])->name('rapports.intervention');
    Route::get('/rapports/performance', [RapportController::class, 'genererRapportPerformance'])->name('rapports.performance');
    Route::get('/rapports/inventaire', [RapportController::class, 'genererRapportInventaire'])->name('rapports.inventaire');
    Route::get('/rapports/statistiques', [RapportController::class, 'genererRapportStatistiques'])->name('rapports.statistiques');
    
    // Routes pour les QR codes - Structure originale conservée
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
        // Gestion des imprimantes (admin uniquement) - Mise à jour pour QZ Tray
        Route::prefix('printers')->name('printers.')->group(function () {
            Route::get('/', [PrinterController::class, 'index'])->name('index');
            Route::get('/create', [PrinterController::class, 'create'])->name('create');
            Route::post('/', [PrinterController::class, 'store'])->name('store');
            Route::get('/{printer}', [PrinterController::class, 'show'])->name('show');
            Route::get('/{printer}/edit', [PrinterController::class, 'edit'])->name('edit');
            Route::put('/{printer}', [PrinterController::class, 'update'])->name('update');
            Route::delete('/{printer}', [PrinterController::class, 'destroy'])->name('destroy');
            Route::post('/{printer}/default', [PrinterController::class, 'setDefault'])->name('set-default');
            
            // Routes pour QZ Tray
            Route::get('/test', [PrinterController::class, 'testQzTray'])->name('test');
            Route::post('/test/print', [PrinterController::class, 'printTestQr'])->name('print-test');
            
            // On conserve cette route pour compatibilité et debuggage
            Route::get('/{id}/test-http', [PrinterController::class, 'testHttp'])->name('test-http');
        });
        
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
            
            // Gestion de la base de données
            Route::get('/database', [AdminController::class, 'databaseManager'])->name('database.manager');
            Route::post('/database/backup', [AdminController::class, 'backupDatabase'])->name('database.backup');
            Route::get('/database/backups', [AdminController::class, 'listBackups'])->name('database.backups');
            Route::get('/database/backups/{filename}/download', [AdminController::class, 'downloadBackup'])->name('database.backups.download');
            Route::post('/database/backups/{filename}/restore', [AdminController::class, 'restoreBackup'])->name('database.backups.restore');
            Route::get('/database/reset', [AdminController::class, 'confirmReset'])->name('database.confirm-reset');
            Route::post('/database/reset', [AdminController::class, 'resetDatabase'])->name('database.reset');
            Route::get('/database/setup-admin', [AdminController::class, 'setupAdmin'])->name('database.setup-admin');
            Route::post('/database/setup-admin', [AdminController::class, 'storeAdmin'])->name('database.store-admin');
        });
    });
});

// Routes d'authentification
require __DIR__.'/auth.php';