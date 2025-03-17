<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Ajouter une dalle') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="mb-6 bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                        <h3 class="font-medium text-blue-300 mb-2">Informations du produit</h3>
                        <p class="text-gray-300"><span class="font-medium">Produit:</span> {{ $produit->marque }} {{ $produit->modele }}</p>
                        <p class="text-gray-300"><span class="font-medium">Chantier:</span> {{ $produit->chantier->nom }}</p>
                        <p class="text-gray-300"><span class="font-medium">Client:</span> {{ $produit->chantier->client->nom_complet }}</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-500/30 border border-red-500/50 text-red-400 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('dalles.store') }}">
                        @csrf
                        <input type="hidden" name="produit_id" value="{{ $produit->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Partie gauche : Dimensions et infos générales -->
                            <div>
                                <h3 class="text-lg font-medium text-blue-300 mb-4">Dimensions et caractéristiques</h3>
                                
                                <!-- Largeur -->
                                <div class="mb-4">
                                    <x-input-label for="largeur" :value="__('Largeur (mm)')" class="text-gray-300" />
                                    <x-text-input id="largeur" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="largeur" :value="old('largeur')" step="0.1" min="1" required />
                                    <x-input-error :messages="$errors->get('largeur')" class="mt-2" />
                                </div>

                                <!-- Hauteur -->
                                <div class="mb-4">
                                    <x-input-label for="hauteur" :value="__('Hauteur (mm)')" class="text-gray-300" />
                                    <x-text-input id="hauteur" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="hauteur" :value="old('hauteur')" step="0.1" min="1" required />
                                    <x-input-error :messages="$errors->get('hauteur')" class="mt-2" />
                                </div>

                                <!-- Nombre de modules -->
                                <div class="mb-4">
                                    <x-input-label for="nb_modules" :value="__('Nombre de modules')" class="text-gray-300" />
                                    <x-text-input id="nb_modules" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="nb_modules" :value="old('nb_modules', 1)" min="1" required />
                                    <x-input-error :messages="$errors->get('nb_modules')" class="mt-2" />
                                </div>

                                <!-- Alimentation -->
                                <div class="mb-4">
                                    <x-input-label for="alimentation" :value="__('Alimentation')" class="text-gray-300" />
                                    <x-text-input id="alimentation" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="alimentation" :value="old('alimentation')" required />
                                    <x-input-error :messages="$errors->get('alimentation')" class="mt-2" />
                                </div>

                                <!-- Référence dalle -->
                                <div class="mb-4">
                                    <x-input-label for="reference_dalle" :value="__('Référence (optionnel)')" class="text-gray-300" />
                                    <x-text-input id="reference_dalle" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="reference_dalle" :value="old('reference_dalle')" />
                                    <p class="text-xs text-gray-400 mt-1">Référence de la dalle dans le système (ex: FC1-D1)</p>
                                    <x-input-error :messages="$errors->get('reference_dalle')" class="mt-2" />
                                </div>
                                
                                <!-- Numéro dalle -->
                                <div class="mb-4">
                                    <x-input-label for="numero_dalle" :value="__('Numéro dalle usine/client')" class="text-gray-300" />
                                    <x-text-input id="numero_dalle" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="numero_dalle" :value="old('numero_dalle')" />
                                    <p class="text-xs text-gray-400 mt-1">Numéro d'usine ou attribué par le client pour la traçabilité</p>
                                    <x-input-error :messages="$errors->get('numero_dalle')" class="mt-2" />
                                </div>
                                
                                <!-- Carte de réception -->
                                <div class="mb-4">
                                    <x-input-label for="carte_reception" :value="__('Carte de réception')" class="text-gray-300" />
                                    <x-text-input id="carte_reception" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="carte_reception" :value="old('carte_reception', $produit->carte_reception ?? '')" />
                                    <x-input-error :messages="$errors->get('carte_reception')" class="mt-2" />
                                </div>
                                
                                <!-- Hub -->
                                <div class="mb-4">
                                    <x-input-label for="hub" :value="__('Hub')" class="text-gray-300" />
                                    <x-text-input id="hub" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="hub" :value="old('hub', $produit->hub ?? '')" />
                                    <x-input-error :messages="$errors->get('hub')" class="mt-2" />
                                </div>
                            </div>
                            
                            <!-- Partie droite : Disposition des modules -->
                            <div>
                                <h3 class="text-lg font-medium text-blue-300 mb-4">Disposition des modules</h3>
                                
                                <!-- Type de disposition -->
                                <div class="mb-4">
                                    <x-input-label for="disposition_type" :value="__('Type de disposition')" class="text-gray-300" />
                                    <select id="disposition_type" name="disposition_type" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="standard" {{ old('disposition_type') == 'standard' ? 'selected' : '' }}>Standard (grille)</option>
                                        <option value="personnalisee" {{ old('disposition_type') == 'personnalisee' ? 'selected' : '' }}>Personnalisée</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('disposition_type')" class="mt-2" />
                                </div>
                                
                                <!-- Configuration standard -->
                                <div id="standard_config" class="{{ old('disposition_type') == 'personnalisee' ? 'hidden' : '' }}">
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Nombre de colonnes -->
                                        <div class="mb-4">
                                            <x-input-label for="nb_colonnes" :value="__('Nombre de colonnes')" class="text-gray-300" />
                                            <x-text-input id="nb_colonnes" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="nb_colonnes" :value="old('nb_colonnes', 1)" min="1" max="20" required />
                                            <x-input-error :messages="$errors->get('nb_colonnes')" class="mt-2" />
                                        </div>
                                        
                                        <!-- Nombre de lignes -->
                                        <div class="mb-4">
                                            <x-input-label for="nb_lignes" :value="__('Nombre de lignes')" class="text-gray-300" />
                                            <x-text-input id="nb_lignes" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="nb_lignes" :value="old('nb_lignes', 1)" min="1" max="20" required />
                                            <x-input-error :messages="$errors->get('nb_lignes')" class="mt-2" />
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-400 mb-2">La lettre A sera attribuée au module en haut à gauche, puis les lettres continueront séquentiellement de haut en bas pour chaque colonne.</p>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="border border-gray-600 rounded-lg p-4 bg-gray-800/30">
                                            <h4 class="font-medium text-gray-300 mb-2">Prévisualisation</h4>
                                            <div id="grid_preview" class="grid gap-2" style="grid-template-columns: repeat(1, 1fr);">
                                                <!-- Prévisualisé en JavaScript -->
                                                <div class="bg-gray-700 rounded-lg p-3 text-center border border-gray-600">
                                                    <span class="text-blue-300 font-bold">A</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Configuration personnalisée -->
                                <div id="custom_config" class="{{ old('disposition_type') == 'personnalisee' ? '' : 'hidden' }}">
                                    <p class="text-gray-400 mb-4">La disposition personnalisée permettra une configuration détaillée des positions de modules après la création de la dalle.</p>
                                </div>
                                
                                <!-- Mode d'emballage -->
                                <div class="mb-4">
                                    <x-input-label for="mode_emballage" :value="__('Mode d\'emballage')" class="text-gray-300" />
                                    <select id="mode_emballage" name="mode_emballage" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="flightcase" {{ old('mode_emballage') == 'flightcase' ? 'selected' : '' }}>Flight case</option>
                                        <option value="carton" {{ old('mode_emballage') == 'carton' ? 'selected' : '' }}>Carton</option>
                                        <option value="palette" {{ old('mode_emballage') == 'palette' ? 'selected' : '' }}>Palette</option>
                                        <option value="autre" {{ old('mode_emballage') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('mode_emballage')" class="mt-2" />
                                </div>
                                
                                <!-- Détail du mode d'emballage (si autre) -->
                                <div id="mode_emballage_detail_container" class="{{ old('mode_emballage') == 'autre' ? '' : 'hidden' }} mb-4">
                                    <x-input-label for="mode_emballage_detail" :value="__('Précisez le mode d\'emballage')" class="text-gray-300" />
                                    <x-text-input id="mode_emballage_detail" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="mode_emballage_detail" :value="old('mode_emballage_detail')" />
                                    <x-input-error :messages="$errors->get('mode_emballage_detail')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('produits.show', $produit) }}" class="btn-action btn-secondary mr-3">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" class="btn-action btn-primary">
                                {{ __('Enregistrer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dispositionTypeSelect = document.getElementById('disposition_type');
            const standardConfig = document.getElementById('standard_config');
            const customConfig = document.getElementById('custom_config');
            const modeEmballageSelect = document.getElementById('mode_emballage');
            const modeEmballageDetailContainer = document.getElementById('mode_emballage_detail_container');
            const nbColonnesInput = document.getElementById('nb_colonnes');
            const nbLignesInput = document.getElementById('nb_lignes');
            const nbModulesInput = document.getElementById('nb_modules');
            const gridPreview = document.getElementById('grid_preview');
            
            // Gérer l'affichage en fonction du type de disposition
            function updateDispositionConfig() {
                if (dispositionTypeSelect.value === 'standard') {
                    standardConfig.classList.remove('hidden');
                    customConfig.classList.add('hidden');
                } else {
                    standardConfig.classList.add('hidden');
                    customConfig.classList.remove('hidden');
                }
            }
            
            // Gérer l'affichage du détail du mode d'emballage
            function updateEmballageDetailVisibility() {
                if (modeEmballageSelect.value === 'autre') {
                    modeEmballageDetailContainer.classList.remove('hidden');
                } else {
                    modeEmballageDetailContainer.classList.add('hidden');
                }
            }
            
            // Mettre à jour le nombre de modules automatiquement
            function updateNbModules() {
                const colonnes = parseInt(nbColonnesInput.value) || 1;
                const lignes = parseInt(nbLignesInput.value) || 1;
                nbModulesInput.value = colonnes * lignes;
            }
            
            // Générer un aperçu de la grille
            function updateGridPreview() {
                const colonnes = parseInt(nbColonnesInput.value) || 1;
                const lignes = parseInt(nbLignesInput.value) || 1;
                
                // Mettre à jour les colonnes de la grille
                gridPreview.style.gridTemplateColumns = `repeat(${colonnes}, 1fr)`;
                
                // Vider la grille
                gridPreview.innerHTML = '';
                
                // Créer les cellules
                let letter = 'A';
                for (let col = 0; col < colonnes; col++) {
                    for (let row = 0; row < lignes; row++) {
                        const cell = document.createElement('div');
                        cell.className = 'bg-gray-700 rounded-lg p-3 text-center border border-gray-600';
                        cell.innerHTML = `<span class="text-blue-300 font-bold">${letter}</span>`;
                        gridPreview.appendChild(cell);
                        
                        // Incrémenter la lettre pour le prochain module
                        letter = incrementLetter(letter);
                    }
                }
            }
            
            // Fonction pour incrémenter les lettres (A->B, Z->AA, etc.)
            function incrementLetter(letter) {
                if (letter === 'Z') {
                    return 'AA';
                } else if (letter.endsWith('Z')) {
                    return incrementLetter(letter.slice(0, -1)) + 'A';
                } else {
                    return letter.slice(0, -1) + String.fromCharCode(letter.charCodeAt(letter.length - 1) + 1);
                }
            }
            
            // Écouter les changements
            dispositionTypeSelect.addEventListener('change', updateDispositionConfig);
            modeEmballageSelect.addEventListener('change', updateEmballageDetailVisibility);
            nbColonnesInput.addEventListener('input', function() {
                updateNbModules();
                updateGridPreview();
            });
            nbLignesInput.addEventListener('input', function() {
                updateNbModules();
                updateGridPreview();
            });
            
            // Initialiser
            updateDispositionConfig();
            updateEmballageDetailVisibility();
            updateGridPreview();
        });
    </script>
</x-app-layout>