<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Chantier - {{ $chantier->reference }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .header h1 {
            color: #2563eb;
            margin-bottom: 5px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #1f2937;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #4b5563;
        }
        .progress-container {
            background-color: #e5e7eb;
            border-radius: 4px;
            height: 20px;
            width: 100%;
            margin: 10px 0;
        }
        .progress-bar {
            background-color: #10b981;
            height: 20px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            background-color: #f3f4f6;
            text-align: left;
            padding: 10px;
            font-weight: bold;
            color: #4b5563;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .status {
            padding: 3px 8px;
            border-radius: 9999px;
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
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport du chantier</h1>
        <p>Référence: {{ $chantier->reference }}</p>
        <p>Date d'édition: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <h2 class="section-title">Informations générales</h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Client:</span> {{ $chantier->client->nom_complet }}
            </div>
            <div class="info-item">
                <span class="info-label">Société:</span> {{ $chantier->client->societe ?: 'N/A' }}
            </div>
            <div class="info-item">
                <span class="info-label">Date de réception:</span> {{ $chantier->date_reception->format('d/m/Y') }}
            </div>
            <div class="info-item">
                <span class="info-label">Date butoir:</span> {{ $chantier->date_butoir->format('d/m/Y') }}
            </div>
            <div class="info-item">
                <span class="info-label">État:</span> 
                @if($chantier->etat == 'non_commence')
                    Non commencé
                @elseif($chantier->etat == 'en_cours')
                    En cours
                @else
                    Terminé
                @endif
            </div>
            <div class="info-item">
                <span class="info-label">Description:</span> {{ $chantier->description ?: 'Aucune description' }}
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Avancement global</h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Modules total:</span> {{ $totalModules }}
            </div>
            <div class="info-item">
                <span class="info-label">Modules terminés:</span> {{ $modulesTermines }}
            </div>
            <div class="info-item">
                <span class="info-label">Modules en cours:</span> {{ $modulesEnCours }}
            </div>
            <div class="info-item">
                <span class="info-label">Modules défaillants:</span> {{ $modulesDefaillants }}
            </div>
            <div class="info-item">
                <span class="info-label">Modules non commencés:</span> {{ $modulesNonCommences }}
            </div>
            <div class="info-item">
                <span class="info-label">Progression:</span> {{ $pourcentageTermines }}%
            </div>
        </div>
        
        <div class="progress-container">
            <div class="progress-bar" style="width: {{ $pourcentageTermines }}%"></div>
        </div>
        
        <div class="info-item">
            <span class="info-label">Temps total d'intervention:</span> {{ $tempsFormate }}
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Composants remplacés</h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">LEDs remplacées:</span> {{ $totalLEDsRemplacees }}
            </div>
            <div class="info-item">
                <span class="info-label">ICs remplacés:</span> {{ $totalICsRemplaces }}
            </div>
            <div class="info-item">
                <span class="info-label">Masques remplacés:</span> {{ $totalMasquesRemplaces }}
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Détails des produits</h2>
        @foreach($chantier->produits as $produit)
            <div style="margin-bottom: 20px;">
                <h3>{{ $produit->marque }} {{ $produit->modele }}</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Pitch:</span> {{ $produit->pitch }} mm
                    </div>
                    <div class="info-item">
                        <span class="info-label">Utilisation:</span> {{ $produit->utilisation == 'indoor' ? 'Indoor' : 'Outdoor' }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Électronique:</span> 
                        @if($produit->electronique == 'autre')
                            {{ $produit->electronique_detail }}
                        @else
                            {{ ucfirst($produit->electronique) }}
                        @endif
                    </div>
                    <div class="info-item">
                        <span class="info-label">Dalles:</span> {{ $produit->dalles->count() }}
                    </div>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Dalle</th>
                            <th>Dimensions</th>
                            <th>Modules (Total)</th>
                            <th>Modules (Terminés)</th>
                            <th>État</th>
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
                                <td>{{ $totalModulesDalle }}</td>
                                <td>{{ $modulesTerminesDalle }}</td>
                                <td>{{ $pourcentageTerminesDalle }}% terminé</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <div class="footer">
        <p>Rapport généré automatiquement par l'application GMAO - {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Page 1/1</p>
    </div>
</body>
</html>