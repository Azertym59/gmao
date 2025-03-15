<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Chantier - {{ $chantier->reference }}</title>
    <style>
        @page {
            margin: 15mm;
            size: portrait;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        
        .page-header {
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            background-color: #f8fafc;
            border-bottom: 3px solid #2563eb;
        }
        
        .header-logo {
            width: 150px;
            display: block;
            margin: 0 auto 15px;
        }
        
        .thank-you-message {
            margin: 15px 0;
            padding: 10px;
            background-color: #dbeafe;
            border-left: 4px solid #2563eb;
            font-style: italic;
            color: #1e40af;
            text-align: center;
        }
        
        h1 {
            font-size: 24px;
            margin: 0 0 5px;
            color: #2563eb;
        }
        
        h2 {
            font-size: 18px;
            color: #2563eb;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        
        h3 {
            font-size: 16px;
            color: #4b5563;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        
        h4 {
            font-size: 14px;
            color: #6b7280;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        
        .container {
            padding: 0 20px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .info-table td {
            padding: 8px;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            font-weight: bold;
            width: 180px;
            color: #4b5563;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        
        .data-table th {
            background-color: #f3f4f6;
            text-align: left;
            padding: 10px;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            font-weight: bold;
        }
        
        .data-table td {
            padding: 10px;
            border: 1px solid #e5e7eb;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-termine {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-en-cours {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-defaillant {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        .status-non-commence {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        
        .progress-bar-container {
            width: 100%;
            height: 10px;
            background-color: #e5e7eb;
            border-radius: 4px;
            margin: 10px 0;
        }
        
        .progress-bar {
            height: 10px;
            background-color: #2563eb;
            border-radius: 4px;
        }
        
        .stat-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            text-align: center;
            display: inline-block;
            width: 23%;
            margin-right: 1%;
            vertical-align: top;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6b7280;
        }
        
        .cause-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        .cause-usure {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .cause-choc {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        .cause-defaut {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .cause-autre {
            background-color: #e5e7eb;
            color: #374151;
        }
        
        .graph-container {
            padding: 15px;
            background-color: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .vertical-bar-container {
            height: 200px;
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            margin: 20px 0;
            padding-bottom: 25px;
            border-bottom: 1px solid #e5e7eb;
            position: relative;
        }
        
        .vertical-bar {
            width: 40px;
            background-color: #2563eb;
            border-radius: 4px 4px 0 0;
            position: relative;
            margin: 0 10px;
        }
        
        .vertical-bar-label {
            position: absolute;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #4b5563;
            transform: rotate(-45deg);
            transform-origin: right top;
            white-space: nowrap;
        }
        
        .vertical-bar-value {
            position: absolute;
            top: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        
        .clearfix {
            clear: both;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .module-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .module-header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        
        .module-title {
            font-weight: bold;
            color: #2563eb;
        }
        
        .module-status {
            font-weight: normal;
        }
        
        .module-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .module-info-item {
            margin-bottom: 5px;
        }
        
        .module-info-label {
            font-weight: bold;
            color: #4b5563;
            font-size: 12px;
        }
        
        .module-info-value {
            color: #111827;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Page d'entête -->
    <div class="page-header">
        <img src="{{ public_path('images/logo-repair.png') }}" alt="TecaLED" class="header-logo">
        <h1>RAPPORT DÉTAILLÉ DU CHANTIER</h1>
        <p>Référence: {{ $chantier->reference }} | Date d'édition: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="container">
        <div class="thank-you-message">
            Cher/Chère {{ $chantier->client->nom }} {{ $chantier->client->prenom }}, nous vous remercions pour votre confiance. 
            Voici le rapport détaillé de votre chantier.
        </div>
        
        <h2>INFORMATIONS GÉNÉRALES</h2>
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
                <td>Date de réception:</td>
                <td>{{ $chantier->date_reception->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Date butoir:</td>
                <td>{{ $chantier->date_butoir->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>État actuel:</td>
                <td>
                    @if($chantier->etat == 'non_commence')
                        <span class="status-badge status-non-commence">Non commencé</span>
                    @elseif($chantier->etat == 'en_cours')
                        <span class="status-badge status-en-cours">En cours</span>
                    @else
                        <span class="status-badge status-termine">Terminé</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Description:</td>
                <td>{{ $chantier->description ?: 'Aucune description' }}</td>
            </tr>
        </table>

        <h2>SYNTHÈSE DE L'AVANCEMENT</h2>
        
        <div class="text-center">
            <div class="stat-box">
                <div class="stat-label">Modules total</div>
                <div class="stat-value">{{ $totalModules }}</div>
            </div>
            
            <div class="stat-box">
                <div class="stat-label">Modules terminés</div>
                <div class="stat-value">{{ $modulesTermines }}</div>
            </div>
            
            <div class="stat-box">
                <div class="stat-label">Progression</div>
                <div class="stat-value">{{ $pourcentageTermines }}%</div>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: {{ $pourcentageTermines }}%"></div>
                </div>
            </div>
            
            <div class="stat-box">
                <div class="stat-label">Temps total</div>
                <div class="stat-value">{{ $tempsFormate }}</div>
            </div>
        </div>
        
        <div class="clearfix"></div>

        <h3>État des modules</h3>
        <div class="graph-container">
            <div class="vertical-bar-container">
                @php
                    $maxModules = max($modulesTermines, $modulesEnCours, $modulesDefaillants, $modulesNonCommences);
                    
                    // Définir une hauteur minimum pour les barres qui ont une valeur mais qui seraient trop petites
                    $minBarHeight = 5;
                @endphp
                
                <div class="vertical-bar" style="height: {{ $modulesTermines > 0 ? max($minBarHeight, $modulesTermines / $maxModules * 180) : 0 }}px; background-color: #10b981;">
                    <span class="vertical-bar-value">{{ $modulesTermines }}</span>
                    <span class="vertical-bar-label">Terminés</span>
                </div>
                
                <div class="vertical-bar" style="height: {{ $modulesEnCours > 0 ? max($minBarHeight, $modulesEnCours / $maxModules * 180) : 0 }}px; background-color: #3b82f6;">
                    <span class="vertical-bar-value">{{ $modulesEnCours }}</span>
                    <span class="vertical-bar-label">En cours</span>
                </div>
                
                <div class="vertical-bar" style="height: {{ $modulesDefaillants > 0 ? max($minBarHeight, $modulesDefaillants / $maxModules * 180) : 0 }}px; background-color: #ef4444;">
                    <span class="vertical-bar-value">{{ $modulesDefaillants }}</span>
                    <span class="vertical-bar-label">Défaillants</span>
                </div>
                
                <div class="vertical-bar" style="height: {{ $modulesNonCommences > 0 ? max($minBarHeight, $modulesNonCommences / $maxModules * 180) : 0 }}px; background-color: #6b7280;">
                    <span class="vertical-bar-value">{{ $modulesNonCommences }}</span>
                    <span class="vertical-bar-label">Non commencés</span>
                </div>
            </div>
        </div>

        <h3>Causes des problèmes</h3>
        <div class="graph-container">
            <div class="vertical-bar-container">
                @php
                    $totalCauses = array_sum($causes);
                    $maxCause = max($causes['usure_normale'], $causes['choc'], $causes['defaut_usine'], $causes['autre']);
                @endphp
                
                <div class="vertical-bar" style="height: {{ $causes['usure_normale'] > 0 ? max($minBarHeight, $causes['usure_normale'] / $maxCause * 180) : 0 }}px; background-color: #3b82f6;">
                    <span class="vertical-bar-value">{{ $causes['usure_normale'] }}</span>
                    <span class="vertical-bar-label">Usure normale</span>
                </div>
                
                <div class="vertical-bar" style="height: {{ $causes['choc'] > 0 ? max($minBarHeight, $causes['choc'] / $maxCause * 180) : 0 }}px; background-color: #ef4444;">
                    <span class="vertical-bar-value">{{ $causes['choc'] }}</span>
                    <span class="vertical-bar-label">Dommage physique</span>
                </div>
                
                <div class="vertical-bar" style="height: {{ $causes['defaut_usine'] > 0 ? max($minBarHeight, $causes['defaut_usine'] / $maxCause * 180) : 0 }}px; background-color: #f59e0b;">
                    <span class="vertical-bar-value">{{ $causes['defaut_usine'] }}</span>
                    <span class="vertical-bar-label">Défaut fabrication</span>
                </div>
                
                <div class="vertical-bar" style="height: {{ $causes['autre'] > 0 ? max($minBarHeight, $causes['autre'] / $maxCause * 180) : 0 }}px; background-color: #6b7280;">
                    <span class="vertical-bar-value">{{ $causes['autre'] }}</span>
                    <span class="vertical-bar-label">Autre cause</span>
                </div>
            </div>
        </div>

        <h3>Composants remplacés</h3>
        <div class="graph-container">
            <div class="vertical-bar-container">
                @php
                    $maxComposant = max($totalLEDsRemplacees, $totalICsRemplaces, $totalMasquesRemplaces);
                @endphp
                
                <div class="vertical-bar" style="height: {{ $totalLEDsRemplacees > 0 ? max($minBarHeight, $totalLEDsRemplacees / $maxComposant * 180) : 0 }}px; background-color: #8b5cf6;">
                    <span class="vertical-bar-value">{{ $totalLEDsRemplacees }}</span>
                    <span class="vertical-bar-label">LEDs</span>
                </div>
                
                <div class="vertical-bar" style="height: {{ $totalICsRemplaces > 0 ? max($minBarHeight, $totalICsRemplaces / $maxComposant * 180) : 0 }}px; background-color: #6366f1;">
                    <span class="vertical-bar-value">{{ $totalICsRemplaces }}</span>
                    <span class="vertical-bar-label">ICs</span>
                </div>
                
                <div class="vertical-bar" style="height: {{ $totalMasquesRemplaces > 0 ? max($minBarHeight, $totalMasquesRemplaces / $maxComposant * 180) : 0 }}px; background-color: #ec4899;">
                    <span class="vertical-bar-value">{{ $totalMasquesRemplaces }}</span>
                    <span class="vertical-bar-label">Masques</span>
                </div>
            </div>
        </div>
        
        <h3>Statistiques d'interventions</h3>
        <table class="info-table">
            <tr>
                <td>Total des interventions:</td>
                <td><strong>{{ $totalInterventions }}</strong></td>
            </tr>
            <tr>
                <td>Temps moyen par intervention:</td>
                <td><strong>{{ $tempsMoyenFormate }}</strong></td>
            </tr>
            <tr>
                <td>Répartition des causes:</td>
                <td>
                    <span class="cause-badge cause-usure">Usure: {{ $causes['usure_normale'] }}</span>
                    <span class="cause-badge cause-choc">Dommage: {{ $causes['choc'] }}</span>
                    <span class="cause-badge cause-defaut">Défaut: {{ $causes['defaut_usine'] }}</span>
                    <span class="cause-badge cause-autre">Autre: {{ $causes['autre'] }}</span>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="page-break"></div>
    
    <div class="page-header">
        <h1>DÉTAILS DES PRODUITS</h1>
        <p>Chantier: {{ $chantier->reference }} - {{ $chantier->nom }}</p>
    </div>
    
    <div class="container">
        @foreach($chantier->produits as $produit)
            <h2>{{ $produit->marque }} {{ $produit->modele }}</h2>
            
            <table class="info-table">
                <tr>
                    <td>Pitch:</td>
                    <td>{{ $produit->pitch }} mm</td>
                </tr>
                <tr>
                    <td>Utilisation:</td>
                    <td>{{ $produit->utilisation == 'indoor' ? 'Indoor' : 'Outdoor' }}</td>
                </tr>
                <tr>
                    <td>Électronique:</td>
                    <td>
                        @if($produit->electronique == 'autre')
                            {{ $produit->electronique_detail }}
                        @else
                            {{ ucfirst($produit->electronique) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Nombre de dalles:</td>
                    <td>{{ $produit->dalles->count() }}</td>
                </tr>
            </table>
            
            <h3>Liste des dalles</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="15%">Dalle</th>
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
                        @endphp
                        <tr>
                            <td>Dalle #{{ $dalle->id }}</td>
                            <td>{{ $dalle->largeur }} × {{ $dalle->hauteur }} mm</td>
                            <td>{{ $modulesTerminesDalle }} / {{ $totalModulesDalle }}</td>
                            <td>
                                <div class="progress-bar-container" style="margin: 0;">
                                    <div class="progress-bar" style="width: {{ $pourcentageTerminesDalle }}%"></div>
                                </div>
                            </td>
                            <td>{{ $pourcentageTerminesDalle }}% terminé</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($loop->last && $loop->count > 0)
                <div class="page-break"></div>
            @endif
        @endforeach
        
        <div class="page-header">
            <h1>DÉTAILS DES MODULES</h1>
            <p>Chantier: {{ $chantier->reference }} - {{ $chantier->nom }}</p>
        </div>
        
        @foreach($chantier->produits as $produit)
            <h2>{{ $produit->marque }} {{ $produit->modele }}</h2>
            
            @foreach($produit->dalles as $dalle)
                <h3>Dalle #{{ $dalle->id }} ({{ $dalle->largeur }} × {{ $dalle->hauteur }} mm)</h3>
                
                @foreach($dalle->modules as $module)
                    <div class="module-box">
                        <div class="module-header">
                            <span class="module-title">Module #{{ $module->id }} - {{ $module->reference_module }}</span>
                            <span class="module-status">
                                @if($module->etat == 'non_commence')
                                    <span class="status-badge status-non-commence">Non commencé</span>
                                @elseif($module->etat == 'en_cours')
                                    <span class="status-badge status-en-cours">En cours</span>
                                @elseif($module->etat == 'termine')
                                    <span class="status-badge status-termine">Terminé</span>
                                @elseif($module->etat == 'defaillant')
                                    <span class="status-badge status-defaillant">Défaillant</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="module-info-grid">
                            <div class="module-info-item">
                                <span class="module-info-label">Dimensions:</span>
                                <span class="module-info-value">{{ $module->largeur }}×{{ $module->hauteur }} mm</span>
                            </div>
                            <div class="module-info-item">
                                <span class="module-info-label">Résolution:</span>
                                <span class="module-info-value">{{ $module->nb_pixels_largeur }}×{{ $module->nb_pixels_hauteur }} pixels</span>
                            </div>
                            <div class="module-info-item">
                                <span class="module-info-label">Numéro de série:</span>
                                <span class="module-info-value">{{ $module->numero_serie ?: 'Non renseigné' }}</span>
                            </div>
                            <div class="module-info-item">
                                <span class="module-info-label">Disposition:</span>
                                <span class="module-info-value">{{ $module->disposition ?: 'Standard' }}</span>
                            </div>
                        </div>
                        
                        @if($module->interventions->count() > 0)
                            <h4>Interventions</h4>
                            @foreach($module->interventions->sortByDesc('created_at') as $intervention)
                                <div style="margin-left: 10px; margin-bottom: 10px; padding-left: 10px; border-left: 2px solid #e5e7eb;">
                                    <div style="font-weight: bold; color: #4b5563; font-size: 13px;">
                                        Intervention #{{ $intervention->id }} ({{ $intervention->created_at->format('d/m/Y') }})
                                        @if($intervention->is_completed)
                                            <span class="status-badge status-termine" style="font-size: 10px;">Terminée</span>
                                        @else
                                            <span class="status-badge status-en-cours" style="font-size: 10px;">En cours</span>
                                        @endif
                                    </div>
                                    
                                    @if($intervention->diagnostic)
                                        <div style="margin: 5px 0; font-size: 12px;">
                                            <span style="font-weight: bold;">Diagnostic:</span> 
                                            {{ $intervention->diagnostic->nb_leds_hs }} LEDs HS, 
                                            {{ $intervention->diagnostic->nb_ic_hs }} ICs HS, 
                                            {{ $intervention->diagnostic->nb_masques_hs }} masques HS
                                            
                                            @if($intervention->diagnostic->cause)
                                                <br>
                                                <span style="font-weight: bold;">Cause:</span>
                                                @if($intervention->diagnostic->cause == 'usure_normale')
                                                    <span class="cause-badge cause-usure" style="font-size: 10px;">Usure normale</span>
                                                @elseif($intervention->diagnostic->cause == 'choc')
                                                    <span class="cause-badge cause-choc" style="font-size: 10px;">Dommage physique</span>
                                                @elseif($intervention->diagnostic->cause == 'defaut_usine')
                                                    <span class="cause-badge cause-defaut" style="font-size: 10px;">Défaut fabrication</span>
                                                @else
                                                    <span class="cause-badge cause-autre" style="font-size: 10px;">Autre cause</span>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if($intervention->reparation)
                                        <div style="margin: 5px 0; font-size: 12px;">
                                            <span style="font-weight: bold;">Réparation:</span> 
                                            {{ $intervention->reparation->nb_leds_remplacees }} LEDs remplacées, 
                                            {{ $intervention->reparation->nb_ic_remplaces }} ICs remplacés, 
                                            {{ $intervention->reparation->nb_masques_remplaces }} masques remplacés
                                        </div>
                                    @endif
                                    
                                    <div style="margin: 5px 0; font-size: 12px;">
                                        <span style="font-weight: bold;">Technicien:</span> 
                                        {{ $intervention->technicien ? $intervention->technicien->name : 'Non assigné' }}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p style="font-size: 12px; color: #6b7280; font-style: italic;">Aucune intervention réalisée sur ce module.</p>
                        @endif
                    </div>
                @endforeach
                
                @if(!$loop->last)
                    <div class="page-break"></div>
                @endif
            @endforeach
        @endforeach
        
        <div class="footer">
            <p>Rapport généré automatiquement par l'application GMAO - {{ now()->format('d/m/Y H:i:s') }}</p>
            <p>TecaLED - GMAO &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>