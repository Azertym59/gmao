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
                    <!-- En-tête avec infos Chantier/Client -->
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-white">{{ $produit->marque }} {{ $produit->modele }}
                                @if($produit->is_variante && $produit->variante_nom)
                                    <span class="ml-2 text-sm bg-blue-900/50 text-blue-300 px-2 py-1 rounded-lg">{{ $produit->variante_nom }}</span>
                                @endif
                            </h3>
                            <div class="flex items-center mt-2">
                                <span class="text-sm text-gray-400 mr-4">Chantier: <a href="{{ route('chantiers.show', $produit->chantier) }}" class="text-accent-blue hover:underline">{{ $produit->chantier->nom }}</a></span>
                                <span class="text-sm text-gray-400">Client: {{ $produit->chantier->client->nom_complet }}</span>
                            </div>
                        </div>
                        <div class="flex space-x-2 mt-4 md:mt-0">
                            @if(!$produit->is_variante)
                                <a href="{{ route('produits.create-variante', $produit) }}" class="btn-action btn-secondary flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Ajouter une variante
                                </a>
                            @endif
                            
                            @if($produit->is_variante)
                                <a href="{{ route('produits.edit-variante', $produit) }}" class="btn-action btn-secondary flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Modifier la variante
                                </a>
                            @else
                                <a href="{{ route('produits.edit', $produit) }}" class="btn-action btn-secondary flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Modifier
                                </a>
                            @endif
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
                            <h4 class="font-medium text-accent-blue mb-2">Informations produit</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-blue">
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
                            
                            <h4 class="font-medium text-accent-green mt-6 mb-2">Caractéristiques du bain</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-green">
                                <div class="mb-2">
                                    <span class="font-semibold">Carte de réception:</span> 
                                    {{ $produit->carte_reception ?: 'Non spécifiée' }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Hub:</span> 
                                    {{ $produit->hub ?: 'Non spécifié' }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Bain de couleur:</span> 
                                    {{ $produit->bain_couleur ?: 'Non spécifié' }}
                                </div>
                                
                                @if($produit->ledDatasheet)
                                <div class="mt-4 pt-4 border-t border-gray-600">
                                    <span class="font-semibold">LED Datasheet:</span> {{ $produit->ledDatasheet->reference }}
                                    
                                    @if($produit->ledDatasheet->image_data)
                                    <div class="mt-2">
                                        <img src="{{ $produit->ledDatasheet->image_data }}" alt="LED Datasheet" class="w-40 h-40 mx-auto bg-white rounded p-1" />
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-accent-purple mb-2">Informations chantier</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-purple">
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
                            
                            @if(!$produit->is_variante && $produit->variantes->count() > 0)
                            <h4 class="font-medium text-accent-orange mt-6 mb-2">Variantes disponibles</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-orange">
                                <div class="space-y-3">
                                    @foreach($produit->variantes as $variante)
                                    <div class="p-3 bg-gray-800/30 rounded-lg hover:bg-gray-800/70 transition-all duration-200">
                                        <h5 class="font-medium text-accent-orange mb-1">{{ $variante->variante_nom }}</h5>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <div>
                                                <span class="font-medium">Carte:</span> {{ $variante->carte_reception ?: 'N/A' }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Hub:</span> {{ $variante->hub ?: 'N/A' }}
                                            </div>
                                            <div class="col-span-2">
                                                <span class="font-medium">Bain:</span> {{ $variante->bain_couleur ?: 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="mt-2 text-right">
                                            <a href="{{ route('produits.show', $variante) }}" class="text-xs text-accent-blue hover:underline">
                                                Voir détails
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            @if($produit->is_variante)
                            <h4 class="font-medium text-accent-orange mt-6 mb-2">Produit parent</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-orange">
                                <div class="mb-2">
                                    <span class="font-semibold">Produit:</span> 
                                    <a href="{{ route('produits.show', $produit->produitParent) }}" class="text-accent-blue hover:underline">
                                        {{ $produit->produitParent->marque }} {{ $produit->produitParent->modele }}
                                    </a>
                                </div>
                                <div>
                                    <span class="font-semibold">Variante:</span> {{ $produit->variante_nom }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dalles du produit -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-medium text-accent-green">Dalles</h4>
                            <div class="flex space-x-2">
                                <a href="{{ route('modules.mass-create.form', $produit) }}" 
                                   class="px-4 py-2 bg-gray-800 text-gray-100 rounded-lg hover:bg-gray-700 border border-gray-600 transition duration-150 ease-in-out flex items-center text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                    </svg>
                                    {{ __('Création en masse') }}
                                </a>
                                <a href="{{ route('dalles.create', ['produit_id' => $produit->id]) }}" 
                                   class="px-4 py-2 bg-white text-gray-900 rounded-lg hover:bg-gray-100 shadow transition duration-150 ease-in-out flex items-center text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    {{ __('Nouvelle dalle') }}
                                </a>
                            </div>
                        </div>
                        
                        @if($produit->dalles->count() > 0)
                            <div class="border border-gray-700 rounded-xl overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-700">
                                    <thead class="bg-gray-800">
                                        <tr class="divide-x divide-gray-700">
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID/Référence</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Dimensions</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Modules</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Alimentation</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Électronique</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-gray-900/30 divide-y divide-gray-800">
                                        @foreach($produit->dalles as $dalle)
                                            <tr class="divide-x divide-gray-800 hover:bg-gray-800/30 transition-colors">
                                                <td class="px-4 py-3 text-sm text-gray-300">
                                                    <div class="font-medium">Dalle #{{ $dalle->id }}</div>
                                                    @if($dalle->reference_dalle)
                                                        <div class="text-xs text-gray-400 mt-1">{{ $dalle->reference_dalle }}</div>
                                                    @endif
                                                    @if($dalle->numero_dalle)
                                                        <div class="text-xs text-accent-blue mt-1">N° {{ $dalle->numero_dalle }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center text-gray-300">
                                                    <span class="inline-block px-2 py-1 bg-gray-800 rounded">
                                                        {{ $dalle->largeur }}×{{ $dalle->hauteur }} mm
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center text-gray-300">
                                                    <span class="inline-flex items-center justify-center px-2 py-1 bg-blue-900/30 border border-blue-800/50 text-blue-300 rounded">
                                                        {{ $dalle->modules->count() }} / {{ $dalle->nb_modules ?? $dalle->modules->count() }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center text-gray-300">
                                                    {{ $dalle->alimentation ?: 'Standard' }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center text-gray-300">
                                                    @if($dalle->carte_reception)
                                                        <div>
                                                            <span class="text-xs text-gray-400">Carte:</span>
                                                            <span class="font-medium">{{ $dalle->carte_reception }}</span>
                                                        </div>
                                                    @endif
                                                    @if($dalle->hub)
                                                        <div class="mt-1">
                                                            <span class="text-xs text-gray-400">Hub:</span>
                                                            <span class="font-medium">{{ $dalle->hub }}</span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm text-right">
                                                    <div class="flex justify-end space-x-2">
                                                        <a href="{{ route('dalles.edit', $dalle) }}" 
                                                            class="inline-flex items-center px-2 py-1 bg-gray-800 text-gray-300 rounded hover:bg-gray-700 transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                        <a href="{{ route('dalles.show', $dalle) }}" 
                                                            class="inline-flex items-center px-2 py-1 bg-white text-gray-800 rounded hover:bg-gray-100 shadow-sm transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="glassmorphism p-10 rounded-lg border border-opacity-10 border-accent-green text-center text-gray-400 flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-accent-green opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                                </svg>
                                <p class="mb-4">Ce produit n'a pas encore de dalles</p>
                                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                                    <a href="{{ route('dalles.create', ['produit_id' => $produit->id]) }}" class="px-4 py-2 bg-white text-gray-900 rounded-lg hover:bg-gray-100 shadow transition duration-150 ease-in-out flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Ajouter une dalle
                                    </a>
                                    <a href="{{ route('modules.mass-create.form', $produit) }}" class="px-4 py-2 bg-gray-800 text-gray-100 rounded-lg hover:bg-gray-700 border border-gray-600 transition duration-150 ease-in-out flex items-center justify-center">
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