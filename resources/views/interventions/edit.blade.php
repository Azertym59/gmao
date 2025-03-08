<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Modifier l\'intervention') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <!-- Informations du module -->
                    <div class="mb-6 bg-indigo-900/30 p-4 rounded-lg border border-indigo-500/30">
                        <h3 class="font-medium text-indigo-300 mb-2">Module #{{ $intervention->module->id }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">Dimensions:</span> {{ $intervention->module->largeur }}×{{ $intervention->module->hauteur }} mm</p>
                                <p class="text-gray-300"><span class="font-semibold">Résolution:</span> {{ $intervention->module->nb_pixels_largeur }}×{{ $intervention->module->nb_pixels_hauteur }} pixels</p>
                            </div>
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">Produit:</span> {{ $intervention->module->dalle->produit->marque }} {{ $intervention->module->dalle->produit->modele }}</p>
                                <p class="text-gray-300"><span class="font-semibold">Dalle:</span> #{{ $intervention->module->dalle->id }}</p>
                            </div>
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">Chantier:</span> {{ $intervention->module->dalle->produit->chantier->nom }}</p>
                                <p class="text-gray-300"><span class="font-semibold">Client:</span> {{ $intervention->module->dalle->produit->chantier->client->nom_complet }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chronomètre pour continuer l'intervention -->
                    <div class="mb-8 p-6 glassmorphism rounded-lg text-center border border-gray-700">
                        <h3 class="text-xl font-bold mb-4 text-white">Temps d'intervention</h3>
                        <div id="chronometre" class="text-5xl font-mono mb-6 text-accent-yellow">
                            @php
                                $heures = floor($intervention->temps_total / 3600);
                                $minutes = floor(($intervention->temps_total % 3600) / 60);
                                $secondes = $intervention->temps_total % 60;
                                echo sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
                            @endphp
                        </div>
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

                    <form method="POST" action="{{ route('interventions.update', $intervention) }}" id="intervention-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="temps_total" name="temps_total" value="{{ $intervention->temps_total }}">

                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <!-- État du module -->
                            <div>
                                <h4 class="font-medium text-gray-300 mb-4">État du module</h4>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                    <x-input-label for="module_etat" :value="__('État')" class="text-gray-300" />
                                    <select id="module_etat" name="module_etat" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50">
                                        <option value="non_commence" {{ $intervention->module->etat == 'non_commence' ? 'selected' : '' }}>Non commencé</option>
                                        <option value="en_cours" {{ $intervention->module->etat == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="defaillant" {{ $intervention->module->etat == 'defaillant' ? 'selected' : '' }}>Défaillant</option>
                                        <option value="termine" {{ $intervention->module->etat == 'termine' ? 'selected' : '' }}>Terminé</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('module_etat')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-300 mb-4">Diagnostic visuel</h4>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <x-input-label for="diagnostic_nb_leds_hs" :value="__('Nombre de LEDs HS')" class="text-gray-300" />
                                            <x-text-input id="diagnostic_nb_leds_hs" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="diagnostic_nb_leds_hs" :value="old('diagnostic_nb_leds_hs', $intervention->diagnostic->nb_leds_hs)" min="0" required />
                                            <x-input-error :messages="$errors->get('diagnostic_nb_leds_hs')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="diagnostic_nb_ic_hs" :value="__('Nombre d\'ICs HS')" class="text-gray-300" />
                                            <x-text-input id="diagnostic_nb_ic_hs" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="diagnostic_nb_ic_hs" :value="old('diagnostic_nb_ic_hs', $intervention->diagnostic->nb_ic_hs)" min="0" required />
                                            <x-input-error :messages="$errors->get('diagnostic_nb_ic_hs')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="diagnostic_nb_masques_hs" :value="__('Nombre de masques HS')" class="text-gray-300" />
                                            <x-text-input id="diagnostic_nb_masques_hs" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="diagnostic_nb_masques_hs" :value="old('diagnostic_nb_masques_hs', $intervention->diagnostic->nb_masques_hs)" min="0" required />
                                            <x-input-error :messages="$errors->get('diagnostic_nb_masques_hs')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div>
                                        <x-input-label for="diagnostic_remarques" :value="__('Remarques')" class="text-gray-300" />
                                        <textarea id="diagnostic_remarques" name="diagnostic_remarques" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" rows="3">{{ old('diagnostic_remarques', $intervention->diagnostic->remarques) }}</textarea>
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
                                            <x-text-input id="reparation_nb_leds_remplacees" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="reparation_nb_leds_remplacees" :value="old('reparation_nb_leds_remplacees', $intervention->reparation->nb_leds_remplacees)" min="0" required />
                                            <x-input-error :messages="$errors->get('reparation_nb_leds_remplacees')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="reparation_nb_ic_remplaces" :value="__('Nombre d\'ICs remplacés')" class="text-gray-300" />
                                            <x-text-input id="reparation_nb_ic_remplaces" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="reparation_nb_ic_remplaces" :value="old('reparation_nb_ic_remplaces', $intervention->reparation->nb_ic_remplaces)" min="0" required />
                                            <x-input-error :messages="$errors->get('reparation_nb_ic_remplaces')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="reparation_nb_masques_remplaces" :value="__('Nombre de masques remplacés')" class="text-gray-300" />
                                            <x-text-input id="reparation_nb_masques_remplaces" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="reparation_nb_masques_remplaces" :value="old('reparation_nb_masques_remplaces', $intervention->reparation->nb_masques_remplaces)" min="0" required />
                                            <x-input-error :messages="$errors->get('reparation_nb_masques_remplaces')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div>
                                        <x-input-label for="reparation_remarques" :value="__('Remarques')" class="text-gray-300" />
                                        <textarea id="reparation_remarques" name="reparation_remarques" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" rows="3">{{ old('reparation_remarques', $intervention->reparation->remarques) }}</textarea>
                                        <x-input-error :messages="$errors->get('reparation_remarques')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('interventions.show', $intervention) }}" class="btn-action btn-secondary flex items-center mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Mettre à jour') }}
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
            
            let secondes = {{ $intervention->temps_total }}; // Initialiser avec le temps actuel
            let interval;
            let enPause = true; // On commence en pause si on édite
            
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
                enPause = false;
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
            btnPause.addEventListener('click', function(e) {
                e.preventDefault();
                mettreEnPause();
            });
            
            btnResume.addEventListener('click', function(e) {
                e.preventDefault();
                reprendreChronometre();
            });
            
            // Initialisation - afficher correctement les boutons
            btnPause.classList.add('hidden');
            btnResume.classList.remove('hidden');
            
            // S'assurer que le temps total est à jour lors de la soumission du formulaire
            document.getElementById('intervention-form').addEventListener('submit', function() {
                tempsTotal.value = secondes;
            });
        });
    </script>
</x-app-layout>