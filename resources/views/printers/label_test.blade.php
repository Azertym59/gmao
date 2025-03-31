@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Test d'étiquettes Brother QL-820NW</h1>
        <a href="{{ route('printers.test-qz-tray') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center">
            <span>Retour au test</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Configuration pour Brother QL-820NW avec rouleau DK-22505</h2>
        
        <div class="mb-4">
            <p class="mb-2"><strong>Imprimante sélectionnée:</strong> {{ $printer->name }}</p>
            <p class="mb-2"><strong>Type de rouleau:</strong> DK-22505 (Ruban continu 62mm)</p>
        </div>

        <div id="qz-status" class="p-3 bg-gray-100 rounded mb-4">
            <p class="text-yellow-600">Vérification du statut de QZ Tray...</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Module Label -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-3">Étiquette Module (29mm)</h3>
            
            <div class="border rounded p-3 mb-4 flex justify-center">
                <img src="data:image/png;base64,{{ $moduleQrBase64 }}" alt="QR Code Module" class="h-32">
            </div>
            
            <div class="text-center">
                <button class="print-btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded" 
                        data-type="module" 
                        data-width="29" 
                        data-height="90"
                        data-printer="{{ $printer->name }}">
                    Imprimer
                </button>
            </div>
        </div>
        
        <!-- Dalle Label -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-3">Étiquette Dalle (38mm)</h3>
            
            <div class="border rounded p-3 mb-4 flex justify-center">
                <img src="data:image/png;base64,{{ $dalleQrBase64 }}" alt="QR Code Dalle" class="h-32">
            </div>
            
            <div class="text-center">
                <button class="print-btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded" 
                        data-type="dalle"
                        data-width="38" 
                        data-height="90"
                        data-printer="{{ $printer->name }}">
                    Imprimer
                </button>
            </div>
        </div>
        
        <!-- Flightcase Label -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-3">Étiquette FlightCase (62mm)</h3>
            
            <div class="border rounded p-3 mb-4 flex justify-center">
                <img src="data:image/png;base64,{{ $flightcaseQrBase64 }}" alt="QR Code FlightCase" class="h-32">
            </div>
            
            <div class="text-center">
                <button class="print-btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded" 
                        data-type="flightcase"
                        data-width="62" 
                        data-height="100"
                        data-printer="{{ $printer->name }}">
                    Imprimer
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Journal des impressions</h2>
        <div id="print-log" class="bg-gray-100 p-4 rounded h-40 overflow-y-auto font-mono text-sm">
            <p>Journal d'impression...</p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.1/qz-tray.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusElement = document.getElementById('qz-status');
        const printLogElement = document.getElementById('print-log');
        const printButtons = document.querySelectorAll('.print-btn');
        
        // Function to update status display
        function updateStatus(message, isError = false) {
            statusElement.innerHTML = `<p class="${isError ? 'text-red-600' : 'text-green-600'}">${message}</p>`;
        }
        
        // Function to log messages
        function logMessage(message, isError = false) {
            const now = new Date().toLocaleTimeString();
            const logClass = isError ? 'text-red-600' : 'text-gray-800';
            printLogElement.innerHTML += `<p class="${logClass}">[${now}] ${message}</p>`;
            printLogElement.scrollTop = printLogElement.scrollHeight;
        }
        
        // Check if QZ Tray is installed and running
        if (typeof qz !== 'undefined') {
            // Try to establish a connection with QZ Tray
            qz.api.setSha256Type(function(data) { return shajs('sha256').update(data).digest('hex'); });
            
            qz.websocket.connect().then(function() {
                updateStatus('✅ QZ Tray est connecté et prêt à être utilisé');
                logMessage('Connecté à QZ Tray avec succès');
                
                // Get list of printers
                return qz.printers.find();
            }).then(function(printers) {
                if (printers && printers.length) {
                    logMessage(`${printers.length} imprimantes détectées: ${printers.join(', ')}`);
                } else {
                    logMessage('Aucune imprimante détectée', true);
                }
            }).catch(function(err) {
                console.error(err);
                updateStatus('❌ Erreur de connexion à QZ Tray: ' + err.message, true);
                logMessage('Erreur de connexion à QZ Tray: ' + err.message, true);
            });
        } else {
            updateStatus('❌ QZ Tray n\'est pas installé ou n\'est pas accessible', true);
            logMessage('QZ Tray n\'est pas installé ou n\'est pas accessible', true);
        }
        
        // Print button handler for each label type
        printButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const labelType = this.getAttribute('data-type');
                const width = this.getAttribute('data-width');
                const height = this.getAttribute('data-height');
                const printerName = this.getAttribute('data-printer');
                
                // Get the appropriate QR code base64 data
                let qrBase64;
                switch(labelType) {
                    case 'module':
                        qrBase64 = @json('data:image/png;base64,' . $moduleQrBase64);
                        break;
                    case 'dalle':
                        qrBase64 = @json('data:image/png;base64,' . $dalleQrBase64);
                        break;
                    case 'flightcase':
                        qrBase64 = @json('data:image/png;base64,' . $flightcaseQrBase64);
                        break;
                }
                
                if (typeof qz === 'undefined') {
                    logMessage('QZ Tray n\'est pas installé ou n\'est pas accessible', true);
                    return;
                }
                
                logMessage(`Préparation de l'impression d'étiquette ${labelType} (${width}x${height}mm)...`);
                
                qz.websocket.connect().then(function() {
                    // Create printer config for Brother QL-820NW
                    const config = qz.configs.create(printerName, {
                        altPrinting: false,
                        copies: 1,
                        density: "300",
                        duplex: false,
                        interpolation: "nearest-neighbor",
                        jobName: `Test étiquette ${labelType}`,
                        margins: {
                            top: 0,
                            right: 0,
                            bottom: 0,
                            left: 0
                        },
                        orientation: "portrait",
                        paperThickness: null,
                        printerTray: null,
                        rasterize: true,
                        rollOver: true,
                        rotation: 0,
                        scaleContent: true,
                        size: {
                            width: width,
                            height: height,
                            units: "mm"
                        },
                        // Brother specific options
                        options: {
                            media: "dk22505",
                            paperType: "continuous",
                            rollWidth: width,
                            rollLength: height
                        }
                    });
                    
                    logMessage(`Configuration de l'imprimante: ${printerName}`);
                    
                    // Create printing data (HTML with QR code)
                    const currentDate = new Date().toLocaleString();
                    const htmlData = [
                        `<!DOCTYPE html>
                        <html>
                        <head>
                            <style>
                                @page {
                                    size: ${width}mm ${height}mm;
                                    margin: 0;
                                }
                                body {
                                    margin: 0;
                                    padding: 0;
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: center;
                                    align-items: center;
                                    width: ${width}mm;
                                    height: ${height}mm;
                                    box-sizing: border-box;
                                }
                                .qr-container {
                                    text-align: center;
                                    width: 100%;
                                }
                                .qr-image {
                                    max-width: 80%;
                                    max-height: 80%;
                                }
                                .label-title {
                                    font-family: Arial, sans-serif;
                                    font-size: 10pt;
                                    font-weight: bold;
                                    margin-bottom: 3mm;
                                }
                                .label-info {
                                    font-family: Arial, sans-serif;
                                    font-size: 8pt;
                                    margin-top: 2mm;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="qr-container">
                                <div class="label-title">Test Étiquette ${labelType.toUpperCase()}</div>
                                <img src="${qrBase64}" class="qr-image" />
                                <div class="label-info">${width}x${height}mm - ${currentDate}</div>
                            </div>
                        </body>
                        </html>`
                    ];
                    
                    logMessage(`Envoi à l'imprimante... (taille: ${width}x${height}mm)`);
                    
                    // Print HTML
                    return qz.print(config, htmlData);
                }).then(function() {
                    logMessage(`✅ Impression ${labelType} envoyée avec succès!`);
                }).catch(function(err) {
                    console.error(err);
                    logMessage(`❌ Erreur d'impression ${labelType}: ${err.message}`, true);
                });
            });
        });
    });
</script>
@endpush
@endsection