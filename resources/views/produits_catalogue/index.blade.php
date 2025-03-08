<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-white leading-tight">
            {{ __('Catalogue de Produits') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">Catalogue de référence des écrans LED</h3>
                        <a href="{{ route('produits-catalogue.create') }}" class="px-4 py-2 bg-accent-yellow text-white rounded-lg hover:bg-yellow-500 transition duration-150 ease-in-out flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter un produit au catalogue
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-500/30 border border-green-500/50 text-green-400 rounded-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filtres -->
                    <div class="mb-6 p-4 glassmorphism rounded-lg border border-gray-700">
                        <h4 class="text-white font-medium mb-3">Filtres</h4>
                        <form action="{{ route('produits-catalogue.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="marque" class="block text-sm font-medium text-gray-400 mb-1">Marque</label>
                                <input type="text" id="marque" name="marque" class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ request('marque') }}">
                            </div>
                            <div>
                                <label for="utilisation" class="block text-sm font-medium text-gray-400 mb-1">Utilisation</label>
                                <select id="utilisation" name="utilisation" class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Toutes les utilisations</option>
                                    <option value="indoor" {{ request('utilisation') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                                    <option value="outdoor" {{ request('utilisation') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                                </select>
                            </div>
                            <div>
                                <label for="electronique" class="block text-sm font-medium text-gray-400 mb-1">Électronique</label>
                                <select id="electronique" name="electronique" class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tous les types</option>
                                    <option value="nova" {{ request('electronique') == 'nova' ? 'selected' : '' }}>Nova</option>
                                    <option value="linsn" {{ request('electronique') == 'linsn' ? 'selected' : '' }}>Linsn</option>
                                    <option value="dbstar" {{ request('electronique') == 'dbstar' ? 'selected' : '' }}>DBStar</option>
                                    <option value="brompton" {{ request('electronique') == 'brompton' ? 'selected' : '' }}>Brompton</option>
                                    <option value="autre" {{ request('electronique') == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition duration-150 ease-in-out">
                                    Filtrer
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($produits as $produit)
                            <div class="glassmorphism p-4 rounded-lg border border-gray-700 hover:bg-gray-700/30 transition duration-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-white">{{ $produit->marque }} {{ $produit->modele }}</h4>
                                    <span class="badge {{ $produit->utilisation == 'indoor' ? 'badge-info' : 'badge-warning' }}">
                                        {{ $produit->utilisation == 'indoor' ? 'Indoor' : 'Outdoor' }}
                                    </span>
                                </div>

                                <div class="text-sm mb-3 text-gray-300">
                                    <p><span class="font-medium">Pitch:</span> {{ $produit->pitch }} mm</p>
                                    <p><span class="font-medium">Électronique:</span> 
                                        @if($produit->electronique == 'autre')
                                            {{ $produit->electronique_detail }}
                                        @else
                                            {{ ucfirst($produit->electronique) }}
                                        @endif
                                    </p>
                                    @if($produit->description)
                                        <p class="mt-2 text-gray-400">{{ Str::limit($produit->description, 100) }}</p>
                                    @endif
                                </div>

                                <div class="flex justify-between mt-4">
                                    <a href="{{ route('produits-catalogue.show', $produit) }}" 
                                       class="btn-action btn-primary flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Voir
                                    </a>
                                    <a href="{{ route('produits-catalogue.edit', $produit) }}" 
                                       class="btn-action btn-secondary flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Modifier
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8 glassmorphism rounded-lg border border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-gray-400 mb-4">Aucun produit trouvé dans le catalogue.</p>
                                <a href="{{ route('produits-catalogue.create') }}" class="inline-block px-4 py-2 bg-accent-yellow text-white rounded-lg hover:bg-yellow-500 transition duration-150 ease-in-out">
                                    Ajouter un produit
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>