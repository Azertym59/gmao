<?php

namespace App\Http\Controllers\QrCode;

use App\Http\Controllers\Controller;
use App\Models\Chantier;
use App\Models\Printer;
use App\Services\QzTrayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ChantierQrCodeController extends Controller
{
    protected $qzTrayService;

    public function __construct(QzTrayService $qzTrayService)
    {
        $this->qzTrayService = $qzTrayService;
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
        $qrCodeData = $this->qzTrayService->generateQrCode($url, 300);
        
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
            // Get default printer or specified printer
            $printer = null;
            
            if ($request->has('printer_id')) {
                $printer = Printer::findOrFail($request->printer_id);
            } else {
                $printer = Printer::where('is_default', true)->first();
                
                if (!$printer) {
                    return redirect()->back()
                        ->with('error', 'Aucune imprimante par défaut trouvée. Veuillez en configurer une.');
                }
            }
            
            // Generate QR code URL
            $url = route('qrcode.chantier.show', $chantier->id);
            
            // Prepare print data for a Brother DK-22205 continuous 62mm roll
            $printData = $this->qzTrayService->prepareQrCodePrint(
                $printer->name,
                $url,
                ['size' => 300, 'errorCorrection' => 'H'],
                [
                    'copies' => $request->input('copies', 1),
                    'size' => '62mm',       // Largeur fixe du rouleau
                    'height' => '100mm',    // Hauteur pour les étiquettes de chantier
                    'roll_type' => 'DK-22205',
                    'mediaType' => 'Continuous',
                    'ignoreHeight' => false,
                    'dpi' => $printer->options['dpi'] ?? 300
                ]
            );
            
            // Return view with label and print script
            return view('qrcodes.chantier.label', [
                'chantier' => $chantier,
                'printData' => $printData,
                'qzTrayService' => $this->qzTrayService
            ]);
            
        } catch (\Exception $e) {
            Log::error('Chantier QR code print error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'impression: ' . $e->getMessage());
        }
    }
}