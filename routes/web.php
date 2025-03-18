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
use App\Http\Middleware\ClientMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Printer;
use PrintNode\Credentials;
use PrintNode\Client;
use PrintNode\PrintJob;

// Routes temporaires supprimées

// Routes publiques
// Route d'accès direct sans authentification (TEMPORAIRE)
Route::get('/direct-access', function () {
    return view('welcome');
})->name('direct-access');

// Route de test PrintNode
Route::get('/test-printnode', function () {
    $apiKey = config('printing.printnode.api_key');
    if (empty($apiKey)) {
        return "Erreur: API Key PrintNode non configurée. Vérifiez le fichier .env";
    }
    
    // Initialiser correctement le client PrintNode avec des credentials
    $credentials = new Credentials();
    $credentials->setApiKey($apiKey);
    $client = new Client($credentials);
    
    // Récupérer les imprimantes disponibles
    try {
        $printers = $client->getPrinters();
        
        if (empty($printers)) {
            return "Aucune imprimante détectée dans PrintNode. Vérifiez que le client PrintNode est installé et que l'imprimante est connectée.";
        }
        
        // Sélectionner l'imprimante Brother si disponible
        $brotherPrinter = null;
        foreach ($printers as $printer) {
            if (stripos($printer->name, 'Brother') !== false || stripos($printer->name, 'QL-820') !== false) {
                $brotherPrinter = $printer;
                break;
            }
        }
        
        $printerId = $brotherPrinter ? $brotherPrinter->id : $printers[0]->id;
        $printerName = $brotherPrinter ? $brotherPrinter->name : $printers[0]->name;
        
        // Créer un PDF basique pour le test
        $html = '<html><body style="margin: 0; padding: 5px;">
            <h3 style="font-size: 14px; text-align: center;">TEST IMPRESSION BROTHER</h3>
            <p style="font-size: 12px; text-align: center;">Date: ' . date('d/m/Y H:i:s') . '</p>
            <p style="font-size: 12px; text-align: center;">Imprimante: ' . $printerName . '</p>
            <div style="text-align: center; margin-top: 10px;">
                <img src="data:image/png;base64,' . base64_encode(
                    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(150)
                    ->margin(1)
                    ->generate('Test QR Code - ' . date('d/m/Y H:i:s'))
                ) . '" style="width: 150px; height: 150px;">
            </div>
        </body></html>';
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdfContent = base64_encode($pdf->output());
        
        // Créer la tâche d'impression avec options A4 et sans marges
        $printJob = new PrintJob();
        $printJob->printer = $printerId;
        $printJob->title = "Test Brother Label - A4 Format";
        $printJob->contentType = 'pdf_base64';
        $printJob->content = $pdfContent;
        $printJob->source = 'GMAO Test PrintNode';
        $printJob->options = [
            'paper' => 'a4',
            'fit' => 'content',
            'bin' => 'default'
        ];
        
        // Envoyer la tâche d'impression
        $jobId = $client->createPrintJob($printJob);
        
        // Enregistrer l'imprimante dans la base de données si elle n'existe pas déjà
        $dbPrinter = Printer::where('name', $printerName)->first();
        if (!$dbPrinter) {
            $dbPrinter = Printer::create([
                'name' => $printerName,
                'type' => stripos($printerName, 'Brother') !== false ? 'brother_label' : 'standard',
                'is_default' => false,
                'options' => [
                    'printnode_id' => $printerId,
                    'dpi' => 300,
                    'label_width' => 62,
                    'label_height' => 100,
                ]
            ]);
        } else if (empty($dbPrinter->printnode_id)) {
            // Mettre à jour l'ID PrintNode si l'imprimante existe mais sans ID PrintNode
            $options = $dbPrinter->options ?? [];
            $options['printnode_id'] = $printerId;
            $dbPrinter->options = $options;
            $dbPrinter->save();
        }
        
        return "Tâche d'impression envoyée avec succès vers l'imprimante: " . $printerName . 
               "<br>ID Tâche PrintNode: " . $jobId . 
               "<br>Imprimante ID: " . $printerId . 
               "<br><strong>Format:</strong> A4 (pour éviter l'erreur de type de rouleau)" .
               "<br><a href='/test-printnode-specific'>Essayer avec un format spécifique pour Brother (62mm x 100mm)</a>";
               
    } catch (\Exception $e) {
        return "Erreur lors du test d'impression PrintNode: " . $e->getMessage() . 
               "<br>Trace: <pre>" . $e->getTraceAsString() . "</pre>";
    }
})->name('test-printnode');

