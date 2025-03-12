<x-app-layout>
    <x-slot name="header">
        Ajouter une imprimante
    </x-slot>

    <div class="space-y-6">
        <!-- En-tête du formulaire -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-white">Configuration d'une nouvelle imprimante</h1>
            <a href="{{ route('printers.index') }}" class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>

        <!-- Formulaire principal -->
        <div class="glassmorphism rounded-lg overflow-hidden shadow-lg">
            <form action="{{ route('printers.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Messages d'erreur -->
                @if ($errors->any())
                    <div class="bg-red-500/20 border border-red-400 text-red-300 px-4 py-3 rounded-lg mb-6">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Nom de l'imprimante -->
                <div>
                    <label for="name" class="block text-sm font-medium text-text-secondary mb-2">Nom de l'imprimante</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent"
                           placeholder="Exemple: Imprimante Étiquettes Atelier">
                    <p class="mt-1 text-sm text-gray-400">Choisissez un nom descriptif pour identifier facilement cette imprimante</p>
                </div>

                <!-- Modèle de l'imprimante -->
                <div>
                    <label for="model" class="block text-sm font-medium text-text-secondary mb-2">Modèle</label>
                    <select name="model" id="model" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                        <option value="">Sélectionnez un modèle</option>
                        <option value="Brother QL-820NWB" {{ old('model') == 'Brother QL-820NWB' ? 'selected' : '' }}>Brother QL-820NWB</option>
                        <option value="Brother QL-800" {{ old('model') == 'Brother QL-800' ? 'selected' : '' }}>Brother QL-800</option>
                        <option value="Brother QL-700" {{ old('model') == 'Brother QL-700' ? 'selected' : '' }}>Brother QL-700</option>
                        <option value="Zebra ZD410" {{ old('model') == 'Zebra ZD410' ? 'selected' : '' }}>Zebra ZD410</option>
                        <option value="Dymo LabelWriter 450" {{ old('model') == 'Dymo LabelWriter 450' ? 'selected' : '' }}>Dymo LabelWriter 450</option>
                        <option value="Autre" {{ old('model') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <!-- Type de connexion -->
                <div>
                    <label for="connection_type" class="block text-sm font-medium text-text-secondary mb-2">Type de connexion</label>
                    <select name="connection_type" id="connection_type" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                        <option value="">Sélectionnez le type de connexion</option>
                        <option value="network" {{ old('connection_type') == 'network' ? 'selected' : '' }}>Réseau (IP)</option>
                        <option value="usb" {{ old('connection_type') == 'usb' ? 'selected' : '' }}>USB</option>
                        <option value="bluetooth" {{ old('connection_type') == 'bluetooth' ? 'selected' : '' }}>Bluetooth</option>
                    </select>
                </div>

                <!-- Adresse IP (conditionnelle) -->
                <div id="ip_address_container" class="hidden">
                    <label for="ip_address" class="block text-sm font-medium text-text-secondary mb-2">Adresse IP</label>
                    <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address') }}" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent"
                           placeholder="Exemple: 192.168.1.100">
                </div>

                <!-- Format d'étiquette -->
                <div>
                    <label for="label_format" class="block text-sm font-medium text-text-secondary mb-2">Format d'étiquette par défaut</label>
                    <select name="label_format" id="label_format" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                        <option value="">Sélectionnez un format</option>
                        <option value="62mm" {{ old('label_format') == '62mm' ? 'selected' : '' }}>62mm (standard)</option>
                        <option value="29mm" {{ old('label_format') == '29mm' ? 'selected' : '' }}>29mm (petite)</option>
                        <option value="102mm" {{ old('label_format') == '102mm' ? 'selected' : '' }}>102mm (large)</option>
                        <option value="17mm" {{ old('label_format') == '17mm' ? 'selected' : '' }}>17mm (très petite)</option>
                        <option value="custom" {{ old('label_format') == 'custom' ? 'selected' : '' }}>Format personnalisé</option>
                    </select>
                </div>

                <!-- Format personnalisé (conditionnel) -->
                <div id="custom_format_container" class="hidden">
                    <label for="custom_format" class="block text-sm font-medium text-text-secondary mb-2">Dimensions personnalisées (mm)</label>
                    <div class="flex space-x-3">
                        <input type="number" name="custom_width" id="custom_width" value="{{ old('custom_width') }}" 
                               class="w-1/2 bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent"
                               placeholder="Largeur">
                        <span class="flex items-center text-white">×</span>
                        <input type="number" name="custom_height" id="custom_height" value="{{ old('custom_height') }}" 
                               class="w-1/2 bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent"
                               placeholder="Hauteur">
                    </div>
                </div>

                <!-- Options avancées -->
                <div class="border-t border-gray-700 pt-6">
                    <h3 class="text-lg font-semibold text-text-primary mb-4">Options avancées</h3>
                    
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                               class="bg-gray-800 border-gray-700 rounded text-accent-blue focus:ring-accent-blue">
                        <label for="is_default" class="ml-2 block text-sm text-text-secondary">
                            Définir comme imprimante par défaut
                        </label>
                    </div>
                    
                    <div>
                        <label for="dpi" class="block text-sm font-medium text-text-secondary mb-2">Résolution (DPI)</label>
                        <select name="dpi" id="dpi" 
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                            <option value="300" {{ old('dpi', '300') == '300' ? 'selected' : '' }}>300 DPI (standard)</option>
                            <option value="600" {{ old('dpi') == '600' ? 'selected' : '' }}>600 DPI (haute qualité)</option>
                            <option value="203" {{ old('dpi') == '203' ? 'selected' : '' }}>203 DPI (basse qualité)</option>
                        </select>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-3 border-t border-gray-700 pt-6">
                    <a href="{{ route('printers.index') }}" class="px-4 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="px-4 py-2 bg-accent-blue hover:bg-blue-600 text-white rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Aide à la configuration -->
        <div class="glassmorphism border border-blue-500/20 rounded-lg overflow-hidden">
            <div class="bg-blue-500/10 px-6 py-4 border-b border-blue-500/20">
                <h2 class="text-lg font-semibold text-blue-300">Conseils pour la configuration</h2>
            </div>
            <div class="p-6 text-text-secondary">
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-blue mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Pour les imprimantes réseau, assurez-vous que l'adresse IP est statique ou réservée dans votre DHCP.</span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-blue mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Les imprimantes Brother doivent avoir le mode P-touch activé pour fonctionner correctement avec le système.</span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-blue mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Vérifiez que le type d'étiquette chargé dans l'imprimante correspond au format sélectionné.</span>
                    </li>
                </ul>
                
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <p>
                        Pour obtenir de l'aide supplémentaire, consultez la 
                        <a href="#" class="text-accent-blue hover:text-blue-400 transition-colors">
                            documentation des imprimantes
                        </a>
                        ou contactez le support technique.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Script pour les champs conditionnels -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const connectionTypeSelect = document.getElementById('connection_type');
            const ipAddressContainer = document.getElementById('ip_address_container');
            
            const labelFormatSelect = document.getElementById('label_format');
            const customFormatContainer = document.getElementById('custom_format_container');
            
            // Gestion de l'affichage du champ IP
            connectionTypeSelect.addEventListener('change', function() {
                if (this.value === 'network') {
                    ipAddressContainer.classList.remove('hidden');
                } else {
                    ipAddressContainer.classList.add('hidden');
                }
            });
            
            // Gestion de l'affichage du format personnalisé
            labelFormatSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customFormatContainer.classList.remove('hidden');
                } else {
                    customFormatContainer.classList.add('hidden');
                }
            });
            
            // Initialiser l'état des champs conditionnels
            if (connectionTypeSelect.value === 'network') {
                ipAddressContainer.classList.remove('hidden');
            }
            
            if (labelFormatSelect.value === 'custom') {
                customFormatContainer.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>