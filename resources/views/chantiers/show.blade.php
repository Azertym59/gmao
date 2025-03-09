<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Détails du chantier') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">{{ $chantier->nom }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('chantiers.edit', $chantier) }}" 
                               class="btn-action btn-secondary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Modifier') }}
                            </a>
                            <a href="{{ route('chantiers.index') }}" 
                               class="btn-action btn-primary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                {{ __('Retour') }}
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Informations générales</h4>
                            <div class="glassmorphism p-4 rounded-xl text-gray-200 border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold">Référence:</span> {{ $chantier->reference }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Client:</span> 
                                    <a href="{{ route('clients.show', $chantier->client) }}" class="text-accent-blue hover:underline">
                                        {{ $chantier->client->nom_complet }}
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Société:</span> {{ $chantier->client->societe ?? 'Non spécifié' }}
                                </div>
                                <div>
                                    <span class="font-semibold">État:</span> 
                                    @if($chantier->etat == 'non_commence')
                                        <span class="badge badge-info">Non commencé</span>
                                    @elseif($chantier->etat == 'en_cours')
                                        <span class="badge badge-warning">En cours</span>
                                    @else
                                        <span class="badge badge-success">Terminé</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Dates</h4>
                            <div class="glassmorphism p-4 rounded-xl text-gray-200 border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold">Date de réception:</span> {{ $chantier->date_reception->format('d/m/Y') }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Date butoir:</span> {{ $chantier->date_butoir->format('d/m/Y') }}
                                </div>
                                <div>
                                    <span class="font-semibold">Créé le:</span> {{ $chantier->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        @if($chantier->description)
                        <div class="md:col-span-2">
                            <h4 class="font-medium text-gray-300 mb-2">Description</h4>
                            <div class="glassmorphism p-4 rounded-xl text-gray-200 border border-gray-700">
                                {{ $chantier->description }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Produits et dalles du chantier -->
                    <div class="mt-8">
                        <h4 class="font-medium text-gray-300 mb-4">Avancement du chantier</h4>
                        
                        @if($chantier->produits->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Résumé de l'avancement -->
                                <div class="glassmorphism p-4 rounded-xl border border-gray-700">
                                    <h5 class="font-medium text-lg text-white mb-3">Avancement global</h5>
                                    @php
                                        $totalModules = 0;
                                        $modulesTermines = 0;
                                        $modulesEnCours = 0;
                                        $modulesDefaillants = 0;
                                        $modulesNonCommences = 0;
                                        
                                        foreach($chantier->produits as $produit) {
                                            foreach($produit->dalles as $dalle) {
                                                $totalModules += $dalle->modules->count();
                                                $modulesTermines += $dalle->modules->where('etat', 'termine')->count();
                                                $modulesEnCours += $dalle->modules->where('etat', 'en_cours')->count();
                                                $modulesDefaillants += $dalle->modules->where('etat', 'defaillant')->count();
                                                $modulesNonCommences += $dalle->modules->where('etat', 'non_commence')->count();
                                            }
                                        }
                                        
                                        $pourcentageTermines = $totalModules > 0 ? round(($modulesTermines / $totalModules) * 100) : 0;
                                    @endphp
                                    
                                    <!-- Barre de progression -->
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm mb-1 text-gray-300">
                                            <span>Progression: {{ $pourcentageTermines }}%</span>
                                            <span>{{ $modulesTermines }}/{{ $totalModules }} modules</span>
                                        </div>
                                        <div class="h-4 w-full bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-accent-green rounded-full" style="width: {{ $pourcentageTermines }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Statistiques des modules -->
                                    <div class="grid grid-cols-2 gap-3 text-sm text-gray-300">
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 rounded-full bg-accent-green mr-2"></span>
                                            <span>Terminés: {{ $modulesTermines }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 rounded-full bg-accent-yellow mr-2"></span>
                                            <span>En cours: {{ $modulesEnCours }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 rounded-full bg-accent-red mr-2"></span>
                                            <span>Défaillants: {{ $modulesDefaillants }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 rounded-full bg-gray-500 mr-2"></span>
                                            <span>Non commencés: {{ $modulesNonCommences }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Informations générales -->
                                <div class="glassmorphism p-4 rounded-xl border border-gray-700">
                                    <h5 class="font-medium text-lg text-white mb-3">Détails</h5>
                                    <p class="text-gray-300"><span class="font-medium">Nombre de produits:</span> {{ $chantier->produits->count() }}</p>
                                    <p class="text-gray-300"><span class="font-medium">Nombre de dalles:</span> {{ $chantier->produits->sum(function($produit) { return $produit->dalles->count(); }) }}</p>
                                    <p class="text-gray-300"><span class="font-medium">Nombre de modules:</span> {{ $totalModules }}</p>
                                    <p class="mt-2 text-gray-300"><span class="font-medium">Date réception:</span> {{ $chantier->date_reception->format('d/m/Y') }}</p>
                                    <p class="text-gray-300"><span class="font-medium">Date butoir:</span> {{ $chantier->date_butoir->format('d/m/Y') }}</p>
                                    <p class="mt-2 text-gray-300">
                                        <span class="font-medium">Temps restant:</span>
                                        @php
                                            $daysLeft = now()->diffInDays($chantier->date_butoir, false);
                                        @endphp
                                        @if($daysLeft < 0)
                                            <span class="text-accent-red font-semibold">Dépassé de {{ abs($daysLeft) }} jour(s)</span>
                                        @elseif($daysLeft == 0)
                                            <span class="text-accent-yellow font-semibold">Dernier jour</span>
                                        @else
                                            <span class="text-accent-green font-semibold">{{ $daysLeft }} jour(s)</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Liste des produits avec leur avancement -->
                            @foreach($chantier->produits as $produit)
                                <div class="glassmorphism p-4 rounded-xl border border-gray-700 mb-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <h5 class="font-medium text-lg text-white">{{ $produit->marque }} {{ $produit->modele }}</h5>
                                        <a href="{{ route('produits.show', $produit) }}" class="btn-action btn-primary flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Détails
                                        </a>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-4 text-sm text-gray-300">
                                        <div><span class="font-medium">Pitch:</span> {{ $produit->pitch }} mm</div>
                                        <div><span class="font-medium">Utilisation:</span> {{ $produit->utilisation == 'indoor' ? 'Indoor' : 'Outdoor' }}</div>
                                        <div>
                                            <span class="font-medium">Électronique:</span> 
                                            @if($produit->electronique == 'autre')
                                                {{ $produit->electronique_detail }}
                                            @else
                                                {{ ucfirst($produit->electronique) }}
                                            @endif
                                        </div>
                                        <div><span class="font-medium">Dalles:</span> {{ $produit->dalles->count() }}</div>
                                    </div>
                                    
                                    <!-- Liste des dalles avec progrès -->
                                    @if($produit->dalles->count() > 0)
                                        <h6 class="font-medium text-gray-300 mb-2">Dalles</h6>
                                        <div class="overflow-x-auto rounded-lg shadow-lg">
                                            <table class="min-w-full table-styled">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="py-3 px-4 text-left">Dalle</th>
                                                        <th scope="col" class="px-3 py-3 text-left">Dimensions</th>
                                                        <th scope="col" class="px-3 py-3 text-left">Modules</th>
                                                        <th scope="col" class="px-3 py-3 text-left">Progrès</th>
                                                        <th scope="col" class="relative py-3 pl-3 pr-4">
                                                            <span class="sr-only">Actions</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($produit->dalles as $dalle)
                                                    <tr>
                                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium">
                                                            Dalle #{{ $dalle->id }}
                                                            @if($dalle->reference_dalle)
                                                                <span class="text-gray-400 text-xs block">{{ $dalle->reference_dalle }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                            {{ $dalle->largeur }} × {{ $dalle->hauteur }} mm
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                            <div class="flex space-x-1">
                                                                <span class="badge badge-success">
                                                                    {{ $dalle->modules->where('etat', 'termine')->count() }} terminés
                                                                </span>
                                                                @if($dalle->modules->where('etat', 'en_cours')->count() > 0)
                                                                    <span class="badge badge-warning">
                                                                        {{ $dalle->modules->where('etat', 'en_cours')->count() }} en cours
                                                                    </span>
                                                                @endif
                                                                @if($dalle->modules->where('etat', 'defaillant')->count() > 0)
                                                                    <span class="badge badge-danger">
                                                                        {{ $dalle->modules->where('etat', 'defaillant')->count() }} défaillants
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                            <div class="w-32">
                                                                <div class="flex justify-between text-xs mb-1">
                                                                    <span>{{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%</span>
                                                                    <span>{{ $dalle->modules->where('etat', 'termine')->count() }}/{{ $dalle->modules->count() }}</span>
                                                                </div>
                                                                <div class="h-2 w-full bg-gray-700 rounded-full overflow-hidden">
                                                                    <div class="h-full bg-accent-green rounded-full" style="width: {{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium">
                                                            <a href="{{ route('dalles.show', $dalle) }}" class="text-accent-blue hover:text-blue-500">
                                                                Voir détails
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Aperçu visuel des modules -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                                            @foreach($produit->dalles as $dalle)
                                                <div class="glassmorphism border border-gray-700 rounded-xl p-4">
                                                    <div class="flex justify-between items-center mb-3">
                                                        <h6 class="font-medium text-white">Dalle #{{ $dalle->id }} - Aperçu des modules</h6>
                                                        <a href="{{ route('dalles.show', $dalle) }}" class="text-xs text-accent-blue hover:text-blue-400">Détails</a>
                                                    </div>
                                                    <div class="grid grid-cols-5 sm:grid-cols-8 gap-1">
                                                        @foreach($dalle->modules as $module)
                                                            <a href="{{ route('modules.show', $module) }}" class="block">
                                                                <div class="aspect-square 
                                                                    @if($module->etat == 'termine') bg-accent-green
                                                                    @elseif($module->etat == 'en_cours') bg-accent-yellow
                                                                    @elseif($module->etat == 'defaillant') bg-accent-red
                                                                    @else bg-gray-600
                                                                    @endif
                                                                    rounded-md hover:opacity-75 transition-opacity hover:scale-110 duration-200"
                                                                    title="Module #{{ $module->id }} - {{ ucfirst($module->etat) }}"
                                                                ></div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="p-4 rounded-xl text-center text-gray-400 bg-gray-800/30">
                                            Ce produit n'a pas encore de dalles.
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="p-10 rounded-xl text-center text-gray-400 bg-gray-800/30 flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="mb-4">Ce chantier n'a pas encore de produits. Ajoutez d'abord un produit pour commencer.</p>
                                <a href="{{ route('produits.create') }}" class="btn-action btn-primary">
                                    Ajouter un produit
                                </a>
                            </div>
                        @endif
                    </div>
                    <!-- Ajoutez le bouton ICI -->
                    <div class="text-center mt-6">
                        <a href="{{ route('qrcode.chantier.print', $chantier->id) }}" 
                        class="btn-action btn-primary inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4M4 8h16V5a1 1 0 00-1-1H5a1 1 0 00-1 1v3zm16 4v7a1 1 0 01-1 1H5a1 1 0 01-1-1v-7" />
                            </svg>
                            Générer QR Code
                        </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>