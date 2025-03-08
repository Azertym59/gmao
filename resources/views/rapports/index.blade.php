<!-- resources/views/rapports/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rapports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Générer des rapports</h3>
                    
                    <div class="space-y-6">
                        <!-- Rapports par chantier -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Rapports de chantier</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date butoir</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                                            <th scope="col" class="relative px-6 py-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($chantiers as $chantier)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $chantier->reference }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chantier->client->nom_complet }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $chantier->date_butoir->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($chantier->etat == 'non_commence')
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Non commencé</span>
                                                    @elseif($chantier->etat == 'en_cours')
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En cours</span>
                                                    @else
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Terminé</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('rapports.chantier', $chantier) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                                        Télécharger PDF
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Autres types de rapports -->
                        <div class="mt-8">
                            <h4 class="font-medium text-gray-700 mb-2">Autres rapports</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="border rounded-lg p-4">
                                    <h5 class="font-medium text-lg mb-2">Rapport de performance</h5>
                                    <p class="text-sm text-gray-500 mb-4">Analyse des performances des techniciens, temps de réparation et taux de réussite.</p>
                                    <a href="{{ route('rapports.performance') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                        Générer
                                    </a>
                                </div>
                                
                                <div class="border rounded-lg p-4">
                                    <h5 class="font-medium text-lg mb-2">Inventaire des pièces</h5>
                                    <p class="text-sm text-gray-500 mb-4">Rapport détaillé sur l'utilisation des pièces par type de réparation.</p>
                                    <a href="{{ route('rapports.inventaire') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                        Générer
                                    </a>
                                </div>
                                
                                <div class="border rounded-lg p-4">
                                    <h5 class="font-medium text-lg mb-2">Statistiques mensuelles</h5>
                                    <p class="text-sm text-gray-500 mb-4">Rapport mensuel d'activité avec graphiques et KPIs.</p>
                                    <a href="{{ route('rapports.statistiques') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                        Générer
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rapports d'interventions -->
                        <div class="mt-8">
                            <h4 class="font-medium text-gray-700 mb-2">Rapports d'interventions récentes</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technicien</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                                            <th scope="col" class="relative px-6 py-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($interventions as $intervention)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $intervention->date_debut->format('d/m/Y H:i') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $intervention->technicien->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Module #{{ $intervention->module->id }}
                                                    <span class="text-xs text-gray-500 block">
                                                        {{ $intervention->module->dalle->produit->marque }} 
                                                        {{ $intervention->module->dalle->produit->modele }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @php
                                                        $heures = floor($intervention->temps_total / 3600);
                                                        $minutes = floor(($intervention->temps_total % 3600) / 60);
                                                        $secondes = $intervention->temps_total % 60;
                                                        echo sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
                                                    @endphp
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('rapports.intervention', $intervention) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                                        Télécharger PDF
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>