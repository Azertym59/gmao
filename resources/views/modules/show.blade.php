<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du module') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-semibold">Module #{{ $module->id }}</h3>
                        <div>
                            <a href="{{ route('modules.edit', $module) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mr-2">
                                {{ __('Modifier') }}
                            </a>
                            <a href="{{ route('dalles.show', $module->dalle) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                                {{ __('Retour à la dalle') }}
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Informations module</h4>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="mb-2">
                                    <span class="font-semibold">Dimensions:</span> {{ $module->largeur }} × {{ $module->hauteur }} mm
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Résolution:</span> {{ $module->nb_pixels_largeur }} × {{ $module->nb_pixels_hauteur }} pixels
                                </div>
                                @if($module->reference_module)
                                <div class="mb-2">
                                    <span class="font-semibold">Référence:</span> {{ $module->reference_module }}
                                </div>
                                @endif
                                <div>
                                    <span class="font-semibold">État:</span>
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
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Composants électroniques</h4>
                            <div class="bg-gray-50 p-4 rounded-md">
                                @if($module->carte_reception)
                                <div class="mb-2">
                                    <span class="font-semibold">Carte de réception:</span> {{ $module->carte_reception }}
                                </div>
                                @endif
                                @if($module->hub)
                                <div class="mb-2">
                                    <span class="font-semibold">Hub:</span> {{ $module->hub }}
                                </div>
                                @endif
                                @if($module->driver)
                                <div class="mb-2">
                                    <span class="font-semibold">Driver:</span> {{ $module->driver }}
                                </div>
                                @endif
                                @if($module->shift_register)
                                <div class="mb-2">
                                    <span class="font-semibold">Shift Register:</span> {{ $module->shift_register }}
                                </div>
                                @endif
                                @if($module->buffer)
                                <div class="mb-2">
                                    <span class="font-semibold">Buffer:</span> {{ $module->buffer }}
                                </div>
                                @endif
                                @if(!$module->carte_reception && !$module->hub && !$module->driver && !$module->shift_register && !$module->buffer)
                                <div class="text-gray-500 italic">
                                    Aucune information disponible sur les composants
                                </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Dalle</h4>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="mb-2">
                                    <span class="font-semibold">Dalle:</span>
                                    <a href="{{ route('dalles.show', $module->dalle) }}" class="text-blue-500 hover:underline">
                                        Dalle #{{ $module->dalle->id }}
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Dimensions dalle:</span> {{ $module->dalle->largeur }} × {{ $module->dalle->hauteur }} mm
                                </div>
                                <div>
                                    <span class="font-semibold">Alimentation:</span> {{ $module->dalle->alimentation }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Produit & Chantier</h4>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="mb-2">
                                    <span class="font-semibold">Produit:</span>
                                    <a href="{{ route('produits.show', $module->dalle->produit) }}" class="text-blue-500 hover:underline">
                                        {{ $module->dalle->produit->marque }} {{ $module->dalle->produit->modele }}
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Chantier:</span>
                                    <a href="{{ route('chantiers.show', $module->dalle->produit->chantier) }}" class="text-blue-500 hover:underline">
                                        {{ $module->dalle->produit->chantier->nom }}
                                    </a>
                                </div>
                                <div>
                                    <span class="font-semibold">Client:</span>
                                    {{ $module->dalle->produit->chantier->client->nom_complet }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Interventions sur ce module -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-medium text-gray-700">Interventions</h4>
                            <a href="{{ route('interventions.create', ['module_id' => $module->id]) }}"
                               class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600">
                                {{ __('+ Nouvelle intervention') }}
                            </a>
                        </div>
                        
                        @if($module->interventions->count() > 0)
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">Date</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">Technicien</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">Durée</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">Diagnostic</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">Réparation</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($module->interventions as $intervention)
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                {{ $intervention->date_debut->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                {{ $intervention->technicien->name }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                @php
                                                    $heures = floor($intervention->temps_total / 3600);
                                                    $minutes = floor(($intervention->temps_total % 3600) / 60);
                                                    $secondes = $intervention->temps_total % 60;
                                                    echo sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
                                                @endphp
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                @if($intervention->diagnostic)
                                                    {{ $intervention->diagnostic->nb_leds_hs }} LEDs HS,
                                                    {{ $intervention->diagnostic->nb_ic_hs }} ICs HS
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                @if($intervention->reparation)
                                                    {{ $intervention->reparation->nb_leds_remplacees }} LEDs,
                                                    {{ $intervention->reparation->nb_ic_remplaces }} ICs
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                <a href="{{ route('interventions.show', $intervention) }}" class="text-blue-500 hover:underline">Voir</a>
                                                <a href="{{ route('qrcode.module.print', $module->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                Imprimer QR Code
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="bg-gray-50 p-4 rounded-md text-center text-gray-500">
                                Ce module n'a pas encore d'interventions.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