// Route de test PrintNode avec format spécifique
Route::get('/test-printnode-specific', function () {
    $apiKey = config('printing.printnode.api_key');
    if (empty($apiKey)) {
        return "Erreur: API Key PrintNode non configurée. Vérifiez le fichier .env";
    }
    
    // Initialiser correctement le client PrintNode avec des credentials
    $credentials = new Credentials();
    $credentials->setApiKey($apiKey);
    $client = new Client($credentials);
    
    try {
        $printers = $client->getPrinters();
        
        if (empty($printers)) {
            return "Aucune imprimante détectée dans PrintNode.";
        }
        
        // Sélectionner l'imprimante Brother si disponible
        $brotherPrinter = null;
        foreach ($printers as $printer) {
            if (stripos($printer->name, 'Brother') !== false || stripos($printer->name, 'QL-820') !== false) {
                $brotherPrinter = $printer;
                break;
            }
        }
        
        $printerId = $brotherPrinter ? $brotherPrinter->id : $printers[0]->id;
        $printerName = $brotherPrinter ? $brotherPrinter->name : $printers[0]->name;
        
        // Création d'un PDF au format 62mm x 100mm (176px x 283px à 72dpi)
        $customPaper = array(0, 0, 176, 283);
        
        $html = '<html><body style="margin: 0; padding: 5px; width: 62mm; height: 100mm;">
            <h3 style="font-size: 12px; text-align: center;">TEST FORMAT SPÉCIFIQUE</h3>
            <p style="font-size: 10px; text-align: center;">Date: ' . date('d/m/Y H:i:s') . '</p>
            <p style="font-size: 10px; text-align: center;">Format: 62mm x 100mm</p>
            <div style="text-align: center; margin-top: 5px;">
                <img src="data:image/png;base64,' . base64_encode(
                    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(120)
                    ->margin(1)
                    ->generate('Test Format Spécifique - ' . date('d/m/Y H:i:s'))
                ) . '" style="width: 120px; height: 120px;">
            </div>
        </body></html>';
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
            ->setPaper($customPaper, 'portrait')
            ->setOptions([
                'dpi' => 300,
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);
            
        $pdfContent = base64_encode($pdf->output());
        
        // Créer la tâche d'impression avec des options spécifiques
        $printJob = new PrintJob();
        $printJob->printer = $printerId;
        $printJob->title = "Test Brother Label - Format Spécifique";
        $printJob->contentType = 'pdf_base64';
        $printJob->content = $pdfContent;
        $printJob->source = 'GMAO Test PrintNode Spécifique';
        $printJob->options = [
            'paper' => 'dk22205',
            'width' => '62mm',
            'height' => '100mm'
        ];
        
        // Envoyer la tâche d'impression
        $jobId = $client->createPrintJob($printJob);
        
        return "Tâche d'impression spécifique envoyée avec succès vers l'imprimante: " . $printerName . 
               "<br>ID Tâche PrintNode: " . $jobId . 
               "<br>Imprimante ID: " . $printerId . 
               "<br><strong>Format:</strong> 62mm x 100mm (spécifique pour Brother)" .
               "<br><a href='/test-printnode'>Revenir au test avec format A4</a>";
               
    } catch (\Exception $e) {
        return "Erreur lors du test d'impression PrintNode spécifique: " . $e->getMessage() . 
               "<br>Trace: <pre>" . $e->getTraceAsString() . "</pre>";
    }
})->name('test-printnode-specific');

