<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Création de chantier - Étape 4/4 : Récapitulatif et rapports') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Indicateur de progression -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="w-full flex items-center">
                        <div class="relative flex flex-col items-center text-green-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-green-500 border-green-500 text-white font-bold flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-green-500">
                                <span class="font-bold">Client</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-500"></div>
                        <div class="relative flex flex-col items-center text-green-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-green-500 border-green-500 text-white font-bold flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-green-500">
                                <span class="font-bold">Chantier</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-500"></div>
                        <div class="relative flex flex-col items-center text-green-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-green-500 border-green-500 text-white font-bold flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-green-500">
                                <span class="font-bold">Produit</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-500"></div>
                        <div class="relative flex flex-col items-center text-accent-blue">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-accent-blue border-accent-blue text-white font-bold flex items-center justify-center">
                                4
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-accent-blue">
                                <span class="font-bold">Rapports</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Récapitulatif du chantier</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Informations client -->
                        <div class="bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                            <h4 class="font-medium text-blue-300 mb-2">Client</h4>
                            <p class="text-gray-300"><span class="font-semibold">Nom:</span> {{ $client->nom_complet }}</p>
                            @if($client->societe)
                                <p class="text-gray-300"><span class="font-semibold">Société:</span> {{ $client->societe }}</p>
                            @endif
                            @if($client->email)
                                <p class="text-gray-300"><span class="font-semibold">Email:</span> {{ $client->email }}</p>
                            @endif
                            @if($client->telephone)
                                <p class="text-gray-300"><span class="font-semibold">Téléphone:</span> {{ $client->telephone }}</p>
                            @endif
                        </div>
                        
                        <!-- Informations chantier -->
                        <div class="bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                            <h4 class="font-medium text-blue-300 mb-2">Chantier</h4>
                            <p class="text-gray-300"><span class="font-semibold">Nom:</span> Réparation écran - {{ $client->societe ?: $client->nom_complet }} - {{ date('d/m/Y') }}</p>
                            <p class="text-gray-300"><span class="font-semibold">Date de réception:</span> {{ date('d/m/Y', strtotime($step1Data['date_reception'])) }}</p>
                            <p class="text-gray-300"><span class="font-semibold">Date butoir:</span> {{ date('d/m/Y', strtotime($step1Data['date_butoir'])) }}</p>
                            <p class="text-gray-300"><span class="font-semibold">État:</span> 
                                @if($step1Data['etat'] == 'non_commence')
                                    Non commencé
                                @elseif($step1Data['etat'] == 'en_cours')
                                    En cours
                                @else
                                    Terminé
                                @endif
                            </p>
                        </div>
                        
                        <!-- Informations produit -->
                        <div class="bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                            <h4 class="font-medium text-blue-300 mb-2">Produit</h4>
                            @if($step2Data['selection_type'] == 'existant' && $produitRef)
                                <p class="text-gray-300"><span class="font-semibold">Produit:</span> {{ $produitRef->marque }} {{ $produitRef->modele }}</p>
                                <p class="text-gray-300"><span class="font-semibold">Pitch:</span> {{ $produitRef->pitch }} mm</p>
                                <p class="text-gray-300"><span class="font-semibold">Utilisation:</span> {{ $produitRef->utilisation == 'indoor' ? 'Indoor' : 'Outdoor' }}</p>
                                <p class="text-gray-300"><span class="font-semibold">Électronique:</span> 
                                    @if($produitRef->electronique == 'autre')
                                        {{ $produitRef->electronique_detail }}
                                    @else
                                        {{ ucfirst($produitRef->electronique) }}
                                    @endif
                                </p>
                            @else
                                <p class="text-gray-300"><span class="font-semibold">Produit:</span> {{ $step2Data['marque'] }} {{ $step2Data['modele'] }} (Nouveau)</p>
                                <p class="text-gray-300"><span class="font-semibold">Pitch:</span> {{ $step2Data['pitch'] }} mm</p>
                                <p class="text-gray-300"><span class="font-semibold">Utilisation:</span> {{ $step2Data['utilisation'] == 'indoor' ? 'Indoor' : 'Outdoor' }}</p>
                                <p class="text-gray-300"><span class="font-semibold">Électronique:</span> 
                                    @if($step2Data['electronique'] == 'autre')
                                        {{ $step2Data['electronique_detail'] }}
                                    @else
                                        {{ ucfirst($step2Data['electronique']) }}
                                    @endif
                                </p>
                            @endif
                        </div>
                        
                        <!-- Informations structure -->
                        <div class="bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                            <h4 class="font-medium text-blue-300 mb-2">Structure</h4>
                            @if($step3Data['mode'] == 'flightcase')
                                <p class="text-gray-300"><span class="font-semibold">Mode:</span> Flight Case</p>
                                <p class="text-gray-300"><span class="font-semibold">Nombre de flight cases:</span> {{ $step3Data['nb_flightcases'] }}</p>
                                
                                <!-- Détails de chaque flightcase -->
                                @if(!empty($step3Data['flightcase_details']))
                                    <div class="ml-4 mt-1 mb-2">
                                        @foreach($step3Data['flightcase_details'] as $detail)
                                            <p class="text-gray-300 text-sm">{{ $detail }}</p>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @if(isset($step3Data['disposition_modules']) && !empty($step3Data['disposition_modules']))
                                    <p class="text-gray-300">
                                        <span class="font-semibold">Modules par dalle:</span> 
                                        @php
                                            // Calculer le nombre de modules à partir de la disposition
                                            $dispositionText = $step3Data['disposition_modules'];
                                            $moduleCount = 4; // Valeur par défaut
                                            
                                            if (preg_match('/^(\d+)x(\d+)$/', $dispositionText, $matches)) {
                                                $moduleCount = (int)$matches[1] * (int)$matches[2];
                                            }
                                        @endphp
                                        {{ $moduleCount }}
                                        <span class="text-gray-400">(Disposition {{ $step3Data['disposition_modules'] }})</span>
                                    </p>
                                @else
                                    <p class="text-gray-300"><span class="font-semibold">Modules par dalle:</span> {{ $step3Data['nb_modules_par_dalle'] }}</p>
                                @endif
                                <p class="text-gray-300"><span class="font-semibold">Total modules:</span> 
                                    {{ $step3Data['theorique'] ?? $step3Data['nb_modules_total'] }}
                                </p>
                            @else
                                <p class="text-gray-300"><span class="font-semibold">Mode:</span> Modules individuels</p>
                                <p class="text-gray-300"><span class="font-semibold">Total modules:</span> {{ $step3Data['theorique'] ?? $step3Data['nb_modules_total'] }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Note sur les interventions -->
                    <div class="mb-6 p-4 bg-gray-900/30 border border-gray-500/30 rounded-xl">
                        <h4 class="font-medium text-gray-300 mb-2">Interventions</h4>
                        <p class="text-gray-300">
                            Après la création du chantier, vous pourrez créer et gérer les interventions manuellement depuis la page de détail du chantier.
                        </p>
                        <p class="text-gray-300 mt-2">
                            <span class="font-semibold">Total modules à traiter:</span> {{ $step3Data['nb_modules_total'] ?? 0 }}
                        </p>
                    </div>
                    
                    <form method="POST" action="{{ route('chantiers.store.step4', $chantier) }}">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="generate_report" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50">
                                <input type="checkbox" name="generate_report" id="generate_report" value="1" class="mr-2 accent-accent-blue">
                                <div>
                                    <span class="font-medium text-white">Générer un rapport</span>
                                    <p class="text-sm text-gray-400">Générer automatiquement un rapport PDF après la création du chantier</p>
                                </div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between mt-8">
                            <a href="{{ route('chantiers.create.step3', $chantier) }}" class="btn-action btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
                                {{ __('Retour') }}
                            </a>
                            <button type="submit" class="btn-action btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Finaliser le chantier') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
