@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Résultat du test d'impression QZ Tray</h1>
        <a href="{{ route('printers.test-qz-tray') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center">
            <span>Retour au test</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Impression de test</h2>
        
        <div class="mb-4">
            <p class="mb-2"><strong>Imprimante:</strong> {{ $printer->name }}</p>
            <p class="mb-2"><strong>Type:</strong> {{ $printer->type }}</p>
        </div>

        <div id="print-status" class="p-3 bg-gray-100 rounded mb-4">
            <p class="text-yellow-600">Préparation de l'impression...</p>
        </div>

        <div class="flex justify-center mb-6">
            <div id="qr-preview" class="border p-4 rounded" style="width: 300px; height: 300px; display: flex; align-items: center; justify-content: center;">
                <div class="text-center">
                    <p class="mb-2 text-gray-700">Aperçu QR Code</p>
                    <div id="qr-image" class="mx-auto" style="width: 200px; height: 200px; background-color: #f3f4f6;"></div>
                </div>
            </div>
        </div>

        <div class="border-t pt-4 flex justify-center">
            <button id="print-now-btn" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
                Imprimer
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.1/qz-tray.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const printStatusElement = document.getElementById('print-status');
        const printNowBtn = document.getElementById('print-now-btn');
        const qrImageElement = document.getElementById('qr-image');
        
        // Function to update status display
        function updateStatus(message, isError = false) {
            printStatusElement.innerHTML = `<p class="${isError ? 'text-red-600' : 'text-green-600'}">${message}</p>`;
        }
        
        // Print data from controller
        const printData = @json($printData);
        
        // Display QR code in preview
        if (printData && printData.qrBase64) {
            qrImageElement.innerHTML = `<img src="${printData.qrBase64}" style="max-width: 100%; max-height: 100%;" />`;
        }
        
        // Print now button handler
        if (printNowBtn) {
            printNowBtn.addEventListener('click', function() {
                if (typeof qz === 'undefined') {
                    updateStatus('❌ QZ Tray n\'est pas installé ou n\'est pas accessible', true);
                    return;
                }
                
                updateStatus('Connexion à QZ Tray...');
                
                qz.websocket.connect().then(function() {
                    updateStatus('Préparation de l\'impression...');
                    
                    // Get printer configuration
                    const printerName = printData.printerName || '{{ $printer->name }}';
                    const config = qz.configs.create(printerName);
                    
                    // Set printer options for Brother QL-820NW
                    config.reconfigure({
                        altPrinting: false,
                        copies: 1,
                        density: printData.options?.dpi || "300",
                        duplex: false,
                        interpolation: "nearest-neighbor",
                        jobName: "Test QR Code",
                        margins: {
                            top: 1,
                            right: 1,
                            bottom: 1,
                            left: 1
                        },
                        orientation: "portrait",
                        paperThickness: null,
                        printerTray: null,
                        rasterize: true,
                        rollOver: true,
                        rotation: 0,
                        scaleContent: true,
                        size: {
                            width: printData.options?.width || 62,
                            height: printData.options?.height || 29,
                            units: "mm"
                        }
                    });
                    
                    // Create printing data (HTML with QR code)
                    const htmlData = [
                        `<!DOCTYPE html>
                        <html>
                        <head>
                            <style>
                                body {
                                    margin: 0;
                                    padding: 0;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    width: ${printData.options?.width || 62}mm;
                                    height: ${printData.options?.height || 29}mm;
                                }
                                .qr-container {
                                    text-align: center;
                                }
                                .qr-image {
                                    max-width: 90%;
                                    max-height: 90%;
                                }
                                .qr-text {
                                    font-family: Arial, sans-serif;
                                    font-size: 8pt;
                                    margin-top: 2mm;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="qr-container">
                                <img src="${printData.qrBase64}" class="qr-image" />
                                <div class="qr-text">Test QR Code - ${new Date().toLocaleString()}</div>
                            </div>
                        </body>
                        </html>`
                    ];
                    
                    updateStatus('Envoi à l\'imprimante...');
                    
                    // Print HTML
                    return qz.print(config, htmlData);
                }).then(function() {
                    updateStatus('✅ Impression envoyée avec succès!');
                }).catch(function(err) {
                    console.error(err);
                    updateStatus('❌ Erreur d\'impression: ' + err.message, true);
                });
            });
        }
    });
</script>
@endpush
@endsection