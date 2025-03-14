<x-app-layout>
    <x-slot name="header">
        {{ __('Tableau de bord') }}
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total des modules -->
        <div class="glassmorphism rounded-xl shadow-lg p-6 border border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500/20 text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-400 text-sm font-medium">Modules</h3>
                    <p class="text-3xl font-semibold text-white">{{ $statsModules['total'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $statsModules['pourcentage'] }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-1">
                    <span>{{ $statsModules['pourcentage'] }}% terminés</span>
                    <span>{{ $statsModules['termines'] }}/{{ $statsModules['total'] }}</span>
                </div>
            </div>
        </div>

   <!-- Modules terminés -->
        <div class="glassmorphism rounded-xl shadow-lg p-6 border border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500/20 text-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-400 text-sm font-medium">Terminés</h3>
                    <p class="text-3xl font-semibold text-white">{{ $statsModules['termines'] }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm">Taux de complétion</span>
                    <span class="text-green-500 text-sm font-semibold">{{ $statsModules['pourcentage'] }}%</span>
                </div>
            </div>
        </div>
    
    <!-- Modules en cours -->
    <div class="glassmorphism rounded-xl shadow-lg p-6 border border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500/20 text-yellow-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-400 text-sm font-medium">En cours</h3>
                    <p class="text-3xl font-semibold text-white">{{ $statsModules['en_cours'] }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm">Taux d'activité</span>
                    <span class="text-yellow-500 text-sm font-semibold">
                        @php
                            $tauxActivite = $statsModules['total'] > 0 ? round(($statsModules['en_cours'] / $statsModules['total']) * 100) : 0;
                        @endphp
                        {{ $tauxActivite }}%
                    </span>
                </div>
            </div>
        </div>
    
    <!-- Modules défaillants -->
    <div class="glassmorphism rounded-xl shadow-lg p-6 border border-gray-700">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-500/20 text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-400 text-sm font-medium">Défaillants</h3>
                    <p class="text-3xl font-semibold text-white">{{ $statsModules['defaillants'] }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm">Taux d'échec</span>
                    <span class="text-red-500 text-sm font-semibold">
                        @php
                            $tauxEchec = ($statsModules['termines'] + $statsModules['defaillants']) > 0 
                                ? round(($statsModules['defaillants'] / ($statsModules['termines'] + $statsModules['defaillants'])) * 100) 
                                : 0;
                        @endphp
                        {{ $tauxEchec }}%
                    </span>
                </div>
            </div>
        </div>
    </div>

 <!-- Chantiers urgents et statistiques de performance -->
 <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Chantiers urgents -->
        <div class="glassmorphism rounded-xl shadow-lg overflow-hidden lg:col-span-2 border border-gray-700">
            <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                <h3 class="font-semibold text-white">Chantiers urgents</h3>
                <a href="{{ route('chantiers.index') }}" class="text-xs text-accent-blue hover:underline">Voir tous</a>
            </div>
            <div class="p-4">
                @if(count($chantiersUrgents) > 0)
                    <div class="divide-y divide-gray-700">
                        @foreach($chantiersUrgents as $chantier)
                            <div class="py-3 px-2 flex items-center justify-between">
                                <div>
                                    <a href="{{ route('chantiers.show', $chantier) }}" class="font-medium text-white hover:text-accent-blue">
                                        {{ $chantier->reference }}
                                    </a>
                                    <p class="text-sm text-gray-400">{{ $chantier->client->societe ?: ($chantier->client->nom_complet ?? 'Client inconnu') }}</p>
                                </div>
                                <div class="flex items-center">
                                    <div class="tooltip-container">
                                        <div class="mr-6 text-sm">
                                            <span class="block text-right {{ \App\Helpers\DateHelper::getTimeRemainingClass($chantier->date_butoir) }}">
                                                {{ \App\Helpers\DateHelper::formatTimeRemaining($chantier->date_butoir) }}
                                            </span>
                                        </div>
                                        <div class="tooltip">Date butoir: {{ \Carbon\Carbon::parse($chantier->date_butoir)->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="w-20">
                                        <div class="w-full bg-gray-700 rounded-full h-2">
                                            <div class="rounded-full h-2 
                                                @php
                                                    $progress = $chantier->pourcentage_avancement ?? 0;
                                                    if ($progress < 30) {
                                                        echo 'bg-red-500';
                                                    } elseif ($progress < 70) {
                                                        echo 'bg-yellow-500';
                                                    } else {
                                                        echo 'bg-green-500';
                                                    }
                                                @endphp
                                            " style="width: {{ $chantier->pourcentage_avancement ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-400 block text-right mt-1">{{ $chantier->pourcentage_avancement ?? 0 }}%</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-6 text-center text-gray-400">
                        <p>Aucun chantier urgent trouvé</p>
                    </div>
                @endif
            </div>
        </div>

    <!-- Statistiques de performance -->
    <div class="glassmorphism rounded-xl shadow-lg overflow-hidden border border-gray-700">
        <div class="px-6 py-4 border-b border-gray-700">
            <h3 class="font-semibold text-white">Performance</h3>
        </div>
        <div class="p-4 space-y-4">
            <!-- Temps moyen d'intervention par module -->
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm text-gray-400">Temps moyen/module</span>
                    <span class="text-sm text-white">{{ $statsInterventions['temps_moyen_format'] }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-accent-purple h-2 rounded-full" style="width: {{ min(100, $statsInterventions['temps_moyen_minutes'] / 60 * 100) }}%"></div>
                </div>
            </div>
            
            <!-- Taux de modules réparés -->
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm text-gray-400">Taux de réparation</span>
                    <span class="text-sm text-white">{{ $statsInterventions['taux_reussite'] }}%</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-accent-green h-2 rounded-full" style="width: {{ $statsInterventions['taux_reussite'] }}%"></div>
                </div>
            </div>
            
            <!-- Composants remplacés -->
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm text-gray-400">Utilisation composants</span>
                    <span class="text-sm text-white">{{ $statsInterventions['nb_composants'] }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-accent-blue h-2 rounded-full" style="width: {{ min(100, $statsInterventions['nb_composants'] / 50 * 100) }}%"></div>
                </div>
            </div>
            
            <!-- Charge de travail -->
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm text-gray-400">Charge de travail</span>
                    <span class="text-sm text-white">{{ $statsInterventions['charge_travail'] }}%</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="
                        @if($statsInterventions['charge_travail'] > 80)
                            bg-red-500
                        @elseif($statsInterventions['charge_travail'] > 50)
                            bg-yellow-500
                        @else
                            bg-green-500
                        @endif
                        h-2 rounded-full
                    " style="width: {{ $statsInterventions['charge_travail'] }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Activité récente et progression des chantiers -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Interventions récentes -->
    <div class="glassmorphism rounded-xl shadow-lg overflow-hidden border border-gray-700">
        <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center">
            <h3 class="font-semibold text-white">Activité récente</h3>
            <a href="{{ route('interventions.index') }}" class="text-xs text-accent-blue hover:underline">Voir tous</a>
        </div>
        <div class="divide-y divide-gray-700">
            @forelse($interventionsRecentes as $intervention)
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $intervention->is_completed ? 'bg-green-500/10 text-green-500' : 'bg-blue-500/10 text-blue-500' }}">
                                @if($intervention->is_completed)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </span>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <div class="text-sm text-white">
                                <span class="font-medium">{{ $intervention->technicien->name ?? 'Technicien' }}</span>
                                <span class="text-gray-400">
                                    a {{ $intervention->is_completed ? 'terminé' : 'démarré' }} une intervention sur le module
                                </span>
                                <a href="{{ route('interventions.show', $intervention) }}" class="font-medium hover:text-accent-blue">
                                    {{ $intervention->module->reference ?? 'Module' }}
                                </a>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                <span>{{ $intervention->updated_at->diffForHumans() }}</span>
                                @if($intervention->temps_total)
                                    <span class="ml-2 px-2 py-0.5 bg-gray-700 rounded-full">
                                        {{ gmdate('H:i:s', $intervention->temps_total) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-400">
                    Aucune intervention récente
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Progression des chantiers actifs -->
    <div class="glassmorphism rounded-xl shadow-lg overflow-hidden lg:col-span-2 border border-gray-700">
        <div class="px-6 py-4 border-b border-gray-700">
            <h3 class="font-semibold text-white">Progression des chantiers actifs</h3>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Chantier</th>
                            <th class="px-4 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Client</th>
                            <th class="px-4 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Modules</th>
                            <th class="px-4 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Avancement</th>
                            <th class="px-4 py-3 bg-gray-800 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">État</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($chantiersActifs as $chantier)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('chantiers.show', $chantier) }}" class="text-white hover:text-accent-blue">
                                        {{ $chantier->reference }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">{{ $chantier->client->societe ?: ($chantier->client->nom_complet ?? 'Client inconnu') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">
                                    <span class="font-medium">{{ $chantier->nb_modules_termines ?? 0 }}</span>
                                    <span class="text-gray-500">/</span>
                                    <span>{{ $chantier->nb_modules_total ?? 0 }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-700 rounded-full h-2 mr-2">
                                            <div class="h-2 rounded-full 
                                                @php
                                                    $progress = $chantier->pourcentage_avancement ?? 0;
                                                    if ($progress < 30) {
                                                        echo 'bg-red-500';
                                                    } elseif ($progress < 70) {
                                                        echo 'bg-yellow-500';
                                                    } else {
                                                        echo 'bg-green-500';
                                                    }
                                                @endphp
                                            " style="width: {{ $chantier->pourcentage_avancement ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-400">{{ $chantier->pourcentage_avancement ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($chantier->etat === 'en_cours')
                                            bg-blue-500/10 text-blue-400
                                        @elseif($chantier->etat === 'urgent')
                                            bg-red-500/10 text-red-400
                                        @elseif($chantier->etat === 'en_attente')
                                            bg-yellow-500/10 text-yellow-400
                                        @elseif($chantier->etat === 'termine')
                                            bg-green-500/10 text-green-400
                                        @else
                                            bg-gray-500/10 text-gray-400
                                        @endif
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $chantier->etat ?? 'inconnu')) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                    Aucun chantier actif en cours
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-app-layout>