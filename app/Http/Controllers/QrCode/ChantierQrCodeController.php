<?php

namespace App\Http\Controllers\QrCode;

use App\Http\Controllers\Controller;
use App\Models\Chantier;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ChantierQrCodeController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Display QR code for a chantier
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chantier = Chantier::findOrFail($id);
        
        // Generate QR code data URL
        $url = route('qrcode.chantier.show', $chantier->id);
        $qrCodeData = $this->qrCodeService->generateQrCodeImage($url, 300);
        
        return view('qrcodes.chantier.show', [
            'chantier' => $chantier,
            'qrCode' => $qrCodeData
        ]);
    }

    /**
     * Print QR code label for a chantier
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function printLabel($id, Request $request)
    {
        $chantier = Chantier::findOrFail($id);
        
        try {
            // Generate QR code URL
            $url = route('qrcode.chantier.show', $chantier->id);
            
            // Generate QR code image
            $qrCodeData = $this->qrCodeService->generateQrCodeImage($url, 300);
            
            // Prepare print data for the view
            $printData = [
                'imageData' => $qrCodeData,
                'content' => $url,
                'options' => [
                    'copies' => $request->input('copies', 1),
                    'width' => 62,      // Largeur en mm
                    'height' => 100,    // Hauteur en mm
                ]
            ];
            
            // Return view with label and print script
            return view('qrcodes.chantier.label', [
                'chantier' => $chantier,
                'printData' => $printData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Chantier QR code print error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'impression: ' . $e->getMessage());
        }
    }
}