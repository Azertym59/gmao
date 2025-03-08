<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter un module') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-medium text-blue-800">Informations de la dalle</h3>
                        <p><span class="font-medium">Dalle:</span> #{{ $dalle->id }} - {{ $dalle->largeur }}×{{ $dalle->hauteur }} mm</p>
                        <p><span class="font-medium">Produit:</span> {{ $dalle->produit->marque }} {{ $dalle->produit->modele }}</p>
                        <p><span class="font-medium">Chantier:</span> {{ $dalle->produit->chantier->nom }}</p>
                    </div>

                    <form method="POST" action="{{ route('modules.store') }}">
                        @csrf
                        <input type="hidden" name="dalle_id" value="{{ $dalle->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Dimensions physiques -->
                            <div>
                                <x-input-label for="largeur" :value="__('Largeur (mm)')" />
                                <x-text-input id="largeur" class="block mt-1 w-full" type="number" name="largeur" :value="old('largeur')" step="0.1" min="1" required />
                                <x-input-error :messages="$errors->get('largeur')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="hauteur" :value="__('Hauteur (mm)')" />
                                <x-text-input id="hauteur" class="block mt-1 w-full" type="number" name="hauteur" :value="old('hauteur')" step="0.1" min="1" required />
                                <x-input-error :messages="$errors->get('hauteur')" class="mt-2" />
                            </div>

                            <!-- Référence module -->
                            <div>
                                <x-input-label for="reference_module" :value="__('Référence (optionnel)')" />
                                <x-text-input id="reference_module" class="block mt-1 w-full" type="text" name="reference_module" :value="old('reference_module')" />
                                <x-input-error :messages="$errors->get('reference_module')" class="mt-2" />
                            </div>

                            <!-- Nombre de pixels -->
                            <div>
                                <x-input-label for="nb_pixels_largeur" :value="__('Pixels en largeur')" />
                                <x-text-input id="nb_pixels_largeur" class="block mt-1 w-full" type="number" name="nb_pixels_largeur" :value="old('nb_pixels_largeur')" min="1" required />
                                <x-input-error :messages="$errors->get('nb_pixels_largeur')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nb_pixels_hauteur" :value="__('Pixels en hauteur')" />
                                <x-text-input id="nb_pixels_hauteur" class="block mt-1 w-full" type="number" name="nb_pixels_hauteur" :value="old('nb_pixels_hauteur')" min="1" required />
                                <x-input-error :messages="$errors->get('nb_pixels_hauteur')" class="mt-2" />
                            </div>

                            <!-- Composants électroniques -->
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

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('dalles.show', $dalle) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button>
                                {{ __('Enregistrer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
