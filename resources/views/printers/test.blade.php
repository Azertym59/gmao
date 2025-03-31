@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Test d'impression QZ Tray</h1>
        <a href="{{ route('printers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center">
            <span>Retour</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Configuration QZ Tray</h2>
        
        <div class="mb-4">
            <p class="mb-2">Pour utiliser QZ Tray, vous devez :</p>
            <ol class="list-decimal pl-5 space-y-2">
                <li>Télécharger et installer <a href="https://qz.io/download/" target="_blank" class="text-blue-600 hover:underline">QZ Tray</a> sur votre ordinateur</li>
                <li>Lancer l'application QZ Tray (elle devrait apparaître dans la barre des tâches)</li>
                <li>Configurer votre imprimante Brother QL-820NW dans votre système d'exploitation</li>
                <li>Vérifier que QZ Tray détecte correctement votre imprimante</li>
                <li>Utiliser les outils de test ci-dessous pour vérifier la communication</li>
            </ol>
        </div>

        <div class="border-t pt-4">
            <h3 class="text-lg font-semibold mb-2">État de QZ Tray:</h3>
            <div id="qz-status" class="p-3 bg-gray-100 rounded mb-4">
                <p class="text-yellow-600">Vérification du statut de QZ Tray...</p>
            </div>
            
            <div id="qz-printers" class="hidden p-3 bg-gray-100 rounded mb-4">
                <h4 class="font-medium mb-2">Imprimantes détectées:</h4>
                <ul id="printer-list" class="list-disc pl-5"></ul>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Test d'impression</h2>
        
        <form id="test-print-form" action="{{ route('printers.print-test-qr') }}" method="POST" class="space-y-4">
            @csrf
            <div class="mb-4">
                <label for="printer_id" class="block text-sm font-medium text-gray-700 mb-1">Sélectionner l'imprimante:</label>
                <select id="printer_id" name="printer_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    @foreach($printers as $printer)
                        <option value="{{ $printer->id }}" {{ (isset($selectedPrinter) && $selectedPrinter->id == $printer->id) ? 'selected' : '' }}>
                            {{ $printer->name }} {{ $printer->is_default ? '(par défaut)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="border-t pt-4">
                <button type="button" id="test-connection-btn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Tester la connexion QZ Tray
                </button>
                
                <button type="submit" id="print-test-qr-btn" class="ml-2 bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
                    Imprimer un QR code test
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Test d'impression Brother QL-820NW</h2>
        
        <form id="test-brother-form" action="{{ route('printers.test-labels') }}" method="GET" class="space-y-4">
            <input type="hidden" name="printer_id" value="{{ $selectedPrinter ? $selectedPrinter->id : '' }}">
            
            <div class="mb-4">
                <p class="mb-2">Types d'étiquettes à tester:</p>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="test_formats[]" value="module" class="form-checkbox h-5 w-5 text-blue-600" checked>
                        <span class="ml-2">Module (29mm)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="test_formats[]" value="dalle" class="form-checkbox h-5 w-5 text-blue-600" checked>
                        <span class="ml-2">Dalle (38mm)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="test_formats[]" value="flightcase" class="form-checkbox h-5 w-5 text-blue-600" checked>
                        <span class="ml-2">Flight Case (62mm)</span>
                    </label>
                </div>
            </div>

            <div class="border-t pt-4">
                <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-4 rounded">
                    Tester l'impression d'étiquettes
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.1/qz-tray.js"></script>
<script>
    // QZ Tray initialization
    document.addEventListener('DOMContentLoaded', function() {
        const statusElement = document.getElementById('qz-status');
        const printersElement = document.getElementById('qz-printers');
        const printerListElement = document.getElementById('printer-list');
        const testConnectionBtn = document.getElementById('test-connection-btn');
        
        // Function to update status display
        function updateStatus(message, isError = false) {
            statusElement.innerHTML = `<p class="${isError ? 'text-red-600' : 'text-green-600'}">${message}</p>`;
        }
        
        // Check if QZ Tray is installed and running
        if (typeof qz !== 'undefined') {
            // Try to establish a connection with QZ Tray
            qz.api.setSha256Type(function(data) { return shajs('sha256').update(data).digest('hex'); });
            
            qz.websocket.connect().then(function() {
                updateStatus('✅ QZ Tray est connecté et prêt à être utilisé');
                
                // Get list of printers
                return qz.printers.find();
            }).then(function(printers) {
                printersElement.classList.remove('hidden');
                
                if (printers && printers.length) {
                    let printerItems = '';
                    printers.forEach(function(printer) {
                        printerItems += `<li>${printer}</li>`;
                    });
                    printerListElement.innerHTML = printerItems;
                } else {
                    printerListElement.innerHTML = '<li>Aucune imprimante détectée</li>';
                }
            }).catch(function(err) {
                console.error(err);
                updateStatus('❌ Erreur de connexion à QZ Tray: ' + err.message, true);
            });
        } else {
            updateStatus('❌ QZ Tray n\'est pas installé ou n\'est pas accessible', true);
        }
        
        // Test connection button handler
        if (testConnectionBtn) {
            testConnectionBtn.addEventListener('click', function() {
                if (typeof qz !== 'undefined') {
                    qz.websocket.connect().then(function() {
                        alert('Connexion à QZ Tray réussie!');
                        
                        // Get list of printers
                        return qz.printers.find();
                    }).then(function(printers) {
                        printersElement.classList.remove('hidden');
                        
                        if (printers && printers.length) {
                            let printerItems = '';
                            printers.forEach(function(printer) {
                                printerItems += `<li>${printer}</li>`;
                            });
                            printerListElement.innerHTML = printerItems;
                        } else {
                            printerListElement.innerHTML = '<li>Aucune imprimante détectée</li>';
                        }
                    }).catch(function(err) {
                        console.error(err);
                        alert('Erreur de connexion à QZ Tray: ' + err.message);
                    });
                } else {
                    alert('QZ Tray n\'est pas installé ou n\'est pas accessible');
                }
            });
        }
    });
</script>
@endpush
@endsection