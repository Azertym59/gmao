<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tableau de bord technicien') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Menu d'accès rapide -->
            <div class="grid grid-cols-3 md:grid-cols-5 gap-4 mb-6">
                <a href="{{ route('modules.index') }}" class="glassmorphism-static flex flex-col items-center p-4 rounded-2xl border border-rose-500/20 hover:border-rose-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="bg-rose-500/10 p-3 rounded-full mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <span class="text-white text-sm">Modules</span>
                </a>
                
                <a href="{{ route('interventions.index') }}" class="glassmorphism-static flex flex-col items-center p-4 rounded-2xl border border-purple-500/20 hover:border-purple-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="bg-purple-500/10 p-3 rounded-full mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <span class="text-white text-sm">Interventions</span>
                </a>
                
                <a href="{{ route('chantiers.index') }}" class="glassmorphism-static flex flex-col items-center p-4 rounded-2xl border border-blue-500/20 hover:border-blue-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="bg-blue-500/10 p-3 rounded-full mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-white text-sm">Chantiers</span>
                </a>
                
                <a href="{{ route('dalles.index') }}" class="glassmorphism-static flex flex-col items-center p-4 rounded-2xl border border-cyan-500/20 hover:border-cyan-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="bg-cyan-500/10 p-3 rounded-full mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <span class="text-white text-sm">Dalles</span>
                </a>
                
                <a href="{{ route('rapports.index') }}" class="glassmorphism-static flex flex-col items-center p-4 rounded-2xl border border-amber-500/20 hover:border-amber-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="bg-amber-500/10 p-3 rounded-full mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-white text-sm">Rapports</span>
                </a>
            </div>
            
            <!-- Statistiques générales -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <!-- Modules réparés -->
                <div class="glassmorphism-static rounded-2xl p-5 border border-green-500/20 hover:border-green-500/40 transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center mb-3">
                        <div class="mr-4 bg-green-500/10 p-3 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-green-300 text-sm font-medium">Modules réparés</h3>
                            <div class="flex items-baseline space-x-1">
                                <span class="text-2xl font-bold text-white">{{ $statsModules['termines'] }}</span>
                                <span class="text-xs text-gray-400">/ {{ $statsModules['total'] }}</span>
                            </div>
                        </div>
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
                <div class="glassmorphism-static rounded-2xl p-5 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center mb-3">
                        <div class="mr-4 bg-blue-500/10 p-3 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-blue-300 text-sm font-medium">Composants</h3>
                            <p class="text-2xl font-bold text-white">{{ $statsComposants['total'] }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mt-2">
                        <div class="bg-blue-900/30 p-1 rounded text-center">
                            <span class="text-xs text-blue-300">LEDs</span>
                            <p class="text-sm font-bold text-white">{{ $statsComposants['leds'] }}</p>
                        </div>
                        <div class="bg-purple-900/30 p-1 rounded text-center">
                            <span class="text-xs text-purple-300">ICs</span>
                            <p class="text-sm font-bold text-white">{{ $statsComposants['ics'] }}</p>
                        </div>
                        <div class="bg-yellow-900/30 p-1 rounded text-center">
                            <span class="text-xs text-yellow-300">Mask</span>
                            <p class="text-sm font-bold text-white">{{ $statsComposants['masques'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Temps total -->
                <div class="glassmorphism-static rounded-2xl p-5 border border-purple-500/20 hover:border-purple-500/40 transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center mb-3">
                        <div class="mr-4 bg-purple-500/10 p-3 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-purple-300 text-sm font-medium">Temps total</h3>
                            <p class="text-2xl font-bold text-white">{{ $statsTempsTotaux['temps_formate'] }}</p>
                        </div>
                    </div>
                    <div class="mt-2 bg-purple-900/30 py-1 px-2 rounded-lg text-center">
                        <span class="text-xs text-purple-300">{{ $statsTempsTotaux['nb_interventions'] }} interventions réalisées</span>
                    </div>
                </div>

                <!-- Temps moyen par module -->
                <div class="glassmorphism-static rounded-2xl p-5 border border-amber-500/20 hover:border-amber-500/40 transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center mb-3">
                        <div class="mr-4 bg-amber-500/10 p-3 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-amber-300 text-sm font-medium">Temps moyen</h3>
                            <p class="text-2xl font-bold text-white">{{ $tempsMoyenParModule['temps_formate'] }}</p>
                        </div>
                    </div>
                    <div class="mt-2 bg-amber-900/30 py-1 px-2 rounded-lg text-center">
                        <span class="text-xs text-amber-300">Par module traité</span>
                    </div>
                </div>
            </div>

            <!-- Chantiers et modules en cours -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Chantiers en cours -->
                <div class="glassmorphism-static rounded-2xl border border-blue-500/20 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700 bg-gradient-to-r from-blue-900/30 to-transparent">
                        <h3 class="font-medium text-lg text-white">Chantiers en cours</h3>
                    </div>
                    <div class="p-5">
                        @if($chantiersEnCours->count() > 0)
                            <div class="space-y-3">
                                @foreach($chantiersEnCours as $chantier)
                                    <div class="bg-gray-800/40 hover:bg-gray-800/60 p-4 rounded-xl border border-gray-700 transition-all duration-300">
                                        <div class="flex items-center">
                                            <div class="flex-1">
                                                <a href="{{ route('chantiers.show', $chantier) }}" class="font-medium text-white hover:text-blue-400 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    {{ $chantier->nom }}
                                                </a>
                                                <div class="text-sm text-gray-400 ml-7">
                                                    {{ $chantier->client->nom_complet }}
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ route('chantiers.show', $chantier) }}" class="btn-action btn-primary text-xs px-3 py-1 transition-transform hover:scale-105">
                                                    Voir
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <div class="flex justify-between text-xs text-gray-400 mb-1">
                                                <span>Progression</span>
                                                <span>{{ $chantier->progression }}%</span>
                                            </div>
                                            <div class="h-1.5 w-full bg-gray-700 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ $chantier->progression }}%"></div>
                                            </div>
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
                <div class="glassmorphism-static rounded-2xl border border-rose-500/20 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700 bg-gradient-to-r from-rose-900/30 to-transparent">
                        <h3 class="font-medium text-lg text-white">Modules à réparer</h3>
                    </div>
                    <div class="p-5">
                        @if($modulesEnCours->count() > 0)
                            <div class="space-y-3">
                                @foreach($modulesEnCours as $module)
                                    <div class="bg-gray-800/40 hover:bg-gray-800/60 p-4 rounded-xl border border-gray-700 transition-all duration-300">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <a href="{{ route('modules.show', $module) }}" class="font-medium text-white hover:text-rose-400 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                    </svg>
                                                    Module #{{ $module->id }}
                                                </a>
                                                <div class="text-sm text-gray-400 ml-7">
                                                    {{ $module->reference_module ?? 'Sans référence' }}
                                                </div>
                                            </div>
                                            @if($module->interventions->where('is_completed', false)->first())
                                                <div>
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-500/20 text-yellow-400">
                                                        <span class="animate-pulse mr-1">⬤</span>
                                                        <span>En cours</span>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-2 text-sm flex flex-wrap gap-2">
                                            <span class="bg-gray-700/50 px-2 py-1 rounded text-xs">
                                                <span class="text-blue-400">Chantier:</span>
                                                <a href="{{ route('chantiers.show', $module->dalle->produit->chantier) }}" class="text-white hover:text-blue-400">
                                                    {{ $module->dalle->produit->chantier->nom }}
                                                </a>
                                            </span>
                                        </div>
                                        <div class="flex justify-end mt-3 gap-2">
                                            <a href="{{ route('modules.show', $module) }}" class="btn-action btn-secondary text-xs px-3 py-1 transition-transform hover:scale-105">
                                                Détails
                                            </a>
                                            @if(!$module->interventions->where('is_completed', false)->first())
                                                <a href="{{ route('interventions.create', ['module_id' => $module->id]) }}" class="btn-action btn-primary text-xs px-3 py-1 transition-transform hover:scale-105">
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
                                <p>Aucun module en attente de réparation.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Chantiers terminés et Interventions récentes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Chantiers terminés -->
                <div class="glassmorphism-static rounded-2xl border border-green-500/20 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700 bg-gradient-to-r from-green-900/30 to-transparent">
                        <h3 class="font-medium text-lg text-white">Chantiers terminés récemment</h3>
                    </div>
                    <div class="p-5">
                        @if($chantiersTermines->count() > 0)
                            <div class="space-y-3">
                                @foreach($chantiersTermines as $chantier)
                                    <div class="bg-gray-800/40 hover:bg-gray-800/60 p-4 rounded-xl border border-gray-700 transition-all duration-300">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <a href="{{ route('chantiers.show', $chantier) }}" class="font-medium text-white hover:text-green-400 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $chantier->nom }}
                                                </a>
                                                <div class="text-sm text-gray-400 ml-7">
                                                    {{ $chantier->client->nom_complet }}
                                                </div>
                                                <div class="text-xs text-gray-400 mt-1 ml-7">
                                                    Terminé le {{ $chantier->updated_at->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            <div>
                                                <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-lg text-xs font-medium">
                                                    Terminé
                                                </span>
                                            </div>
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
                <div class="glassmorphism-static rounded-2xl border border-purple-500/20 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-700 bg-gradient-to-r from-purple-900/30 to-transparent">
                        <h3 class="font-medium text-lg text-white">Vos interventions récentes</h3>
                    </div>
                    <div class="p-5">
                        @if($interventionsRecentes->count() > 0)
                            <div class="space-y-3">
                                @foreach($interventionsRecentes as $intervention)
                                    <div class="bg-gray-800/40 hover:bg-gray-800/60 p-4 rounded-xl border border-gray-700 transition-all duration-300">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span class="font-medium text-white flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Intervention #{{ $intervention->id }}
                                                </span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="text-sm text-gray-400 mr-2">
                                                    {{ $intervention->created_at->format('d/m/Y') }}
                                                </div>
                                                @if($intervention->is_completed)
                                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-lg text-xs font-medium">
                                                        Terminée
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded-lg text-xs font-medium">
                                                        <span class="animate-pulse mr-1">⬤</span>
                                                        En cours
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-2 text-sm flex flex-wrap gap-2">
                                            <span class="bg-gray-700/50 px-2 py-1 rounded text-xs">
                                                <span class="text-rose-400">Module:</span>
                                                <a href="{{ route('modules.show', $intervention->module) }}" class="text-white hover:text-rose-400">
                                                    {{ $intervention->module->reference_module ?? 'Module #' . $intervention->module->id }}
                                                </a>
                                            </span>
                                            <span class="bg-gray-700/50 px-2 py-1 rounded text-xs">
                                                <span class="text-blue-400">Chantier:</span>
                                                <a href="{{ route('chantiers.show', $intervention->module->dalle->produit->chantier) }}" class="text-white hover:text-blue-400">
                                                    {{ $intervention->module->dalle->produit->chantier->nom }}
                                                </a>
                                            </span>
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