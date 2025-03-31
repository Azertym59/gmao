<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
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
use App\Http\Middleware\ClientMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

// Routes publiques
// Route d'accès direct sans authentification (TEMPORAIRE)
Route::get('/direct-access', function () {
    return view('welcome');
})->name('direct-access');

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification
Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes authentifiées
Route::middleware(['auth'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour les techniciens
    Route::middleware(['auth', 'technicien'])->group(function () {
        Route::get('/technicien/dashboard', [TechnicienDashboardController::class, 'index'])->name('technicien.dashboard');
    });
    
    // Routes pour les administrateurs
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Gestion des utilisateurs
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        
        // Gestion de la base de données
        Route::get('/admin/database', [AdminController::class, 'databaseManager'])->name('admin.database.manager');
        Route::get('/admin/database/backups', [AdminController::class, 'listBackups'])->name('admin.database.backups');
        Route::post('/admin/database/backup', [AdminController::class, 'backupDatabase'])->name('admin.database.backup');
        Route::get('/admin/database/backups/{filename}/download', [AdminController::class, 'downloadBackup'])->name('admin.database.backup.download');
        Route::post('/admin/database/backups/{filename}/restore', [AdminController::class, 'restoreBackup'])->name('admin.database.backup.restore');
        Route::get('/admin/database/reset', [AdminController::class, 'confirmReset'])->name('admin.database.reset.confirm');
        Route::post('/admin/database/reset', [AdminController::class, 'resetDatabase'])->name('admin.database.reset');
        Route::get('/admin/database/setup-admin', [AdminController::class, 'setupAdmin'])->name('admin.database.setup-admin');
        Route::post('/admin/database/setup-admin', [AdminController::class, 'storeAdmin'])->name('admin.database.setup-admin.store');
        
        // Gestion des templates d'étiquettes
        Route::get('/admin/etiquettes', [App\Http\Controllers\EtiquetteTemplateController::class, 'index'])->name('admin.etiquettes.index');
        Route::get('/admin/etiquettes/create', [App\Http\Controllers\EtiquetteTemplateController::class, 'create'])->name('admin.etiquettes.create');
        Route::post('/admin/etiquettes', [App\Http\Controllers\EtiquetteTemplateController::class, 'store'])->name('admin.etiquettes.store');
        Route::get('/admin/etiquettes/{template}/edit', [App\Http\Controllers\EtiquetteTemplateController::class, 'edit'])->name('admin.etiquettes.edit');
        Route::put('/admin/etiquettes/{template}', [App\Http\Controllers\EtiquetteTemplateController::class, 'update'])->name('admin.etiquettes.update');
        Route::delete('/admin/etiquettes/{template}', [App\Http\Controllers\EtiquetteTemplateController::class, 'destroy'])->name('admin.etiquettes.destroy');
        Route::post('/admin/etiquettes/{template}/set-default', [App\Http\Controllers\EtiquetteTemplateController::class, 'setDefault'])->name('admin.etiquettes.set-default');
        Route::get('/admin/etiquettes/{template}/preview', [App\Http\Controllers\EtiquetteTemplateController::class, 'preview'])->name('admin.etiquettes.preview');
        Route::post('/admin/etiquettes/{template}/test-print', [App\Http\Controllers\EtiquetteTemplateController::class, 'testPrint'])->name('admin.etiquettes.test-print');
        Route::get('/admin/etiquettes/{template}/duplicate', [App\Http\Controllers\EtiquetteTemplateController::class, 'duplicate'])->name('admin.etiquettes.duplicate');
        
        // API pour l'éditeur d'étiquettes
        Route::prefix('api/etiquettes')->group(function () {
            Route::get('/variables/{type}', [App\Http\Controllers\EtiquetteTemplateController::class, 'getVariables'])->name('api.etiquettes.variables');
            Route::post('/render-preview', [App\Http\Controllers\EtiquetteTemplateController::class, 'renderPreview'])->name('api.etiquettes.render-preview');
            Route::get('/printers', [App\Http\Controllers\EtiquetteTemplateController::class, 'getPrinters'])->name('api.etiquettes.printers');
        });
    });
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Clients
    Route::resource('clients', ClientController::class);
    
    // Chantiers
    Route::get('/chantiers', [ChantierController::class, 'index'])->name('chantiers.index');
    Route::get('/chantiers/create', [ChantierController::class, 'create'])->name('chantiers.create');
    // Route pour le choix de type de projet (maintenance ou vente)
    Route::get('/chantiers/type-choix', [ChantierController::class, 'typeChoix'])->name('nouveau.projet');
    // Routes pour créer des projets de maintenance ou vente
    Route::get('/chantiers/maintenance/create', [ChantierController::class, 'createMaintenanceProject'])->name('chantiers.maintenance.create');
    Route::get('/chantiers/vente/create', [ChantierController::class, 'createVenteProject'])->name('chantiers.vente.create');
    // Route pour créer un client via AJAX lors de la création d'un chantier
    Route::post('/chantiers/store-client-ajax', [ChantierController::class, 'storeClientAjax'])->name('chantiers.store.client-ajax');
    Route::get('/chantiers/create/step1', [ChantierController::class, 'createStep1'])->name('chantiers.create.step1');
    Route::post('/chantiers/create/step1', [ChantierController::class, 'storeStep1'])->name('chantiers.store.step1');
    Route::get('/chantiers/create/step2/{chantier}', [ChantierController::class, 'createStep2'])->name('chantiers.create.step2');
    Route::post('/chantiers/create/step2/{chantier}', [ChantierController::class, 'storeStep2'])->name('chantiers.store.step2');
    Route::get('/chantiers/create/step3/{chantier}', [ChantierController::class, 'createStep3'])->name('chantiers.create.step3');
    Route::post('/chantiers/create/step3/{chantier}', [ChantierController::class, 'storeStep3'])->name('chantiers.store.step3');
    // Le workflow est maintenant de 4 étapes au lieu de 5 (l'ancienne étape 4 a été supprimée)
    Route::get('/chantiers/create/step4/{chantier}', [ChantierController::class, 'createStep4'])->name('chantiers.create.step4');
    Route::post('/chantiers/create/step4/{chantier}', [ChantierController::class, 'storeStep4'])->name('chantiers.store.step4');
    Route::get('/chantiers/{chantier}', [ChantierController::class, 'show'])->name('chantiers.show');
    Route::get('/chantiers/{chantier}/edit', [ChantierController::class, 'edit'])->name('chantiers.edit');
    Route::put('/chantiers/{chantier}', [ChantierController::class, 'update'])->name('chantiers.update');
    Route::patch('/chantiers/{chantier}/state', [ChantierController::class, 'updateState'])->name('chantiers.update.state');
    Route::delete('/chantiers/{chantier}', [ChantierController::class, 'destroy'])->name('chantiers.destroy');
    
    // Impression d'étiquettes QR Code pour chantiers
    Route::get('/chantiers/{chantier}/qrcode', [ChantierQrCodeController::class, 'show'])->name('qrcode.chantier.show');
    Route::get('/chantiers/{chantier}/print-qrcode', [ChantierQrCodeController::class, 'printLabel'])->name('qrcode.chantier.print');
    
    // Routes pour les étiquettes
    Route::get('/chantiers/{chantier}/etiquette', [App\Http\Controllers\EtiquetteController::class, 'chantierEtiquette'])->name('etiquettes.chantier');
    Route::get('/chantiers/{chantier}/etiquette/ptouch', [App\Http\Controllers\EtiquetteController::class, 'chantierPTouchTemplate'])->name('etiquettes.chantier.ptouch');
    Route::get('/chantiers/{chantier}/flightcase-etiquettes', [App\Http\Controllers\EtiquetteController::class, 'flightcaseEtiquettes'])->name('etiquettes.flightcase');
    Route::get('/chantiers/{chantier}/flightcase-etiquettes/{flightcase}/print', [App\Http\Controllers\EtiquetteController::class, 'printFlightcaseEtiquette'])->name('etiquettes.flightcase.print');
    Route::get('/chantiers/{chantier}/flightcase-etiquettes/{flightcase}/download', [App\Http\Controllers\EtiquetteController::class, 'downloadFlightcasePdf'])->name('etiquettes.flightcase.download');
    Route::get('/dalles/{dalle}/etiquette', [App\Http\Controllers\EtiquetteController::class, 'dalleEtiquette'])->name('etiquettes.dalle');
    Route::get('/modules/{module}/etiquette', [App\Http\Controllers\EtiquetteController::class, 'moduleEtiquette'])->name('etiquettes.module');
    Route::get('/etiquettes/test', [App\Http\Controllers\EtiquetteController::class, 'testEtiquettes'])->name('etiquettes.test');
    Route::post('/modules/batch-etiquettes', [App\Http\Controllers\EtiquetteController::class, 'moduleBatchEtiquettes'])->name('etiquettes.module.batch');
    
    // Dalles
    Route::get('/chantiers/{chantier}/dalles/create', [DalleController::class, 'create'])->name('dalles.create');
    Route::post('/chantiers/{chantier}/dalles', [DalleController::class, 'store'])->name('dalles.store');
    Route::get('/dalles', [DalleController::class, 'index'])->name('dalles.index');
    Route::get('/dalles/{dalle}', [DalleController::class, 'show'])->name('dalles.show');
    Route::get('/dalles/{dalle}/edit', [DalleController::class, 'edit'])->name('dalles.edit');
    Route::put('/dalles/{dalle}', [DalleController::class, 'update'])->name('dalles.update');
    Route::delete('/dalles/{dalle}', [DalleController::class, 'destroy'])->name('dalles.destroy');
    
    // Impression d'étiquettes QR Code pour dalles
    Route::get('/dalles/{dalle}/qrcode', [DalleQrCodeController::class, 'show'])->name('qrcode.dalle.show');
    Route::get('/dalles/{dalle}/print-qrcode', [DalleQrCodeController::class, 'printQrCode'])->name('qrcode.dalle.print');
    
    // Modules
    Route::get('/dalles/{dalle}/modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('/dalles/{dalle}/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::get('/dalles/{dalle}/modules/mass-create', [ModuleController::class, 'massCreate'])->name('modules.mass-create');
    Route::post('/dalles/{dalle}/modules/mass-store', [ModuleController::class, 'massStore'])->name('modules.mass-store');
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
    Route::get('/modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
    Route::put('/modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');
    Route::post('/modules/{module}/mark-as-completed', [ModuleController::class, 'markAsCompleted'])->name('modules.mark-as-completed');
    
    // Impression d'étiquettes QR Code pour modules
    Route::get('/modules/{module}/qrcode', [ModuleQrCodeController::class, 'show'])->name('qrcode.module.show');
    Route::get('/modules/{module}/print-qrcode', [ModuleQrCodeController::class, 'printQrCode'])->name('qrcode.module.print');
    Route::get('/modules/batch/{ids}/print-qrcode', [ModuleQrCodeController::class, 'batchPrintQrCode'])->name('qrcode.module.batch-print');
    
    // Interventions
    Route::get('/modules/{module}/interventions/create', [InterventionController::class, 'create'])->name('interventions.create');
    Route::post('/modules/{module}/interventions', [InterventionController::class, 'store'])->name('interventions.store');
    Route::get('/interventions', [InterventionController::class, 'index'])->name('interventions.index');
    Route::get('/interventions/{intervention}', [InterventionController::class, 'show'])->name('interventions.show');
    Route::get('/interventions/{intervention}/edit', [InterventionController::class, 'edit'])->name('interventions.edit');
    Route::put('/interventions/{intervention}', [InterventionController::class, 'update'])->name('interventions.update');
    Route::delete('/interventions/{intervention}', [InterventionController::class, 'destroy'])->name('interventions.destroy');
    
    // Etape 1 de l'intervention : diagnostic
    Route::get('/interventions/{intervention}/diagnostic', [InterventionController::class, 'showDiagnosticStep'])->name('interventions.diagnostic');
    Route::post('/interventions/{intervention}/diagnostic', [InterventionController::class, 'storeDiagnostic'])->name('interventions.store-diagnostic');
    
    // Etape 2 de l'intervention : réparation
    Route::get('/interventions/{intervention}/reparation', [InterventionController::class, 'showReparationStep'])->name('interventions.reparation');
    Route::post('/interventions/{intervention}/reparation', [InterventionController::class, 'storeReparation'])->name('interventions.store-reparation');
    
    // Etape 3 de l'intervention : finalisation
    Route::get('/interventions/{intervention}/finalisation', [InterventionController::class, 'showFinalisationStep'])->name('interventions.finalisation');
    Route::post('/interventions/{intervention}/finalisation', [InterventionController::class, 'storeFinalisation'])->name('interventions.store-finalisation');
    
    // Annulation d'intervention
    Route::post('/interventions/{intervention}/cancel', [InterventionController::class, 'cancel'])->name('interventions.cancel');
    
    // Route pour nettoyer les modules bloqués (pour administrateurs)
    Route::post('/modules/clean-status', [ModuleController::class, 'cleanStatus'])->name('modules.clean-status')->middleware('admin');
    
    // Route pour nettoyer les modules incohérents (marqués comme occupés sans intervention active)
    Route::get('/maintenance/clean-modules', [ModuleController::class, 'cleanStatus'])->name('maintenance.clean-modules');
    
    // Route pour l'auto-nettoyage silencieux (via AJAX)
    Route::get('/maintenance/auto-clean-modules', [ModuleController::class, 'autoCleanStatus'])->name('maintenance.auto-clean-modules');
    
    // Produits du catalogue
    Route::resource('produits-catalogue', ProduitCatalogueController::class);
    
    // Produits
    Route::resource('produits', ProduitController::class);
    Route::get('/produits/create/variante/{produit}', [ProduitController::class, 'createVariante'])->name('produits.create-variante');
    Route::post('/produits/store/variante/{produit}', [ProduitController::class, 'storeVariante'])->name('produits.store-variante');
    
    // Rapports
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/chantier/{chantier}', [RapportController::class, 'chantier'])->name('rapports.chantier');
    Route::get('/rapports/intervention/{intervention}', [RapportController::class, 'intervention'])->name('rapports.intervention');
    
    // Emails
    Route::post('/emails/chantier/{chantier}', [\App\Http\Controllers\EmailController::class, 'sendChantierEmail'])->name('emails.chantier');
});