// Route de test simple avec paper=roll
Route::get('/test-printnode-roll', function () {
    $apiKey = config('printing.printnode.api_key');
    if (empty($apiKey)) {
        return "Erreur: API Key PrintNode non configurée. Vérifiez le fichier .env";
    }
    
    // Initialiser correctement le client PrintNode avec des credentials
    $credentials = new Credentials();
    $credentials->setApiKey($apiKey);
    $client = new Client($credentials);
    
    try {
        $printers = $client->getPrinters();
        
        if (empty($printers)) {
            return "Aucune imprimante détectée dans PrintNode.";
        }
        
        // Sélectionner l'imprimante Brother si disponible
        $brotherPrinter = null;
        foreach ($printers as $printer) {
            if (stripos($printer->name, 'Brother') !== false || stripos($printer->name, 'QL-820') !== false) {
                $brotherPrinter = $printer;
                break;
            }
        }
        
        $printerId = $brotherPrinter ? $brotherPrinter->id : $printers[0]->id;
        $printerName = $brotherPrinter ? $brotherPrinter->name : $printers[0]->name;
        
        // Création d'un PDF simple
        $html = '<html><body style="margin: 0; padding: 5px;">
            <h3 style="font-size: 12px; text-align: center;">TEST FORMAT ROULEAU</h3>
            <p style="font-size: 10px; text-align: center;">Date: ' . date('d/m/Y H:i:s') . '</p>
            <p style="font-size: 10px; text-align: center;">Option: paper=roll</p>
            <div style="text-align: center; margin-top: 5px;">
                <img src="data:image/png;base64,' . base64_encode(
                    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(120)
                    ->margin(1)
                    ->generate('Test Option Rouleau - ' . date('d/m/Y H:i:s'))
                ) . '" style="width: 120px; height: 120px;">
            </div>
        </body></html>';
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdfContent = base64_encode($pdf->output());
        
        // Créer la tâche d'impression avec l'option paper=roll
        $printJob = new PrintJob();
        $printJob->printer = $printerId;
        $printJob->title = "Test Brother - Option Roll";
        $printJob->contentType = 'pdf_base64';
        $printJob->content = $pdfContent;
        $printJob->source = 'GMAO Test PrintNode Roll';
        $printJob->options = [
            'paper' => 'roll',
            'fit' => 'content'
        ];
        
        // Envoyer la tâche d'impression
        $jobId = $client->createPrintJob($printJob);
        
        return "Tâche d'impression spécifique envoyée avec succès vers l'imprimante: " . $printerName . 
               "<br>ID Tâche PrintNode: " . $jobId . 
               "<br>Imprimante ID: " . $printerId . 
               "<br><strong>Format:</strong> Option paper=roll" .
               "<br><a href='/test-printnode'>Revenir au test avec format A4</a>" .
               "<br><a href='/test-printnode-dk22205'>Essayer avec paper=dk22205</a>";
               
    } catch (\Exception $e) {
        return "Erreur lors du test d'impression PrintNode roll: " . $e->getMessage() . 
               "<br>Trace: <pre>" . $e->getTraceAsString() . "</pre>";
    }
})->name('test-printnode-roll');

