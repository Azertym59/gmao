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
}