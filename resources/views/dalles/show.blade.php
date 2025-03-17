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
                            <x-edit-button :route="route('dalles.edit', $dalle)" />
                            <x-back-button :route="route('produits.show', $dalle->produit)" />
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
                            <x-add-button :route="route('modules.create', ['dalle_id' => $dalle->id])">
                                {{ __('Nouveau module') }}
                            </x-add-button>
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
                                            <x-status-badge :status="$module->etat" />
                                        </div>
                                        <div class="mt-2 text-center">
                                            <x-view-button :route="route('modules.show', $module)" size="xs" />
                                        </div>
                                        <div class="mt-2 text-center">
                                            <x-print-button 
                                                tag="a" 
                                                route="{{ route('qrcode.dalle.print', $dalle->id) }}" 
                                                type="qrcode"
                                                size="sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4M4 8h16V5a1 1 0 00-1-1H5a1 1 0 00-1 1v3zm16 4v7a1 1 0 01-1 1H5a1 1 0 01-1-1v-7" />
                                                </svg>
                                                QR Code
                                            </x-print-button>
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
