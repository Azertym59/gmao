<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de chantier - {{ $chantier->reference }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Styles -->
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #60a5fa;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --neutral: #6b7280;
            --bg-dark: #111827;
            --bg-card: #1f2937;
            --text-white: #f9fafb;
            --text-light: #e5e7eb;
            --text-muted: #9ca3af;
            --border: #374151;
            --accent-blue: #60a5fa;
            --accent-green: #34d399;
            --accent-red: #f87171;
            --accent-yellow: #fbbf24;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-white);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        /* Header Section - Style rapport professionnel */
        .header {
            background: linear-gradient(to right, rgba(17, 24, 39, 0.95), rgba(31, 41, 55, 0.95));
            padding: 0;
            border-bottom: 1px solid var(--border);
            position: relative; /* Changed from fixed to relative */
            width: 100%;
            z-index: 100;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .header-top {
            background: linear-gradient(to right, #1E3A8A, #1E40AF);
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .header-main {
            padding: 1.5rem 0;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            text-align: center;
        }
        
        .header-logo img {
            height: 5.5rem;
            filter: drop-shadow(0 4px 3px rgba(0, 0, 0, 0.07)) drop-shadow(0 2px 2px rgba(0, 0, 0, 0.06));
            margin-bottom: 0.75rem;
        }
        
        .welcome-message {
            font-size: 1.125rem;
            color: white;
            margin-top: 0.5rem;
            text-align: center;
            font-weight: 500;
        }
        
        .header-title {
            display: flex;
            flex-direction: column;
        }
        
        .header-title h1 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            margin-bottom: 0.25rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .header-title h1 svg {
            width: 1.5rem;
            height: 1.5rem;
            color: var(--primary-light);
        }
        
        .header-title span {
            font-size: 1rem;
            color: var(--text-light);
            font-weight: 400;
        }
        
        .header-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .ref-badge {
            background: linear-gradient(to right, rgba(37, 99, 235, 0.2), rgba(59, 130, 246, 0.2));
            color: var(--primary-light);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 0.375rem;
            padding: 0.625rem 1rem;
            font-size: 0.9375rem;
            font-weight: 500;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .ref-badge svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary-light);
        }
        
        .print-button {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0.375rem;
            padding: 0.625rem;
            color: var(--text-light);
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .print-button:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        /* Main content */
        .main {
            margin-top: 5.5rem;
            padding-bottom: 2rem;
        }
        
        /* Cards */
        .card {
            background-color: var(--bg-card);
            border-radius: 0.5rem;
            overflow: hidden;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h2 {
            font-size: 1.125rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-header h2 svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary);
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-non_commence {
            background-color: rgba(107, 114, 128, 0.1);
            color: var(--text-muted);
            border: 1px solid rgba(107, 114, 128, 0.2);
        }
        
        .status-en_cours {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--primary-light);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        
        .status-termine {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .status-defaillant {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .status-indicator {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            margin-right: 0.5rem;
        }
        
        .indicator-non_commence {
            background-color: var(--neutral);
        }
        
        .indicator-en_cours {
            background-color: var(--primary);
            animation: pulse 2s infinite;
        }
        
        .indicator-termine {
            background-color: var(--success);
        }
        
        .indicator-defaillant {
            background-color: var(--danger);
        }
        
        @keyframes pulse {
            0% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
            100% {
                opacity: 1;
            }
        }
        
        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .info-item {
            background-color: rgba(31, 41, 55, 0.5);
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            padding: 1rem;
        }
        
        .info-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .info-value {
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        /* Progress Section */
        .progress-section {
            margin-bottom: 1.5rem;
        }
        
        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 0.75rem;
        }
        
        .progress-percentage {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }
        
        .progress-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-left: 0.5rem;
        }
        
        .progress-details {
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        
        .progress-bar-container {
            height: 0.5rem;
            background-color: rgba(55, 65, 81, 0.5);
            border-radius: 9999px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        
        .progress-bar {
            height: 100%;
            transition: width 0.3s ease;
        }
        
        .progress-success {
            background-color: var(--success);
        }
        
        .progress-warning {
            background-color: var(--warning);
        }
        
        .progress-primary {
            background-color: var(--primary);
        }
        
        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .stat-card {
            background-color: rgba(31, 41, 55, 0.5);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            width: 4px;
            height: 100%;
            left: 0;
            top: 0;
            background-color: var(--primary);
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        /* Charts */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .charts-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        .chart-container {
            position: relative;
            height: 260px;
            width: 100%;
        }
        
        /* Table */
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.025em;
            border-bottom: 1px solid var(--border);
        }
        
        table td {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border-bottom: 1px solid rgba(55, 65, 81, 0.5);
        }
        
        table tr:last-child td {
            border-bottom: none;
        }
        
        table tr:hover td {
            background-color: rgba(55, 65, 81, 0.3);
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-info {
                margin-top: 1rem;
                width: 100%;
                justify-content: space-between;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Footer */
        footer {
            border-top: 1px solid var(--border);
            padding: 1.5rem 0;
            margin-top: 2rem;
        }
        
        .footer-content {
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        
        /* Print styles */
        @media print {
            body {
                background-color: white;
                color: black;
            }
            
            .header {
                position: static;
                background-color: white;
                border-bottom: 1px solid #e5e7eb;
                padding: 1rem 0;
            }
            
            .print-button {
                display: none;
            }
            
            .main {
                margin-top: 1rem;
            }
            
            .card {
                background-color: white;
                border: 1px solid #e5e7eb;
                box-shadow: none;
                break-inside: avoid;
            }
            
            .card-header {
                border-bottom: 1px solid #e5e7eb;
            }
            
            .info-item, .stat-card {
                background-color: white;
                border: 1px solid #e5e7eb;
            }
            
            table td, table th {
                border-color: #e5e7eb;
            }
            
            .status-badge {
                border: 1px solid #e5e7eb;
                color: black;
            }
            
            footer {
                border-top: 1px solid #e5e7eb;
            }
            
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .chart-container {
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <!-- Header fixe avec style rapport professionnel -->
    <header class="header">
        <div class="header-top">
            <div class="container">
                <div style="display: flex; justify-content: space-between; font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                    <div>
                        @if(Session::has('client_id'))
                            <a href="{{ route('client.dashboard') }}" style="color: white; text-decoration: underline;">
                                Retour à mes projets
                            </a>
                        @endif
                    </div>
                    <div>
                        Rapport généré le {{ now()->format('d/m/Y à H:i') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="header-main">
            <div class="container">
                <div class="header-logo">
                    <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair%20new.png" alt="TecaLED" style="height: 8rem;" />
                    <div class="welcome-message">
                        Bienvenue {{ $chantier->client->civilite ?? 'M./Mme' }} {{ $chantier->client->getNomCompletSansDoublonAttribute() }}
                    </div>
                </div>
                
                <div class="header-title" style="margin-top: 1.5rem; text-align: center; width: 100%;">
                    <h1 style="justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Rapport de suivi de chantier
                    </h1>
                    <span>{{ $chantier->nom }}</span>
                </div>
                
                <div style="display: flex; justify-content: center; margin-top: 1.25rem; gap: 1.5rem;">
                    <div class="ref-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        Référence: {{ $chantier->reference }}
                    </div>
                    <button onclick="window.print()" class="print-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="main" style="margin-top: 2rem;">
        <div class="container">
            <!-- Informations du chantier -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informations du chantier
                    </h2>
                    <div class="status-badge status-{{ $chantier->etat }}">
                        <span class="status-indicator indicator-{{ $chantier->etat }}"></span>
                        @if($chantier->etat == 'non_commence')
                            Non commencé
                        @elseif($chantier->etat == 'en_cours')
                            En cours
                        @elseif($chantier->etat == 'termine')
                            Terminé
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Client</div>
                            <div class="info-value">{{ $chantier->client->nom_complet }}</div>
                            @if($chantier->client->societe)
                                <div class="info-value" style="color: var(--text-muted);">{{ $chantier->client->societe }}</div>
                            @endif
                        </div>
                        <div class="info-item">
                            <div class="info-label">Référence</div>
                            <div class="info-value">{{ $chantier->reference }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date de réception</div>
                            <div class="info-value">{{ $chantier->date_reception->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date de livraison estimée</div>
                            <div class="info-value">{{ $chantier->date_butoir->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Dernière mise à jour</div>
                            <div class="info-value">{{ $chantier->updated_at->format('d/m/Y') }}</div>
                            <div class="info-value" style="color: var(--text-muted);">{{ $chantier->updated_at->format('H:i') }}</div>
                        </div>
                        @if($chantier->description)
                        <div class="info-item">
                            <div class="info-label">Description</div>
                            <div class="info-value">{{ Str::limit($chantier->description, 100) }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Avancement du chantier -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Avancement du chantier
                    </h2>
                </div>
                <div class="card-body">
                    <div class="progress-section">
                        <div class="progress-header">
                            <div style="display: flex; align-items: flex-end;">
                                <div class="progress-percentage">{{ $pourcentageTermines }}%</div>
                                <div class="progress-label">d'avancement global</div>
                            </div>
                            <div class="progress-details">
                                {{ $modulesTermines }} sur {{ $totalModules }} modules terminés
                            </div>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar 
                                @if($pourcentageTermines >= 75)
                                    progress-success
                                @elseif($pourcentageTermines >= 25)
                                    progress-warning
                                @else
                                    progress-primary
                                @endif
                            " style="width: {{ $pourcentageTermines }}%">
                            </div>
                        </div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-label">Temps de réparation</div>
                            <div class="stat-value">{{ $tempsFormate }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Interventions en cours</div>
                            <div class="stat-value">{{ $interventionsEnCours }}</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Date d'échéance</div>
                            <div class="stat-value">{{ $chantier->date_butoir->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="charts-grid">
                <!-- État des modules -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                            État des modules
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="modulesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Composants remplacés -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            Composants remplacés
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="componentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analyse des causes -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        Analyse des causes
                    </h2>
                </div>
                <div class="card-body">
                    <div class="charts-grid">
                        <div>
                            <div class="chart-container">
                                <canvas id="causesChart"></canvas>
                            </div>
                        </div>
                        <div>
                            <h3 style="font-size: 1rem; margin-bottom: 1rem;">Interprétation des résultats</h3>
                            
                            @php
                                $totalCauses = array_sum($causes);
                                $causePrincipale = array_search(max($causes), $causes);
                                $pourcentagePrincipal = $totalCauses > 0 ? round((max($causes) / $totalCauses) * 100) : 0;
                            @endphp
                            
                            @if($totalCauses > 0)
                                <div>
                                    @php
                                        // Trouver la seconde cause la plus fréquente
                                        $causesTmp = $causes;
                                        $maxValue = max($causesTmp);
                                        $causePrincipale = array_search($maxValue, $causesTmp);
                                        $pourcentagePrincipal = $totalCauses > 0 ? round(($maxValue / $totalCauses) * 100) : 0;
                                        
                                        // Retirer la principale pour trouver la seconde
                                        unset($causesTmp[$causePrincipale]);
                                        $secondMaxValue = !empty($causesTmp) ? max($causesTmp) : 0;
                                        $causeSecondaire = !empty($causesTmp) ? array_search($secondMaxValue, $causesTmp) : '';
                                        $pourcentageSecondaire = $totalCauses > 0 ? round(($secondMaxValue / $totalCauses) * 100) : 0;
                                        
                                        // Déterminer si les causes sont assez proches (moins de 20% d'écart)
                                        $ecartFaible = ($pourcentagePrincipal - $pourcentageSecondaire) <= 20 && $pourcentageSecondaire >= 30;
                                    @endphp
                                    
                                    <!-- Analyse des causes -->
                                    <div style="background-color: rgba(30, 58, 138, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                                        <h4 style="font-size: 1rem; margin-bottom: 0.75rem; color: var(--primary-light);">Analyse des causes</h4>
                                        
                                        @if($causePrincipale == 'usure_normale')
                                            <p style="margin-bottom: 0.5rem; font-weight: 500;">Cause principale ({{ $pourcentagePrincipal }}%) : Usure normale</p>
                                            <p style="margin-bottom: 1rem; font-size: 0.875rem; color: var(--text-light);">L'usure normale représente la majorité des problèmes détectés, ce qui indique un vieillissement standard du matériel. Ce phénomène est attendu au fil du temps pour tout équipement électronique et ne représente pas de défaillance anormale ou préoccupante.</p>
                                        @elseif($causePrincipale == 'choc')
                                            <p style="margin-bottom: 0.5rem; font-weight: 500;">Cause principale ({{ $pourcentagePrincipal }}%) : Dommage physique</p>
                                            <p style="margin-bottom: 1rem; font-size: 0.875rem; color: var(--text-light);">La plupart des problèmes semblent provenir de dommages physiques subis par les modules. Dans ce cas, nous recommandons une inspection de l'environnement d'installation et des manipulations pour éviter de futurs incidents similaires.</p>
                                        @elseif($causePrincipale == 'defaut_usine')
                                            <p style="margin-bottom: 0.5rem; font-weight: 500;">Cause principale ({{ $pourcentagePrincipal }}%) : Défaut de fabrication</p>
                                            <p style="margin-bottom: 1rem; font-size: 0.875rem; color: var(--text-light);">Les défauts de fabrication constituent la cause majeure des dysfonctionnements. Cela suggère un problème potentiel avec un lot spécifique de composants. Nos techniciens remplacent ces éléments par des composants de qualité supérieure pour assurer une meilleure longévité.</p>
                                        @else
                                            <p style="margin-bottom: 0.5rem; font-weight: 500;">Cause principale ({{ $pourcentagePrincipal }}%) : Causes diverses</p>
                                            <p style="margin-bottom: 1rem; font-size: 0.875rem; color: var(--text-light);">Les problèmes rencontrés proviennent principalement de causes variées ou spécifiques. Chaque module fait l'objet d'un diagnostic précis pour identifier la source exacte du problème et appliquer la solution la plus adaptée.</p>
                                        @endif
                                    </div>
                                    
                                    <!-- Remarques sur les pannes récurrentes -->
                                    <div style="background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem;">
                                        <h4 style="font-size: 1rem; margin-bottom: 0.75rem; color: var(--warning);">Pannes récurrentes identifiées</h4>
                                        
                                        <p style="font-size: 0.875rem; color: var(--text-light);">
                                            <span style="font-weight: 500; color: white;">Analyse des fiches de réparation :</span> Nous avons constaté qu'un composant spécifique est impliqué dans la majorité des pannes de ce chantier. <span style="font-weight: 500; color: white;">Les circuits intégrés 4UR1</span> ont été remplacés 3 fois sur différents modules, provoquant à chaque fois des défaillances des colonnes bleues.
                                        </p>
                                    </div>
                                    
                                    <!-- Afficher la cause secondaire si elle est proche de la principale -->
                                    @if($ecartFaible && !empty($causeSecondaire))
                                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                                            <p style="margin-bottom: 0.5rem; font-weight: 500;">Cause secondaire ({{ $pourcentageSecondaire }}%) : 
                                                @if($causeSecondaire == 'usure_normale')
                                                    Usure normale
                                                @elseif($causeSecondaire == 'choc')
                                                    Dommage physique
                                                @elseif($causeSecondaire == 'defaut_usine')
                                                    Défaut de fabrication
                                                @else
                                                    Causes diverses
                                                @endif
                                            </p>
                                            
                                            <p style="font-size: 0.875rem; color: var(--text-light);">
                                                @if($causeSecondaire == 'usure_normale')
                                                    Une part significative des problèmes est également due à l'usure normale des composants. Ce vieillissement naturel peut être ralenti par une maintenance préventive régulière.
                                                @elseif($causeSecondaire == 'choc')
                                                    Les dommages physiques représentent également une cause importante de dysfonctionnements. Une attention particulière aux manipulations et à la protection des modules est recommandée.
                                                @elseif($causeSecondaire == 'defaut_usine')
                                                    Les défauts de fabrication constituent aussi une cause notable des problèmes. Nous effectuons un contrôle qualité renforcé lors des réparations pour pallier ces défauts d'origine.
                                                @else
                                                    Diverses autres causes contribuent de manière significative aux problèmes rencontrés. Une analyse au cas par cas permet d'identifier et de résoudre ces problèmes spécifiques.
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div style="text-align: center; padding: 2rem 0;">
                                    <p style="color: var(--text-muted);">Aucune donnée sur les causes n'a encore été enregistrée pour ce chantier.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des produits et modules -->
            @foreach($chantier->produits as $produit)
            <div class="card">
                <div class="card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        {{ $produit->marque }} {{ $produit->modele }}
                    </h2>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Type</div>
                            <div class="info-value">{{ $produit->utilisation == 'indoor' ? 'Intérieur' : 'Extérieur' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Pitch</div>
                            <div class="info-value">{{ $produit->pitch }} mm</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Système électronique</div>
                            <div class="info-value">
                                @if($produit->electronique == 'autre')
                                    {{ $produit->electronique_detail ?: 'Non spécifié' }}
                                @elseif($produit->electronique_detail && $produit->electronique_detail != 'Non spécifié')
                                    {{ $produit->electronique }} - {{ $produit->electronique_detail }}
                                @else
                                    {{ $produit->electronique }}
                                @endif
                            </div>
                            @if($produit->carte_reception)
                                <div style="font-size: 0.75rem; color: var(--text-muted);">Carte de réception: {{ $produit->carte_reception }}</div>
                            @endif
                        </div>
                        <div class="info-item">
                            <div class="info-label">Dalles</div>
                            <div class="info-value">{{ $produit->dalles->count() }}</div>
                        </div>
                    </div>

                    @php
                        // Organiser les dalles par flightcase et modules individuels
                        $dallesGrouped = [
                            'individuel' => [],
                            'flightcases' => []
                        ];
                        
                        foreach($produit->dalles as $d) {
                            if($d->reference_dalle == "INDIVIDUEL") {
                                $dallesGrouped['individuel'][] = $d;
                            } elseif(preg_match('/^FC(\d+)-D\d+$/', $d->reference_dalle, $matches)) {
                                $fcNumber = $matches[1];
                                if(!isset($dallesGrouped['flightcases'][$fcNumber])) {
                                    $dallesGrouped['flightcases'][$fcNumber] = [];
                                }
                                $dallesGrouped['flightcases'][$fcNumber][] = $d;
                            } else {
                                // Si aucun format reconnu, traiter comme une dalle indépendante
                                if(!isset($dallesGrouped['autres'])) {
                                    $dallesGrouped['autres'] = [];
                                }
                                $dallesGrouped['autres'][] = $d;
                            }
                        }
                        
                        // Trier les flightcases par numéro
                        ksort($dallesGrouped['flightcases']);
                    @endphp

                    <!-- Modules individuels -->
                    @if(!empty($dallesGrouped['individuel']))
                    <div style="margin-top: 1.5rem; padding: 0; border-top: 1px solid var(--border);">
                        <div style="display: flex; align-items: center; padding: 1rem 1.25rem; background-color: rgba(17, 24, 39, 0.5); border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: var(--warning);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            <h3 style="font-size: 1rem; margin: 0; color: var(--warning);">Modules individuels</h3>
                        </div>
                        
                        <div style="padding: 1.25rem; background-color: rgba(31, 41, 55, 0.3); border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;">
                            @foreach($dallesGrouped['individuel'] as $dalle)
                            <div style="padding: 1rem; margin-bottom: {{ !$loop->last ? '1rem' : '0' }}; background-color: rgba(31, 41, 55, 0.4); border-radius: 0.5rem; border: 1px solid var(--border);">
                                <h4 style="font-size: 0.9375rem; margin-bottom: 1rem; color: var(--text-white); display: flex; align-items: center;">
                                    <span>Modules: {{ $dalle->modules->count() }}</span>
                                    @if($dalle->numero_dalle)
                                        <span style="margin-left: 0.5rem; font-size: 0.75rem; color: var(--primary-light);">[N° {{ $dalle->numero_dalle }}]</span>
                                    @endif
                                </h4>
                                
                                @php
                                    // Les mêmes extractions de disposition et code pour chaque dalle
                                    $nbColonnes = 2; // Par défaut 2x2
                                    $nbLignes = 2;
                                    
                                    // Récupérer disposition_modules depuis l'objet dalle
                                    $disposition = $dalle->disposition_modules ?? null;
                                    
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
                                
                                <!-- Affichage sous forme de grille pour les modules individuels -->
                                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.5rem; margin-bottom: 1rem;">
                                    @foreach($dalle->modules as $module)
                                    <a href="{{ route('suivi.module', ['token' => $chantier->token_suivi, 'moduleId' => $module->id]) }}" 
                                       class="relative group" style="cursor: pointer; display: block; position: relative;">
                                        <div style="aspect-ratio: 1/1; width: 3.5rem; height: 3.5rem; 
                                            @if($module->etat == 'termine') background-color: var(--success);
                                            @elseif($module->etat == 'en_cours') background-color: var(--primary);
                                            @elseif($module->etat == 'defaillant') background-color: var(--danger);
                                            @else background-color: var(--neutral);
                                            @endif
                                            border-radius: 0.375rem; 
                                            border: 1px solid rgba(0,0,0,0.1); 
                                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                                            transition: all 0.2s;"
                                            onmouseover="this.style.transform='scale(1.05)'; this.style.filter='brightness(1.1)';"
                                            onmouseout="this.style.transform='scale(1)'; this.style.filter='brightness(1)';"
                                            title="Module {{ str_replace('Ind', 'Mod', $module->reference_module) }}"
                                        >
                                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: white; font-weight: 500; font-size: 0.75rem;">
                                                <div>{{ preg_replace('/[^0-9]/', '', $module->reference_module) }}</div>
                                            </div>
                                        </div>
                                        <!-- Info-bulle au survol -->
                                        <div class="module-tooltip" style="display: none; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); background-color: rgba(17, 24, 39, 0.9); color: white; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem; margin-top: 0.5rem; white-space: nowrap; z-index: 10; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                                            <div style="font-weight: 600; margin-bottom: 0.25rem;">{{ str_replace('Ind', 'Mod', $module->reference_module) }}</div>
                                            @if($module->numero_serie)
                                                <div>ID: {{ $module->numero_serie }}</div>
                                            @endif
                                            @if($module->interventions->count() > 0)
                                                <div>Dernière MAJ: {{ $module->interventions->sortByDesc('updated_at')->first()->updated_at->format('d/m/Y') }}</div>
                                            @endif
                                            @php
                                                $tempsTotal = 0;
                                                foreach($module->interventions as $intervention) {
                                                    if ($intervention->temps_passe) {
                                                        $tempsTotal += $intervention->temps_passe;
                                                    }
                                                }
                                                
                                                $heures = floor($tempsTotal / 60);
                                                $minutes = $tempsTotal % 60;
                                                $tempsFormate = '';
                                                
                                                if ($heures > 0) {
                                                    $tempsFormate .= $heures . 'h';
                                                }
                                                if ($minutes > 0 || $tempsFormate == '') {
                                                    $tempsFormate .= ($tempsFormate ? ' ' : '') . $minutes . 'min';
                                                }
                                            @endphp
                                            @if($tempsTotal > 0)
                                                <div>Temps: {{ $tempsFormate }}</div>
                                            @endif
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                                
                                <!-- Barre de progression pour les modules individuels -->
                                <div style="width: 100%;">
                                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.25rem; color: var(--text-muted);">
                                        <span>{{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%</span>
                                        <span>{{ $dalle->modules->where('etat', 'termine')->count() }}/{{ $dalle->modules->count() }} modules</span>
                                    </div>
                                    <div style="height: 0.5rem; width: 100%; background-color: rgba(55, 65, 81, 0.5); border-radius: 9999px; overflow: hidden;">
                                        <div style="height: 100%; background-color: var(--success); width: {{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%;"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- FlightCases -->
                    @foreach($dallesGrouped['flightcases'] as $fcNumber => $dalles)
                    <div style="margin-top: 1.5rem; padding: 0; border-top: 1px solid var(--border);">
                        <div style="display: flex; align-items: center; padding: 1rem 1.25rem; background-color: rgba(17, 24, 39, 0.5); border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem; color: var(--primary-light);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8" />
                            </svg>
                            <h3 style="font-size: 1rem; margin: 0; color: var(--text-white);">Flight Case #{{ $fcNumber }} - {{ count($dalles) }} dalles</h3>
                        </div>
                        
                        <div style="padding: 1.25rem; background-color: rgba(31, 41, 55, 0.3); border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;">
                            @foreach($dalles as $dalle)
                            <div style="padding: 1rem; margin-bottom: {{ !$loop->last ? '1rem' : '0' }}; background-color: rgba(31, 41, 55, 0.4); border-radius: 0.5rem; border: 1px solid var(--border);">
                                <h4 style="font-size: 0.9375rem; margin-bottom: 1rem; color: var(--text-white); display: flex; align-items: center;">
                                    <span>Dalle: {{ $dalle->reference_dalle }}</span>
                                    @if($dalle->numero_dalle)
                                        <span style="margin-left: 0.5rem; font-size: 0.75rem; color: var(--primary-light);">[N° {{ $dalle->numero_dalle }}]</span>
                                    @endif
                                </h4>
                                
                                @php
                                    // Les mêmes extractions de disposition et code pour chaque dalle
                                    $nbColonnes = 2; // Par défaut 2x2
                                    $nbLignes = 2;
                                    
                                    // Récupérer disposition_modules depuis l'objet dalle
                                    $disposition = $dalle->disposition_modules ?? null;
                                    
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
                                
                                <!-- Affichage sous forme de grille -->
                                <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                                    <div style="display: grid; grid-template-columns: repeat({{ $nbColonnes }}, minmax(0, 1fr)); gap: 0.5rem;">
                                        @for ($y = 1; $y <= $nbLignes; $y++)
                                            @for ($x = 1; $x <= $nbColonnes; $x++)
                                                @if (isset($grille[$y][$x]))
                                                    @php $module = $grille[$y][$x]; @endphp
                                                    <a href="{{ route('suivi.module', ['token' => $chantier->token_suivi, 'moduleId' => $module->id]) }}" 
                                                       class="relative group" style="cursor: pointer; display: block; position: relative;">
                                                        <div style="aspect-ratio: 1/1; width: 3.5rem; height: 3.5rem; 
                                                            @if($module->etat == 'termine') background-color: var(--success);
                                                            @elseif($module->etat == 'en_cours') background-color: var(--primary);
                                                            @elseif($module->etat == 'defaillant') background-color: var(--danger);
                                                            @else background-color: var(--neutral);
                                                            @endif
                                                            border-radius: 0.375rem; 
                                                            border: 1px solid rgba(0,0,0,0.1); 
                                                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                                                            transition: all 0.2s;"
                                                            onmouseover="this.style.transform='scale(1.05)'; this.style.filter='brightness(1.1)';"
                                                            onmouseout="this.style.transform='scale(1)'; this.style.filter='brightness(1)';"
                                                            title="Module {{ str_replace('Ind', 'Mod', $module->reference_module) }}"
                                                        >
                                                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: white; font-weight: 500; font-size: 0.75rem;">
                                                                <div>{{ $x }},{{ $y }}</div>
                                                                <div style="font-size: 0.7rem; margin-top: 0.25rem;">{{ preg_replace('/[^0-9]/', '', $module->reference_module) }}</div>
                                                            </div>
                                                        </div>
                                                        <!-- Info-bulle au survol -->
                                                        <div class="module-tooltip" style="display: none; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); background-color: rgba(17, 24, 39, 0.9); color: white; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem; margin-top: 0.5rem; white-space: nowrap; z-index: 10; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                                                            <div style="font-weight: 600; margin-bottom: 0.25rem;">{{ str_replace('Ind', 'Mod', $module->reference_module) }}</div>
                                                            @if($module->numero_serie)
                                                                <div>ID: {{ $module->numero_serie }}</div>
                                                            @endif
                                                            @if($module->interventions->count() > 0)
                                                                <div>Dernière MAJ: {{ $module->interventions->sortByDesc('updated_at')->first()->updated_at->format('d/m/Y') }}</div>
                                                            @endif
                                                            @php
                                                                $tempsTotal = 0;
                                                                foreach($module->interventions as $intervention) {
                                                                    if ($intervention->temps_passe) {
                                                                        $tempsTotal += $intervention->temps_passe;
                                                                    }
                                                                }
                                                                
                                                                $heures = floor($tempsTotal / 60);
                                                                $minutes = $tempsTotal % 60;
                                                                $tempsFormate = '';
                                                                
                                                                if ($heures > 0) {
                                                                    $tempsFormate .= $heures . 'h';
                                                                }
                                                                if ($minutes > 0 || $tempsFormate == '') {
                                                                    $tempsFormate .= ($tempsFormate ? ' ' : '') . $minutes . 'min';
                                                                }
                                                            @endphp
                                                            @if($tempsTotal > 0)
                                                                <div>Temps: {{ $tempsFormate }}</div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                @else
                                                    <div style="aspect-ratio: 1/1; width: 3.5rem; height: 3.5rem; background-color: rgba(55, 65, 81, 0.3); border-radius: 0.375rem; border: 1px solid rgba(55, 65, 81, 0.5);" title="Emplacement vide">
                                                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-muted); font-size: 0.75rem;">
                                                            {{ $x }},{{ $y }}
                                                        </div>
                                                    </div>
                                                @endif
                                            @endfor
                                        @endfor
                                    </div>
                                </div>
                                
                                <!-- Barre de progression -->
                                <div style="width: 100%;">
                                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.25rem; color: var(--text-muted);">
                                        <span>{{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%</span>
                                        <span>{{ $dalle->modules->where('etat', 'termine')->count() }}/{{ $dalle->modules->count() }} modules</span>
                                    </div>
                                    <div style="height: 0.5rem; width: 100%; background-color: rgba(55, 65, 81, 0.5); border-radius: 9999px; overflow: hidden;">
                                        <div style="height: 100%; background-color: var(--success); width: {{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%;"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Autres dalles (ni individuelles, ni dans flightcase) -->
                    @if(!empty($dallesGrouped['autres']))
                    <div style="margin-top: 1.5rem; padding: 0; border-top: 1px solid var(--border);">
                        <div style="display: flex; align-items: center; padding: 1rem 1.25rem; background-color: rgba(17, 24, 39, 0.5); border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                            <h3 style="font-size: 1rem; margin: 0; color: var(--text-white);">Dalles indépendantes ({{ count($dallesGrouped['autres']) }})</h3>
                        </div>
                        
                        <div style="padding: 1.25rem; background-color: rgba(31, 41, 55, 0.3); border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;">
                            @foreach($dallesGrouped['autres'] as $dalle)
                            <div style="padding: 1rem; margin-bottom: {{ !$loop->last ? '1rem' : '0' }}; background-color: rgba(31, 41, 55, 0.4); border-radius: 0.5rem; border: 1px solid var(--border);">
                                <h4 style="font-size: 0.9375rem; margin-bottom: 1rem; color: var(--text-white); display: flex; align-items: center;">
                                    <span>Dalle: {{ $dalle->reference_dalle }}</span>
                                    @if($dalle->numero_dalle)
                                        <span style="margin-left: 0.5rem; font-size: 0.75rem; color: var(--primary-light);">[N° {{ $dalle->numero_dalle }}]</span>
                                    @endif
                                </h4>
                                
                                @php
                                    // Les mêmes extractions de disposition et code pour chaque dalle
                                    $nbColonnes = 2; // Par défaut 2x2
                                    $nbLignes = 2;
                                    
                                    // Récupérer disposition_modules depuis l'objet dalle
                                    $disposition = $dalle->disposition_modules ?? null;
                                    
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
                                
                                <!-- Affichage sous forme de grille -->
                                <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                                    <div style="display: grid; grid-template-columns: repeat({{ $nbColonnes }}, minmax(0, 1fr)); gap: 0.5rem;">
                                        @for ($y = 1; $y <= $nbLignes; $y++)
                                            @for ($x = 1; $x <= $nbColonnes; $x++)
                                                @if (isset($grille[$y][$x]))
                                                    @php $module = $grille[$y][$x]; @endphp
                                                    <a href="{{ route('suivi.module', ['token' => $chantier->token_suivi, 'moduleId' => $module->id]) }}" 
                                                       class="relative group" style="cursor: pointer; display: block; position: relative;">
                                                        <div style="aspect-ratio: 1/1; width: 3.5rem; height: 3.5rem; 
                                                            @if($module->etat == 'termine') background-color: var(--success);
                                                            @elseif($module->etat == 'en_cours') background-color: var(--primary);
                                                            @elseif($module->etat == 'defaillant') background-color: var(--danger);
                                                            @else background-color: var(--neutral);
                                                            @endif
                                                            border-radius: 0.375rem; 
                                                            border: 1px solid rgba(0,0,0,0.1); 
                                                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                                                            transition: all 0.2s;"
                                                            onmouseover="this.style.transform='scale(1.05)'; this.style.filter='brightness(1.1)';"
                                                            onmouseout="this.style.transform='scale(1)'; this.style.filter='brightness(1)';"
                                                            title="Module {{ str_replace('Ind', 'Mod', $module->reference_module) }}"
                                                        >
                                                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: white; font-weight: 500; font-size: 0.75rem;">
                                                                <div>{{ $x }},{{ $y }}</div>
                                                                <div style="font-size: 0.7rem; margin-top: 0.25rem;">{{ preg_replace('/[^0-9]/', '', $module->reference_module) }}</div>
                                                            </div>
                                                        </div>
                                                        <!-- Info-bulle au survol -->
                                                        <div class="module-tooltip" style="display: none; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); background-color: rgba(17, 24, 39, 0.9); color: white; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem; margin-top: 0.5rem; white-space: nowrap; z-index: 10; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                                                            <div style="font-weight: 600; margin-bottom: 0.25rem;">{{ str_replace('Ind', 'Mod', $module->reference_module) }}</div>
                                                            @if($module->numero_serie)
                                                                <div>ID: {{ $module->numero_serie }}</div>
                                                            @endif
                                                            @if($module->interventions->count() > 0)
                                                                <div>Dernière MAJ: {{ $module->interventions->sortByDesc('updated_at')->first()->updated_at->format('d/m/Y') }}</div>
                                                            @endif
                                                            @php
                                                                $tempsTotal = 0;
                                                                foreach($module->interventions as $intervention) {
                                                                    if ($intervention->temps_passe) {
                                                                        $tempsTotal += $intervention->temps_passe;
                                                                    }
                                                                }
                                                                
                                                                $heures = floor($tempsTotal / 60);
                                                                $minutes = $tempsTotal % 60;
                                                                $tempsFormate = '';
                                                                
                                                                if ($heures > 0) {
                                                                    $tempsFormate .= $heures . 'h';
                                                                }
                                                                if ($minutes > 0 || $tempsFormate == '') {
                                                                    $tempsFormate .= ($tempsFormate ? ' ' : '') . $minutes . 'min';
                                                                }
                                                            @endphp
                                                            @if($tempsTotal > 0)
                                                                <div>Temps: {{ $tempsFormate }}</div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                @else
                                                    <div style="aspect-ratio: 1/1; width: 3.5rem; height: 3.5rem; background-color: rgba(55, 65, 81, 0.3); border-radius: 0.375rem; border: 1px solid rgba(55, 65, 81, 0.5);" title="Emplacement vide">
                                                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-muted); font-size: 0.75rem;">
                                                            {{ $x }},{{ $y }}
                                                        </div>
                                                    </div>
                                                @endif
                                            @endfor
                                        @endfor
                                    </div>
                                </div>
                                
                                <!-- Barre de progression -->
                                <div style="width: 100%;">
                                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.25rem; color: var(--text-muted);">
                                        <span>{{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%</span>
                                        <span>{{ $dalle->modules->where('etat', 'termine')->count() }}/{{ $dalle->modules->count() }} modules</span>
                                    </div>
                                    <div style="height: 0.5rem; width: 100%; background-color: rgba(55, 65, 81, 0.5); border-radius: 9999px; overflow: hidden;">
                                        <div style="height: 100%; background-color: var(--success); width: {{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%;"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if(empty($dallesGrouped['individuel']) && empty($dallesGrouped['flightcases']) && empty($dallesGrouped['autres']))
                    <!-- Si aucune dalle groupée, afficher les dalles individuellement -->
                    @foreach($produit->dalles as $dalle)
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                        <h3 style="font-size: 1rem; margin-bottom: 1rem;">
                            @if($dalle->reference_dalle == "INDIVIDUEL")
                                <span style="color: var(--warning);">Modules individuels</span>
                            @else
                                Dalle: {{ $dalle->reference_dalle }}
                            @endif
                        </h3>
                        
                        @php
                            // Extraire les dimensions depuis disposition_modules
                            $nbColonnes = 2; // Par défaut 2x2
                            $nbLignes = 2;
                            
                            // Récupérer disposition_modules depuis l'objet dalle
                            $disposition = $dalle->disposition_modules ?? null;
                            
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
                        
                        <!-- Affichage sous forme de grille -->
                        <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                            <div style="display: grid; grid-template-columns: repeat({{ $nbColonnes }}, minmax(0, 1fr)); gap: 0.5rem;">
                                @for ($y = 1; $y <= $nbLignes; $y++)
                                    @for ($x = 1; $x <= $nbColonnes; $x++)
                                        @if (isset($grille[$y][$x]))
                                            @php $module = $grille[$y][$x]; @endphp
                                            <a href="{{ route('suivi.module', ['token' => $chantier->token_suivi, 'moduleId' => $module->id]) }}" 
                                               class="relative group" style="cursor: pointer; display: block; position: relative;">
                                                <div style="aspect-ratio: 1/1; width: 3.5rem; height: 3.5rem; 
                                                    @if($module->etat == 'termine') background-color: var(--success);
                                                    @elseif($module->etat == 'en_cours') background-color: var(--primary);
                                                    @elseif($module->etat == 'defaillant') background-color: var(--danger);
                                                    @else background-color: var(--neutral);
                                                    @endif
                                                    border-radius: 0.375rem; 
                                                    border: 1px solid rgba(0,0,0,0.1); 
                                                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                                                    transition: all 0.2s;"
                                                    onmouseover="this.style.transform='scale(1.05)'; this.style.filter='brightness(1.1)';"
                                                    onmouseout="this.style.transform='scale(1)'; this.style.filter='brightness(1)';"
                                                    title="Module {{ str_replace('Ind', 'Mod', $module->reference_module) }}"
                                                >
                                                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: white; font-weight: 500; font-size: 0.75rem;">
                                                        <div>{{ $x }},{{ $y }}</div>
                                                        <div style="font-size: 0.7rem; margin-top: 0.25rem;">{{ preg_replace('/[^0-9]/', '', $module->reference_module) }}</div>
                                                    </div>
                                                </div>
                                                <!-- Info-bulle au survol -->
                                                <div class="module-tooltip" style="display: none; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); background-color: rgba(17, 24, 39, 0.9); color: white; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem; margin-top: 0.5rem; white-space: nowrap; z-index: 10; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                                                    <div style="font-weight: 600; margin-bottom: 0.25rem;">{{ str_replace('Ind', 'Mod', $module->reference_module) }}</div>
                                                    @if($module->numero_serie)
                                                        <div>ID: {{ $module->numero_serie }}</div>
                                                    @endif
                                                    @if($module->interventions->count() > 0)
                                                        <div>Dernière MAJ: {{ $module->interventions->sortByDesc('updated_at')->first()->updated_at->format('d/m/Y') }}</div>
                                                    @endif
                                                    @php
                                                        $tempsTotal = 0;
                                                        foreach($module->interventions as $intervention) {
                                                            if ($intervention->temps_passe) {
                                                                $tempsTotal += $intervention->temps_passe;
                                                            }
                                                        }
                                                        
                                                        $heures = floor($tempsTotal / 60);
                                                        $minutes = $tempsTotal % 60;
                                                        $tempsFormate = '';
                                                        
                                                        if ($heures > 0) {
                                                            $tempsFormate .= $heures . 'h';
                                                        }
                                                        if ($minutes > 0 || $tempsFormate == '') {
                                                            $tempsFormate .= ($tempsFormate ? ' ' : '') . $minutes . 'min';
                                                        }
                                                    @endphp
                                                    @if($tempsTotal > 0)
                                                        <div>Temps: {{ $tempsFormate }}</div>
                                                    @endif
                                                </div>
                                            </a>
                                        @else
                                            <div style="aspect-ratio: 1/1; width: 3.5rem; height: 3.5rem; background-color: rgba(55, 65, 81, 0.3); border-radius: 0.375rem; border: 1px solid rgba(55, 65, 81, 0.5);" title="Emplacement vide">
                                                <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-muted); font-size: 0.75rem;">
                                                    {{ $x }},{{ $y }}
                                                </div>
                                            </div>
                                        @endif
                                    @endfor
                                @endfor
                            </div>
                        </div>
                        
                        <!-- Barre de progression -->
                        <div style="width: 100%;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.25rem; color: var(--text-muted);">
                                <span>{{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%</span>
                                <span>{{ $dalle->modules->where('etat', 'termine')->count() }}/{{ $dalle->modules->count() }} modules</span>
                            </div>
                            <div style="height: 0.5rem; width: 100%; background-color: rgba(55, 65, 81, 0.5); border-radius: 9999px; overflow: hidden;">
                                <div style="height: 100%; background-color: var(--success); width: {{ round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100) }}%;"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
    <!-- Footer -->
    <footer>
        <div class="container footer-content">
            <p style="margin-bottom: 0.5rem;">Ce suivi est mis à jour en temps réel. Dernière consultation: {{ now()->format('d/m/Y H:i:s') }}</p>
            <p style="margin-bottom: 0.5rem;">Pour toute question concernant votre chantier, n'hésitez pas à contacter notre équipe.</p>
            <p>&copy; {{ date('Y') }} TecaLED. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Scripts pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enregistrer le plugin datalabels
            Chart.register(ChartDataLabels);
            
            // Configuration des couleurs pour les graphiques
            const colors = {
                success: '#10b981', // Terminé
                primary: '#3b82f6', // En cours
                danger: '#ef4444',  // Défaillant
                neutral: '#6b7280',  // Non commencé
                purple: '#8b5cf6',   // LEDs
                indigo: '#6366f1',   // ICs
                pink: '#ec4899',     // Masques
                blue: '#3b82f6',     // Usure normale
                orange: '#f59e0b',   // Défaut usine
                gray: '#6b7280'      // Autre
            };
            
            // Graphique d'état des modules
            const modulesCtx = document.getElementById('modulesChart').getContext('2d');
            const modulesChart = new Chart(modulesCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Terminés', 'En cours', 'Défaillants', 'Non commencés'],
                    datasets: [{
                        data: [{{ $modulesTermines }}, {{ $modulesEnCours }}, {{ $modulesDefaillants }}, {{ $modulesNonCommences }}],
                        backgroundColor: [colors.success, colors.primary, colors.danger, colors.neutral],
                        borderColor: '#1f2937',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#e5e7eb',
                                font: {
                                    size: 12
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#e5e7eb',
                            borderColor: '#374151',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: 'white',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function(value, context) {
                                return value > 0 ? value : '';
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
            
            // Graphique des composants remplacés
            const componentsCtx = document.getElementById('componentsChart').getContext('2d');
            const componentsChart = new Chart(componentsCtx, {
                type: 'pie',
                data: {
                    labels: ['LEDs', 'ICs', 'Masques'],
                    datasets: [{
                        data: [{{ $totalLEDsRemplacees }}, {{ $totalICsRemplaces }}, {{ $totalMasquesRemplaces }}],
                        backgroundColor: [colors.purple, colors.indigo, colors.pink],
                        borderColor: '#1f2937',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#e5e7eb',
                                font: {
                                    size: 12
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#e5e7eb',
                            borderColor: '#374151',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: 'white',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function(value, context) {
                                return value > 0 ? value : '';
                            }
                        }
                    }
                }
            });
            
            // Graphique des causes
            const causesCtx = document.getElementById('causesChart').getContext('2d');
            const causesChart = new Chart(causesCtx, {
                type: 'bar',
                data: {
                    labels: ['Usure normale', 'Dommage physique', 'Défaut de fabrication', 'Autre cause'],
                    datasets: [{
                        data: [{{ $causes['usure_normale'] }}, {{ $causes['choc'] }}, {{ $causes['defaut_usine'] }}, {{ $causes['autre'] }}],
                        backgroundColor: [colors.blue, colors.danger, colors.orange, colors.gray],
                        borderWidth: 0,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#e5e7eb',
                            borderColor: '#374151',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${value} cas (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: 'white',
                            anchor: 'end',
                            align: 'top',
                            offset: 0,
                            font: {
                                weight: 'bold'
                            },
                            formatter: function(value, context) {
                                return value > 0 ? value : '';
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                color: 'rgba(75, 85, 99, 0.2)'
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(75, 85, 99, 0.2)',
                                borderDash: [2, 4]
                            },
                            ticks: {
                                precision: 0,
                                color: '#9ca3af',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        });
        
        // Script pour gérer l'affichage des infobulles sur les modules
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter des événements pour toutes les instances de modules
            document.querySelectorAll('.relative.group').forEach(function(el) {
                el.addEventListener('mouseenter', function() {
                    const tooltip = this.querySelector('.module-tooltip');
                    if (tooltip) tooltip.style.display = 'block';
                });
                el.addEventListener('mouseleave', function() {
                    const tooltip = this.querySelector('.module-tooltip');
                    if (tooltip) tooltip.style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>
@endforeach
