<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Création de chantier - Étape 4/5 : Planification des interventions') }}
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
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="ml-2 font-medium text-green-500">Chantier</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-green-500 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="ml-2 font-medium text-green-500">Produit</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-accent-blue self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-accent-blue text-white font-bold">4</div>
                        <span class="ml-2 font-medium text-white">Interventions</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-600 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-gray-400 font-bold">5</div>
                        <span class="ml-2 font-medium text-gray-400">Rapports</span>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Planification des interventions</h3>
                    
                    <form method="POST" action="{{ route('chantiers.store.step4') }}">
                        @csrf
                        
                        <div class="mb-6 bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                            <p class="text-blue-300 mb-2">À cette étape, vous pouvez planifier des interventions préliminaires pour les modules qui seront créés.</p>
                            <p class="text-blue-300">Les interventions permettent de suivre le travail de réparation effectué sur chaque module et de générer des rapports détaillés.</p>
                        </div>
                        
                        <div class="mb-6">
                            <div>
                                <label for="planifier_interventions" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50">
                                    <input type="hidden" name="planifier_interventions" value="0">
                                    <input type="checkbox" name="planifier_interventions" id="planifier_interventions" value="1" class="mr-2 accent-accent-blue" onchange="toggleInterventionOptions()">
                                    <div>
                                        <span class="font-medium text-white">Planifier des interventions</span>
                                        <p class="text-sm text-gray-400">Créer automatiquement des interventions pour tous les modules avec ou sans technicien assigné</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div id="intervention_options" class="mb-6 p-4 border border-gray-700 rounded-xl bg-gray-800/30" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="technicien_id" :value="__('Technicien assigné (optionnel)')" class="text-gray-300" />
                                    <select id="technicien_id" name="technicien_id" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="">Aucun technicien assigné</option>
                                        @foreach($techniciens as $technicien)
                                            <option value="{{ $technicien->id }}">{{ $technicien->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('technicien_id')" class="mt-2" />
                                    <p class="text-sm text-blue-300 mt-1">Vous pouvez laisser ce champ vide si plusieurs techniciens travailleront sur ce chantier.</p>
                                </div>
                                
                                <div>
                                    <x-input-label for="date_debut_interventions" :value="__('Date de début')" class="text-gray-300" />
                                    <x-text-input id="date_debut_interventions" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="date" name="date_debut_interventions" :value="old('date_debut_interventions', date('Y-m-d'))" />
                                    <x-input-error :messages="$errors->get('date_debut_interventions')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-8">
                            <a href="{{ route('chantiers.create.step3') }}" class="btn-action btn-secondary">
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

    <script>
        function toggleInterventionOptions() {
            const interventionOptions = document.getElementById('intervention_options');
            const checkbox = document.getElementById('planifier_interventions');
            
            if (checkbox.checked) {
                interventionOptions.style.display = 'block';
                // Technicien n'est plus requis
                document.getElementById('date_debut_interventions').setAttribute('required', 'required');
            } else {
                interventionOptions.style.display = 'none';
                document.getElementById('technicien_id').removeAttribute('required');
                document.getElementById('date_debut_interventions').removeAttribute('required');
            }
        }
        
        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            toggleInterventionOptions();
        });
    </script>
</x-app-layout>