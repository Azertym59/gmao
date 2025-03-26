<?php

namespace App\Services;

use App\Models\EtiquetteTemplate;
use App\Models\Printer;
use App\Models\PrintJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class EtiquettePrintService
{
    protected $qzTrayService;
    protected $ezeepService;
    protected $printNodeService;

    public function __construct(QzTrayService $qzTrayService = null, EzeepService $ezeepService = null)
    {
        $this->qzTrayService = $qzTrayService;
        $this->ezeepService = $ezeepService;
    }

    /**
     * Render a template with provided data.
     *
     * @param EtiquetteTemplate $template
     * @param array $data
     * @return string The rendered HTML
     */
    public function renderTemplate(EtiquetteTemplate $template, array $data)
    {
        $templateData = json_decode($template->template_data, true);
        if (!$templateData) {
            return '<div class="error">Erreur: Données de template invalides</div>';
        }

        // Process special variables like QR codes and logo
        $data = $this->processSpecialVariables($template, $data);

        // Create base container div with correct dimensions
        $html = '<div class="template-container" style="width: ' . $template->width . 'mm; height: ' . $template->height . 'mm; position: relative; box-sizing: border-box;">';

        // Render each element
        foreach ($templateData as $element) {
            $html .= $this->renderElement($element, $data);
        }

        $html .= '</div>';
        return $html;
    }
    
    /**
     * Process special variables like QR codes and logos before rendering.
     *
     * @param EtiquetteTemplate $template
     * @param array $data
     * @return array The processed data array
     */
    protected function processSpecialVariables(EtiquetteTemplate $template, array $data)
    {
        // Handle QR code generation if needed
        if (!isset($data['qrcode']) && isset($data['qrcode_url'])) {
            // If we have a URL but not the actual QR code, try to generate it
            $qrCodeService = app(\App\Services\QrCodeService::class);
            if (filter_var($data['qrcode_url'], FILTER_VALIDATE_URL)) {
                // Generate the QR code as a data URL
                $data['qrcode'] = $qrCodeService->generateQrCodeImage($data['qrcode_url'], 300);
            }
        }

        // For specific entity types, generate special QR codes
        switch ($template->type) {
            case 'chantier':
                if (isset($data['id'])) {
                    $qrCodeService = app(\App\Services\QrCodeService::class);
                    $data['qrcode'] = $qrCodeService->generateQrCodeImage(
                        route('qrcode.chantier.show', $data['id']), 
                        300
                    );
                }
                break;
                
            case 'dalle':
                if (isset($data['id'])) {
                    $qrCodeService = app(\App\Services\QrCodeService::class);
                    $data['qrcode'] = $qrCodeService->generateQrCodeImage(
                        route('qrcode.dalle.show', $data['id']), 
                        300
                    );
                }
                break;
                
            case 'module':
                if (isset($data['id'])) {
                    $qrCodeService = app(\App\Services\QrCodeService::class);
                    $data['qrcode'] = $qrCodeService->generateQrCodeImage(
                        route('qrcode.module.show', $data['id']), 
                        300
                    );
                }
                break;
        }
        
        // Handle logo if not already set
        if (!isset($data['logo'])) {
            // Use a default logo path
            $data['logo'] = asset('images/logo-repair.png');
        }
        
        return $data;
    }

    /**
     * Render a single element with data substitution.
     *
     * @param array $element
     * @param array $data
     * @return string
     */
    protected function renderElement(array $element, array $data)
    {
        $style = "position: absolute; "
              . "left: {$element['x']}px; "
              . "top: {$element['y']}px; "
              . "width: {$element['width']}px; "
              . "height: {$element['height']}px; ";

        // Additional style based on element type
        switch ($element['type']) {
            case 'text':
                $content = $element['content'];
                $style .= "font-size: {$element['fontSize']}px; "
                       . "font-weight: {$element['fontWeight']}; "
                       . "color: {$element['color']}; "
                       . "text-align: {$element['textAlign']}; ";
                
                return "<div style=\"$style\">$content</div>";
                
            case 'variable':
                $content = $element['content'];
                // Process variable content
                if (preg_match('/\{\{(.*?)\}\}/', $content, $matches)) {
                    $varName = trim($matches[1]);
                    
                    // Special handling for certain variable names
                    if ($varName == 'qrcode' || $varName == 'qrcode_url') {
                        // This variable should be a QR code image
                        if (isset($data[$varName])) {
                            return "<div style=\"$style\"><img src=\"{$data[$varName]}\" style=\"width: 100%; height: 100%; object-fit: contain;\"/></div>";
                        } else {
                            // Placeholder image for preview
                            return "<div style=\"$style\"><img src=\"data:image/png;base64,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\" style=\"width: 100%; height: 100%; object-fit: contain;\"/></div>";
                        }
                    } else if ($varName == 'logo') {
                        // This variable should be the company logo
                        if (isset($data[$varName])) {
                            return "<div style=\"$style\"><img src=\"{$data[$varName]}\" style=\"width: 100%; height: 100%; object-fit: contain;\"/></div>";
                        } else {
                            // Placeholder image for logo preview
                            return "<div style=\"$style\"><img src=\"/images/logo-repair.png\" style=\"width: 100%; height: 100%; object-fit: contain;\"/></div>";
                        }
                    } else {
                        // Regular variable replacement
                        if (isset($data[$varName])) {
                            $content = $data[$varName];
                        } else {
                            // Add a visual indicator for missing variables in preview
                            $content = "<span style=\"color: red; text-decoration: underline;\">{{{$varName}}}</span>";
                        }
                    }
                }

                $style .= "font-size: {$element['fontSize']}px; "
                       . "font-weight: {$element['fontWeight']}; "
                       . "color: {$element['color']}; "
                       . "text-align: {$element['textAlign']}; ";
                
                return "<div style=\"$style\">$content</div>";

            case 'qrcode':
                // For QR code, we'll use an image tag
                $content = $element['content'] ?? '{{qrcode_url}}';
                $qrcodeUrl = '';
                
                // Check if this is a variable QR code
                if (preg_match('/\{\{(.*?)\}\}/', $content, $matches)) {
                    $varName = trim($matches[1]);
                    if (isset($data[$varName])) {
                        $qrcodeUrl = $data[$varName];
                    } else {
                        // Placeholder image for preview
                        $qrcodeUrl = 'data:image/png;base64,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';
                    }
                }
                
                return "<div style=\"$style\"><img src=\"$qrcodeUrl\" style=\"width: 100%; height: 100%; object-fit: contain;\"/></div>";

            case 'image':
                $src = $element['src'];
                return "<div style=\"$style\"><img src=\"$src\" style=\"width: 100%; height: 100%; object-fit: contain;\"/></div>";

            case 'rectangle':
                $style .= "background-color: {$element['backgroundColor']}; "
                       . "border: {$element['borderWidth']}px solid {$element['borderColor']}; "
                       . "border-radius: {$element['borderRadius']}px; ";
                return "<div style=\"$style\"></div>";

            case 'line':
                $style .= "background-color: {$element['color']}; ";
                return "<div style=\"$style\"></div>";

            default:
                return '';
        }
    }

    /**
     * Generate PDF from a template.
     *
     * @param EtiquetteTemplate $template
     * @param array $data
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generatePdf(EtiquetteTemplate $template, array $data)
    {
        $renderedHtml = $this->renderTemplate($template, $data);

        // Wrap in a proper HTML document
        $html = '<!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    @page {
                        margin: 0;
                        padding: 0;
                        size: ' . $template->width . 'mm ' . $template->height . 'mm;
                    }
                    body {
                        margin: 0;
                        padding: 0;
                        font-family: Arial, sans-serif;
                    }
                    .template-container {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: ' . $template->width . 'mm;
                        height: ' . $template->height . 'mm;
                        overflow: hidden;
                    }
                </style>
            </head>
            <body>' . $renderedHtml . '</body>
            </html>';

        // Generate PDF with custom paper size
        $pdf = Pdf::loadHtml($html);
        $pdf->setPaper([0, 0, $template->width * 2.83465, $template->height * 2.83465], 'portrait'); // Convert mm to points (1mm = 2.83465pt)
        
        return $pdf;
    }

    /**
     * Print a template on a specified printer.
     *
     * @param EtiquetteTemplate $template
     * @param array $data
     * @param Printer $printer
     * @return bool
     */
    public function printTemplate(EtiquetteTemplate $template, array $data, Printer $printer)
    {
        try {
            // Generate PDF
            $pdf = $this->generatePdf($template, $data);
            
            // Save to temporary file
            $tempFile = 'etiquettes/' . Str::random(16) . '.pdf';
            Storage::put('public/' . $tempFile, $pdf->output());
            $filePath = Storage::path('public/' . $tempFile);
            
            // Create a print job record
            $printJob = PrintJob::create([
                'printer_id' => $printer->id,
                'content_type' => 'etiquette',
                'content_id' => $template->id,
                'status' => 'pending',
                'file_path' => $tempFile,
            ]);
            
            // Send to printer based on printer type
            $result = false;
            
            if ($printer->type === 'printnode') {
                // Use PrintNode if available
                $apiKey = config('printing.printnode_api_key');
                if ($apiKey) {
                    $printNodeClient = new \PrintNode\Client();
                    $credentials = new \PrintNode\Credentials\ApiKey($apiKey);
                    $printNodeClient->setCredentials($credentials);
                    
                    $printJob = new \PrintNode\Entity\PrintJob();
                    $printJob->printer = $printer->printer_id;
                    $printJob->title = 'Etiquette: ' . $template->name;
                    $printJob->contentType = 'pdf_uri';
                    $printJob->content = url(Storage::url($tempFile));
                    $printJob->source = 'GMAO App';
                    
                    // Set options for Brother printer (DK-22205 Continuous Label)
                    if ($template->size_type === 'continuous') {
                        $printJob->options = [
                            'media' => 'dk2205',
                            'papertype' => 'continuous',
                            'rollwidth' => $template->width,
                            'rolllength' => $template->height
                        ];
                    }
                    
                    $response = $printNodeClient->createPrintJob($printJob);
                    $result = !empty($response);
                }
            } elseif ($printer->type === 'qztray') {
                // Use QZ Tray
                $result = $this->qzTrayService->printPDF(
                    $printer->name,
                    $filePath,
                    [
                        'media' => 'dk2205',
                        'papertype' => 'continuous',
                        'rollwidth' => $template->width,
                        'rolllength' => $template->height
                    ]
                );
            } elseif ($printer->type === 'ezeep') {
                // Use Ezeep service if available
                if ($this->ezeepService) {
                    $result = $this->ezeepService->printFile(
                        $printer->printer_id,
                        $filePath,
                        [
                            'media' => 'dk2205',
                            'papertype' => 'continuous',
                            'rollwidth' => $template->width,
                            'rolllength' => $template->height
                        ]
                    );
                }
            }
            
            // Update job status
            $printJob->update([
                'status' => $result ? 'completed' : 'failed',
                'completed_at' => $result ? now() : null,
            ]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error printing template: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Replace variables in a text with actual data.
     *
     * @param string $text
     * @param array $data
     * @return string
     */
    protected function replaceVariables($text, array $data)
    {
        return preg_replace_callback('/\{\{(.*?)\}\}/', function ($matches) use ($data) {
            $varName = trim($matches[1]);
            
            // Check if the variable exists in the data array
            if (isset($data[$varName])) {
                return $data[$varName];
            }
            
            // If variable is not found, return a placeholder
            Log::debug("Variable not found in template: {$varName}");
            return "<span class=\"text-red-500\">{{{$varName}}}</span>";
        }, $text);
    }
}
