<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Réparation du module') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <!-- Barre de progression de l'intervention -->
                    <div class="mb-6">
                        <div class="flex mb-4">
                            <div class="w-1/3 text-center">
                                <div class="relative mb-2">
                                    <div class="w-10 h-10 mx-auto rounded-full text-lg flex items-center justify-center bg-blue-500 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div class="absolute top-0 right-0 -mr-2">
                                        <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-gray-300">Diagnostic</div>
                            </div>
                            <div class="w-1/3 text-center">
                                <div class="relative mb-2">
                                    <div class="absolute top-0 -ml-10 text-center mt-4 w-32">
                                        <div class="flex items-center justify-center">
                                            <div class="w-full bg-gray-600 rounded items-center align-middle">
                                                <div class="bg-blue-500 h-1 rounded" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 mx-auto rounded-full text-lg flex items-center justify-center bg-blue-500 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    </div>
                                    <!-- Pas de coche verte, nous sommes à cette étape -->
                                </div>
                                <div class="text-gray-300 font-bold">Réparation</div>
                            </div>
                            <div class="w-1/3 text-center">
                                <div class="relative mb-2">
                                    <div class="absolute top-0 -ml-10 text-center mt-4 w-32">
                                        <div class="flex items-center justify-center">
                                            <div class="w-full bg-gray-600 rounded items-center align-middle">
                                                <div class="bg-blue-500 h-1 rounded" style="width: 0%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 mx-auto rounded-full text-lg flex items-center justify-center bg-gray-600 text-white opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-gray-500">Finalisation</div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations du module -->
                    <div class="mb-6 bg-indigo-900/30 p-4 rounded-lg border border-indigo-500/30">
                        <h3 class="font-medium text-indigo-300 mb-2">Module #{{ $module->id }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">Dimensions:</span> {{ $module->largeur }}×{{ $module->hauteur }} mm</p>
                                <p class="text-gray-300"><span class="font-semibold">Résolution:</span> {{ $module->nb_pixels_largeur }}×{{ $module->nb_pixels_hauteur }} pixels</p>
                            </div>
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">Produit:</span> {{ $module->dalle->produit->marque }} {{ $module->dalle->produit->modele }}</p>
                                <p class="text-gray-300"><span class="font-semibold">Dalle:</span> #{{ $module->dalle->id }}</p>
                            </div>
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">Chantier:</span> {{ $module->dalle->produit->chantier->nom }}</p>
                                <p class="text-gray-300"><span class="font-semibold">Client:</span> {{ $module->dalle->produit->chantier->client->nom_complet }}</p>
                            </div>
                        </div>
                        
                        @if($module->dalle->produit->ledDatasheet)
                        <div class="mt-4 pt-4 border-t border-indigo-500/30 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-indigo-300 mb-2">LED Datasheet</h4>
                                <p class="text-gray-300"><span class="font-semibold">Type:</span> {{ $module->dalle->produit->ledDatasheet->type }} {{ $module->dalle->produit->ledDatasheet->reference }}</p>
                                <p class="text-gray-300"><span class="font-semibold">Pôles:</span> {{ $module->dalle->produit->ledDatasheet->nb_poles }}</p>
                                <p class="text-gray-300"><span class="font-semibold">Notes:</span> {{ $module->dalle->produit->ledDatasheet->notes }}</p>
                            </div>
                            @if($module->dalle->produit->ledDatasheet->image_data)
                            <div class="flex justify-center">
                                <div class="bg-white p-2 rounded">
                                    <img src="{{ $module->dalle->produit->ledDatasheet->image_data }}" alt="LED Datasheet" class="h-60 w-auto">
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Résumé du diagnostic -->
                    <div id="diagnostic-summary" class="mb-6 bg-blue-900/20 p-4 rounded-lg border border-blue-500/20">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-medium text-blue-300">Diagnostic effectué</h3>
                            <button id="replace-all-btn" type="button" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m-8 5H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                Remplacer tout
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-3 bg-gray-800/50 rounded-lg border border-blue-500/30">
                                <div class="text-2xl text-blue-400 font-bold leds-hs-value">{{ $diagnostic->nb_leds_hs }}</div>
                                <p class="text-gray-300 text-sm">LEDs défectueuses</p>
                            </div>
                            <div class="p-3 bg-gray-800/50 rounded-lg border border-purple-500/30">
                                <div class="text-2xl text-purple-400 font-bold ics-hs-value">{{ $diagnostic->nb_ic_hs }}</div>
                                <p class="text-gray-300 text-sm">ICs défectueux</p>
                            </div>
                            <div class="p-3 bg-gray-800/50 rounded-lg border border-amber-500/30">
                                <div class="text-2xl text-amber-400 font-bold masques-hs-value">{{ $diagnostic->nb_masques_hs }}</div>
                                <p class="text-gray-300 text-sm">Masques défectueux</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                            <div class="p-3 bg-gray-800/50 rounded-lg border border-gray-700">
                                <div class="flex justify-between">
                                    <span class="text-gray-300">Fake PCB nécessaire:</span>
                                    <span class="font-bold {{ $diagnostic->pose_fake_pcb ? 'text-red-400' : 'text-gray-400' }} fake-pcb-needed">
                                        {{ $diagnostic->pose_fake_pcb ? 'Oui' : 'Non' }}
                                    </span>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="text-gray-300">Cause identifiée:</span>
                                    <span class="font-bold text-gray-300">
                                        @if($diagnostic->cause === 'usure_normale')
                                            Usure normale
                                        @elseif($diagnostic->cause === 'choc')
                                            Choc/Impact
                                        @elseif($diagnostic->cause === 'defaut_usine')
                                            Défaut d'usine
                                        @elseif($diagnostic->cause === 'autre')
                                            Autre cause
                                        @else
                                            Non précisée
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            @if($diagnostic->remarques)
                            <div class="p-3 bg-gray-800/50 rounded-lg border border-gray-700">
                                <p class="text-gray-400 text-sm">Remarques du diagnostic:</p>
                                <p class="text-gray-300 mt-1">{{ $diagnostic->remarques }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Chronomètre -->
                    <div class="mb-8 p-6 glassmorphism rounded-lg text-center border border-gray-700">
                        <h3 class="text-xl font-bold mb-4 text-white">Temps d'intervention</h3>
                        <div id="chronometre" class="text-5xl font-mono mb-6 text-accent-yellow">00:00:00</div>
                        <div class="flex justify-center space-x-4">
                            <button id="btn-pause" class="px-6 py-3 bg-yellow-600 text-white rounded-lg shadow-lg hover:bg-yellow-700 transition-colors flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Pause
                            </button>
                            <button id="btn-resume" class="px-6 py-3 bg-green-600 text-white rounded-lg shadow-lg hover:bg-green-700 transition-colors hidden flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                </svg>
                                Reprendre
                            </button>
                        </div>
                    </div>

                    <form id="reparation-form" method="POST" action="{{ route('interventions.store.reparation', $intervention->id) }}">
                        @csrf
                        <input type="hidden" name="intervention_id" value="{{ $intervention->id }}">
                        <input type="hidden" id="temps_total" name="temps_total" value="{{ $intervention->temps_total }}">

                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <div>
                                <h4 class="font-medium text-gray-300 mb-4">Réparations effectuées</h4>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                    <!-- Compteurs visuels pour la réparation -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                        <!-- Compteur de LEDs remplacées -->
                                        <div id="leds-replaced-counter" class="bg-gray-800/80 rounded-lg p-4 text-center border border-blue-600/30 shadow">
                                            <h4 class="text-blue-400 font-semibold mb-2">LEDs Remplacées</h4>
                                            <div class="flex items-center justify-center space-x-4">
                                                <button type="button" class="decrement-btn bg-red-600 text-white h-10 w-10 rounded-full flex items-center justify-center focus:outline-none hover:bg-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <div class="counter-display text-4xl font-bold text-blue-500 w-16">
                                                    {{ old('reparation_nb_leds_remplacees', $intervention->reparation->nb_leds_remplacees ?? $diagnostic->nb_leds_hs ?? 0) }}
                                                </div>
                                                <button type="button" class="increment-btn bg-green-600 text-white h-10 w-10 rounded-full flex items-center justify-center focus:outline-none hover:bg-green-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="text-xs text-gray-400 mt-2">Appuyez sur <kbd class="bg-gray-700 px-1 py-0.5 rounded">1</kbd> pour incrémenter</div>
                                            <!-- Champ caché pour stocker la valeur -->
                                            <input id="reparation_nb_leds_remplacees" type="hidden" name="reparation_nb_leds_remplacees" value="{{ old('reparation_nb_leds_remplacees', $intervention->reparation->nb_leds_remplacees ?? $diagnostic->nb_leds_hs ?? 0) }}" />
                                        </div>

                                        <!-- Compteur d'ICs remplacés -->
                                        <div id="ics-replaced-counter" class="bg-gray-800/80 rounded-lg p-4 text-center border border-purple-600/30 shadow">
                                            <h4 class="text-purple-400 font-semibold mb-2">ICs Remplacés</h4>
                                            <div class="flex items-center justify-center space-x-4">
                                                <button type="button" class="decrement-btn bg-red-600 text-white h-10 w-10 rounded-full flex items-center justify-center focus:outline-none hover:bg-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <div class="counter-display text-4xl font-bold text-purple-500 w-16">
                                                    {{ old('reparation_nb_ic_remplaces', $intervention->reparation->nb_ic_remplaces ?? $diagnostic->nb_ic_hs ?? 0) }}
                                                </div>
                                                <button type="button" class="increment-btn bg-green-600 text-white h-10 w-10 rounded-full flex items-center justify-center focus:outline-none hover:bg-green-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="text-xs text-gray-400 mt-2">Appuyez sur <kbd class="bg-gray-700 px-1 py-0.5 rounded">2</kbd> pour incrémenter</div>
                                            <!-- Champ caché pour stocker la valeur -->
                                            <input id="reparation_nb_ic_remplaces" type="hidden" name="reparation_nb_ic_remplaces" value="{{ old('reparation_nb_ic_remplaces', $intervention->reparation->nb_ic_remplaces ?? $diagnostic->nb_ic_hs ?? 0) }}" />
                                        </div>

                                        <!-- Compteur de Masques remplacés -->
                                        <div id="masques-replaced-counter" class="bg-gray-800/80 rounded-lg p-4 text-center border border-amber-600/30 shadow">
                                            <h4 class="text-amber-400 font-semibold mb-2">Masques Remplacés</h4>
                                            <div class="flex items-center justify-center space-x-4">
                                                <button type="button" class="decrement-btn bg-red-600 text-white h-10 w-10 rounded-full flex items-center justify-center focus:outline-none hover:bg-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <div class="counter-display text-4xl font-bold text-amber-500 w-16">
                                                    {{ old('reparation_nb_masques_remplaces', $intervention->reparation->nb_masques_remplaces ?? $diagnostic->nb_masques_hs ?? 0) }}
                                                </div>
                                                <button type="button" class="increment-btn bg-green-600 text-white h-10 w-10 rounded-full flex items-center justify-center focus:outline-none hover:bg-green-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="text-xs text-gray-400 mt-2">Appuyez sur <kbd class="bg-gray-700 px-1 py-0.5 rounded">3</kbd> pour incrémenter</div>
                                            <!-- Champ caché pour stocker la valeur -->
                                            <input id="reparation_nb_masques_remplaces" type="hidden" name="reparation_nb_masques_remplaces" value="{{ old('reparation_nb_masques_remplaces', $intervention->reparation->nb_masques_remplaces ?? $diagnostic->nb_masques_hs ?? 0) }}" />
                                        </div>
                                    </div>
                                    
                                    <!-- Fake PCB -->
                                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Checkbox Fake PCB posé -->
                                        <div class="flex items-center p-3 border border-red-500/50 rounded-lg bg-red-900/20">
                                            <label class="flex items-center text-gray-300 cursor-pointer w-full">
                                                <input type="checkbox" id="reparation_fake_pcb_pose" name="reparation_fake_pcb_pose" value="1" class="h-6 w-6 rounded bg-gray-700 border-gray-600 text-red-600 focus:ring-red-500" 
                                                    {{ old('reparation_fake_pcb_pose', $intervention->reparation->fake_pcb_pose ?? $diagnostic->pose_fake_pcb ?? false) ? 'checked' : '' }}>
                                                <div class="ml-3">
                                                    <span class="block text-white font-medium">Fake PCB posé</span>
                                                    <span class="text-gray-400 text-sm">Cocher si un Fake PCB a été installé</span>
                                                </div>
                                            </label>
                                        </div>
                                        
                                        <!-- Compteur de Fake PCB -->
                                        <div id="fake-pcb-counter" class="bg-gray-800/80 rounded-lg p-4 text-center border border-red-600/30 shadow">
                                            <h4 class="text-red-400 font-semibold mb-2">Nombre de Fake PCB</h4>
                                            <div class="flex items-center justify-center space-x-4">
                                                <button type="button" class="decrement-btn bg-red-600 text-white h-10 w-10 rounded-full flex items-center justify-center focus:outline-none hover:bg-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <div class="counter-display text-4xl font-bold text-red-500 w-16">
                                                    {{ old('reparation_fake_pcb_nb', $intervention->reparation->fake_pcb_nb ?? 0) }}
                                                </div>
                                                <button type="button" class="increment-btn bg-green-600 text-white h-10 w-10 rounded-full flex items-center justify-center focus:outline-none hover:bg-green-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="text-xs text-gray-400 mt-2">Appuyez sur <kbd class="bg-gray-700 px-1 py-0.5 rounded">4</kbd> pour incrémenter</div>
                                            <!-- Champ caché pour stocker la valeur -->
                                            <input id="reparation_fake_pcb_nb" type="hidden" name="reparation_fake_pcb_nb" value="{{ old('reparation_fake_pcb_nb', $intervention->reparation->fake_pcb_nb ?? 0) }}" />
                                        </div>
                                    </div>

                                    <div>
                                        <x-input-label for="reparation_remarques" :value="__('Remarques (actions effectuées)')" class="text-gray-300" />
                                        <textarea id="reparation_remarques" name="reparation_remarques" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" rows="3">{{ old('reparation_remarques', $intervention->reparation->remarques ?? '') }}</textarea>
                                        <x-input-error :messages="$errors->get('reparation_remarques')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button type="submit" class="bg-blue-600 hover:bg-blue-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('Passer à la finalisation') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script du chronomètre -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const interventionId = {{ $intervention->id }};
            const chronometre = document.getElementById('chronometre');
            const btnPause = document.getElementById('btn-pause');
            const btnResume = document.getElementById('btn-resume');
            const tempsTotal = document.getElementById('temps_total');
            
            let secondes = {{ $intervention->temps_total }};
            let interval;
            let enPause = false;
            
            // Fonction pour formater le temps
            function formatTemps(totalSecondes) {
                const heures = Math.floor(totalSecondes / 3600);
                const minutes = Math.floor((totalSecondes % 3600) / 60);
                const secondes = totalSecondes % 60;
                
                return `${String(heures).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secondes).padStart(2, '0')}`;
            }
            
            // Fonction pour mettre à jour l'affichage du chronomètre
            function mettreAJourChronometre() {
                secondes++;
                chronometre.textContent = formatTemps(secondes);
                tempsTotal.value = secondes;
            }
            
            // Démarrer le chronomètre
            function demarrerChronometre() {
                interval = setInterval(mettreAJourChronometre, 1000);
            }
            
            // Mettre en pause le chronomètre
            function mettreEnPause() {
                clearInterval(interval);
                enPause = true;
                btnPause.classList.add('hidden');
                btnResume.classList.remove('hidden');
                
                // Appel Ajax pour enregistrer la pause
                fetch(`/interventions/${interventionId}/pause`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        secondes = data.temps_total;
                        chronometre.textContent = data.temps_formate;
                        tempsTotal.value = secondes;
                    }
                })
                .catch(error => console.error('Erreur:', error));
            }
            
            // Reprendre le chronomètre
            function reprendreChronometre() {
                demarrerChronometre();
                enPause = false;
                btnResume.classList.add('hidden');
                btnPause.classList.remove('hidden');
                
                // Appel Ajax pour enregistrer la reprise
                fetch(`/interventions/${interventionId}/resume`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reprise réussie
                    }
                })
                .catch(error => console.error('Erreur:', error));
            }
            
            // Événements des boutons
            btnPause.addEventListener('click', mettreEnPause);
            btnResume.addEventListener('click', reprendreChronometre);
            
            // Initialiser l'affichage
            chronometre.textContent = formatTemps(secondes);
            
            // Démarrer le chronomètre au chargement de la page
            demarrerChronometre();
            
            // S'assurer que le temps total est à jour lors de la soumission du formulaire
            document.getElementById('reparation-form').addEventListener('submit', function(e) {
                // Assurer que le temps total est à jour
                tempsTotal.value = secondes;
                
                // Le reste de la validation se fait maintenant dans intervention-counters.js
            });
            
            // Toggle datasheet
            const toggleDatasheetBtn = document.getElementById('toggle-datasheet');
            const datasheetContent = document.getElementById('datasheet-content');
            
            if (toggleDatasheetBtn && datasheetContent) {
                toggleDatasheetBtn.addEventListener('click', function() {
                    datasheetContent.classList.toggle('hidden');
                    const isHidden = datasheetContent.classList.contains('hidden');
                    
                    toggleDatasheetBtn.innerHTML = isHidden 
                        ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                           </svg> Afficher`
                        : `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                           </svg> Masquer`;
                });
            }
        });
    </script>
    
    <!-- Inclure notre script de compteurs -->
    <script src="{{ asset('js/intervention-counters.js') }}"></script>
</x-app-layout>