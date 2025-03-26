<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Créer un chantier - Étape 3/4 : Produit et composition') }}
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
                        <div class="relative flex flex-col items-center text-green-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-green-500 border-green-500 text-white font-bold flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-green-500">
                                <span class="font-bold">Produit</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-700"></div>
                        <div class="relative flex flex-col items-center text-gray-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-700 text-gray-400 font-bold flex items-center justify-center">
                                4
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

                    <form id="compositionForm" method="POST" action="{{ route('chantiers.store.step3', $chantier) }}">
                        @csrf
                        
                        <!-- Sélection du mode -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-white mb-4">Mode de création</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="mode_flightcase" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50 @error('mode_configuration') border-red-500 @enderror" id="flightcase_container">
                                        <input type="radio" name="mode_configuration" id="mode_flightcase" value="flightcase" class="mr-2 accent-accent-blue" checked>
                                        <div>
                                            <span class="font-medium text-white">Structure Flight Case</span>
                                            <p class="text-sm text-gray-400">Pour les envois structurés en flight cases contenant des dalles</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <div>
                                    <label for="mode_individuel" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50 @error('mode_configuration') border-red-500 @enderror" id="individuel_container">
                                        <input type="radio" name="mode_configuration" id="mode_individuel" value="individuel" class="mr-2 accent-accent-blue">
                                        <div>
                                            <span class="font-medium text-white">Modules individuels</span>
                                            <p class="text-sm text-gray-400">Pour les envois de modules en pièces détachées</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            @error('mode_configuration')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Configuration Flight Case - visible seulement si mode=flightcase -->
                        <div id="flightcase_config" class="mb-6 p-4 border border-gray-700 rounded-xl bg-gray-800/30">
                            <h3 class="text-lg font-medium text-white mb-4">Configuration Flight Case</h3>
                            
                            <!-- Option pour tailles multiples -->
                            <div class="mb-4 p-3 bg-amber-900/20 border border-amber-600/30 rounded-lg">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="multiple_sizes" name="multiple_sizes" value="1" 
                                        class="rounded bg-gray-700 border-gray-600 text-accent-blue focus:ring-accent-blue focus:ring-opacity-50">
                                    <span class="ml-2 font-medium text-white">Tailles de dalles multiples</span>
                                </label>
                                <p class="mt-1 text-sm text-gray-300">Cochez cette option si vous avez différentes tailles de dalles dans le même chantier (par exemple, des dalles 500x500 et des dalles 500x1000).</p>
                            </div>
                            
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
                                    <x-input-label :value="__('Modules par Dalle')" class="text-gray-300" />
                                    <div class="flex items-center mt-1">
                                        <div class="bg-gray-800 border border-accent-blue rounded-lg px-4 py-2 flex-1">
                                            @php
                                                // Récupérer la disposition des modules depuis les données du produit
                                                $dispositionText = $produitData['disposition_modules'] ?? '2x2';
                                                
                                                // Calculer le nombre de modules à partir de la disposition
                                                $moduleCount = 4; // Valeur par défaut (2x2)
                                                
                                                if (preg_match('/^(\d+)x(\d+)$/', $dispositionText, $matches)) {
                                                    $moduleCount = (int)$matches[1] * (int)$matches[2];
                                                }
                                            @endphp
                                            <span class="text-white font-medium">{{ $moduleCount }} modules</span>
                                            <span class="text-gray-400 ml-2">(Disposition {{ $dispositionText }})</span>
                                        </div>
                                        <button type="button" id="customize_modules_btn" class="ml-2 py-2 px-3 bg-accent-blue hover:bg-blue-600 text-white rounded-lg text-sm flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Personnaliser
                                        </button>
                                    </div>
                                    <input type="hidden" id="nb_modules_par_dalle" name="nb_modules_par_dalle" value="{{ $moduleCount }}" />
                                    <input type="hidden" id="disposition_modules" name="disposition_modules" value="{{ $dispositionText }}" />
                                    <p class="text-xs text-gray-400 mt-1">Configuration issue de la définition du produit. Vous pouvez la personnaliser au besoin.</p>
                                    <x-input-error :messages="$errors->get('nb_modules_par_dalle')" class="mt-2" />
                                    
                                    <!-- Modal pour personnaliser la disposition des modules -->
                                    <div id="modules_modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70" x-data>
                                        <div class="bg-gray-900 border border-gray-700 rounded-xl p-6 w-full max-w-xl">
                                            <h3 class="text-xl font-medium text-white mb-4">Personnaliser la disposition des modules</h3>
                                            <div class="mb-4">
                                                <label class="block text-gray-300 mb-2">Disposition</label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-gray-400 mb-1">Colonnes</label>
                                                        <input type="number" id="modal_colonnes" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50" value="{{ $matches[1] ?? substr($dispositionText, 0, 1) }}" min="1" max="10">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-400 mb-1">Lignes</label>
                                                        <input type="number" id="modal_lignes" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50" value="{{ $matches[2] ?? substr($dispositionText, 2, 1) }}" min="1" max="10">
                                                    </div>
                                                </div>
                                                <p class="mt-3 font-medium text-accent-blue text-center">
                                                    <span id="modal_total_modules">4</span> modules par dalle au total
                                                </p>
                                                <div class="mt-2 p-2 bg-blue-900/20 border border-blue-500/30 rounded-lg text-sm text-gray-300">
                                                    <p>La taille de chaque module sera ajustée en fonction des dimensions de la dalle et de cette disposition.</p>
                                                </div>
                                            </div>
                                            <div class="flex justify-end space-x-3">
                                                <button type="button" id="modules_modal_cancel" class="px-4 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-800">Annuler</button>
                                                <button type="button" id="modules_modal_apply" class="px-4 py-2 bg-accent-blue text-white rounded-lg hover:bg-blue-600">Appliquer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Configurations de flight cases par type de dalle -->
                            <div id="multiple_sizes_config" class="mt-5 pt-4 border-t border-gray-700" style="display:none;">
                                <h4 class="font-medium text-lg text-white mb-3">Configuration par type de dalle</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                                    <!-- Configuration 1 - Dalles 500x500 -->
                                    <div class="bg-blue-900/20 border border-blue-500/30 p-4 rounded-xl">
                                        <h5 class="font-medium text-blue-300 mb-3">Configuration 1 (500x500)</h5>
                                        <div class="grid grid-cols-1 gap-3">
                                            <div>
                                                <x-input-label for="config1_nb_flightcases" :value="__('Nombre de Flight Cases')" class="text-gray-300" />
                                                <x-text-input id="config1_nb_flightcases" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="config1[nb_flightcases]" :value="old('config1.nb_flightcases', 1)" min="0" />
                                            </div>
                                            <div>
                                                <x-input-label for="config1_nb_dalles" :value="__('Dalles par Flight Case')" class="text-gray-300" />
                                                <x-text-input id="config1_nb_dalles" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="config1[nb_dalles]" :value="old('config1.nb_dalles', 8)" min="1" />
                                            </div>
                                            <div>
                                                <x-input-label for="config1_modules_config" :value="__('Configuration des modules')" class="text-gray-300" />
                                                <select id="config1_modules_config" name="config1[modules_config]" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white rounded-md focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                    <option value="2x2">2x2 (4 modules par dalle)</option>
                                                    <option value="1x1">1x1 (1 module par dalle)</option>
                                                    <option value="3x3">3x3 (9 modules par dalle)</option>
                                                    <option value="4x4">4x4 (16 modules par dalle)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-400">
                                            <span id="config1_total">8 flight cases × 8 dalles × 4 modules = 256 modules</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Configuration 2 - Dalles 500x1000 -->
                                    <div class="bg-purple-900/20 border border-purple-500/30 p-4 rounded-xl">
                                        <h5 class="font-medium text-purple-300 mb-3">Configuration 2 (500x1000)</h5>
                                        <div class="grid grid-cols-1 gap-3">
                                            <div>
                                                <x-input-label for="config2_nb_flightcases" :value="__('Nombre de Flight Cases')" class="text-gray-300" />
                                                <x-text-input id="config2_nb_flightcases" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="config2[nb_flightcases]" :value="old('config2.nb_flightcases', 1)" min="0" />
                                            </div>
                                            <div>
                                                <x-input-label for="config2_nb_dalles" :value="__('Dalles par Flight Case')" class="text-gray-300" />
                                                <x-text-input id="config2_nb_dalles" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="config2[nb_dalles]" :value="old('config2.nb_dalles', 6)" min="1" />
                                            </div>
                                            <div>
                                                <x-input-label for="config2_modules_config" :value="__('Configuration des modules')" class="text-gray-300" />
                                                <select id="config2_modules_config" name="config2[modules_config]" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white rounded-md focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                    <option value="2x4">2x4 (8 modules 250x250)</option>
                                                    <option value="1x2">1x2 (2 modules 500x500)</option>
                                                    <option value="2x2">2x2 (4 modules 250x500)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-400">
                                            <span id="config2_total">1 flight cases × 6 dalles × 8 modules = 48 modules</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Configuration détaillée des FlightCases (mode standard) -->
                            <div id="standard_config" class="mt-6 bg-blue-900/20 border border-blue-500/30 p-4 rounded-xl">
                                <h4 class="font-medium text-blue-300 mb-3">Configuration détaillée des FlightCases</h4>
                                
                                <!-- Types de dalles disponibles - caché par défaut -->
                                <div id="dalle_types_section" class="mb-4 bg-gray-800/50 p-3 rounded-lg" style="display: none;">
                                    <div class="flex justify-between items-center mb-2">
                                        <h5 class="text-sm font-medium text-white">Types de dalles disponibles</h5>
                                        <button type="button" id="add_dalle_type_btn" class="px-3 py-2 bg-accent-blue hover:bg-blue-600 text-white rounded-lg text-sm flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Ajouter un type de dalle
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2" id="dalle_types_container">
                                        @if(isset($dalleTypes) && count($dalleTypes) > 0)
                                            @foreach($dalleTypes as $type)
                                            <div class="bg-gray-700/50 p-2 rounded border border-gray-600">
                                                <span class="block text-sm font-medium text-white">{{ $type['nom'] }}</span>
                                                <span class="block text-xs text-gray-300">{{ $type['largeur'] }}x{{ $type['hauteur'] }}mm</span>
                                                <span class="block text-xs text-gray-400">Disposition: {{ $type['disposition'] }}</span>
                                                @if($type['largeur'] == 500 && $type['hauteur'] == 1000)
                                                <div class="mt-1 flex items-center">
                                                    <span class="inline-block px-2 py-1 text-xs font-medium bg-amber-600/30 text-amber-300 rounded">Configuration des modules variable</span>
                                                </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        @endif
                                        <!-- Les nouveaux types de dalles seront ajoutés ici -->
                                    </div>
                                </div>
                                
                                <!-- Modal pour ajouter un nouveau type de dalle -->
                                <div id="dalle_type_modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70">
                                    <div class="bg-gray-900 border border-gray-700 rounded-xl p-6 w-full max-w-xl">
                                        <h3 class="text-xl font-medium text-white mb-4">Ajouter un type de dalle</h3>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-gray-300 mb-2">Nom du type</label>
                                                <input type="text" id="modal_dalle_nom" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50" placeholder="Ex: Grande dalle 1000x1000">
                                            </div>
                                            
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-gray-300 mb-2">Largeur (mm)</label>
                                                    <input type="number" id="modal_dalle_largeur" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50" value="1000" min="100" max="5000">
                                                </div>
                                                <div>
                                                    <label class="block text-gray-300 mb-2">Hauteur (mm)</label>
                                                    <input type="number" id="modal_dalle_hauteur" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50" value="1000" min="100" max="5000">
                                                </div>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-gray-300 mb-2">Disposition des modules</label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-gray-400 mb-1">Colonnes</label>
                                                        <input type="number" id="modal_dalle_colonnes" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50" value="4" min="1" max="10">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-400 mb-1">Lignes</label>
                                                        <input type="number" id="modal_dalle_lignes" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50" value="4" min="1" max="10">
                                                    </div>
                                                </div>
                                                <p class="mt-3 font-medium text-accent-blue text-center">
                                                    <span id="modal_dalle_total_modules">16</span> modules par dalle au total
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-end space-x-3 mt-6">
                                            <button type="button" id="dalle_type_modal_cancel" class="px-4 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-800">Annuler</button>
                                            <button type="button" id="dalle_type_modal_apply" class="px-4 py-2 bg-accent-blue text-white rounded-lg hover:bg-blue-600">Ajouter</button>
                                        </div>
                                    </div>
                                </div>
                                
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
                                <a href="{{ route('chantiers.create.step2', $chantier) }}" class="btn-action btn-secondary flex items-center mr-2">
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
            
            // Éléments pour le nouveau modal de type de dalle
            const addDalleTypeBtn = document.getElementById('add_dalle_type_btn');
            const dalleTypeModal = document.getElementById('dalle_type_modal');
            const dalleTypeContainer = document.getElementById('dalle_types_container');
            const modalDalleNom = document.getElementById('modal_dalle_nom');
            const modalDalleLargeur = document.getElementById('modal_dalle_largeur');
            const modalDalleHauteur = document.getElementById('modal_dalle_hauteur');
            const modalDalleColonnes = document.getElementById('modal_dalle_colonnes');
            const modalDalleLignes = document.getElementById('modal_dalle_lignes');
            const modalDalleTotalModules = document.getElementById('modal_dalle_total_modules');
            const modalDalleCancel = document.getElementById('dalle_type_modal_cancel');
            const modalDalleApply = document.getElementById('dalle_type_modal_apply');
            
            // Nouvel élément pour le mode de tailles multiples
            const multipleSizesCheckbox = document.getElementById('multiple_sizes');
            const multipleSizesConfig = document.getElementById('multiple_sizes_config');
            const standardConfig = document.getElementById('standard_config');
            
            // Champs pour le mode flight case
            const nbFlightcases = document.getElementById('nb_flightcases');
            const nbDallesParFlightcase = document.getElementById('nb_dalles_par_flightcase');
            const nbModulesParDalle = document.getElementById('nb_modules_par_dalle');
            const dispositionModulesInput = document.getElementById('disposition_modules');
            
            // Champs pour le modal de personnalisation des modules
            const customizeModulesBtn = document.getElementById('customize_modules_btn');
            const modulesModal = document.getElementById('modules_modal');
            const modalColonnes = document.getElementById('modal_colonnes');
            const modalLignes = document.getElementById('modal_lignes');
            const modalTotalModules = document.getElementById('modal_total_modules');
            const modalCancelBtn = document.getElementById('modules_modal_cancel');
            const modalApplyBtn = document.getElementById('modules_modal_apply');
            
            // Champs pour la configuration 1 (500x500)
            const config1NbFlightcases = document.getElementById('config1_nb_flightcases');
            const config1NbDalles = document.getElementById('config1_nb_dalles');
            const config1ModulesConfig = document.getElementById('config1_modules_config');
            const config1Total = document.getElementById('config1_total');
            
            // Champs pour la configuration 2 (500x1000)
            const config2NbFlightcases = document.getElementById('config2_nb_flightcases');
            const config2NbDalles = document.getElementById('config2_nb_dalles');
            const config2ModulesConfig = document.getElementById('config2_modules_config');
            const config2Total = document.getElementById('config2_total');
            
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
            
            // Configuration du modal de personnalisation des modules
            customizeModulesBtn.addEventListener('click', function() {
                // Extraire les valeurs actuelles de la disposition
                let currentDisposition = dispositionModulesInput.value;
                let currentColumns = 2;
                let currentRows = 2;
                
                if (currentDisposition.match(/^(\d+)x(\d+)$/)) {
                    const matches = currentDisposition.match(/^(\d+)x(\d+)$/);
                    currentColumns = parseInt(matches[1]);
                    currentRows = parseInt(matches[2]);
                }
                
                // Initialiser le modal avec les valeurs actuelles
                modalColonnes.value = currentColumns;
                modalLignes.value = currentRows;
                modalTotalModules.textContent = currentColumns * currentRows;
                
                // Afficher le modal
                modulesModal.classList.remove('hidden');
            });
            
            // Mise à jour du total lors de la modification des valeurs dans le modal
            function updateModalTotal() {
                const columns = parseInt(modalColonnes.value) || 1;
                const rows = parseInt(modalLignes.value) || 1;
                modalTotalModules.textContent = columns * rows;
            }
            
            // Ajouter les événements pour le modal
            modalColonnes.addEventListener('input', updateModalTotal);
            modalLignes.addEventListener('input', updateModalTotal);
            
            // Gestion du bouton Annuler
            modalCancelBtn.addEventListener('click', function() {
                modulesModal.classList.add('hidden');
            });
            
            // Gestion du bouton Appliquer
            modalApplyBtn.addEventListener('click', function() {
                const columns = parseInt(modalColonnes.value) || 1;
                const rows = parseInt(modalLignes.value) || 1;
                const totalModules = columns * rows;
                
                // Mettre à jour les champs cachés
                dispositionModulesInput.value = `${columns}x${rows}`;
                nbModulesParDalle.value = totalModules;
                
                // Mettre à jour l'affichage
                const moduleCountElement = document.querySelector('#customize_modules_btn').previousElementSibling.querySelector('.text-white');
                const dispositionElement = document.querySelector('#customize_modules_btn').previousElementSibling.querySelector('.text-gray-400');
                
                if (moduleCountElement) {
                    moduleCountElement.textContent = `${totalModules} modules`;
                }
                
                if (dispositionElement) {
                    dispositionElement.textContent = `(Disposition ${columns}x${rows})`;
                }
                
                // Mettre à jour la variable calculatedModulesPerDalle pour les calculs
                calculatedModulesPerDalle = totalModules;
                
                // Recalculer le total
                updateTotalModules();
                
                // Fermer le modal
                modulesModal.classList.add('hidden');
            });
            
            // Référence à la section des types de dalles
            const dalleTypesSection = document.getElementById('dalle_types_section');
            
            // Gestionnaire d'événements pour le mode de tailles multiples
            multipleSizesCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Afficher la configuration multiples tailles
                    multipleSizesConfig.style.display = 'block';
                    standardConfig.style.display = 'none';
                    nbFlightcases.disabled = true;
                    nbDallesParFlightcase.disabled = true;
                    
                    // Afficher la section des types de dalles
                    if (dalleTypesSection) {
                        dalleTypesSection.style.display = 'block';
                    }
                    
                    updateMultipleSizesTotal();
                } else {
                    // Masquer la configuration multiples tailles
                    multipleSizesConfig.style.display = 'none';
                    standardConfig.style.display = 'block';
                    nbFlightcases.disabled = false;
                    nbDallesParFlightcase.disabled = false;
                    
                    // Masquer la section des types de dalles
                    if (dalleTypesSection) {
                        dalleTypesSection.style.display = 'none';
                    }
                    
                    generateFlightCaseControls();
                    updateTotalModules();
                }
            });
            
            // Fonction pour calculer et mettre à jour le total pour le mode tailles multiples
            function updateMultipleSizesTotal() {
                // Calcul du total pour la configuration 1 (500x500)
                const config1Flightcases = parseInt(config1NbFlightcases.value) || 0;
                const config1Dalles = parseInt(config1NbDalles.value) || 0;
                let config1ModulesPerDalle = 4; // Par défaut pour 2x2
                
                if (config1ModulesConfig.value === '1x1') config1ModulesPerDalle = 1;
                else if (config1ModulesConfig.value === '2x2') config1ModulesPerDalle = 4;
                else if (config1ModulesConfig.value === '3x3') config1ModulesPerDalle = 9;
                else if (config1ModulesConfig.value === '4x4') config1ModulesPerDalle = 16;
                
                const config1TotalModules = config1Flightcases * config1Dalles * config1ModulesPerDalle;
                config1Total.textContent = `${config1Flightcases} flight cases × ${config1Dalles} dalles × ${config1ModulesPerDalle} modules = ${config1TotalModules} modules`;
                
                // Calcul du total pour la configuration 2 (500x1000)
                const config2Flightcases = parseInt(config2NbFlightcases.value) || 0;
                const config2Dalles = parseInt(config2NbDalles.value) || 0;
                let config2ModulesPerDalle = 8; // Par défaut pour 2x4
                
                if (config2ModulesConfig.value === '1x2') config2ModulesPerDalle = 2;
                else if (config2ModulesConfig.value === '2x2') config2ModulesPerDalle = 4;
                else if (config2ModulesConfig.value === '2x4') config2ModulesPerDalle = 8;
                
                const config2TotalModules = config2Flightcases * config2Dalles * config2ModulesPerDalle;
                config2Total.textContent = `${config2Flightcases} flight cases × ${config2Dalles} dalles × ${config2ModulesPerDalle} modules = ${config2TotalModules} modules`;
                
                // Afficher le total global
                const grandTotal = config1TotalModules + config2TotalModules;
                totalModulesFc.textContent = grandTotal;
                totalModulesMessage.textContent = grandTotal + ' modules seront créés au total';
            }
            
            // Événements pour la mise à jour des totaux du mode tailles multiples
            config1NbFlightcases.addEventListener('input', updateMultipleSizesTotal);
            config1NbDalles.addEventListener('input', updateMultipleSizesTotal);
            config1ModulesConfig.addEventListener('change', updateMultipleSizesTotal);
            config2NbFlightcases.addEventListener('input', updateMultipleSizesTotal);
            config2NbDalles.addEventListener('input', updateMultipleSizesTotal);
            config2ModulesConfig.addEventListener('change', updateMultipleSizesTotal);
            
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
                    
                    // Contrôle du nombre de dalles et types de dalles
                    const fcDallesControl = document.createElement('div');
                    fcDallesControl.className = 'col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4';
                    fcDallesControl.id = `fc_dalles_control_${f}`;
                    
                    // HTML pour le nombre de dalles
                    const nbDallesHTML = `
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
                    
                    // HTML pour le sélecteur de type de dalle
                    let typeDalleOptions = '';
                    
                    // Option pour les dimensions personnalisées du produit
                    @if(isset($produitData['largeur_dalle']) && isset($produitData['hauteur_dalle']) && $produitData['largeur_dalle'] > 0 && $produitData['hauteur_dalle'] > 0)
                        typeDalleOptions = `<option value="custom" selected>Dimensions du produit ({{ $produitData['largeur_dalle'] }}x{{ $produitData['hauteur_dalle'] }}mm)</option>`;
                    @elseif(isset($produit) && isset($produit->largeur_dalle) && isset($produit->hauteur_dalle) && $produit->largeur_dalle > 0 && $produit->hauteur_dalle > 0)
                        typeDalleOptions = `<option value="custom" selected>Dimensions du produit ({{ $produit->largeur_dalle }}x{{ $produit->hauteur_dalle }}mm)</option>`;
                    @endif
                    
                    // Ajouter les types prédéfinis
                    @if(isset($dalleTypes))
                        @foreach($dalleTypes as $type)
                            typeDalleOptions += `<option value="{{ $type['id'] }}">{{ $type['nom'] }} ({{ $type['largeur'] }}x{{ $type['hauteur'] }}mm)</option>`;
                        @endforeach
                    @endif
                    
                    // Ajouter les types personnalisés s'ils existent
                    const customDalleElements = document.querySelectorAll('#dalle_types_container [data-dalle-type-id]');
                    customDalleElements.forEach(element => {
                        const typeId = element.getAttribute('data-dalle-type-id');
                        const nomType = element.querySelector('.text-white').innerText;
                        const largeur = element.getAttribute('data-dalle-largeur');
                        const hauteur = element.getAttribute('data-dalle-hauteur');
                        
                        typeDalleOptions += `<option value="${typeId}">${nomType} (${largeur}x${hauteur}mm)</option>`;
                    });
                    
                    const typeDalleHTML = `
                        <div class="w-full">
                            <label class="block text-sm font-medium text-blue-300 mb-1">Type de dalle</label>
                            <select id="fc_dalle_type_${f}" name="flights[${f}][dalle_type_id]" 
                                class="fc-dalle-type w-full rounded-lg bg-gray-700 border-gray-600 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50" onchange="checkDalleType(${f})">
                                ${typeDalleOptions}
                            </select>
                        </div>
                    `;
                    
                    // HTML pour le sélecteur de modules par dalle (pour les dalles 500x1000)
                    const modulesConfigHTML = `
                        <div id="modules_config_container_${f}" class="w-full mt-2" style="display: none;">
                            <label class="block text-sm font-medium text-amber-300 mb-1">Configuration des modules</label>
                            <select id="fc_modules_config_${f}" name="flights[${f}][modules_config]" 
                                class="fc-modules-config w-full rounded-lg bg-gray-700 border-gray-600 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50" onchange="updateModulesPerDalle(${f})">
                                <option value="2x4">2x4 (8 modules 250x250)</option>
                                <option value="1x2">1x2 (2 modules 500x500)</option>
                                <option value="2x2">2x2 (4 modules 250x500)</option>
                            </select>
                        </div>
                    `;
                    
                    // Assembler le contenu complet
                    fcDallesControl.innerHTML = nbDallesHTML + typeDalleHTML + modulesConfigHTML;
                    
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
                    
                    // Toujours afficher les contrôles, indépendamment de l'état de la case à cocher
                    dallesControl.style.display = 'grid';
                    
                    checkbox.addEventListener('change', function() {
                        // Mettre à jour le total lorsque la case est cochée/décochée
                        updateTotalModules();
                    });
                    
                    if (nbDallesInput) {
                        nbDallesInput.addEventListener('input', function() {
                            // Vérifier si le nombre est inférieur au maximum
                            const maxDalles = parseInt(nbDallesParFlightcaseValue);
                            const currentDalles = parseInt(this.value) || 0;
                            
                            // Si le nombre de dalles est inférieur au maximum, activer l'option "flightcase partiel"
                            if (currentDalles < maxDalles) {
                                checkbox.checked = true;
                            } 
                            // Si le nombre de dalles est égal au maximum, désactiver l'option "flightcase partiel"
                            else if (currentDalles === maxDalles) {
                                checkbox.checked = false;
                            }
                            
                            // Mettre à jour le total
                            updateTotalModules();
                        });
                    }
                }
            }
            
            // Fonction pour calculer et mettre à jour le total des modules (mode standard)
            function updateTotalModules() {
                let totalModules = 0;
                
                if (modeFlightcaseRadio.checked) {
                    // Si mode tailles multiples activé, utiliser ce calcul
                    if (multipleSizesCheckbox.checked) {
                        updateMultipleSizesTotal();
                        return;
                    }
                    
                    // Sinon mode flightcase standard
                    const nbFlightcasesValue = parseInt(nbFlightcases.value) || 1;
                    const nbDallesParFlightcaseValue = parseInt(nbDallesParFlightcase.value) || 8;
                    
                    // Parcourir chaque FlightCase et compter ses dalles
                    for (let f = 1; f <= nbFlightcasesValue; f++) {
                        const fcPartielCheckbox = document.getElementById(`fc_partiel_${f}`);
                        
                        // Déterminer le nombre de modules par dalle pour ce flightcase
                        let modulesPerDalle = calculatedModulesPerDalle;
                        const dalleTypeSelect = document.getElementById(`fc_dalle_type_${f}`);
                        const modulesConfigSelect = document.getElementById(`fc_modules_config_${f}`);
                        
                        // Pour le type de dalle personnalisé, utiliser la valeur calculée
                        if (dalleTypeSelect && dalleTypeSelect.value === "custom") {
                            // Utiliser la valeur calculée du produit ou celle du modal
                            modulesPerDalle = calculatedModulesPerDalle;
                        }
                        // Si c'est un type de dalle 500x1000 et qu'il a une config modules visible
                        else if (dalleTypeSelect && dalleTypeSelect.value == "1" && 
                            document.getElementById(`modules_config_container_${f}`) && 
                            document.getElementById(`modules_config_container_${f}`).style.display !== 'none') {
                            
                            const configValue = modulesConfigSelect.value;
                            if (configValue === '2x4') modulesPerDalle = 8;
                            else if (configValue === '1x2') modulesPerDalle = 2;
                            else if (configValue === '2x2') modulesPerDalle = 4;
                        }
                        
                        // Toujours utiliser la valeur du champ de saisie pour le nombre de dalles
                        const nbDallesInput = document.getElementById(`fc_nb_dalles_${f}`);
                        const nbDalles = nbDallesInput ? (parseInt(nbDallesInput.value) || 0) : nbDallesParFlightcaseValue;
                        totalModules += nbDalles * modulesPerDalle;
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
            
            // Fermer les modals si l'utilisateur clique en dehors
            window.addEventListener('click', function(event) {
                if (event.target === modulesModal) {
                    modulesModal.classList.add('hidden');
                }
                if (event.target === dalleTypeModal) {
                    dalleTypeModal.classList.add('hidden');
                }
            });
            
            // Mise à jour du total des modules pour le modal de type de dalle
            function updateDalleTypeModalTotal() {
                const colonnes = parseInt(modalDalleColonnes.value) || 1;
                const lignes = parseInt(modalDalleLignes.value) || 1;
                modalDalleTotalModules.textContent = colonnes * lignes;
            }
            
            // Gestion du modal de type de dalle
            addDalleTypeBtn.addEventListener('click', function() {
                // Initialiser le modal avec des valeurs par défaut
                modalDalleNom.value = '';
                modalDalleLargeur.value = '1000';
                modalDalleHauteur.value = '1000';
                modalDalleColonnes.value = '4';
                modalDalleLignes.value = '4';
                updateDalleTypeModalTotal();
                
                // Afficher le modal
                dalleTypeModal.classList.remove('hidden');
            });
            
            // Ajouter événements pour le modal de dalle
            modalDalleColonnes.addEventListener('input', updateDalleTypeModalTotal);
            modalDalleLignes.addEventListener('input', updateDalleTypeModalTotal);
            
            // Gestion du bouton Annuler
            modalDalleCancel.addEventListener('click', function() {
                dalleTypeModal.classList.add('hidden');
            });
            
            // Gestion du bouton Ajouter
            modalDalleApply.addEventListener('click', function() {
                // Récupérer les valeurs
                const nomType = modalDalleNom.value || `Dalle ${modalDalleLargeur.value}x${modalDalleHauteur.value}`;
                const largeur = parseInt(modalDalleLargeur.value) || 1000;
                const hauteur = parseInt(modalDalleHauteur.value) || 1000;
                const colonnes = parseInt(modalDalleColonnes.value) || 4;
                const lignes = parseInt(modalDalleLignes.value) || 4;
                const disposition = `${colonnes}x${lignes}`;
                const totalModules = colonnes * lignes;
                
                // Générer un ID unique pour ce type
                const typeId = 'custom_' + Date.now();
                
                // Créer le nouvel élément HTML
                const newTypeElement = document.createElement('div');
                newTypeElement.className = 'bg-green-900/20 p-2 rounded border border-green-600';
                newTypeElement.setAttribute('data-dalle-type-id', typeId);
                newTypeElement.setAttribute('data-dalle-largeur', largeur);
                newTypeElement.setAttribute('data-dalle-hauteur', hauteur);
                newTypeElement.setAttribute('data-dalle-disposition', disposition);
                newTypeElement.setAttribute('data-dalle-colonnes', colonnes);
                newTypeElement.setAttribute('data-dalle-lignes', lignes);
                
                // Fournir le contenu HTML
                newTypeElement.innerHTML = `
                    <span class="block text-sm font-medium text-white">${nomType} <span class="ml-1 text-xs font-normal text-green-300">(Personnalisé)</span></span>
                    <span class="block text-xs text-gray-300">${largeur}x${hauteur}mm</span>
                    <span class="block text-xs text-gray-400">Disposition: ${disposition} (${totalModules} modules)</span>
                    <input type="hidden" name="custom_dalle_types[${typeId}][nom]" value="${nomType}">
                    <input type="hidden" name="custom_dalle_types[${typeId}][largeur]" value="${largeur}">
                    <input type="hidden" name="custom_dalle_types[${typeId}][hauteur]" value="${hauteur}">
                    <input type="hidden" name="custom_dalle_types[${typeId}][disposition]" value="${disposition}">
                    <input type="hidden" name="custom_dalle_types[${typeId}][colonnes]" value="${colonnes}">
                    <input type="hidden" name="custom_dalle_types[${typeId}][lignes]" value="${lignes}">
                `;
                
                // Ajouter au conteneur
                dalleTypeContainer.appendChild(newTypeElement);
                
                // Actualiser les sélecteurs pour les FlightCases
                generateFlightCaseControls();
                
                // Fermer le modal
                dalleTypeModal.classList.add('hidden');
            });
            
            // Fonctions pour gérer les configurations spécifiques des modules
            window.checkDalleType = function(flightcaseIndex) {
                const dalleTypeSelect = document.getElementById(`fc_dalle_type_${flightcaseIndex}`);
                const modulesConfigContainer = document.getElementById(`modules_config_container_${flightcaseIndex}`);
                
                if (dalleTypeSelect && modulesConfigContainer) {
                    // Si c'est une dalle 500x1000 (type 1), afficher les options de configuration de modules
                    if (dalleTypeSelect.value == "1") {
                        modulesConfigContainer.style.display = "block";
                    } else if (dalleTypeSelect.value == "custom") {
                        // Pour les dalles personnalisées, on peut aussi personnaliser la configuration des modules
                        // Créer dynamiquement un conteneur si besoin
                        let customModulesContainer = document.getElementById(`custom_modules_container_${flightcaseIndex}`);
                        
                        if (!customModulesContainer) {
                            customModulesContainer = document.createElement('div');
                            customModulesContainer.id = `custom_modules_container_${flightcaseIndex}`;
                            customModulesContainer.className = 'w-full mt-2';
                            
                            // Bouton pour personnaliser la configuration des modules
                            const customizeBtn = document.createElement('button');
                            customizeBtn.type = 'button';
                            customizeBtn.className = 'px-3 py-2 bg-accent-blue hover:bg-blue-600 text-white rounded-lg text-sm flex items-center';
                            customizeBtn.innerHTML = `
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Personnaliser les modules
                            `;
                            
                            // Ajouter un événement au bouton pour ouvrir le modal de personnalisation
                            customizeBtn.addEventListener('click', function() {
                                // Réutiliser le modal existant
                                document.getElementById('customize_modules_btn').click();
                            });
                            
                            customModulesContainer.appendChild(customizeBtn);
                            
                            // Insérer le conteneur après le sélecteur de type de dalle
                            dalleTypeSelect.parentNode.parentNode.appendChild(customModulesContainer);
                        }
                        
                        customModulesContainer.style.display = "block";
                        modulesConfigContainer.style.display = "none";
                    } else {
                        // Cacher à la fois le conteneur standard et personnalisé
                        modulesConfigContainer.style.display = "none";
                        
                        const customModulesContainer = document.getElementById(`custom_modules_container_${flightcaseIndex}`);
                        if (customModulesContainer) {
                            customModulesContainer.style.display = "none";
                        }
                    }
                    
                    // Mettre à jour le total après changement
                    updateTotalModules();
                }
            };
            
            window.updateModulesPerDalle = function(flightcaseIndex) {
                // Cette fonction est appelée quand l'utilisateur change la configuration des modules
                // pour mettre à jour le calcul du nombre total
                updateTotalModules();
            };
        });
    </script>
</x-app-layout>