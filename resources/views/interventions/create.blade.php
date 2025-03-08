<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Ajouter une intervention') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="mb-6 bg-indigo-900/30 p-4 rounded-lg border border-indigo-500/30">
                        <h3 class="font-medium text-indigo-300 mb-2">Informations du module</h3>
                        <p class="text-gray-300"><span class="font-medium">Module:</span> #{{ $module->id }} - {{ $module->largeur }}×{{ $module->hauteur }} mm</p>
                        <p class="text-gray-300"><span class="font-medium">Résolution:</span> {{ $module->nb_pixels_largeur }}×{{ $module->nb_pixels_hauteur }} pixels</p>
                        <p class="text-gray-300"><span class="font-medium">Produit:</span> {{ $module->dalle->produit->marque }} {{ $module->dalle->produit->modele }}</p>
                        <p class="text-gray-300"><span class="font-medium">Chantier:</span> {{ $module->dalle->produit->chantier->nom }}</p>
                    </div>

                    <form method="POST" action="{{ route('interventions.store') }}">
                        @csrf
                        <input type="hidden" name="module_id" value="{{ $module->id }}">
                        <input type="hidden" name="temps_total" value="0"> <!-- Pour l'instant, pas de chrono -->

                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <div>
                                <h4 class="font-medium text-gray-300 mb-4">Diagnostic visuel</h4>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <x-input-label for="diagnostic_nb_leds_hs" :value="__('Nombre de LEDs HS')" class="text-gray-300" />
                                            <x-text-input id="diagnostic_nb_leds_hs" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="diagnostic_nb_leds_hs" :value="old('diagnostic_nb_leds_hs', 0)" min="0" required />
                                            <x-input-error :messages="$errors->get('diagnostic_nb_leds_hs')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="diagnostic_nb_ic_hs" :value="__('Nombre d\'ICs HS')" class="text-gray-300" />
                                            <x-text-input id="diagnostic_nb_ic_hs" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="diagnostic_nb_ic_hs" :value="old('diagnostic_nb_ic_hs', 0)" min="0" required />
                                            <x-input-error :messages="$errors->get('diagnostic_nb_ic_hs')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="diagnostic_nb_masques_hs" :value="__('Nombre de masques HS')" class="text-gray-300" />
                                            <x-text-input id="diagnostic_nb_masques_hs" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="diagnostic_nb_masques_hs" :value="old('diagnostic_nb_masques_hs', 0)" min="0" required />
                                            <x-input-error :messages="$errors->get('diagnostic_nb_masques_hs')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div>
                                        <x-input-label for="diagnostic_remarques" :value="__('Remarques')" class="text-gray-300" />
                                        <textarea id="diagnostic_remarques" name="diagnostic_remarques" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" rows="3">{{ old('diagnostic_remarques') }}</textarea>
                                        <x-input-error :messages="$errors->get('diagnostic_remarques')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-300 mb-4">Réparations effectuées</h4>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <x-input-label for="reparation_nb_leds_remplacees" :value="__('Nombre de LEDs remplacées')" class="text-gray-300" />
                                            <x-text-input id="reparation_nb_leds_remplacees" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="reparation_nb_leds_remplacees" :value="old('reparation_nb_leds_remplacees', 0)" min="0" required />
                                            <x-input-error :messages="$errors->get('reparation_nb_leds_remplacees')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="reparation_nb_ic_remplaces" :value="__('Nombre d\'ICs remplacés')" class="text-gray-300" />
                                            <x-text-input id="reparation_nb_ic_remplaces" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="reparation_nb_ic_remplaces" :value="old('reparation_nb_ic_remplaces', 0)" min="0" required />
                                            <x-input-error :messages="$errors->get('reparation_nb_ic_remplaces')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="reparation_nb_masques_remplaces" :value="__('Nombre de masques remplacés')" class="text-gray-300" />
                                            <x-text-input id="reparation_nb_masques_remplaces" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="reparation_nb_masques_remplaces" :value="old('reparation_nb_masques_remplaces', 0)" min="0" required />
                                            <x-input-error :messages="$errors->get('reparation_nb_masques_remplaces')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div>
                                        <x-input-label for="reparation_remarques" :value="__('Remarques')" class="text-gray-300" />
                                        <textarea id="reparation_remarques" name="reparation_remarques" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" rows="3">{{ old('reparation_remarques') }}</textarea>
                                        <x-input-error :messages="$errors->get('reparation_remarques')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('modules.show', $module) }}" class="btn-action btn-secondary flex items-center mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Enregistrer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>