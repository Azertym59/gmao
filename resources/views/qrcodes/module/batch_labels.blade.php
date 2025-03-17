<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Impression en masse des QR codes') }} ({{ $count }} modules)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Informations d'impression -->
                    <div class="mb-6">
                        <div class="p-4 rounded-md bg-blue-50 mb-4">
                            <h3 class="font-semibold text-lg text-blue-800 mb-2">Informations d'impression</h3>
                            <p><strong>Imprimante :</strong> {{ $printer->name }}</p>
                            <p><strong>Type :</strong> {{ ucfirst($printer->type) }}</p>
                            <p><strong>Nombre de QR codes :</strong> {{ $count }}</p>
                        </div>
                        
                        <!-- Statut et boutons -->
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="badge bg-secondary px-3 py-1 rounded-full text-white bg-gray-500" id="qz-status">En attente</span>
                            </div>
                            <div>
                                <button type="button" id="print-all-button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    {{ __('Imprimer tous les QR codes') }}
                                </button>
                            </div>
                        </div>
                        
                        <!-- Messages d'erreurs / succès -->
                        <div class="mt-4">
                            <div class="alert alert-danger bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded mb-3" id="qz-error" style="display: none;"></div>
                            <div class="alert alert-success bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded" id="print-success" style="display: none;">Impression réussie</div>
                        </div>
                    </div>
                    
                    <!-- Prévisualisations des QR codes -->
                    <div class="mt-6">
                        <h3 class="font-semibold text-lg mb-3">Prévisualisations des QR codes</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($batchPrintData as $index => $data)
                                <div class="border rounded-md overflow-hidden hover:shadow-md">
                                    <div class="bg-gray-50 px-4 py-2 border-b">
                                        <h4 class="font-medium">{{ $data['module']->reference_module ?? "Module #" . $data['module']->id }}</h4>
                                    </div>
                                    <div class="p-4 text-center">
                                        <img src="{{ $data['printData']['imageData'] }}" alt="QR Code" class="mx-auto h-36 w-36 object-contain mb-3">
                                        <p class="text-sm text-gray-600">
                                            <strong>Dalle:</strong> {{ $data['module']->dalle->reference_dalle ?? "Dalle #" . $data['module']->dalle->id }}
                                        </p>
                                        <button type="button" class="print-single-button mt-3 px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm" 
                                                data-index="{{ $index }}">
                                            Imprimer ce QR code
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Bouton de retour -->
                    <div class="mt-6 flex justify-between">
                        <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                            {{ __('Retour') }}
                        </a>
                        <p class="text-gray-500 text-sm italic">
                            Note: Le QZ Tray doit être installé et en cours d'exécution pour que l'impression fonctionne.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Inclusion des scripts QZ Tray -->
    {!! $qzTrayService->getQzTrayScripts() !!}
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Stocker toutes les données d'impression
            const printDataCollection = @json($batchPrintData);
            let currentPrintIndex = 0;
            let isPrinting = false;
            
            // Référence aux éléments d'interface
            const qzStatusElement = document.getElementById('qz-status');
            const qzErrorElement = document.getElementById('qz-error');
            const printSuccessElement = document.getElementById('print-success');
            const printAllButton = document.getElementById('print-all-button');
            const printSingleButtons = document.querySelectorAll('.print-single-button');
            
            // Fonction pour imprimer un QR code unique
            function printSingle(index) {
                if (isPrinting) return;
                isPrinting = true;
                
                // Mettre à jour le statut
                updateStatus('printing');
                
                const printData = printDataCollection[index].printData;
                
                // Imprimer avec QZ Tray
                window.QZTray.connect()
                    .then(() => {
                        console.log(`[QZ Print] Impression du QR code ${index + 1}`);
                        return window.QZTray.printFromController(printData);
                    })
                    .then(() => {
                        console.log(`[QZ Print] QR code ${index + 1} imprimé avec succès`);
                        updateStatus('success');
                        isPrinting = false;
                    })
                    .catch((error) => {
                        console.error(`[QZ Print] Erreur lors de l'impression du QR code ${index + 1}:`, error);
                        updateStatus('error', error.message || String(error));
                        isPrinting = false;
                    });
            }
            
            // Fonction pour imprimer tous les QR codes en séquence
            function printAll() {
                if (isPrinting) return;
                
                // Réinitialiser l'index
                currentPrintIndex = 0;
                
                // Commencer l'impression séquentielle
                printNext();
            }
            
            // Fonction pour imprimer le QR code suivant dans la séquence
            function printNext() {
                if (currentPrintIndex >= printDataCollection.length) {
                    // Tous les QR codes ont été imprimés
                    updateStatus('success', `Tous les QR codes (${printDataCollection.length}) ont été imprimés`);
                    return;
                }
                
                isPrinting = true;
                updateStatus('printing', `Impression du QR code ${currentPrintIndex + 1} sur ${printDataCollection.length}`);
                
                const printData = printDataCollection[currentPrintIndex].printData;
                
                // Imprimer avec QZ Tray
                window.QZTray.connect()
                    .then(() => {
                        console.log(`[QZ Print] Impression du QR code ${currentPrintIndex + 1}/${printDataCollection.length}`);
                        return window.QZTray.printFromController(printData);
                    })
                    .then(() => {
                        console.log(`[QZ Print] QR code ${currentPrintIndex + 1} imprimé avec succès`);
                        
                        // Passer au suivant
                        currentPrintIndex++;
                        isPrinting = false;
                        
                        // Attendre un court délai avant d'imprimer le suivant
                        setTimeout(printNext, 500);
                    })
                    .catch((error) => {
                        console.error(`[QZ Print] Erreur lors de l'impression du QR code ${currentPrintIndex + 1}:`, error);
                        updateStatus('error', error.message || String(error));
                        isPrinting = false;
                        
                        // On peut décider de continuer malgré l'erreur après un délai
                        setTimeout(() => {
                            currentPrintIndex++;
                            printNext();
                        }, 2000);
                    });
            }
            
            // Fonction pour mettre à jour le statut d'impression dans l'interface
            function updateStatus(status, message = '') {
                switch (status) {
                    case 'printing':
                        qzStatusElement.className = 'badge bg-info px-3 py-1 rounded-full text-white bg-blue-500';
                        qzStatusElement.textContent = message || 'Impression en cours...';
                        qzErrorElement.style.display = 'none';
                        printSuccessElement.style.display = 'none';
                        break;
                    case 'success':
                        qzStatusElement.className = 'badge bg-success px-3 py-1 rounded-full text-white bg-green-500';
                        qzStatusElement.textContent = 'Impression réussie';
                        qzErrorElement.style.display = 'none';
                        printSuccessElement.textContent = message || 'Impression réussie';
                        printSuccessElement.style.display = 'block';
                        break;
                    case 'error':
                        qzStatusElement.className = 'badge bg-danger px-3 py-1 rounded-full text-white bg-red-500';
                        qzStatusElement.textContent = 'Erreur d\'impression';
                        qzErrorElement.textContent = message || 'Erreur d\'impression';
                        qzErrorElement.style.display = 'block';
                        printSuccessElement.style.display = 'none';
                        break;
                    default:
                        qzStatusElement.className = 'badge bg-secondary px-3 py-1 rounded-full text-white bg-gray-500';
                        qzStatusElement.textContent = 'En attente';
                        qzErrorElement.style.display = 'none';
                        printSuccessElement.style.display = 'none';
                }
            }
            
            // Ajouter les écouteurs d'événements aux boutons
            printAllButton.addEventListener('click', printAll);
            
            printSingleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'), 10);
                    printSingle(index);
                });
            });
        });
    </script>
</x-app-layout>