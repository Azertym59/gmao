<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Création de chantier - Étape 2/5 : Produit') }}
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
                        <span class="ml-2 font-medium text-white">Produit</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-600 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-gray-400 font-bold">3</div>
                        <span class="ml-2 font-medium text-gray-400">Composition</span>
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
                    
                    @if(session('error'))
                    <div class="mb-6 bg-red-900/30 border border-red-500/30 p-4 rounded-xl text-red-300">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('chantiers.store.step2') }}" id="product_form">
                        @csrf
                        <div class="mb-4 bg-blue-900/20 border border-blue-500/30 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <span class="text-blue-300 text-sm font-medium">Astuce : Autocomplétion des produits</span>
                                    <p class="text-xs text-gray-300 mt-1">Commencez à taper dans les champs Marque et Modèle pour afficher des suggestions basées sur les produits existants. Vous pouvez sélectionner un produit existant ou créer un nouveau.</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Sélection du produit</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label for="from_catalogue_false" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50" id="new_product_container">
                                        <input type="radio" name="from_catalogue" id="from_catalogue_false" value="0" class="mr-2 accent-accent-blue" checked>
                                        <div>
                                            <span class="font-medium text-white">Créer un nouveau produit</span>
                                            <p class="text-sm text-gray-400">Pour ajouter un nouveau type d'écran</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <div>
                                    <label for="from_catalogue_true" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50" id="catalogue_container">
                                        <input type="radio" name="from_catalogue" id="from_catalogue_true" value="1" class="mr-2 accent-accent-blue">
                                        <div>
                                            <span class="font-medium text-white">Sélectionner du catalogue</span>
                                            <p class="text-sm text-gray-400">Pour choisir parmi les modèles existants</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Options du catalogue -->
                            <div id="catalogue_options" class="mb-6 p-4 border border-gray-700 rounded-xl bg-gray-800/30" style="display: none;">
                                <div>
                                    <x-input-label for="catalogue_id" :value="__('Produit du catalogue')" class="text-gray-300" />
                                    <select id="catalogue_id" name="catalogue_id" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="">Sélectionnez un produit</option>
                                        @foreach($produitsCatalogue as $produit)
                                            <option value="{{ $produit->id }}">{{ $produit->marque }} {{ $produit->modele }} ({{ $produit->pitch }}mm)</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-red-400 mt-1" id="catalogue_error" style="display: none;">Veuillez sélectionner un produit du catalogue</p>
                                </div>
                            </div>
                            
                            <div id="new_product_options" style="display: none;">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <!-- Champ marque avec autocomplétion -->
                                        <x-marque-autocomplete label="Marque" required="true" />
                                        <!-- Champ caché pour stocker la valeur -->
                                        <input type="hidden" id="marque" name="marque">
                                        <p class="text-xs text-gray-400 mt-1">La marque sera automatiquement convertie en majuscules</p>
                                    </div>
                                    
                                    <div>
                                        <!-- Champ modèle avec autocomplétion -->
                                        <x-modele-autocomplete label="Modèle" required="true" />
                                        <!-- Champ caché pour stocker la valeur -->
                                        <input type="hidden" id="modele" name="modele">
                                        <p class="text-xs text-gray-400 mt-1">Le modèle sera automatiquement converti en majuscules</p>
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="pitch" :value="__('Pitch (mm)')" class="text-gray-300" />
                                        <x-text-input id="pitch" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="pitch" step="0.01" min="0.1" max="100" />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="utilisation" :value="__('Utilisation')" class="text-gray-300" />
                                        <select id="utilisation" name="utilisation" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="indoor">Indoor</option>
                                            <option value="outdoor">Outdoor</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="bain_couleur" :value="__('Bain de couleur')" class="text-gray-300" />
                                        <x-text-input id="bain_couleur" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="bain_couleur" placeholder="Ex: SMD 3-en-1, COB, etc." />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="alimentation" :value="__('Alimentation')" class="text-gray-300" />
                                        <x-text-input id="alimentation" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="alimentation" placeholder="Ex: 5V, 110-220V, etc." />
                                    </div>
                                </div>
                                
                                <!-- Système électronique global -->
                                <h4 class="text-md font-semibold text-accent-blue mb-4">Électronique</h4>
                                
                                <!-- Section Cerveau -->
                                <div class="mb-4 p-3 border-l-4 border-accent-blue/70 bg-gray-800/20">
                                    <h5 class="text-sm font-semibold text-accent-blue/90 mb-3">Cerveau</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="electronique" :value="__('Système électronique')" class="text-gray-300" />
                                            <select id="electronique" name="electronique" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="nova">Nova</option>
                                                <option value="linsn">Linsn</option>
                                                <option value="dbstar">DBstar</option>
                                                <option value="colorlight">Colorlight</option>
                                                <option value="barco">Barco</option>
                                                <option value="brompton">Brompton</option>
                                                <option value="autre">Autre</option>
                                            </select>
                                        </div>
                                        
                                        <div id="electronique_detail_container" class="hidden">
                                            <x-input-label for="electronique_detail" :value="__('Précisez l\'électronique')" class="text-gray-300" />
                                            <x-text-input id="electronique_detail" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="electronique_detail" />
                                        </div>
                                        
                                        <div>
                                            <x-input-label for="hub" :value="__('Hub')" class="text-gray-300" />
                                            <x-text-input id="hub" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="hub" placeholder="Ex: HUB75, HUB75E, etc." />
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Section Module -->
                                <div class="mb-6 p-3 border-l-4 border-accent-blue/70 bg-gray-800/20">
                                    <h5 class="text-sm font-semibold text-accent-blue/90 mb-3">Module</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                
                                <!-- Datasheet LED -->
                                <h4 class="text-md font-semibold text-accent-blue mb-4">Datasheet LED</h4>
                                <div class="border border-gray-700 rounded-lg bg-gray-800/20 p-4 mb-6">
                                    <!-- Sélection LED existante ou nouvelle -->
                                    <div class="mb-4">
                                        <div class="flex items-center mb-3">
                                            <input type="radio" id="led_nouveau" name="led_selection" value="nouveau" class="mr-2 accent-accent-blue" checked>
                                            <label for="led_nouveau" class="text-gray-300">Nouvelle configuration</label>
                                            
                                            <input type="radio" id="led_existant" name="led_selection" value="existant" class="ml-6 mr-2 accent-accent-blue">
                                            <label for="led_existant" class="text-gray-300">Sélectionner une configuration existante</label>
                                        </div>
                                        
                                        <div id="led_existant_container" class="mb-4 hidden">
                                            <x-input-label for="led_existant_select" :value="__('Sélectionnez une LED')" class="text-gray-300" />
                                            <select id="led_existant_select" name="led_existant_select" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="">-- Sélectionnez une LED --</option>
                                                <option value="SMD1515RGB+0">SMD1515RGB+0</option>
                                                <option value="SMD2121RGB+0">SMD2121RGB+0</option>
                                                <!-- Ici nous pourrions charger dynamiquement les LED de la base de données -->
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div id="led_nouveau_container" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
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
                                            <div class="flex">
                                                <select id="led_size_preset" class="block mt-1 w-full rounded-l-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                    <option value="">Personnalisé</option>
                                                    <option value="1010">1010</option>
                                                    <option value="1515">1515</option>
                                                    <option value="2020">2020</option>
                                                    <option value="2121">2121</option>
                                                    <option value="2727">2727</option>
                                                    <option value="3030">3030</option>
                                                    <option value="3535">3535</option>
                                                    <option value="5050">5050</option>
                                                </select>
                                                <x-text-input id="led_size" class="block mt-1 w-full rounded-r-md border-l-0 bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="led_size" :value="old('led_size')" placeholder="Ex: 1515" />
                                            </div>
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
                                        <!-- 2 pads: colonne gauche/droite -->
                                        <div class="pad-configs grid grid-cols-1 md:grid-cols-2 gap-4" id="pad-config-2">
                                            <div class="bg-gray-900/30 p-3 rounded-lg">
                                                <h6 class="font-medium text-accent-blue mb-2">Côté Gauche</h6>
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
                                            </div>
                                            <div class="bg-gray-900/30 p-3 rounded-lg">
                                                <h6 class="font-medium text-accent-blue mb-2">Côté Droit</h6>
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
                                        </div>
                                        
                                        <!-- 4 pads: 2 à gauche, 2 à droite -->
                                        <div class="pad-configs hidden grid grid-cols-1 md:grid-cols-2 gap-4" id="pad-config-4">
                                            <div class="bg-gray-900/30 p-3 rounded-lg">
                                                <h6 class="font-medium text-accent-blue mb-2">Côté Gauche</h6>
                                                <div class="space-y-3">
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
                                                </div>
                                            </div>
                                            <div class="bg-gray-900/30 p-3 rounded-lg">
                                                <h6 class="font-medium text-accent-blue mb-2">Côté Droit</h6>
                                                <div class="space-y-3">
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
                                            </div>
                                        </div>
                                        
                                        <!-- 6 pads: 3 à gauche, 3 à droite -->
                                        <div class="pad-configs hidden grid grid-cols-1 md:grid-cols-2 gap-4" id="pad-config-6">
                                            <div class="bg-gray-900/30 p-3 rounded-lg">
                                                <h6 class="font-medium text-accent-blue mb-2">Côté Gauche</h6>
                                                <div class="space-y-3">
                                                    <div>
                                                        <x-input-label for="pad_1_6" :value="__('Pad 1')" class="text-gray-300" />
                                                        <select id="pad_1_6" name="pad_1_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            <option value="+">Commun (+)</option>
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="pad_2_6" :value="__('Pad 2')" class="text-gray-300" />
                                                        <select id="pad_2_6" name="pad_2_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="pad_3_6" :value="__('Pad 3')" class="text-gray-300" />
                                                        <select id="pad_3_6" name="pad_3_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-900/30 p-3 rounded-lg">
                                                <h6 class="font-medium text-accent-blue mb-2">Côté Droit</h6>
                                                <div class="space-y-3">
                                                    <div>
                                                        <x-input-label for="pad_4_6" :value="__('Pad 4')" class="text-gray-300" />
                                                        <select id="pad_4_6" name="pad_4_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="pad_5_6" :value="__('Pad 5')" class="text-gray-300" />
                                                        <select id="pad_5_6" name="pad_5_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="pad_6_6" :value="__('Pad 6')" class="text-gray-300" />
                                                        <select id="pad_6_6" name="pad_6_6" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- 8 pads: 4 à gauche, 4 à droite -->
                                        <div class="pad-configs hidden grid grid-cols-1 md:grid-cols-2 gap-4" id="pad-config-8">
                                            <div class="bg-gray-900/30 p-3 rounded-lg">
                                                <h6 class="font-medium text-accent-blue mb-2">Côté Gauche</h6>
                                                <div class="space-y-3">
                                                    <div>
                                                        <x-input-label for="pad_1_8" :value="__('Pad 1')" class="text-gray-300" />
                                                        <select id="pad_1_8" name="pad_1_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="pad_2_8" :value="__('Pad 2')" class="text-gray-300" />
                                                        <select id="pad_2_8" name="pad_2_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="pad_3_8" :value="__('Pad 3')" class="text-gray-300" />
                                                        <select id="pad_3_8" name="pad_3_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="pad_4_8" :value="__('Pad 4')" class="text-gray-300" />
                                                        <select id="pad_4_8" name="pad_4_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-900/30 p-3 rounded-lg">
                                                <h6 class="font-medium text-accent-blue mb-2">Côté Droit</h6>
                                                <div class="space-y-3">
                                                    <div>
                                                        <x-input-label for="pad_5_8" :value="__('Pad 5')" class="text-gray-300" />
                                                        <select id="pad_5_8" name="pad_5_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="pad_6_8" :value="__('Pad 6')" class="text-gray-300" />
                                                        <select id="pad_6_8" name="pad_6_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="pad_7_8" :value="__('Pad 7')" class="text-gray-300" />
                                                        <select id="pad_7_8" name="pad_7_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <x-input-label for="pad_8_8" :value="__('Pad 8')" class="text-gray-300" />
                                                        <select id="pad_8_8" name="pad_8_8" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                            <option value="R">Rouge (R)</option>
                                                            <option value="G">Vert (G)</option>
                                                            <option value="B">Bleu (B)</option>
                                                            
                                                            
                                                            
                                                            <option value="+">Commun (+)</option>
                                                            
                                                            <option value="-">Commun (-)</option>
                                                        </select>
                                                    </div>
                                                </div>
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
                                
                                <!-- Dimensions des dalles et modules -->
                                <h4 class="text-md font-semibold text-accent-blue mb-4">Dimensions</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <x-input-label for="largeur_dalle" :value="__('Largeur dalle (mm)')" class="text-gray-300" />
                                        <x-text-input id="largeur_dalle" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="largeur_dalle" step="1" min="1" value="500" required />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="hauteur_dalle" :value="__('Hauteur dalle (mm)')" class="text-gray-300" />
                                        <x-text-input id="hauteur_dalle" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="hauteur_dalle" step="1" min="1" value="500" required />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="largeur_module" :value="__('Largeur module (mm)')" class="text-gray-300" />
                                        <x-text-input id="largeur_module" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="largeur_module" step="1" min="1" value="250" required />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="hauteur_module" :value="__('Hauteur module (mm)')" class="text-gray-300" />
                                        <x-text-input id="hauteur_module" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="hauteur_module" step="1" min="1" value="250" required />
                                    </div>
                                </div>
                                
                                <!-- Disposition des modules -->
                                <h4 class="text-md font-semibold text-accent-blue mb-4">Disposition des modules</h4>
                                <div class="grid grid-cols-1 gap-4 mb-4">
                                    <div class="flex flex-wrap gap-4 justify-center">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="disposition_modules" value="2x2" class="sr-only" checked>
                                            <div class="bg-gray-700 p-2 rounded-lg border-2 border-transparent hover:border-accent-blue transition disposition-option">
                                                <div class="grid grid-cols-2 gap-1 w-32 h-32">
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                </div>
                                                <div class="text-center mt-2 text-gray-300">2×2</div>
                                            </div>
                                        </label>
                                        
                                        <label class="cursor-pointer">
                                            <input type="radio" name="disposition_modules" value="4x1" class="sr-only">
                                            <div class="bg-gray-700 p-2 rounded-lg border-2 border-transparent hover:border-accent-blue transition disposition-option">
                                                <div class="grid grid-cols-4 gap-1 w-32 h-32">
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                </div>
                                                <div class="text-center mt-2 text-gray-300">4×1</div>
                                            </div>
                                        </label>
                                        
                                        <label class="cursor-pointer">
                                            <input type="radio" name="disposition_modules" value="1x4" class="sr-only">
                                            <div class="bg-gray-700 p-2 rounded-lg border-2 border-transparent hover:border-accent-blue transition disposition-option">
                                                <div class="grid grid-cols-1 gap-1 w-32 h-32">
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                </div>
                                                <div class="text-center mt-2 text-gray-300">1×4</div>
                                            </div>
                                        </label>
                                        
                                        <label class="cursor-pointer">
                                            <input type="radio" name="disposition_modules" value="4x4" class="sr-only">
                                            <div class="bg-gray-700 p-2 rounded-lg border-2 border-transparent hover:border-accent-blue transition disposition-option">
                                                <div class="grid grid-cols-4 gap-1 w-32 h-32">
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                    <div class="bg-accent-blue rounded module-preview"></div>
                                                </div>
                                                <div class="text-center mt-2 text-gray-300">4×4</div>
                                            </div>
                                        </label>
                                        
                                        <label class="cursor-pointer">
                                            <input type="radio" name="disposition_modules" value="personnalise" class="sr-only">
                                            <div class="bg-gray-700 p-2 rounded-lg border-2 border-transparent hover:border-accent-blue transition disposition-option">
                                                <div class="w-32 h-32 flex items-center justify-center text-gray-300">
                                                    <div class="text-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                        </svg>
                                                        Personnalisé
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div id="disposition_personnalisee" class="hidden mt-4 p-4 bg-gray-800 rounded-lg">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="modules_largeur" :value="__('Modules en largeur')" class="text-gray-300" />
                                                <x-text-input id="modules_largeur" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="modules_largeur" min="1" max="10" value="2" />
                                            </div>
                                            
                                            <div>
                                                <x-input-label for="modules_hauteur" :value="__('Modules en hauteur')" class="text-gray-300" />
                                                <x-text-input id="modules_hauteur" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="modules_hauteur" min="1" max="10" value="2" />
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <div id="preview_personnalise" class="bg-gray-700 p-4 rounded-lg mx-auto" style="max-width:400px;">
                                                <div id="grid_preview" class="grid grid-cols-2 gap-2 w-full aspect-square">
                                                    <div class="bg-accent-blue rounded"></div>
                                                    <div class="bg-accent-blue rounded"></div>
                                                    <div class="bg-accent-blue rounded"></div>
                                                    <div class="bg-accent-blue rounded"></div>
                                                </div>
                                            </div>
                                            <p class="text-center text-sm text-gray-400 mt-2">Prévisualisation de la disposition</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="md:col-span-2 mt-4">
                                    <label class="flex items-center mt-2">
                                        <input type="checkbox" name="add_to_catalogue" value="1" class="accent-accent-blue">
                                        <span class="ml-2 text-sm text-gray-300">Ajouter ce produit au catalogue pour une utilisation future</span>
                                    </label>
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
                            <button type="button" class="btn-action btn-primary" id="submit_button">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Éléments du formulaire
            const productForm = document.getElementById('product_form');
            const fromCatalogueFalse = document.getElementById('from_catalogue_false');
            const fromCatalogueTrue = document.getElementById('from_catalogue_true');
            const catalogueOptions = document.getElementById('catalogue_options');
            const newProductOptions = document.getElementById('new_product_options');
            const catalogueContainer = document.getElementById('catalogue_container');
            const newProductContainer = document.getElementById('new_product_container');
            const catalogueId = document.getElementById('catalogue_id');
            const catalogueError = document.getElementById('catalogue_error');
            const submitButton = document.getElementById('submit_button');
            
            // Champs requis pour le nouveau produit
            const marqueInput = document.getElementById('marque');
            const modeleInput = document.getElementById('modele');
            const pitchInput = document.getElementById('pitch');
            
            // SOLUTION CRITIQUE: Manipuler les attributs required sur les champs
            function toggleRequiredFields() {
                if (fromCatalogueTrue.checked) {
                    // Mode catalogue: rendre catalogue_id requis, ôter required des autres champs
                    catalogueId.setAttribute('required', 'required');
                    
                    // Enlever l'attribut required des champs de nouveau produit
                    if (marqueInput) marqueInput.removeAttribute('required');
                    if (modeleInput) modeleInput.removeAttribute('required');
                    if (pitchInput) pitchInput.removeAttribute('required');
                } else {
                    // Mode nouveau produit: rendre les champs de produit requis, ôter required de catalogue_id
                    catalogueId.removeAttribute('required');
                    
                    // Ajouter l'attribut required aux champs de nouveau produit
                    if (marqueInput) marqueInput.setAttribute('required', 'required');
                    if (modeleInput) modeleInput.setAttribute('required', 'required');
                    if (pitchInput) pitchInput.setAttribute('required', 'required');
                }
            }
            
            // Basculer entre les modes nouveau produit et catalogue
            function toggleProductSelection() {
                if (fromCatalogueTrue.checked) {
                    // Mode catalogue
                    catalogueOptions.style.display = 'block';
                    newProductOptions.style.display = 'none';
                    catalogueContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
                    newProductContainer.classList.remove('border-accent-blue', 'bg-gray-800/50');
                } else {
                    // Mode nouveau produit
                    catalogueOptions.style.display = 'none';
                    newProductOptions.style.display = 'block';
                    catalogueContainer.classList.remove('border-accent-blue', 'bg-gray-800/50');
                    newProductContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
                }
                
                // Mettre à jour les champs requis
                toggleRequiredFields();
            }
            
            // Gestionnaires d'événements pour les boutons radio
            if (fromCatalogueTrue && fromCatalogueFalse) {
                fromCatalogueTrue.addEventListener('change', toggleProductSelection);
                fromCatalogueFalse.addEventListener('change', toggleProductSelection);
            }
            
            // Validation et soumission manuelle du formulaire
            if (submitButton && productForm) {
                submitButton.addEventListener('click', function() {
                    let isValid = true;
                    
                    // Vérifier si on est en mode catalogue et qu'un produit est sélectionné
                    if (fromCatalogueTrue.checked) {
                        if (!catalogueId.value) {
                            isValid = false;
                            catalogueError.style.display = 'block';
                            catalogueId.style.border = '2px solid #f56565';
                            catalogueId.focus();
                        } else {
                            catalogueError.style.display = 'none';
                            catalogueId.style.border = '';
                        }
                    }
                    
                    // Si tout est valide, soumettre le formulaire
                    if (isValid) {
                        // SOLUTION CRITIQUE: Désactiver temporairement tous les champs cachés non utilisés
                        if (fromCatalogueTrue.checked) {
                            // Désactiver tous les champs pour nouveau produit (ils ne sont pas utilisés)
                            const allNewProductInputs = newProductOptions.querySelectorAll('input, select, textarea');
                            allNewProductInputs.forEach(input => {
                                input.disabled = true;
                            });
                        } else {
                            // Désactiver le sélecteur de catalogue (pas utilisé)
                            catalogueId.disabled = true;
                        }
                        
                        // Ajouter des logs pour le débogage
                        console.log('Soumission du formulaire - isValid =', isValid);
                        console.log('Route du formulaire :', productForm.action);
                        console.log('Méthode du formulaire :', productForm.method);
                        
                        // Vérifier et corriger l'action du formulaire si nécessaire
                        if (!productForm.action || productForm.action === '') {
                            productForm.action = "{{ route('chantiers.store.step2') }}";
                            console.log('Action du formulaire corrigée :', productForm.action);
                        }
                        
                        // S'assurer que la méthode est correcte
                        if (!productForm.method || productForm.method.toLowerCase() !== 'post') {
                            // Ajouter un champ hidden pour le method si nécessaire
                            if (!document.querySelector('input[name="_method"]')) {
                                const methodField = document.createElement('input');
                                methodField.type = 'hidden';
                                methodField.name = '_method';
                                methodField.value = 'POST';
                                productForm.appendChild(methodField);
                                console.log('Champ method ajouté');
                            }
                        }
                        
                        // Soumettre le formulaire
                        console.log('Formulaire soumis!');
                        productForm.submit();
                    }
                });
            }
            
            // Style du select lorsqu'un produit est choisi
            if (catalogueId) {
                catalogueId.addEventListener('change', function() {
                    if (this.value) {
                        // Style pour indiquer la sélection
                        this.style.border = '2px solid #10b981';
                        catalogueError.style.display = 'none';
                    } else {
                        this.style.border = '';
                    }
                });
            }
            
            // Initialisation
            toggleProductSelection();
        });
    </script>
    @push('scripts')
    <style>
        /* Styles pour corriger l'affichage des configurations de pad */
        .pad-configs {
            display: none; /* Masquer par défaut */
        }
        #pad-config-2 {
            display: grid; /* Afficher la configuration 2 pads par défaut */
        }
        
        /* Assurer que les pads sont visibles dans le canvas */
        #led_preview {
            background-color: #333;
            border-radius: 8px;
        }
        
        /* Améliorer le style des options de disposition */
        .disposition-option.selected {
            border-color: #3B82F6 !important;
            background-color: rgba(59, 130, 246, 0.1);
        }
    </style>
    <script src="{{ asset('js/pages/chantier-create-step2.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Script inline chargé - Formulaire création chantier étape 2');
            
            // Gestion du basculement entre catalogue et nouveau produit
            const fromCatalogueFalse = document.getElementById('from_catalogue_false');
            const fromCatalogueTrue = document.getElementById('from_catalogue_true');
            const catalogueOptions = document.getElementById('catalogue_options');
            const newProductOptions = document.getElementById('new_product_options');
            
            if (fromCatalogueFalse && fromCatalogueTrue) {
                // Fonction pour basculer les modes
                function toggleProductMode() {
                    if (fromCatalogueTrue.checked) {
                        catalogueOptions.style.display = 'block';
                        newProductOptions.style.display = 'none';
                    } else {
                        catalogueOptions.style.display = 'none';
                        newProductOptions.style.display = 'block';
                    }
                }
                
                // Initialiser au chargement
                toggleProductMode();
                
                // Ajouter les écouteurs d'événements
                fromCatalogueFalse.addEventListener('change', toggleProductMode);
                fromCatalogueTrue.addEventListener('change', toggleProductMode);
            }
            
            // 1. Gestion des pads pour le datasheet LED
            const ledPadsSelect = document.getElementById('led_pads');
            const padConfigs = document.querySelectorAll('.pad-configs');
            
            if (ledPadsSelect && padConfigs.length > 0) {
                function updatePadDisplay() {
                    const selectedPads = ledPadsSelect.value;
                    console.log('Sélection de pads changée à:', selectedPads);
                    
                    // Masquer tous les configurations
                    padConfigs.forEach(config => {
                        config.classList.add('hidden');
                    });
                    
                    // Afficher la configuration correspondante
                    const activeConfig = document.getElementById(`pad-config-${selectedPads}`);
                    if (activeConfig) {
                        console.log('Affichage de la configuration:', selectedPads);
                        activeConfig.classList.remove('hidden');
                    } else {
                        console.error('Configuration non trouvée pour', selectedPads);
                    }
                }
                
                // Initialiser
                updatePadDisplay();
                
                // Ajouter l'écouteur d'événements
                ledPadsSelect.addEventListener('change', updatePadDisplay);
            } else {
                console.error('Éléments pour la configuration des pads non trouvés');
            }
            
            // 2. Gestion de la disposition des modules
            const dispositionRadios = document.querySelectorAll('input[name="disposition_modules"]');
            const dispositionPersonnalisee = document.getElementById('disposition_personnalisee');
            const dispositionOptions = document.querySelectorAll('.disposition-option');
            
            if (dispositionRadios.length > 0 && dispositionPersonnalisee) {
                function updateDispositionDisplay() {
                    // Trouver le bouton radio sélectionné
                    const selectedDisposition = document.querySelector('input[name="disposition_modules"]:checked');
                    if (selectedDisposition) {
                        console.log('Disposition sélectionnée:', selectedDisposition.value);
                        
                        // Mettre à jour les styles visuels
                        dispositionOptions.forEach(option => {
                            option.classList.remove('selected');
                        });
                        
                        // Ajouter la classe 'selected' à l'option sélectionnée
                        const parentLabel = selectedDisposition.closest('label');
                        if (parentLabel) {
                            const optionDiv = parentLabel.querySelector('.disposition-option');
                            if (optionDiv) {
                                optionDiv.classList.add('selected');
                            }
                        }
                        
                        // Afficher/masquer les options personnalisées
                        if (selectedDisposition.value === 'personnalise') {
                            dispositionPersonnalisee.style.display = 'block';
                        } else {
                            dispositionPersonnalisee.style.display = 'none';
                        }
                    }
                }
                
                // Initialiser
                updateDispositionDisplay();
                
                // Ajouter les écouteurs d'événements
                dispositionRadios.forEach(radio => {
                    radio.addEventListener('change', updateDispositionDisplay);
                });
                
                // Cliquer sur une option de disposition doit sélectionner le bouton radio correspondant
                dispositionOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        const parentLabel = this.closest('label');
                        if (parentLabel) {
                            const radio = parentLabel.querySelector('input[type="radio"]');
                            if (radio) {
                                radio.checked = true;
                                radio.dispatchEvent(new Event('change'));
                            }
                        }
                    });
                });
            } else {
                console.error('Éléments pour la disposition des modules non trouvés');
            }
            
            // 3. Prévisualisation de la grille pour la disposition personnalisée
            const modulesLargeur = document.getElementById('modules_largeur');
            const modulesHauteur = document.getElementById('modules_hauteur');
            const gridPreview = document.getElementById('grid_preview');
            
            if (modulesLargeur && modulesHauteur && gridPreview) {
                function updateGridPreview() {
                    const largeur = parseInt(modulesLargeur.value) || 2;
                    const hauteur = parseInt(modulesHauteur.value) || 2;
                    
                    console.log('Mise à jour de la grille:', largeur, 'x', hauteur);
                    
                    // Mettre à jour la classe CSS pour les colonnes
                    gridPreview.className = 'grid gap-2 w-full aspect-square';
                    gridPreview.style.gridTemplateColumns = `repeat(${largeur}, 1fr)`;
                    
                    // Vider et recréer les cellules
                    gridPreview.innerHTML = '';
                    for (let i = 0; i < largeur * hauteur; i++) {
                        const cell = document.createElement('div');
                        cell.className = 'bg-accent-blue rounded';
                        gridPreview.appendChild(cell);
                    }
                }
                
                // Initialiser
                updateGridPreview();
                
                // Ajouter les écouteurs d'événements
                modulesLargeur.addEventListener('change', updateGridPreview);
                modulesHauteur.addEventListener('change', updateGridPreview);
            } else {
                console.error('Éléments pour la prévisualisation de la grille non trouvés');
            }
        });
    </script>
    @endpush
</x-app-layout>