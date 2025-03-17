<x-app-layout>
    <x-slot name="header">
        Modifier une imprimante
    </x-slot>

    <div class="space-y-6">
        <!-- En-tête du formulaire -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-white">Modification de l'imprimante {{ $printer->name }}</h1>
            <a href="{{ route('printers.index') }}" class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>

        <!-- Formulaire principal -->
        <div class="glassmorphism rounded-lg overflow-hidden shadow-lg">
            <form action="{{ route('printers.update', $printer) }}" method="POST" class="p-6 space-y-6">
                @method('PUT')
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
                    <input type="text" name="name" id="name" value="{{ old('name', $printer->name) }}" 
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
                        @php
                            $currentModel = old('model', $printer->options['model'] ?? '');
                        @endphp
                        <option value="Brother QL-820NWB" {{ $currentModel == 'Brother QL-820NWB' ? 'selected' : '' }}>Brother QL-820NWB</option>
                        <option value="Brother QL-800" {{ $currentModel == 'Brother QL-800' ? 'selected' : '' }}>Brother QL-800</option>
                        <option value="Brother QL-700" {{ $currentModel == 'Brother QL-700' ? 'selected' : '' }}>Brother QL-700</option>
                        <option value="Zebra ZD410" {{ $currentModel == 'Zebra ZD410' ? 'selected' : '' }}>Zebra ZD410</option>
                        <option value="Dymo LabelWriter 450" {{ $currentModel == 'Dymo LabelWriter 450' ? 'selected' : '' }}>Dymo LabelWriter 450</option>
                        <option value="Autre" {{ $currentModel == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <!-- Type de connexion -->
                <div>
                    <label for="connection_type" class="block text-sm font-medium text-text-secondary mb-2">Type de connexion</label>
                    <select name="connection_type" id="connection_type" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                        <option value="">Sélectionnez le type de connexion</option>
                        @php
                            $currentConnectionType = old('connection_type', $printer->options['connection_type'] ?? '');
                        @endphp
                        <option value="network" {{ $currentConnectionType == 'network' ? 'selected' : '' }}>Réseau (IP)</option>
                        <option value="usb" {{ $currentConnectionType == 'usb' ? 'selected' : '' }}>USB</option>
                        <option value="bluetooth" {{ $currentConnectionType == 'bluetooth' ? 'selected' : '' }}>Bluetooth</option>
                    </select>
                </div>

                <!-- Adresse IP (conditionnelle) -->
                <div id="ip_address_container" class="{{ $printer->options['connection_type'] ?? '' == 'network' ? '' : 'hidden' }}">
                    <label for="ip_address" class="block text-sm font-medium text-text-secondary mb-2">Adresse IP</label>
                    <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address', $printer->options['ip_address'] ?? '') }}" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent"
                           placeholder="Exemple: 192.168.1.100">
                </div>

                <!-- Référence de rouleau Brother (pour les imprimantes Brother) -->
                <div id="brother_roll_container">
                    <label for="brother_roll" class="block text-sm font-medium text-text-secondary mb-2">Référence de rouleau Brother</label>
                    <select name="brother_roll" id="brother_roll" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                        <option value="">Sélectionnez une référence</option>
                        @php
                            $currentRoll = old('brother_roll', $printer->options['brother_roll'] ?? '');
                        @endphp
                        <option value="DK-22205" {{ $currentRoll == 'DK-22205' ? 'selected' : '' }}>DK-22205 (62mm x continu)</option>
                        <option value="DK-22210" {{ $currentRoll == 'DK-22210' ? 'selected' : '' }}>DK-22210 (29mm x continu)</option>
                        <option value="DK-22214" {{ $currentRoll == 'DK-22214' ? 'selected' : '' }}>DK-22214 (12mm x continu)</option>
                        <option value="DK-11201" {{ $currentRoll == 'DK-11201' ? 'selected' : '' }}>DK-11201 (29mm x 90mm, adresse standard)</option>
                        <option value="DK-11202" {{ $currentRoll == 'DK-11202' ? 'selected' : '' }}>DK-11202 (62mm x 100mm, expédition)</option>
                        <option value="DK-11208" {{ $currentRoll == 'DK-11208' ? 'selected' : '' }}>DK-11208 (38mm x 90mm)</option>
                        <option value="DK-11209" {{ $currentRoll == 'DK-11209' ? 'selected' : '' }}>DK-11209 (62mm x 29mm, adresse petite)</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-400">Sélectionnez la référence exacte du rouleau installé dans l'imprimante</p>
                </div>

                <!-- Format d'étiquette (sera automatiquement défini par la référence du rouleau) -->
                <div>
                    <label for="label_format" class="block text-sm font-medium text-text-secondary mb-2">Format d'étiquette par défaut</label>
                    <select name="label_format" id="label_format" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                        <option value="">Sélectionnez un format</option>
                        @php
                            $currentFormat = old('label_format', $printer->options['label_format'] ?? '');
                        @endphp
                        <option value="62mm" {{ $currentFormat == '62mm' ? 'selected' : '' }}>62mm (standard)</option>
                        <option value="29mm" {{ $currentFormat == '29mm' ? 'selected' : '' }}>29mm (petite)</option>
                        <option value="12mm" {{ $currentFormat == '12mm' ? 'selected' : '' }}>12mm (très petite)</option>
                        <option value="38mm" {{ $currentFormat == '38mm' ? 'selected' : '' }}>38mm (moyenne)</option>
                        <option value="102mm" {{ $currentFormat == '102mm' ? 'selected' : '' }}>102mm (large)</option>
                        <option value="17mm" {{ $currentFormat == '17mm' ? 'selected' : '' }}>17mm (très petite)</option>
                        <option value="predefined" {{ $currentFormat == 'predefined' ? 'selected' : '' }}>Format prédéfini</option>
                        <option value="custom" {{ $currentFormat == 'custom' ? 'selected' : '' }}>Format personnalisé</option>
                    </select>
                </div>

                <!-- Mode d'impression (pour Brother) -->
                <div id="print_mode_container">
                    <label for="print_mode" class="block text-sm font-medium text-text-secondary mb-2">Mode d'impression</label>
                    <select name="print_mode" id="print_mode" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                        <option value="">Sélectionnez un mode</option>
                        @php
                            $currentPrintMode = old('print_mode', $printer->options['print_mode'] ?? '');
                        @endphp
                        <option value="raster" {{ $currentPrintMode == 'raster' ? 'selected' : '' }}>Raster (recommandé)</option>
                        <option value="template" {{ $currentPrintMode == 'template' ? 'selected' : '' }}>P-touch Template</option>
                        <option value="escp" {{ $currentPrintMode == 'escp' ? 'selected' : '' }}>ESC/P</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-400">Choisissez le mode d'impression correspondant à votre configuration d'imprimante</p>
                </div>

                <!-- Format personnalisé (conditionnel) -->
                <div id="custom_format_container" class="{{ $currentFormat == 'custom' ? '' : 'hidden' }}">
                    <label for="custom_format" class="block text-sm font-medium text-text-secondary mb-2">Dimensions personnalisées (mm)</label>
                    <div class="flex space-x-3">
                        <input type="number" name="custom_width" id="custom_width" value="{{ old('custom_width', $printer->options['custom_width'] ?? '') }}" 
                               class="w-1/2 bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent"
                               placeholder="Largeur">
                        <span class="flex items-center text-white">×</span>
                        <input type="number" name="custom_height" id="custom_height" value="{{ old('custom_height', $printer->options['custom_height'] ?? '') }}" 
                               class="w-1/2 bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent"
                               placeholder="Hauteur">
                    </div>
                </div>

                <!-- Champs cachés pour les dimensions d'étiquette -->
                <input type="hidden" name="label_width" id="label_width" value="{{ $printer->options['label_width'] ?? '' }}">
                <input type="hidden" name="label_height" id="label_height" value="{{ $printer->options['label_height'] ?? '' }}">

                <!-- Options avancées -->
                <div class="border-t border-gray-700 pt-6">
                    <h3 class="text-lg font-semibold text-text-primary mb-4">Options avancées</h3>
                    
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default', $printer->is_default) ? 'checked' : '' }}
                               class="bg-gray-800 border-gray-700 rounded text-accent-blue focus:ring-accent-blue">
                        <label for="is_default" class="ml-2 block text-sm text-text-secondary">
                            Définir comme imprimante par défaut
                        </label>
                    </div>
                    
                    <div>
                        <label for="dpi" class="block text-sm font-medium text-text-secondary mb-2">Résolution (DPI)</label>
                        <select name="dpi" id="dpi" 
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                            <option value="300" {{ old('dpi', $printer->options['dpi'] ?? '300') == '300' ? 'selected' : '' }}>300 DPI (standard)</option>
                            <option value="600" {{ old('dpi', $printer->options['dpi'] ?? '') == '600' ? 'selected' : '' }}>600 DPI (haute qualité)</option>
                            <option value="203" {{ old('dpi', $printer->options['dpi'] ?? '') == '203' ? 'selected' : '' }}>203 DPI (basse qualité)</option>
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
    </div>

    <!-- Script pour les champs conditionnels et dimensions -->
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
                updateLabelDimensions();
            });
            
            // Gestion de la sélection automatique par référence de rouleau Brother
            const brotherRollSelect = document.getElementById('brother_roll');
            
            if (brotherRollSelect) {
                brotherRollSelect.addEventListener('change', function() {
                    // Définir les dimensions en fonction de la référence du rouleau
                    const rollDimensions = {
                        'DK-22205': { width: 62, height: 0, format: '62mm', type: 'continuous' },
                        'DK-22210': { width: 29, height: 0, format: '29mm', type: 'continuous' },
                        'DK-22214': { width: 12, height: 0, format: '12mm', type: 'continuous' },
                        'DK-11201': { width: 29, height: 90, format: 'predefined', type: 'die-cut' },
                        'DK-11202': { width: 62, height: 100, format: 'predefined', type: 'die-cut' },
                        'DK-11208': { width: 38, height: 90, format: 'predefined', type: 'die-cut' },
                        'DK-11209': { width: 62, height: 29, format: 'predefined', type: 'die-cut' }
                    };
                    
                    const rollRef = this.value;
                    
                    if (rollRef && rollDimensions[rollRef]) {
                        const dimensions = rollDimensions[rollRef];
                        
                        // Mettre à jour le select du format
                        labelFormatSelect.value = dimensions.format;
                        
                        // Mettre à jour les dimensions cachées
                        document.getElementById('label_width').value = dimensions.width;
                        document.getElementById('label_height').value = dimensions.height;
                        
                        // Si c'est un format prédéfini, mettre à jour les champs de format personnalisé
                        if (dimensions.format === 'predefined') {
                            document.getElementById('custom_width').value = dimensions.width;
                            document.getElementById('custom_height').value = dimensions.height;
                            
                            // Afficher le conteneur de format personnalisé
                            customFormatContainer.classList.remove('hidden');
                        } else {
                            // Masquer le conteneur de format personnalisé si ce n'est pas un format custom
                            if (dimensions.format !== 'custom') {
                                customFormatContainer.classList.add('hidden');
                            }
                        }
                        
                        // Déclencher un événement change sur le select de format
                        const event = new Event('change');
                        labelFormatSelect.dispatchEvent(event);
                    }
                });
            }
            
            // Fonction pour mettre à jour les dimensions d'étiquette
            function updateLabelDimensions() {
                const labelWidthHidden = document.getElementById('label_width');
                const labelHeightHidden = document.getElementById('label_height');
                const customWidthInput = document.getElementById('custom_width');
                const customHeightInput = document.getElementById('custom_height');

                switch(labelFormatSelect.value) {
                    case '62mm':
                        labelWidthHidden.value = 62;
                        labelHeightHidden.value = 100; // valeur par défaut
                        break;
                    case '29mm':
                        labelWidthHidden.value = 29;
                        labelHeightHidden.value = 90; // valeur par défaut
                        break;
                    case '12mm':
                        labelWidthHidden.value = 12;
                        labelHeightHidden.value = 90; // valeur par défaut
                        break;
                    case '38mm':
                        labelWidthHidden.value = 38;
                        labelHeightHidden.value = 90; // valeur par défaut
                        break;
                    case '102mm':
                        labelWidthHidden.value = 102;
                        labelHeightHidden.value = 150; // valeur par défaut
                        break;
                    case '17mm':
                        labelWidthHidden.value = 17;
                        labelHeightHidden.value = 54; // valeur par défaut
                        break;
                    case 'predefined': // Pour les étiquettes prédécoupées
                        labelWidthHidden.value = customWidthInput.value || '';
                        labelHeightHidden.value = customHeightInput.value || '';
                        break;
                    case 'custom':
                        labelWidthHidden.value = customWidthInput.value || '';
                        labelHeightHidden.value = customHeightInput.value || '';
                        break;
                    default:
                        labelWidthHidden.value = '';
                        labelHeightHidden.value = '';
                }
            }

            // Ajouter des écouteurs pour mettre à jour les dimensions
            labelFormatSelect.addEventListener('change', updateLabelDimensions);
            document.getElementById('custom_width').addEventListener('input', updateLabelDimensions);
            document.getElementById('custom_height').addEventListener('input', updateLabelDimensions);
            
            // Initialiser l'état des champs conditionnels
            if (connectionTypeSelect.value === 'network') {
                ipAddressContainer.classList.remove('hidden');
            }
            
            if (labelFormatSelect.value === 'custom') {
                customFormatContainer.classList.remove('hidden');
            }

            // Initialiser les dimensions au chargement
            updateLabelDimensions();
        });
    </script>
</x-app-layout>