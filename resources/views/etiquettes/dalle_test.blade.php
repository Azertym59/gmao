@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4">
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Test - Étiquette Dalle</h1>
        <div class="flex space-x-2">
            <button id="print-btn" onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Imprimer l'étiquette
            </button>
            <a href="{{ route('etiquettes.test') }}" class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg shadow flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Étiquette prévisualisée en format 62mm x 38mm (format dalle) -->
    <div id="etiquette-preview" class="mx-auto border border-gray-400 shadow-lg rounded bg-white" style="width: 248px; height: 152px;">
        <div class="etiquette-dalle">
            <!-- En-tête -->
            <div class="etiquette-header">
                <div class="header-left">
                    <h2 class="header-title">DALLE</h2>
                </div>
                <div class="header-right">
                    <p class="header-ref">D-TEST-01</p>
                </div>
            </div>
            
            <!-- Contenu principal en deux colonnes -->
            <div class="etiquette-content">
                <!-- Colonne gauche: infos dalle -->
                <div class="etiquette-info">
                    <div class="info-group">
                        <p class="info-label">Chantier:</p>
                        <p class="info-value">TEST-123</p>
                    </div>
                    <div class="info-group">
                        <p class="info-label">Dimension:</p>
                        <p class="info-value">2×2</p>
                    </div>
                    <div class="info-group">
                        <p class="info-label">Modules:</p>
                        <p class="info-value">4</p>
                    </div>
                    <div class="info-group">
                        <p class="info-label">Produit:</p>
                        <p class="info-value">P6 Indoor</p>
                    </div>
                </div>
                
                <!-- Colonne droite: QR code -->
                <div class="etiquette-qrcode">
                    <img src="{{ $qrCode }}" alt="QR Code Dalle">
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6 text-center max-w-md mx-auto">
        <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Cliquez sur le bouton "Imprimer l'étiquette" pour envoyer directement vers l'imprimante. Cette étiquette utilise le format 38mm de hauteur.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour l'étiquette dalle */
.etiquette-dalle {
    width: 100%;
    height: 100%;
    font-family: Arial, sans-serif;
    color: #000;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid #000;
}

.etiquette-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #000;
    padding: 4px 6px;
}

.header-title {
    font-size: 12px;
    font-weight: bold;
    margin: 0;
    color: #fff;
    text-transform: uppercase;
}

.header-ref {
    font-size: 10px;
    font-weight: bold;
    margin: 0;
    color: #fff;
}

.etiquette-content {
    display: flex;
    flex-grow: 1;
}

.etiquette-info {
    width: 60%;
    padding: 6px;
    border-right: 1px solid #000;
}

.info-group {
    margin-bottom: 3px;
}

.info-label {
    font-size: 7px;
    font-weight: bold;
    margin: 0;
    color: #666;
}

.info-value {
    font-size: 9px;
    font-weight: bold;
    margin: 0;
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
    max-width: 90px;
}

/* Styles d'impression */
@media print {
    body {
        margin: 0;
        padding: 0;
        background: white;
    }
    
    .container {
        width: 62mm !important;
        max-width: 62mm !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    #etiquette-preview {
        width: 62mm !important;
        height: 38mm !important;
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        page-break-after: always;
    }
    
    /* Masquer les éléments inutiles à l'impression */
    .mb-4, .mt-6, button, a {
        display: none !important;
    }
    
    /* Assurer que les couleurs sont correctement imprimées */
    .etiquette-header {
        background-color: #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

/* Réglages spécifiques pour les imprimantes Brother à étiquettes */
@page {
    size: 62mm 38mm;
    margin: 0;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ajouter des instructions à l'impression
        const printBtn = document.getElementById('print-btn');
        if (printBtn) {
            printBtn.addEventListener('click', function() {
                // Permettre un petit délai pour s'assurer que tout est bien chargé
                setTimeout(function() {
                    window.print();
                }, 100);
            });
        }
    });
</script>
@endsection