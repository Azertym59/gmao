<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-white leading-tight">
            {{ __('Ajouter un module') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl border border-gray-700">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-semibold text-white">Nouveau module pour la dalle #{{ $dalle->id }}</h3>
                        <a href="{{ route('dalles.show', $dalle) }}" class="btn-action btn-primary flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Retour à la dalle') }}
                        </a>
                    </div>

                    <div class="mb-6 glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-blue">
                        <h4 class="font-medium text-accent-blue mb-2">Informations de la dalle</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-300"><span class="font-medium text-white">Dalle:</span> #{{ $dalle->id }} - {{ $dalle->largeur }}×{{ $dalle->hauteur }} mm</p>
                                <p class="text-gray-300"><span class="font-medium text-white">Alimentation:</span> {{ $dalle->alimentation ?? 'Non spécifiée' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-300"><span class="font-medium text-white">Produit:</span> {{ $dalle->produit->marque }} {{ $dalle->produit->modele }}</p>
                                <p class="text-gray-300"><span class="font-medium text-white">Chantier:</span> {{ $dalle->produit->chantier->nom }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('modules.store') }}">
                        @csrf
                        <input type="hidden" name="dalle_id" value="{{ $dalle->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Informations de base -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-accent-green mb-2 mt-4">Informations de base</h4>
                                <div class="border-t border-gray-700 pt-3 pb-1"></div>
                            </div>

                            <!-- Référence du module -->
                            <div>
                                <x-input-label for="reference" :value="__('Référence')" class="text-gray-300 mb-1" />
                                <x-text-input id="reference" class="block w-full bg-gray-700 border-gray-600 text-white" type="text" name="reference" :value="old('reference')" required />
                                <x-input-error :messages="$errors->get('reference')" class="mt-2" />
                            </div>

                            <!-- Numéro du module -->
                            <div>
                                <x-input-label for="reference_module" :value="__('Numéro du module')" class="text-gray-300 mb-1" />
                                <x-text-input id="reference_module" class="block w-full bg-gray-700 border-gray-600 text-white" type="text" name="reference_module" :value="old('reference_module')" />
                                <p class="text-xs text-gray-400 mt-1">Numéro inscrit sur le module (si présent)</p>
                                <x-input-error :messages="$errors->get('reference_module')" class="mt-2" />
                            </div>

                            <!-- État -->
                            <div>
                                <x-input-label for="etat" :value="__('État')" class="text-gray-300 mb-1" />
                                <select id="etat" name="etat" class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="non_commence">Non commencé</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="defaillant">Défaillant</option>
                                    <option value="termine">Terminé</option>
                                </select>
                                <x-input-error :messages="$errors->get('etat')" class="mt-2" />
                            </div>

                            <!-- Dimensions physiques -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-accent-yellow mb-2 mt-4">Dimensions</h4>
                                <div class="border-t border-gray-700 pt-3 pb-1"></div>
                            </div>

                            <div>
                                <x-input-label for="largeur" :value="__('Largeur (mm)')" class="text-gray-300 mb-1" />
                                <x-text-input id="largeur" class="block w-full bg-gray-700 border-gray-600 text-white" type="number" name="largeur" :value="old('largeur')" step="0.1" min="1" required />
                                <x-input-error :messages="$errors->get('largeur')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="hauteur" :value="__('Hauteur (mm)')" class="text-gray-300 mb-1" />
                                <x-text-input id="hauteur" class="block w-full bg-gray-700 border-gray-600 text-white" type="number" name="hauteur" :value="old('hauteur')" step="0.1" min="1" required />
                                <x-input-error :messages="$errors->get('hauteur')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="position_x" :value="__('Position X')" class="text-gray-300 mb-1" />
                                <x-text-input id="position_x" class="block w-full bg-gray-700 border-gray-600 text-white" type="number" name="position_x" :value="old('position_x')" min="0" />
                                <x-input-error :messages="$errors->get('position_x')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="position_y" :value="__('Position Y')" class="text-gray-300 mb-1" />
                                <x-text-input id="position_y" class="block w-full bg-gray-700 border-gray-600 text-white" type="number" name="position_y" :value="old('position_y')" min="0" />
                                <x-input-error :messages="$errors->get('position_y')" class="mt-2" />
                            </div>

                            <!-- Pixels -->
                            <div>
                                <x-input-label for="nb_pixels_largeur" :value="__('Pixels en largeur')" class="text-gray-300 mb-1" />
                                <x-text-input id="nb_pixels_largeur" class="block w-full bg-gray-700 border-gray-600 text-white" type="number" name="nb_pixels_largeur" :value="old('nb_pixels_largeur')" min="1" required />
                                <x-input-error :messages="$errors->get('nb_pixels_largeur')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nb_pixels_hauteur" :value="__('Pixels en hauteur')" class="text-gray-300 mb-1" />
                                <x-text-input id="nb_pixels_hauteur" class="block w-full bg-gray-700 border-gray-600 text-white" type="number" name="nb_pixels_hauteur" :value="old('nb_pixels_hauteur')" min="1" required />
                                <x-input-error :messages="$errors->get('nb_pixels_hauteur')" class="mt-2" />
                            </div>

                            <!-- Composants électroniques -->
                            <div class="lg:col-span-3">
                                <h4 class="font-medium text-accent-blue mb-2 mt-4">Composants électroniques</h4>
                                <div class="border-t border-gray-700 pt-3 pb-1"></div>
                            </div>

                            <div>
                                <x-input-label for="carte_reception" :value="__('Carte de réception')" class="text-gray-300 mb-1" />
                                <x-text-input id="carte_reception" class="block w-full bg-gray-700 border-gray-600 text-white" type="text" name="carte_reception" :value="old('carte_reception')" />
                                <x-input-error :messages="$errors->get('carte_reception')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="hub" :value="__('Hub')" class="text-gray-300 mb-1" />
                                <x-text-input id="hub" class="block w-full bg-gray-700 border-gray-600 text-white" type="text" name="hub" :value="old('hub')" />
                                <x-input-error :messages="$errors->get('hub')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="driver" :value="__('Driver (IC de commande)')" class="text-gray-300 mb-1" />
                                <x-text-input id="driver" class="block w-full bg-gray-700 border-gray-600 text-white" type="text" name="driver" :value="old('driver')" />
                                <x-input-error :messages="$errors->get('driver')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="shift_register" :value="__('Shift Register')" class="text-gray-300 mb-1" />
                                <x-text-input id="shift_register" class="block w-full bg-gray-700 border-gray-600 text-white" type="text" name="shift_register" :value="old('shift_register')" />
                                <x-input-error :messages="$errors->get('shift_register')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="buffer" :value="__('Buffer')" class="text-gray-300 mb-1" />
                                <x-text-input id="buffer" class="block w-full bg-gray-700 border-gray-600 text-white" type="text" name="buffer" :value="old('buffer')" />
                                <x-input-error :messages="$errors->get('buffer')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 space-x-3">
                            <a href="{{ route('dalles.show', $dalle) }}" class="btn-action btn-secondary">
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
</x-app-layout>