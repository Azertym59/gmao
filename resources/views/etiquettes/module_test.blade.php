<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test - Étiquette Module</title>
    <style>
        /* Réinitialisation pour l'impression */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            width: 62mm;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: white;
        }
        
        .print-only {
            display: none;
        }
        
        .preview-container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .controls {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        
        .btn-print {
            background-color: #3182ce;
            color: white;
        }
        
        .btn-back {
            background-color: #4a5568;
            color: white;
        }
        
        .btn svg {
            width: 20px;
            height: 20px;
            margin-right: 8px;
        }
        
        .etiquette-container {
            width: 248px;
            height: 116px;
            border: 1px solid #ccc;
            margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .instructions {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff8e1;
            border-left: 4px solid #ffb300;
            border-radius: 5px;
        }
        
        /* Styles pour l'étiquette module */
        .etiquette-module {
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
            color: #000;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid #000;
        }
        
        .etiquette-content {
            display: flex;
            flex-grow: 1;
        }
        
        .etiquette-info {
            width: 60%;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #000;
        }
        
        .etiquette-header {
            background-color: #000;
            padding: 3px;
            text-align: center;
        }
        
        .header-title {
            font-size: 10px;
            font-weight: bold;
            margin: 0;
            color: #fff;
            text-transform: uppercase;
        }
        
        .module-details {
            padding: 4px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .module-id, .module-position, .module-resolution, .module-dalle {
            font-size: 8px;
            margin: 0 0 2px 0;
        }
        
        .module-id {
            font-weight: bold;
        }
        
        .etiquette-qrcode {
            width: 40%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4px;
        }
        
        .etiquette-qrcode img {
            width: 100%;
            height: auto;
        }
        
        /* Styles d'impression */
        @media print {
            @page {
                size: 62mm 29mm;
                margin: 0;
            }
            
            html, body {
                width: 62mm;
                height: 29mm;
                margin: 0;
                padding: 0;
            }
            
            .preview-container, .controls, .instructions {
                display: none !important;
            }
            
            .print-only {
                display: block;
                width: 62mm;
                height: 29mm;
            }
            
            .etiquette-container {
                width: 62mm !important;
                height: 29mm !important;
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
                overflow: visible !important;
            }
            
            /* Assurer que les couleurs sont correctement imprimées */
            .etiquette-header {
                background-color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Version pour l'écran uniquement -->
    <div class="preview-container">
        <div class="controls">
            <h1>Test - Étiquette Module</h1>
            <div>
                <button onclick="window.print()" class="btn btn-print">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimer l'étiquette
                </button>
                <a href="{{ route('etiquettes.test') }}" class="btn btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>
            </div>
        </div>
        
        <!-- Aperçu de l'étiquette -->
        <div class="etiquette-container">
            <div class="etiquette-module">
                <!-- Contenu de l'étiquette en deux colonnes -->
                <div class="etiquette-content">
                    <!-- Colonne gauche: infos module -->
                    <div class="etiquette-info">
                        <div class="etiquette-header">
                            <h2 class="header-title">MODULE</h2>
                        </div>
                        <div class="module-details">
                            <p class="module-id">ID: M-TEST-01</p>
                            <p class="module-position">Position: X1-Y3</p>
                            <p class="module-resolution">100×100px</p>
                            <p class="module-dalle">Dalle: D-TEST-01</p>
                        </div>
                    </div>
                    
                    <!-- Colonne droite: QR code -->
                    <div class="etiquette-qrcode">
                        <img src="{{ $qrCode }}" alt="QR Code Module">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="instructions">
            <h3>Instructions d'impression</h3>
            <ol>
                <li>Cliquez sur le bouton "Imprimer l'étiquette"</li>
                <li>Dans les options d'impression, sélectionnez votre imprimante d'étiquettes</li>
                <li>Paramètres importants:
                    <ul>
                        <li>Format: <strong>62mm × 29mm</strong> (ou le plus proche disponible)</li>
                        <li>Marges: <strong>Aucune</strong></li>
                        <li>Mise à l'échelle: <strong>100%</strong> (ne pas ajuster à la page)</li>
                    </ul>
                </li>
            </ol>
        </div>
    </div>
    
    <!-- Version pure pour l'impression, sans éléments d'interface -->
    <div class="print-only">
        <div class="etiquette-container">
            <div class="etiquette-module">
                <!-- Contenu de l'étiquette en deux colonnes -->
                <div class="etiquette-content">
                    <!-- Colonne gauche: infos module -->
                    <div class="etiquette-info">
                        <div class="etiquette-header">
                            <h2 class="header-title">MODULE</h2>
                        </div>
                        <div class="module-details">
                            <p class="module-id">ID: M-TEST-01</p>
                            <p class="module-position">Position: X1-Y3</p>
                            <p class="module-resolution">100×100px</p>
                            <p class="module-dalle">Dalle: D-TEST-01</p>
                        </div>
                    </div>
                    
                    <!-- Colonne droite: QR code -->
                    <div class="etiquette-qrcode">
                        <img src="{{ $qrCode }}" alt="QR Code Module">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Si vous voulez que la boîte de dialogue d'impression s'ouvre automatiquement
            // window.print();
        });
    </script>
</body>
</html>