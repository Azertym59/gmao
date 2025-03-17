<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Test d\'impression') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ url('/etiquettes/test') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    Tester étiquettes
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
            <div class="glassmorphism overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(isset($selectedPrinter) && $selectedPrinter)
                    <div class="mb-4 p-4 bg-blue-100/10 dark:bg-blue-900/20 rounded-lg border border-blue-200/20">
                        <h3 class="text-lg font-semibold mb-2">Imprimante sélectionnée</h3>
                        <p><strong>Nom:</strong> {{ $selectedPrinter->name }} @if($selectedPrinter->is_default) <span class="ml-2 px-2 py-1 bg-yellow-500/50 text-yellow-900 dark:text-yellow-200 rounded-full text-xs font-medium">Par défaut</span> @endif</p>
                        <p><strong>Type:</strong> {{ $selectedPrinter->type }}</p>
                    </div>
                    @endif

                    <div class="alert dark:bg-blue-900/20 p-4 mb-4 rounded-lg border border-blue-500/30">
                        <strong class="font-medium text-blue-300">Info:</strong> Assurez-vous que QZ Tray est installé et en cours d'exécution sur votre ordinateur.
                    </div>

                    <!-- QZ Tray Status -->
                    <div class="mb-6 p-4 bg-gray-100/10 dark:bg-gray-800/40 rounded-lg border border-gray-300/20">
                        <h4 class="font-medium mb-2 text-lg">Statut QZ Tray</h4>
                        <div class="flex items-center">
                            <div id="qz-status" class="px-3 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200 text-sm">Vérification...</div>
                            <div class="ml-2">
                                <button id="connect-qz" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-full transition">
                                    Connecter à QZ Tray
                                </button>
                            </div>
                        </div>
                        <div id="qz-error" class="mt-2 text-red-600 dark:text-red-400 text-sm p-2 bg-red-100/10 dark:bg-red-900/20 rounded-lg" style="display: none;"></div>
                    </div>

                    <!-- Printer Selection Form -->
                    <form method="POST" action="{{ route('printers.print-test') }}" class="mb-6 p-4 bg-gray-100/10 dark:bg-gray-800/40 rounded-lg border border-gray-300/20">
                        @csrf
                        <div class="mb-3">
                            <label for="printer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sélectionner une imprimante</label>
                            <select class="form-select block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" id="printer_id" name="printer_id" required>
                                <option value="">Sélectionner...</option>
                                @foreach($printers as $printer)
                                    <option value="{{ $printer->id }}" {{ isset($selectedPrinter) && $selectedPrinter && $selectedPrinter->id == $printer->id ? 'selected' : '' }}>
                                        {{ $printer->name }} ({{ $printer->type }})
                                        @if($printer->is_default) [Par défaut] @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition" id="print-test">
                            Imprimer QR Code de test
                        </button>
                    </form>

                    <!-- Printers List from QZ Tray -->
                    <div class="mt-6 p-4 bg-gray-100/10 dark:bg-gray-800/40 rounded-lg border border-gray-300/20">
                        <h4 class="font-medium mb-2 text-lg">Imprimantes détectées par QZ Tray</h4>
                        <div id="printer-list" class="mt-2 divide-y divide-gray-200 dark:divide-gray-700">
                            <div class="text-center p-3 text-gray-400">Chargement en cours...</div>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <div id="print-success" class="mt-4 p-3 bg-green-100/20 dark:bg-green-900/20 text-green-800 dark:text-green-200 rounded-lg" style="display: none;">
                        Impression envoyée avec succès!
                    </div>
                    
                    <!-- Options pour imprimante Brother -->
                    @if(isset($printer) && $printer->isBrotherLabel())
                    <div class="mt-6 p-4 bg-blue-100/10 dark:bg-blue-900/20 rounded-lg border border-blue-200/20">
                        <h3 class="text-lg font-semibold mb-3">Test d'impression Brother</h3>
                        
                        <div class="flex flex-col space-y-3">
                            <a href="{{ route('printers.test-brother', $printer->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded inline-flex items-center w-fit">
                                <i class="fas fa-print mr-2"></i> Test d'impression Brother
                            </a>
                            
                            @if($printer->shouldUseBpac())
                            <div class="p-4 bg-green-900/30 border border-green-800 rounded">
                                <h4 class="font-medium text-green-300">Imprimante Brother avec b-PAC SDK</h4>
                                <p class="text-sm text-gray-300 mt-1">Cette imprimante est configurée pour utiliser le SDK Brother b-PAC pour une meilleure compatibilité avec les imprimantes Brother.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- QZ Tray Scripts -->
    <script src="{{ asset('js/qz-tray.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Connect QZ Tray button
            document.getElementById('connect-qz').addEventListener('click', function() {
                if (window.QZTray) {
                    window.QZTray.connect();
                } else {
                    document.getElementById('qz-error').textContent = "Le module QZ Tray n'est pas correctement chargé. Vérifiez que QZ Tray est installé.";
                    document.getElementById('qz-error').style.display = 'block';
                }
            });

            // Test QZ connection after page load
            setTimeout(function() {
                const statusElem = document.getElementById('qz-status');
                const errorElem = document.getElementById('qz-error');
                const printerListElem = document.getElementById('printer-list');
                
                if (!window.QZTray) {
                    statusElem.className = 'px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 text-sm';
                    statusElem.textContent = 'Non disponible';
                    errorElem.textContent = "Le module QZ Tray n'a pas été trouvé. Assurez-vous qu'il est correctement installé.";
                    errorElem.style.display = 'block';
                    return;
                }
                
                window.QZTray.checkStatus()
                    .then(function(status) {
                        statusElem.className = 'px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 text-sm';
                        statusElem.textContent = 'Connecté';
                        return window.QZTray.getPrinters();
                    })
                    .then(function(printers) {
                        if (printers.length === 0) {
                            printerListElem.innerHTML = '<div class="p-3 text-yellow-600 dark:text-yellow-400">Aucune imprimante détectée</div>';
                            return;
                        }
                        
                        printerListElem.innerHTML = '';
                        let listHtml = '';
                        
                        printers.forEach(function(printer) {
                            listHtml += `
                                <div class="py-2 px-1 flex items-center justify-between hover:bg-gray-100/10 dark:hover:bg-gray-700/20 transition cursor-pointer" onclick="selectPrinter('${printer}')">
                                    <span>${printer}</span>
                                    <button class="px-2 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition">Sélectionner</button>
                                </div>
                            `;
                        });
                        
                        printerListElem.innerHTML = listHtml;
                    })
                    .catch(function(error) {
                        statusElem.className = 'px-3 py-1 rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200 text-sm';
                        statusElem.textContent = 'Non connecté';
                        errorElem.textContent = "Erreur de connexion: " + error;
                        errorElem.style.display = 'block';
                        printerListElem.innerHTML = '<div class="p-3 text-red-600 dark:text-red-400">Impossible de se connecter à QZ Tray. Vérifiez qu\'il est actif et exécuté.</div>';
                    });
            }, 1000);
        });
        
        // Function to select a printer from the list
        function selectPrinter(printerName) {
            const printerSelect = document.getElementById('printer_id');
            
            // Find by name
            for(let i = 0; i < printerSelect.options.length; i++) {
                const option = printerSelect.options[i];
                if (option.text.includes(printerName)) {
                    printerSelect.value = option.value;
                    break;
                }
            }
            
            // Scroll to the form
            document.getElementById('print-test').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    </script>
</x-app-layout>