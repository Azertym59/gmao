<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Printer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PrintNode\Client;
use PrintNode\Credentials\ApiKey;

class PrintNodeController extends Controller
{
    /**
     * Liste des imprimantes disponibles sur PrintNode
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPrinters(Request $request)
    {
        try {
            // Récupérer l'API key de PrintNode
            $apiKey = config('services.printnode.api_key');
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La clé API PrintNode n\'est pas configurée'
                ], 400);
            }

            // Initialiser le client PrintNode
            $credentials = new ApiKey($apiKey);
            $client = new Client($credentials);

            // Récupérer les imprimantes
            $printers = $client->getPrinters();

            // Préparer les données de réponse
            $printersData = [];
            foreach ($printers as $printer) {
                $printersData[] = [
                    'id' => $printer->id,
                    'name' => $printer->name,
                    'description' => $printer->description,
                    'state' => $printer->state,
                    'computer' => [
                        'id' => $printer->computer->id,
                        'name' => $printer->computer->name
                    ],
                    'capabilities' => !empty($printer->capabilities) ? json_encode($printer->capabilities) : null
                ];
            }

            return response()->json([
                'success' => true,
                'printers' => $printersData
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des imprimantes PrintNode: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tester l'impression avec PrintNode
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testPrint(Request $request)
    {
        try {
            // Valider la requête
            $request->validate([
                'printer_id' => 'required|integer'
            ]);

            $printerId = $request->printer_id;

            // Récupérer l'API key de PrintNode
            $apiKey = config('services.printnode.api_key');
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La clé API PrintNode n\'est pas configurée'
                ], 400);
            }

            // Générer un contenu HTML de test
            $html = '<html><body style="font-family: Arial; text-align: center;">';
            $html .= '<h1>Test d\'impression PrintNode</h1>';
            $html .= '<p>Date: ' . date('Y-m-d H:i:s') . '</p>';
            $html .= '<p>Imprimante ID: ' . $printerId . '</p>';
            $html .= '</body></html>';

            // Convertir en PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->output();
            $pdfBase64 = base64_encode($pdf);

            // Initialiser le client PrintNode
            $credentials = new ApiKey($apiKey);
            $client = new Client($credentials);

            // Préparer les données du job d'impression
            $printJob = new \PrintNode\Entity\PrintJob();
            $printJob->printer = $printerId;
            $printJob->title = 'Test impression API';
            $printJob->contentType = 'pdf_base64';
            $printJob->content = $pdfBase64;
            $printJob->source = 'GMAO API';

            // Options pour Brother QL-820NWB
            $printJob->options = [
                'paper' => 'Custom.62x100mm',
                'orientation' => 'portrait',
                'media' => 'dk-22205',
                'dpi' => '300'
            ];

            // Envoyer le job d'impression
            $printJobId = $client->createPrintJob($printJob);

            return response()->json([
                'success' => true,
                'message' => 'Job d\'impression envoyé avec succès',
                'print_job_id' => $printJobId
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du test d\'impression PrintNode: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifier le statut d'un job d'impression
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPrintJobStatus(Request $request, $jobId)
    {
        try {
            // Récupérer l'API key de PrintNode
            $apiKey = config('services.printnode.api_key');
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La clé API PrintNode n\'est pas configurée'
                ], 400);
            }

            // Initialiser le client PrintNode
            $credentials = new ApiKey($apiKey);
            $client = new Client($credentials);

            // Récupérer le statut du job
            $printJobState = $client->getPrintJobStates($jobId);

            if (empty($printJobState)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job d\'impression non trouvé'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'job_id' => $jobId,
                'state' => $printJobState[0]->state ?? 'unknown',
                'created' => $printJobState[0]->created ?? null,
                'message' => 'Statut récupéré avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification du statut du job d\'impression: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'job_id' => $jobId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imprimer une étiquette personnalisée
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function printLabel(Request $request)
    {
        try {
            // Valider la requête
            $request->validate([
                'printer_id' => 'required',
                'content' => 'required|string',
                'title' => 'nullable|string',
                'width' => 'nullable|integer',
                'height' => 'nullable|integer'
            ]);

            // Récupérer l'imprimante (soit de la base de données, soit directement)
            $printerId = $request->printer_id;
            $printerFromDb = null;

            // Si printerId est numérique, c'est un ID PrintNode direct
            if (!is_numeric($printerId)) {
                // Sinon, c'est un ID de notre base de données
                $printerFromDb = Printer::find($printerId);
                if (!$printerFromDb) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Imprimante non trouvée'
                    ], 404);
                }
                $printerId = $printerFromDb->options['printnode_id'] ?? null;
            }

            if (!$printerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID d\'imprimante PrintNode non disponible'
                ], 400);
            }

            // Récupérer l'API key de PrintNode
            $apiKey = config('services.printnode.api_key');
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La clé API PrintNode n\'est pas configurée'
                ], 400);
            }

            // Générer un HTML basé sur le contenu
            $html = '<html><body style="font-family: Arial; text-align: center;">';
            $html .= $request->content;
            $html .= '</body></html>';

            // Convertir en PDF avec DomPDF
            $width = $request->width ?? 62;
            $height = $request->height ?? 100;

            // Convertir mm en points (1 inch = 25.4 mm, 1 inch = 72 points)
            $widthPt = ($width / 25.4) * 72;
            $heightPt = ($height / 25.4) * 72;

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
                ->setPaper([0, 0, $widthPt, $heightPt])
                ->setOptions([
                    'dpi' => 300,
                    'defaultFont' => 'sans-serif'
                ]);

            $pdfContent = base64_encode($pdf->output());

            // Initialiser le client PrintNode
            $credentials = new ApiKey($apiKey);
            $client = new Client($credentials);

            // Préparer les données du job d'impression
            $printJob = new \PrintNode\Entity\PrintJob();
            $printJob->printer = (int)$printerId;
            $printJob->title = $request->title ?? 'Étiquette GMAO';
            $printJob->contentType = 'pdf_base64';
            $printJob->content = $pdfContent;
            $printJob->source = 'GMAO API';

            // Options pour Brother QL-820NWB
            $printJob->options = [
                'paper' => "Custom.{$width}x{$height}mm",
                'orientation' => 'portrait',
                'media' => 'dk-22205',
                'dpi' => '300'
            ];

            // Envoyer le job d'impression
            $printJobId = $client->createPrintJob($printJob);

            return response()->json([
                'success' => true,
                'message' => 'Étiquette envoyée à l\'impression',
                'print_job_id' => $printJobId
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'impression d\'étiquette: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}
