<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController; // Ajoutez cette ligne
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrinterController;  // Ajoutez cette ligne
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\TechnicienMiddleware;

// Routes pour la configuration initiale
Route::get('/setup/admin', [App\Http\Controllers\SetupController::class, 'showAdminSetup'])->name('setup.admin');
Route::post('/setup/admin', [App\Http\Controllers\SetupController::class, 'createAdmin'])->name('setup.admin.store');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
//    Route::get('/dashboard', function () {
//        return view('dashboard');
//    })->name('dashboard');
    
    Route::resource('clients', ClientController::class); // Modifiez cette ligne
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('chantiers', App\Http\Controllers\ChantierController::class);
    Route::resource('produits', App\Http\Controllers\ProduitController::class);
    Route::resource('dalles', App\Http\Controllers\DalleController::class);
    Route::resource('modules', App\Http\Controllers\ModuleController::class);
    Route::resource('interventions', App\Http\Controllers\InterventionController::class);
    Route::get('/produits/{produit}/modules/mass-create', [App\Http\Controllers\ModuleController::class, 'showMassCreateForm'])->name('modules.mass-create.form');
    Route::post('/produits/{produit}/modules/mass-create', [App\Http\Controllers\ModuleController::class, 'processMassCreate'])->name('modules.mass-create.process');
    Route::get('/chantiers/create/step1', [App\Http\Controllers\ChantierController::class, 'createStep1'])->name('chantiers.create.step1');
    Route::post('/chantiers/create/step1', [App\Http\Controllers\ChantierController::class, 'storeStep1'])->name('chantiers.store.step1');
    Route::get('/chantiers/create/step2', [App\Http\Controllers\ChantierController::class, 'createStep2'])->name('chantiers.create.step2');
    Route::post('/chantiers/create/step2', [App\Http\Controllers\ChantierController::class, 'storeStep2'])->name('chantiers.store.step2');
    Route::get('/chantiers/create/step3', [App\Http\Controllers\ChantierController::class, 'createStep3'])->name('chantiers.create.step3');
    Route::post('/chantiers/create/step3', [App\Http\Controllers\ChantierController::class, 'storeStep3'])->name('chantiers.store.step3');
    Route::resource('produits-catalogue', App\Http\Controllers\ProduitCatalogueController::class);
    Route::post('/interventions/{intervention}/pause', [App\Http\Controllers\InterventionController::class, 'pause'])->name('interventions.pause');
    Route::post('/interventions/{intervention}/resume', [App\Http\Controllers\InterventionController::class, 'resume'])->name('interventions.resume');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Routes pour les rapports
    Route::get('/rapports', [App\Http\Controllers\RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/chantier/{chantier}', [App\Http\Controllers\RapportController::class, 'genererRapportChantier'])->name('rapports.chantier');
    Route::get('/rapports/intervention/{intervention}', [App\Http\Controllers\RapportController::class, 'genererFicheIntervention'])->name('rapports.intervention');
    Route::get('/rapports/performance', [App\Http\Controllers\RapportController::class, 'genererRapportPerformance'])->name('rapports.performance');
    Route::get('/rapports/inventaire', [App\Http\Controllers\RapportController::class, 'genererRapportInventaire'])->name('rapports.inventaire');
    Route::get('/rapports/statistiques', [App\Http\Controllers\RapportController::class, 'genererRapportStatistiques'])->name('rapports.statistiques');
    // Routes pour les QR codes
    Route::prefix('qrcode')->name('qrcode.')->group(function () {
    // Routes pour les chantiers
    Route::get('/chantier/{id}/print', [App\Http\Controllers\QrCode\ChantierQrCodeController::class, 'printLabel'])->name('chantier.print');
    Route::get('/chantier/{id}', [App\Http\Controllers\QrCode\ChantierQrCodeController::class, 'show'])->name('chantier.show');

    // Route pour la page de scan
    Route::get('/scan', function () {
        return view('qrcodes.scan');
    })->name('qrcode.scan');
    }); 

// Pour admins ET techniciens
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::resource('printers', PrinterController::class);
});


    // Routes pour les dalles
    Route::get('/dalle/{id}/print', [App\Http\Controllers\QrCode\DalleQrCodeController::class, 'printLabel'])->name('dalle.print');
    Route::get('/dalle/{id}', [App\Http\Controllers\QrCode\DalleQrCodeController::class, 'show'])->name('dalle.show');
    
    // Routes pour les modules
    Route::get('/module/{id}/print', [App\Http\Controllers\QrCode\ModuleQrCodeController::class, 'printLabel'])->name('module.print');
    Route::get('/module/{id}', [App\Http\Controllers\QrCode\ModuleQrCodeController::class, 'show'])->name('module.show');

    // Route pour le tableau de bord technicien
    Route::get('/technicien/dashboard', [App\Http\Controllers\TechnicienDashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('technicien.dashboard');

    // Routes pour les notifications
    Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread', [App\Http\Controllers\NotificationController::class, 'getUnreadNotifications'])->name('notifications.unread');
});

    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
            
            // Gestion des utilisateurs
            Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
            Route::get('/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('users.create');
            Route::post('/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
            Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
            Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
            Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');

        });
    }); // Fermeture du groupe d'admin middleware
}); // Fermeture du groupe middleware auth, verified

require __DIR__.'/auth.php';