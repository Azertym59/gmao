@extends('layouts.app')

@section('header', 'Tableau de bord')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- En-tête avec design sobre -->
    <div class="mb-8 card-glow overflow-hidden">
        <div class="px-6 py-8 md:py-10 flex flex-col md:flex-row items-center justify-between">
            <div class="flex flex-col items-center md:items-start text-center md:text-left mb-6 md:mb-0">
                <h1 class="text-3xl md:text-4xl font-bold text-text-primary mb-2">
                    GMAO TecaLED
                </h1>
                <p class="mt-2 text-lg text-text-secondary">
                    @if (auth()->check() && isset(auth()->user()->client))
                        Bienvenue, {{ auth()->user()->client->civilite }} {{ auth()->user()->client->getNomCompletSansDoublonAttribute() }}
                    @else
                        Votre plateforme de gestion de maintenance
                    @endif
                </p>
            </div>
            <div>
                <div class="bg-card-bg p-3 rounded-lg border border-border-light">
                    <img src="{{ asset('images/Logo rectangle V2.png') }}" alt="TecaLED Logo" class="h-24 md:h-28 rounded">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Menu d'accès rapide -->
    <div class="card-glow mb-8">
        <div>
            <div class="px-6 py-4 border-b border-border-light flex items-center">
                <h2 class="text-lg font-semibold text-text-primary">Accès rapide</h2>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                @if(auth()->user()->role === 'admin')
                <!-- Admin quick actions - style sobre -->
                <a href="{{ route('chantiers.index') }}" class="group">
                    <div class="p-5 border border-border-light rounded-lg bg-card-bg flex items-start transition-all duration-200 hover:border-accent-primary shadow-sm hover:shadow">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center bg-accent-primary/10 text-accent-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-text-primary">Chantiers</h3>
                            <p class="text-text-secondary mt-1">Gérer les chantiers</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('clients.index') }}" class="group">
                    <div class="p-5 border border-border-light rounded-lg bg-card-bg flex items-start transition-all duration-200 hover:border-accent-secondary shadow-sm hover:shadow">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center bg-accent-secondary/10 text-accent-secondary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-text-primary">Clients</h3>
                            <p class="text-text-secondary mt-1">Gérer les clients</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('produits.index') }}" class="group">
                    <div class="p-5 border border-border-light rounded-lg bg-card-bg flex items-start transition-all duration-200 hover:border-accent-tertiary shadow-sm hover:shadow">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center bg-accent-tertiary/10 text-accent-tertiary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-text-primary">Produits</h3>
                            <p class="text-text-secondary mt-1">Gérer les produits</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('interventions.index') }}" class="group">
                    <div class="p-5 border border-border-light rounded-lg bg-card-bg flex items-start transition-all duration-200 hover:border-accent-primary shadow-sm hover:shadow">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center bg-accent-primary/10 text-accent-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-text-primary">Interventions</h3>
                            <p class="text-text-secondary mt-1">Gérer les interventions</p>
                        </div>
                    </div>
                </a>
                @elseif(auth()->user()->role === 'technicien')
                <!-- Technicien quick actions - style sobre -->
                <a href="{{ route('interventions.index') }}" class="group">
                    <div class="p-5 border border-border-light rounded-lg bg-card-bg flex items-start transition-all duration-200 hover:border-accent-primary shadow-sm hover:shadow">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center bg-accent-primary/10 text-accent-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-text-primary">Mes interventions</h3>
                            <p class="text-text-secondary mt-1">Gérer vos interventions</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('modules.index') }}" class="group">
                    <div class="p-5 border border-border-light rounded-lg bg-card-bg flex items-start transition-all duration-200 hover:border-accent-secondary shadow-sm hover:shadow">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center bg-accent-secondary/10 text-accent-secondary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-text-primary">Modules</h3>
                            <p class="text-text-secondary mt-1">Voir les modules</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('chantiers.index') }}" class="group">
                    <div class="p-5 border border-border-light rounded-lg bg-card-bg flex items-start transition-all duration-200 hover:border-accent-tertiary shadow-sm hover:shadow">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center bg-accent-tertiary/10 text-accent-tertiary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-text-primary">Chantiers</h3>
                            <p class="text-text-secondary mt-1">Voir les chantiers</p>
                        </div>
                    </div>
                </a>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Statistiques avec design sobre -->
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'technicien')
    <div class="mb-8 card-glow">
        <div class="px-6 py-4 border-b border-border-light flex items-center">
            <h2 class="text-lg font-semibold text-text-primary">Tableau de bord</h2>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-card-bg p-5 rounded-lg border border-border-light shadow-sm hover:shadow transition-all duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-lg bg-accent-primary/10 flex items-center justify-center">
                            <svg class="w-7 h-7 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-sm uppercase font-medium text-text-secondary tracking-wider">Interventions</h3>
                        <div class="mt-1.5">
                            <p class="text-3xl font-semibold text-text-primary">
                                {{ $statsInterventions['total_interventions'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-card-bg p-5 rounded-lg border border-border-light shadow-sm hover:shadow transition-all duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-lg bg-accent-primary/10 flex items-center justify-center">
                            <svg class="w-7 h-7 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-sm uppercase font-medium text-text-secondary tracking-wider">Terminées</h3>
                        <div class="mt-1.5">
                            <p class="text-3xl font-semibold text-text-primary">
                                {{ $statsInterventions['interventions_terminees'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-card-bg p-5 rounded-lg border border-border-light shadow-sm hover:shadow transition-all duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-lg bg-accent-secondary/10 flex items-center justify-center">
                            <svg class="w-7 h-7 text-accent-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-sm uppercase font-medium text-text-secondary tracking-wider">Temps moyen</h3>
                        <div class="mt-1.5 flex items-baseline">
                            <p class="text-3xl font-semibold text-text-primary">
                                {{ round($statsInterventions['temps_moyen_minutes'] ?? 0) }}
                            </p>
                            <span class="ml-1 text-base text-text-secondary">min</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-card-bg p-5 rounded-lg border border-border-light shadow-sm hover:shadow transition-all duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-lg bg-accent-tertiary/10 flex items-center justify-center">
                            <svg class="w-7 h-7 text-accent-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-sm uppercase font-medium text-text-secondary tracking-wider">Chantiers actifs</h3>
                        <div class="mt-1.5">
                            <p class="text-3xl font-semibold text-text-primary">
                                {{ $chantiersActifs->count() ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection