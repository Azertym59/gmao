<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Test des étiquettes') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('printers.test') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Test d'impression
                </a>
                <a href="{{ route('printers.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour aux imprimantes
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Section de test d'impression -->
                    <h2 class="text-2xl font-bold mb-4">Test des formats d'étiquettes</h2>
                    
                    @if(isset($printer))
                    <div class="mb-4 p-4 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Imprimante sélectionnée</h3>
                        <p><strong>Nom:</strong> {{ $printer->name }} @if($printer->is_default) <span class="ml-2 px-2 py-1 bg-yellow-500/50 text-yellow-900 dark:text-yellow-200 rounded-full text-xs font-medium">Par défaut</span> @endif</p>
                        <p><strong>Type:</strong> {{ $printer->type }}</p>
                        @if(isset($printer->options) && is_array($printer->options))
                            <p><strong>Rouleau:</strong> {{ $printer->options['brother_roll'] ?? 'Non spécifié' }}</p>
                            <p><strong>Format:</strong> {{ $printer->options['label_format'] ?? 'Non spécifié' }}</p>
                            <p><strong>Dimensions:</strong> {{ $printer->options['label_width'] ?? '0' }}mm x {{ $printer->options['label_height'] ?? '0' }}mm</p>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Sélection des formats d'étiquettes à tester -->
                    <form action="{{ route('printers.test-labels') }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="printer_id" value="{{ $printer->id ?? '' }}">
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Formats d'étiquettes à tester:
                            </label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="test_module" name="test_formats[]" value="module" class="mr-2" checked>
                                    <label for="test_module">Format Module (petit QR code, 29mm)</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="test_dalle" name="test_formats[]" value="dalle" class="mr-2" checked>
                                    <label for="test_dalle">Format Dalle (QR code moyen, 38mm)</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="test_flightcase" name="test_formats[]" value="flightcase" class="mr-2" checked>
                                    <label for="test_flightcase">Format FlightCase (grand QR code, 62mm)</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Imprimer les étiquettes de test
                            </button>
                        </div>
                    </form>
                    
                    <!-- Prévisualisation des étiquettes -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Prévisualisation des étiquettes</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Module QR Code -->
                            <div class="border rounded p-4">
                                <h4 class="font-medium mb-2">Format Module</h4>
                                <div class="flex justify-center mb-2">
                                    <img src="{{ $moduleQrBase64 ?? '' }}" alt="QR Code Module" class="w-32 h-32">
                                </div>
                                <p class="text-sm text-center">29mm x 90mm</p>
                                @if(isset($moduleQrBase64))
                                <div class="mt-2 text-center">
                                    <button type="button" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600" 
                                            onclick="printSingleTest('module')">
                                        Imprimer
                                    </button>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Dalle QR Code -->
                            <div class="border rounded p-4">
                                <h4 class="font-medium mb-2">Format Dalle</h4>
                                <div class="flex justify-center mb-2">
                                    <img src="{{ $dalleQrBase64 ?? '' }}" alt="QR Code Dalle" class="w-32 h-32">
                                </div>
                                <p class="text-sm text-center">38mm x 90mm</p>
                                @if(isset($dalleQrBase64))
                                <div class="mt-2 text-center">
                                    <button type="button" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600" 
                                            onclick="printSingleTest('dalle')">
                                        Imprimer
                                    </button>
                                </div>
                                @endif
                            </div>
                            
                            <!-- FlightCase QR Code -->
                            <div class="border rounded p-4">
                                <h4 class="font-medium mb-2">Format FlightCase</h4>
                                <div class="flex justify-center mb-2">
                                    <img src="{{ $flightcaseQrBase64 ?? '' }}" alt="QR Code FlightCase" class="w-32 h-32">
                                </div>
                                <p class="text-sm text-center">62mm x 100mm</p>
                                @if(isset($flightcaseQrBase64))
                                <div class="mt-2 text-center">
                                    <button type="button" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600" 
                                            onclick="printSingleTest('flightcase')">
                                        Imprimer
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Guide des formats d'étiquettes -->
                    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded">
                        <h3 class="text-lg font-semibold mb-2">Guide des formats d'étiquettes pour Brother</h3>
                        <div class="bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 p-4 mb-4 rounded">
                            <h4 class="font-semibold mb-1">Rouleau continu 62mm (DK-22205) configuré</h4>
                            <p class="text-sm">
                                Cette page utilise un rouleau continu de 62mm de largeur pour tous les formats d'étiquettes, mais avec des hauteurs différentes. L'imprimante coupera automatiquement l'étiquette à la bonne hauteur.
                            </p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left">Format d'étiquette</th>
                                        <th class="px-4 py-2 text-left">Dimensions</th>
                                        <th class="px-4 py-2 text-left">Utilisation</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr>
                                        <td class="px-4 py-2">Module (petit)</td>
                                        <td class="px-4 py-2">62mm x 29mm</td>
                                        <td class="px-4 py-2">Petits QR codes pour modules</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2">Dalle (moyen)</td>
                                        <td class="px-4 py-2">62mm x 38mm</td>
                                        <td class="px-4 py-2">QR codes moyens pour dalles</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2">FlightCase (grand)</td>
                                        <td class="px-4 py-2">62mm x 62mm</td>
                                        <td class="px-4 py-2">Grands QR codes pour FlightCases</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded border border-yellow-200 dark:border-yellow-800">
                            <h4 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-2">Résolution des problèmes d'impression</h4>
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                <li><strong>Erreur "mauvais type de rouleau"</strong> : Vérifiez que l'imprimante contient bien un rouleau DK-22205 (62mm de largeur)</li>
                                <li><strong>Étiquette coupée incorrectement</strong> : Vérifiez les paramètres de coupe automatique dans les options de l'imprimante</li>
                                <li><strong>QR code tronqué</strong> : La taille du QR code est automatiquement adaptée à la hauteur de l'étiquette</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Statut de QZ Tray -->
                    <div class="mt-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Statut de QZ Tray</h3>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 mr-2" id="qz-status">
                                Non connecté
                            </span>
                            <button type="button" id="qz-connect" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                Connecter
                            </button>
                        </div>
                        <div id="qz-error" class="mt-2 text-red-600 dark:text-red-400" style="display: none;"></div>
                        <div id="print-success" class="mt-2 text-green-600 dark:text-green-400" style="display: none;">Impression réussie</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Inclusion des scripts QZ Tray -->
    {!! $qzTrayService->getQzTrayScripts() !!}
    
    <script>
        // Store print data for the test labels
        const printData = {
            module: @json($modulePrintData ?? null),
            dalle: @json($dallePrintData ?? null),
            flightcase: @json($flightcasePrintData ?? null)
        };
        
        document.addEventListener('DOMContentLoaded', function() {
            const statusElement = document.getElementById('qz-status');
            const qzConnectButton = document.getElementById('qz-connect');
            const qzErrorElement = document.getElementById('qz-error');
            const printSuccessElement = document.getElementById('print-success');
            
            // Function to print a single test label
            window.printSingleTest = function(type) {
                if (!printData[type]) {
                    alert('Données d\'impression non disponibles pour ce format');
                    return;
                }
                
                // Update status
                statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-200 mr-2';
                statusElement.textContent = 'Impression en cours...';
                qzErrorElement.style.display = 'none';
                printSuccessElement.style.display = 'none';
                
                // Print using QZ Tray
                window.QZTray.connect()
                    .then(() => {
                        console.log(`[QZ Print] Impression du format ${type}`);
                        return window.QZTray.printFromController(printData[type]);
                    })
                    .then(() => {
                        console.log(`[QZ Print] Impression du format ${type} réussie`);
                        statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200 mr-2';
                        statusElement.textContent = 'Connecté';
                        printSuccessElement.textContent = `Impression du format ${type} réussie`;
                        printSuccessElement.style.display = 'block';
                    })
                    .catch((error) => {
                        console.error(`[QZ Print] Erreur lors de l'impression du format ${type}:`, error);
                        statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200 mr-2';
                        statusElement.textContent = 'Erreur d\'impression';
                        qzErrorElement.textContent = 'Erreur: ' + (error.message || error);
                        qzErrorElement.style.display = 'block';
                    });
            };
            
            // Tentative de connexion automatique
            QZTray.connect()
                .then(() => {
                    statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200 mr-2';
                    statusElement.textContent = 'Connecté';
                })
                .catch((error) => {
                    statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200 mr-2';
                    statusElement.textContent = 'Non connecté';
                    qzErrorElement.textContent = 'Erreur: ' + (error.message || error);
                    qzErrorElement.style.display = 'block';
                });
            
            // Connexion manuelle
            qzConnectButton.addEventListener('click', function() {
                QZTray.connect()
                    .then(() => {
                        statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200 mr-2';
                        statusElement.textContent = 'Connecté';
                        qzErrorElement.style.display = 'none';
                    })
                    .catch((error) => {
                        statusElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200 mr-2';
                        statusElement.textContent = 'Non connecté';
                        qzErrorElement.textContent = 'Erreur: ' + (error.message || error);
                        qzErrorElement.style.display = 'block';
                    });
            });
        });
    </script>
</x-app-layout>