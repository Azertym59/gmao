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
                            <a href="{{ route('etiquettes.chantier', $chantier->id) }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2 rounded-lg shadow transition-all duration-300 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Imprimer étiquette
                            </a>
                            <x-edit-button :route="route('chantiers.edit', $chantier)" />
                            <x-back-button :route="route('chantiers.index')" />
                        </div>
                    </div>
                    
                    <!-- Boutons d'envoi d'email -->
                    <div class="mt-4 mb-6 flex space-x-3 justify-end">
                        <x-email-button :chantier="$chantier" type="created" buttonType="info" />
                        <x-email-button :chantier="$chantier" type="started" buttonType="warning" />
                        <x-email-button :chantier="$chantier" type="completed" buttonType="success" />
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
                                <div class="flex items-center">
                                    <span class="font-semibold mr-2">État:</span> 
                                    <x-status-badge :status="$chantier->etat" />
                                    
                                    <!-- Boutons de changement d'état rapide -->
                                    <div class="ml-4 flex space-x-1">
                                        <x-state-button 
                                            state="non_commence"
                                            :route="route('chantiers.update.state', $chantier)"
                                            :model="$chantier"
                                            :currentState="$chantier->etat" />
                                        
                                        <x-state-button 
                                            state="en_cours"
                                            :route="route('chantiers.update.state', $chantier)"
                                            :model="$chantier"
                                            :currentState="$chantier->etat" />
                                        
                                        <x-state-button 
                                            state="termine"
                                            :route="route('chantiers.update.state', $chantier)"
                                            :model="$chantier"
                                            :currentState="$chantier->etat" />
                                    </div>
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
                                        <span class="{{ \App\Helpers\DateHelper::getTimeRemainingClass($chantier->date_butoir) }} font-semibold">
                                            {{ \App\Helpers\DateHelper::formatTimeRemaining($chantier->date_butoir) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Liste des produits avec leur avancement -->
                            @foreach($chantier->produits as $produit)
                                <div class="glassmorphism p-4 rounded-xl border border-gray-700 mb-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <h5 class="font-medium text-lg text-white">{{ $produit->marque }} {{ $produit->modele }}</h5>
                                        <x-view-button :route="route('produits.show', $produit)">
                                            Détails
                                        </x-view-button>
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
                                    
                                    <!-- Liste des dalles avec modules -->
                                    @if($produit->dalles->count() > 0)
                                        <h6 class="font-medium text-gray-300 mb-2">Dalles et modules</h6>
                                        
                                        @php
                                            // Organiser les dalles par flightcase et modules individuels
                                            $dallesGrouped = [
                                                'individuel' => [],
                                                'flightcases' => []
                                            ];
                                            
                                            foreach($produit->dalles as $dalle) {
                                                if($dalle->reference_dalle == "INDIVIDUEL") {
                                                    $dallesGrouped['individuel'][] = $dalle;
                                                } elseif(preg_match('/^FC(\d+)-D\d+$/', $dalle->reference_dalle, $matches)) {
                                                    $fcNumber = $matches[1];
                                                    if(!isset($dallesGrouped['flightcases'][$fcNumber])) {
                                                        $dallesGrouped['flightcases'][$fcNumber] = [];
                                                    }
                                                    $dallesGrouped['flightcases'][$fcNumber][] = $dalle;
                                                } else {
                                                    // Si aucun format reconnu, traiter comme une dalle indépendante
                                                    if(!isset($dallesGrouped['autres'])) {
                                                        $dallesGrouped['autres'] = [];
                                                    }
                                                    $dallesGrouped['autres'][] = $dalle;
                                                }
                                            }
                                            
                                            // Trier les flightcases par numéro
                                            ksort($dallesGrouped['flightcases']);
                                        @endphp
                                        
                                        <!-- Modules individuels -->
                                        @if(!empty($dallesGrouped['individuel']))
                                            <div class="glassmorphism border border-gray-700 rounded-xl p-4 mb-6" x-data="{ open: true }">
                                                <div @click="open = !open" class="flex justify-between items-center mb-3 cursor-pointer">
                                                    <h6 class="font-medium text-white flex items-center">
                                                        <span class="text-amber-400">Modules individuels</span>
                                                        
                                                        @foreach($dallesGrouped['individuel'] as $dalle)
                                                        <!-- Affichage du numéro de dalle ou formulaire de saisie rapide -->
                                                        <span class="ml-2" x-data="{ 
                                                            isEditing: false,
                                                            numeroValue: '{{ $dalle->numero_dalle }}',
                                                            initialValue: '{{ $dalle->numero_dalle }}',
                                                            toggleEdit() { this.isEditing = !this.isEditing; },
                                                            save() {
                                                                if (this.numeroValue.trim() === this.initialValue) {
                                                                    this.isEditing = false;
                                                                    return;
                                                                }
                                                                
                                                                fetch('{{ route('dalles.update.numero', $dalle->id) }}', {
                                                                    method: 'POST',
                                                                    headers: {
                                                                        'Content-Type': 'application/json',
                                                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                                    },
                                                                    body: JSON.stringify({ numero_dalle: this.numeroValue })
                                                                })
                                                                .then(response => response.json())
                                                                .then(data => {
                                                                    if (data.success) {
                                                                        this.isEditing = false;
                                                                        this.initialValue = this.numeroValue;
                                                                    }
                                                                });
                                                            },
                                                            cancel() {
                                                                this.numeroValue = this.initialValue;
                                                                this.isEditing = false;
                                                            }
                                                        }">
                                                            <!-- Affichage si on a déjà un numéro -->
                                                            <template x-if="!isEditing && initialValue">
                                                                <span @click.stop="toggleEdit()" class="cursor-pointer group">
                                                                    <span class="text-xs text-accent-blue">[N° <span x-text="initialValue"></span>]</span>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline ml-1 text-gray-400 group-hover:text-accent-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                    </svg>
                                                                </span>
                                                            </template>
                                                            
                                                            <!-- Bouton pour ajouter un numéro si on n'en a pas -->
                                                            <template x-if="!isEditing && !initialValue">
                                                                <button @click.stop="toggleEdit()" class="text-xs px-2 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded flex items-center shadow-md transition-all duration-150 transform hover:scale-105 border border-purple-500">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                    </svg>
                                                                    N° dalle
                                                                </button>
                                                            </template>
                                                            
                                                            <!-- Formulaire de saisie -->
                                                            <template x-if="isEditing">
                                                                <span class="flex items-center" @click.stop>
                                                                    <input 
                                                                        type="text" 
                                                                        x-model="numeroValue" 
                                                                        class="text-xs px-1 py-0.5 w-28 bg-gray-700 border border-accent-blue rounded" 
                                                                        placeholder="N° dalle"
                                                                        @keydown.enter="save()"
                                                                        @keydown.escape="cancel()"
                                                                    />
                                                                    <button @click="save()" class="ml-1 text-accent-green">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                    </button>
                                                                    <button @click="cancel()" class="ml-1 text-accent-red">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>
                                                                </span>
                                                            </template>
                                                        </span>
                                                        @endforeach
                                                    </h6>
                                                    <div class="flex items-center">
                                                        <div class="flex space-x-4 items-center mr-4">
                                                            @foreach($dallesGrouped['individuel'] as $dalle)
                                                                <div class="text-sm text-gray-300">
                                                                    <span class="mr-2">Progrès:</span>
                                                                    <span class="badge badge-success">
                                                                        {{ $dalle->modules->where('etat', 'termine')->count() }}/{{ $dalle->modules->count() }}
                                                                    </span>
                                                                </div>
                                                                <a href="{{ route('dalles.show', $dalle) }}" class="text-xs text-accent-blue hover:text-blue-400">Détails</a>
                                                            @endforeach
                                                        </div>
                                                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                
                                                <div x-show="open" x-transition:enter="transition ease-out duration-200" 
                                                     x-transition:enter-start="opacity-0 transform -translate-y-2" 
                                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                                     x-transition:leave="transition ease-in duration-200"
                                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                
                                                @foreach($dallesGrouped['individuel'] as $dalle)
                                                    <div class="flex flex-wrap gap-2 justify-center mt-3">
                                                        @foreach($dalle->modules as $module)
                                                            <div x-data="{ hover: false, showActions: false, moduleId: {{ $module->id }} }" 
                                                               class="relative group module-container">
                                                                <div class="aspect-square w-10 h-10 
                                                                    @if($module->etat == 'termine') bg-accent-green
                                                                    @elseif($module->etat == 'en_cours') bg-accent-yellow
                                                                    @elseif($module->etat == 'defaillant') bg-accent-red
                                                                    @else bg-gray-600
                                                                    @endif
                                                                    rounded-sm border border-black/10 transition-all hover:shadow-lg duration-150 transform hover:scale-105 cursor-pointer"
                                                                    title="Module #{{ $module->id }} - {{ ucfirst($module->etat) }}"
                                                                    @mouseenter="hover = true; showActions = true; if(window.moduleTimeoutId) clearTimeout(window.moduleTimeoutId)"
                                                                    @mouseleave="hover = false; window.moduleTimeoutId = setTimeout(() => showActions = false, 1000)"
                                                                    @click="window.location.href='{{ route('modules.show', $module) }}'"
                                                                >
                                                                    <div class="flex items-center justify-center h-full text-xs font-medium text-white/90">
                                                                        {{ preg_replace('/[^0-9]/', '', $module->reference_module) }}
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Nom du module (au survol) -->
                                                                <div x-show="hover" 
                                                                     x-transition:enter="transition ease-out duration-200"
                                                                     x-transition:enter-start="opacity-0 scale-95"
                                                                     x-transition:enter-end="opacity-100 scale-100"
                                                                     class="absolute top-[-20px] left-1/2 transform -translate-x-1/2 bg-gray-800 text-xs text-white p-1 rounded whitespace-nowrap z-10">
                                                                    Module #{{ $module->id }}
                                                                </div>
                                                                
                                                                <!-- Actions popup -->
                                                                <div x-show="showActions" 
                                                                     x-transition:enter="transition ease-out duration-200"
                                                                     x-transition:enter-start="opacity-0 scale-95"
                                                                     x-transition:enter-end="opacity-100 scale-100"
                                                                     x-transition:leave="transition ease-in duration-150"
                                                                     x-transition:leave-start="opacity-100 scale-100"
                                                                     x-transition:leave-end="opacity-0 scale-95"
                                                                     class="absolute -top-20 -right-2 bg-gray-900/95 border border-gray-700 rounded-lg p-2 shadow-xl z-20 flex flex-col gap-2 min-w-[120px]"
                                                                     
                                                                     @click.away="showActions = false"
                                                                     @keydown.escape.window="showActions = false">
                                                                    
                                                                    <!-- Marquer comme OK -->
                                                                    <button @click.stop="
                                                                        fetch('{{ route('modules.update', $module) }}', {
                                                                            method: 'PATCH',
                                                                            headers: {
                                                                                'Content-Type': 'application/json',
                                                                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                                            },
                                                                            body: JSON.stringify({
                                                                                dalle_id: '{{ $module->dalle_id }}',
                                                                                largeur: '{{ $module->largeur }}',
                                                                                hauteur: '{{ $module->hauteur }}',
                                                                                nb_pixels_largeur: '{{ $module->nb_pixels_largeur }}',
                                                                                nb_pixels_hauteur: '{{ $module->nb_pixels_hauteur }}',
                                                                                etat: 'termine',
                                                                                reference_module: '{{ $module->reference_module }}',
                                                                                numero_serie: '{{ $module->numero_serie }}',
                                                                                technicien_id: '{{ $module->technicien_id }}',
                                                                                position_x: '{{ $module->position_x }}',
                                                                                position_y: '{{ $module->position_y }}',
                                                                                position_lettre: '{{ $module->position_lettre }}',
                                                                                carte_reception: '{{ $module->carte_reception }}',
                                                                                hub: '{{ $module->hub }}',
                                                                                driver: '{{ $module->driver }}',
                                                                                shift_register: '{{ $module->shift_register }}',
                                                                                buffer: '{{ $module->buffer }}'
                                                                            })
                                                                        })
                                                                        .then(response => {
                                                                            if (response.ok) {
                                                                                window.location.reload();
                                                                            }
                                                                        });
                                                                    " class="flex items-center text-left text-xs px-2 py-1 text-green-400 hover:bg-green-900/30 rounded">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                        <span>Marquer OK</span>
                                                                    </button>
                                                                    
                                                                    <!-- Créer intervention -->
                                                                    <a href="{{ route('interventions.create', ['module_id' => $module->id]) }}" @click.stop class="flex items-center text-left text-xs px-2 py-1 text-yellow-400 hover:bg-yellow-900/30 rounded">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                                        </svg>
                                                                        <span>Intervention</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="w-full mt-3">
                                                        <div class="flex justify-between text-xs mb-1 text-gray-300">
                                                            <span>{{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%</span>
                                                            <span>{{ $dalle->modules->where('etat', 'termine')->count() }}/{{ $dalle->modules->count() }} modules</span>
                                                        </div>
                                                        <div class="h-2 w-full bg-gray-700 rounded-full overflow-hidden">
                                                            <div class="h-full bg-accent-green rounded-full" style="width: {{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%"></div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <!-- FlightCases -->
                                        @foreach($dallesGrouped['flightcases'] as $fcNumber => $dalles)
                                            <div class="mb-6" x-data="{ open: false }">
                                                <div @click="open = !open" class="flex items-center justify-between bg-gray-800/50 p-2 rounded-t-xl border-t border-l border-r border-gray-700 cursor-pointer">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8" />
                                                        </svg>
                                                        <h6 class="font-medium text-white">Flight Case #{{ $fcNumber }} - {{ count($dalles) }} dalles</h6>
                                                    </div>
                                                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                    </svg>
                                                </div>
                                                
                                                <div x-show="open" x-transition:enter="transition ease-out duration-200" 
                                                     x-transition:enter-start="opacity-0 transform -translate-y-2" 
                                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                                     x-transition:leave="transition ease-in duration-200"
                                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                     class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-900/30 rounded-b-xl border-b border-l border-r border-gray-700">
                                                    @foreach($dalles as $dalle)
                                                        <div class="glassmorphism border border-gray-700 rounded-xl p-4">
                                                            <div class="flex justify-between items-center mb-3">
                                                                <h6 class="font-medium text-white flex items-center flex-wrap">
                                                                    {{ $dalle->reference_dalle }} 
                                                                    <span class="text-xs text-gray-400 ml-1">({{ $dalle->largeur }}×{{ $dalle->hauteur }} mm)</span>
                                                                    
                                                                    <!-- Affichage du numéro de dalle ou formulaire de saisie rapide -->
                                                                    <span class="ml-2" x-data="{ 
                                                                        isEditing: false,
                                                                        numeroValue: '{{ $dalle->numero_dalle }}',
                                                                        initialValue: '{{ $dalle->numero_dalle }}',
                                                                        toggleEdit() { this.isEditing = !this.isEditing; },
                                                                        save() {
                                                                            if (this.numeroValue.trim() === this.initialValue) {
                                                                                this.isEditing = false;
                                                                                return;
                                                                            }
                                                                            
                                                                            fetch('{{ route('dalles.update.numero', $dalle->id) }}', {
                                                                                method: 'POST',
                                                                                headers: {
                                                                                    'Content-Type': 'application/json',
                                                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                                                },
                                                                                body: JSON.stringify({ numero_dalle: this.numeroValue })
                                                                            })
                                                                            .then(response => response.json())
                                                                            .then(data => {
                                                                                if (data.success) {
                                                                                    this.isEditing = false;
                                                                                    this.initialValue = this.numeroValue;
                                                                                }
                                                                            });
                                                                        },
                                                                        cancel() {
                                                                            this.numeroValue = this.initialValue;
                                                                            this.isEditing = false;
                                                                        }
                                                                    }">
                                                                        <!-- Affichage si on a déjà un numéro -->
                                                                        <template x-if="!isEditing && initialValue">
                                                                            <span @click="toggleEdit()" class="cursor-pointer group">
                                                                                <span class="text-xs text-accent-blue">[N° <span x-text="initialValue"></span>]</span>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline ml-1 text-gray-400 group-hover:text-accent-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                                </svg>
                                                                            </span>
                                                                        </template>
                                                                        
                                                                        <!-- Bouton pour ajouter un numéro si on n'en a pas -->
                                                                        <template x-if="!isEditing && !initialValue">
                                                                            <button @click="toggleEdit()" class="text-xs px-2 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded flex items-center shadow-md transition-all duration-150 transform hover:scale-105 border border-purple-500">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                                </svg>
                                                                                N° dalle
                                                                            </button>
                                                                        </template>
                                                                        
                                                                        <!-- Formulaire de saisie -->
                                                                        <template x-if="isEditing">
                                                                            <span class="flex items-center">
                                                                                <input 
                                                                                    type="text" 
                                                                                    x-model="numeroValue" 
                                                                                    class="text-xs px-1 py-0.5 w-28 bg-gray-700 border border-accent-blue rounded" 
                                                                                    placeholder="N° dalle"
                                                                                    @keydown.enter="save()"
                                                                                    @keydown.escape="cancel()"
                                                                                />
                                                                                <button @click="save()" class="ml-1 text-accent-green">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                                    </svg>
                                                                                </button>
                                                                                <button @click="cancel()" class="ml-1 text-accent-red">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                                    </svg>
                                                                                </button>
                                                                            </span>
                                                                        </template>
                                                                    </span>
                                                                </h6>
                                                                <div class="flex space-x-3 items-center">
                                                                    <div class="text-xs">
                                                                        <span class="badge badge-success">
                                                                            {{ $dalle->modules->where('etat', 'termine')->count() }}/{{ $dalle->modules->count() }}
                                                                        </span>
                                                                    </div>
                                                                    <a href="{{ route('dalles.show', $dalle) }}" class="text-xs text-accent-blue hover:text-blue-400">Détails</a>
                                                                </div>
                                                            </div>
                                                            
                                                            @php
                                                                // Extraire les dimensions depuis disposition_modules
                                                                $nbColonnes = 2; // Par défaut 2x2
                                                                $nbLignes = 2;
                                                                
                                                                // Récupérer disposition_modules depuis l'objet dalle ou la session
                                                                $disposition = $dalle->disposition_modules ?? session('disposition_modules_dalle_' . $dalle->id);
                                                                
                                                                // Si disposition_modules est définie et au format AxB
                                                                if (!empty($disposition) && strpos($disposition, 'x') !== false) {
                                                                    $parts = explode('x', $disposition);
                                                                    if (count($parts) == 2) {
                                                                        $nbColonnes = (int)$parts[0];
                                                                        $nbLignes = (int)$parts[1];
                                                                    }
                                                                } else {
                                                                    // Essayer de calculer la disposition en fonction du nombre de modules
                                                                    $nbModules = $dalle->modules->count();
                                                                    if ($nbModules == 4) {
                                                                        $nbColonnes = 2;
                                                                        $nbLignes = 2;
                                                                    } elseif ($nbModules == 6) {
                                                                        $nbColonnes = 3;
                                                                        $nbLignes = 2;
                                                                    } elseif ($nbModules == 9) {
                                                                        $nbColonnes = 3;
                                                                        $nbLignes = 3;
                                                                    }
                                                                }
                                                                
                                                                // Créer une grille pour les modules
                                                                $modules = $dalle->modules->all();
                                                                $grille = [];
                                                                
                                                                // Remplir la grille dans l'ordre ligne par ligne
                                                                $moduleIndex = 0;
                                                                for ($y = 1; $y <= $nbLignes; $y++) {
                                                                    for ($x = 1; $x <= $nbColonnes; $x++) {
                                                                        if ($moduleIndex < count($modules)) {
                                                                            $grille[$y][$x] = $modules[$moduleIndex];
                                                                            $moduleIndex++;
                                                                        } else {
                                                                            $grille[$y][$x] = null;
                                                                        }
                                                                    }
                                                                }
                                                            @endphp
                                                            
                                                            <div class="flex justify-center">
                                                                <div class="grid max-w-xs mx-auto" style="grid-template-columns: repeat({{ $nbColonnes }}, minmax(0, 1fr)); gap: 0.25rem;">
                                                                    @for ($y = 1; $y <= $nbLignes; $y++)
                                                                        @for ($x = 1; $x <= $nbColonnes; $x++)
                                                                            @if (isset($grille[$y][$x]))
                                                                                @php $module = $grille[$y][$x]; @endphp
                                                                                <a href="{{ route('modules.show', $module) }}" 
                                                                                   class="relative group">
                                                                                    <div class="aspect-square w-10 h-10 
                                                                                        @if($module->etat == 'termine') bg-accent-green
                                                                                        @elseif($module->etat == 'en_cours') bg-accent-yellow
                                                                                        @elseif($module->etat == 'defaillant') bg-accent-red
                                                                                        @else bg-gray-600
                                                                                        @endif
                                                                                        rounded-sm border border-black/10 hover:brightness-110 transition-all hover:shadow-lg duration-150 transform hover:scale-105"
                                                                                        title="Module #{{ $module->id }} - {{ ucfirst($module->etat) }}"
                                                                                    >
                                                                                        <div class="flex items-center justify-center h-full text-xs font-medium text-white/90">
                                                                                            {{ $x }},{{ $y }}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="block absolute top-full mt-1 left-1/2 transform -translate-x-1/2 bg-gray-800 text-xs text-white p-1 rounded whitespace-nowrap z-10">
                                                                                        Module #{{ $module->id }}
                                                                                    </div>
                                                                                </a>
                                                                            @else
                                                                                <div class="aspect-square w-10 h-10 bg-gray-800/50 rounded-sm border border-gray-700" title="Emplacement vide">
                                                                                    <div class="flex items-center justify-center h-full text-xs text-gray-600">
                                                                                        {{ $x }},{{ $y }}
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endfor
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="w-full mt-3">
                                                                <div class="flex justify-between text-xs mb-1 text-gray-300">
                                                                    <span>{{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%</span>
                                                                    <span>{{ $dalle->modules->where('etat', 'termine')->count() }}/{{ $dalle->modules->count() }} modules</span>
                                                                </div>
                                                                <div class="h-2 w-full bg-gray-700 rounded-full overflow-hidden">
                                                                    <div class="h-full bg-accent-green rounded-full" style="width: {{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        <!-- Autres dalles -->
                                        @if(!empty($dallesGrouped['autres']))
                                            <div class="mb-6" x-data="{ open: false }">
                                                <div @click="open = !open" class="flex items-center justify-between bg-gray-800/50 p-2 rounded-t-xl border-t border-l border-r border-gray-700 cursor-pointer">
                                                    <h6 class="font-medium text-white">Dalles indépendantes ({{ count($dallesGrouped['autres']) }})</h6>
                                                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                    </svg>
                                                </div>
                                                
                                                <div x-show="open" x-transition:enter="transition ease-out duration-200" 
                                                     x-transition:enter-start="opacity-0 transform -translate-y-2" 
                                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                                     x-transition:leave="transition ease-in duration-200"
                                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                     class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-900/30 rounded-b-xl border-b border-l border-r border-gray-700">
                                                    @foreach($dallesGrouped['autres'] as $dalle)
                                                        <div class="glassmorphism border border-gray-700 rounded-xl p-4">
                                                            <div class="flex justify-between items-center mb-3">
                                                                <h6 class="font-medium text-white flex items-center flex-wrap">
                                                                    Dalle #{{ $dalle->id }} 
                                                                    @if($dalle->reference_dalle)
                                                                        <span class="text-gray-400 ml-1">({{ $dalle->reference_dalle }})</span>
                                                                    @endif
                                                                    <span class="text-xs text-gray-400 ml-1">({{ $dalle->largeur }}×{{ $dalle->hauteur }} mm)</span>
                                                                    
                                                                    <!-- Affichage du numéro de dalle ou formulaire de saisie rapide -->
                                                                    <span class="ml-2" x-data="{ 
                                                                        isEditing: false,
                                                                        numeroValue: '{{ $dalle->numero_dalle }}',
                                                                        initialValue: '{{ $dalle->numero_dalle }}',
                                                                        toggleEdit() { this.isEditing = !this.isEditing; },
                                                                        save() {
                                                                            if (this.numeroValue.trim() === this.initialValue) {
                                                                                this.isEditing = false;
                                                                                return;
                                                                            }
                                                                            
                                                                            fetch('{{ route('dalles.update.numero', $dalle->id) }}', {
                                                                                method: 'POST',
                                                                                headers: {
                                                                                    'Content-Type': 'application/json',
                                                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                                                },
                                                                                body: JSON.stringify({ numero_dalle: this.numeroValue })
                                                                            })
                                                                            .then(response => response.json())
                                                                            .then(data => {
                                                                                if (data.success) {
                                                                                    this.isEditing = false;
                                                                                    this.initialValue = this.numeroValue;
                                                                                }
                                                                            });
                                                                        },
                                                                        cancel() {
                                                                            this.numeroValue = this.initialValue;
                                                                            this.isEditing = false;
                                                                        }
                                                                    }">
                                                                        <!-- Affichage si on a déjà un numéro -->
                                                                        <template x-if="!isEditing && initialValue">
                                                                            <span @click="toggleEdit()" class="cursor-pointer group">
                                                                                <span class="text-xs text-accent-blue">[N° <span x-text="initialValue"></span>]</span>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline ml-1 text-gray-400 group-hover:text-accent-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                                </svg>
                                                                            </span>
                                                                        </template>
                                                                        
                                                                        <!-- Bouton pour ajouter un numéro si on n'en a pas -->
                                                                        <template x-if="!isEditing && !initialValue">
                                                                            <button @click="toggleEdit()" class="text-xs px-2 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded flex items-center shadow-md transition-all duration-150 transform hover:scale-105 border border-purple-500">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                                </svg>
                                                                                N° dalle
                                                                            </button>
                                                                        </template>
                                                                        
                                                                        <!-- Formulaire de saisie -->
                                                                        <template x-if="isEditing">
                                                                            <span class="flex items-center">
                                                                                <input 
                                                                                    type="text" 
                                                                                    x-model="numeroValue" 
                                                                                    class="text-xs px-1 py-0.5 w-28 bg-gray-700 border border-accent-blue rounded" 
                                                                                    placeholder="N° dalle"
                                                                                    @keydown.enter="save()"
                                                                                    @keydown.escape="cancel()"
                                                                                />
                                                                                <button @click="save()" class="ml-1 text-accent-green">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                                    </svg>
                                                                                </button>
                                                                                <button @click="cancel()" class="ml-1 text-accent-red">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                                    </svg>
                                                                                </button>
                                                                            </span>
                                                                        </template>
                                                                    </span>
                                                                </h6>
                                                                <div class="flex space-x-3 items-center">
                                                                    <div class="text-xs">
                                                                        <span class="badge badge-success">
                                                                            {{ $dalle->modules->where('etat', 'termine')->count() }}/{{ $dalle->modules->count() }}
                                                                        </span>
                                                                    </div>
                                                                    <a href="{{ route('dalles.show', $dalle) }}" class="text-xs text-accent-blue hover:text-blue-400">Détails</a>
                                                                </div>
                                                            </div>
                                                            
                                                            @php
                                                                // Extraire les dimensions depuis disposition_modules
                                                                $nbColonnes = 2; // Par défaut 2x2
                                                                $nbLignes = 2;
                                                                
                                                                // Récupérer disposition_modules depuis l'objet dalle ou la session
                                                                $disposition = $dalle->disposition_modules ?? session('disposition_modules_dalle_' . $dalle->id);
                                                                
                                                                // Si disposition_modules est définie et au format AxB
                                                                if (!empty($disposition) && strpos($disposition, 'x') !== false) {
                                                                    $parts = explode('x', $disposition);
                                                                    if (count($parts) == 2) {
                                                                        $nbColonnes = (int)$parts[0];
                                                                        $nbLignes = (int)$parts[1];
                                                                    }
                                                                } else {
                                                                    // Essayer de calculer la disposition en fonction du nombre de modules
                                                                    $nbModules = $dalle->modules->count();
                                                                    if ($nbModules == 4) {
                                                                        $nbColonnes = 2;
                                                                        $nbLignes = 2;
                                                                    } elseif ($nbModules == 6) {
                                                                        $nbColonnes = 3;
                                                                        $nbLignes = 2;
                                                                    } elseif ($nbModules == 9) {
                                                                        $nbColonnes = 3;
                                                                        $nbLignes = 3;
                                                                    }
                                                                }
                                                                
                                                                // Créer une grille pour les modules
                                                                $modules = $dalle->modules->all();
                                                                $grille = [];
                                                                
                                                                // Remplir la grille dans l'ordre ligne par ligne
                                                                $moduleIndex = 0;
                                                                for ($y = 1; $y <= $nbLignes; $y++) {
                                                                    for ($x = 1; $x <= $nbColonnes; $x++) {
                                                                        if ($moduleIndex < count($modules)) {
                                                                            $grille[$y][$x] = $modules[$moduleIndex];
                                                                            $moduleIndex++;
                                                                        } else {
                                                                            $grille[$y][$x] = null;
                                                                        }
                                                                    }
                                                                }
                                                            @endphp
                                                            
                                                            <div class="flex justify-center">
                                                                <div class="grid max-w-xs mx-auto" style="grid-template-columns: repeat({{ $nbColonnes }}, minmax(0, 1fr)); gap: 0.25rem;">
                                                                    @for ($y = 1; $y <= $nbLignes; $y++)
                                                                        @for ($x = 1; $x <= $nbColonnes; $x++)
                                                                            @if (isset($grille[$y][$x]))
                                                                                @php $module = $grille[$y][$x]; @endphp
                                                                                <a href="{{ route('modules.show', $module) }}" 
                                                                                   class="relative group">
                                                                                    <div class="aspect-square w-10 h-10 
                                                                                        @if($module->etat == 'termine') bg-accent-green
                                                                                        @elseif($module->etat == 'en_cours') bg-accent-yellow
                                                                                        @elseif($module->etat == 'defaillant') bg-accent-red
                                                                                        @else bg-gray-600
                                                                                        @endif
                                                                                        rounded-sm border border-black/10 hover:brightness-110 transition-all hover:shadow-lg duration-150 transform hover:scale-105"
                                                                                        title="Module #{{ $module->id }} - {{ ucfirst($module->etat) }}"
                                                                                    >
                                                                                        <div class="flex items-center justify-center h-full text-xs font-medium text-white/90">
                                                                                            {{ $x }},{{ $y }}
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="block absolute top-full mt-1 left-1/2 transform -translate-x-1/2 bg-gray-800 text-xs text-white p-1 rounded whitespace-nowrap z-10">
                                                                                        Module #{{ $module->id }}
                                                                                    </div>
                                                                                </a>
                                                                            @else
                                                                                <div class="aspect-square w-10 h-10 bg-gray-800/50 rounded-sm border border-gray-700" title="Emplacement vide">
                                                                                    <div class="flex items-center justify-center h-full text-xs text-gray-600">
                                                                                        {{ $x }},{{ $y }}
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endfor
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="w-full mt-3">
                                                                <div class="flex justify-between text-xs mb-1 text-gray-300">
                                                                    <span>{{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%</span>
                                                                    <span>{{ $dalle->modules->where('etat', 'termine')->count() }}/{{ $dalle->modules->count() }} modules</span>
                                                                </div>
                                                                <div class="h-2 w-full bg-gray-700 rounded-full overflow-hidden">
                                                                    <div class="h-full bg-accent-green rounded-full" style="width: {{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
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
                    <!-- Boutons d'impression et QR Code -->
                    <div class="flex justify-center mt-6 space-x-4">
                        <x-print-button 
                            tag="a" 
                            route="{{ route('qrcode.chantier.print', $chantier->id) }}"
                            type="qrcode"
                            buttonStyle="font-semibold btn-3d">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4M4 8h16V5a1 1 0 00-1-1H5a1 1 0 00-1 1v3zm16 4v7a1 1 0 01-1 1H5a1 1 0 01-1-1v-7" />
                            </svg>
                            Générer QR Code
                        </x-print-button>
                        
                        <x-print-button 
                            type="brother"
                            onclick="printBrotherDirectly('{{ route('etiquettes.chantier.ptouch', $chantier->id) }}');"
                            buttonStyle="font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            Imprimer Brother
                        </x-print-button>
                    </div>
                    
                    <!-- Boutons d'envoi d'email -->
                    <div class="flex flex-col items-center mt-6">
                        <h5 class="font-medium text-gray-300 mb-3">Envoyer des emails au client</h5>
                        <div class="flex space-x-4">
                            <button type="button" 
                                onclick="document.getElementById('email-created-form').submit();" 
                                class="flex items-center px-4 py-2 rounded-lg shadow bg-indigo-600 hover:bg-indigo-700 text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email de création du chantier
                            </button>
                            <form id="email-created-form" action="{{ route('emails.chantier', $chantier) }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="email_type" value="created">
                            </form>
                            
                            <button type="button" 
                                onclick="document.getElementById('email-started-form').submit();" 
                                class="flex items-center px-4 py-2 rounded-lg shadow bg-yellow-600 hover:bg-yellow-700 text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email de début des interventions
                            </button>
                            <form id="email-started-form" action="{{ route('emails.chantier', $chantier) }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="email_type" value="started">
                            </form>
                            
                            <button type="button" 
                                onclick="document.getElementById('email-completed-form').submit();" 
                                class="flex items-center px-4 py-2 rounded-lg shadow bg-green-600 hover:bg-green-700 text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email de finalisation du chantier
                            </button>
                            <form id="email-completed-form" action="{{ route('emails.chantier', $chantier) }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="email_type" value="completed">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function printBrotherDirectly(url) {
            // Afficher un indicateur de chargement
            const loadingIndicator = document.createElement('div');
            loadingIndicator.style.position = 'fixed';
            loadingIndicator.style.top = '50%';
            loadingIndicator.style.left = '50%';
            loadingIndicator.style.transform = 'translate(-50%, -50%)';
            loadingIndicator.style.padding = '20px';
            loadingIndicator.style.backgroundColor = 'rgba(0,0,0,0.8)';
            loadingIndicator.style.color = 'white';
            loadingIndicator.style.borderRadius = '10px';
            loadingIndicator.style.zIndex = '9999';
            loadingIndicator.style.fontWeight = 'bold';
            loadingIndicator.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" class="animate-pulse" style="display: inline-block; margin-bottom: 10px;" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg><div>Envoi vers l\'imprimante Brother...</div></div>';
            document.body.appendChild(loadingIndicator);
            
            // Envoyer la requête à l'API
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Supprimer l'indicateur de chargement
                document.body.removeChild(loadingIndicator);
                
                // Afficher le message de succès ou d'erreur
                const messageBox = document.createElement('div');
                messageBox.style.position = 'fixed';
                messageBox.style.top = '50%';
                messageBox.style.left = '50%';
                messageBox.style.transform = 'translate(-50%, -50%)';
                messageBox.style.padding = '20px';
                messageBox.style.borderRadius = '10px';
                messageBox.style.zIndex = '9999';
                messageBox.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
                
                if (data.success) {
                    messageBox.style.backgroundColor = 'rgba(46, 125, 50, 0.9)';
                    messageBox.style.color = 'white';
                    messageBox.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-bottom: 10px;" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg><div>Étiquette envoyée avec succès à l\'imprimante ' + data.printer + '</div></div>';
                } else {
                    messageBox.style.backgroundColor = 'rgba(198, 40, 40, 0.9)';
                    messageBox.style.color = 'white';
                    messageBox.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-bottom: 10px;" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg><div>Erreur: ' + data.message + '</div></div>';
                }
                
                document.body.appendChild(messageBox);
                
                // Supprimer le message après 5 secondes
                setTimeout(() => {
                    document.body.removeChild(messageBox);
                }, 5000);
            })
            .catch(error => {
                // Supprimer l'indicateur de chargement
                document.body.removeChild(loadingIndicator);
                
                // Afficher le message d'erreur
                const errorBox = document.createElement('div');
                errorBox.style.position = 'fixed';
                errorBox.style.top = '50%';
                errorBox.style.left = '50%';
                errorBox.style.transform = 'translate(-50%, -50%)';
                errorBox.style.padding = '20px';
                errorBox.style.backgroundColor = 'rgba(198, 40, 40, 0.9)';
                errorBox.style.color = 'white';
                errorBox.style.borderRadius = '10px';
                errorBox.style.zIndex = '9999';
                errorBox.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
                errorBox.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-bottom: 10px;" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg><div>Erreur de connexion: ' + error.message + '</div></div>';
                
                document.body.appendChild(errorBox);
                
                // Supprimer le message après 5 secondes
                setTimeout(() => {
                    document.body.removeChild(errorBox);
                }, 5000);
            });
        }
    </script>
    @endpush
</x-app-layout>