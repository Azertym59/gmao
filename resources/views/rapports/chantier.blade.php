<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport Chantier - {{ $chantier->reference }}</title>
    <style>
        /* Styles de base */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        
        /* En-tête et pied de page moderne */
        .header {
            padding: 20px;
            position: relative;
            background: #2563eb; /* Couleur principale bleue */
            color: white;
            margin-bottom: 30px;
            background-image: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            border-radius: 0 0 30px 0;
        }
        
        .header::after {
            content: "";
            position: absolute;
            bottom: -15px;
            right: 60px;
            width: 30px;
            height: 30px;
            background: #1e40af;
            transform: rotate(45deg);
            z-index: -1;
        }
        
        .logo-container {
            position: absolute;
            top: 20px;
            right: 30px;
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            width: 80px;
            height: auto;
        }
        
        .header-content {
            padding-right: 120px;
        }
        
        .report-date {
            display: inline-block;
            margin-top: 10px;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 10pt;
        }
        
        .footer {
            text-align: center;
            font-size: 9pt;
            margin-top: 30px;
            padding: 15px;
            color: #666;
            background: #f8fafc;
            border-top: 3px solid #2563eb;
        }
        
        /* Titres modernes */
        h1 {
            font-size: 22pt;
            margin: 0 0 5px 0;
            color: white;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        h2 {
            font-size: 16pt;
            margin-top: 30px;
            margin-bottom: 15px;
            color: #2563eb;
            position: relative;
            padding-bottom: 8px;
        }
        
        h2::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: #2563eb;
            border-radius: 2px;
        }
        
        h3 {
            font-size: 14pt;
            color: #1e40af;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        
        h4 {
            font-size: 12pt;
            color: #3b82f6;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        
        /* Message de remerciement */
        .thank-you {
            background-color: #f0f9ff;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
            color: #1e40af;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        /* Tableau de données moderne */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        table.striped tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        table thead tr {
            background-color: #2563eb;
            color: white;
        }
        
        table th {
            text-align: left;
            padding: 12px;
            font-weight: bold;
            border: none;
        }
        
        table td {
            padding: 12px;
            border: none;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        
        table.info-table {
            background: white;
        }
        
        .info-table td:first-child {
            width: 180px;
            font-weight: 700;
            color: #1e40af;
            border-right: 1px solid #e5e7eb;
            background-color: #f0f9ff;
        }
        
        /* Cartes de statistiques */
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 30px 0;
        }
        
        .stat-card {
            width: 23%;
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background-color: #2563eb;
        }
        
        .stat-value {
            font-size: 28pt;
            font-weight: bold;
            color: #2563eb;
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 10pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        
        /* Carte avec puce de couleur */
        .card-chip-success::before { background-color: #10b981; }
        .card-chip-warning::before { background-color: #f59e0b; }
        .card-chip-danger::before { background-color: #ef4444; }
        .card-chip-info::before { background-color: #3b82f6; }
        
        /* Barres de progression modernes */
        .progress-container {
            width: 100%;
            background-color: #e5e7eb;
            border-radius: 10px;
            height: 10px;
            margin: 10px 0;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 10px;
            border-radius: 10px;
            transition: width 0.5s ease;
            background-image: linear-gradient(to right, #2563eb, #3b82f6);
        }
        
        /* État des modules avec couleurs */
        .progress-success {
            background-image: linear-gradient(to right, #059669, #10b981);
        }
        
        .progress-warning {
            background-image: linear-gradient(to right, #d97706, #f59e0b);
        }
        
        .progress-danger {
            background-image: linear-gradient(to right, #dc2626, #ef4444);
        }
        
        /* Badges modernes */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-danger {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        .badge-neutral {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        
        /* Cartes modules */
        .module-box {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #2563eb;
        }
        
        .module-box.module-success { border-left-color: #10b981; }
        .module-box.module-warning { border-left-color: #f59e0b; }
        .module-box.module-danger { border-left-color: #ef4444; }
        .module-box.module-neutral { border-left-color: #6b7280; }
        
        .module-header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .module-title {
            font-weight: bold;
            color: #1e40af;
            font-size: 14pt;
        }
        
        .module-detail {
            margin-bottom: 8px;
            display: flex;
        }
        
        .module-label {
            font-weight: bold;
            color: #64748b;
            font-size: 10pt;
            width: 120px;
            text-transform: uppercase;
        }
        
        .module-intervention {
            margin: 15px 0;
            padding: 15px;
            border-left: 3px solid #e5e7eb;
            background-color: #f8fafc;
            border-radius: 0 8px 8px 0;
        }
        
        /* Graphiques modernes */
        .chart-container {
            margin: 25px 0;
            padding: 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .chart-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: #1e40af;
            font-size: 14pt;
            position: relative;
            padding-bottom: 10px;
        }
        
        .chart-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #2563eb;
            border-radius: 2px;
        }
        
        .bar-container {
            height: 30px;
            margin: 15px 0;
            display: flex;
            align-items: center;
        }
        
        .bar-label {
            width: 130px;
            font-weight: 600;
            color: #334155;
            font-size: 10pt;
        }
        
        .bar-wrapper {
            flex: 1;
            background-color: #f1f5f9;
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
            margin: 0 10px;
        }
        
        .bar {
            height: 20px;
            border-radius: 10px;
        }
        
        .bar-value {
            width: 40px;
            text-align: right;
            font-weight: bold;
            color: #1e40af;
        }
        
        /* Couleurs pour les barres */
        .bar-usure {
            background-image: linear-gradient(to right, #2563eb, #3b82f6);
        }
        
        .bar-choc {
            background-image: linear-gradient(to right, #dc2626, #ef4444);
        }
        
        .bar-defaut {
            background-image: linear-gradient(to right, #d97706, #f59e0b);
        }
        
        .bar-autre {
            background-image: linear-gradient(to right, #4b5563, #6b7280);
        }
        
        .bar-termine {
            background-image: linear-gradient(to right, #059669, #10b981);
        }
        
        .bar-encours {
            background-image: linear-gradient(to right, #2563eb, #3b82f6);
        }
        
        .bar-defaillant {
            background-image: linear-gradient(to right, #dc2626, #ef4444);
        }
        
        .bar-noncommence {
            background-image: linear-gradient(to right, #4b5563, #6b7280);
        }
        
        .bar-led {
            background-image: linear-gradient(to right, #7c3aed, #8b5cf6);
        }
        
        .bar-ic {
            background-image: linear-gradient(to right, #4f46e5, #6366f1);
        }
        
        .bar-masque {
            background-image: linear-gradient(to right, #db2777, #ec4899);
        }
        
        /* Disposition en grille */
        .grid-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 25px 0;
        }
        
        .grid-item-half {
            width: calc(50% - 10px);
        }
        
        /* Utilitaires */
        .page-break {
            page-break-before: always;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-muted {
            color: #64748b;
        }
        
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }
        
        /* Section avec fond */
        .section-highlight {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        /* Fiche résumé */
        .summary-card {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        
        .summary-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .summary-title {
            font-size: 18pt;
            color: #1e40af;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .summary-subtitle {
            color: #64748b;
        }
        
        .big-percentage {
            font-size: 48pt;
            font-weight: 800;
            color: #2563eb;
            line-height: 1;
            margin: 20px 0;
        }
        
        /* Barre de données */
        .data-bar-container {
            display: flex;
            margin: 20px 0;
            padding: 0;
            list-style: none;
            height: 30px;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .data-bar-segment {
            height: 30px;
        }
        
        .data-bar-segment-termine { background-color: #10b981; }
        .data-bar-segment-encours { background-color: #3b82f6; }
        .data-bar-segment-defaillant { background-color: #ef4444; }
        .data-bar-segment-noncommence { background-color: #6b7280; }
        
        .data-bar-legend {
            display: flex;
            justify-content: center;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin: 0 10px;
            font-size: 9pt;
        }
        
        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <!-- En-tête moderne -->
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('images/logo-repair.png') }}" alt="TecaLED" class="logo">
        </div>
        <div class="header-content">
            <h1>Rapport Chantier</h1>
            <div style="font-size: 14pt; margin: 5px 0;">{{ $chantier->reference }} | {{ $chantier->nom }}</div>
            <div class="report-date">Édité le {{ now()->format('d/m/Y') }}</div>
        </div>
    </div>
    
    <!-- Message de remerciement -->
    <div class="thank-you">
        Cher/Chère {{ $chantier->client->nom }} {{ $chantier->client->prenom }}, nous vous remercions pour votre confiance. 
        Voici le rapport détaillé de votre chantier.
    </div>
    
    <!-- Résumé du chantier -->
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Avancement du Chantier</div>
            <div class="summary-subtitle">{{ $chantier->client->societe ? $chantier->client->societe : $chantier->client->nom_complet }}</div>
        </div>
        
        <div class="grid-container">
            <div class="grid-item-half text-center">
                <div class="text-muted">PROGRESSION GLOBALE</div>
                <div class="big-percentage">{{ $pourcentageTermines }}%</div>
                <div class="progress-container" style="height: 15px; margin: 15px 20px;">
                    <div class="progress-bar {{ $pourcentageTermines >= 75 ? 'progress-success' : ($pourcentageTermines >= 25 ? 'progress-warning' : 'progress-danger') }}" style="width: {{ $pourcentageTermines }}%; height: 15px;"></div>
                </div>
                <div class="text-muted">{{ $modulesTermines }} modules terminés sur {{ $totalModules }}</div>
            </div>
            
            <div class="grid-item-half">
                <div class="text-muted text-center" style="margin-bottom: 10px;">RÉPARTITION DES MODULES</div>
                
                <!-- Barre de données empilée -->
                <div class="data-bar-container">
                    @if($totalModules > 0)
                        <div class="data-bar-segment data-bar-segment-termine" style="width: {{ ($modulesTermines / $totalModules) * 100 }}%"></div>
                        <div class="data-bar-segment data-bar-segment-encours" style="width: {{ ($modulesEnCours / $totalModules) * 100 }}%"></div>
                        <div class="data-bar-segment data-bar-segment-defaillant" style="width: {{ ($modulesDefaillants / $totalModules) * 100 }}%"></div>
                        <div class="data-bar-segment data-bar-segment-noncommence" style="width: {{ ($modulesNonCommences / $totalModules) * 100 }}%"></div>
                    @endif
                </div>
                
                <div class="data-bar-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #10b981;"></div>
                        <span>Terminés ({{ $modulesTermines }})</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #3b82f6;"></div>
                        <span>En cours ({{ $modulesEnCours }})</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #ef4444;"></div>
                        <span>Défaillants ({{ $modulesDefaillants }})</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #6b7280;"></div>
                        <span>Non commencés ({{ $modulesNonCommences }})</span>
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <table style="margin-bottom: 0; box-shadow: none;">
                        <tr>
                            <td style="padding: 5px 10px; border: none;"><strong>Date de réception:</strong></td>
                            <td style="padding: 5px 10px; border: none;">{{ $chantier->date_reception->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 10px; border: none;"><strong>Date butoir:</strong></td>
                            <td style="padding: 5px 10px; border: none;">{{ $chantier->date_butoir->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 10px; border: none;"><strong>Temps total:</strong></td>
                            <td style="padding: 5px 10px; border: none;">{{ $tempsFormate }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Informations du chantier -->
    <h2>Informations du Chantier</h2>
    <table class="info-table">
        <tr>
            <td>Client:</td>
            <td>{{ $chantier->client->nom_complet }}</td>
        </tr>
        <tr>
            <td>Société:</td>
            <td>{{ $chantier->client->societe ?: 'N/A' }}</td>
        </tr>
        <tr>
            <td>Référence:</td>
            <td>{{ $chantier->reference }}</td>
        </tr>
        <tr>
            <td>État actuel:</td>
            <td>
                @if($chantier->etat == 'non_commence')
                    <span class="badge badge-neutral">Non commencé</span>
                @elseif($chantier->etat == 'en_cours')
                    <span class="badge badge-warning">En cours</span>
                @else
                    <span class="badge badge-success">Terminé</span>
                @endif
            </td>
        </tr>
        <tr>
            <td>Description:</td>
            <td>{{ $chantier->description ?: 'Aucune description' }}</td>
        </tr>
    </table>
    
    <!-- Statistiques -->
    <h2>Statistiques du Chantier</h2>
    
    <div class="stats-container">
        <div class="stat-card card-chip-info">
            <div class="stat-label">Modules total</div>
            <div class="stat-value">{{ $totalModules }}</div>
            <div>unités</div>
        </div>
        
        <div class="stat-card card-chip-success">
            <div class="stat-label">Modules terminés</div>
            <div class="stat-value">{{ $modulesTermines }}</div>
            <div>unités</div>
        </div>
        
        <div class="stat-card card-chip-warning">
            <div class="stat-label">Interventions</div>
            <div class="stat-value">{{ $totalInterventions }}</div>
            <div>réalisées</div>
        </div>
        
        <div class="stat-card card-chip-danger">
            <div class="stat-label">Temps moyen</div>
            <div class="stat-value" style="font-size: 20pt;">{{ $tempsMoyenFormate }}</div>
            <div>par intervention</div>
        </div>
    </div>
    
    <!-- Graphiques -->
    <div class="grid-container">
        <div class="grid-item-half">
            <div class="chart-container">
                <div class="chart-title">État des modules</div>
                
                <div class="bar-container">
                    <div class="bar-label">Terminés</div>
                    <div class="bar-wrapper">
                        <div class="bar bar-termine" style="width: {{ $modulesTermines > 0 ? ($modulesTermines / $totalModules) * 100 : 0 }}%"></div>
                    </div>
                    <div class="bar-value">{{ $modulesTermines }}</div>
                </div>
                
                <div class="bar-container">
                    <div class="bar-label">En cours</div>
                    <div class="bar-wrapper">
                        <div class="bar bar-encours" style="width: {{ $modulesEnCours > 0 ? ($modulesEnCours / $totalModules) * 100 : 0 }}%"></div>
                    </div>
                    <div class="bar-value">{{ $modulesEnCours }}</div>
                </div>
                
                <div class="bar-container">
                    <div class="bar-label">Défaillants</div>
                    <div class="bar-wrapper">
                        <div class="bar bar-defaillant" style="width: {{ $modulesDefaillants > 0 ? ($modulesDefaillants / $totalModules) * 100 : 0 }}%"></div>
                    </div>
                    <div class="bar-value">{{ $modulesDefaillants }}</div>
                </div>
                
                <div class="bar-container">
                    <div class="bar-label">Non commencés</div>
                    <div class="bar-wrapper">
                        <div class="bar bar-noncommence" style="width: {{ $modulesNonCommences > 0 ? ($modulesNonCommences / $totalModules) * 100 : 0 }}%"></div>
                    </div>
                    <div class="bar-value">{{ $modulesNonCommences }}</div>
                </div>
            </div>
        </div>
        
        <div class="grid-item-half">
            <div class="chart-container">
                <div class="chart-title">Causes des problèmes</div>
                
                @php
                    $totalCauses = array_sum($causes);
                @endphp
                
                <div class="bar-container">
                    <div class="bar-label">Usure normale</div>
                    <div class="bar-wrapper">
                        <div class="bar bar-usure" style="width: {{ $totalCauses > 0 ? ($causes['usure_normale'] / $totalCauses) * 100 : 0 }}%"></div>
                    </div>
                    <div class="bar-value">{{ $causes['usure_normale'] }}</div>
                </div>
                
                <div class="bar-container">
                    <div class="bar-label">Dommage physique</div>
                    <div class="bar-wrapper">
                        <div class="bar bar-choc" style="width: {{ $totalCauses > 0 ? ($causes['choc'] / $totalCauses) * 100 : 0 }}%"></div>
                    </div>
                    <div class="bar-value">{{ $causes['choc'] }}</div>
                </div>
                
                <div class="bar-container">
                    <div class="bar-label">Défaut fabrication</div>
                    <div class="bar-wrapper">
                        <div class="bar bar-defaut" style="width: {{ $totalCauses > 0 ? ($causes['defaut_usine'] / $totalCauses) * 100 : 0 }}%"></div>
                    </div>
                    <div class="bar-value">{{ $causes['defaut_usine'] }}</div>
                </div>
                
                <div class="bar-container">
                    <div class="bar-label">Autre cause</div>
                    <div class="bar-wrapper">
                        <div class="bar bar-autre" style="width: {{ $totalCauses > 0 ? ($causes['autre'] / $totalCauses) * 100 : 0 }}%"></div>
                    </div>
                    <div class="bar-value">{{ $causes['autre'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="chart-container">
        <div class="chart-title">Composants remplacés</div>
        
        @php
            $maxComposant = max($totalLEDsRemplacees, $totalICsRemplaces, $totalMasquesRemplaces, 1);
        @endphp
        
        <div class="bar-container">
            <div class="bar-label">LEDs</div>
            <div class="bar-wrapper">
                <div class="bar bar-led" style="width: {{ $totalLEDsRemplacees > 0 ? ($totalLEDsRemplacees / $maxComposant) * 100 : 0 }}%"></div>
            </div>
            <div class="bar-value">{{ $totalLEDsRemplacees }}</div>
        </div>
        
        <div class="bar-container">
            <div class="bar-label">ICs</div>
            <div class="bar-wrapper">
                <div class="bar bar-ic" style="width: {{ $totalICsRemplaces > 0 ? ($totalICsRemplaces / $maxComposant) * 100 : 0 }}%"></div>
            </div>
            <div class="bar-value">{{ $totalICsRemplaces }}</div>
        </div>
        
        <div class="bar-container">
            <div class="bar-label">Masques</div>
            <div class="bar-wrapper">
                <div class="bar bar-masque" style="width: {{ $totalMasquesRemplaces > 0 ? ($totalMasquesRemplaces / $maxComposant) * 100 : 0 }}%"></div>
            </div>
            <div class="bar-value">{{ $totalMasquesRemplaces }}</div>
        </div>
    </div>
    
    <!-- Recommandations spécifiques -->
    @if(count($specificRecommendations) > 0)
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-dark">Recommandations spécifiques</h2>
        </div>
        <div class="card-body">
            <div class="section-highlight" style="background-color: #f0f9ff; border-left: 4px solid #2563eb;">
                <h3 style="margin-bottom: 15px; color: #1e40af;">Conseils techniques et préventifs</h3>
                <p style="margin-bottom: 10px; font-style: italic; color: #4b5563;">
                    Sur la base de l'analyse des problèmes rencontrés, nous vous recommandons les actions suivantes pour optimiser les performances et prolonger la durée de vie de votre installation :
                </p>
                <ul style="list-style-type: disc; padding-left: 20px; margin-top: 10px;">
                    @foreach($specificRecommendations as $key => $data)
                        <li style="margin-bottom: 15px; padding: 10px; background-color: white; border-radius: 8px; border: 1px solid #e5e7eb;">
                            <div style="font-weight: 600; color: #1e40af; margin-bottom: 5px;">
                                @if($key == 'perte_couleur_rouge')
                                    Perte de couleur rouge ({{ $data['count'] }} modules concernés)
                                @elseif($key == 'perte_couleur_verte')
                                    Perte de couleur verte ({{ $data['count'] }} modules concernés)
                                @elseif($key == 'ic_remplacement')
                                    Remplacement fréquent de circuits intégrés ({{ $data['count'] }} modules concernés)
                                @elseif($key == 'ligne_morte')
                                    Lignes mortes détectées ({{ $data['count'] }} modules concernés)
                                @elseif($key == 'humidite')
                                    Problèmes d'humidité ({{ $data['count'] }} modules concernés)
                                @else
                                    Problème spécifique ({{ $data['count'] }} modules concernés)
                                @endif
                            </div>
                            <div style="color: #4b5563;">
                                {{ $data['recommendation'] }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Saut de page -->
    <div class="page-break"></div>
    
    <!-- Section produits -->
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('images/logo-repair.png') }}" alt="TecaLED" class="logo">
        </div>
        <div class="header-content">
            <h1>Détails des Produits</h1>
            <div style="font-size: 14pt; margin: 5px 0;">Chantier: {{ $chantier->reference }}</div>
            <div class="report-date">{{ $chantier->client->nom_complet }}</div>
        </div>
    </div>
    
    @foreach($chantier->produits as $produit)
        <div class="summary-card">
            <div class="summary-header">
                <div class="summary-title">{{ $produit->marque }} {{ $produit->modele }}</div>
                <div class="summary-subtitle">
                    <span class="badge" style="background-color: #f0f9ff; color: #1e40af;">Pitch: {{ $produit->pitch }} mm</span>
                    <span class="badge" style="background-color: #f0f9ff; color: #1e40af;">{{ $produit->utilisation == 'indoor' ? 'Indoor' : 'Outdoor' }}</span>
                    <span class="badge" style="background-color: #f0f9ff; color: #1e40af;">
                        @if($produit->electronique == 'autre')
                            {{ $produit->electronique_detail }}
                        @else
                            {{ ucfirst($produit->electronique) }}
                        @endif
                    </span>
                </div>
            </div>
            
            <h3>Liste des dalles ({{ $produit->dalles->count() }})</h3>
            <table class="striped">
                <thead>
                    <tr>
                        <th width="15%">Dalle #</th>
                        <th width="20%">Dimensions</th>
                        <th width="25%">Modules</th>
                        <th width="25%">Progression</th>
                        <th width="15%">État</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produit->dalles as $dalle)
                        @php
                            $totalModulesDalle = $dalle->modules->count();
                            $modulesTerminesDalle = $dalle->modules->where('etat', 'termine')->count();
                            $pourcentageTerminesDalle = $totalModulesDalle > 0 ? round(($modulesTerminesDalle / $totalModulesDalle) * 100) : 0;
                            
                            $progressClass = 'progress-bar';
                            if ($pourcentageTerminesDalle >= 75) {
                                $progressClass .= ' progress-success';
                            } elseif ($pourcentageTerminesDalle >= 25) {
                                $progressClass .= ' progress-warning';
                            } else {
                                $progressClass .= ' progress-danger';
                            }
                        @endphp
                        <tr>
                            <td>Dalle #{{ $dalle->id }}</td>
                            <td>{{ $dalle->largeur }} × {{ $dalle->hauteur }} mm</td>
                            <td>{{ $modulesTerminesDalle }} / {{ $totalModulesDalle }}</td>
                            <td>
                                <div class="progress-container" style="margin: 0;">
                                    <div class="{{ $progressClass }}" style="width: {{ $pourcentageTerminesDalle }}%"></div>
                                </div>
                            </td>
                            <td>{{ $pourcentageTerminesDalle }}% terminé</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
    
    <!-- Saut de page -->
    <div class="page-break"></div>
    
    <!-- Section modules -->
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('images/logo-repair.png') }}" alt="TecaLED" class="logo">
        </div>
        <div class="header-content">
            <h1>Détails des Modules</h1>
            <div style="font-size: 14pt; margin: 5px 0;">Chantier: {{ $chantier->reference }}</div>
            <div class="report-date">{{ $chantier->client->nom_complet }}</div>
        </div>
    </div>
    
    @foreach($chantier->produits as $produit)
        <h2>{{ $produit->marque }} {{ $produit->modele }}</h2>
        
        @foreach($produit->dalles as $dalle)
            <h3>Dalle #{{ $dalle->id }} ({{ $dalle->largeur }} × {{ $dalle->hauteur }} mm)</h3>
            
            <div class="grid-container">
                @foreach($dalle->modules as $module)
                    @php
                        $moduleBoxClass = 'module-box';
                        if ($module->etat == 'termine') {
                            $moduleBoxClass .= ' module-success';
                        } elseif ($module->etat == 'en_cours') {
                            $moduleBoxClass .= ' module-warning';
                        } elseif ($module->etat == 'defaillant') {
                            $moduleBoxClass .= ' module-danger';
                        } else {
                            $moduleBoxClass .= ' module-neutral';
                        }
                    @endphp
                    
                    <div class="grid-item-half">
                        <div class="{{ $moduleBoxClass }}">
                            <div class="module-header">
                                <div class="module-title">Module #{{ $module->id }}</div>
                                <div>
                                    @if($module->etat == 'non_commence')
                                        <span class="badge badge-neutral">Non commencé</span>
                                    @elseif($module->etat == 'en_cours')
                                        <span class="badge badge-warning">En cours</span>
                                    @elseif($module->etat == 'termine')
                                        <span class="badge badge-success">Terminé</span>
                                    @elseif($module->etat == 'defaillant')
                                        <span class="badge badge-danger">Défaillant</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="module-detail">
                                <div class="module-label">Référence</div>
                                <div>{{ $module->reference_module }}</div>
                            </div>
                            
                            <div class="module-detail">
                                <div class="module-label">Dimensions</div>
                                <div>{{ $module->largeur }}×{{ $module->hauteur }} mm</div>
                            </div>
                            
                            <div class="module-detail">
                                <div class="module-label">Résolution</div>
                                <div>{{ $module->nb_pixels_largeur }}×{{ $module->nb_pixels_hauteur }} pixels</div>
                            </div>
                            
                            <div class="module-detail">
                                <div class="module-label">N° Série</div>
                                <div>{{ $module->numero_serie ?: 'Non renseigné' }}</div>
                            </div>
                            
                            @if($module->interventions->count() > 0)
                                <h4>Interventions</h4>
                                @foreach($module->interventions->sortByDesc('created_at') as $intervention)
                                    <div class="module-intervention">
                                        <div style="font-weight: bold; margin-bottom: 5px; display: flex; justify-content: space-between;">
                                            <span>Intervention #{{ $intervention->id }} ({{ $intervention->created_at->format('d/m/Y') }})</span>
                                            @if($intervention->is_completed)
                                                <span class="badge badge-success" style="font-size: 8pt;">Terminée</span>
                                            @else
                                                <span class="badge badge-warning" style="font-size: 8pt;">En cours</span>
                                            @endif
                                        </div>
                                        
                                        @if($intervention->diagnostic)
                                            <div style="margin: 8px 0; font-size: 10pt;">
                                                <strong>Diagnostic:</strong> 
                                                {{ $intervention->diagnostic->nb_leds_hs }} LEDs HS, 
                                                {{ $intervention->diagnostic->nb_ic_hs }} ICs HS, 
                                                {{ $intervention->diagnostic->nb_masques_hs }} masques HS
                                                
                                                @if($intervention->diagnostic->cause)
                                                    <div style="margin-top: 5px;">
                                                        <strong>Cause:</strong>
                                                        @if($intervention->diagnostic->cause == 'usure_normale')
                                                            <span class="badge" style="background-color: #dbeafe; color: #1e40af; font-size: 8pt;">Usure normale</span>
                                                            <div style="margin-top: 5px; font-style: italic; font-size: 8pt; color: #4b5563;">
                                                                Ce phénomène correspond à une usure normale des composants électroniques qui survient avec le temps et l'utilisation. Il s'agit d'un processus naturel qui ne présente aucun caractère anormal ou inquiétant.
                                                            </div>
                                                        @elseif($intervention->diagnostic->cause == 'choc')
                                                            <span class="badge" style="background-color: #fee2e2; color: #b91c1c; font-size: 8pt;">Dommage physique</span>
                                                            <div style="margin-top: 5px; font-style: italic; font-size: 8pt; color: #4b5563;">
                                                                Les dégâts constatés sont dus à un impact physique ou à une contrainte mécanique. Pour éviter ce type de problème à l'avenir, nous recommandons une manipulation plus précautionneuse et la mise en place de protections adaptées.
                                                            </div>
                                                        @elseif($intervention->diagnostic->cause == 'defaut_usine')
                                                            <span class="badge" style="background-color: #fef3c7; color: #92400e; font-size: 8pt;">Défaut fabrication</span>
                                                            <div style="margin-top: 5px; font-style: italic; font-size: 8pt; color: #4b5563;">
                                                                Ce défaut est lié à un problème survenu lors de la fabrication initiale du module. Il s'agit d'une anomalie de production qui a été identifiée et corrigée lors de notre intervention.
                                                            </div>
                                                        @else
                                                            <span class="badge" style="background-color: #f3f4f6; color: #4b5563; font-size: 8pt;">Autre cause</span>
                                                            <div style="margin-top: 5px; font-style: italic; font-size: 8pt; color: #4b5563;">
                                                                La cause exacte ne correspond pas aux catégories standards. Veuillez consulter les remarques complémentaires pour plus de détails sur l'origine du problème.
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if($intervention->reparation)
                                            <div style="margin: 8px 0; font-size: 10pt;">
                                                <strong>Réparation:</strong> 
                                                {{ $intervention->reparation->nb_leds_remplacees }} LEDs remplacées, 
                                                {{ $intervention->reparation->nb_ic_remplaces }} ICs remplacés, 
                                                {{ $intervention->reparation->nb_masques_remplaces }} masques remplacés
                                            </div>
                                        @endif
                                        
                                        <div style="margin: 8px 0; font-size: 10pt;">
                                            <strong>Technicien:</strong> 
                                            {{ $intervention->technicien ? $intervention->technicien->name : 'Non assigné' }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p style="font-style: italic; color: #6b7280; margin-top: 15px;">Aucune intervention réalisée sur ce module.</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($loop->iteration % 4 == 0 && !$loop->last)
                        </div>
                        <div class="page-break"></div>
                        <div class="grid-container">
                    @endif
                @endforeach
            </div>
            
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    @endforeach
    
    <!-- Pied de page -->
    <div class="footer">
        <p>Rapport généré automatiquement par l'application GMAO TecaLED - {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>&copy; {{ date('Y') }} TecaLED - Tous droits réservés</p>
    </div>
</body>
</html>