<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Espace Client - {{ config('app.name', 'TecaLED') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/fixes.css') }}?t={{ time() }}">
    
    <style>
        /* Local styles for client dashboard */
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
        
        /* Stat cards */
        .stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
        }
        
        /* Card hover effects */
        .hover-card {
            transition: all 0.3s ease;
        }
        
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
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
            <div class="flex items-center">
                <div class="text-sm text-gray-300 mr-6">
                    <span>{{ now()->format('d/m/Y') }}</span>
                </div>
                <x-logout-button route="client.logout" class="btn-logout">
                    Déconnexion
                </x-logout-button>
            </div>
        </div>
    </header>

    <!-- Main content area -->
    <main class="container mx-auto p-6">
        <!-- Welcome message -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-2">Bienvenue {{ ucfirst(strtolower($client->prenom)) }} {{ strtoupper($client->nom) }}</h2>
            <p class="text-gray-400">Suivez l'avancement de vos projets et consultez les informations détaillées.</p>
        </div>
        
        <!-- Stats Counters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="glassmorphism p-6 hover-card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500/20 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 uppercase tracking-wider">Total des projets</p>
                        <div class="flex items-baseline">
                            <p class="text-3xl font-bold text-white">{{ $client->chantiers->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glassmorphism p-6 hover-card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500/20 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 uppercase tracking-wider">En cours</p>
                        <div class="flex items-baseline">
                            <p class="text-3xl font-bold text-white">{{ $client->chantiers->where('etat', 'in_progress')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glassmorphism p-6 hover-card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500/20 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 uppercase tracking-wider">Terminés</p>
                        <div class="flex items-baseline">
                            <p class="text-3xl font-bold text-white">{{ $client->chantiers->where('etat', 'completed')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="glassmorphism overflow-hidden mb-8">
            <div class="flex items-center justify-between p-6 border-b border-white/5">
                <h2 class="text-xl font-semibold">Vos projets récents</h2>
                @if($client->chantiers->count() > 5)
                    <a href="{{ route('client.chantiers') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium flex items-center">
                        Voir tous les projets
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif
            </div>

            @if($client->chantiers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Référence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($client->chantiers->sortByDesc('created_at')->take(5) as $chantier)
                            @php
                                $rowClass = '';
                                if ($chantier->etat == 'completed') {
                                    $rowClass = 'bg-green-500/5';
                                } elseif ($chantier->etat == 'in_progress') {
                                    $rowClass = 'bg-blue-500/5';
                                }
                            @endphp
                            <tr class="{{ $rowClass }} hover:bg-white/5 transition-colors border-b border-white/5">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $chantier->reference }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    @php
                                        $produit = $chantier->produits->first();
                                        $bainColor = '';
                                        if ($produit && $produit->bain_couleur) {
                                            if (strtolower($produit->bain_couleur) == 'noir') {
                                                $bainColor = 'bg-gray-900 border border-gray-600';
                                            } elseif (strtolower($produit->bain_couleur) == 'blanc') {
                                                $bainColor = 'bg-gray-100 border border-gray-300';
                                            } elseif (strtolower($produit->bain_couleur) == 'rouge' || strtolower($produit->bain_couleur) == 'red') {
                                                $bainColor = 'bg-red-600';
                                            } elseif (strtolower($produit->bain_couleur) == 'bleu' || strtolower($produit->bain_couleur) == 'blue') {
                                                $bainColor = 'bg-blue-600';
                                            } elseif (strtolower($produit->bain_couleur) == 'vert' || strtolower($produit->bain_couleur) == 'green') {
                                                $bainColor = 'bg-green-600';
                                            } else {
                                                $bainColor = 'bg-gray-500';
                                            }
                                        }
                                    @endphp
                                    @if($produit)
                                        <div class="flex flex-col">
                                            <span class="font-medium text-white">{{ $produit->marque }} {{ $produit->modele }}</span>
                                            <div class="flex items-center mt-1">
                                                <span class="text-xs text-gray-400 mr-2">Pitch: {{ $produit->pitch }}mm</span>
                                                @if($produit->bain_couleur)
                                                    <div class="flex items-center">
                                                        <span class="text-xs text-gray-400 mr-1">Bain:</span>
                                                        <div class="w-3 h-3 rounded-full {{ $bainColor }} mr-1"></div>
                                                        <span class="text-xs text-gray-400">{{ $produit->bain_couleur }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-500">Aucun produit</span>
                                    @endif
                                </td>
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
            <div class="glassmorphism p-6 hover-card border-t-2 border-blue-500">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Support Technique
                </h2>
                <p class="text-gray-400 mb-4">Notre équipe technique est disponible pour vous aider avec vos projets et répondre à vos questions techniques.</p>
                <a href="mailto:maintenance@tecaled.fr" class="btn-info">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contacter le support
                </a>
            </div>
            
            <div class="glassmorphism p-6 hover-card border-t-2 border-green-500">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Guide d'utilisation
                </h2>
                <p class="text-gray-400 mb-4">Suivez l'état de vos réparations et comprenez les différentes informations de votre tableau de bord.</p>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Suivez l'avancement de vos projets en temps réel</span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Consultez les détails des interventions effectuées</span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Accédez à l'historique complet de vos projets</span>
                    </li>
                </ul>
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
</body>
</html>