<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Détails du produit') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-semibold text-white">{{ $produit->marque }} {{ $produit->modele }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('produits-catalogue.edit', $produit) }}" class="btn-action btn-primary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Modifier') }}
                            </a>
                            <a href="{{ route('produits-catalogue.index') }}" class="btn-action btn-secondary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                {{ __('Retour') }}
                            </a>
                            <form method="POST" action="{{ route('produits-catalogue.destroy', $produit) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit du catalogue?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action bg-red-600 hover:bg-red-700 text-white flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    {{ __('Supprimer') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Spécifications techniques</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Marque:</span> <span class="text-gray-400">{{ $produit->marque }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Modèle:</span> <span class="text-gray-400">{{ $produit->modele }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Pitch:</span> <span class="text-gray-400">{{ $produit->pitch }} mm</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Utilisation:</span> 
                                    <span class="badge {{ $produit->utilisation == 'indoor' ? 'badge-info' : 'badge-warning' }}">
                                        {{ $produit->getUtilisationFormattedAttribute() }}
                                    </span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-300">Électronique:</span> 
                                    <span class="text-gray-400">{{ $produit->getElectroniqueFormattedAttribute() }}</span>
                                </div>
                            </div>
                        </div>

                        @if($produit->image_url)
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Image</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700 h-64 flex items-center justify-center">
                                <img src="{{ $produit->image_url }}" alt="{{ $produit->nom_complet }}" class="max-h-full max-w-full object-contain">
                            </div>
                        </div>
                        @endif

                        @if($produit->description)
                        <div class="md:col-span-2">
                            <h4 class="font-medium text-gray-300 mb-2">Description</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                <span class="text-gray-400">{{ $produit->description }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        <h4 class="font-medium text-gray-300 mb-2">Utiliser ce produit</h4>
                        <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                            <p class="text-gray-400 mb-4">Vous pouvez utiliser ce produit du catalogue lors de la création d'un nouveau chantier.</p>
                            <a href="{{ route('chantiers.create.step1') }}" class="btn-action btn-primary inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Créer un nouveau chantier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>