<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sélectionner un produit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Sélectionnez un produit pour ajouter une dalle</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse ($produits as $produit)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <h4 class="font-medium text-lg mb-2">{{ $produit->marque }} {{ $produit->modele }}</h4>
                                <p class="text-sm text-gray-600 mb-2">
                                    Chantier: {{ $produit->chantier->nom }}
                                </p>
                                <p class="text-sm text-gray-600 mb-2">
                                    Client: {{ $produit->chantier->client->nom_complet }}
                                </p>
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Pitch:</span> {{ $produit->pitch }} mm
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('dalles.create', ['produit_id' => $produit->id]) }}" 
                                       class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                        Créer une dalle
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8">
                                <p class="text-gray-500 mb-4">Aucun produit trouvé. Vous devez d'abord créer un produit.</p>
                                <a href="{{ route('produits.create') }}" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Créer un produit
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
