<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modules') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-semibold">Liste des modules</h3>
                        <a href="{{ route('modules.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Ajouter un module
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filtres -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-700 mb-2">Filtres</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <select id="filter-etat" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tous les états</option>
                                    <option value="non_commence">Non commencé</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="defaillant">Défaillant</option>
                                    <option value="termine">Terminé</option>
                                </select>
                            </div>
                            <div>
                                <input type="text" id="filter-search" placeholder="Rechercher..." class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>

                    @if($modules->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach ($modules as $module)
                                <div class="border rounded-lg p-4 hover:bg-gray-50 {{ $module->etat == 'defaillant' ? 'border-red-300 bg-red-50' : '' }} {{ $module->etat == 'termine' ? 'border-green-300 bg-green-50' : '' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-medium">Module #{{ $module->id }}</h5>
                                        @if($module->etat == 'non_commence')
                                            <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-xs">Non commencé</span>
                                        @elseif($module->etat == 'en_cours')
                                            <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs">En cours</span>
                                        @elseif($module->etat == 'defaillant')
                                            <span class="px-2 py-1 bg-red-200 text-red-800 rounded-full text-xs">Défaillant</span>
                                        @else
                                            <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-xs">Terminé</span>
                                        @endif
                                    </div>
                                    
                                    <div class="text-sm mb-3">
                                        <p><span class="font-medium">Dimensions:</span> {{ $module->largeur }}×{{ $module->hauteur }} mm</p>
                                        <p><span class="font-medium">Résolution:</span> {{ $module->nb_pixels_largeur }}×{{ $module->nb_pixels_hauteur }} px</p>
                                        <p><span class="font-medium">Produit:</span> {{ $module->dalle->produit->marque }} {{ $module->dalle->produit->modele }}</p>
                                        <p><span class="font-medium">Chantier:</span> {{ $module->dalle->produit->chantier->nom }}</p>
                                    </div>
                                    
                                    <div class="mt-4 flex justify-end">
                                        <a href="{{ route('modules.show', $module) }}" 
                                           class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                                            Détails
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded-md text-center text-gray-500">
                            Aucun module trouvé. Commencez par en créer un!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
