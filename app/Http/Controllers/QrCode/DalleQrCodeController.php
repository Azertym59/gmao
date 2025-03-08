<?php

namespace App\Http\Controllers\QrCode;

use App\Http\Controllers\Controller;
use App\Models\Dalle;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class DalleQrCodeController extends Controller
{
    protected $qrCodeService;
    
    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }
    
    /**
     * Affiche l'étiquette QR code pour une dalle
     */
    public function printLabel($id)
    {
        $dalle = Dalle::with(['produit', 'produit.chantier', 'produit.chantier.client'])
            ->findOrFail($id);
            
        $qrCode = $this->qrCodeService->generateDalleQrCode($id);
        
        return view('qrcodes.dalle.label', compact('dalle', 'qrCode'));
    }
    
    /**
     * Affiche les informations de la dalle après scan du QR code
     * y compris les modules actuellement installés
     */
    public function show($id)
    {
        $dalle = Dalle::with([
            'produit', 
            'produit.chantier', 
            'produit.chantier.client',
            'modules'
        ])->findOrFail($id);
        
        return view('qrcodes.dalle.show', compact('dalle'));
    }
}