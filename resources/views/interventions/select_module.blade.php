<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Sélectionner un module') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-white">Sélectionnez un module pour ajouter une intervention</h3>

                    <!-- Filtres -->
                    <div class="mb-6 p-4 glassmorphism rounded-lg border border-gray-700">
                        <h4 class="font-medium text-white mb-2">Filtres</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <select id="filter-etat" class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50">
                                    <option value="">Tous les états</option>
                                    <option value="non_commence">Non commencé</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="defaillant">Défaillant</option>
                                    <option value="termine">Terminé</option>
                                </select>
                            </div>
                            <div>
                                <input type="text" id="filter-search" placeholder="Rechercher..." class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse ($modules as $module)
                            @php 
                                // IMPORTANTE DOUBLE VÉRIFICATION
                                $activeIntervention = $module->getActiveIntervention();
                                $isOccupe = $activeIntervention || $module->est_occupe;
                                if ($activeIntervention) {
                                    $technicien = App\Models\User::find($activeIntervention->technicien_id);
                                    $activeTechName = $technicien ? $technicien->name : 'Inconnu';
                                } else {
                                    $activeTechName = null;
                                }
                            @endphp
                            <div class="glassmorphism p-4 rounded-lg border border-gray-700 hover:bg-gray-700/30 transition duration-200
                                {{ $module->etat == 'defaillant' ? 'border-red-500/50 bg-red-900/20' : '' }}
                                {{ $module->etat == 'termine' ? 'border-green-500/50 bg-green-900/20' : '' }}
                                {{ $isOccupe ? 'module-busy' : '' }}"
                                @if($isOccupe) 
                                    title="MODULE OCCUPÉ{{ $activeTechName ? ' - En intervention par: '.$activeTechName : '' }}"
                                @endif>
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-white">Module #{{ $module->id }}</h4>
                                    @if($module->etat == 'non_commence')
                                        <span class="badge badge-info">Non commencé</span>
                                    @elseif($module->etat == 'en_cours')
                                        <span class="badge badge-warning">En cours</span>
                                    @elseif($module->etat == 'defaillant')
                                        <span class="badge badge-danger">Défaillant</span>
                                    @else
                                        <span class="badge badge-success">Terminé</span>
                                    @endif
                                </div>

                                <div class="text-sm mb-3 text-gray-300">
                                    <p><span class="font-medium">Dimensions:</span> {{ $module->largeur }}×{{ $module->hauteur }} mm</p>
                                    <p><span class="font-medium">Résolution:</span> {{ $module->nb_pixels_largeur }}×{{ $module->nb_pixels_hauteur }} px</p>
                                    <p><span class="font-medium">Produit:</span> {{ $module->dalle->produit->marque }} {{ $module->dalle->produit->modele }}</p>
                                    <p><span class="font-medium">Chantier:</span> {{ $module->dalle->produit->chantier->nom }}</p>
                                </div>

                                <div class="mt-4">
                                    @if($isOccupe)
                                        <div class="flex flex-col">
                                            <div class="bg-orange-600 text-white text-sm p-3 rounded-lg mb-2 font-bold flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                @if($activeTechName)
                                                    MODULE OCCUPÉ - Intervention en cours par <strong class="ml-1 text-amber-300">{{ $activeTechName }}</strong>
                                                @else
                                                    MODULE OCCUPÉ - Intervention en cours
                                                @endif
                                            </div>
                                            
                                            @if($activeIntervention)
                                                <a href="{{ route('interventions.diagnostic', $activeIntervention) }}" 
                                                   class="btn-action btn-warning flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Voir l'intervention en cours
                                                </a>
                                            @else
                                                <a href="{{ route('interventions.create', $module->id) }}" 
                                                   class="btn-action btn-warning flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Vérifier le statut du module
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <a href="{{ route('interventions.create', $module->id) }}" 
                                           class="btn-action btn-primary flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Créer une intervention
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8 glassmorphism rounded-lg border border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <p class="text-gray-400 mb-4">Aucun module trouvé. Vous devez d'abord créer un module.</p>
                                <a href="{{ route('dalles.index') }}" 
                                   class="btn-action btn-primary inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Créer un module
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>