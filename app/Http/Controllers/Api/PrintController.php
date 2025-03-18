<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrintJob;
use App\Models\Printer;
use App\Services\QzTrayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PrintController extends Controller
{
    protected $qzTrayService;

    public function __construct(QzTrayService $qzTrayService)
    {
        $this->qzTrayService = $qzTrayService;
    }

    /**
     * Créer une nouvelle tâche d'impression
     */
    public function createJob(Request $request)
    {
        // Valider la requête
        $validator = Validator::make($request->all(), [
            'printer_id' => 'required|exists:printers,id',
            'type' => 'required|string|in:chantier,module,dalle',
            'item_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // Récupérer l'imprimante
            $printer = Printer::findOrFail($request->printer_id);
            
            // Préparer le contenu selon le type
            $content = null;
            $name = "";
            $entityType = null;
            $entityId = null;
            
            switch ($request->type) {
                case 'chantier':
                    $chantier = \App\Models\Chantier::findOrFail($request->item_id);
                    $content = view('qrcodes.chantier.label', ['chantier' => $chantier])->render();
                    $name = "Chantier: " . $chantier->nom;
                    $entityType = 'App\Models\Chantier';
                    $entityId = $chantier->id;
                    break;
                
                case 'module':
                    $module = \App\Models\Module::findOrFail($request->item_id);
                    $content = view('qrcodes.module.label', ['module' => $module])->render();
                    $name = "Module: " . $module->reference;
                    $entityType = 'App\Models\Module';
                    $entityId = $module->id;
                    break;
                
                case 'dalle':
                    $dalle = \App\Models\Dalle::findOrFail($request->item_id);
                    $content = view('qrcodes.dalle.label', ['dalle' => $dalle])->render();
                    $name = "Dalle: " . $dalle->reference;
                    $entityType = 'App\Models\Dalle';
                    $entityId = $dalle->id;
                    break;
            }
            
            // Créer la tâche d'impression
            $printJob = $this->qzTrayService->queuePrintJob(
                $printer,
                $content,
                $request->type,
                $entityType,
                $entityId
            );
            
            // Traiter immédiatement le job si c'est une imprimante PrintNode
            if ($printer->hasPrintNode()) {
                $result = $this->qzTrayService->processPrintJob($printJob);
                
                if (!$result['success']) {
                    return response()->json([
                        'success' => false,
                        'job_id' => $printJob->id,
                        'message' => $result['message']
                    ], 500);
                }
            }
            
            return response()->json([
                'success' => true,
                'job_id' => $printJob->id,
                'message' => 'Tâche d\'impression créée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la tâche d\'impression', [
                'type' => $request->type,
                'item_id' => $request->item_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Récupérer les tâches d'impression en attente
     */
    public function getPendingJobs()
    {
        // Récupérer les tâches en attente
        $jobs = PrintJob::where('status', 'queued')
            ->with('printer', 'user')
            ->orderBy('created_at', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'jobs' => $jobs
        ]);
    }
    
    /**
     * Mettre à jour le statut d'une tâche d'impression
     */
    public function updateJobStatus(Request $request, $id)
    {
        // Valider la requête
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:printing,completed,failed',
            'error_message' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        // Récupérer la tâche
        $job = PrintJob::findOrFail($id);
        
        // Mettre à jour le statut
        $job->status = $request->status;
        $job->error_message = $request->error_message;
        
        if ($request->status === 'printing') {
            $job->printed_at = now();
        } elseif ($request->status === 'completed' || $request->status === 'failed') {
            $job->completed_at = now();
        }
        
        $job->save();
        
        // Log l'action
        Log::info('Print job status updated', [
            'job_id' => $job->id,
            'status' => $job->status,
            'user_id' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Statut de la tâche mis à jour avec succès'
        ]);
    }
    
    /**
     * Test d'impression simple - Version DirectPrintNode
     */
    public function testPrint(Request $request)
    {
        // Valider la requête
        $validator = Validator::make($request->all(), [
            'printer_id' => 'required|exists:printers,id',
            'type' => 'nullable|string|in:html,qrcode',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Récupérer l'imprimante
        $printer = Printer::findOrFail($request->printer_id);
        $type = $request->input('type', 'qrcode');
        
        try {
            // Journaliser la tentative d'impression
            Log::info('Tentative de test d\'impression', [
                'printer_id' => $printer->id,
                'printer_name' => $printer->name,
                'type' => $type,
                'printnode_id' => $printer->printnode_id,
                'api_key' => !empty(config('printing.printnode.api_key')) ? 'configurée' : 'non configurée'
            ]);
            
            if ($type === 'qrcode') {
                // Générer des données de test
                $testData = json_encode([
                    'test' => true,
                    'printer' => $printer->name,
                    'timestamp' => now()->toDateTimeString(),
                    'id' => uniqid()
                ]);
                
                // Générer un QR code
                $qrCode = $this->qzTrayService->generateQrCode($testData, 250, 'H');
                
                // Créer un PDF minimal avec QR Code pour PrintNode
                $content = '<html><body style="margin: 0; padding: 0;">';
                $content .= '<div style="text-align: center; width: 100%;">';
                $content .= '<h1 style="font-size: 12pt; margin-bottom: 10px;">Test Impression QR Code</h1>';
                $content .= '<div>';
                $content .= '<img src="' . $qrCode . '" style="width: 250px; height: 250px;">';
                $content .= '</div>';
                $content .= '<p style="font-size: 10pt; margin-top: 10px;">Imprimante: ' . $printer->name . '</p>';
                $content .= '<p style="font-size: 10pt;">Date: ' . date('d/m/Y H:i:s') . '</p>';
                $content .= '</div>';
                $content .= '</body></html>';
                
                // Convertir en PDF avec DomPDF
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($content);
                $pdfContent = base64_encode($pdf->output());
                
                // Si l'imprimante a PrintNode, imprimer directement via API
                if ($printer->hasPrintNode()) {
                    $apiKey = config('printing.printnode.api_key');
                    
                    if (empty($apiKey)) {
                        Log::error('PrintNode API key missing');
                        return response()->json([
                            'success' => false,
                            'message' => 'Clé API PrintNode non configurée'
                        ], 500);
                    }
                    
                    // Appel direct à l'API PrintNode
                    $printJobData = [
                        'printerId' => $printer->printnode_id,
                        'title' => 'Test QR Code',
                        'contentType' => 'pdf_base64',
                        'content' => $pdfContent,
                        'source' => 'GMAO Direct Test'
                    ];
                    
                    // Log complet des données envoyées à PrintNode
                    Log::info('Envoi direct à PrintNode', [
                        'printer_id' => $printer->printnode_id,
                        'has_content' => !empty($pdfContent)
                    ]);
                    
                    $response = Http::withBasicAuth($apiKey, '')
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post('https://api.printnode.com/printjobs', $printJobData);
                    
                    if ($response->successful()) {
                        $printNodeJobId = $response->body();
                        
                        // Créer un enregistrement local du job
                        $printJob = PrintJob::create([
                            'printer_id' => $printer->id,
                            'user_id' => Auth::id(),
                            'content' => 'Direct PrintNode API call',
                            'type' => 'test_qrcode_direct',
                            'status' => 'completed',
                            'completed_at' => now(),
                            'metadata' => ['printnode_job_id' => $printNodeJobId]
                        ]);
                        
                        Log::info('Test d\'impression direct réussi', [
                            'printnode_job_id' => $printNodeJobId,
                            'local_job_id' => $printJob->id
                        ]);
                        
                        return response()->json([
                            'success' => true,
                            'job_id' => $printJob->id,
                            'printnode_job_id' => $printNodeJobId,
                            'preview' => $qrCode,
                            'message' => 'Impression QR code envoyée à PrintNode avec succès'
                        ]);
                    } else {
                        Log::error('Erreur API PrintNode', [
                            'status' => $response->status(),
                            'body' => $response->body()
                        ]);
                        
                        return response()->json([
                            'success' => false,
                            'status' => $response->status(),
                            'message' => 'Erreur PrintNode: ' . $response->body()
                        ], 500);
                    }
                } else {
                    // Utiliser la méthode standard pour les autres imprimantes
                    $printJob = $this->qzTrayService->queuePrintJob(
                        $printer,
                        $content,
                        'test_qrcode'
                    );
                    
                    return response()->json([
                        'success' => true,
                        'job_id' => $printJob->id,
                        'preview' => $qrCode,
                        'message' => 'Test d\'impression QR code créé avec succès (file d\'attente)'
                    ]);
                }
            } else {
                // Créer un contenu HTML de test
                $content = '<html><body style="margin: 0; padding: 0;">';
                $content .= '<div style="text-align: center; width: 100%;">';
                $content .= '<h1 style="font-size: 14pt; margin-bottom: 10px;">TEST IMPRESSION</h1>';
                $content .= '<p style="font-size: 12pt;">Imprimante : ' . $printer->name . '</p>';
                $content .= '<p style="font-size: 12pt;">Date : ' . date('d/m/Y H:i:s') . '</p>';
                
                // Générer un QR code si possible
                try {
                    $qrCode = $this->qzTrayService->generateQrCode('Test QR Code - ' . $printer->name, 80);
                    $content .= '<div style="margin-top: 15px;">';
                    $content .= '<img src="' . $qrCode . '" style="width: 80px; height: 80px;">';
                    $content .= '</div>';
                } catch (\Exception $e) {
                    Log::error('Erreur génération QR code pour test: ' . $e->getMessage());
                }
                
                $content .= '</div>';
                $content .= '</body></html>';
                
                // Créer la tâche d'impression
                $printJob = $this->qzTrayService->queuePrintJob(
                    $printer, 
                    $content,
                    'test_html'
                );
                
                // Traiter immédiatement le job si c'est une imprimante PrintNode
                if ($printer->hasPrintNode()) {
                    $result = $this->qzTrayService->processPrintJob($printJob);
                    
                    if (!$result['success']) {
                        return response()->json([
                            'success' => false,
                            'job_id' => $printJob->id,
                            'message' => $result['message']
                        ], 500);
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'job_id' => $printJob->id,
                    'message' => 'Test d\'impression HTML créé avec succès'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du test d\'impression', [
                'printer_id' => $printer->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test d\'impression: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Créer un job d'impression QR code spécifique
     */
    public function createQrCodePrintJob(Request $request)
    {
        // Valider la requête
        $validator = Validator::make($request->all(), [
            'printer_id' => 'required|exists:printers,id',
            'data' => 'required|string',
            'size' => 'nullable|integer|min:50|max:1000',
            'error_correction' => 'nullable|in:L,M,Q,H',
            'entity_type' => 'nullable|string',
            'entity_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // Récupérer l'imprimante
            $printer = Printer::findOrFail($request->printer_id);
            
            // Obtenir les options
            $size = $request->input('size', 250);
            $errorCorrection = $request->input('error_correction', 'H');
            
            // Générer le QR code
            $qrCode = $this->qzTrayService->generateQrCode(
                $request->data,
                $size,
                $errorCorrection
            );
            
            // Créer un contenu HTML avec le QR code pour l'impression via PDF
            $content = '<html><body style="margin: 0; padding: 0;">';
            $content .= '<div style="text-align: center; width: 100%;">';
            $content .= '<div style="padding: 5px;">';
            $content .= '<img src="' . $qrCode . '" style="width: ' . $size . 'px; height: ' . $size . 'px;">';
            $content .= '</div>';
            $content .= '</div>';
            $content .= '</body></html>';
            
            // Créer la tâche d'impression
            $printJob = $this->qzTrayService->queuePrintJob(
                $printer,
                $content,
                'qr_code_custom',
                $request->entity_type,
                $request->entity_id
            );
            
            // Traiter immédiatement le job si c'est une imprimante PrintNode
            if ($printer->hasPrintNode()) {
                $result = $this->qzTrayService->processPrintJob($printJob);
                
                if (!$result['success']) {
                    return response()->json([
                        'success' => false,
                        'job_id' => $printJob->id,
                        'message' => $result['message']
                    ], 500);
                }
            }
            
            return response()->json([
                'success' => true,
                'job_id' => $printJob->id,
                'preview' => $qrCode,
                'message' => 'Tâche d\'impression QR code créée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur création job d\'impression QR code: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Récupère l'historique des impressions
     */
    public function getHistory(Request $request)
    {
        $limit = $request->input('limit', 50);
        $offset = $request->input('offset', 0);
        
        $query = PrintJob::with(['printer', 'user'])
            ->orderBy('created_at', 'desc');
            
        // Filtrer par état si demandé
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filtrer par imprimante si demandé
        if ($request->has('printer_id')) {
            $query->where('printer_id', $request->printer_id);
        }
        
        // Filtrer par utilisateur si demandé
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Pagination
        $total = $query->count();
        $jobs = $query->skip($offset)->take($limit)->get();
        
        return response()->json([
            'success' => true,
            'total' => $total,
            'offset' => $offset,
            'limit' => $limit,
            'jobs' => $jobs
        ]);
    }
    
    /**
     * Check PrintNode configuration
     * 
     * @return \Illuminate\Http\Response
     */
    public function checkConfig()
    {
        try {
            $config = [
                'printnode_enabled' => !empty(config('printing.printnode.enabled')),
                'printnode_api_key' => !empty(config('printing.printnode.api_key')),
                'default_printer_id' => config('printing.printnode.default_printer_id'),
                'printers' => Printer::where('printnode_id', '!=', null)->count(),
            ];
            
            return response()->json([
                'success' => true,
                'config' => $config,
                'message' => 'Configuration récupérée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification de la configuration PrintNode', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification de la configuration: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Print QR code for a chantier using PrintNode
     * 
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function printChantierQrCode($id, Request $request)
    {
        try {
            // Find the chantier
            $chantier = \App\Models\Chantier::findOrFail($id);
            
            // Get default printer or a specific printer
            $printer = null;
            
            if ($request->has('printer_id')) {
                $printer = Printer::findOrFail($request->printer_id);
            } else {
                $printer = Printer::where('is_default', true)->first();
                
                if (!$printer) {
                    return redirect()->back()
                        ->with('error', 'Aucune imprimante par défaut trouvée. Veuillez en configurer une.');
                }
            }
            
            if (!$printer->hasPrintNode()) {
                return redirect()->back()
                    ->with('error', 'L\'imprimante sélectionnée n\'est pas configurée pour PrintNode.');
            }
            
            // Generate QR code URL and render the view
            $url = route('qrcode.chantier.show', $chantier->id);
            $content = view('qrcodes.chantier.label', ['chantier' => $chantier, 'printData' => ['imageData' => $this->qzTrayService->generateQrCode($url, 300)], 'printer' => $printer])->render();
            
            // Create the print job
            $printJob = $this->qzTrayService->queuePrintJob(
                $printer,
                $content,
                'chantier_qrcode',
                'App\\Models\\Chantier',
                $chantier->id
            );
            
            // Process the print job
            $result = $this->qzTrayService->processPrintJob($printJob);
            
            if ($result['success']) {
                return redirect()->back()
                    ->with('success', 'L\'étiquette a été envoyée à l\'imprimante avec succès.');
            } else {
                return redirect()->back()
                    ->with('error', 'Erreur lors de l\'impression: ' . $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'impression du QR code du chantier', [
                'chantier_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'impression: ' . $e->getMessage());
        }
    }
}