<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étiquette Flightcase</title>
    <style>
        @page {
            size: 62mm 90mm;
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            width: 62mm;
            height: 90mm;
        }
        .etiquette {
            width: 62mm;
            height: 90mm;
            padding: 2mm;
            box-sizing: border-box;
            overflow: hidden;
            position: relative;
        }
        .header {
            text-align: center;
            padding-bottom: 1mm;
            border-bottom: 1pt solid #000;
            margin-bottom: 2mm;
        }
        .logo {
            height: 8mm;
            margin-bottom: 1mm;
        }
        .company {
            font-size: 10pt;
            font-weight: bold;
            margin: 0;
        }
        .reference {
            font-size: 12pt;
            font-weight: bold;
            color: #cc0000;
            margin: 1mm 0;
        }
        .client {
            font-size: 10pt;
            margin: 1mm 0;
        }
        .flightcase-info {
            border: 1pt solid #000;
            padding: 1mm;
            margin: 2mm 0;
            font-size: 10pt;
            font-weight: bold;
            text-align: center;
        }
        .qrcode-container {
            text-align: center;
            margin: 3mm 0;
        }
        .qrcode {
            width: 28mm;
            height: 28mm;
        }
        .remarque {
            border: 1pt solid #000;
            padding: 1mm;
            margin-top: 2mm;
            min-height: 15mm;
        }
        .remarque-label {
            font-size: 8pt;
            font-weight: bold;
            margin-bottom: 1mm;
        }
        .dalles-info {
            font-size: 9pt;
            margin: 2mm 0;
        }
        .footer {
            position: absolute;
            bottom: 2mm;
            left: 2mm;
            right: 2mm;
            font-size: 7pt;
            text-align: center;
            color: #666;
        }
        
        @media print {
            html, body {
                width: 62mm;
                height: 90mm;
                margin: 0;
                padding: 0;
            }
            .no-print { 
                display: none; 
            }
        }
    </style>
</head>
<body>
    <div class="etiquette">
        <div class="header">
            <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair%20new.png" alt="TecaLED" class="logo">
            <p class="company">TECALED GMAO</p>
        </div>
        
        <p class="reference">{{ $chantier->reference }}</p>
        <p class="client">{{ $chantier->client->societe }}</p>
        
        <div class="flightcase-info">
            FLIGHTCASE {{ $flightcaseNumber }}/{{ $totalFlightcases }}
        </div>
        
        <p class="dalles-info">Contient {{ $nbDalles }} dalle(s)</p>
        
        <div class="qrcode-container">
            <img src="{{ $qrCode }}" alt="QR Code" class="qrcode">
        </div>
        
        <div class="remarque">
            <div class="remarque-label">REMARQUES:</div>
        </div>
        
        <div class="footer">
            Scan QR code pour accès au chantier - {{ date('d/m/Y') }}
        </div>
    </div>
</body>
</html>