<?php

namespace App\Services;

use App\Models\PrintJob;
use App\Models\Printer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QzTrayService
{
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
     * Prepare print data for QZ Tray
     */
    public function preparePrintData(string $printerName, string $imageData, array $options = []): array
    {
        // Default options
        $defaultOptions = [
            'size' => '62mm',            // Default label size (updated to 62mm width)
            'height' => '20mm',          // Height for the new small label format
            'orientation' => 'portrait', // Default orientation
            'copies' => 1,               // Default copies
            'margins' => [               // Default margins
                'top' => 0,
                'right' => 0,
                'bottom' => 0,
                'left' => 0
            ]
        ];

        // Merge options
        $options = array_merge($defaultOptions, $options);

        // Return data structure for QZ Tray
        return [
            'printerName' => $printerName,
            'imageData' => $imageData,
            'options' => $options
        ];
    }

    /**
     * Generate QR code and prepare print data in one step
     */
    public function prepareQrCodePrint(string $printerName, string $data, array $qrOptions = [], array $printOptions = []): array
    {
        // Extract QR code options
        $size = $qrOptions['size'] ?? 250;
        $errorCorrection = $qrOptions['errorCorrection'] ?? 'H';

        // Generate QR code
        $imageData = $this->generateQrCode($data, $size, $errorCorrection);

        // Prepare print data
        return $this->preparePrintData($printerName, $imageData, $printOptions);
    }
    
    /**
     * Create a print job record and queue it for processing
     */
    public function queuePrintJob(Printer $printer, string $content, string $type, ?string $entityType = null, ?int $entityId = null): PrintJob
    {
        // Pour Brother + PrintNode, fournir des options simplifiées compatibles
        $options = [];
        
        // Si c'est une imprimante Brother qui utilise PrintNode, on simplifie les options
        if ($printer->isBrotherLabel() && $printer->hasPrintNode()) {
            $options = [
                'size' => 'A4',  // Utiliser un format standard au lieu d'un format personnalisé
                'copies' => 1,
                'paper_source' => 'tray1', // Utiliser un bac standard
            ];
            
            // Journaliser l'adaptation des options pour Brother
            Log::info('PrintNode job for Brother printer - using simplified options', [
                'printer_id' => $printer->id,
                'printer_name' => $printer->name
            ]);
        } else {
            // Sinon utiliser les options normales
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
     * Get script tag to include QZ Tray JavaScript
     */
    public function getQzTrayScripts(): string
    {
        return '
            <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.0/qz-tray.min.js"></script>
            <script src="' . asset('js/qz-tray.js') . '?v=' . filemtime(public_path('js/qz-tray.js')) . '"></script>
        ';
    }

    /**
     * Get JavaScript to initiate printing with QZ Tray
     */
    public function getQzTrayPrintScript(array $printData, bool $autoprint = true, ?int $printJobId = null): string
    {
        // Escape JSON for JavaScript
        $jsonData = json_encode($printData);
        
        // Generate a unique ID for this print job for tracking
        $uniqueId = $printJobId ?? uniqid('qz_print_');
        
        // Return JavaScript code to initiate printing with enhanced error handling
        $script = "<script>
            // Store print data for reuse
            window.printData_{$uniqueId} = {$jsonData};
            
            // Print function with logging
            function executePrint_{$uniqueId}() {
                console.log('[QZ Print {$uniqueId}] Starting print job');
                
                // Update UI if elements exist
                const statusElement = document.getElementById('qz-status');
                if (statusElement) {
                    statusElement.className = 'badge bg-info';
                    statusElement.textContent = 'Impression en cours...';
                }
                
                // Ensure QZ Tray is available
                if (typeof window.QZTray === 'undefined') {
                    console.error('[QZ Print {$uniqueId}] QZ Tray not available');
                    
                    if (statusElement) {
                        statusElement.className = 'badge bg-warning';
                        statusElement.textContent = 'QZ Tray non disponible';
                    }
                    
                    // Retry after delay
                    setTimeout(executePrint_{$uniqueId}, 1000);
                    return;
                }
                
                // Send status to server if we have a job ID
                " . ($printJobId ? "
                fetch('/api/print-jobs/{$printJobId}/status', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({ status: 'printing' })
                });" : "") . "
                
                // Execute the print job
                window.QZTray.connect()
                    .then(() => {
                        console.log('[QZ Print {$uniqueId}] Connected, sending print job');
                        return window.QZTray.printFromController(window.printData_{$uniqueId});
                    })
                    .then(() => {
                        console.log('[QZ Print {$uniqueId}] Print job successful');
                        if (statusElement) {
                            statusElement.className = 'badge bg-success';
                            statusElement.textContent = 'Impression réussie';
                        }
                        
                        // Show success message
                        const successElement = document.getElementById('print-success');
                        if (successElement) {
                            successElement.style.display = 'block';
                            setTimeout(() => {
                                successElement.style.display = 'none';
                            }, 5000);
                        }
                        
                        // Update job status if we have a job ID
                        " . ($printJobId ? "
                        fetch('/api/print-jobs/{$printJobId}/status', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content')
                            },
                            body: JSON.stringify({ status: 'completed' })
                        });" : "") . "
                    })
                    .catch((error) => {
                        console.error('[QZ Print {$uniqueId}] Print error:', error);
                        
                        if (statusElement) {
                            statusElement.className = 'badge bg-danger';
                            statusElement.textContent = 'Erreur d\'impression';
                        }
                        
                        // Show error message
                        const errorElement = document.getElementById('qz-error');
                        if (errorElement) {
                            errorElement.textContent = 'Erreur d\'impression: ' + (error.message || error);
                            errorElement.style.display = 'block';
                        }
                        
                        // Update job status if we have a job ID
                        " . ($printJobId ? "
                        fetch('/api/print-jobs/{$printJobId}/status', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content')
                            },
                            body: JSON.stringify({ status: 'failed', error_message: error.message || String(error) })
                        });" : "") . "
                    });
            }
            
            document.addEventListener('DOMContentLoaded', function() {
                " . ($autoprint ? "
                // Auto print after a delay to ensure everything is loaded
                setTimeout(executePrint_{$uniqueId}, 1500);" : "") . "
                
                // Add print button click handler if it exists
                const printButton = document.getElementById('print-button');
                if (printButton) {
                    printButton.addEventListener('click', executePrint_{$uniqueId});
                }
                
                // Add reprint button click handler if it exists
                const reprintButton = document.getElementById('reprint');
                if (reprintButton) {
                    reprintButton.addEventListener('click', executePrint_{$uniqueId});
                }
            });
        </script>";
        
        return $script;
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
        $html .= '<span class="badge bg-secondary" id="qz-status">En attente</span>';
        $html .= '<button type="button" class="btn btn-primary btn-sm" id="print-button">Imprimer</button>';
        $html .= '</div>';
        
        // Error message container (hidden by default)
        $html .= '<div class="alert alert-danger mt-3" id="qz-error" style="display: none;"></div>';
        
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
        // In a real implementation, this would communicate with the printer
        // via QZ Tray to get the actual status
        
        // For now, simulate with a status check
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
}