<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Étiquette d'impression pour chantier GMAO TecaLED - Format Brother">
    <meta name="print-dimension" content="62mm x 100mm">
    <meta name="print-type" content="label-continuous-roll-dk22205">
    <title>Étiquette Brother - Chantier {{ $chantier->id }}</title>
    <style>
        /* Réinitialisation pour l'impression */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            width: 62mm;
            height: 100mm;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: white;
        }
        
        /* Styles pour l'étiquette */
        .etiquette-flightcase {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .etiquette-header {
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px;
        }
        
        .etiquette-header .logo {
            height: 24px;
        }
        
        .etiquette-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            color: #fff;
        }
        
        .etiquette-reference-section {
            display: flex;
            border-bottom: 1px solid #000;
            padding: 6px;
        }
        
        .etiquette-reference {
            width: 60%;
            padding-right: 4px;
        }
        
        .etiquette-qrcode {
            width: 40%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #000;
            padding: 2px;
            background-color: white;
        }
        
        .etiquette-qrcode img {
            width: 100%;
            max-width: 80px;
            height: auto;
        }
        
        .label {
            font-size: 8px;
            font-weight: bold;
            margin: 0 0 2px 0;
        }
        
        .reference-code {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 2px 0;
            color: #f00;
        }
        
        .date-info, .deadline {
            font-size: 8px;
            margin: 1px 0;
        }
        
        .deadline {
            font-weight: bold;
            color: #f00;
        }
        
        .etiquette-client-section {
            display: flex;
            padding: 6px;
            border-bottom: 1px solid #000;
        }
        
        .client-info, .address-info {
            width: 50%;
        }
        
        .client-name, .address {
            font-size: 10px;
            margin: 0;
        }
        
        .etiquette-composition-section {
            padding: 6px;
            flex-grow: 1;
        }
        
        .composition-title {
            font-size: 10px;
            font-weight: bold;
            margin: 0 0 4px 0;
        }
        
        .composition-counts {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        
        .count-item {
            border: 1px solid #000;
            padding: 2px 4px;
            width: 32%;
            text-align: center;
            border-radius: 2px;
        }
        
        .count-label {
            font-size: 8px;
            font-weight: bold;
            margin: 0;
        }
        
        .count-value {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        
        .product-references {
            margin-top: 6px;
            border-top: 1px solid #ddd;
            padding-top: 4px;
        }
        
        .references-list {
            font-size: 8px;
        }
        
        .etiquette-footer {
            background-color: #f0f0f0;
            border-top: 1px solid #000;
            padding: 4px;
            text-align: center;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <div class="etiquette-flightcase">
        <!-- En-tête -->
        <div class="etiquette-header">
            <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair.png" alt="Logo TecaLED" class="logo">
            <h2 class="etiquette-title">FICHE CHANTIER</h2>
        </div>
        
        <!-- Section référence et QR code -->
        <div class="etiquette-reference-section">
            <div class="etiquette-reference">
                <p class="label">Référence:</p>
                <p class="reference-code">
                    @if(isset($chantier->reference))
                        {{ $chantier->reference }}
                    @else
                        GMAO-{{ str_pad($chantier->id, 3, '0', STR_PAD_LEFT) }}
                    @endif
                </p>
                <p class="date-info">
                    Créé le: {{ date('d/m/Y', strtotime($chantier->created_at)) }}
                </p>
                @if(isset($chantier->deadline) && !empty($chantier->deadline))
                    <p class="deadline">
                        Butoir: {{ date('d/m/Y', strtotime($chantier->deadline)) }}
                    </p>
                @elseif(isset($chantier->date_butoir) && !empty($chantier->date_butoir))
                    <p class="deadline">
                        Butoir: {{ date('d/m/Y', strtotime($chantier->date_butoir)) }}
                    </p>
                @endif
            </div>
            <div class="etiquette-qrcode">
                <img src="{{ $qrCode }}" alt="QR Code du chantier">
            </div>
        </div>
        
        <!-- Section client et adresse -->
        <div class="etiquette-client-section">
            <div class="client-info">
                <p class="label">Client:</p>
                <p class="client-name">
                    @if(isset($chantier->client) && isset($chantier->client->name))
                        {{ $chantier->client->name }}
                    @elseif(isset($chantier->client) && isset($chantier->client->societe))
                        {{ $chantier->client->societe }}
                    @else
                        Non défini
                    @endif
                </p>
            </div>
            <div class="address-info">
                <p class="label">Adresse:</p>
                <p class="address">
                    @if(isset($chantier->adresse) && !empty($chantier->adresse))
                        {{ $chantier->adresse }}
                    @elseif(isset($chantier->address) && !empty($chantier->address))
                        {{ $chantier->address }}
                    @elseif(isset($chantier->location) && !empty($chantier->location))
                        {{ $chantier->location }}
                    @else
                        Non définie
                    @endif
                </p>
            </div>
        </div>
        
        <!-- Section composition -->
        <div class="etiquette-composition-section">
            <h3 class="composition-title">Composition</h3>
            <div class="composition-counts">
                <div class="count-item">
                    <p class="count-label">Produits</p>
                    <p class="count-value">
                        @if(isset($chantier->produits))
                            {{ $chantier->produits->count() }}
                        @else
                            0
                        @endif
                    </p>
                </div>
                <div class="count-item">
                    <p class="count-label">Dalles</p>
                    <p class="count-value">
                        @if(isset($chantier->produits))
                            {{ $chantier->produits->sum(function($produit) { 
                                return isset($produit->dalles) ? $produit->dalles->count() : 0; 
                            }) }}
                        @else
                            0
                        @endif
                    </p>
                </div>
                <div class="count-item">
                    <p class="count-label">Modules</p>
                    <p class="count-value">
                        @if(isset($chantier->produits))
                            {{ $chantier->produits->sum(function($produit) { 
                                return isset($produit->dalles) ? $produit->dalles->sum(function($dalle) { 
                                    return isset($dalle->modules) ? $dalle->modules->count() : 0; 
                                }) : 0; 
                            }) }}
                        @else
                            0
                        @endif
                    </p>
                </div>
            </div>
            
            <!-- Références produit si présentes -->
            @if(isset($chantier->produits) && $chantier->produits->count() > 0)
                <div class="product-references">
                    <p class="label">Références produit:</p>
                    <div class="references-list">
                        @foreach($chantier->produits as $produit)
                            <span class="reference-item">
                                @if(isset($produit->reference) && !empty($produit->reference))
                                    {{ $produit->reference }}
                                @elseif(isset($produit->ref) && !empty($produit->ref))
                                    {{ $produit->ref }}
                                @elseif(isset($produit->produit_reference) && !empty($produit->produit_reference))
                                    {{ $produit->produit_reference }}
                                @elseif(isset($produit->code) && !empty($produit->code))
                                    {{ $produit->code }}
                                @elseif(isset($produit->name) && !empty($produit->name))
                                    {{ $produit->name }}
                                @elseif(isset($produit->nom) && !empty($produit->nom))
                                    {{ $produit->nom }}
                                @elseif(isset($produit->product_name) && !empty($produit->product_name))
                                    {{ $produit->product_name }}
                                @elseif(isset($produit->model) && !empty($produit->model))
                                    {{ $produit->model }}
                                @else
                                    ID:{{ $produit->id }}
                                @endif
                                @if(!$loop->last), @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Pied de page -->
        <div class="etiquette-footer">
            <p>Scannez le QR code pour accéder aux détails</p>
        </div>
    </div>
</body>
</html>