// Routes de paramétrage initial
Route::prefix('setup')->group(function () {
    Route::get('/', [SetupController::class, 'index'])->name('setup.index');
    Route::get('/admin', [SetupController::class, 'admin'])->name('setup.admin');
    Route::post('/admin', [SetupController::class, 'storeAdmin'])->name('setup.admin.store');
});

// Routes de QR Code Public
Route::prefix('suivi')->group(function () {
    // Suivi client
    Route::get('/client/{token}', [ClientController::class, 'suivi'])->name('suivi.client');
    
    // Suivi chantier
    Route::get('/chantier/{token}', [ChantierController::class, 'suivi'])->name('suivi.chantier');
    
    // Suivi module
    Route::get('/module/{token}', [ModuleController::class, 'suivi'])->name('suivi.module');
});

// Public QR Code routes without token
Route::prefix('qr')->group(function () {
    // Scan de QR Code générique
    Route::get('/scan', function () {
        return view('qrcodes.scan');
    })->name('qrcode.scan');
});

// Routes pour tester l'impression
Route::middleware(['auth'])->group(function () {
    // Printer Management Routes
    Route::resource('printers', App\Http\Controllers\PrinterController::class);
    Route::post('/printers/{printer}/set-default', [App\Http\Controllers\PrinterController::class, 'setDefault'])->name('printers.set-default');
    Route::get('/printers/{id}/test', [App\Http\Controllers\PrinterController::class, 'testPrint'])->name('printers.test');
    
    // QZ Tray Routes
    Route::get('/printers/test-qz-tray', [App\Http\Controllers\PrinterController::class, 'testQzTray'])->name('printers.test-qz-tray');
    Route::post('/printers/print-test-qr', [App\Http\Controllers\PrinterController::class, 'printTestQr'])->name('printers.print-test-qr');
    Route::get('/printers/test-labels', [App\Http\Controllers\PrinterController::class, 'testLabels'])->name('printers.test-labels');
    Route::post('/printers/process-test-labels', [App\Http\Controllers\PrinterController::class, 'processTestLabels'])->name('printers.process-test-labels');
    
    // Route simple de test
    Route::get('/test-etiquette', function () {
        // Générer un QR code pour le test
        $service = app(App\Services\PrinterService::class);
        $qrCode = $service->generateQrCode('TEST_ETIQUETTE_' . date('YmdHis'), 250);
        
        return view('etiquettes.brother_test_62x70', [
            'qrcode' => $qrCode,
            'test_id' => uniqid('test_'),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    })->name('test-etiquette');
    
    // Page de test pour étiquette 62x80mm (rouleau continu)
    Route::get('/print-test/brother-label', function () {
        // Générer un QR code pour le test
        $service = app(App\Services\PrinterService::class);
        $qrCode = $service->generateQrCode('TEST_ETIQUETTE_' . date('YmdHis'), 250);
        
        return view('etiquettes.brother_test_62x70', [
            'qrcode' => $qrCode,
            'test_id' => uniqid('test_'),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    })->name('print-test.brother-label');
    
    // Ajout d'une route spécifique pour tester l'impression avec les nouveaux paramètres
    Route::get('/test-impression-rouleau-continu', function (App\Http\Controllers\Api\PrintController $printController) {
        // Génération d'un QR code pour le test
        $service = app(App\Services\PrinterService::class);
        $qrCode = $service->generateQrCode('TEST_ROULEAU_CONTINU_' . date('YmdHis'), 300);
        
        // Options d'affichage
        $viewData = [
            'qrcode' => $qrCode,
            'test_id' => uniqid('test_'),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Rendu de la vue
        return view('etiquettes.brother_test_62x70', $viewData);
    })->name('test-impression-rouleau-continu');
    
    // Route de test directe pour PrintNode sans passer par le système complexe
    Route::get('/printnode-test-direct', function() {
        // PrintNode API credentials
        $apiKey = 'JTDVu0eHs2bXjVOb_HDUKVimF_WechuZM2XuwQAiE04';
        $printerId = 74188357; // Brother QL-820NWB (Copie 1)
        
        // Créer une image simple
        $width = 696;   // 62mm à 300 DPI
        $height = 1181; // 100mm à 300 DPI
        
        try {
            Log::debug('Début du test PrintNode direct');
            
            $image = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            $red = imagecolorallocate($image, 255, 0, 0);
            
            // Remplir l'arrière-plan en blanc
            imagefill($image, 0, 0, $white);
            
            // Dessiner un cadre
            imagerectangle($image, 0, 0, $width-1, $height-1, $black);
            
            // Ajouter du texte
            imagestring($image, 5, 20, 20, "TECALED GMAO", $black);
            imagestring($image, 5, 20, 60, "Test d'impression", $red);
            imagestring($image, 4, 20, 100, "Flightcase Label", $black);
            imagestring($image, 4, 20, 140, "Date: " . date('Y-m-d H:i:s'), $black);
            
            // Sauvegarder l'image dans un buffer
            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();
            
            // Libérer la mémoire
            imagedestroy($image);
            
            // Convertir en base64
            $imageBase64 = base64_encode($imageData);
            
            // Log de l'opération
            Log::info('Test direct PrintNode - Image générée', [
                'taille_base64' => strlen($imageBase64),
                'taille_image' => $width . 'x' . $height
            ]);
            
            // Créer le payload pour PrintNode
            $payload = [
                "printerId" => (int)$printerId,
                "title" => "Test Flightcase Label " . date('Y-m-d H:i:s'),
                "contentType" => "raw_base64",
                "content" => $imageBase64,
                "source" => "GMAO TecaLED Print Test",
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
            
            Log::info('Réponse PrintNode direct', [
                'http_code' => $httpCode,
                'response' => $response
            ]);
            
            curl_close($ch);
            
            if ($httpCode >= 200 && $httpCode < 300) {
                $printJobId = json_decode($response);
                return response()->json([
                    'success' => true,
                    'message' => 'Test d\'impression envoyé avec succès !',
                    'print_job_id' => $printJobId
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur API PrintNode',
                    'http_code' => $httpCode,
                    'response' => $response
                ], 500);
            }
            
        } catch (Exception $e) {
            Log::error('Exception test PrintNode direct: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    })->name('printnode-test-direct');
});

// Routes pour l'authentification client
Route::prefix('client')->group(function () {
    Route::get('/login', [App\Http\Controllers\ClientAuthController::class, 'showLoginForm'])->name('client.login');
    Route::post('/login', [App\Http\Controllers\ClientAuthController::class, 'login'])->name('client.login.submit');
    Route::get('/register', [App\Http\Controllers\ClientAuthController::class, 'showRegisterForm'])->name('client.register');
    Route::post('/register', [App\Http\Controllers\ClientAuthController::class, 'register'])->name('client.register.submit');
    Route::post('/logout', [App\Http\Controllers\ClientAuthController::class, 'logout'])->name('client.logout');
    Route::get('/dashboard', [App\Http\Controllers\ClientAuthController::class, 'dashboard'])->name('client.dashboard');
});

// Route pour définir le mot de passe d'un client (utilisé dans l'édition de client)
Route::post('/clients/{client}/set-password', [ClientController::class, 'setPassword'])->name('client.set-password')->middleware('auth');

// Routes d'authentification Laravel (login, register, etc.)
require __DIR__.'/auth.php';