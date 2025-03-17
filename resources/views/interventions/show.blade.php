<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Détails de l\'intervention') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-semibold text-white">Intervention #{{ $intervention->id }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('interventions.edit', $intervention) }}" class="btn-action btn-primary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Modifier') }}
                            </a>
                            <a href="{{ route('modules.show', $intervention->module) }}" class="btn-action btn-secondary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                {{ __('Retour au module') }}
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Informations générales</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Date:</span> <span class="text-gray-400">{{ $intervention->date_debut->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Technicien:</span> <span class="text-gray-400">{{ $intervention->technicien ? $intervention->technicien->name : "Non assigné" }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Durée:</span>
                                    <span class="text-gray-400">
                                    @php
                                        $heures = floor($intervention->temps_total / 3600);
                                        $minutes = floor(($intervention->temps_total % 3600) / 60);
                                        $secondes = $intervention->temps_total % 60;
                                        echo sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
                                    @endphp
                                    </span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-300">État:</span> 
                                    @if($intervention->is_completed)
                                        <span class="badge badge-success">Terminée</span>
                                    @else
                                        <span class="badge badge-warning">En cours</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Module</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Module:</span>
                                    <a href="{{ route('modules.show', $intervention->module) }}" class="text-indigo-400 hover:text-indigo-300 hover:underline">
                                        Module #{{ $intervention->module->id }}
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Produit:</span>
                                    <span class="text-gray-400">{{ $intervention->module->dalle->produit->marque }} {{ $intervention->module->dalle->produit->modele }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Chantier:</span>
                                    <span class="text-gray-400">{{ $intervention->module->dalle->produit->chantier->nom }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-300">Client:</span>
                                    <span class="text-gray-400">{{ $intervention->module->dalle->produit->chantier->client->nom_complet }}</span>
                                    @if($intervention->module->dalle->produit->chantier->client->societe)
                                        <span class="text-gray-500">({{ $intervention->module->dalle->produit->chantier->client->societe }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($intervention->diagnostic)
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Diagnostic</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">LEDs HS:</span> <span class="text-gray-400">{{ $intervention->diagnostic->nb_leds_hs }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">ICs HS:</span> <span class="text-gray-400">{{ $intervention->diagnostic->nb_ic_hs }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Masques HS:</span> <span class="text-gray-400">{{ $intervention->diagnostic->nb_masques_hs }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Fake PCB nécessaire:</span> 
                                    <span class="text-gray-400">{{ $intervention->diagnostic->pose_fake_pcb ? 'Oui' : 'Non' }}</span>
                                </div>
                                @if($intervention->diagnostic->remarques)
                                <div>
                                    <span class="font-semibold text-gray-300">Remarques:</span><br>
                                    <span class="text-gray-400">{{ $intervention->diagnostic->remarques }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($intervention->reparation)
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Réparation</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">LEDs remplacées:</span> <span class="text-gray-400">{{ $intervention->reparation->nb_leds_remplacees }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">ICs remplacés:</span> <span class="text-gray-400">{{ $intervention->reparation->nb_ic_remplaces }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Masques remplacés:</span> <span class="text-gray-400">{{ $intervention->reparation->nb_masques_remplaces }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Fake PCB posé:</span> 
                                    <span class="text-gray-400">{{ $intervention->reparation->fake_pcb_pose ? 'Oui' : 'Non' }}</span>
                                </div>
                                @if($intervention->reparation->remarques)
                                <div>
                                    <span class="font-semibold text-gray-300">Remarques:</span><br>
                                    <span class="text-gray-400">{{ $intervention->reparation->remarques }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>