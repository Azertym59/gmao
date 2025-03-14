<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Impression d\'étiquette QR Code - Module') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl mb-6">
                        <h3 class="text-lg font-medium text-blue-300 mb-2">Impression en cours...</h3>
                        <p class="text-gray-300">Un QR code pour le module <strong>{{ $module->reference_module }}</strong> est en cours d'impression.</p>
                    </div>

                    <!-- QR Code Preview -->
                    <div class="flex flex-col items-center justify-center mb-6">
                        <h4 class="font-medium text-accent-blue mb-4">Aperçu du QR Code</h4>
                        <div class="glassmorphism p-6 rounded-xl border border-opacity-10 border-accent-blue w-64 flex flex-col items-center">
                            <img src="{{ $printData['imageData'] }}" alt="QR Code" class="max-w-full">
                            <div class="mt-3 text-center">
                                <h5 class="font-medium text-white">{{ $module->reference_module ?: 'Module #' . $module->id }}</h5>
                                <p class="text-sm text-gray-400">Dalle: {{ $module->dalle->reference_dalle }}</p>
                                <p class="text-sm text-gray-400">Chantier: {{ $module->dalle->produit->chantier->nom }}</p>
                                <p class="text-sm text-gray-400">ID: {{ $module->id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <h4 class="font-medium text-accent-purple mb-2">Statut QZ Tray</h4>
                        <div class="flex items-center">
                            <div id="qz-status" class="badge badge-warning">Vérification...</div>
                            <div id="qz-error" class="bg-red-900/30 border border-red-500/30 p-3 rounded-lg text-red-300 mt-2 hidden"></div>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <div id="print-success" class="bg-green-900/30 border border-green-500/30 p-4 rounded-xl text-green-300 mb-6 hidden">
                        Impression envoyée avec succès!
                    </div>

                    <div class="flex space-x-4 mt-6">
                        <a href="{{ route('modules.show', $module->id) }}" class="btn-action btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                            </svg>
                            Retour au module
                        </a>
                        <button type="button" class="btn-action btn-success" id="reprint">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Réimprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/qz-tray.js') }}"></script>
    {!! $qzTrayService->getQzTrayPrintScript($printData) !!}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reprint button
            document.getElementById('reprint').addEventListener('click', function() {
                window.QZTray.printFromController(@json($printData));
            });

            // Update display when print job is successful
            window.onPrintSuccess = function() {
                document.getElementById('print-success').classList.remove('hidden');
            };

            // Update status display
            window.updateQzStatus = function(status, isError) {
                const statusEl = document.getElementById('qz-status');
                const errorEl = document.getElementById('qz-error');
                
                if (isError) {
                    statusEl.className = 'badge badge-danger';
                    statusEl.textContent = 'Erreur';
                    errorEl.textContent = status;
                    errorEl.classList.remove('hidden');
                } else {
                    if (status === 'connected') {
                        statusEl.className = 'badge badge-success';
                        statusEl.textContent = 'Connecté';
                    } else {
                        statusEl.className = 'badge badge-warning';
                        statusEl.textContent = status;
                    }
                    errorEl.classList.add('hidden');
                }
            };
        });
    </script>
</x-app-layout>