<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Création en masse de modules') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-medium text-blue-800">Produit: {{ $produit->marque }} {{ $produit->modele }}</h3>
                        <p>Pitch: {{ $produit->pitch }} mm</p>
                        <p>Utilisation: {{ $produit->utilisation == 'indoor' ? 'Indoor' : 'Outdoor' }}</p>
                        <p>Électronique: {{ ucfirst($produit->electronique) }}</p>
                    </div>

                    <form id="massCreateForm" method="POST" action="{{ route('modules.mass-create.process', $produit) }}">
                        @csrf
                        
                        <!-- Sélection du mode -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Mode de création</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="mode_flightcase" class="flex items-center p-4 border rounded-lg cursor-pointer @error('mode') border-red-500 @enderror" id="flightcase_container">
                                        <input type="radio" name="mode" id="mode_flightcase" value="flightcase" class="mr-2" checked>
                                        <div>
                                            <span class="font-medium">Structure Flight Case</span>
                                            <p class="text-sm text-gray-500">Pour les envois structurés en flight cases contenant des dalles</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <div>
                                    <label for="mode_individuel" class="flex items-center p-4 border rounded-lg cursor-pointer @error('mode') border-red-500 @enderror" id="individuel_container">
                                        <input type="radio" name="mode" id="mode_individuel" value="individuel" class="mr-2">
                                        <div>
                                            <span class="font-medium">Modules individuels</span>
                                            <p class="text-sm text-gray-500">Pour les envois de modules en pièces détachées</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            @error('mode')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Configuration Flight Case - visible seulement si mode=flightcase -->
                        <div id="flightcase_config" class="mb-6 p-4 border rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Configuration Flight Case</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="nb_flightcases" :value="__('Nombre de Flight Cases')" />
                                    <x-text-input id="nb_flightcases" class="block mt-1 w-full" type="number" name="nb_flightcases" :value="old('nb_flightcases', 1)" min="1" required />
                                    <x-input-error :messages="$errors->get('nb_flightcases')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="nb_dalles_par_flightcase" :value="__('Dalles par Flight Case')" />
                                    <x-text-input id="nb_dalles_par_flightcase" class="block mt-1 w-full" type="number" name="nb_dalles_par_flightcase" :value="old('nb_dalles_par_flightcase', 8)" min="1" required />
                                    <x-input-error :messages="$errors->get('nb_dalles_par_flightcase')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="nb_modules_par_dalle" :value="__('Modules par Dalle')" />
                                    <x-text-input id="nb_modules_par_dalle" class="block mt-1 w-full" type="number" name="nb_modules_par_dalle" :value="old('nb_modules_par_dalle', 4)" min="1" required />
                                    <x-input-error :messages="$errors->get('nb_modules_par_dalle')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div class="mt-4 bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" id="flightcase_partiel" name="flightcase_partiel" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="flightcase_partiel" class="ml-2 font-bold text-yellow-800">FlightCase partiel / Dalles manquantes</label>
                                </div>
                                <p class="text-sm text-yellow-700 mb-2">Cochez cette option si certaines dalles sont manquantes dans un ou plusieurs FlightCases.</p>
                            </div>
                            
                            <div id="dalles_manquantes_container" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg" style="display: none;">
                                <h4 class="font-medium text-blue-800 mb-2">Sélection des dalles présentes</h4>
                                <p class="mb-3 text-blue-700">Sélectionnez uniquement les dalles qui sont présentes (celles non sélectionnées seront ignorées)</p>
                                
                                <div id="dalles_selection_container" class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-3">
                                    <!-- Ce div sera rempli dynamiquement par JavaScript -->
                                </div>
                                
                                <div class="mt-3 text-sm text-blue-600 flex justify-end">
                                    <button type="button" id="select_all_dalles" class="bg-blue-500 text-white px-3 py-1 rounded mr-2 hover:bg-blue-600">Tout sélectionner</button>
                                    <button type="button" id="deselect_all_dalles" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Tout désélectionner</button>
                                </div>
                            </div>
                            
                            <div class="mt-4 p-2 bg-gray-50 rounded">
                                <p class="text-sm text-gray-700"><span id="total_modules_fc">32</span> modules seront créés au total.</p>
                            </div>
                        </div>
                        
                        <!-- Configuration Modules individuels - visible seulement si mode=individuel -->
                        <div id="individuel_config" class="mb-6 p-4 border rounded-lg" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Configuration Modules individuels</h3>
                            
                            <div>
                                <x-input-label for="nb_modules_total" :value="__('Nombre total de modules')" />
                                <x-text-input id="nb_modules_total" class="block mt-1 w-full" type="number" name="nb_modules_total" :value="old('nb_modules_total', 32)" min="1" required />
                                <x-input-error :messages="$errors->get('nb_modules_total')" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- Configuration commune des dalles -->
                        <div class="mb-6 p-4 border rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Configuration des dalles</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="largeur_dalle" :value="__('Largeur dalle (mm)')" />
                                    <x-text-input id="largeur_dalle" class="block mt-1 w-full" type="number" name="largeur_dalle" :value="old('largeur_dalle', 500)" step="0.1" min="1" required />
                                    <x-input-error :messages="$errors->get('largeur_dalle')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="hauteur_dalle" :value="__('Hauteur dalle (mm)')" />
                                    <x-text-input id="hauteur_dalle" class="block mt-1 w-full" type="number" name="hauteur_dalle" :value="old('hauteur_dalle', 500)" step="0.1" min="1" required />
                                    <x-input-error :messages="$errors->get('hauteur_dalle')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="alimentation_dalle" :value="__('Alimentation')" />
                                    <x-text-input id="alimentation_dalle" class="block mt-1 w-full" type="text" name="alimentation_dalle" :value="old('alimentation_dalle', 'Standard 5V')" required />
                                    <x-input-error :messages="$errors->get('alimentation_dalle')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="carte_reception" :value="__('Carte de réception')" />
                                    <x-text-input id="carte_reception" class="block mt-1 w-full" type="text" name="carte_reception" :value="old('carte_reception')" />
                                    <x-input-error :messages="$errors->get('carte_reception')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="hub" :value="__('Hub')" />
                                    <x-text-input id="hub" class="block mt-1 w-full" type="text" name="hub" :value="old('hub')" />
                                    <x-input-error :messages="$errors->get('hub')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Configuration commune des modules -->
                        <div class="mb-6 p-4 border rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Configuration des modules</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="largeur_module" :value="__('Largeur module (mm)')" />
                                    <x-text-input id="largeur_module" class="block mt-1 w-full" type="number" name="largeur_module" :value="old('largeur_module', 250)" step="0.1" min="1" required />
                                    <x-input-error :messages="$errors->get('largeur_module')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="hauteur_module" :value="__('Hauteur module (mm)')" />
                                    <x-text-input id="hauteur_module" class="block mt-1 w-full" type="number" name="hauteur_module" :value="old('hauteur_module', 250)" step="0.1" min="1" required />
                                    <x-input-error :messages="$errors->get('hauteur_module')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="nb_pixels_largeur" :value="__('Pixels en largeur')" />
                                    <x-text-input id="nb_pixels_largeur" class="block mt-1 w-full" type="number" name="nb_pixels_largeur" :value="old('nb_pixels_largeur', 64)" min="1" required />
                                    <x-input-error :messages="$errors->get('nb_pixels_largeur')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="nb_pixels_hauteur" :value="__('Pixels en hauteur')" />
                                    <x-text-input id="nb_pixels_hauteur" class="block mt-1 w-full" type="number" name="nb_pixels_hauteur" :value="old('nb_pixels_hauteur', 64)" min="1" required />
                                    <x-input-error :messages="$errors->get('nb_pixels_hauteur')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="driver" :value="__('Driver (IC de commande)')" />
                                    <x-text-input id="driver" class="block mt-1 w-full" type="text" name="driver" :value="old('driver')" />
                                    <x-input-error :messages="$errors->get('driver')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="shift_register" :value="__('Shift Register')" />
                                    <x-text-input id="shift_register" class="block mt-1 w-full" type="text" name="shift_register" :value="old('shift_register')" />
                                    <x-input-error :messages="$errors->get('shift_register')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="buffer" :value="__('Buffer')" />
                                    <x-text-input id="buffer" class="block mt-1 w-full" type="text" name="buffer" :value="old('buffer')" />
                                    <x-input-error :messages="$errors->get('buffer')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Options d'impression des QR codes -->
                        <div class="mb-6 p-4 border rounded-lg bg-blue-50">
                            <h3 class="text-lg font-medium text-blue-800 mb-4">Options d'impression</h3>
                            
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="print_qrcodes" name="print_qrcodes" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="print_qrcodes" class="ml-2 text-blue-800">Imprimer les QR codes après la création</label>
                            </div>
                            
                            <p class="text-sm text-blue-700 mb-2">
                                En cochant cette option, vous serez redirigé vers la page d'impression des QR codes après la création des modules. Vous pourrez y imprimer les QR codes pour les FlightCases, les Dalles et les Modules.
                            </p>
                            
                            <div class="mt-2 bg-yellow-50 p-2 rounded border border-yellow-200">
                                <p class="text-sm text-yellow-800">
                                    <strong>Note:</strong> Assurez-vous que QZ Tray est installé et en cours d'exécution sur votre ordinateur, et qu'une imprimante est configurée comme imprimante par défaut.
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-6">
                            <div>
                                <div id="total_modules_message" class="font-medium text-lg text-blue-700">
                                    32 modules seront créés au total
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <a href="{{ route('produits.show', $produit) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">
                                    {{ __('Annuler') }}
                                </a>
                                <x-primary-button id="submitBtn">
                                    {{ __('Créer les modules') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modeFlightcaseRadio = document.getElementById('mode_flightcase');
            const modeIndividuelRadio = document.getElementById('mode_individuel');
            const flightcaseConfig = document.getElementById('flightcase_config');
            const individuelConfig = document.getElementById('individuel_config');
            const flightcaseContainer = document.getElementById('flightcase_container');
            const individuelContainer = document.getElementById('individuel_container');
            const totalModulesMessage = document.getElementById('total_modules_message');
            const totalModulesFc = document.getElementById('total_modules_fc');
            
            // Champs pour le mode flight case
            const nbFlightcases = document.getElementById('nb_flightcases');
            const nbDallesParFlightcase = document.getElementById('nb_dalles_par_flightcase');
            const nbModulesParDalle = document.getElementById('nb_modules_par_dalle');
            
            // Champ pour le mode individuel
            const nbModulesTotal = document.getElementById('nb_modules_total');
            
            // Champs pour la gestion des FlightCases partiels
            const flightcasePartielCheckbox = document.getElementById('flightcase_partiel');
            const dallesManquantesContainer = document.getElementById('dalles_manquantes_container');
            const dallesSelectionContainer = document.getElementById('dalles_selection_container');
            const selectAllDallesBtn = document.getElementById('select_all_dalles');
            const deselectAllDallesBtn = document.getElementById('deselect_all_dalles');
            
            // Fonction pour calculer et mettre à jour le total des modules
            function updateTotalModules() {
                let totalModules = 0;
                
                if (modeFlightcaseRadio.checked) {
                    if (flightcasePartielCheckbox && flightcasePartielCheckbox.checked) {
                        // Compter uniquement les dalles sélectionnées
                        const dallesSelectionnees = document.querySelectorAll('.dalle-checkbox:checked').length;
                        totalModules = dallesSelectionnees * parseInt(nbModulesParDalle.value || 4);
                    } else {
                        // Compter toutes les dalles
                        totalModules = nbFlightcases.value * nbDallesParFlightcase.value * nbModulesParDalle.value;
                    }
                    totalModulesFc.textContent = totalModules;
                } else {
                    totalModules = parseInt(nbModulesTotal.value);
                }
                
                totalModulesMessage.textContent = totalModules + ' modules seront créés au total';
            }
            
            // Mettre à jour la liste des dalles selon les entrées du formulaire
            function updateDallesSelection() {
                if (!flightcasePartielCheckbox || !flightcasePartielCheckbox.checked) return;
                
                const nbFlightcasesValue = parseInt(nbFlightcases.value) || 1;
                const nbDallesParFlightcaseValue = parseInt(nbDallesParFlightcase.value) || 8;
                
                dallesSelectionContainer.innerHTML = ''; // Vider le conteneur
                
                // Générer les cases à cocher pour chaque dalle
                for (let f = 1; f <= nbFlightcasesValue; f++) {
                    // Ajouter un titre pour chaque flight case
                    const fcTitle = document.createElement('div');
                    fcTitle.className = 'col-span-full mt-2 mb-1 font-medium text-gray-800';
                    fcTitle.textContent = `Flight Case ${f}`;
                    dallesSelectionContainer.appendChild(fcTitle);
                    
                    for (let d = 1; d <= nbDallesParFlightcaseValue; d++) {
                        const dalleId = `FC${f}-D${d}`;
                        
                        const checkbox = document.createElement('div');
                        checkbox.className = 'flex items-center';
                        checkbox.innerHTML = `
                            <input type="checkbox" id="dalle_${dalleId}" name="dalles_presentes[]" value="${dalleId}" class="dalle-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-200" checked>
                            <label for="dalle_${dalleId}" class="ml-2 text-sm text-gray-700">${dalleId}</label>
                        `;
                        
                        dallesSelectionContainer.appendChild(checkbox);
                    }
                }
            }
            
            // Gestionnaires d'événements pour le changement de mode
            modeFlightcaseRadio.addEventListener('change', function() {
                if (this.checked) {
                    flightcaseConfig.style.display = 'block';
                    individuelConfig.style.display = 'none';
                    flightcaseContainer.classList.add('border-blue-500', 'bg-blue-50');
                    individuelContainer.classList.remove('border-blue-500', 'bg-blue-50');
                    updateTotalModules();
                }
            });
            
            modeIndividuelRadio.addEventListener('change', function() {
                if (this.checked) {
                    flightcaseConfig.style.display = 'none';
                    individuelConfig.style.display = 'block';
                    flightcaseContainer.classList.remove('border-blue-500', 'bg-blue-50');
                    individuelContainer.classList.add('border-blue-500', 'bg-blue-50');
                    updateTotalModules();
                }
            });
            
            // Afficher/masquer les options de dalles manquantes
            if (flightcasePartielCheckbox) {
                flightcasePartielCheckbox.addEventListener('change', function() {
                    dallesManquantesContainer.style.display = this.checked ? 'block' : 'none';
                    updateDallesSelection();
                    updateTotalModules(); // Mettre à jour le total des modules
                });
            }
            
            // Sélectionner toutes les dalles
            if (selectAllDallesBtn) {
                selectAllDallesBtn.addEventListener('click', function() {
                    const checkboxes = document.querySelectorAll('.dalle-checkbox');
                    checkboxes.forEach(cb => cb.checked = true);
                    updateTotalModules();
                });
            }
            
            // Désélectionner toutes les dalles
            if (deselectAllDallesBtn) {
                deselectAllDallesBtn.addEventListener('click', function() {
                    const checkboxes = document.querySelectorAll('.dalle-checkbox');
                    checkboxes.forEach(cb => cb.checked = false);
                    updateTotalModules();
                });
            }
            
            // Mettre à jour le total des modules quand une dalle est cochée/décochée
            if (dallesSelectionContainer) {
                dallesSelectionContainer.addEventListener('change', function(e) {
                    if (e.target.classList.contains('dalle-checkbox')) {
                        updateTotalModules();
                    }
                });
            }
            
            // Gestionnaires d'événements pour les champs de saisie
            nbFlightcases.addEventListener('input', function() {
                updateDallesSelection();
                updateTotalModules();
            });
            nbDallesParFlightcase.addEventListener('input', function() {
                updateDallesSelection();
                updateTotalModules();
            });
            nbModulesParDalle.addEventListener('input', updateTotalModules);
            nbModulesTotal.addEventListener('input', updateTotalModules);
            
            // Initialisation
            if (modeFlightcaseRadio.checked) {
                flightcaseContainer.classList.add('border-blue-500', 'bg-blue-50');
            } else {
                individuelContainer.classList.add('border-blue-500', 'bg-blue-50');
            }
            
            updateDallesSelection();
            updateTotalModules();
        });
    </script>
</x-app-layout>
