<?php

namespace App\Http\Controllers\QrCode;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class ModuleQrCodeController extends Controller
{
    protected $qrCodeService;
    
    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }
    
    /**
     * Affiche l'étiquette QR code pour un module
     */
    public function printLabel($id)
    {
        $module = Module::with(['dalle', 'dalle.produit', 'dalle.produit.chantier'])
            ->findOrFail($id);
            
        $qrCode = $this->qrCodeService->generateModuleQrCode($id);
        
        return view('qrcodes.module.label', compact('module', 'qrCode'));
    }
    
    /**
     * Affiche les informations du module après scan du QR code
     * y compris l'historique des interventions
     */
    public function show($id)
    {
        $module = Module::with([
            'dalle', 
            'dalle.produit', 
            'dalle.produit.chantier',
            'interventions',
            'interventions.diagnostics',
            'interventions.reparations'
        ])->findOrFail($id);
        
        return view('qrcodes.module.show', compact('module'));
    }
}