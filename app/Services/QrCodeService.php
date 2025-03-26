<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

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

    /**
     * Générer un QR code en PNG avec des options avancées
     *
     * @param string $data Données à encoder
     * @param int $size Taille en pixels
     * @param string $errorCorrection Niveau de correction d'erreur
     * @param array $options Options supplémentaires
     * @return string Image en base64
     */
    public function generateQrCodeImage(string $data, int $size = 300, string $errorCorrection = 'H', array $options = []): string
    {
        $margin = $options['margin'] ?? 1;
        $qrCode = QrCode::format('png')
            ->size($size)
            ->margin($margin)
            ->errorCorrection($errorCorrection)
            ->generate($data);
            
        return 'data:image/png;base64,' . base64_encode($qrCode);
    }
    
    /**
     * Générer un QR code SVG
     *
     * @param string $data Données à encoder
     * @param int $size Taille en pixels
     * @param string $errorCorrection Niveau de correction d'erreur
     * @param array $options Options supplémentaires
     * @return string Image SVG
     */
    public function generateQrCodeSvg(string $data, int $size = 300, string $errorCorrection = 'H', array $options = []): string
    {
        $margin = $options['margin'] ?? 1;
        return QrCode::format('svg')
            ->size($size)
            ->margin($margin)
            ->errorCorrection($errorCorrection)
            ->generate($data);
    }
}