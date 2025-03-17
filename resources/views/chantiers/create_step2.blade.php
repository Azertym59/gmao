<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Création de chantier - Étape 2/5 : Chantier') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Indicateur de progression -->
            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="ml-2 font-medium text-green-500">Client</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-green-500 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-accent-blue text-white font-bold">2</div>
                        <span class="ml-2 font-medium text-white">Chantier</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-600 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-gray-400 font-bold">3</div>
                        <span class="ml-2 font-medium text-gray-400">Produit</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-600 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-gray-400 font-bold">4</div>
                        <span class="ml-2 font-medium text-gray-400">Interventions</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-600 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-gray-400 font-bold">5</div>
                        <span class="ml-2 font-medium text-gray-400">Rapports</span>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="mb-6 bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                        <h3 class="font-medium text-blue-300 mb-2">Client sélectionné</h3>
                        <p class="text-gray-300"><span class="font-medium">Nom:</span> {{ $client->nom_complet }}</p>
                        @if($client->societe)
                            <p class="text-gray-300"><span class="font-medium">Société:</span> {{ $client->societe }}</p>
                        @endif
                        @if($client->email)
                            <p class="text-gray-300"><span class="font-medium">Email:</span> {{ $client->email }}</p>
                        @endif
                    </div>

                    <h3 class="text-lg font-semibold text-white mb-4">Informations du chantier</h3>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-500/30 border border-red-500/50 text-red-400 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('chantiers.store.step2') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <x-input-label for="nom" :value="__('Nom du chantier')" class="text-gray-300" />
                                <x-text-input id="nom" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="nom" :value="old('nom', $chantierData['nom'] ?? '')" required />
                                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                            </div>

                            <!-- Référence -->
                            <div>
                                <x-input-label for="reference" :value="__('Référence')" class="text-gray-300" />
                                <x-text-input id="reference" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="reference" :value="old('reference', $chantierData['reference'] ?? '')" />
                                <x-input-error :messages="$errors->get('reference')" class="mt-2" />
                            </div>

                            <!-- Date de réception -->
                            <div>
                                <x-input-label for="date_reception" :value="__('Date de réception')" class="text-gray-300" />
                                <x-text-input id="date_reception" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="date" name="date_reception" :value="old('date_reception', $chantierData['date_reception'] ?? date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date_reception')" class="mt-2" />
                            </div>

                            <!-- Date butoir -->
                            <div>
                                <x-input-label for="date_butoir" :value="__('Date butoir')" class="text-gray-300" />
                                <x-text-input id="date_butoir" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="date" name="date_butoir" :value="old('date_butoir', $chantierData['date_butoir'] ?? date('Y-m-d', strtotime('+1 week')))" required />
                                <x-input-error :messages="$errors->get('date_butoir')" class="mt-2" />
                            </div>

                            <!-- État -->
                            <div>
                                <x-input-label for="etat" :value="__('État')" class="text-gray-300" />
                                <select id="etat" name="etat" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="non_commence" {{ old('etat', $chantierData['etat'] ?? '') == 'non_commence' ? 'selected' : '' }}>Non commencé</option>
                                    <option value="en_cours" {{ old('etat', $chantierData['etat'] ?? '') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="termine" {{ old('etat', $chantierData['etat'] ?? '') == 'termine' ? 'selected' : '' }}>Terminé</option>
                                </select>
                                <x-input-error :messages="$errors->get('etat')" class="mt-2" />
                            </div>
                            
                            <!-- Mode d'emballage -->
                            <div>
                                <x-input-label for="mode_emballage" :value="__('Mode d\'emballage')" class="text-gray-300" />
                                <select id="mode_emballage" name="mode_emballage" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="flightcase" {{ old('mode_emballage', $chantierData['mode_emballage'] ?? '') == 'flightcase' ? 'selected' : '' }}>Flight case</option>
                                    <option value="carton" {{ old('mode_emballage', $chantierData['mode_emballage'] ?? '') == 'carton' ? 'selected' : '' }}>Carton</option>
                                    <option value="palette" {{ old('mode_emballage', $chantierData['mode_emballage'] ?? '') == 'palette' ? 'selected' : '' }}>Palette</option>
                                    <option value="autre" {{ old('mode_emballage', $chantierData['mode_emballage'] ?? '') == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                <x-input-error :messages="$errors->get('mode_emballage')" class="mt-2" />
                            </div>
                            
                            <!-- Détail du mode d'emballage (si autre) -->
                            <div id="mode_emballage_detail_container" class="{{ old('mode_emballage', $chantierData['mode_emballage'] ?? '') == 'autre' ? '' : 'hidden' }}">
                                <x-input-label for="mode_emballage_detail" :value="__('Précisez le mode d\'emballage')" class="text-gray-300" />
                                <x-text-input id="mode_emballage_detail" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="mode_emballage_detail" :value="old('mode_emballage_detail', $chantierData['mode_emballage_detail'] ?? '')" />
                                <x-input-error :messages="$errors->get('mode_emballage_detail')" class="mt-2" />
                            </div>

                            <!-- Configuration des modules -->
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
                            
                            <!-- Disposition des modules -->
                            <div>
                                <x-input-label for="disposition" :value="__('Disposition des modules')" class="text-gray-300" />
                                <select id="disposition" name="disposition" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="grid" {{ old('disposition') == 'grid' ? 'selected' : '' }}>Grille standard</option>
                                    <option value="zigzag" {{ old('disposition') == 'zigzag' ? 'selected' : '' }}>Zigzag</option>
                                    <option value="custom" {{ old('disposition') == 'custom' ? 'selected' : '' }}>Personnalisée</option>
                                </select>
                                <x-input-error :messages="$errors->get('disposition')" class="mt-2" />
                            </div>
                            
                            <!-- Disposition personnalisée des modules (cachée par défaut) -->
                            <div id="disposition_custom_container" class="hidden md:col-span-2 p-4 border border-gray-500 rounded-xl bg-gray-800/50 mb-4">
                                <h4 class="font-medium text-white mb-2">Configuration personnalisée</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <x-input-label for="disposition_description" :value="__('Description de la disposition')" class="text-gray-300" />
                                        <textarea id="disposition_description" name="disposition_description" rows="3" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">{{ old('disposition_description') }}</textarea>
                                        <p class="text-xs text-gray-400 mt-1">Décrivez comment les modules sont agencés (sera utilisé pour la documentation)</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Electronique -->
                            <div class="md:col-span-2 p-4 border border-gray-700 rounded-xl bg-gray-800/20 mb-4">
                                <h4 class="font-medium text-white mb-2">Configuration électronique</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                    
                                    <div>
                                        <x-input-label for="carte_reception" :value="__('Carte de Réception')" class="text-gray-300" />
                                        <x-text-input id="carte_reception" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="carte_reception" :value="old('carte_reception')" placeholder="Modèle de carte de réception" />
                                        <x-input-error :messages="$errors->get('carte_reception')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Datasheet LED -->
                            <div class="md:col-span-2 p-4 border border-gray-700 rounded-xl bg-gray-800/20">
                                <h4 class="font-medium text-white mb-2">Configuration des LEDs</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <x-input-label for="led_type" :value="__('Type de LED')" class="text-gray-300" />
                                        <select id="led_type" name="led_type" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="SMD" {{ old('led_type') == 'SMD' ? 'selected' : '' }}>SMD</option>
                                            <option value="DIP" {{ old('led_type') == 'DIP' ? 'selected' : '' }}>DIP</option>
                                            <option value="COB" {{ old('led_type') == 'COB' ? 'selected' : '' }}>COB</option>
                                            <option value="GOB" {{ old('led_type') == 'GOB' ? 'selected' : '' }}>GOB</option>
                                            <option value="HOB" {{ old('led_type') == 'HOB' ? 'selected' : '' }}>HOB</option>
                                            <option value="MiniLED" {{ old('led_type') == 'MiniLED' ? 'selected' : '' }}>MiniLED</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('led_type')" class="mt-2" />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="led_size" :value="__('Taille de la LED')" class="text-gray-300" />
                                        <select id="led_size" name="led_size" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="1010" {{ old('led_size') == '1010' ? 'selected' : '' }}>1010</option>
                                            <option value="1515" {{ old('led_size') == '1515' ? 'selected' : '' }}>1515</option>
                                            <option value="2020" {{ old('led_size') == '2020' ? 'selected' : '' }}>2020</option>
                                            <option value="2121" {{ old('led_size') == '2121' ? 'selected' : '' }}>2121</option>
                                            <option value="2727" {{ old('led_size') == '2727' ? 'selected' : '' }}>2727</option>
                                            <option value="3030" {{ old('led_size') == '3030' ? 'selected' : '' }}>3030</option>
                                            <option value="3535" {{ old('led_size') == '3535' ? 'selected' : '' }}>3535</option>
                                            <option value="5050" {{ old('led_size') == '5050' ? 'selected' : '' }}>5050</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('led_size')" class="mt-2" />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="led_pads" :value="__('Nombre de pads')" class="text-gray-300" />
                                        <select id="led_pads" name="led_pads" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="2" {{ old('led_pads') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="4" {{ old('led_pads') == '4' ? 'selected' : '' }}>4</option>
                                            <option value="6" {{ old('led_pads') == '6' ? 'selected' : '' }}>6</option>
                                            <option value="8" {{ old('led_pads') == '8' ? 'selected' : '' }}>8</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('led_pads')" class="mt-2" />
                                    </div>
                                </div>
                                
                                <!-- Configuration des pads -->
                                <div id="pads_container" class="mb-4">
                                    <h5 class="text-gray-300 font-medium mb-2">Configuration des pads</h5>
                                
                                    <!-- Configuration pour 2 pads -->
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pad-configs" id="pad-config-2">
                                        <!-- Pad configs for 2 pads -->
                                        <div>
                                            <x-input-label for="pad_1" :value="__('Pad 1')" class="text-gray-300" />
                                            <select id="pad_1" name="pad_1" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R" {{ old('pad_1') == 'R' ? 'selected' : '' }}>Rouge (R)</option>
                                                <option value="G" {{ old('pad_1') == 'G' ? 'selected' : '' }}>Vert (G)</option>
                                                <option value="B" {{ old('pad_1') == 'B' ? 'selected' : '' }}>Bleu (B)</option>
                                                <option value="+" {{ old('pad_1') == '+' ? 'selected' : '' }}>Commun (+)</option>
                                                <option value="-" {{ old('pad_1') == '-' ? 'selected' : '' }}>Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_2" :value="__('Pad 2')" class="text-gray-300" />
                                            <select id="pad_2" name="pad_2" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R" {{ old('pad_2') == 'R' ? 'selected' : '' }}>Rouge (R)</option>
                                                <option value="G" {{ old('pad_2') == 'G' ? 'selected' : '' }}>Vert (G)</option>
                                                <option value="B" {{ old('pad_2') == 'B' ? 'selected' : '' }}>Bleu (B)</option>
                                                <option value="+" {{ old('pad_2') == '+' ? 'selected' : '' }}>Commun (+)</option>
                                                <option value="-" {{ old('pad_2') == '-' ? 'selected' : '' }}>Commun (-)</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Configuration pour 4 pads -->
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pad-configs hidden" id="pad-config-4">
                                        <!-- Pad configs for 4 pads -->
                                        <div>
                                            <x-input-label for="pad_1_4" :value="__('Pad 1')" class="text-gray-300" />
                                            <select id="pad_1_4" name="pad_1_4" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R" {{ old('pad_1_4') == 'R' ? 'selected' : '' }}>Rouge (R)</option>
                                                <option value="G" {{ old('pad_1_4') == 'G' ? 'selected' : '' }}>Vert (G)</option>
                                                <option value="B" {{ old('pad_1_4') == 'B' ? 'selected' : '' }}>Bleu (B)</option>
                                                <option value="+" {{ old('pad_1_4') == '+' ? 'selected' : '' }}>Commun (+)</option>
                                                <option value="-" {{ old('pad_1_4') == '-' ? 'selected' : '' }}>Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_2_4" :value="__('Pad 2')" class="text-gray-300" />
                                            <select id="pad_2_4" name="pad_2_4" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R" {{ old('pad_2_4') == 'R' ? 'selected' : '' }}>Rouge (R)</option>
                                                <option value="G" {{ old('pad_2_4') == 'G' ? 'selected' : '' }}>Vert (G)</option>
                                                <option value="B" {{ old('pad_2_4') == 'B' ? 'selected' : '' }}>Bleu (B)</option>
                                                <option value="+" {{ old('pad_2_4') == '+' ? 'selected' : '' }}>Commun (+)</option>
                                                <option value="-" {{ old('pad_2_4') == '-' ? 'selected' : '' }}>Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_3_4" :value="__('Pad 3')" class="text-gray-300" />
                                            <select id="pad_3_4" name="pad_3_4" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R" {{ old('pad_3_4') == 'R' ? 'selected' : '' }}>Rouge (R)</option>
                                                <option value="G" {{ old('pad_3_4') == 'G' ? 'selected' : '' }}>Vert (G)</option>
                                                <option value="B" {{ old('pad_3_4') == 'B' ? 'selected' : '' }}>Bleu (B)</option>
                                                <option value="+" {{ old('pad_3_4') == '+' ? 'selected' : '' }}>Commun (+)</option>
                                                <option value="-" {{ old('pad_3_4') == '-' ? 'selected' : '' }}>Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_4_4" :value="__('Pad 4')" class="text-gray-300" />
                                            <select id="pad_4_4" name="pad_4_4" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R" {{ old('pad_4_4') == 'R' ? 'selected' : '' }}>Rouge (R)</option>
                                                <option value="G" {{ old('pad_4_4') == 'G' ? 'selected' : '' }}>Vert (G)</option>
                                                <option value="B" {{ old('pad_4_4') == 'B' ? 'selected' : '' }}>Bleu (B)</option>
                                                <option value="+" {{ old('pad_4_4') == '+' ? 'selected' : '' }}>Commun (+)</option>
                                                <option value="-" {{ old('pad_4_4') == '-' ? 'selected' : '' }}>Commun (-)</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Similaire pour 6 et 8 pads -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 pad-configs hidden" id="pad-config-6">
                                        <!-- 6 pads config -->
                                        <div>
                                            <x-input-label for="pad_1_6" :value="__('Pad 1')" class="text-gray-300" />
                                            <select id="pad_1_6" name="pad_1_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+">Commun (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <!-- Répéter pour les pads 2-6 -->
                                        <div>
                                            <x-input-label for="pad_2_6" :value="__('Pad 2')" class="text-gray-300" />
                                            <select id="pad_2_6" name="pad_2_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+">Commun (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_3_6" :value="__('Pad 3')" class="text-gray-300" />
                                            <select id="pad_3_6" name="pad_3_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+">Commun (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_4_6" :value="__('Pad 4')" class="text-gray-300" />
                                            <select id="pad_4_6" name="pad_4_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+">Commun (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_5_6" :value="__('Pad 5')" class="text-gray-300" />
                                            <select id="pad_5_6" name="pad_5_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+">Commun (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_6_6" :value="__('Pad 6')" class="text-gray-300" />
                                            <select id="pad_6_6" name="pad_6_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+">Commun (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-8 gap-4 pad-configs hidden" id="pad-config-8">
                                        <!-- 8 pads config -->
                                        <div>
                                            <x-input-label for="pad_1_8" :value="__('Pad 1')" class="text-gray-300" />
                                            <select id="pad_1_8" name="pad_1_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+1">Commun 1 (+)</option>
                                                <option value="+2">Commun 2 (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <!-- Répéter pour les pads 2-8 -->
                                        <div>
                                            <x-input-label for="pad_2_8" :value="__('Pad 2')" class="text-gray-300" />
                                            <select id="pad_2_8" name="pad_2_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+1">Commun 1 (+)</option>
                                                <option value="+2">Commun 2 (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <!-- Continuer pour les autres pads -->
                                        <div>
                                            <x-input-label for="pad_3_8" :value="__('Pad 3')" class="text-gray-300" />
                                            <select id="pad_3_8" name="pad_3_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+1">Commun 1 (+)</option>
                                                <option value="+2">Commun 2 (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_4_8" :value="__('Pad 4')" class="text-gray-300" />
                                            <select id="pad_4_8" name="pad_4_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+1">Commun 1 (+)</option>
                                                <option value="+2">Commun 2 (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_5_8" :value="__('Pad 5')" class="text-gray-300" />
                                            <select id="pad_5_8" name="pad_5_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+1">Commun 1 (+)</option>
                                                <option value="+2">Commun 2 (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_6_8" :value="__('Pad 6')" class="text-gray-300" />
                                            <select id="pad_6_8" name="pad_6_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+1">Commun 1 (+)</option>
                                                <option value="+2">Commun 2 (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_7_8" :value="__('Pad 7')" class="text-gray-300" />
                                            <select id="pad_7_8" name="pad_7_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+1">Commun 1 (+)</option>
                                                <option value="+2">Commun 2 (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="pad_8_8" :value="__('Pad 8')" class="text-gray-300" />
                                            <select id="pad_8_8" name="pad_8_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="R1">Rouge 1 (R1)</option>
                                                <option value="G1">Vert 1 (G1)</option>
                                                <option value="B1">Bleu 1 (B1)</option>
                                                <option value="R2">Rouge 2 (R2)</option>
                                                <option value="G2">Vert 2 (G2)</option>
                                                <option value="B2">Bleu 2 (B2)</option>
                                                <option value="+1">Commun 1 (+)</option>
                                                <option value="+2">Commun 2 (+)</option>
                                                <option value="-">Commun (-)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Rotation de la LED -->
                                <div class="mb-4">
                                    <x-input-label for="led_rotation" :value="__('Rotation de la LED')" class="text-gray-300" />
                                    <select id="led_rotation" name="led_rotation" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="0" {{ old('led_rotation') == '0' ? 'selected' : '' }}>0° (Normal)</option>
                                        <option value="90" {{ old('led_rotation') == '90' ? 'selected' : '' }}>90°</option>
                                        <option value="180" {{ old('led_rotation') == '180' ? 'selected' : '' }}>180°</option>
                                        <option value="270" {{ old('led_rotation') == '270' ? 'selected' : '' }}>270°</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('led_rotation')" class="mt-2" />
                                </div>
                                
                                <!-- Prévisualisation de la LED -->
                                <div class="bg-gray-900/50 p-4 rounded-xl mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h5 class="text-white font-medium">Prévisualisation</h5>
                                        <div class="text-gray-300 text-sm" id="led_name_preview">SMD1515RGB+0</div>
                                    </div>
                                    
                                    <div class="flex justify-center">
                                        <!-- Canvas pour le dessin de la LED -->
                                        <div class="relative">
                                            <canvas id="led_preview" width="200" height="200" class="border border-gray-600 rounded bg-gray-800"></canvas>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <button type="button" id="generate_datasheet" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        Générer le datasheet
                                    </button>
                                    <input type="hidden" id="led_datasheet_name" name="led_datasheet_name" value="">
                                    <input type="hidden" id="led_datasheet_image" name="led_datasheet_image" value="">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" class="text-gray-300" />
                                <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">{{ old('description', $chantierData['description'] ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                            
                            <!-- Section garantie -->
                            <div class="md:col-span-2 p-4 border border-gray-700 rounded-xl bg-blue-900/10 mb-4">
                                <h4 class="font-medium text-white mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Informations de garantie et provenance
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <div class="flex items-center mb-2">
                                            <input id="is_client_achat" name="is_client_achat" type="checkbox" class="w-4 h-4 accent-blue-500 bg-gray-700 border-gray-600 rounded focus:ring-blue-600" {{ old('is_client_achat') ? 'checked' : '' }}>
                                            <label for="is_client_achat" class="ml-2 text-sm text-gray-300">Le client a acheté le produit chez nous</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="is_under_warranty" name="is_under_warranty" type="checkbox" class="w-4 h-4 accent-green-500 bg-gray-700 border-gray-600 rounded focus:ring-green-600" {{ old('is_under_warranty') ? 'checked' : '' }}>
                                            <label for="is_under_warranty" class="ml-2 text-sm text-gray-300">Le produit est sous garantie</label>
                                        </div>
                                    </div>
                                    
                                    <div id="warranty_details" class="{{ old('is_under_warranty') ? '' : 'hidden' }}">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="warranty_end_date" :value="__('Date de fin de garantie')" class="text-gray-300" />
                                                <x-text-input id="warranty_end_date" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="date" name="warranty_end_date" :value="old('warranty_end_date')" />
                                            </div>
                                            <div>
                                                <x-input-label for="warranty_type" :value="__('Type de garantie')" class="text-gray-300" />
                                                <select id="warranty_type" name="warranty_type" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                    <option value="">Sélectionnez un type de garantie</option>
                                                    <option value="standard" {{ old('warranty_type') == 'standard' ? 'selected' : '' }}>Standard (1 an)</option>
                                                    <option value="premium" {{ old('warranty_type') == 'premium' ? 'selected' : '' }}>Premium (2 ans)</option>
                                                    <option value="prolongee" {{ old('warranty_type') == 'prolongee' ? 'selected' : '' }}>Extension de garantie</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('chantiers.create.step1') }}" class="btn-action btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
                                {{ __('Retour') }}
                            </a>
                            <button type="submit" class="btn-action btn-primary">
                                {{ __('Continuer') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script externe pour la page de création de chantier - étape 2 -->
    <script src="{{ asset('js/pages/chantier-create-step2.js') }}"></script>
</x-app-layout>