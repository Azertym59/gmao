<?php

namespace App\Http\Controllers\QrCode;

use App\Http\Controllers\Controller;
use App\Models\Dalle;
use App\Models\Printer;
use App\Services\QzTrayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DalleQrCodeController extends Controller
{
    protected $qzTrayService;

    public function __construct(QzTrayService $qzTrayService)
    {
        $this->qzTrayService = $qzTrayService;
    }

    /**
     * Display QR code for a dalle
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dalle = Dalle::with('produit.chantier')->findOrFail($id);
        
        // Generate QR code data URL
        $url = route('qrcode.dalle.show', $dalle->id);
        $qrCodeData = $this->qzTrayService->generateQrCode($url, 150);
        
        return view('qrcodes.dalle.show', [
            'dalle' => $dalle,
            'qrCode' => $qrCodeData
        ]);
    }

    /**
     * Print QR code label for a dalle
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function printLabel($id, Request $request)
    {
        $dalle = Dalle::with(['produit.chantier', 'modules.interventions'])->findOrFail($id);
        
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
            
            // Get latest repair date
            $latestIntervention = $dalle->modules->flatMap(function ($module) {
                return $module->interventions;
            })->sortByDesc('date_debut')->first();
            
            $repairDate = $latestIntervention ? $latestIntervention->date_debut : null;
            
            // Generate QR code URL
            $url = route('qrcode.dalle.show', $dalle->id);
            
            // Prepare print data
            $printData = $this->qzTrayService->prepareQrCodePrint(
                $printer->name,
                $url,
                ['size' => 150, 'margin' => 1],
                [
                    'copies' => $request->input('copies', 1),
                    'size' => '62mm',
                    'height' => '20mm',
                    'logoUrl' => asset('images/Logo rectangle V2.png'),
                    'clientName' => $dalle->produit->chantier->client->nom ?? '',
                    'chantierReference' => $dalle->produit->chantier->reference ?? '',
                    'reference' => $dalle->reference_dalle ?: 'Dalle #' . $dalle->id,
                    'repairDate' => $repairDate ? \Carbon\Carbon::parse($repairDate)->format('d/m/Y') : null
                ]
            );
            
            // Return view with label and print script
            return view('qrcodes.dalle.label', [
                'dalle' => $dalle,
                'printData' => $printData,
                'qzTrayService' => $this->qzTrayService,
                'repairDate' => $repairDate
            ]);
            
        } catch (\Exception $e) {
            Log::error('Dalle QR code print error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'impression: ' . $e->getMessage());
        }
    }
}