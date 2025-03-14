<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Informations QR Code - Dalle') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">Dalle: {{ $dalle->reference_dalle ?: "ID #".$dalle->id }}</h3>
                        <a href="{{ route('dalles.show', $dalle->id) }}" class="btn-action btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Voir détails complets
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-accent-blue mb-2">Informations générales</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-blue">
                                <div class="grid grid-cols-1 gap-2">
                                    <div>
                                        <span class="font-semibold">Produit:</span> 
                                        <span class="text-gray-300">{{ $dalle->produit->marque }} {{ $dalle->produit->modele }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Chantier:</span> 
                                        <span class="text-gray-300">{{ $dalle->produit->chantier->nom }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Client:</span> 
                                        <span class="text-gray-300">{{ $dalle->produit->chantier->client->nom_complet }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-accent-green mb-2">Caractéristiques techniques</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-green">
                                <div class="grid grid-cols-1 gap-2">
                                    <div>
                                        <span class="font-semibold">Dimensions:</span> 
                                        <span class="text-gray-300">{{ $dalle->largeur }} × {{ $dalle->hauteur }} mm</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Carte réception:</span> 
                                        <span class="text-gray-300">{{ $dalle->carte_reception ?? 'Non spécifiée' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Hub:</span> 
                                        <span class="text-gray-300">{{ $dalle->hub ?? 'Non spécifié' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h4 class="font-medium text-accent-purple mb-4">Modules installés</h4>
                        
                        @if($dalle->modules->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 text-left">Référence</th>
                                            <th class="py-2 px-4 text-left">Dimensions</th>
                                            <th class="py-2 px-4 text-left">État</th>
                                            <th class="py-2 px-4 text-left">Dernière intervention</th>
                                            <th class="py-2 px-4 text-left">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dalle->modules as $module)
                                        <tr class="border-t border-gray-700 hover:bg-gray-800/50">
                                            <td class="py-3 px-4">{{ $module->reference_module }}</td>
                                            <td class="py-3 px-4">{{ $module->largeur }}×{{ $module->hauteur }} mm</td>
                                            <td class="py-3 px-4">
                                                @if($module->etat == 'non_commence')
                                                    <span class="badge badge-info">Non commencé</span>
                                                @elseif($module->etat == 'en_cours')
                                                    <span class="badge badge-warning">En cours</span>
                                                @elseif($module->etat == 'defaillant')
                                                    <span class="badge badge-danger">Défaillant</span>
                                                @else
                                                    <span class="badge badge-success">Terminé</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                @if($module->interventions && $module->interventions->count() > 0)
                                                    {{ $module->interventions->sortByDesc('date')->first() ? $module->interventions->sortByDesc('date_debut')->first()->date_debut->format('d/m/Y') : 'Aucune' }}
                                                @else
                                                    <span class="text-gray-500">Aucune</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('qrcode.module.show', $module->id) }}" class="btn-action btn-primary text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Détails
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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