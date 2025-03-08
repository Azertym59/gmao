<?php

namespace App\Http\Controllers\QrCode;

use App\Http\Controllers\Controller;
use App\Models\Chantier;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class ChantierQrCodeController extends Controller
{
    protected $qrCodeService;
    
    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }
    
    /**
     * Affiche l'étiquette QR code pour un chantier
     */
    public function printLabel($id)
    {
        $chantier = Chantier::with(['client', 'produits', 'produits.dalles', 'produits.dalles.modules'])
            ->findOrFail($id);
            
        $qrCode = $this->qrCodeService->generateChantierQrCode($id);
        
        return view('qrcodes.chantier.label', compact('chantier', 'qrCode'));
    }
    
    /**
     * Affiche les informations du chantier après scan du QR code
     */
    public function show($id)
    {
        $chantier = Chantier::with(['client', 'produits', 'produits.dalles', 'produits.dalles.modules'])
            ->findOrFail($id);
            
        return view('qrcodes.chantier.show', compact('chantier'));
    }
}