<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la dalle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-semibold">Dalle #{{ $dalle->id }}</h3>
                        <div>
                            <a href="{{ route('dalles.edit', $dalle) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mr-2">
                                {{ __('Modifier') }}
                            </a>
                            <a href="{{ route('produits.show', $dalle->produit) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                                {{ __('Retour au produit') }}
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Informations dalle</h4>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="mb-2">
                                    <span class="font-semibold">Dimensions:</span> {{ $dalle->largeur }} × {{ $dalle->hauteur }} mm
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Nombre de modules:</span> {{ $dalle->nb_modules }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Alimentation:</span> {{ $dalle->alimentation }}
                                </div>
                                @if($dalle->reference_dalle)
                                <div class="mb-2">
                                    <span class="font-semibold">Référence:</span> {{ $dalle->reference_dalle }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Informations produit</h4>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="mb-2">
                                    <span class="font-semibold">Produit:</span> 
                                    <a href="{{ route('produits.show', $dalle->produit) }}" class="text-blue-500 hover:underline">
                                        {{ $dalle->produit->marque }} {{ $dalle->produit->modele }}
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Pitch:</span> {{ $dalle->produit->pitch }} mm
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Chantier:</span> 
                                    <a href="{{ route('chantiers.show', $dalle->produit->chantier) }}" class="text-blue-500 hover:underline">
                                        {{ $dalle->produit->chantier->nom }}
                                    </a>
                                </div>
                                <div>
                                    <span class="font-semibold">Client:</span> 
                                    {{ $dalle->produit->chantier->client->nom_complet }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modules de la dalle -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-medium text-gray-700">Modules</h4>
                            <a href="{{ route('modules.create', ['dalle_id' => $dalle->id]) }}" 
                               class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600">
                                {{ __('+ Nouveau module') }}
                            </a>
                        </div>
                        
                        @if($dalle->modules->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                @foreach($dalle->modules as $module)
                                    <div class="border rounded-lg p-3 hover:bg-gray-50 {{ $module->etat == 'defaillant' ? 'border-red-300 bg-red-50' : '' }} {{ $module->etat == 'termine' ? 'border-green-300 bg-green-50' : '' }}">
                                        <h5 class="font-medium text-center mb-2">Module #{{ $module->id }}</h5>
                                        <div class="text-xs text-center">
                                            {{ $module->nb_pixels_largeur }}×{{ $module->nb_pixels_hauteur }} pixels
                                        </div>
                                        <div class="mt-2 text-center">
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
                                        <div class="mt-2 text-center">
                                            <a href="{{ route('modules.show', $module) }}" 
                                               class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                                                Détails
                                            </a>
                                        </div>
                                        <a href="{{ route('qrcode.dalle.print', $dalle->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Imprimer QR Code
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-md text-center text-gray-500">
                                Cette dalle n'a pas encore de modules.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
