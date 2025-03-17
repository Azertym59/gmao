<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Détails de la dalle') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-semibold text-white">Dalle #{{ $dalle->id }}</h3>
                        <div>
                            <a href="{{ route('dalles.edit', $dalle) }}" class="btn-action btn-primary mr-2">
                                {{ __('Modifier') }}
                            </a>
                            <a href="{{ route('produits.show', $dalle->produit) }}" class="btn-action btn-secondary">
                                {{ __('Retour au produit') }}
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-accent-blue mb-2">Informations dalle</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-blue">
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
                                @if($dalle->numero_dalle)
                                <div class="mb-2">
                                    <span class="font-semibold">Numéro dalle (usine/client):</span> {{ $dalle->numero_dalle }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-accent-green mb-2">Informations produit</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-green">
                                <div class="mb-2">
                                    <span class="font-semibold">Produit:</span> 
                                    <a href="{{ route('produits.show', $dalle->produit) }}" class="text-accent-blue hover:underline">
                                        {{ $dalle->produit->marque }} {{ $dalle->produit->modele }}
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Pitch:</span> {{ $dalle->produit->pitch }} mm
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Chantier:</span> 
                                    <a href="{{ route('chantiers.show', $dalle->produit->chantier) }}" class="text-accent-blue hover:underline">
                                        {{ $dalle->produit->chantier->nom }}
                                    </a>
                                </div>
                                <div>
                                    <span class="font-semibold">Client:</span> 
                                    {{ $dalle->produit->chantier->client->nom_complet }}
                                    @if($dalle->produit->chantier->client->societe)
                                        <span class="text-gray-400">({{ $dalle->produit->chantier->client->societe }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modules de la dalle -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-medium text-accent-purple">Modules</h4>
                            <a href="{{ route('modules.create', ['dalle_id' => $dalle->id]) }}" 
                               class="btn-action btn-success text-sm">
                                {{ __('+ Nouveau module') }}
                            </a>
                        </div>
                        
                        @if($dalle->modules->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                @foreach($dalle->modules as $module)
                                    <div class="glassmorphism rounded-lg p-3 border
                                        {{ $module->etat == 'defaillant' ? 'border-opacity-30 border-accent-red bg-accent-red/5' : 'border-opacity-10' }}
                                        {{ $module->etat == 'termine' ? 'border-opacity-30 border-accent-green bg-accent-green/5' : '' }}">
                                        <h5 class="font-medium text-center mb-2">Module #{{ $module->id }}</h5>
                                        <div class="text-xs text-center text-gray-300">
                                            {{ $module->nb_pixels_largeur }}×{{ $module->nb_pixels_hauteur }} pixels
                                        </div>
                                        <div class="mt-2 text-center">
                                            @if($module->etat == 'non_commence')
                                                <span class="badge badge-info">Non commencé</span>
                                            @elseif($module->etat == 'en_cours')
                                                <span class="badge badge-warning">En cours</span>
                                            @elseif($module->etat == 'defaillant')
                                                <span class="badge badge-danger">Défaillant</span>
                                            @else
                                                <span class="badge badge-success">Terminé</span>
                                            @endif
                                        </div>
                                        <div class="mt-2 text-center">
                                            <a href="{{ route('modules.show', $module) }}" 
                                               class="btn-action btn-primary text-xs">
                                                Détails
                                            </a>
                                        </div>
                                        <div class="mt-2 text-center">
                                            <a href="{{ route('qrcode.dalle.print', $dalle->id) }}" class="btn-action btn-secondary text-xs">
                                                Imprimer QR Code
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="glassmorphism p-4 rounded-lg text-center text-gray-400">
                                Cette dalle n'a pas encore de modules.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
