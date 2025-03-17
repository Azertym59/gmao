<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module {{ $module->reference_module }} - Détails</title>
    
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        /* Header Section - Style rapport professionnel */
        .header {
            background: linear-gradient(to right, rgba(17, 24, 39, 0.95), rgba(31, 41, 55, 0.95));
            padding: 0;
            border-bottom: 1px solid var(--border);
            position: relative;
            width: 100%;
            top: 0;
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
        
        .header-buttons {
            display: flex;
            gap: 0.75rem;
        }
        
        .icon-button {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.375rem;
            padding: 0.625rem;
            color: var(--text-light);
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .icon-button:hover {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.1));
            transform: translateY(-1px);
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
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
        
        /* Timeline */
        .timeline {
            margin: 1.5rem 0;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 2rem;
            padding-bottom: 2rem;
        }
        
        .timeline-item:last-child {
            padding-bottom: 0;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 1px;
            height: 100%;
            background-color: var(--border);
        }
        
        .timeline-item:last-child::before {
            height: 1.5rem;
        }
        
        .timeline-dot {
            position: absolute;
            left: -0.4375rem;
            top: 0;
            width: 0.875rem;
            height: 0.875rem;
            border-radius: 50%;
            background-color: var(--primary);
            border: 2px solid var(--bg-card);
        }
        
        .timeline-content {
            background-color: rgba(31, 41, 55, 0.7);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1.25rem;
        }
        
        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .timeline-title {
            font-size: 1rem;
            font-weight: 600;
        }
        
        .timeline-date {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.25rem;
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
            height: 220px;
            width: 100%;
        }
        
        /* Data grid */
        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .data-item {
            background-color: rgba(31, 41, 55, 0.5);
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }
        
        .data-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .data-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        /* Details section */
        .details-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }
        
        .details-header {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .details-header svg {
            width: 1rem;
            height: 1rem;
            color: var(--primary);
        }
        
        .details-content {
            font-size: 0.875rem;
            color: var(--text-light);
            line-height: 1.5;
        }
        
        /* Cause badge */
        .cause-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
        }
        
        .cause-usure_normale {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--primary-light);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        
        .cause-choc {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .cause-defaut_usine {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        
        .cause-autre {
            background-color: rgba(107, 114, 128, 0.1);
            color: var(--neutral);
            border: 1px solid rgba(107, 114, 128, 0.2);
        }
        
        /* Notes */
        .notes-box {
            background-color: rgba(31, 41, 55, 0.3);
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            padding: 1rem;
            margin-top: 1rem;
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
            
            .data-grid {
                grid-template-columns: 1fr 1fr;
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
            
            .icon-button {
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
            
            .info-item, .data-item {
                background-color: white;
                border: 1px solid #e5e7eb;
            }
            
            .timeline-content {
                background-color: white;
                border: 1px solid #e5e7eb;
            }
            
            .status-badge, .cause-badge {
                border: 1px solid #e5e7eb;
                color: black;
            }
            
            .notes-box {
                background-color: white;
                border: 1px solid #e5e7eb;
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
                <div style="text-align: right; font-size: 0.875rem; color: rgba(255, 255, 255, 0.7);">
                    Rapport généré le {{ now()->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>
        <div class="header-main">
            <div class="container header-container">
                <div class="header-logo">
                    <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair%20new.png" alt="TecaLED" style="height: 8rem;" />
                    <div class="header-divider"></div>
                    <div class="header-title">
                        <h1>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            Fiche technique du module
                        </h1>
                        <span>{{ $module->dalle->reference_dalle }} - {{ $module->dalle->produit->marque }} {{ $module->dalle->produit->modele }}</span>
                    </div>
                </div>
                <div class="header-info">
                    <div class="ref-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        Module: {{ str_replace('Ind', 'Mod', $module->reference_module) }}
                    </div>
                    <div class="header-buttons">
                        <a href="{{ route('suivi.chantier', $token) }}" class="icon-button" title="Retour au chantier">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                            </svg>
                        </a>
                        <button onclick="window.print()" class="icon-button" title="Imprimer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="main" style="margin-top: 2rem;">
        <div class="container">
            <!-- Caractéristiques du module -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Caractéristiques du module
                    </h2>
                    <div class="status-badge status-{{ $module->etat }}">
                        <span class="status-indicator indicator-{{ $module->etat }}"></span>
                        @if($module->etat == 'non_commence')
                            Non commencé
                        @elseif($module->etat == 'en_cours')
                            En cours
                        @elseif($module->etat == 'termine')
                            Terminé
                        @elseif($module->etat == 'defaillant')
                            Défaillant
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Référence</div>
                            <div class="info-value">{{ $module->reference_module }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">ID Usine / Numéro de série</div>
                            <div class="info-value">{{ $module->numero_serie ?: 'Non renseigné' }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Disposition</div>
                            <div class="info-value">{{ $module->disposition ?: 'Standard' }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Dimensions</div>
                            <div class="info-value">{{ $module->largeur }} × {{ $module->hauteur }} mm</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Résolution</div>
                            <div class="info-value">{{ $module->nb_pixels_largeur }} × {{ $module->nb_pixels_hauteur }} px</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Dalle / Produit</div>
                            <div class="info-value">{{ $module->dalle->reference_dalle }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $module->dalle->produit->marque }} {{ $module->dalle->produit->modele }}</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($module->interventions->count() > 0)
            <!-- Statistiques des problèmes -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Statistiques des problèmes
                    </h2>
                </div>
                <div class="card-body">
                    <div class="charts-grid">
                        <div>
                            <h3 style="font-size: 0.875rem; margin-bottom: 0.75rem; color: var(--text-muted);">Composants défectueux</h3>
                            <div class="chart-container">
                                <canvas id="componentsChart"></canvas>
                            </div>
                        </div>
                        <div>
                            <h3 style="font-size: 0.875rem; margin-bottom: 0.75rem; color: var(--text-muted);">Évolution des problèmes</h3>
                            <div class="chart-container">
                                <canvas id="timelineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Historique des interventions -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Historique des interventions
                    </h2>
                    <span style="font-size: 0.875rem; color: var(--text-muted);">{{ $module->interventions->count() }} intervention(s)</span>
                </div>
                <div class="card-body">
                    @if($module->interventions->count() > 0)
                        <div class="timeline">
                            @foreach($module->interventions->sortByDesc('created_at') as $intervention)
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <div class="timeline-title">Intervention #{{ $intervention->id }}</div>
                                        <div class="timeline-date">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $intervention->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                        <div style="font-size: 0.875rem;">
                                            <span style="color: var(--text-muted);">Technicien:</span> 
                                            <span style="font-weight: 500;">{{ $intervention->technicien ? $intervention->technicien->name : 'Non assigné' }}</span>
                                        </div>
                                        <div class="status-badge {{ $intervention->is_completed ? 'status-termine' : 'status-en_cours' }}" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                            <span class="status-indicator {{ $intervention->is_completed ? 'indicator-termine' : 'indicator-en_cours' }}"></span>
                                            {{ $intervention->is_completed ? 'Terminée' : 'En cours' }}
                                        </div>
                                    </div>
                                    
                                    <!-- Diagnostic -->
                                    <div>
                                        <div class="details-header">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            Diagnostic
                                        </div>
                                        
                                        <div class="data-grid">
                                            <div class="data-item">
                                                <div class="data-label">LEDs défectueuses</div>
                                                <div class="data-value">{{ $intervention->diagnostic->nb_leds_hs }}</div>
                                            </div>
                                            
                                            <div class="data-item">
                                                <div class="data-label">ICs défectueux</div>
                                                <div class="data-value">{{ $intervention->diagnostic->nb_ic_hs }}</div>
                                            </div>
                                            
                                            <div class="data-item">
                                                <div class="data-label">Masques défectueux</div>
                                                <div class="data-value">{{ $intervention->diagnostic->nb_masques_hs }}</div>
                                            </div>
                                            
                                            <div class="data-item">
                                                <div class="data-label">Fake PCB posé</div>
                                                <div class="data-value" style="font-size: 1rem;">{{ $intervention->diagnostic->pose_fake_pcb == 1 ? 'Oui' : 'Non' }}</div>
                                            </div>
                                        </div>
                                        
                                        @if($intervention->diagnostic->cause)
                                        <div style="margin-top: 1rem;">
                                            <div style="margin-bottom: 0.5rem; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.025em;">Cause identifiée</div>
                                            <div class="cause-badge cause-{{ $intervention->diagnostic->cause }}">
                                                @if($intervention->diagnostic->cause == 'usure_normale')
                                                    Usure normale
                                                @elseif($intervention->diagnostic->cause == 'choc')
                                                    Dommage physique
                                                @elseif($intervention->diagnostic->cause == 'defaut_usine')
                                                    Défaut de fabrication
                                                @else
                                                    Autre cause
                                                @endif
                                            </div>
                                            
                                            <div class="notes-box">
                                                @if($intervention->diagnostic->cause == 'usure_normale')
                                                    Ce phénomène correspond à une usure normale des composants électroniques qui survient avec le temps et l'utilisation. Il s'agit d'un processus naturel qui ne présente aucun caractère anormal ou inquiétant.
                                                @elseif($intervention->diagnostic->cause == 'choc')
                                                    Les dégâts constatés sont dus à un impact physique ou à une contrainte mécanique. Pour éviter ce type de problème à l'avenir, nous recommandons une manipulation plus précautionneuse et la mise en place de protections adaptées.
                                                @elseif($intervention->diagnostic->cause == 'defaut_usine')
                                                    Ce défaut est lié à un problème survenu lors de la fabrication initiale du module. Il s'agit d'une anomalie de production qui a été identifiée et corrigée lors de notre intervention.
                                                @else
                                                    La cause exacte ne correspond pas aux catégories standards. Veuillez consulter les remarques complémentaires pour plus de détails sur l'origine du problème.
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($intervention->diagnostic->remarques)
                                        <div style="margin-top: 1rem;">
                                            <div style="margin-bottom: 0.5rem; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.025em;">Remarques</div>
                                            <div class="notes-box">
                                                {{ $intervention->diagnostic->remarques }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Réparation -->
                                    @if($intervention->reparation)
                                    <div class="details-section">
                                        <div class="details-header">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Réparation
                                        </div>
                                        
                                        <div style="margin-top: 1rem;">
                                            <div style="margin-bottom: 0.5rem; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.025em;">Actions effectuées</div>
                                            <div class="notes-box">
                                                {{ $intervention->reparation->actions_effectuees }}
                                            </div>
                                        </div>
                                        
                                        @if($intervention->reparation->pieces_remplacees)
                                        <div style="margin-top: 1rem;">
                                            <div style="margin-bottom: 0.5rem; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.025em;">Pièces remplacées</div>
                                            <div class="notes-box">
                                                {{ $intervention->reparation->pieces_remplacees }}
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($intervention->reparation->remarques)
                                        <div style="margin-top: 1rem;">
                                            <div style="margin-bottom: 0.5rem; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.025em;">Remarques</div>
                                            <div class="notes-box">
                                                {{ $intervention->reparation->remarques }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                    
                                    <!-- Temps d'intervention -->
                                    <div class="details-section">
                                        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 0.5rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div style="font-size: 0.875rem; color: var(--text-muted);">
                                                Temps d'intervention: 
                                                <span style="font-weight: 500;">
                                                    @php
                                                        $heures = floor($intervention->temps_total / 3600);
                                                        $minutes = floor(($intervention->temps_total % 3600) / 60);
                                                        $secondes = $intervention->temps_total % 60;
                                                        echo sprintf('%dh %02dm %02ds', $heures, $minutes, $secondes);
                                                    @endphp
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem 0;">
                            <p style="color: var(--text-muted);">Aucune intervention n'a encore été effectuée sur ce module.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container footer-content">
            <p style="margin-bottom: 0.5rem;">Ce suivi est mis à jour en temps réel. Dernière consultation: {{ now()->format('d/m/Y H:i:s') }}</p>
            <p style="margin-bottom: 0.5rem;">Pour toute question concernant votre module, n'hésitez pas à contacter notre équipe.</p>
            <p>&copy; {{ date('Y') }} TecaLED. Tous droits réservés.</p>
        </div>
    </footer>

    @if($module->interventions->count() > 0)
    <!-- Scripts pour les graphiques -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
            
            // Préparation des données pour le graphique des composants
            let totalLEDs = 0;
            let totalICs = 0;
            let totalMasques = 0;
            
            // Préparation des données pour le graphique timeline
            let dates = [];
            let ledsCounts = [];
            let icsCounts = [];
            let masquesCounts = [];
            
            @foreach($module->interventions->sortBy('created_at') as $intervention)
                @if($intervention->diagnostic)
                    totalLEDs += {{ $intervention->diagnostic->nb_leds_hs }};
                    totalICs += {{ $intervention->diagnostic->nb_ic_hs }};
                    totalMasques += {{ $intervention->diagnostic->nb_masques_hs }};
                    
                    dates.push('{{ $intervention->created_at->format('d/m') }}');
                    ledsCounts.push({{ $intervention->diagnostic->nb_leds_hs }});
                    icsCounts.push({{ $intervention->diagnostic->nb_ic_hs }});
                    masquesCounts.push({{ $intervention->diagnostic->nb_masques_hs }});
                @endif
            @endforeach
            
            // Graphique des composants
            const componentsCtx = document.getElementById('componentsChart').getContext('2d');
            const componentsChart = new Chart(componentsCtx, {
                type: 'pie',
                data: {
                    labels: ['LEDs', 'ICs', 'Masques'],
                    datasets: [{
                        data: [totalLEDs, totalICs, totalMasques],
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
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
            
            // Graphique timeline des interventions
            const timelineCtx = document.getElementById('timelineChart').getContext('2d');
            const timelineChart = new Chart(timelineCtx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: 'LEDs défectueuses',
                            data: ledsCounts,
                            borderColor: colors.purple,
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            tension: 0.2,
                            fill: true
                        },
                        {
                            label: 'ICs défectueux',
                            data: icsCounts,
                            borderColor: colors.indigo,
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            tension: 0.2,
                            fill: true
                        },
                        {
                            label: 'Masques défectueux',
                            data: masquesCounts,
                            borderColor: colors.pink,
                            backgroundColor: 'rgba(236, 72, 153, 0.1)',
                            tension: 0.2,
                            fill: true
                        }
                    ]
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
                            borderWidth: 1
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
    </script>
    @endif
</body>
</html>