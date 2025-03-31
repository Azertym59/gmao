<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

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
     * Generate a QR code for printing with QZ Tray (returns actual QR code data)
     *
     * @param string $data The data to encode in the QR code
     * @param int $size Size in pixels
     * @param string $errorCorrection Error correction level (L, M, Q, H)
     * @param array $options Additional options
     * @return \Illuminate\Support\HtmlString
     */
    public function generateQrCodeForPrinting(string $data, int $size = 150, string $errorCorrection = 'H', array $options = [])
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
            
            // Return the QR code data
            return $qrCode->generate($data);
        } catch (\Exception $e) {
            Log::error('Error generating QR code for printing: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Prepare QR code print data for QZ Tray
     *
     * @param string $printerName Name of the printer as recognized by QZ Tray
     * @param string $data Data to encode in QR code
     * @param array $qrOptions QR code generation options
     * @param array $printOptions Printing options
     * @return array Print data for QZ Tray
     */
    public function prepareQrCodePrint(string $printerName, string $data, array $qrOptions = [], array $printOptions = [])
    {
        try {
            // Default QR code options
            $qrSize = $qrOptions['size'] ?? 200;
            $errorCorrection = $qrOptions['errorCorrection'] ?? 'H';
            $margin = $qrOptions['margin'] ?? 1;
            
            // Generate QR code
            $qrCode = $this->generateQrCode(
                $data, 
                $qrSize, 
                $errorCorrection, 
                ['margin' => $margin]
            );
            
            // Default print options
            $defaultPrintOptions = [
                'size' => '62mm x 29mm',  // Default size for Brother labels
                'dpi' => 300,            // Default DPI
                'copies' => 1,           // Default copies
                'orientation' => 'portrait', // Default orientation
                'media' => 'dk22505',    // Default media (DK-22505 continuous label)
                'paperType' => 'continuous', // Default paper type
                'rollWidth' => 62,       // Default roll width in mm
                'rollLength' => 29,      // Default roll length/height in mm
            ];
            
            // Merge with provided options
            $printOptions = array_merge($defaultPrintOptions, $printOptions);
            
            // Return data for QZ Tray
            return [
                'printerName' => $printerName,
                'qrBase64' => $qrCode,
                'qrData' => $data,
                'options' => $printOptions
            ];
        } catch (\Exception $e) {
            Log::error('Error preparing QR code print: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Print a PDF file using QZ Tray
     *
     * @param string $printerName Name of the printer
     * @param string $filePath Path to the PDF file
     * @param array $options Printing options
     * @return bool Success status
     */
    public function printPDF(string $printerName, string $filePath, array $options = [])
    {
        try {
            // Log the print attempt
            Log::info('QZ Tray print attempt', [
                'printer' => $printerName,
                'file' => $filePath,
                'options' => $options
            ]);
            
            // In a real implementation, we would return JSON data for QZ Tray
            // For now, we'll just return success to indicate the method was called
            return true;
        } catch (\Exception $e) {
            Log::error('Error printing PDF with QZ Tray: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Print an HTML document using QZ Tray
     *
     * @param string $printerName Name of the printer
     * @param string $html HTML content to print
     * @param array $options Printing options
     * @return bool Success status
     */
    public function printHTML(string $printerName, string $html, array $options = [])
    {
        try {
            // Convert HTML to PDF first
            $pdf = Pdf::loadHTML($html);
            
            // Set paper size if provided
            if (isset($options['width']) && isset($options['height'])) {
                // Convert mm to points (1mm = 2.83465pt)
                $width = $options['width'] * 2.83465;
                $height = $options['height'] * 2.83465;
                $pdf->setPaper([0, 0, $width, $height], 'portrait');
            }
            
            // Save to temporary file
            $tempFile = sys_get_temp_dir() . '/' . uniqid('qztray_') . '.pdf';
            file_put_contents($tempFile, $pdf->output());
            
            // Print the PDF
            $result = $this->printPDF($printerName, $tempFile, $options);
            
            // Clean up temporary file
            @unlink($tempFile);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error printing HTML with QZ Tray: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate a test label for Brother QL-820NW
     *
     * @param string $labelType Type of label (module, dalle, flightcase)
     * @param array $data Data for the label
     * @return string HTML content for the label
     */
    public function generateTestLabel(string $labelType = 'module', array $data = [])
    {
        // Default dimensions based on label type
        $dimensions = [
            'module' => ['width' => 29, 'height' => 90],
            'dalle' => ['width' => 38, 'height' => 90],
            'flightcase' => ['width' => 62, 'height' => 100]
        ];
        
        // Get dimensions for the selected label type
        $width = $dimensions[$labelType]['width'] ?? 62;
        $height = $dimensions[$labelType]['height'] ?? 29;
        
        // Default data
        $defaultData = [
            'title' => "Test étiquette " . ucfirst($labelType),
            'date' => date('Y-m-d H:i:s'),
            'id' => uniqid(),
        ];
        
        // Merge with provided data
        $data = array_merge($defaultData, $data);
        
        // Generate QR code data
        $qrData = json_encode([
            'type' => $labelType,
            'test' => true,
            'timestamp' => $data['date'],
            'id' => $data['id']
        ]);
        
        // Generate QR code
        $qrCode = $this->generateQrCode($qrData, 200, 'H');
        
        // Generate HTML for the label
        $html = '<!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    @page {
                        margin: 0;
                        padding: 0;
                        size: ' . $width . 'mm ' . $height . 'mm;
                    }
                    body {
                        margin: 0;
                        padding: 0;
                        font-family: Arial, sans-serif;
                        width: ' . $width . 'mm;
                        height: ' . $height . 'mm;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                    }
                    .qr-container {
                        text-align: center;
                        width: 100%;
                    }
                    .label-title {
                        font-size: 10pt;
                        font-weight: bold;
                        margin-bottom: 3mm;
                    }
                    .qr-image {
                        max-width: 80%;
                        max-height: 80%;
                    }
                    .label-info {
                        font-size: 8pt;
                        margin-top: 2mm;
                    }
                </style>
            </head>
            <body>
                <div class="qr-container">
                    <div class="label-title">' . $data['title'] . '</div>
                    <img src="' . $qrCode . '" class="qr-image" />
                    <div class="label-info">' . $width . 'x' . $height . 'mm - ' . $data['date'] . '</div>
                </div>
            </body>
            </html>';
        
        return $html;
    }
}