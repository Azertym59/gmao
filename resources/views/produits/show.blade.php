<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Détails du produit') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">{{ $produit->marque }} {{ $produit->modele }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('produits.edit', $produit) }}" class="btn-action btn-secondary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Modifier') }}
                            </a>
                            <a href="{{ route('chantiers.show', $produit->chantier) }}" class="btn-action btn-primary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                {{ __('Retour au chantier') }}
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Informations produit</h4>
                            <div class="glassmorphism p-4 rounded-xl text-gray-200 border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold">Marque:</span> {{ $produit->marque }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Modèle:</span> {{ $produit->modele }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Pitch:</span> {{ $produit->pitch }} mm
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Utilisation:</span> 
                                    @if($produit->utilisation == 'indoor')
                                        <span class="badge badge-info">Indoor</span>
                                    @else
                                        <span class="badge badge-warning">Outdoor</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-semibold">Électronique:</span> 
                                    @if($produit->electronique == 'autre')
                                        {{ $produit->electronique_detail }}
                                    @else
                                        {{ ucfirst($produit->electronique) }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Informations chantier</h4>
                            <div class="glassmorphism p-4 rounded-xl text-gray-200 border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold">Chantier:</span> 
                                    <a href="{{ route('chantiers.show', $produit->chantier) }}" class="text-accent-blue hover:underline">
                                        {{ $produit->chantier->nom }}
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Référence:</span> {{ $produit->chantier->reference }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Client:</span> 
                                    <a href="{{ route('clients.show', $produit->chantier->client) }}" class="text-accent-blue hover:underline">
                                        {{ $produit->chantier->client->nom_complet }}
                                    </a>
                                </div>
                                <div>
                                    <span class="font-semibold">Date butoir:</span> 
                                    {{ $produit->chantier->date_butoir->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dalles du produit -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-medium text-gray-300">Dalles</h4>
                            <div class="flex space-x-2">
                                <a href="{{ route('modules.mass-create.form', $produit) }}" 
                                   class="px-4 py-2 bg-accent-purple text-white rounded-lg hover:bg-purple-600 transition duration-150 ease-in-out flex items-center text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                    </svg>
                                    {{ __('Création en masse') }}
                                </a>
                                <a href="{{ route('dalles.create', ['produit_id' => $produit->id]) }}" 
                                   class="px-4 py-2 bg-accent-green text-white rounded-lg hover:bg-green-600 transition duration-150 ease-in-out flex items-center text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    {{ __('Nouvelle dalle') }}
                                </a>
                            </div>
                        </div>
                        
                        @if($produit->dalles->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach($produit->dalles as $dalle)
                                    <div class="glassmorphism border border-gray-700 hover:border-accent-green rounded-xl p-6 hover:bg-gray-800/50 transition-all duration-200">
                                        <h5 class="font-medium text-lg text-white mb-3">Dalle #{{ $dalle->id }}</h5>
                                        <div class="grid grid-cols-2 gap-3 text-sm text-gray-300">
                                            <div>
                                                <span class="font-medium">Dimension:</span> {{ $dalle->largeur }}×{{ $dalle->hauteur }} mm
                                            </div>
                                            <div>
                                                <span class="font-medium">Modules:</span> {{ $dalle->nb_modules ?? $dalle->modules->count() }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Alimentation:</span> {{ $dalle->alimentation }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Référence:</span> {{ $dalle->reference_dalle ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="mt-6 flex justify-end">
                                            <a href="{{ route('dalles.show', $dalle) }}" 
                                               class="btn-action btn-primary flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Détails
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-10 rounded-xl text-center text-gray-400 bg-gray-800/30 flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                </svg>
                                <p class="mb-4">Ce produit n'a pas encore de dalles.</p>
                                <div class="flex space-x-4">
                                    <a href="{{ route('dalles.create', ['produit_id' => $produit->id]) }}" class="btn-action btn-primary flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Ajouter une dalle
                                    </a>
                                    <a href="{{ route('modules.mass-create.form', $produit) }}" class="btn-action btn-secondary flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                        </svg>
                                        Création en masse
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>