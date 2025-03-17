<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dalles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-semibold">Liste des dalles</h3>
                        <a href="{{ route('dalles.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Ajouter une dalle
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($dalles->count() > 0)
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">ID</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">Dimensions</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">Modules</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">Produit</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">Chantier</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dalles as $dalle)
                                    <tr>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $dalle->id }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $dalle->largeur }} × {{ $dalle->hauteur }} mm</td>
                                        <td class="py-2 px-4 border-b border-gray-200">{{ $dalle->nb_modules }}</td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <a href="{{ route('produits.show', $dalle->produit) }}" class="text-blue-500 hover:underline">
                                                {{ $dalle->produit->marque }} {{ $dalle->produit->modele }}
                                            </a>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <a href="{{ route('chantiers.show', $dalle->produit->chantier) }}" class="text-blue-500 hover:underline">
                                                {{ $dalle->produit->chantier->nom }}
                                            </a>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-200">
                                            <a href="{{ route('dalles.show', $dalle) }}" class="text-blue-500 hover:underline">Voir</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="bg-gray-50 p-4 rounded-md text-center text-gray-500">
                            Aucune dalle trouvée. Commencez par en créer une!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
