<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class QzTrayService
{
    /**
     * Generate QR code as base64 image
     *
     * @param string $data QR code data
     * @param int $size Size of QR code in pixels
     * @param string $errorCorrection Error correction level (L, M, Q, H)
     * @return string Base64 encoded image
     */
    public function generateQrCode($data, $size = 250, $errorCorrection = 'H')
    {
        try {
            // Generate QR code with high error correction
            $qrCode = QrCode::format('png')
                ->size($size)
                ->errorCorrection($errorCorrection)
                ->generate($data);

            // Convert to base64
            $base64 = 'data:image/png;base64,' . base64_encode($qrCode);
            
            return $base64;
        } catch (\Exception $e) {
            Log::error('Error generating QR code: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Prepare print data for QZ Tray
     *
     * @param string $printerName Name of the printer
     * @param string $imageData Base64 encoded image
     * @param array $options Printing options
     * @return array Print data for QZ Tray
     */
    public function preparePrintData($printerName, $imageData, array $options = [])
    {
        // Default options
        $defaultOptions = [
            'size' => '57mm',            // Default label size
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
     *
     * @param string $printerName Printer name
     * @param string $data QR code data
     * @param array $qrOptions QR code generation options
     * @param array $printOptions Printing options
     * @return array Print data for QZ Tray
     */
    public function prepareQrCodePrint($printerName, $data, array $qrOptions = [], array $printOptions = [])
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
     * Get script tag to include QZ Tray JavaScript
     *
     * @return string HTML script tag
     */
    public function getQzTrayScript()
    {
        return '<script src="' . asset('js/qz-tray.js') . '"></script>';
    }

    /**
     * Get JavaScript to initiate printing with QZ Tray
     *
     * @param array $printData Print data for QZ Tray
     * @return string JavaScript code
     */
    public function getQzTrayPrintScript(array $printData)
    {
        // Escape JSON for JavaScript
        $jsonData = json_encode($printData);
        
        // Return JavaScript code to initiate printing
        return "<script>
            document.addEventListener('DOMContentLoaded', function() {
                // Wait for QZ Tray to initialize
                setTimeout(function() {
                    window.QZTray.printFromController($jsonData);
                }, 1000);
            });
        </script>";
    }
}