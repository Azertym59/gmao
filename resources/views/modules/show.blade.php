<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-white leading-tight">
            {{ __('Détails du module') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl border border-gray-700">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-white">Module #{{ $module->id }}</h3>
                            <p class="text-sm text-gray-400">Référence: {{ $module->reference }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('modules.edit', $module) }}" class="btn-action btn-secondary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Modifier') }}
                            </a>
                            <a href="{{ route('dalles.show', $module->dalle) }}" class="btn-action btn-primary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                {{ __('Retour à la dalle') }}
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-accent-green mb-2">Informations module</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-green">
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Dimensions:</span> 
                                    <span class="text-gray-300">{{ $module->largeur }} × {{ $module->hauteur }} mm</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Résolution:</span> 
                                    <span class="text-gray-300">{{ $module->nb_pixels_largeur ?? '-' }} × {{ $module->nb_pixels_hauteur ?? '-' }} pixels</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Référence:</span> 
                                    <span class="text-gray-300">{{ $module->reference ?? 'Non renseignée' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Numéro du module:</span> 
                                    <span class="text-gray-300">{{ $module->reference_module ?? 'Non renseigné' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Numéro de série / ID Usine:</span> 
                                    <span class="text-gray-300">{{ $module->numero_serie ?? 'Non renseigné' }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-white">État:</span>
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
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-accent-blue mb-2">Composants électroniques</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-blue">
                                @if($module->carte_reception)
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Carte de réception:</span> 
                                    <span class="text-gray-300">{{ $module->carte_reception }}</span>
                                </div>
                                @endif
                                @if($module->hub)
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Hub:</span> 
                                    <span class="text-gray-300">{{ $module->hub }}</span>
                                </div>
                                @endif
                                @if($module->driver)
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Driver:</span> 
                                    <span class="text-gray-300">{{ $module->driver }}</span>
                                </div>
                                @endif
                                @if($module->shift_register)
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Shift Register:</span> 
                                    <span class="text-gray-300">{{ $module->shift_register }}</span>
                                </div>
                                @endif
                                @if($module->buffer)
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Buffer:</span> 
                                    <span class="text-gray-300">{{ $module->buffer }}</span>
                                </div>
                                @endif
                                @if(!$module->carte_reception && !$module->hub && !$module->driver && !$module->shift_register && !$module->buffer)
                                <div class="text-gray-400 italic">Aucun composant électronique spécifié</div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-accent-yellow mb-2">Dalle</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-yellow">
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Dalle:</span>
                                    <a href="{{ route('dalles.show', $module->dalle) }}" class="text-accent-blue hover:underline">
                                        Dalle #{{ $module->dalle->id }}
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Dimensions dalle:</span> 
                                    <span class="text-gray-300">{{ $module->dalle->largeur }} × {{ $module->dalle->hauteur }} mm</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-white">Alimentation:</span> 
                                    <span class="text-gray-300">{{ $module->dalle->alimentation ?? 'Non spécifiée' }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-accent-purple mb-2">Produit & Chantier</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-purple">
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Produit:</span>
                                    <a href="{{ route('produits.show', $module->dalle->produit) }}" class="text-accent-blue hover:underline">
                                        {{ $module->dalle->produit->marque }} {{ $module->dalle->produit->modele }}
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Chantier:</span>
                                    <a href="{{ route('chantiers.show', $module->dalle->produit->chantier) }}" class="text-accent-blue hover:underline">
                                        {{ $module->dalle->produit->chantier->nom }}
                                    </a>
                                </div>
                                <div>
                                    <span class="font-semibold text-white">Client:</span>
                                    <span class="text-gray-300">{{ $module->dalle->produit->chantier->client->nom_complet }}</span>
                                    @if($module->dalle->produit->chantier->client->societe)
                                        <span class="text-gray-500">({{ $module->dalle->produit->chantier->client->societe }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Interventions sur ce module -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-medium text-white">Historique des interventions</h4>
                            <a href="{{ route('interventions.create', ['module_id' => $module->id]) }}" 
                               class="btn-action btn-success text-sm">
                                {{ __('+ Nouvelle intervention') }}
                            </a>
                        </div>
                        
                        @if($module->interventions->count() > 0)
                            <div class="overflow-x-auto rounded-lg shadow-lg">
                                <table class="min-w-full table-styled">
                                    <thead>
                                        <tr>
                                            <th class="py-3 px-4 text-left">Date</th>
                                            <th class="py-3 px-4 text-left">Technicien</th>
                                            <th class="py-3 px-4 text-left">Durée</th>
                                            <th class="py-3 px-4 text-left">Diagnostic</th>
                                            <th class="py-3 px-4 text-left">Réparation</th>
                                            <th class="py-3 px-4 text-left">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($module->interventions as $intervention)
                                            <tr>
                                                <td class="py-3 px-4">
                                                    {{ $intervention->date_debut->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    {{ $intervention->technicien ? $intervention->technicien->name : "Non assigné" }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    @php
                                                        $heures = floor($intervention->temps_total / 3600);
                                                        $minutes = floor(($intervention->temps_total % 3600) / 60);
                                                        $secondes = $intervention->temps_total % 60;
                                                        echo sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
                                                    @endphp
                                                </td>
                                                <td class="py-3 px-4">
                                                    @if($intervention->diagnostic)
                                                        {{ (int)($intervention->diagnostic->nb_leds_hs) }} LEDs HS,
                                                        {{ (int)($intervention->diagnostic->nb_ic_hs) }} ICs HS,
                                                        {{ (int)($intervention->diagnostic->nb_masques_hs) }} masques HS
                                                        @if($intervention->diagnostic->pose_fake_pcb)
                                                            <br><span class="text-green-500">Fake PCB nécessaire</span>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4">
                                                    @if($intervention->reparation)
                                                        {{ (int)($intervention->reparation->nb_leds_remplacees) }} LEDs,
                                                        {{ (int)($intervention->reparation->nb_ic_remplaces) }} ICs,
                                                        {{ (int)($intervention->reparation->nb_masques_remplaces) }} masques
                                                        @if($intervention->reparation->fake_pcb_pose)
                                                            <br><span class="text-green-500">Fake PCB posé</span>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4">
                                                    <a href="{{ route('interventions.show', $intervention) }}" 
                                                      class="text-accent-blue hover:underline">
                                                        Détails
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="glassmorphism p-8 rounded-lg text-center text-gray-400 border border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p>Aucune intervention n'a encore été effectuée sur ce module.</p>
                                <a href="{{ route('interventions.create', ['module_id' => $module->id]) }}" 
                                   class="btn-action btn-primary mt-3 inline-flex items-center">
                                    {{ __('Commencer une intervention') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex justify-center mt-8">
                        <a href="{{ route('qrcode.module.print', $module->id) }}" class="btn-action btn-primary flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4M4 8h16V5a1 1 0 00-1-1H5a1 1 0 00-1 1v3zm16 4v7a1 1 0 01-1 1H5a1 1 0 01-1-1v-7" />
                            </svg>
                            Générer QR Code
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>