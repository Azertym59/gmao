<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Génère un QR code pour un chantier
     */
    public function generateChantierQrCode($chantierId)
    {
        return QrCode::size(300)->generate(route('qrcode.chantier.show', $chantierId));
    }
    
    /**
     * Génère un QR code pour une dalle
     */
    public function generateDalleQrCode($dalleId)
    {
        return QrCode::size(300)->generate(route('qrcode.dalle.show', $dalleId));
    }
    
    /**
     * Génère un QR code pour un module
     */
    public function generateModuleQrCode($moduleId)
    {
        return QrCode::size(300)->generate(route('qrcode.module.show', $moduleId));
    }
}