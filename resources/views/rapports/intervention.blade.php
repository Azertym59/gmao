<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche d'intervention - #{{ $intervention->id }}</title>
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
        .info-block {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .signature-box {
            margin-top: 30px;
            border: 1px solid #e5e7eb;
            padding: 20px;
            width: 45%;
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
        <h1>Fiche d'intervention</h1>
        <p>N° {{ $intervention->id }} | Date: {{ $intervention->date_debut->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <h2 class="section-title">Informations générales</h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Chantier:</span> {{ $intervention->module->dalle->produit->chantier->nom }}
            </div>
            <div class="info-item">
                <span class="info-label">Référence chantier:</span> {{ $intervention->module->dalle->produit->chantier->reference }}
            </div>
            <div class="info-item">
                <span class="info-label">Client:</span> {{ $intervention->module->dalle->produit->chantier->client->nom_complet }}
            </div>
            <div class="info-item">
                <span class="info-label">Société:</span> {{ $intervention->module->dalle->produit->chantier->client->societe ?: 'N/A' }}
            </div>
            <div class="info-item">
                <span class="info-label">Technicien:</span> {{ $intervention->technicien ? $intervention->technicien->name : "Non assigné" }}
            </div>
            <div class="info-item">
                <span class="info-label">Durée de l'intervention:</span> {{ $tempsFormate }}
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Détails du module</h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Module ID:</span> #{{ $intervention->module->id }}
            </div>
            <div class="info-item">
                <span class="info-label">Dalle:</span> #{{ $intervention->module->dalle->id }}
            </div>
            <div class="info-item">
                <span class="info-label">Produit:</span> {{ $intervention->module->dalle->produit->marque }} {{ $intervention->module->dalle->produit->modele }}
            </div>
            <div class="info-item">
                <span class="info-label">Dimensions:</span> {{ $intervention->module->largeur }}×{{ $intervention->module->hauteur }} mm
            </div>
            <div class="info-item">
                <span class="info-label">Résolution:</span> {{ $intervention->module->nb_pixels_largeur }}×{{ $intervention->module->nb_pixels_hauteur }} pixels
            </div>
            <div class="info-item">
                <span class="info-label">État:</span>
                @if($intervention->module->etat == 'non_commence')
                    Non commencé
                @elseif($intervention->module->etat == 'en_cours')
                    En cours
                @elseif($intervention->module->etat == 'defaillant')
                    Défaillant
                @else
                    Terminé
                @endif
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Diagnostic</h2>
        <div class="info-block">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nombre de LEDs HS:</span> {{ $intervention->diagnostic->nb_leds_hs }}
                </div>
                <div class="info-item">
                    <span class="info-label">Nombre d'ICs HS:</span> {{ $intervention->diagnostic->nb_ic_hs }}
                </div>
                <div class="info-item">
                    <span class="info-label">Nombre de masques HS:</span> {{ $intervention->diagnostic->nb_masques_hs }}
                </div>
            </div>
            
            <div class="info-item" style="margin-top: 15px;">
                <span class="info-label">Remarques:</span><br>
                {{ $intervention->diagnostic->remarques ?: 'Aucune remarque' }}
            </div>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Réparations effectuées</h2>
        <div class="info-block">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">LEDs remplacées:</span> {{ $intervention->reparation->nb_leds_remplacees }}
                </div>
                <div class="info-item">
                    <span class="info-label">ICs remplacés:</span> {{ $intervention->reparation->nb_ic_remplaces }}
                </div>
                <div class="info-item">
                    <span class="info-label">Masques remplacés:</span> {{ $intervention->reparation->nb_masques_remplaces }}
                </div>
            </div>
            
            <div class="info-item" style="margin-top: 15px;">
                <span class="info-label">Remarques:</span><br>
                {{ $intervention->reparation->remarques ?: 'Aucune remarque' }}
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between;">
        <div class="signature-box">
            <p class="info-label">Signature du technicien:</p>
            <div style="height: 60px;"></div>
            <p>{{ $intervention->technicien ? $intervention->technicien->name : "Non assigné" }}</p>
        </div>

        <div class="signature-box">
            <p class="info-label">Signature du responsable:</p>
            <div style="height: 60px;"></div>
            <p>____________________</p>
        </div>
    </div>

    <div class="footer">
        <p>Fiche d'intervention générée automatiquement par l'application GMAO - {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Page 1/1</p>
    </div>
</body>
</html>