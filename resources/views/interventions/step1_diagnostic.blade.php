<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Intervention en cours') }}
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
                                    <!-- Pas de coche verte, nous sommes à cette étape -->
                                </div>
                                <div class="text-gray-300 font-bold">Diagnostic</div>
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
                                    <div class="w-10 h-10 mx-auto rounded-full text-lg flex items-center justify-center bg-gray-600 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    </div>
                                    <!-- Pas de coche verte, étape non réalisée -->
                                </div>
                                <div class="text-gray-400">Réparation</div>
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

                    <form id="diagnostic-form" method="POST" action="{{ route('interventions.store.diagnostic', $intervention->id) }}">
                        @csrf
                        <input type="hidden" name="intervention_id" value="{{ $intervention->id }}">
                        <input type="hidden" id="temps_total" name="temps_total" value="0">

                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <div>
                                <h4 class="font-medium text-gray-300 mb-4">Diagnostic visuel</h4>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <x-input-label for="diagnostic_nb_leds_hs" :value="__('Nombre de LEDs HS')" class="text-gray-300" />
                                            <input id="diagnostic_nb_leds_hs" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="diagnostic_nb_leds_hs" value="{{ old('diagnostic_nb_leds_hs', 0) }}" min="0" required />
                                            <x-input-error :messages="$errors->get('diagnostic_nb_leds_hs')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="diagnostic_nb_ic_hs" :value="__('Nombre d\'ICs HS')" class="text-gray-300" />
                                            <input id="diagnostic_nb_ic_hs" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="diagnostic_nb_ic_hs" value="{{ old('diagnostic_nb_ic_hs', 0) }}" min="0" required />
                                            <x-input-error :messages="$errors->get('diagnostic_nb_ic_hs')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="diagnostic_nb_masques_hs" :value="__('Nombre de masques HS')" class="text-gray-300" />
                                            <input id="diagnostic_nb_masques_hs" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="diagnostic_nb_masques_hs" value="{{ old('diagnostic_nb_masques_hs', 0) }}" min="0" required />
                                            <x-input-error :messages="$errors->get('diagnostic_nb_masques_hs')" class="mt-2" />
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="inline-flex items-center text-gray-300">
                                            <input type="checkbox" id="diagnostic_pose_fake_pcb" name="diagnostic_pose_fake_pcb" value="1" class="rounded bg-gray-700 border-gray-600 text-indigo-600 focus:ring-indigo-500" 
                                                {{ old('diagnostic_pose_fake_pcb') ? 'checked' : '' }}>
                                            <span class="ml-2">Pose de Fake PCB nécessaire</span>
                                        </label>
                                    </div>

                                    <div>
                                        <x-input-label for="diagnostic_remarques" :value="__('Remarques (description du problème)')" class="text-gray-300" />
                                        <textarea id="diagnostic_remarques" name="diagnostic_remarques" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" rows="3">{{ old('diagnostic_remarques') }}</textarea>
                                        <x-input-error :messages="$errors->get('diagnostic_remarques')" class="mt-2" />
                                    </div>
                                    <!-- Champs supprimés car non existants dans la base de données -->
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button type="submit" class="bg-blue-600 hover:bg-blue-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('Passer à la réparation') }}
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
            const btnTerminer = document.getElementById('btn-terminer');
            const tempsTotal = document.getElementById('temps_total');
            
            let secondes = 0;
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
            
            // Démarrer le chronomètre au chargement de la page
            demarrerChronometre();
            
            // S'assurer que le temps total est à jour lors de la soumission du formulaire
            document.getElementById('diagnostic-form').addEventListener('submit', function() {
                tempsTotal.value = secondes;
            });
        });
    </script>
</x-app-layout>