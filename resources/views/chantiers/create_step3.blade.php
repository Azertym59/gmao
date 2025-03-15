<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Créer un chantier - Étape 3/5 : Produit et composition') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Étapes de progression -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="w-full flex items-center">
                        <div class="relative flex flex-col items-center text-green-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-green-500 border-green-500 text-white font-bold flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-green-500">
                                <span class="font-bold">Client</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-500"></div>
                        <div class="relative flex flex-col items-center text-green-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-green-500 border-green-500 text-white font-bold flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-green-500">
                                <span class="font-bold">Chantier</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-500"></div>
                        <div class="relative flex flex-col items-center text-accent-blue">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-accent-blue border-accent-blue text-white font-bold flex items-center justify-center">
                                3
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-accent-blue">
                                <span class="font-bold">Produit</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-700"></div>
                        <div class="relative flex flex-col items-center text-gray-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-700 text-gray-400 font-bold flex items-center justify-center">
                                4
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-gray-500">
                                <span>Interventions</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-700"></div>
                        <div class="relative flex flex-col items-center text-gray-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-700 text-gray-400 font-bold flex items-center justify-center">
                                5
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-gray-500">
                                <span>Rapports</span>
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
                                    <div class="relative">
                                        <x-text-input id="nb_modules_par_dalle" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="nb_modules_par_dalle" :value="old('nb_modules_par_dalle', isset($produitData['disposition_modules']) ? 
                                        (preg_match('/^(\d+)x(\d+)$/', $produitData['disposition_modules'], $matches) ? ($matches[1] * $matches[2]) : 4) : 4)" min="1" readonly />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <span class="text-xs text-gray-400">Auto</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Basé sur la disposition {{isset($produitData['disposition_modules']) ? $produitData['disposition_modules'] : '2x2'}} sélectionnée à l'étape précédente</p>
                                    <x-input-error :messages="$errors->get('nb_modules_par_dalle')" class="mt-2" />
                                </div>
                            </div>
                            
                            <!-- Configuration détaillée des FlightCases -->
                            <div class="mt-6 bg-blue-900/20 border border-blue-500/30 p-4 rounded-xl">
                                <h4 class="font-medium text-blue-300 mb-3">Configuration détaillée des FlightCases</h4>
                                <div id="flightcases_detail_container" class="space-y-4">
                                    <!-- Ce div sera rempli dynamiquement par JavaScript -->
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    {{ __('Continuer') }}
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
            
            // Champs pour la gestion des FlightCases détaillés
            const flightcasesDetailContainer = document.getElementById('flightcases_detail_container');
            
            // Récupérer la disposition des modules depuis les données du produit
            const dispositionModules = "{{ isset($produitData['disposition_modules']) ? $produitData['disposition_modules'] : '2x2' }}";
            let calculatedModulesPerDalle = 4; // Par défaut 2x2 = 4 modules
            
            // Calcul du nombre de modules par dalle en fonction de la disposition
            if (dispositionModules.match(/^(\d+)x(\d+)$/)) {
                const matches = dispositionModules.match(/^(\d+)x(\d+)$/);
                calculatedModulesPerDalle = parseInt(matches[1]) * parseInt(matches[2]);
            }
            
            // Fonction pour générer les contrôles de chaque FlightCase
            function generateFlightCaseControls() {
                const nbFlightcasesValue = parseInt(nbFlightcases.value) || 1;
                const nbDallesParFlightcaseValue = parseInt(nbDallesParFlightcase.value) || 8;
                const modulesParDalleValue = calculatedModulesPerDalle;
                
                // Vider le conteneur
                flightcasesDetailContainer.innerHTML = '';
                
                // Générer les contrôles pour chaque FlightCase
                for (let f = 1; f <= nbFlightcasesValue; f++) {
                    const fcContainer = document.createElement('div');
                    fcContainer.className = 'grid grid-cols-8 gap-4 p-3 bg-gray-800/30 rounded-lg border border-gray-700';
                    
                    // Informations du FlightCase
                    const fcInfo = document.createElement('div');
                    fcInfo.className = 'col-span-3 flex items-center';
                    fcInfo.innerHTML = `
                        <div class="bg-blue-500/20 p-2 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-white">FlightCase ${f}</p>
                            <p class="text-sm text-gray-400">Standard: ${nbDallesParFlightcaseValue} dalles</p>
                        </div>
                    `;
                    
                    // Case à cocher pour FlightCase partiel
                    const fcPartialCheck = document.createElement('div');
                    fcPartialCheck.className = 'col-span-2 flex items-center';
                    fcPartialCheck.innerHTML = `
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="fc_partiel_${f}" name="fc_partiel[${f}]" value="1" 
                                class="fc-partiel-checkbox rounded bg-gray-700 border-gray-600 text-accent-blue focus:ring-accent-blue focus:ring-opacity-50">
                            <span class="ml-2 text-amber-300">FlightCase partiel</span>
                        </label>
                    `;
                    
                    // Contrôle du nombre de dalles (caché par défaut)
                    const fcDallesControl = document.createElement('div');
                    fcDallesControl.className = 'col-span-3 flex items-center';
                    fcDallesControl.id = `fc_dalles_control_${f}`;
                    fcDallesControl.style.display = 'none';
                    fcDallesControl.innerHTML = `
                        <div class="w-full">
                            <label class="block text-sm font-medium text-blue-300 mb-1">Nombre de dalles présentes</label>
                            <div class="flex items-center">
                                <input type="number" id="fc_nb_dalles_${f}" name="fc_nb_dalles[${f}]" value="${nbDallesParFlightcaseValue}"
                                    min="1" max="${nbDallesParFlightcaseValue}" 
                                    class="fc-nb-dalles w-24 rounded-lg bg-gray-700 border-gray-600 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50">
                                <span class="ml-2 text-gray-400">sur ${nbDallesParFlightcaseValue}</span>
                            </div>
                        </div>
                    `;
                    
                    // Ajouter les éléments au conteneur du FlightCase
                    fcContainer.appendChild(fcInfo);
                    fcContainer.appendChild(fcPartialCheck);
                    fcContainer.appendChild(fcDallesControl);
                    
                    // Ajouter le conteneur du FlightCase au conteneur principal
                    flightcasesDetailContainer.appendChild(fcContainer);
                    
                    // Ajouter les événements pour la case à cocher
                    const checkbox = document.getElementById(`fc_partiel_${f}`);
                    const dallesControl = document.getElementById(`fc_dalles_control_${f}`);
                    const nbDallesInput = document.getElementById(`fc_nb_dalles_${f}`);
                    
                    checkbox.addEventListener('change', function() {
                        dallesControl.style.display = this.checked ? 'flex' : 'none';
                        updateTotalModules();
                    });
                    
                    if (nbDallesInput) {
                        nbDallesInput.addEventListener('input', updateTotalModules);
                    }
                }
            }
            
            // Fonction pour calculer et mettre à jour le total des modules
            function updateTotalModules() {
                let totalModules = 0;
                
                if (modeFlightcaseRadio.checked) {
                    const nbFlightcasesValue = parseInt(nbFlightcases.value) || 1;
                    const nbDallesParFlightcaseValue = parseInt(nbDallesParFlightcase.value) || 8;
                    
                    // Parcourir chaque FlightCase et compter ses dalles
                    for (let f = 1; f <= nbFlightcasesValue; f++) {
                        const fcPartielCheckbox = document.getElementById(`fc_partiel_${f}`);
                        
                        if (fcPartielCheckbox && fcPartielCheckbox.checked) {
                            // FlightCase partiel: utiliser le nombre de dalles spécifié
                            const nbDallesInput = document.getElementById(`fc_nb_dalles_${f}`);
                            const nbDalles = parseInt(nbDallesInput.value) || 0;
                            totalModules += nbDalles * calculatedModulesPerDalle;
                        } else {
                            // FlightCase complet: utiliser le nombre standard de dalles
                            totalModules += nbDallesParFlightcaseValue * calculatedModulesPerDalle;
                        }
                    }
                    
                    totalModulesFc.textContent = totalModules;
                } else {
                    totalModules = parseInt(nbModulesTotal.value) || 0;
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
            nbFlightcases.addEventListener('input', function() {
                generateFlightCaseControls();
                updateTotalModules();
            });
            nbDallesParFlightcase.addEventListener('input', function() {
                generateFlightCaseControls();
                updateTotalModules();
            });
            nbModulesTotal.addEventListener('input', updateTotalModules);
            
            // Initialisation
            if (modeFlightcaseRadio.checked) {
                flightcaseContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
                generateFlightCaseControls();
            } else {
                individuelContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
            }
            
            updateTotalModules();
        });
    </script>
</x-app-layout>