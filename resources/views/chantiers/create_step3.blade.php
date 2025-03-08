<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Créer un chantier - Étape 3/3') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Étapes de progression -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="w-full flex items-center">
                        <div class="relative flex flex-col items-center text-accent-blue">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-accent-blue border-accent-blue text-white font-bold flex items-center justify-center">
                                ✓
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-accent-blue">
                                <span class="font-bold">Informations</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-accent-blue"></div>
                        <div class="relative flex flex-col items-center text-accent-blue">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-accent-blue border-accent-blue text-white font-bold flex items-center justify-center">
                                ✓
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-accent-blue">
                                <span class="font-bold">Produit</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-accent-blue"></div>
                        <div class="relative flex flex-col items-center text-accent-blue">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-accent-blue border-accent-blue text-white font-bold flex items-center justify-center">
                                3
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-accent-blue">
                                <span class="font-bold">Composition</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <!-- Informations sur le produit sélectionné -->
                    <div class="mb-6 bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                        <h3 class="font-medium text-blue-300 mb-2">Produit sélectionné</h3>
                        @if($produitData['selection_type'] == 'existant' && $produitRef)
                            <p class="text-gray-300"><span class="font-semibold">Produit:</span> {{ $produitRef->marque }} {{ $produitRef->modele }}</p>
                            <p class="text-gray-300"><span class="font-semibold">Pitch:</span> {{ $produitRef->pitch }} mm</p>
                            <p class="text-gray-300"><span class="font-semibold">Utilisation:</span> {{ $produitRef->utilisation == 'indoor' ? 'Indoor' : 'Outdoor' }}</p>
                            <p class="text-gray-300"><span class="font-semibold">Électronique:</span> 
                                @if($produitRef->electronique == 'autre')
                                    {{ $produitRef->electronique_detail }}
                                @else
                                    {{ ucfirst($produitRef->electronique) }}
                                @endif
                            </p>
                        @else
                            <p class="text-gray-300"><span class="font-semibold">Produit:</span> {{ $produitData['marque'] }} {{ $produitData['modele'] }} (Nouveau)</p>
                            <p class="text-gray-300"><span class="font-semibold">Pitch:</span> {{ $produitData['pitch'] }} mm</p>
                            <p class="text-gray-300"><span class="font-semibold">Utilisation:</span> {{ $produitData['utilisation'] == 'indoor' ? 'Indoor' : 'Outdoor' }}</p>
                            <p class="text-gray-300"><span class="font-semibold">Électronique:</span> 
                                @if($produitData['electronique'] == 'autre')
                                    {{ $produitData['electronique_detail'] }}
                                @else
                                    {{ ucfirst($produitData['electronique']) }}
                                @endif
                            </p>
                        @endif
                    </div>

                    <form id="compositionForm" method="POST" action="{{ route('chantiers.store.step3') }}">
                        @csrf
                        
                        <!-- Sélection du mode -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-white mb-4">Mode de création</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="mode_flightcase" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50 @error('mode') border-red-500 @enderror" id="flightcase_container">
                                        <input type="radio" name="mode" id="mode_flightcase" value="flightcase" class="mr-2 accent-accent-blue" checked>
                                        <div>
                                            <span class="font-medium text-white">Structure Flight Case</span>
                                            <p class="text-sm text-gray-400">Pour les envois structurés en flight cases contenant des dalles</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <div>
                                    <label for="mode_individuel" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50 @error('mode') border-red-500 @enderror" id="individuel_container">
                                        <input type="radio" name="mode" id="mode_individuel" value="individuel" class="mr-2 accent-accent-blue">
                                        <div>
                                            <span class="font-medium text-white">Modules individuels</span>
                                            <p class="text-sm text-gray-400">Pour les envois de modules en pièces détachées</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            @error('mode')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Configuration Flight Case - visible seulement si mode=flightcase -->
                        <div id="flightcase_config" class="mb-6 p-4 border border-gray-700 rounded-xl bg-gray-800/30">
                            <h3 class="text-lg font-medium text-white mb-4">Configuration Flight Case</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="nb_flightcases" :value="__('Nombre de Flight Cases')" class="text-gray-300" />
                                    <x-text-input id="nb_flightcases" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="nb_flightcases" :value="old('nb_flightcases', 1)" min="1" required />
                                    <x-input-error :messages="$errors->get('nb_flightcases')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="nb_dalles_par_flightcase" :value="__('Dalles par Flight Case')" class="text-gray-300" />
                                    <x-text-input id="nb_dalles_par_flightcase" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="nb_dalles_par_flightcase" :value="old('nb_dalles_par_flightcase', 8)" min="1" required />
                                    <x-input-error :messages="$errors->get('nb_dalles_par_flightcase')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="nb_modules_par_dalle" :value="__('Modules par Dalle')" class="text-gray-300" />
                                    <x-text-input id="nb_modules_par_dalle" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="nb_modules_par_dalle" :value="old('nb_modules_par_dalle', 4)" min="1" required />
                                    <x-input-error :messages="$errors->get('nb_modules_par_dalle')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div class="mt-4 p-2 bg-gray-700/50 rounded-lg">
                                <p class="text-sm text-gray-300"><span id="total_modules_fc">32</span> modules seront créés au total.</p>
                            </div>
                        </div>
                        
                        <!-- Configuration Modules individuels - visible seulement si mode=individuel -->
                        <div id="individuel_config" class="mb-6 p-4 border border-gray-700 rounded-xl bg-gray-800/30" style="display: none;">
                            <h3 class="text-lg font-medium text-white mb-4">Configuration Modules individuels</h3>
                            
                            <div>
                                <x-input-label for="nb_modules_total" :value="__('Nombre total de modules')" class="text-gray-300" />
                                <x-text-input id="nb_modules_total" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="nb_modules_total" :value="old('nb_modules_total', 32)" min="1" required />
                                <x-input-error :messages="$errors->get('nb_modules_total')" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- Configuration commune des dalles -->
                        <div class="mb-6 p-4 border border-gray-700 rounded-xl bg-gray-800/30">
                            <h3 class="text-lg font-medium text-white mb-4">Configuration des dalles</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="largeur_dalle" :value="__('Largeur dalle (mm)')" class="text-gray-300" />
                                    <x-text-input id="largeur_dalle" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="largeur_dalle" :value="old('largeur_dalle', 500)" step="0.1" min="1" required />
                                    <x-input-error :messages="$errors->get('largeur_dalle')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="hauteur_dalle" :value="__('Hauteur dalle (mm)')" class="text-gray-300" />
                                    <x-text-input id="hauteur_dalle" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="hauteur_dalle" :value="old('hauteur_dalle', 500)" step="0.1" min="1" required />
                                    <x-input-error :messages="$errors->get('hauteur_dalle')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="alimentation_dalle" :value="__('Alimentation')" class="text-gray-300" />
                                    <x-text-input id="alimentation_dalle" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="alimentation_dalle" :value="old('alimentation_dalle', 'Standard 5V')" required />
                                    <x-input-error :messages="$errors->get('alimentation_dalle')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="carte_reception" :value="__('Carte de réception')" class="text-gray-300" />
                                    <x-text-input id="carte_reception" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="carte_reception" :value="old('carte_reception')" />
                                    <x-input-error :messages="$errors->get('carte_reception')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="hub" :value="__('Hub')" class="text-gray-300" />
                                    <x-text-input id="hub" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="hub" :value="old('hub')" />
                                    <x-input-error :messages="$errors->get('hub')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Configuration commune des modules -->
                        <div class="mb-6 p-4 border border-gray-700 rounded-xl bg-gray-800/30">
                            <h3 class="text-lg font-medium text-white mb-4">Configuration des modules</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="largeur_module" :value="__('Largeur module (mm)')" class="text-gray-300" />
                                    <x-text-input id="largeur_module" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="largeur_module" :value="old('largeur_module', 250)" step="0.1" min="1" required />
                                    <x-input-error :messages="$errors->get('largeur_module')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="hauteur_module" :value="__('Hauteur module (mm)')" class="text-gray-300" />
                                    <x-text-input id="hauteur_module" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="hauteur_module" :value="old('hauteur_module', 250)" step="0.1" min="1" required />
                                    <x-input-error :messages="$errors->get('hauteur_module')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="nb_pixels_largeur" :value="__('Pixels en largeur')" class="text-gray-300" />
                                    <x-text-input id="nb_pixels_largeur" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="nb_pixels_largeur" :value="old('nb_pixels_largeur', 64)" min="1" required />
                                    <x-input-error :messages="$errors->get('nb_pixels_largeur')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="nb_pixels_hauteur" :value="__('Pixels en hauteur')" class="text-gray-300" />
                                    <x-text-input id="nb_pixels_hauteur" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="nb_pixels_hauteur" :value="old('nb_pixels_hauteur', 64)" min="1" required />
                                    <x-input-error :messages="$errors->get('nb_pixels_hauteur')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="driver" :value="__('Driver (IC de commande)')" class="text-gray-300" />
                                    <x-text-input id="driver" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="driver" :value="old('driver')" />
                                    <x-input-error :messages="$errors->get('driver')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="shift_register" :value="__('Shift Register')" class="text-gray-300" />
                                    <x-text-input id="shift_register" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="shift_register" :value="old('shift_register')" />
                                    <x-input-error :messages="$errors->get('shift_register')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="buffer" :value="__('Buffer')" class="text-gray-300" />
                                    <x-text-input id="buffer" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="buffer" :value="old('buffer')" />
                                    <x-input-error :messages="$errors->get('buffer')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-6">
                            <div>
                                <div id="total_modules_message" class="font-medium text-lg text-accent-blue">
                                    32 modules seront créés au total
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <a href="{{ route('chantiers.create.step2') }}" class="btn-action btn-secondary flex items-center mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    {{ __('Précédent') }}
                                </a>
                                <button type="submit" id="submitBtn" class="btn-action btn-primary flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ __('Créer le chantier') }}
                                </button>
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
            
            // Fonction pour calculer et mettre à jour le total des modules
            function updateTotalModules() {
                let totalModules = 0;
                
                if (modeFlightcaseRadio.checked) {
                    totalModules = nbFlightcases.value * nbDallesParFlightcase.value * nbModulesParDalle.value;
                    totalModulesFc.textContent = totalModules;
                } else {
                    totalModules = parseInt(nbModulesTotal.value);
                }
                
                totalModulesMessage.textContent = totalModules + ' modules seront créés au total';
            }
            
            // Gestionnaires d'événements pour le changement de mode
            modeFlightcaseRadio.addEventListener('change', function() {
                if (this.checked) {
                    flightcaseConfig.style.display = 'block';
                    individuelConfig.style.display = 'none';
                    flightcaseContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
                    individuelContainer.classList.remove('border-accent-blue', 'bg-gray-800/50');
                    updateTotalModules();
                }
            });
            
            modeIndividuelRadio.addEventListener('change', function() {
                if (this.checked) {
                    flightcaseConfig.style.display = 'none';
                    individuelConfig.style.display = 'block';
                    flightcaseContainer.classList.remove('border-accent-blue', 'bg-gray-800/50');
                    individuelContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
                    updateTotalModules();
                }
            });
            
            // Gestionnaires d'événements pour les champs de saisie
            nbFlightcases.addEventListener('input', updateTotalModules);
            nbDallesParFlightcase.addEventListener('input', updateTotalModules);
            nbModulesParDalle.addEventListener('input', updateTotalModules);
            nbModulesTotal.addEventListener('input', updateTotalModules);
            
            // Initialisation
            if (modeFlightcaseRadio.checked) {
                flightcaseContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
            } else {
                individuelContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
            }
            
            updateTotalModules();
        });
    </script>
</x-app-layout>