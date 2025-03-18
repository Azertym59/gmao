<?php

namespace App\Services;

use App\Models\PrintJob;
use App\Models\Printer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class QzTrayService
{
    /**
     * Get QZ Tray scripts for use in templates
     * 
     * @return string HTML script tags
     */
    public function getQzTrayScripts(): string
    {
        // Nous avons supprimé QZ Tray, donc retourner une chaîne vide
        return '';
    }
    
    /**
     * Get QZ Tray print script for the specific print data
     * 
     * @param array $printData The print data
     * @return string JavaScript for printing
     */
    public function getQzTrayPrintScript(array $printData): string
    {
        // Nous avons supprimé QZ Tray, donc retourner un script minimal
        return '<script>
            // Stubs for QZ Tray functionality
            window.QZTray = {
                connect: function() { 
                    console.log("PrintNode est utilisé à la place de QZ Tray");
                    return Promise.resolve(); 
                },
                getPrinters: function() { 
                    return Promise.resolve(["PrintNode Printer"]); 
                },
                printFromController: function() {
                    console.log("Impression via PrintNode");
                    // Montrer un message d\'impression réussie
                    document.getElementById("print-success").style.display = "block";
                    document.getElementById("qz-status").className = "badge bg-success";
                    document.getElementById("qz-status").textContent = "Impression gérée par PrintNode";
                    document.getElementById("print-progress").style.width = "100%";
                    document.getElementById("print-progress").className = "progress-bar bg-success";
                    return Promise.resolve(); 
                }
            };
        </script>';
    }
    
    /**
     * Generate QR code as base64 image
     */
    public function generateQrCode(string $data, int $size = 150, string $errorCorrection = 'H', array $options = []): string
    {
        try {
            // Generate QR code with high error correction
            $qrCode = QrCode::format('png')
                ->size($size)
                ->errorCorrection($errorCorrection);
                
            // Apply margin option if provided
            if (isset($options['margin'])) {
                $qrCode->margin($options['margin']);
            } else {
                // Use smaller margins by default for labels
                $qrCode->margin(1);
            }
            
            // Generate the QR code
            $qrCodeData = $qrCode->generate($data);

            // Convert to base64
            $base64 = 'data:image/png;base64,' . base64_encode($qrCodeData);
            
            return $base64;
        } catch (\Exception $e) {
            Log::error('Error generating QR code: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a print job record and queue it for processing via PrintNode
     */
    public function queuePrintJob(Printer $printer, string $content, string $type, ?string $entityType = null, ?int $entityId = null): PrintJob
    {
        // Options d'impression pour PrintNode
        $options = [];
        
        // Si c'est une imprimante Brother qui utilise PrintNode, on configure les options appropriées
        if ($printer->isBrotherLabel() && $printer->hasPrintNode()) {
            $options = [
                'printnode_id' => $printer->printnode_id,
                'width' => $printer->label_width ?? 62,
                'height' => $printer->label_height ?? 100,
                'dpi' => $printer->dpi ?? 300,
                'copies' => 1,
            ];
            
            // Journaliser les options pour Brother
            Log::info('PrintNode job for Brother printer - using proper options', [
                'printer_id' => $printer->id,
                'printer_name' => $printer->name,
                'printnode_id' => $printer->printnode_id
            ]);
        } else {
            // Sinon utiliser les options standard
            $options = [
                'size' => $printer->default_width ?? '62mm',
                'height' => $printer->default_height ?? '20mm',
                'copies' => 1,
            ];
        }
        
        // Create a print job record
        $printJob = PrintJob::create([
            'printer_id' => $printer->id,
            'user_id' => Auth::id(),
            'content' => $content,
            'type' => $type,
            'status' => 'queued',
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'options' => $options,
        ]);
        
        // Log the print job
        Log::info('Print job queued', [
            'job_id' => $printJob->id,
            'printer' => $printer->name,
            'type' => $type,
        ]);
        
        return $printJob;
    }
    
    /**
     * Generate PDF for printing through PrintNode
     */
    public function generatePdf(string $htmlContent, array $options = []): string
    {
        // Default options for PDF generation
        $defaultOptions = [
            'format' => [$options['width'] ?? 62, $options['height'] ?? 100],
            'margin_top' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'dpi' => $options['dpi'] ?? 300
        ];
        
        // Create PDF using DomPDF
        $pdf = PDF::loadHTML($htmlContent)
            ->setPaper($defaultOptions['format'], 'portrait')
            ->setOptions([
                'dpi' => $defaultOptions['dpi'],
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);
        
        // Return PDF as base64 encoded string
        return base64_encode($pdf->output());
    }
    
    /**
     * Send print job to PrintNode API
     */
    public function sendToPrintNode(int $printNodeId, string $pdfContent, array $options = []): array
    {
        $apiKey = config('printing.printnode_api_key');
        
        if (empty($apiKey)) {
            Log::error('PrintNode API key not configured');
            return [
                'success' => false,
                'message' => 'PrintNode API key not configured'
            ];
        }
        
        try {
            // Construct the API request to PrintNode
            $response = Http::withBasicAuth($apiKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post('https://api.printnode.com/printjobs', [
                    'printerId' => $printNodeId,
                    'title' => $options['title'] ?? 'Label Print Job',
                    'contentType' => 'pdf_base64',
                    'content' => $pdfContent,
                    'source' => 'GMAO Application',
                    'options' => [
                        'copies' => $options['copies'] ?? 1
                    ]
                ]);
            
            if ($response->successful()) {
                $printJobId = $response->json();
                Log::info('PrintNode job created successfully', [
                    'printnode_id' => $printNodeId,
                    'printnode_job_id' => $printJobId
                ]);
                
                return [
                    'success' => true,
                    'printnode_job_id' => $printJobId,
                    'message' => 'Print job sent to PrintNode successfully'
                ];
            } else {
                Log::error('Failed to create PrintNode job', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return [
                    'success' => false,
                    'status' => $response->status(),
                    'message' => 'Failed to create PrintNode job: ' . $response->body()
                ];
            }
        } catch (\Exception $e) {
            Log::error('PrintNode API error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'PrintNode API error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Process a print job using direct PDF generation and PrintNode
     */
    public function processPrintJob(PrintJob $printJob): array
    {
        try {
            $printer = $printJob->printer;
            
            // Update job status
            $printJob->status = 'processing';
            $printJob->save();
            
            // Check if printer supports PrintNode
            if (!$printer->hasPrintNode()) {
                throw new \Exception('Printer does not support PrintNode');
            }
            
            // Generate PDF from content
            $pdfContent = $this->generatePdf($printJob->content, $printJob->options);
            
            // Send to PrintNode
            $result = $this->sendToPrintNode(
                $printer->printnode_id,
                $pdfContent,
                [
                    'title' => 'Print Job #' . $printJob->id,
                    'copies' => $printJob->options['copies'] ?? 1
                ]
            );
            
            if ($result['success']) {
                // Update job status
                $printJob->status = 'completed';
                $printJob->completed_at = now();
                $printJob->metadata = array_merge(
                    $printJob->metadata ?? [],
                    ['printnode_job_id' => $result['printnode_job_id']]
                );
                $printJob->save();
                
                return [
                    'success' => true,
                    'message' => 'Print job processed successfully',
                    'job_id' => $printJob->id,
                    'printnode_job_id' => $result['printnode_job_id']
                ];
            } else {
                // Update job status
                $printJob->status = 'failed';
                $printJob->completed_at = now();
                $printJob->error_message = $result['message'];
                $printJob->save();
                
                return [
                    'success' => false,
                    'message' => $result['message'],
                    'job_id' => $printJob->id
                ];
            }
        } catch (\Exception $e) {
            // Log error
            Log::error('Error processing print job', [
                'job_id' => $printJob->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Update job status
            $printJob->status = 'failed';
            $printJob->completed_at = now();
            $printJob->error_message = $e->getMessage();
            $printJob->save();
            
            return [
                'success' => false,
                'message' => 'Error processing print job: ' . $e->getMessage(),
                'job_id' => $printJob->id
            ];
        }
    }
    
    /**
     * Generate HTML for a print preview
     */
    public function generatePreview(array $printData): string
    {
        $html = '<div class="print-preview p-3 border rounded bg-light">';
        $html .= '<h5 class="mb-3">Aperçu de l\'impression</h5>';
        
        // Show image if available
        if (!empty($printData['imageData'])) {
            $html .= '<div class="text-center mb-3">';
            $html .= '<img src="' . $printData['imageData'] . '" alt="Aperçu d\'impression" class="img-fluid" style="max-width: 250px;">';
            $html .= '</div>';
        }
        
        // Show printer info
        $html .= '<div class="mb-3">';
        $html .= '<strong>Imprimante:</strong> ' . htmlspecialchars($printData['printerName'] ?? 'Non spécifiée');
        $html .= '</div>';
        
        // Show options
        if (!empty($printData['options'])) {
            $html .= '<div class="mb-3">';
            $html .= '<strong>Options:</strong>';
            $html .= '<ul class="mb-0 ps-3">';
            foreach ($printData['options'] as $key => $value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $html .= '<li>' . htmlspecialchars($key) . ': ' . htmlspecialchars($value) . '</li>';
            }
            $html .= '</ul>';
            $html .= '</div>';
        }
        
        // Print status and controls
        $html .= '<div class="d-flex justify-content-between align-items-center mt-3">';
        $html .= '<span class="badge bg-secondary" id="print-status">En attente</span>';
        $html .= '<button type="button" class="btn-action btn-primary btn-sm" id="print-button">Imprimer</button>';
        $html .= '</div>';
        
        // Error message container (hidden by default)
        $html .= '<div class="alert alert-danger mt-3" id="print-error" style="display: none;"></div>';
        
        // Success message container (hidden by default)
        $html .= '<div class="alert alert-success mt-3" id="print-success" style="display: none;">Impression réussie</div>';
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Check and update printer statuses
     */
    public function checkPrinterStatus(Printer $printer): array
    {
        // Vérifier le statut avec PrintNode si disponible
        if ($printer->hasPrintNode()) {
            try {
                $apiKey = config('printing.printnode_api_key');
                
                if (empty($apiKey)) {
                    throw new \Exception('PrintNode API key not configured');
                }
                
                $response = Http::withBasicAuth($apiKey, '')
                    ->get('https://api.printnode.com/printers/' . $printer->printnode_id);
                
                if ($response->successful()) {
                    $printerData = $response->json();
                    
                    $isOnline = $printerData['computer']['state'] === 'online' && 
                                $printerData['state'] === 'online';
                    
                    $hasError = false;
                    $errorMessage = null;
                    
                    if (isset($printerData['state']) && $printerData['state'] !== 'online') {
                        $hasError = true;
                        $errorMessage = 'Printer state: ' . $printerData['state'];
                    }
                    
                    // Update the printer status in the database
                    $printer->is_online = $isOnline;
                    $printer->last_error = $errorMessage;
                    $printer->last_checked_at = now();
                    $printer->save();
                    
                    return [
                        'printer_id' => $printer->id,
                        'is_online' => $isOnline,
                        'has_error' => $hasError,
                        'error_message' => $errorMessage,
                        'last_checked_at' => $printer->last_checked_at->toIso8601String(),
                    ];
                } else {
                    Log::error('Failed to check PrintNode printer status', [
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                    
                    $printer->is_online = false;
                    $printer->last_error = 'Failed to check printer status: ' . $response->status();
                    $printer->last_checked_at = now();
                    $printer->save();
                    
                    return [
                        'printer_id' => $printer->id,
                        'is_online' => false,
                        'has_error' => true,
                        'error_message' => 'Failed to check printer status: ' . $response->status(),
                        'last_checked_at' => $printer->last_checked_at->toIso8601String(),
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Error checking PrintNode printer status', [
                    'printer_id' => $printer->id,
                    'error' => $e->getMessage()
                ]);
                
                $printer->is_online = false;
                $printer->last_error = 'Error checking printer status: ' . $e->getMessage();
                $printer->last_checked_at = now();
                $printer->save();
                
                return [
                    'printer_id' => $printer->id,
                    'is_online' => false,
                    'has_error' => true,
                    'error_message' => 'Error checking printer status: ' . $e->getMessage(),
                    'last_checked_at' => $printer->last_checked_at->toIso8601String(),
                ];
            }
        }
        
        // Pour les autres imprimantes ou si PrintNode n'est pas configuré, simuler un statut
        $isOnline = true;
        $hasError = false;
        $errorMessage = null;
        
        // Update the printer status in the database
        $printer->is_online = $isOnline;
        $printer->last_error = $errorMessage;
        $printer->last_checked_at = now();
        $printer->save();
        
        return [
            'printer_id' => $printer->id,
            'is_online' => $isOnline,
            'has_error' => $hasError,
            'error_message' => $errorMessage,
            'last_checked_at' => $printer->last_checked_at->toIso8601String(),
        ];
    }
    
    /**
     * Prepare QR code print data for a chantier
     * 
     * @param string $printerName The name of the printer
     * @param string $url The URL to encode in the QR code
     * @param array $qrOptions QR code generation options
     * @param array $printOptions Printing options
     * @return array Print data for the template
     */
    public function prepareQrCodePrint(string $printerName, string $url, array $qrOptions = [], array $printOptions = []): array
    {
        try {
            // Generate QR code image
            $qrCodeImage = $this->generateQrCode($url, $qrOptions['size'] ?? 300, $qrOptions['errorCorrection'] ?? 'H');
            
            // Prepare print data
            $printData = [
                'printerName' => $printerName,
                'imageData' => $qrCodeImage,
                'content' => $url,
                'options' => $printOptions
            ];
            
            // Return the prepared print data
            return $printData;
        } catch (\Exception $e) {
            Log::error('Error preparing QR code print data: ' . $e->getMessage());
            throw $e;
        }
    }
}