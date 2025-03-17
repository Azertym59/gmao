<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-text-primary leading-tight">
            {{ __('Registre SAV & Ventes') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- En-tête avec titre et bouton d'action -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 px-2">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-xl font-semibold text-text-primary flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-accent-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Liste des projets</span>
                    </h2>
                    <p class="text-text-secondary mt-1">Gérez tous vos projets de vente et SAV</p>
                </div>
                <div>
                    <a href="{{ route('nouveau.projet') }}" class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter un projet
                    </a>
                </div>
            </div>

            <!-- Message de succès -->
            @if (session('success'))
                <div class="alert alert-success">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ session('success') }}
                    </div>
                    <button onclick="this.parentElement.style.display = 'none'" class="text-green-400 hover:text-green-300">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            
            <!-- Tableau des chantiers -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table-styled">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Client</th>
                                    <th>Nom</th>
                                    <th>Date butoir</th>
                                    <th>Temps restant</th>
                                    <th>État</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($chantiers as $chantier)
                                    <tr>
                                        <td class="font-medium">{{ $chantier->reference }}</td>
                                        <td>{{ $chantier->client->societe ?: $chantier->client->nom_complet }}</td>
                                        <td>{{ $chantier->nom }}</td>
                                        <td>{{ $chantier->date_butoir->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="{{ \App\Helpers\DateHelper::getTimeRemainingClass($chantier->date_butoir) }}">
                                                {{ \App\Helpers\DateHelper::formatTimeRemaining($chantier->date_butoir) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($chantier->etat == 'non_commence')
                                                <span class="badge badge-info">Non commencé</span>
                                            @elseif($chantier->etat == 'en_cours')
                                                <span class="badge badge-warning">En cours</span>
                                            @else
                                                <span class="badge badge-success">Terminé</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex gap-2">
                                                <a href="{{ route('chantiers.show', $chantier) }}" 
                                                   class="btn-action bg-accent-blue/10 text-accent-blue hover:bg-accent-blue/20">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Voir
                                                </a>
                                                <a href="{{ route('chantiers.edit', $chantier) }}" 
                                                   class="btn-action bg-gray-600/10 text-gray-300 hover:bg-gray-600/20">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Modifier
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-3">
                                                <div class="bg-gray-800 rounded-full p-3 w-16 h-16 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-text-secondary">Aucun projet trouvé</p>
                                                    <p class="text-text-secondary text-sm mt-1">Commencez par en créer un</p>
                                                </div>
                                                <a href="{{ route('nouveau.projet') }}" class="btn bg-white text-gray-900 hover:bg-gray-100 hover:scale-105 transition-transform mt-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Créer mon premier projet
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($chantiers) && method_exists($chantiers, 'links'))
                        <div class="p-4 border-t border-border-dark">
                            {{ $chantiers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>