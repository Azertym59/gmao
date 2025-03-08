<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le module') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('modules.update', $module) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Dalle -->
                            <div class="lg:col-span-3">
                                <x-input-label for="dalle_id" :value="__('Dalle')" />
                                <select id="dalle_id" name="dalle_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($dalles as $dalle)
                                        <option value="{{ $dalle->id }}" {{ $module->dalle_id == $dalle->id ? 'selected' : '' }}>
                                            Dalle #{{ $dalle->id }} - {{ $dalle->largeur }}×{{ $dalle->hauteur }}mm - {{ $dalle->produit->marque }} {{ $dalle->produit->modele }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('dalle_id')" class="mt-2" />
                            </div>

                            <!-- État -->
                            <div>
                                <x-input-label for="etat" :value="__('État')" />
                                <select id="etat" name="etat" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="non_commence" {{ $module->etat == 'non_commence' ? 'selected' : '' }}>Non commencé</option>
                                    <option value="en_cours" {{ $module->etat == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="defaillant" {{ $module->etat == 'defaillant' ? 'selected' : '' }}>Défaillant</option>
                                    <option value="termine" {{ $module->etat == 'termine' ? 'selected' : '' }}>Terminé</option>
                                </select>
                                <x-input-error :messages="$errors->get('etat')" class="mt-2" />
                            </div>

                            <!-- Dimensions physiques -->
                            <div>
                                <x-input-label for="largeur" :value="__('Largeur (mm)')" />
                                <x-text-input id="largeur" class="block mt-1 w-full" type="number" name="largeur" :value="old('largeur', $module->largeur)" step="0.1" min="1" required />
                                <x-input-error :messages="$errors->get('largeur')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="hauteur" :value="__('Hauteur (mm)')" />
                                <x-text-input id="hauteur" class="block mt-1 w-full" type="number" name="hauteur" :value="old('hauteur', $module->hauteur)" step="0.1" min="1" required />
                                <x-input-error :messages="$errors->get('hauteur')" class="mt-2" />
                            </div>

                            <!-- Référence module -->
                            <div>
                                <x-input-label for="reference_module" :value="__('Référence (optionnel)')" />
                                <x-text-input id="reference_module" class="block mt-1 w-full" type="text" name="reference_module" :value="old('reference_module', $module->reference_module)" />
                                <x-input-error :messages="$errors->get('reference_module')" class="mt-2" />
                            </div>

                            <!-- Nombre de pixels -->
                            <div>
                                <x-input-label for="nb_pixels_largeur" :value="__('Pixels en largeur')" />
                                <x-text-input id="nb_pixels_largeur" class="block mt-1 w-full" type="number" name="nb_pixels_largeur" :value="old('nb_pixels_largeur', $module->nb_pixels_largeur)" min="1" required />
                                <x-input-error :messages="$errors->get('nb_pixels_largeur')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nb_pixels_hauteur" :value="__('Pixels en hauteur')" />
                                <x-text-input id="nb_pixels_hauteur" class="block mt-1 w-full" type="number" name="nb_pixels_hauteur" :value="old('nb_pixels_hauteur', $module->nb_pixels_hauteur)" min="1" required />
                                <x-input-error :messages="$errors->get('nb_pixels_hauteur')" class="mt-2" />
                            </div>

                            <!-- Composants électroniques -->
                            <div>
                                <x-input-label for="carte_reception" :value="__('Carte de réception')" />
                                <x-text-input id="carte_reception" class="block mt-1 w-full" type="text" name="carte_reception" :value="old('carte_reception', $module->carte_reception)" />
                                <x-input-error :messages="$errors->get('carte_reception')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="hub" :value="__('Hub')" />
                                <x-text-input id="hub" class="block mt-1 w-full" type="text" name="hub" :value="old('hub', $module->hub)" />
                                <x-input-error :messages="$errors->get('hub')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="driver" :value="__('Driver (IC de commande)')" />
                                <x-text-input id="driver" class="block mt-1 w-full" type="text" name="driver" :value="old('driver', $module->driver)" />
                                <x-input-error :messages="$errors->get('driver')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="shift_register" :value="__('Shift Register')" />
                                <x-text-input id="shift_register" class="block mt-1 w-full" type="text" name="shift_register" :value="old('shift_register', $module->shift_register)" />
                                <x-input-error :messages="$errors->get('shift_register')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="buffer" :value="__('Buffer')" />
                                <x-text-input id="buffer" class="block mt-1 w-full" type="text" name="buffer" :value="old('buffer', $module->buffer)" />
                                <x-input-error :messages="$errors->get('buffer')" class="mt-2" />
                            </div>
                        </div>
                        <!-- Sélection du technicien -->
                            <div>
                                <x-input-label for="technicien_id" :value="__('Assigner à un technicien')" class="text-gray-300" />
                                <select id="technicien_id" name="technicien_id" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="">-- Sélectionnez un technicien --</option>
                                    @foreach(App\Models\User::where('role', 'technicien')->get() as $technicien)
                                        <option value="{{ $technicien->id }}" {{ (old('technicien_id', $module->technicien_id) == $technicien->id) ? 'selected' : '' }}>
                                            {{ $technicien->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('technicien_id')" class="mt-2" />
                            </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('modules.show', $module) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button>
                                {{ __('Mettre à jour') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
