<?php
// Dans app/Http/Controllers/Api/PrintController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Printer;
use App\Models\PrintJob;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PrintController extends Controller
{
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
        
        switch ($request->type) {
            case 'chantier':
                $chantier = \App\Models\Chantier::findOrFail($request->item_id);
                $content = view('qrcodes.chantier.label', ['chantier' => $chantier])->render();
                $name = "Chantier: " . $chantier->nom;
                break;
            
            case 'module':
                $module = \App\Models\Module::findOrFail($request->item_id);
                $content = view('qrcodes.module.label', ['module' => $module])->render();
                $name = "Module: " . $module->reference;
                break;
            
            case 'dalle':
                $dalle = \App\Models\Dalle::findOrFail($request->item_id);
                $content = view('qrcodes.dalle.label', ['dalle' => $dalle])->render();
                $name = "Dalle: " . $dalle->reference;
                break;
        }
        
        // Créer la tâche d'impression
        $job = new PrintJob();
        $job->printer_id = $request->printer_id;
        $job->content = $content;
        $job->name = $name;
        $job->status = 'pending';
        $job->job_token = Str::random(32); // Token unique pour cette tâche
        $job->save();
        
        return response()->json([
            'success' => true,
            'job_id' => $job->id,
            'job_token' => $job->job_token
        ]);
    }
    
    /**
     * Récupérer les tâches d'impression en attente
     */
    public function getPendingJobs(Request $request)
    {
        // Vérifier le token API du client
        $apiToken = $request->header('X-API-Token');
        if (!$apiToken || $apiToken !== config('printing.client_api_token')) {
            return response()->json(['success' => false, 'message' => 'Non autorisé'], 401);
        }
        
        // Récupérer les tâches en attente
        $jobs = PrintJob::where('status', 'pending')
            ->with('printer')
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
        // Vérifier le token API du client
        $apiToken = $request->header('X-API-Token');
        if (!$apiToken || $apiToken !== config('printing.client_api_token')) {
            return response()->json(['success' => false, 'message' => 'Non autorisé'], 401);
        }
        
        // Valider la requête
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:printing,completed,failed',
            'job_token' => 'required|string',
            'message' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        // Récupérer et vérifier la tâche
        $job = PrintJob::findOrFail($id);
        
        if ($job->job_token !== $request->job_token) {
            return response()->json(['success' => false, 'message' => 'Token de tâche invalide'], 401);
        }
        
        // Mettre à jour le statut
        $job->status = $request->status;
        $job->message = $request->message;
        $job->save();
        
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
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Récupérer l'imprimante
        $printer = Printer::findOrFail($request->printer_id);
        
        // Créer un contenu de test
        $content = '<html><body>';
        $content .= '<h1 style="font-size: 14pt; text-align: center; font-weight: bold;">TEST IMPRESSION</h1>';
        $content .= '<p style="text-align: center;">Imprimante : ' . $printer->name . '</p>';
        $content .= '<p style="text-align: center;">Date : ' . date('d/m/Y H:i:s') . '</p>';
        
        // Générer un QR code si possible
        if (class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode')) {
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate('Test QR Code - ' . $printer->name);
            $content .= '<div style="text-align: center; margin-top: 10px; border: 1px solid black; display: inline-block; padding: 5px;">' . $qrCode . '</div>';
        }
        
        $content .= '</body></html>';
        
        // Créer la tâche d'impression
        $job = new PrintJob();
        $job->printer_id = $request->printer_id;
        $job->content = $content;
        $job->name = "Test d'impression";
        $job->status = 'pending';
        $job->job_token = Str::random(32); // Token unique pour cette tâche
        $job->save();
        
        return response()->json([
            'success' => true,
            'job_id' => $job->id,
            'job_token' => $job->job_token
        ]);
    }
}