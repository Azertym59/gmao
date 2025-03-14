<?php

namespace App\Http\Controllers\QrCode;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Printer;
use App\Services\QzTrayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ModuleQrCodeController extends Controller
{
    protected $qzTrayService;

    public function __construct(QzTrayService $qzTrayService)
    {
        $this->qzTrayService = $qzTrayService;
    }

    /**
     * Display QR code for a module
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $module = Module::with(['dalle', 'dalle.produit', 'dalle.produit.chantier'])->findOrFail($id);
        
        // Generate QR code data URL
        $url = route('qrcode.module.show', $module->id);
        $qrCodeData = $this->qzTrayService->generateQrCode($url, 300);
        
        return view('qrcodes.module.show', [
            'module' => $module,
            'qrCode' => $qrCodeData
        ]);
    }

    /**
     * Print QR code label for a module
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function printLabel($id, Request $request)
    {
        $module = Module::with(['dalle', 'dalle.produit', 'dalle.produit.chantier'])->findOrFail($id);
        
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
            $url = route('qrcode.module.show', $module->id);
            
            // Prepare print data
            $printData = $this->qzTrayService->prepareQrCodePrint(
                $printer->name,
                $url,
                ['size' => 300],
                [
                    'copies' => $request->input('copies', 1),
                    'size' => $printer->type === 'thermal' ? '57mm' : 'A4'
                ]
            );
            
            // Return view with label and print script
            return view('qrcodes.module.label', [
                'module' => $module,
                'printData' => $printData,
                'qzTrayService' => $this->qzTrayService
            ]);
            
        } catch (\Exception $e) {
            Log::error('Module QR code print error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'impression: ' . $e->getMessage());
        }
    }
}