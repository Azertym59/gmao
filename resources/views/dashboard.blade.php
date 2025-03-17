<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center">
            <img src="{{ asset('images/Logo rectangle V2.png') }}" alt="TecaLED Logo" class="h-48 mx-auto mb-6">
            
            @if (auth()->check() && isset(auth()->user()->client))
            <p class="text-2xl mb-6 text-white font-medium">Bienvenue {{ auth()->user()->client->civilite }} {{ auth()->user()->client->getNomCompletSansDoublonAttribute() }}</p>
            @endif
            
            <div class="glassmorphism-static overflow-hidden shadow-xl rounded-xl">
                <div class="p-6 text-text-primary">
                    <h3 class="text-xl font-semibold mb-3">Bienvenue dans GMAO TecaLED</h3>
                    <p class="mb-5">Utilisez les options ci-dessous pour gérer votre activité</p>
                    
                    <!-- Menu d'accès rapide -->
                    <div class="grid grid-cols-3 md:grid-cols-6 gap-4 mb-8">
                        <a href="{{ route('chantiers.index') }}" class="glassmorphism-static flex flex-col items-center p-4 rounded-2xl border border-blue-500/20 hover:border-blue-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                            <div class="bg-blue-500/10 p-3 rounded-full mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <span class="text-white font-medium">Chantiers</span>
                        </a>
                        
                        <a href="{{ route('produits.index') }}" class="glassmorphism-static flex flex-col items-center p-4 rounded-2xl border border-green-500/20 hover:border-green-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                            <div class="bg-green-500/10 p-3 rounded-full mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                            </div>
                            <span class="text-white font-medium">Produits</span>
                        </a>
                        
                        <a href="{{ route('interventions.index') }}" class="glassmorphism-static flex flex-col items-center p-4 rounded-2xl border border-purple-500/20 hover:border-purple-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                            <div class="bg-purple-500/10 p-3 rounded-full mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <span class="text-white font-medium">Interventions</span>
                        </a>
                        
                        <a href="{{ route('clients.index') }}" class="glassmorphism-static flex flex-col items-center p-4 rounded-2xl border border-amber-500/20 hover:border-amber-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                            <div class="bg-amber-500/10 p-3 rounded-full mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span class="text-white font-medium">Clients</span>
                        </a>
                        
                        <a href="{{ route('dalles.index') }}" class="glassmorphism-static flex flex-col items-center p-4 rounded-2xl border border-cyan-500/20 hover:border-cyan-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                            <div class="bg-cyan-500/10 p-3 rounded-full mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </div>
                            <span class="text-white font-medium">Dalles</span>
                        </a>
                        
                        <a href="{{ route('modules.index') }}" class="glassmorphism-static flex flex-col items-center p-4 rounded-2xl border border-rose-500/20 hover:border-rose-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                            <div class="bg-rose-500/10 p-3 rounded-full mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <span class="text-white font-medium">Modules</span>
                        </a>
                    </div>
                    
                    <!-- Statistiques des chantiers -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <!-- Chantiers en cours -->
                        <div class="glassmorphism-static rounded-2xl p-5 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-300 hover:shadow-lg">
                            <div class="flex items-center mb-2">
                                <div class="mr-4 bg-blue-500/10 p-3 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-blue-300 text-sm font-medium">Chantiers en cours</h3>
                                    <p class="text-2xl font-bold text-white">
                                        {{ \App\Models\Chantier::where('etat', 'en_cours')->count() }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 text-center">
                                <a href="{{ route('chantiers.index', ['filter_etat' => 'en_cours']) }}" class="btn-action btn-primary text-xs px-2 py-1 w-full inline-block">Voir tous</a>
                            </div>
                        </div>
                        
                        <!-- Chantiers urgents -->
                        <div class="glassmorphism-static rounded-2xl p-5 border border-red-500/20 hover:border-red-500/40 transition-all duration-300 hover:shadow-lg">
                            <div class="flex items-center mb-2">
                                <div class="mr-4 bg-red-500/10 p-3 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-red-300 text-sm font-medium">Chantiers urgents</h3>
                                    <p class="text-2xl font-bold text-white">
                                        {{ $chantiersUrgents->count() }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 text-center">
                                <a href="{{ route('chantiers.index', ['filter_urgent' => 'true']) }}" class="btn-action btn-primary text-xs px-2 py-1 w-full inline-block">Voir tous</a>
                            </div>
                        </div>
                        
                        <!-- Chantiers terminés -->
                        <div class="glassmorphism-static rounded-2xl p-5 border border-green-500/20 hover:border-green-500/40 transition-all duration-300 hover:shadow-lg">
                            <div class="flex items-center mb-2">
                                <div class="mr-4 bg-green-500/10 p-3 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-green-300 text-sm font-medium">Chantiers terminés</h3>
                                    <p class="text-2xl font-bold text-white">
                                        {{ \App\Models\Chantier::where('etat', 'termine')->count() }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 text-center">
                                <a href="{{ route('chantiers.index', ['filter_etat' => 'termine']) }}" class="btn-action btn-primary text-xs px-2 py-1 w-full inline-block">Voir tous</a>
                            </div>
                        </div>
                        
                        <!-- Interventions actives -->
                        <div class="glassmorphism-static rounded-2xl p-5 border border-purple-500/20 hover:border-purple-500/40 transition-all duration-300 hover:shadow-lg">
                            <div class="flex items-center mb-2">
                                <div class="mr-4 bg-purple-500/10 p-3 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-purple-300 text-sm font-medium">Interventions actives</h3>
                                    <p class="text-2xl font-bold text-white">
                                        {{ \App\Models\Intervention::where('is_completed', false)->count() }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 text-center">
                                <a href="{{ route('interventions.index', ['filter_status' => 'active']) }}" class="btn-action btn-primary text-xs px-2 py-1 w-full inline-block">Voir toutes</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistiques de réparation -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        <!-- Modules réparés -->
                        <div class="glassmorphism-static rounded-2xl p-5 border border-amber-500/20 hover:border-amber-500/40 transition-all duration-300 hover:shadow-lg">
                            <div class="flex items-center mb-2 justify-between">
                                <div class="flex items-center">
                                    <div class="mr-4 bg-amber-500/10 p-3 rounded-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-amber-300 text-sm font-medium">Modules réparés</h3>
                                        <p class="text-2xl font-bold text-white">
                                            {{ $statsInterventions['modules_repares'] ?? 0 }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Composants remplacés -->
                        <div class="glassmorphism-static rounded-2xl p-5 border border-cyan-500/20 hover:border-cyan-500/40 transition-all duration-300 hover:shadow-lg">
                            <div class="mb-2">
                                <h3 class="text-cyan-300 text-sm font-medium flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                    Composants remplacés
                                </h3>
                                <p class="text-2xl font-bold text-white mt-2">
                                    {{ $statsInterventions['total_composants'] ?? 0 }}
                                </p>
                            </div>
                            <div class="text-xs text-gray-400 grid grid-cols-3 gap-1">
                                <div>LEDs: {{ $statsInterventions['nb_leds_remplacees'] ?? 0 }}</div>
                                <div>ICs: {{ $statsInterventions['nb_ic_remplaces'] ?? 0 }}</div>
                                <div>Masques: {{ $statsInterventions['nb_masques_remplaces'] ?? 0 }}</div>
                            </div>
                        </div>
                        
                        <!-- Taux de réussite -->
                        <div class="glassmorphism-static rounded-2xl p-5 border border-rose-500/20 hover:border-rose-500/40 transition-all duration-300 hover:shadow-lg">
                            <div class="flex items-center mb-2 justify-between">
                                <div class="flex items-center">
                                    <div class="mr-4 bg-rose-500/10 p-3 rounded-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-rose-300 text-sm font-medium">Taux de réussite</h3>
                                        <p class="text-2xl font-bold text-white">
                                            {{ $statsInterventions['taux_reussite'] ?? 0 }}%
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-400">
                                <div>Interventions terminées: {{ $statsInterventions['interventions_terminees'] ?? 0 }}</div>
                                <div>Temps moyen: {{ $statsInterventions['temps_moyen_format'] ?? "00h 00m 00s" }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Sections principales -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Chantiers urgents -->
                        <div class="glassmorphism-static rounded-2xl border border-red-500/20 overflow-hidden transition-all duration-300 hover:shadow-lg">
                            <div class="px-5 py-4 border-b border-gray-700 bg-gradient-to-r from-red-900/30 to-transparent flex justify-between items-center">
                                <h3 class="text-white font-medium flex items-center text-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Chantiers urgents
                                </h3>
                                <span class="text-xs text-red-400 bg-red-500/10 rounded-full py-1 px-2">{{ $chantiersUrgents->count() }}</span>
                            </div>
                            <div class="p-4">
                                @if($chantiersUrgents->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($chantiersUrgents as $chantier)
                                            <div class="bg-gray-800/40 hover:bg-gray-800/60 p-4 rounded-xl border border-gray-700 transition-all duration-300">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <a href="{{ route('chantiers.show', $chantier) }}" class="font-medium text-white hover:text-red-400 flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            {{ $chantier->reference ?? $chantier->nom }}
                                                        </a>
                                                        <div class="text-sm text-gray-400 ml-7">
                                                            {{ $chantier->client->societe ?? $chantier->client->nom_complet ?? 'Client inconnu' }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-lg text-xs font-medium">
                                                            {{ \App\Helpers\DateHelper::formatTimeRemaining($chantier->date_butoir) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                                                        <span>Progression</span>
                                                        <span>{{ $chantier->pourcentage_avancement ?? 0 }}%</span>
                                                    </div>
                                                    <div class="h-1.5 w-full bg-gray-700 rounded-full overflow-hidden">
                                                        <div class="h-full bg-red-500 rounded-full" style="width: {{ $chantier->pourcentage_avancement ?? 0 }}%"></div>
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
                                        <p>Aucun chantier urgent actuellement</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Chantiers en cours -->
                        <div class="glassmorphism-static rounded-2xl border border-blue-500/20 overflow-hidden transition-all duration-300 hover:shadow-lg">
                            <div class="px-5 py-4 border-b border-gray-700 bg-gradient-to-r from-blue-900/30 to-transparent flex justify-between items-center">
                                <h3 class="text-white font-medium flex items-center text-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Chantiers en cours
                                </h3>
                                <a href="{{ route('chantiers.index') }}" class="text-xs text-blue-400 hover:underline">Voir tous</a>
                            </div>
                            <div class="p-4">
                                @php
                                    $chantiersEnCours = \App\Models\Chantier::with('client')
                                                        ->where('etat', 'en_cours')
                                                        ->orderBy('date_butoir', 'asc')
                                                        ->take(5)
                                                        ->get();
                                @endphp
                                @if($chantiersEnCours->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($chantiersEnCours as $chantier)
                                            <div class="bg-gray-800/40 hover:bg-gray-800/60 p-4 rounded-xl border border-gray-700 transition-all duration-300">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <a href="{{ route('chantiers.show', $chantier) }}" class="font-medium text-white hover:text-blue-400 flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                            {{ $chantier->reference ?? $chantier->nom }}
                                                        </a>
                                                        <div class="text-sm text-gray-400 ml-7">
                                                            {{ $chantier->client->societe ?? $chantier->client->nom_complet ?? 'Client inconnu' }}
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('chantiers.show', $chantier) }}" class="btn-action btn-primary text-xs px-3 py-1 transition-transform hover:scale-105">
                                                        Voir
                                                    </a>
                                                </div>
                                                <div class="mt-3">
                                                    <div class="flex justify-between text-xs text-gray-400 mb-1">
                                                        <span>Progression</span>
                                                        <span>{{ $chantier->pourcentage_avancement ?? $chantier->progression ?? 0 }}%</span>
                                                    </div>
                                                    <div class="h-1.5 w-full bg-gray-700 rounded-full overflow-hidden">
                                                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ $chantier->pourcentage_avancement ?? $chantier->progression ?? 0 }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-6 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <p>Aucun chantier en cours actuellement</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Dernières sections -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Dernières interventions -->
                        <div class="glassmorphism-static rounded-2xl border border-purple-500/20 overflow-hidden transition-all duration-300 hover:shadow-lg">
                            <div class="px-5 py-4 border-b border-gray-700 bg-gradient-to-r from-purple-900/30 to-transparent flex justify-between items-center">
                                <h3 class="text-white font-medium flex items-center text-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    5 dernières interventions
                                </h3>
                                <a href="{{ route('interventions.index') }}" class="text-xs text-purple-400 hover:underline">Voir toutes</a>
                            </div>
                            <div class="p-4">
                                @php
                                    $dernieresInterventions = \App\Models\Intervention::with(['module.dalle.produit.chantier.client', 'technicien'])
                                                    ->orderBy('updated_at', 'desc')
                                                    ->take(5)
                                                    ->get();
                                @endphp
                                @if($dernieresInterventions->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($dernieresInterventions as $intervention)
                                            <div class="bg-gray-800/40 hover:bg-gray-800/60 p-3 rounded-xl border border-gray-700 transition-all duration-300">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <a href="{{ route('interventions.show', $intervention) }}" class="font-medium text-white flex items-center hover:text-purple-400">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            {{ $intervention->technicien->name ?? 'Technicien' }} - Module #{{ $intervention->module_id }}
                                                        </a>
                                                        @if($intervention->module && $intervention->module->dalle && $intervention->module->dalle->produit)
                                                            <div class="ml-7 text-sm text-gray-400">
                                                                <strong>Société:</strong> {{ $intervention->module->dalle->produit->chantier->client->societe ?? 'N/A' }}
                                                            </div>
                                                            <div class="ml-7 text-sm text-gray-400">
                                                                <strong>Produit:</strong> {{ $intervention->module->dalle->produit->marque }} {{ $intervention->module->dalle->produit->modele }}
                                                            </div>
                                                            <div class="ml-7 text-sm text-gray-400">
                                                                <strong>Specs:</strong> Pitch {{ $intervention->module->dalle->produit->pitch }}mm | 
                                                                Bain {{ $intervention->module->dalle->produit->bain_couleur ?? 'N/A' }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex flex-col items-end">
                                                        <div class="flex items-center mb-2">
                                                            <div class="text-sm text-gray-400 mr-2">
                                                                {{ $intervention->updated_at->format('d/m/Y') }}
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
                                                        <a href="{{ route('interventions.show', $intervention) }}" class="btn-action btn-primary text-xs px-3 py-1 transition-transform hover:scale-105">
                                                            Voir l'intervention
                                                        </a>
                                                    </div>
                                                </div>
                                                @if($intervention->temps_total)
                                                    <div class="mt-1 text-sm text-gray-400">
                                                        Durée: {{ gmdate('H:i:s', $intervention->temps_total) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-6 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <p>Aucune intervention récente</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Chantiers terminés récemment -->
                        <div class="glassmorphism-static rounded-2xl border border-green-500/20 overflow-hidden transition-all duration-300 hover:shadow-lg">
                            <div class="px-5 py-4 border-b border-gray-700 bg-gradient-to-r from-green-900/30 to-transparent flex justify-between items-center">
                                <h3 class="text-white font-medium flex items-center text-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    5 derniers chantiers terminés
                                </h3>
                                <a href="{{ route('chantiers.index') }}" class="text-xs text-green-400 hover:underline">Voir tous</a>
                            </div>
                            <div class="p-4">
                                @php
                                    $chantiersTermines = \App\Models\Chantier::with('client')
                                                ->where('etat', 'termine')
                                                ->orderBy('updated_at', 'desc')
                                                ->take(5)
                                                ->get();
                                @endphp
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
                                                            {{ $chantier->reference ?? $chantier->nom }}
                                                        </a>
                                                        <div class="text-sm text-gray-400 ml-7">
                                                            {{ $chantier->client->societe ?? $chantier->client->nom_complet ?? 'Client inconnu' }}
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
                                        <p>Aucun chantier terminé récemment</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions principales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Section chantiers -->
                        <div class="glassmorphism-static rounded-2xl border border-blue-500/20 overflow-hidden transition-all duration-300 hover:shadow-lg">
                            <div class="px-5 py-4 border-b border-gray-700 bg-gradient-to-r from-blue-900/30 to-transparent">
                                <h3 class="text-white font-medium flex items-center text-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Gestion des chantiers
                                </h3>
                            </div>
                            <div class="p-5">
                                <p class="text-gray-300 text-sm mb-4">
                                    Créez, suivez et gérez l'ensemble de vos chantiers de réparation d'écrans LED.
                                </p>
                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('chantiers.create') }}" class="btn-action btn-secondary text-xs px-3 py-1 transition-transform hover:scale-105">
                                            Nouveau
                                        </a>
                                        <a href="{{ route('chantiers.index') }}" class="btn-action btn-primary text-xs px-3 py-1 transition-transform hover:scale-105">
                                            Voir tous
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section produits -->
                        <div class="glassmorphism-static rounded-2xl border border-green-500/20 overflow-hidden transition-all duration-300 hover:shadow-lg">
                            <div class="px-5 py-4 border-b border-gray-700 bg-gradient-to-r from-green-900/30 to-transparent">
                                <h3 class="text-white font-medium flex items-center text-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                    </svg>
                                    Gestion des produits
                                </h3>
                            </div>
                            <div class="p-5">
                                <p class="text-gray-300 text-sm mb-4">
                                    Ajoutez et consultez les produits en cours de réparation dans vos ateliers.
                                </p>
                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('produits.create') }}" class="btn-action btn-secondary text-xs px-3 py-1 transition-transform hover:scale-105">
                                            Nouveau
                                        </a>
                                        <a href="{{ route('produits.index') }}" class="btn-action btn-primary text-xs px-3 py-1 transition-transform hover:scale-105">
                                            Voir tous
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
