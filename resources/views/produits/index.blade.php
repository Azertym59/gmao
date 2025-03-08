<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Produits') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">Liste des produits</h3>
                        <a href="{{ route('produits.create') }}" class="px-4 py-2 bg-accent-pink text-white rounded-lg hover:bg-pink-600 transition duration-150 ease-in-out flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter un produit
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

                    @if($produits->count() > 0)
                        <div class="overflow-x-auto rounded-lg shadow-lg">
                            <table class="min-w-full table-styled border border-gray-700 bg-gray-800/50 text-gray-300">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4 text-left border-b border-gray-600">Marque</th>
                                        <th class="py-3 px-4 text-left border-b border-gray-600">Modèle</th>
                                        <th class="py-3 px-4 text-left border-b border-gray-600">Pitch</th>
                                        <th class="py-3 px-4 text-left border-b border-gray-600">Utilisation</th>
                                        <th class="py-3 px-4 text-left border-b border-gray-600">Chantier</th>
                                        <th class="py-3 px-4 text-left border-b border-gray-600">Client</th>
                                        <th class="py-3 px-4 text-left border-b border-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produits as $produit)
                                        <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition duration-200">
                                            <td class="py-3 px-4">{{ $produit->marque }}</td>
                                            <td class="py-3 px-4">{{ $produit->modele }}</td>
                                            <td class="py-3 px-4">{{ $produit->pitch }} mm</td>
                                            <td class="py-3 px-4">
                                                @if($produit->utilisation == 'indoor')
                                                    <span class="badge badge-info">Indoor</span>
                                                @else
                                                    <span class="badge badge-warning">Outdoor</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('chantiers.show', $produit->chantier) }}" class="text-accent-blue hover:underline">
                                                    {{ $produit->chantier->nom }}
                                                </a>
                                            </td>
                                            <td class="py-3 px-4">
                                                {{ $produit->chantier->client->nom_complet }}
                                            </td>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('produits.show', $produit) }}" 
                                                   class="btn-action btn-primary flex items-center w-max">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Voir
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-10 rounded-xl text-center text-gray-400 bg-gray-800/30 flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            <p class="mb-4">Aucun produit trouvé. Commencez par en créer un !</p>
                            <a href="{{ route('produits.create') }}" class="btn-action btn-primary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Créer mon premier produit
                            </a>
                        </div>
                    @endif
                    
                    @if(isset($produits) && method_exists($produits, 'links'))
                        <div class="mt-6">
                            {{ $produits->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>