// Route de test avec option dk22205 (correspond au rouleau continu 62mm)
Route::get('/test-printnode-dk22205', function () {
    $apiKey = config('printing.printnode.api_key');
    if (empty($apiKey)) {
        return "Erreur: API Key PrintNode non configurée. Vérifiez le fichier .env";
    }
    
    // Initialiser correctement le client PrintNode avec des credentials
    $credentials = new Credentials();
    $credentials->setApiKey($apiKey);
    $client = new Client($credentials);
    
    try {
        $printers = $client->getPrinters();
        
        if (empty($printers)) {
            return "Aucune imprimante détectée dans PrintNode.";
        }
        
        // Sélectionner l'imprimante Brother si disponible
        $brotherPrinter = null;
        foreach ($printers as $printer) {
            if (stripos($printer->name, 'Brother') !== false || stripos($printer->name, 'QL-820') !== false) {
                $brotherPrinter = $printer;
                break;
            }
        }
        
        $printerId = $brotherPrinter ? $brotherPrinter->id : $printers[0]->id;
        $printerName = $brotherPrinter ? $brotherPrinter->name : $printers[0]->name;
        
        // Création d'un PDF simple
        $html = '<html><body style="margin: 0; padding: 5px;">
            <h3 style="font-size: 12px; text-align: center;">TEST FORMAT DK22205</h3>
            <p style="font-size: 10px; text-align: center;">Date: ' . date('d/m/Y H:i:s') . '</p>
            <p style="font-size: 10px; text-align: center;">Option: paper=dk22205</p>
            <div style="text-align: center; margin-top: 5px;">
                <img src="data:image/png;base64,' . base64_encode(
                    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(120)
                    ->margin(1)
                    ->generate('Test Option DK22205 - ' . date('d/m/Y H:i:s'))
                ) . '" style="width: 120px; height: 120px;">
            </div>
        </body></html>';
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdfContent = base64_encode($pdf->output());
        
        // Créer la tâche d'impression avec l'option paper=dk22205
        $printJob = new PrintJob();
        $printJob->printer = $printerId;
        $printJob->title = "Test Brother - Option DK22205";
        $printJob->contentType = 'pdf_base64';
        $printJob->content = $pdfContent;
        $printJob->source = 'GMAO Test PrintNode DK22205';
        $printJob->options = [
            'paper' => 'dk22205'
        ];
        
        // Envoyer la tâche d'impression
        $jobId = $client->createPrintJob($printJob);
        
        return "Tâche d'impression spécifique envoyée avec succès vers l'imprimante: " . $printerName . 
               "<br>ID Tâche PrintNode: " . $jobId . 
               "<br>Imprimante ID: " . $printerId . 
               "<br><strong>Format:</strong> Option paper=dk22205" .
               "<br><a href='/test-printnode'>Revenir au test avec format A4</a>" .
               "<br><a href='/test-printnode-roll'>Essayer avec paper=roll</a>";
               
    } catch (\Exception $e) {
        return "Erreur lors du test d'impression PrintNode dk22205: " . $e->getMessage() . 
               "<br>Trace: <pre>" . $e->getTraceAsString() . "</pre>";
    }
})->name('test-printnode-dk22205');

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
    Route::middleware(['auth', 'role:technicien'])->group(function () {
        Route::get('/technicien/dashboard', [TechnicienDashboardController::class, 'index'])->name('technicien.dashboard');
    });
    
    // Routes pour les administrateurs
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Gestion des utilisateurs
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        
        // Gestion de la base de données
        Route::get('/admin/database', [AdminController::class, 'database'])->name('admin.database');
        Route::get('/admin/database/backups', [AdminController::class, 'databaseBackups'])->name('admin.database.backups');
        Route::post('/admin/database/backup', [AdminController::class, 'createBackup'])->name('admin.database.backup');
        Route::get('/admin/database/reset', [AdminController::class, 'confirmDatabaseReset'])->name('admin.database.reset.confirm');
        Route::post('/admin/database/reset', [AdminController::class, 'resetDatabase'])->name('admin.database.reset');
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
    Route::get('/chantiers/create/step1', [ChantierController::class, 'createStep1'])->name('chantiers.create.step1');
    Route::post('/chantiers/create/step1', [ChantierController::class, 'storeStep1'])->name('chantiers.store.step1');
    Route::get('/chantiers/create/step2/{chantier}', [ChantierController::class, 'createStep2'])->name('chantiers.create.step2');
    Route::post('/chantiers/create/step2/{chantier}', [ChantierController::class, 'storeStep2'])->name('chantiers.store.step2');
    Route::get('/chantiers/create/step3/{chantier}', [ChantierController::class, 'createStep3'])->name('chantiers.create.step3');
    Route::post('/chantiers/create/step3/{chantier}', [ChantierController::class, 'storeStep3'])->name('chantiers.store.step3');
    Route::get('/chantiers/create/step4/{chantier}', [ChantierController::class, 'createStep4'])->name('chantiers.create.step4');
    Route::post('/chantiers/create/step4/{chantier}', [ChantierController::class, 'storeStep4'])->name('chantiers.store.step4');
    Route::get('/chantiers/create/step5/{chantier}', [ChantierController::class, 'createStep5'])->name('chantiers.create.step5');
    Route::post('/chantiers/create/step5/{chantier}', [ChantierController::class, 'storeStep5'])->name('chantiers.store.step5');
    Route::get('/chantiers/{chantier}', [ChantierController::class, 'show'])->name('chantiers.show');
    Route::get('/chantiers/{chantier}/edit', [ChantierController::class, 'edit'])->name('chantiers.edit');
    Route::put('/chantiers/{chantier}', [ChantierController::class, 'update'])->name('chantiers.update');
    Route::delete('/chantiers/{chantier}', [ChantierController::class, 'destroy'])->name('chantiers.destroy');
    
    // Impression d'étiquettes QR Code pour chantiers
    Route::get('/chantiers/{chantier}/qrcode', [ChantierQrCodeController::class, 'show'])->name('qrcode.chantier.show');
    Route::get('/chantiers/{chantier}/print-qrcode', [ChantierQrCodeController::class, 'printQrCode'])->name('qrcode.chantier.print');
    
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
    Route::get('/interventions/{intervention}/diagnostic', [InterventionController::class, 'editDiagnostic'])->name('interventions.diagnostic');
    Route::post('/interventions/{intervention}/diagnostic', [InterventionController::class, 'storeDiagnostic'])->name('interventions.store-diagnostic');
    
    // Etape 2 de l'intervention : réparation
    Route::get('/interventions/{intervention}/reparation', [InterventionController::class, 'editReparation'])->name('interventions.reparation');
    Route::post('/interventions/{intervention}/reparation', [InterventionController::class, 'storeReparation'])->name('interventions.store-reparation');
    
    // Etape 3 de l'intervention : finalisation
    Route::get('/interventions/{intervention}/finalisation', [InterventionController::class, 'editFinalisation'])->name('interventions.finalisation');
    Route::post('/interventions/{intervention}/finalisation', [InterventionController::class, 'storeFinalisation'])->name('interventions.store-finalisation');
    
    // Produits du catalogue
    Route::resource('produits-catalogue', ProduitCatalogueController::class);
    
    // Produits
    Route::resource('produits', ProduitController::class);
    Route::get('/produits/create/variante/{produit}', [ProduitController::class, 'createVariante'])->name('produits.create-variante');
    Route::post('/produits/store/variante/{produit}', [ProduitController::class, 'storeVariante'])->name('produits.store-variante');
    
    // Imprimantes
    Route::resource('printers', PrinterController::class);
    Route::get('/printers/{printer}/test', [PrinterController::class, 'test'])->name('printers.test');
    Route::post('/printers/{printer}/test', [PrinterController::class, 'executeTest'])->name('printers.execute-test');
    
    // Rapports
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/chantier/{chantier}', [RapportController::class, 'chantier'])->name('rapports.chantier');
    Route::get('/rapports/intervention/{intervention}', [RapportController::class, 'intervention'])->name('rapports.intervention');
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

// Routes d'authentification Laravel (login, register, etc.)
require __DIR__.'/auth.php';