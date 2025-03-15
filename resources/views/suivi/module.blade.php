<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du module - {{ $module->reference_module }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        :root {
            color-scheme: dark;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #121212;
            color: #F3F4F6;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.5rem;
        }
        
        .glassmorphism {
            background: rgba(30, 30, 30, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .header-logo {
            height: 2.5rem;
        }
        
        .card {
            background: rgba(30, 30, 30, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background-color: rgba(26, 32, 44, 0.4);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1rem;
        }
        
        @media (min-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            
            .grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-non_commence {
            background-color: rgba(75, 85, 99, 0.2);
            color: #9CA3AF;
            border: 1px solid rgba(75, 85, 99, 0.3);
        }
        
        .status-en_cours {
            background-color: rgba(59, 130, 246, 0.2);
            color: #60A5FA;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        .status-termine {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34D399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        
        .status-defaillant {
            background-color: rgba(239, 68, 68, 0.2);
            color: #F87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .stat-box {
            background-color: rgba(55, 65, 81, 0.3);
            border-radius: 0.5rem;
            padding: 1rem;
            text-align: center;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
            margin-top: 1.5rem;
        }
        
        .timeline-line {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 8px;
            width: 2px;
            background-color: rgba(75, 85, 99, 0.5);
            z-index: 0;
        }
        
        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }
        
        .timeline-dot {
            position: absolute;
            top: 0;
            left: -2rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: #3B82F6;
            border: 3px solid rgba(30, 30, 30, 0.8);
            z-index: 1;
        }
        
        .timeline-content {
            background: rgba(55, 65, 81, 0.2);
            border: 1px solid rgba(75, 85, 99, 0.3);
            border-radius: 0.5rem;
            padding: 1rem;
        }
        
        .timeline-date {
            display: block;
            font-size: 0.75rem;
            color: #9CA3AF;
            margin-bottom: 0.5rem;
        }
        
        .flex {
            display: flex;
        }
        
        .flex-col {
            flex-direction: column;
        }
        
        .justify-between {
            justify-content: space-between;
        }
        
        .items-center {
            align-items: center;
        }
        
        .mt-2 {
            margin-top: 0.5rem;
        }
        
        .mt-4 {
            margin-top: 1rem;
        }
        
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        
        .mb-4 {
            margin-bottom: 1rem;
        }
        
        .text-sm {
            font-size: 0.875rem;
        }
        
        .text-lg {
            font-size: 1.125rem;
        }
        
        .text-xl {
            font-size: 1.25rem;
        }
        
        .text-2xl {
            font-size: 1.5rem;
        }
        
        .font-medium {
            font-weight: 500;
        }
        
        .font-semibold {
            font-weight: 600;
        }
        
        .text-gray-400 {
            color: #9CA3AF;
        }
        
        .text-red-400 {
            color: #F87171;
        }
        
        .text-yellow-400 {
            color: #FBBF24;
        }
        
        .text-green-400 {
            color: #34D399;
        }
        
        .border-t {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .pt-4 {
            padding-top: 1rem;
        }
        
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .py-8 {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .cause-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        
        .cause-usure_normale {
            background-color: rgba(59, 130, 246, 0.2);
            color: #60A5FA;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        .cause-choc {
            background-color: rgba(239, 68, 68, 0.2);
            color: #F87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .cause-defaut_usine {
            background-color: rgba(245, 158, 11, 0.2);
            color: #FBBF24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .cause-autre {
            background-color: rgba(75, 85, 99, 0.2);
            color: #9CA3AF;
            border: 1px solid rgba(75, 85, 99, 0.3);
        }

        .footer {
            text-align: center;
            padding: 1.5rem;
            color: #9CA3AF;
            font-size: 0.875rem;
            margin-top: 2rem;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            color: #60A5FA;
            text-decoration: none;
            margin-bottom: 1rem;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header glassmorphism">
        <div>
            <img src="{{ asset('images/logo-repair.png') }}" alt="TecaLED" class="header-logo">
        </div>
        <div>
            <h1 class="text-2xl font-semibold mb-2">Détails du module</h1>
            <p class="text-gray-400">{{ $module->reference_module }}</p>
        </div>
    </div>

    <div class="container">
        <a href="{{ route('suivi.chantier', $token) }}" class="back-link">
            <span>&larr;</span>
            <span class="ml-2">Retour au suivi du chantier</span>
        </a>
        
        <div class="card">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Informations du module</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2">
                    <div>
                        <p class="text-gray-400">Référence</p>
                        <p class="font-medium">{{ $module->reference_module }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Numéro de série / ID Usine</p>
                        <p class="font-medium">{{ $module->numero_serie ?: 'Non renseigné' }}</p>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400">État actuel</p>
                        <p>
                            <span class="status-badge status-{{ $module->etat }}">
                                @if($module->etat == 'non_commence')
                                    Non commencé
                                @elseif($module->etat == 'en_cours')
                                    En cours
                                @elseif($module->etat == 'termine')
                                    Terminé
                                @elseif($module->etat == 'defaillant')
                                    Défaillant
                                @endif
                            </span>
                        </p>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400">Dimensions</p>
                        <p class="font-medium">{{ $module->largeur }}×{{ $module->hauteur }} mm</p>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400">Résolution</p>
                        <p class="font-medium">{{ $module->nb_pixels_largeur }}×{{ $module->nb_pixels_hauteur }} pixels</p>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400">Disposition</p>
                        <p class="font-medium">{{ $module->disposition ?: 'Standard' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Historique des interventions</h2>
            </div>
            <div class="card-body">
                @if($module->interventions->count() > 0)
                    <div class="timeline">
                        <div class="timeline-line"></div>
                        
                        @foreach($module->interventions->sortByDesc('created_at') as $intervention)
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <span class="timeline-date">{{ $intervention->created_at->format('d/m/Y H:i') }}</span>
                                    <h3 class="font-medium">Intervention #{{ $intervention->id }}</h3>
                                    
                                    <div class="mt-4">
                                        <h4 class="font-medium text-lg">Diagnostic</h4>
                                        <div class="grid grid-cols-2 gap-4 mt-2">
                                            <div>
                                                <p class="text-gray-400 text-sm">LEDs défectueuses</p>
                                                <p class="font-medium">{{ $intervention->diagnostic->nb_leds_hs }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-400 text-sm">ICs défectueux</p>
                                                <p class="font-medium">{{ $intervention->diagnostic->nb_ic_hs }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-400 text-sm">Masques défectueux</p>
                                                <p class="font-medium">{{ $intervention->diagnostic->nb_masques_hs }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-400 text-sm">Fake PCB posé</p>
                                                <p class="font-medium">{{ $intervention->diagnostic->pose_fake_pcb ? 'Oui' : 'Non' }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <p class="text-gray-400 text-sm">Cause du problème</p>
                                            @if($intervention->diagnostic->cause)
                                                <span class="cause-badge cause-{{ $intervention->diagnostic->cause }}">
                                                    @if($intervention->diagnostic->cause == 'usure_normale')
                                                        Usure normale
                                                    @elseif($intervention->diagnostic->cause == 'choc')
                                                        Dommage physique
                                                    @elseif($intervention->diagnostic->cause == 'defaut_usine')
                                                        Défaut de fabrication
                                                    @else
                                                        Autre cause
                                                    @endif
                                                </span>
                                            @else
                                                <p class="font-medium">Non spécifiée</p>
                                            @endif
                                        </div>
                                        
                                        @if($intervention->diagnostic->remarques)
                                            <div class="mt-4 pt-2 border-t border-gray-700">
                                                <p class="text-gray-400 text-sm">Remarques</p>
                                                <p class="text-sm">{{ $intervention->diagnostic->remarques }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($intervention->reparation)
                                        <div class="mt-4 pt-4 border-t border-gray-700">
                                            <h4 class="font-medium text-lg">Réparation</h4>
                                            <div class="mt-2">
                                                <p class="text-gray-400 text-sm">Actions effectuées</p>
                                                <p class="text-sm">{{ $intervention->reparation->actions_effectuees }}</p>
                                            </div>
                                            
                                            @if($intervention->reparation->pieces_remplacees)
                                                <div class="mt-2">
                                                    <p class="text-gray-400 text-sm">Pièces remplacées</p>
                                                    <p class="text-sm">{{ $intervention->reparation->pieces_remplacees }}</p>
                                                </div>
                                            @endif
                                            
                                            @if($intervention->reparation->remarques)
                                                <div class="mt-2">
                                                    <p class="text-gray-400 text-sm">Remarques</p>
                                                    <p class="text-sm">{{ $intervention->reparation->remarques }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="mt-4 pt-4 border-t border-gray-700">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-gray-400 text-sm">Technicien</p>
                                                <p class="font-medium">{{ $intervention->technicien ? $intervention->technicien->name : 'Non assigné' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-400 text-sm">Temps d'intervention</p>
                                                <p class="font-medium">
                                                    @php
                                                        $heures = floor($intervention->temps_total / 3600);
                                                        $minutes = floor(($intervention->temps_total % 3600) / 60);
                                                        $secondes = $intervention->temps_total % 60;
                                                        echo sprintf('%dh %02dm %02ds', $heures, $minutes, $secondes);
                                                    @endphp
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-gray-400 text-sm">Statut</p>
                                                <p class="font-medium">
                                                    @if($intervention->is_completed)
                                                        <span class="text-green-400">Terminée</span>
                                                    @else
                                                        <span class="text-yellow-400">En cours</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400">Aucune intervention n'a été effectuée sur ce module.</p>
                @endif
            </div>
        </div>
        
        <div class="footer glassmorphism">
            <p>Ce suivi est mis à jour en temps réel. Dernière consultation: {{ now()->format('d/m/Y H:i:s') }}</p>
            <p class="mt-2">Pour toute question sur votre chantier, veuillez nous contacter.</p>
            <p class="mt-4">&copy; {{ date('Y') }} TecaLED - GMAO. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>