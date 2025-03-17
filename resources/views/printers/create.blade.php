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
                        <option value="printnode" {{ old('connection_type') == 'printnode' ? 'selected' : '' }}>PrintNode (Cloud)</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-400">Sélectionnez "PrintNode" si l'imprimante est connectée à un ordinateur local et non au serveur</p>
                </div>

                <!-- Adresse IP (conditionnelle) -->
                <div id="ip_address_container" class="hidden">
                    <label for="ip_address" class="block text-sm font-medium text-text-secondary mb-2">Adresse IP</label>
                    <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address') }}" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent"
                           placeholder="Exemple: 192.168.1.100">
                </div>
                
                <!-- ID PrintNode (conditionnel) -->
                <div id="printnode_container" class="hidden">
                    <label for="printnode_id" class="block text-sm font-medium text-text-secondary mb-2">ID d'imprimante PrintNode</label>
                    <input type="text" name="printnode_id" id="printnode_id" value="{{ old('printnode_id') }}" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent"
                           placeholder="Exemple: 12345">
                    <p class="mt-1 text-sm text-gray-400">Entrez l'ID de l'imprimante qu'on trouve dans l'interface PrintNode. <a href="https://app.printnode.com/printers" target="_blank" class="text-accent-blue underline">Voir mes imprimantes PrintNode</a></p>
                </div>

                <!-- Référence de rouleau Brother (pour les imprimantes Brother) -->
                <div id="brother_roll_container">
                    <label for="brother_roll" class="block text-sm font-medium text-text-secondary mb-2">Référence de rouleau Brother</label>
                    <select name="brother_roll" id="brother_roll" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                        <option value="">Sélectionnez une référence</option>
                        <option value="DK-22205">DK-22205 (62mm x continu)</option>
                        <option value="DK-22210">DK-22210 (29mm x continu)</option>
                        <option value="DK-22214">DK-22214 (12mm x continu)</option>
                        <option value="DK-11201">DK-11201 (29mm x 90mm, adresse standard)</option>
                        <option value="DK-11202">DK-11202 (62mm x 100mm, expédition)</option>
                        <option value="DK-11208">DK-11208 (38mm x 90mm)</option>
                        <option value="DK-11209">DK-11209 (62mm x 29mm, adresse petite)</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-400">Sélectionnez la référence exacte du rouleau installé dans l'imprimante</p>
                </div>

                <!-- Format d'étiquette (sera automatiquement défini par la référence du rouleau) -->
                <div>
                    <label for="label_format" class="block text-sm font-medium text-text-secondary mb-2">Format d'étiquette par défaut</label>
                    <select name="label_format" id="label_format" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                        <option value="">Sélectionnez un format</option>
                        <option value="62mm" {{ old('label_format') == '62mm' ? 'selected' : '' }}>62mm (standard)</option>
                        <option value="29mm" {{ old('label_format') == '29mm' ? 'selected' : '' }}>29mm (petite)</option>
                        <option value="12mm" {{ old('label_format') == '12mm' ? 'selected' : '' }}>12mm (très petite)</option>
                        <option value="38mm" {{ old('label_format') == '38mm' ? 'selected' : '' }}>38mm (moyenne)</option>
                        <option value="102mm" {{ old('label_format') == '102mm' ? 'selected' : '' }}>102mm (large)</option>
                        <option value="17mm" {{ old('label_format') == '17mm' ? 'selected' : '' }}>17mm (très petite)</option>
                        <option value="predefined" {{ old('label_format') == 'predefined' ? 'selected' : '' }}>Format prédéfini</option>
                        <option value="custom" {{ old('label_format') == 'custom' ? 'selected' : '' }}>Format personnalisé</option>
                    </select>
                </div>
                
                <!-- Mode d'impression (pour Brother) -->
                <div id="print_mode_container">
                    <label for="print_mode" class="block text-sm font-medium text-text-secondary mb-2">Mode d'impression</label>
                    <select name="print_mode" id="print_mode" 
                           class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                        <option value="">Sélectionnez un mode</option>
                        <option value="raster" {{ old('print_mode') == 'raster' ? 'selected' : '' }}>Raster (recommandé)</option>
                        <option value="template" {{ old('print_mode') == 'template' ? 'selected' : '' }}>P-touch Template</option>
                        <option value="escp" {{ old('print_mode') == 'escp' ? 'selected' : '' }}>ESC/P</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-400">Choisissez le mode d'impression correspondant à votre configuration d'imprimante</p>
                </div>
                
                <!-- Utilisation du pilote b-PAC (pour Brother) -->
                <div id="use_bpac_container">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="use_bpac" id="use_bpac" value="1" {{ old('use_bpac') ? 'checked' : '' }}
                               class="bg-gray-800 border-gray-700 rounded text-accent-blue focus:ring-accent-blue">
                        <label for="use_bpac" class="ml-2 block text-sm text-text-secondary">
                            Utiliser le pilote Brother b-PAC SDK (recommandé pour les imprimantes Brother)
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-400">Cette option requiert l'installation de Brother b-PAC SDK sur votre système</p>
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

                <!-- Champs cachés pour les dimensions d'étiquette -->
                <input type="hidden" name="label_width" id="label_width" value="">
                <input type="hidden" name="label_height" id="label_height" value="">

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
    </div>

    <!-- Script pour les champs conditionnels et dimensions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const connectionTypeSelect = document.getElementById('connection_type');
            const ipAddressContainer = document.getElementById('ip_address_container');
            
            const labelFormatSelect = document.getElementById('label_format');
            const customFormatContainer = document.getElementById('custom_format_container');
            
            // Référence à la div PrintNode
            const printnodeContainer = document.getElementById('printnode_container');
            
            // Récupérer la référence au conteneur b-PAC
            const useBpacContainer = document.getElementById('use_bpac_container');
            
            // Gestion de l'affichage des champs conditionnels selon le type de connexion
            connectionTypeSelect.addEventListener('change', function() {
                // Masquer tous les conteneurs conditionnels par défaut
                ipAddressContainer.classList.add('hidden');
                printnodeContainer.classList.add('hidden');
                
                // Afficher le conteneur approprié selon la valeur sélectionnée
                if (this.value === 'network') {
                    ipAddressContainer.classList.remove('hidden');
                } else if (this.value === 'printnode') {
                    printnodeContainer.classList.remove('hidden');
                }
                
                // Afficher l'option b-PAC seulement pour USB et réseau avec Brother
                const modelValue = document.getElementById('model').value.toLowerCase();
                if ((this.value === 'usb' || this.value === 'network') && modelValue.includes('brother')) {
                    useBpacContainer.classList.remove('hidden');
                } else {
                    useBpacContainer.classList.add('hidden');
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
            
            // Gestion du modèle et affichage de l'option b-PAC
            const modelSelect = document.getElementById('model');
            const useBpacContainer = document.getElementById('use_bpac_container');
            
            modelSelect.addEventListener('change', function() {
                const modelValue = this.value.toLowerCase();
                const connectionType = connectionTypeSelect.value;
                
                // Afficher l'option b-PAC seulement pour USB et réseau avec Brother
                if ((connectionType === 'usb' || connectionType === 'network') && modelValue.includes('brother')) {
                    useBpacContainer.classList.remove('hidden');
                } else {
                    useBpacContainer.classList.add('hidden');
                }
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
            } else if (connectionTypeSelect.value === 'printnode') {
                printnodeContainer.classList.remove('hidden');
            }
            
            if (labelFormatSelect.value === 'custom') {
                customFormatContainer.classList.remove('hidden');
            }
            
            // Initialiser l'état du conteneur b-PAC
            const modelValue = modelSelect.value.toLowerCase();
            if ((connectionTypeSelect.value === 'usb' || connectionTypeSelect.value === 'network') && modelValue.includes('brother')) {
                useBpacContainer.classList.remove('hidden');
            } else {
                useBpacContainer.classList.add('hidden');
            }

            // Initialiser les dimensions au chargement
            updateLabelDimensions();
        });
    </script>
</x-app-layout>