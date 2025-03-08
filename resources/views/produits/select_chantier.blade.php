<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Sélectionner un chantier') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-white mb-6">Sélectionnez un chantier pour ajouter un produit</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse ($chantiers as $chantier)
                            <div class="glassmorphism border border-gray-700 hover:border-accent-pink rounded-xl p-6 hover:bg-gray-800/50 transition-all duration-200">
                                <h4 class="font-medium text-lg text-white mb-3">{{ $chantier->nom }}</h4>
                                <p class="text-sm text-gray-300 mb-2">
                                    <span class="font-medium">Client:</span> {{ $chantier->client->nom_complet }}
                                </p>
                                <p class="text-sm text-gray-300 mb-2">
                                    <span class="font-medium">Référence:</span> {{ $chantier->reference }}
                                </p>
                                <p class="text-sm text-gray-300 mb-2">
                                    <span class="font-medium">Date butoir:</span> {{ $chantier->date_butoir->format('d/m/Y') }}
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('produits.create', ['chantier_id' => $chantier->id]) }}" 
                                       class="w-full inline-flex justify-center items-center px-4 py-2 bg-accent-pink text-white rounded-lg hover:bg-pink-600 transition duration-150 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Créer un produit
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 p-10 rounded-xl text-center text-gray-400 bg-gray-800/30 flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p class="mb-4">Aucun chantier trouvé. Vous devez d'abord créer un chantier.</p>
                                <a href="{{ route('chantiers.create.step1') }}" class="btn-action btn-primary flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Créer un chantier
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>