<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sélectionner une dalle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Sélectionnez une dalle pour ajouter un module</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse ($dalles as $dalle)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <h4 class="font-medium text-lg mb-2">Dalle #{{ $dalle->id }}</h4>
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Dimensions:</span> {{ $dalle->largeur }}×{{ $dalle->hauteur }} mm
                                </p>
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Produit:</span> {{ $dalle->produit->marque }} {{ $dalle->produit->modele }}
                                </p>
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Chantier:</span> {{ $dalle->produit->chantier->nom }}
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('modules.create', ['dalle_id' => $dalle->id]) }}" 
                                       class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                        Créer un module
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8">
                                <p class="text-gray-500 mb-4">Aucune dalle trouvée. Vous devez d'abord créer une dalle.</p>
                                <a href="{{ route('dalles.create') }}" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Créer une dalle
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
