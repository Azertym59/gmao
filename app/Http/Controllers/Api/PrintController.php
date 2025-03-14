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
        
        return response()->json([
            'success' => true,
            'job_id' => $printJob->id,
            'message' => 'Tâche d\'impression créée avec succès'
        ]);
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
     * Test d'impression simple
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
        
        if ($type === 'qrcode') {
            // Générer des données de test
            $testData = json_encode([
                'test' => true,
                'printer' => $printer->name,
                'timestamp' => now()->toDateTimeString(),
                'id' => uniqid()
            ]);
            
            // Calculer la taille de papier appropriée
            $paperSize = $printer->default_width ?? ($printer->type === 'thermal' ? '57mm' : 'A4');
            $paperHeight = $printer->default_height ?? ($printer->type === 'thermal' ? '40mm' : '297mm');
            
            // Préparer le QR code pour l'impression
            $printData = $this->qzTrayService->prepareQrCodePrint(
                $printer->name,
                $testData,
                [
                    'size' => 250,
                    'errorCorrection' => 'H'
                ],
                [
                    'copies' => 1,
                    'size' => $paperSize,
                    'height' => $paperHeight,
                ]
            );
            
            // Créer la tâche d'impression
            $printJob = $this->qzTrayService->queuePrintJob(
                $printer,
                base64_encode(json_encode($printData)),
                'test_qrcode'
            );
            
            return response()->json([
                'success' => true,
                'job_id' => $printJob->id,
                'preview' => $printData['imageData'],
                'message' => 'Test d\'impression QR code créé avec succès'
            ]);
        } else {
            // Créer un contenu HTML de test
            $content = '<html><body>';
            $content .= '<h1 style="font-size: 14pt; text-align: center; font-weight: bold;">TEST IMPRESSION</h1>';
            $content .= '<p style="text-align: center;">Imprimante : ' . $printer->name . '</p>';
            $content .= '<p style="text-align: center;">Date : ' . date('d/m/Y H:i:s') . '</p>';
            
            // Générer un QR code si possible
            try {
                $qrCode = $this->qzTrayService->generateQrCode('Test QR Code - ' . $printer->name, 80);
                $content .= '<div style="text-align: center; margin-top: 10px;">';
                $content .= '<img src="' . $qrCode . '" style="width: 80px; height: 80px;">';
                $content .= '</div>';
            } catch (\Exception $e) {
                Log::error('Erreur génération QR code pour test: ' . $e->getMessage());
            }
            
            $content .= '</body></html>';
            
            // Créer la tâche d'impression
            $printJob = $this->qzTrayService->queuePrintJob(
                $printer, 
                $content,
                'test_html'
            );
            
            return response()->json([
                'success' => true,
                'job_id' => $printJob->id,
                'message' => 'Test d\'impression HTML créé avec succès'
            ]);
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
            
            // Créer la tâche d'impression
            $printJob = $this->qzTrayService->queuePrintJob(
                $printer,
                $qrCode,
                'qr_code_custom',
                $request->entity_type,
                $request->entity_id
            );
            
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
}