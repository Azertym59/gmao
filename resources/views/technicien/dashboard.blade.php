<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tableau de bord technicien') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Statistiques générales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Modules réparés -->
                <div class="glassmorphism rounded-xl p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-medium text-white">Modules réparés</h3>
                        <div class="p-2 bg-green-500/20 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-baseline space-x-1">
                        <span class="text-3xl font-bold text-white">{{ $statsModules['termines'] }}</span>
                        <span class="text-sm text-gray-400">/ {{ $statsModules['total'] }}</span>
                    </div>
                    <div class="mt-2">
                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                            <span>Taux de réussite</span>
                            <span>{{ $statsModules['pourcentage_succes'] }}%</span>
                        </div>
                        <div class="h-2 w-full bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full" style="width: {{ $statsModules['pourcentage_succes'] }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Composants remplacés -->
                <div class="glassmorphism rounded-xl p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-medium text-white">Composants</h3>
                        <div class="p-2 bg-blue-500/20 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-white">{{ $statsComposants['total'] }}</div>
                    <div class="mt-2 space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-blue-400">LEDs:</span>
                            <span class="text-white">{{ $statsComposants['leds'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-purple-400">ICs:</span>
                            <span class="text-white">{{ $statsComposants['ics'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-yellow-400">Masques:</span>
                            <span class="text-white">{{ $statsComposants['masques'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Temps total -->
                <div class="glassmorphism rounded-xl p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-medium text-white">Temps total</h3>
                        <div class="p-2 bg-purple-500/20 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-white">{{ $statsTempsTotaux['temps_formate'] }}</div>
                    <div class="mt-2 text-sm text-gray-400">
                        <span>{{ $statsTempsTotaux['nb_interventions'] }} interventions réalisées</span>
                    </div>
                </div>

                <!-- Temps moyen par module -->
                <div class="glassmorphism rounded-xl p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-medium text-white">Temps moyen</h3>
                        <div class="p-2 bg-yellow-500/20 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-white">{{ $tempsMoyenParModule['temps_formate'] }}</div>
                    <div class="mt-2 text-sm text-gray-400">
                        <span>Par module traité</span>
                    </div>
                </div>
            </div>

            <!-- Chantiers et modules en cours -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Chantiers en cours -->
                <div class="glassmorphism rounded-xl border border-gray-700">
                    <div class="border-b border-gray-700 px-6 py-3">
                        <h3 class="font-medium text-lg text-white">Derniers chantiers en cours</h3>
                    </div>
                    <div class="p-6">
                        @if($chantiersEnCours->count() > 0)
                            <div class="space-y-4">
                                @foreach($chantiersEnCours as $chantier)
                                    <div class="flex items-center p-3 bg-gray-800/50 rounded-lg">
                                        <div class="flex-1">
                                            <a href="{{ route('chantiers.show', $chantier) }}" class="font-medium text-white hover:text-accent-blue">
                                                {{ $chantier->nom }}
                                            </a>
                                            <div class="text-sm text-gray-400">
                                                {{ $chantier->client->nom_complet }}
                                            </div>
                                            <div class="mt-2">
                                                <div class="flex justify-between text-xs text-gray-400 mb-1">
                                                    <span>Progression</span>
                                                    <span>{{ $chantier->progression }}%</span>
                                                </div>
                                                <div class="h-1.5 w-full bg-gray-700 rounded-full overflow-hidden">
                                                    <div class="h-full bg-accent-blue rounded-full" style="width: {{ $chantier->progression }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('chantiers.show', $chantier) }}" class="btn-action btn-primary">
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p>Aucun chantier en cours actuellement.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Modules en cours de réparation -->
                <div class="glassmorphism rounded-xl border border-gray-700">
                    <div class="border-b border-gray-700 px-6 py-3">
                        <h3 class="font-medium text-lg text-white">Modules en cours de réparation</h3>
                    </div>
                    <div class="p-6">
                        @if($modulesEnCours->count() > 0)
                            <div class="space-y-4">
                                @foreach($modulesEnCours as $module)
                                    <div class="p-3 bg-gray-800/50 rounded-lg">
                                        <div class="flex justify-between">
                                            <div>
                                                <a href="{{ route('modules.show', $module) }}" class="font-medium text-white hover:text-accent-yellow">
                                                    Module #{{ $module->id }}
                                                </a>
                                                <div class="text-sm text-gray-400 mt-1">
                                                    <span>{{ $module->reference_module ?? 'Sans référence' }}</span>
                                                </div>
                                            </div>
                                            @if($module->interventions->where('is_completed', false)->first())
                                                <div>
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-500/20 text-yellow-400">
                                                        <span class="animate-pulse mr-1">⬤</span>
                                                        <span>En intervention</span>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-2 text-sm">
                                            <span class="text-gray-400">Chantier: </span>
                                            <a href="{{ route('chantiers.show', $module->dalle->produit->chantier) }}" class="text-accent-blue hover:text-blue-400">
                                                {{ $module->dalle->produit->chantier->nom }}
                                            </a>
                                        </div>
                                        <div class="flex justify-end mt-2">
                                            <a href="{{ route('modules.show', $module) }}" class="btn-action btn-secondary text-xs">
                                                Détails
                                            </a>
                                            @if(!$module->interventions->where('is_completed', false)->first())
                                                <a href="{{ route('interventions.create', ['module_id' => $module->id]) }}" class="btn-action btn-primary text-xs ml-2">
                                                    Intervenir
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                <p>Aucun module en cours de réparation actuellement.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Chantiers terminés et Interventions récentes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Chantiers terminés -->
                <div class="glassmorphism rounded-xl border border-gray-700">
                    <div class="border-b border-gray-700 px-6 py-3">
                        <h3 class="font-medium text-lg text-white">Derniers chantiers terminés</h3>
                    </div>
                    <div class="p-6">
                        @if($chantiersTermines->count() > 0)
                            <div class="space-y-4">
                                @foreach($chantiersTermines as $chantier)
                                    <div class="flex items-center p-3 bg-gray-800/50 rounded-lg">
                                        <div class="flex-1">
                                            <a href="{{ route('chantiers.show', $chantier) }}" class="font-medium text-white hover:text-accent-green">
                                                {{ $chantier->nom }}
                                            </a>
                                            <div class="text-sm text-gray-400">
                                                {{ $chantier->client->nom_complet }}
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                Terminé le {{ $chantier->updated_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <span class="badge badge-success">Terminé</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p>Aucun chantier terminé récemment.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Dernières interventions -->
                <div class="glassmorphism rounded-xl border border-gray-700">
                    <div class="border-b border-gray-700 px-6 py-3">
                        <h3 class="font-medium text-lg text-white">Vos dernières interventions</h3>
                    </div>
                    <div class="p-6">
                        @if($interventionsRecentes->count() > 0)
                            <div class="space-y-4">
                                @foreach($interventionsRecentes as $intervention)
                                    <div class="p-3 bg-gray-800/50 rounded-lg">
                                        <div class="flex justify-between">
                                            <div>
                                                <span class="font-medium text-white">
                                                    Intervention #{{ $intervention->id }}
                                                </span>
                                                @if($intervention->is_completed)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-500/20 text-green-400">
                                                        Terminée
                                                    </span>
                                                @else
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-500/20 text-yellow-400">
                                                        En cours
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-400">
                                                {{ $intervention->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                        <div class="mt-1 text-sm">
                                            <span class="text-gray-400">Module: </span>
                                            <a href="{{ route('modules.show', $intervention->module) }}" class="text-accent-yellow hover:text-yellow-400">
                                                {{ $intervention->module->reference_module ?? 'Module #' . $intervention->module->id }}
                                            </a>
                                        </div>
                                        <div class="mt-1 text-sm">
                                            <span class="text-gray-400">Chantier: </span>
                                            <a href="{{ route('chantiers.show', $intervention->module->dalle->produit->chantier) }}" class="text-accent-blue hover:text-blue-400">
                                                {{ $intervention->module->dalle->produit->chantier->nom }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <p>Aucune intervention récente.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>