<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-4">
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Test - Étiquette Flightcase</h1>
        <div class="flex space-x-2">
            <button id="print-btn" onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Imprimer l'étiquette
            </button>
            <a href="<?php echo e(route('etiquettes.test')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg shadow flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Étiquette prévisualisée en format 62mm x 100mm (format flightcase) -->
    <div id="etiquette-preview" class="mx-auto border border-gray-400 shadow-lg rounded bg-white" style="width: 248px; height: 400px;">
        <div class="etiquette-flightcase">
            <!-- En-tête -->
            <div class="etiquette-header">
                <img src="<?php echo e(asset('images/Logo rectangle flag.png')); ?>" alt="Logo TecaLED" class="logo">
                <h2 class="etiquette-title">FICHE CHANTIER</h2>
            </div>
            
            <!-- Section référence et QR code -->
            <div class="etiquette-reference-section">
                <div class="etiquette-reference">
                    <p class="label">Référence:</p>
                    <p class="reference-code">TEST-123</p>
                    <p class="date-info">
                        Créé le: <?php echo e(date('d/m/Y')); ?>

                    </p>
                    <p class="deadline">
                        Butoir: <?php echo e(date('d/m/Y', strtotime('+15 days'))); ?>

                    </p>
                </div>
                <div class="etiquette-qrcode">
                    <img src="<?php echo e($qrCode); ?>" alt="QR Code du chantier">
                </div>
            </div>
            
            <!-- Section client et adresse -->
            <div class="etiquette-client-section">
                <div class="client-info">
                    <p class="label">Client:</p>
                    <p class="client-name">TECALED DEMO</p>
                </div>
                <div class="address-info">
                    <p class="label">Adresse:</p>
                    <p class="address">123 Avenue Test, 75000 Paris</p>
                </div>
            </div>
            
            <!-- Section composition -->
            <div class="etiquette-composition-section">
                <h3 class="composition-title">Composition</h3>
                <div class="composition-counts">
                    <div class="count-item">
                        <p class="count-label">Produits</p>
                        <p class="count-value">2</p>
                    </div>
                    <div class="count-item">
                        <p class="count-label">Dalles</p>
                        <p class="count-value">8</p>
                    </div>
                    <div class="count-item">
                        <p class="count-label">Modules</p>
                        <p class="count-value">64</p>
                    </div>
                </div>
                
                <!-- Références produit pour le test -->
                <div class="product-references">
                    <p class="label">Références produit:</p>
                    <div class="references-list">
                        <span class="reference-item">PROD-A-2025, PROD-B-2025</span>
                    </div>
                </div>
            </div>
            
            <!-- Pied de page -->
            <div class="etiquette-footer">
                <p>Scannez le QR code pour accéder aux détails</p>
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
                        Cliquez sur le bouton "Imprimer l'étiquette" pour envoyer directement vers l'imprimante. Assurez-vous que l'imprimante est configurée pour le format 62mm de largeur.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour l'étiquette flightcase */
.etiquette-flightcase {
    width: 100%;
    height: 100%;
    font-family: Arial, sans-serif;
    color: #000;
    display: flex;
    flex-direction: column;
    overflow: hidden;
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
        height: 100mm !important;
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
    .reference-code, .deadline {
        color: #f00 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .etiquette-header {
        background-color: #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .etiquette-footer {
        background-color: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

/* Réglages spécifiques pour les imprimantes Brother à étiquettes */
@page {
    size: 62mm 100mm;
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/gmao/resources/views/etiquettes/flightcase_test.blade.php ENDPATH**/ ?>