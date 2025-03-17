<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tous vos projets - {{ config('app.name', 'TecaLED') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/fixes.css') }}">
    
    <style>
        /* Local styles for client projects page */
        body {
            background-color: #121212;
            min-height: 100vh;
        }
        
        /* Progress bar */
        .progress-bar-bg {
            background-color: rgba(55, 65, 81, 0.5);
            border-radius: 9999px;
            height: 0.5rem;
            width: 100%;
            margin-top: 0.25rem;
        }
        
        .progress-bar-fill {
            background-color: #F59E0B; /* Orange */
            border-radius: 9999px;
            height: 0.5rem;
        }
        
        /* Search and filter inputs */
        .filter-dropdown {
            background-color: #1E1E1E;
            border: 1px solid rgba(75, 85, 99, 0.5);
            color: #F3F4F6;
            padding: 0.5rem;
            border-radius: 0.375rem;
        }
        
        .filter-dropdown:focus {
            border-color: #3B82F6;
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
        }
        
        .search-input {
            background-color: #1E1E1E;
            border: 1px solid rgba(75, 85, 99, 0.5);
            color: #F3F4F6;
            border-radius: 0.375rem;
            padding-left: 2.5rem;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        
        .search-input:focus {
            border-color: #3B82F6;
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
        }
    </style>
</head>
<body class="text-white font-sans antialiased">
    <!-- Header -->
    <header class="bg-black py-4 px-6 border-b border-white/5">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('images/Logo rectangle V2.png') }}" alt="TecaLED" class="h-10 mr-3">
                <h1 class="text-xl font-bold">Espace client - {{ $client->societe ?? 'TecaLED' }}</h1>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('client.dashboard') }}" class="btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Tableau de bord</span>
                </a>
                <form method="POST" action="{{ route('client.logout') }}">
                    @csrf
                    <button type="submit" class="btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main content area -->
    <main class="container mx-auto p-6">
        <!-- Page title -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-2">Tous vos projets</h2>
            <p class="text-gray-400">Consultez l'ensemble de vos projets et leur statut actuel.</p>
        </div>
        
        <!-- Filter Section -->
        <div class="glassmorphism p-6 mb-8">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center">
                    <span class="text-sm font-medium mr-2 text-gray-400">Statut:</span>
                    <select id="status-filter" class="filter-dropdown">
                        <option value="all">Tous</option>
                        <option value="completed">Terminés</option>
                        <option value="in_progress">En cours</option>
                        <option value="waiting">En attente</option>
                    </select>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium mr-2 text-gray-400">Trier par:</span>
                    <select id="sort-by" class="filter-dropdown">
                        <option value="date-desc">Date (récent → ancien)</option>
                        <option value="date-asc">Date (ancien → récent)</option>
                        <option value="ref-asc">Référence (A → Z)</option>
                        <option value="ref-desc">Référence (Z → A)</option>
                    </select>
                </div>
                <div class="flex-1 relative ml-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="search" placeholder="Rechercher un projet..." class="search-input w-full md:w-64 pl-10">
                </div>
            </div>
        </div>

        <!-- Projects Table -->
        <div class="glassmorphism overflow-hidden mb-8">
            <div class="flex items-center justify-between p-6 border-b border-white/5">
                <h2 class="text-xl font-semibold">Liste des projets ({{ $client->chantiers->count() }})</h2>
            </div>

            @if($client->chantiers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Référence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Adresse</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Produits</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($client->chantiers->sortByDesc('created_at') as $chantier)
                            @php
                                $rowClass = '';
                                if ($chantier->etat == 'completed') {
                                    $rowClass = 'bg-green-500/5';
                                } elseif ($chantier->etat == 'in_progress') {
                                    $rowClass = 'bg-blue-500/5';
                                }
                            @endphp
                            <tr class="{{ $rowClass }} hover:bg-white/5 transition-colors border-b border-white/5 project-row" 
                                data-state="{{ $chantier->etat }}"
                                data-reference="{{ $chantier->reference }}"
                                data-date="{{ $chantier->created_at->format('Y-m-d') }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $chantier->reference }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $chantier->adresse }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $chantier->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $completion = $chantier->getCompletionPercentage();
                                    @endphp
                                    
                                    <div class="flex items-center">
                                        @if($chantier->etat == 'completed')
                                            <span class="badge badge-success">Terminé (100%)</span>
                                        @elseif($chantier->etat == 'in_progress')
                                            <div class="flex flex-col w-full" style="min-width: 120px;">
                                                <span class="badge badge-warning">En cours ({{ $completion }}%)</span>
                                                <div class="progress-bar-bg">
                                                    <div class="progress-bar-fill" style="width: {{ $completion }}%;"></div>
                                                </div>
                                            </div>
                                        @elseif($chantier->etat == 'waiting')
                                            <span class="badge badge-warning">En attente</span>
                                        @elseif($chantier->etat == 'planned')
                                            <span class="badge badge-warning">Planifié</span>
                                        @elseif($chantier->etat == 'cancelled' || $chantier->etat == 'canceled')
                                            <span class="badge badge-danger">Annulé</span>
                                        @else
                                            <span class="badge">{{ ucfirst($chantier->etat ?? 'Non défini') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $chantier->produits_count ?? $chantier->produits->count() }} produit(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('suivi.chantier', $chantier->token_suivi) }}" class="btn-action">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
                <div class="p-10 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="text-lg font-medium text-white mb-1">Aucun projet trouvé</h3>
                    <p class="text-gray-400">Vous n'avez pas encore de projets enregistrés dans notre système.</p>
                </div>
            @endif
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="glassmorphism p-6 hover-card border-t-2 border-yellow-500">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Légende des statuts
                </h2>
                <div class="grid grid-cols-1 gap-3 text-gray-400">
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full bg-green-500/20 border border-green-500/50 mr-3"></span>
                        <span>Terminé : Votre projet a été complété avec succès</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full bg-yellow-500/20 border border-yellow-500/50 mr-3"></span>
                        <span>En cours : Nos techniciens travaillent actuellement sur votre projet</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full bg-blue-500/20 border border-blue-500/50 mr-3"></span>
                        <span>Planifié : Une intervention est programmée prochainement</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full bg-gray-500/20 border border-gray-500/50 mr-3"></span>
                        <span>Autre : État spécifique (diagnostic, attente, etc.)</span>
                    </div>
                </div>
            </div>
            
            <div class="glassmorphism p-6 hover-card border-t-2 border-blue-500">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informations
                </h2>
                <p class="text-gray-400 mb-4">
                    Pour chaque projet, vous pouvez accéder à son suivi détaillé en cliquant sur "Détails". 
                    Cela vous permettra de consulter l'avancement des interventions, les diagnostics effectués et les réparations réalisées.
                </p>
                <p class="text-gray-400">
                    Si vous avez des questions concernant un projet spécifique, n'hésitez pas à contacter notre équipe technique 
                    <a href="mailto:maintenance@tecaled.fr" class="text-blue-400 hover:text-blue-300 transition-colors">maintenance@tecaled.fr</a>.
                </p>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="border-t border-white/5 py-6 px-6">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center mb-4 md:mb-0">
                <img src="{{ asset('images/Logo rectangle V2.png') }}" alt="TecaLED Logo" class="h-8 mr-3">
                <span class="text-gray-400">© {{ date('Y') }} TecaLED. Tous droits réservés.</span>
            </div>
            <div class="flex space-x-6">
                <a href="#" class="text-gray-400 hover:text-white transition-colors">Mentions légales</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">Confidentialité</a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">Contact</a>
            </div>
        </div>
    </footer>

    <!-- Filtering and sorting script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('status-filter');
            const sortBy = document.getElementById('sort-by');
            const searchInput = document.getElementById('search');
            const projectRows = document.querySelectorAll('.project-row');
            
            function applyFilters() {
                const statusValue = statusFilter.value;
                const searchValue = searchInput.value.toLowerCase();
                
                projectRows.forEach(row => {
                    // Status filtering
                    let statusMatch = true;
                    if (statusValue !== 'all') {
                        const rowStatus = row.getAttribute('data-state');
                        if (statusValue === 'completed' && rowStatus !== 'completed') {
                            statusMatch = false;
                        } else if (statusValue === 'in_progress' && rowStatus !== 'in_progress') {
                            statusMatch = false;
                        } else if (statusValue === 'waiting' && rowStatus !== 'waiting') {
                            statusMatch = false;
                        }
                    }
                    
                    // Search filtering
                    let searchMatch = true;
                    if (searchValue) {
                        searchMatch = Array.from(row.querySelectorAll('td')).some(cell => 
                            cell.textContent.toLowerCase().includes(searchValue)
                        );
                    }
                    
                    // Apply visibility
                    row.style.display = statusMatch && searchMatch ? '' : 'none';
                });
                
                // Check if we need to show "no results" message
                checkNoResults();
            }
            
            function sortRows() {
                const sortValue = sortBy.value;
                const tbody = document.querySelector('tbody');
                const rows = Array.from(projectRows);
                
                rows.sort((a, b) => {
                    if (sortValue === 'date-desc') {
                        return b.getAttribute('data-date').localeCompare(a.getAttribute('data-date'));
                    } else if (sortValue === 'date-asc') {
                        return a.getAttribute('data-date').localeCompare(b.getAttribute('data-date'));
                    } else if (sortValue === 'ref-asc') {
                        return a.getAttribute('data-reference').localeCompare(b.getAttribute('data-reference'));
                    } else if (sortValue === 'ref-desc') {
                        return b.getAttribute('data-reference').localeCompare(a.getAttribute('data-reference'));
                    }
                    return 0;
                });
                
                // Remove all rows
                projectRows.forEach(row => row.remove());
                
                // Add sorted rows
                rows.forEach(row => tbody.appendChild(row));
            }
            
            function checkNoResults() {
                const visibleRows = Array.from(projectRows).filter(row => row.style.display !== 'none');
                const tableBody = document.querySelector('tbody');
                const existingNoResults = document.querySelector('.no-results-message');
                
                if (visibleRows.length === 0) {
                    if (!existingNoResults) {
                        const noResults = document.createElement('tr');
                        noResults.className = 'no-results-message';
                        noResults.innerHTML = `
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <p class="text-lg font-medium text-white mb-1">Aucun résultat trouvé</p>
                                <p>Essayez de modifier vos filtres ou votre recherche</p>
                            </td>
                        `;
                        tableBody.appendChild(noResults);
                    }
                } else if (existingNoResults) {
                    existingNoResults.remove();
                }
            }
            
            // Event listeners
            if (statusFilter) statusFilter.addEventListener('change', applyFilters);
            if (searchInput) searchInput.addEventListener('input', applyFilters);
            if (sortBy) sortBy.addEventListener('change', function() {
                sortRows();
                applyFilters(); // Re-apply filters after sorting
            });
            
            // Initial sort
            sortRows();
        });
    </script>
</body>
</html>