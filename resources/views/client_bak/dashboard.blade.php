<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Espace Client - {{ config('app.name', 'TecaLED') }} (v2)</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/fixes.css') }}?t={{ time() }}">
    
    <style>
        /* Local styles - Updated for better appearance */
        .glassmorphism {
            background: rgba(30, 30, 30, 0.6) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 0.75rem !important;
        }
        
        /* Force styles for thead */
        .table-header {
            background-color: #000000 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Completed status styling */
        .completed-status {
            background-color: rgba(16, 185, 129, 0.2) !important; 
            color: #10B981 !important; 
            border: 1px solid rgba(16, 185, 129, 0.3) !important;
            border-radius: 9999px !important;
            padding: 0.25rem 0.75rem !important;
            font-size: 0.75rem !important;
        }
        
        /* In-progress status styling */
        .in-progress-status {
            background-color: rgba(59, 130, 246, 0.2) !important;
            color: #60A5FA !important;
            border: 1px solid rgba(59, 130, 246, 0.3) !important;
            border-radius: 9999px !important;
            padding: 0.25rem 0.75rem !important;
            font-size: 0.75rem !important;
        }
        
        /* Progress bar */
        .progress-bar-bg {
            background-color: rgba(55, 65, 81, 0.5) !important;
            border-radius: 9999px !important;
            height: 0.375rem !important;
        }
        
        .progress-bar-fill {
            background-color: #3B82F6 !important;
            border-radius: 9999px !important;
            height: 0.375rem !important;
        }
        
        /* Row highlighting */
        .completed-row {
            background-color: rgba(16, 185, 129, 0.05) !important;
        }
        
        .in-progress-row {
            background-color: rgba(59, 130, 246, 0.05) !important;
        }
        
        body {
            background-color: #121212;
            background-image: 
                radial-gradient(circle at 85% 15%, rgba(59, 130, 246, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 10% 85%, rgba(16, 185, 129, 0.1) 0%, transparent 40%);
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        .stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
        }
        
        /* Button styles */
        .btn-primary {
            background-color: #333333;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn-primary:hover {
            background-color: #444444;
        }
        
        /* Badge info style */
        .badge-info {
            background-color: rgba(59, 130, 246, 0.2);
            color: #60A5FA;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        .badge-warning {
            background-color: rgba(245, 158, 11, 0.2);
            color: #F59E0B;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .btn-secondary {
            background-color: rgba(75, 85, 99, 0.3);
            color: #F3F4F6;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .btn-secondary:hover {
            background-color: rgba(75, 85, 99, 0.5);
        }
        
        /* Status badges */
        .badge-completed {
            background-color: rgba(16, 185, 129, 0.2);
            color: #10B981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        
        .badge-in-progress {
            background-color: rgba(245, 158, 11, 0.2);
            color: #F59E0B;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .badge-default {
            background-color: rgba(75, 85, 99, 0.2);
            color: #9CA3AF;
            border: 1px solid rgba(75, 85, 99, 0.3);
        }
        
        /* Header - simple black background */
        .animated-gradient {
            background: #000000;
        }
    </style>
</head>
<body class="text-text-primary font-sans antialiased">
    <!-- Header with animated gradient -->
    <header class="animated-gradient py-6 mb-8 relative">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <img src="{{ asset('images/logo-repair.png') }}" alt="TecaLED Logo" class="h-16 mr-4">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Espace Client</h1>
                        <p class="text-blue-100 opacity-80">Bienvenue, {{ $client->nom_complet }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <form method="POST" action="{{ route('client.logout') }}">
                        @csrf
                        <button type="submit" class="bg-white/20 hover:bg-white/30 text-white rounded-md px-4 py-2 text-sm font-medium transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Simple straight line instead of wave -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-800"></div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pb-12">
        <!-- Stats Counters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stat-card glassmorphism p-6 border-l-4 border-accent-blue">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-accent-blue/20 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary uppercase tracking-wider">Total des projets</p>
                        <div class="flex items-baseline">
                            <p class="text-3xl font-bold text-text-primary">{{ $client->chantiers->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-card glassmorphism p-6 border-l-4 border-accent-yellow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-accent-yellow/20 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-yellow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary uppercase tracking-wider">En cours</p>
                        <div class="flex items-baseline">
                            <p class="text-3xl font-bold text-text-primary">{{ $client->chantiers->where('etat', 'in_progress')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-card glassmorphism p-6 border-l-4 border-accent-green">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-accent-green/20 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-text-secondary uppercase tracking-wider">Terminés</p>
                        <div class="flex items-baseline">
                            <p class="text-3xl font-bold text-text-primary">{{ $client->chantiers->where('etat', 'completed')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="glassmorphism overflow-hidden mb-8">
            <div class="flex items-center justify-between p-6 border-b border-border-dark">
                <h2 class="text-xl font-semibold text-text-primary">Vos projets récents</h2>
                @if($client->chantiers->count() > 5)
                    <a href="{{ route('client.chantiers') }}" class="text-gray-300 hover:text-white text-sm font-medium flex items-center">
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
                            <tr style="background-color: #000000; border-bottom: 1px solid rgba(255, 255, 255, 0.1);" class="border-b border-border-dark">
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">Référence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($client->chantiers->sortByDesc('created_at')->take(5) as $chantier)
                            @php
                                $rowStyle = '';
                                if ($chantier->etat == 'completed') {
                                    $rowStyle = 'background-color: rgba(16, 185, 129, 0.05);';
                                } elseif ($chantier->etat == 'in_progress') {
                                    $rowStyle = 'background-color: rgba(59, 130, 246, 0.05);';
                                }
                            @endphp
                            <tr style="{{ $rowStyle }}" class="hover:bg-white/5 transition-colors border-b border-border-dark">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $chantier->reference }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
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
                                            <span class="font-medium">{{ $produit->marque }} {{ $produit->modele }}</span>
                                            <div class="flex items-center mt-1">
                                                <span class="text-xs text-gray-400 mr-2">Pitch: {{ $produit->pitch }}mm</span>
                                                @if($produit->bain_couleur)
                                                    <div class="flex items-center">
                                                        <span class="text-xs text-gray-400 mr-1">Bain:</span>
                                                        <div class="w-3 h-3 rounded-full {{ $bainColor }} mr-1"></div>
                                                        <span class="text-xs text-gray-400">{{ $produit->bain_couleur }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-500">Inconnu</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span>Aucun produit</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $chantier->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $completion = $chantier->getCompletionPercentage();
                                        $statusColor = '';
                                        $rowColor = '';
                                        
                                        if ($chantier->etat == 'completed') {
                                            $statusColor = 'bg-green-500';
                                            $rowColor = 'bg-green-900/10';
                                        } elseif ($chantier->etat == 'in_progress') {
                                            $statusColor = 'bg-blue-500';
                                            $rowColor = 'bg-blue-900/10';
                                        } elseif ($chantier->etat == 'waiting') {
                                            $statusColor = 'bg-orange-500';
                                            $rowColor = '';
                                        } elseif ($chantier->etat == 'planned') {
                                            $statusColor = 'bg-purple-500';
                                            $rowColor = '';
                                        } else {
                                            $statusColor = 'bg-gray-500';
                                            $rowColor = '';
                                        }
                                    @endphp
                                    
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full {{ $statusColor }} mr-2"></div>
                                        
                                        @if($chantier->etat == 'completed')
                                            <span style="background-color: rgba(16, 185, 129, 0.2); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 9999px; padding: 0.25rem 0.75rem; font-size: 0.75rem;">Terminé (100%)</span>
                                        @elseif($chantier->etat == 'in_progress')
                                            <div class="flex flex-col">
                                                <span style="background-color: rgba(59, 130, 246, 0.2); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 9999px; padding: 0.25rem 0.75rem; font-size: 0.75rem; margin-bottom: 0.25rem;">En cours ({{ $completion }}%)</span>
                                                <div style="background-color: rgba(55, 65, 81, 0.5); border-radius: 9999px; height: 0.375rem; width: 100%;">
                                                    <div style="background-color: #3B82F6; border-radius: 9999px; height: 0.375rem; width: {{ $completion }}%;"></div>
                                                </div>
                                            </div>
                                        @elseif($chantier->etat == 'waiting')
                                            <span style="background-color: rgba(245, 158, 11, 0.2); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 9999px; padding: 0.25rem 0.75rem; font-size: 0.75rem;">En attente</span>
                                        @elseif($chantier->etat == 'planned')
                                            <span style="background-color: rgba(59, 130, 246, 0.2); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 9999px; padding: 0.25rem 0.75rem; font-size: 0.75rem;">Planifié</span>
                                        @else
                                            <span style="background-color: rgba(75, 85, 99, 0.2); color: #9CA3AF; border: 1px solid rgba(75, 85, 99, 0.3); border-radius: 9999px; padding: 0.25rem 0.75rem; font-size: 0.75rem;">{{ ucfirst($chantier->etat ?? 'Non défini') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('suivi.chantier', $chantier->token_suivi) }}" class="bg-gray-800 hover:bg-gray-700 text-white py-1 px-3 text-sm inline-flex items-center rounded-md border border-gray-700">
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-text-secondary mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="text-lg font-medium text-text-primary mb-1">Aucun projet trouvé</h3>
                    <p class="text-text-secondary">Vous n'avez pas encore de projets enregistrés dans notre système.</p>
                </div>
            @endif
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="glassmorphism p-6 border-t-4 border-accent-blue">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-blue mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Support Technique
                </h2>
                <p class="text-text-secondary mb-4">Notre équipe technique est disponible pour vous aider avec vos projets et répondre à vos questions techniques.</p>
                <a href="mailto:support@tecaled.fr" class="bg-gray-800 hover:bg-gray-700 text-white py-2 px-4 rounded-md border border-gray-700 inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contacter le support
                </a>
            </div>
            
            <div class="glassmorphism p-6 border-t-4 border-accent-green">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-green mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Guide d'utilisation
                </h2>
                <p class="text-text-secondary mb-4">Suivez l'état de vos réparations et comprenez les différentes informations de votre tableau de bord.</p>
                <ul class="space-y-2 text-text-secondary">
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-green mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Suivez l'avancement de vos projets en temps réel</span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-green mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Consultez les détails des interventions effectuées</span>
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-green mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Accédez à l'historique complet de vos projets</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-border-dark py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <img src="{{ asset('images/logo-repair.png') }}" alt="TecaLED Logo" class="h-8 mr-3">
                    <span class="text-text-secondary">© {{ date('Y') }} TecaLED. Tous droits réservés.</span>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-text-secondary hover:text-text-primary transition-colors">Mentions légales</a>
                    <a href="#" class="text-text-secondary hover:text-text-primary transition-colors">Confidentialité</a>
                    <a href="#" class="text-text-secondary hover:text-text-primary transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>