<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Étiquette d'impression pour chantier GMAO TecaLED">
    <meta name="print-dimension" content="62mm x 100mm">
    <meta name="print-type" content="label-continuous-roll-dk22205">
    <title>Étiquette Flightcase - Chantier <?php echo e($chantier->id); ?></title>
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
            height: 400px;
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
        
        .browser-tip {
            margin-top: 10px;
            padding: 8px;
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            border-radius: 4px;
        }
        
        /* Style pour les boutons "Télécharger PDF" */
        .btn-download {
            background-color: #4caf50; 
            color: white;
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
        
        /* Styles d'impression */
        @media print {
            /* Définition de la taille d'impression pour Brother DK-22205 */
            @page {
                size: 62mm 100mm;
                margin: 0;
            }
            
            html, body {
                width: 62mm;
                height: 100mm;
                margin: 0;
                padding: 0;
            }
            
            .preview-container, .controls, .instructions {
                display: none !important;
            }
            
            .print-only {
                display: block;
                width: 62mm;
                height: 100mm;
            }
            
            .etiquette-container {
                width: 62mm !important;
                height: 100mm !important;
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
                overflow: visible !important;
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
    </style>
</head>
<body>
    <!-- Version pour l'écran uniquement -->
    <div class="preview-container">
        <div class="controls">
            <h1>Étiquette Flightcase - Chantier</h1>
            <div>
                <button class="btn btn-print">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimer l'étiquette
                </button>
                <button id="create-pdf" class="btn btn-download">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Télécharger PDF
                </button>
                <button id="brother-print-btn" class="btn shadow-lg transform hover:scale-105 transition-all duration-150" style="background-color: #b01e8e; color: white; font-weight: bold; padding: 10px 15px; border: 1px solid #9e1b7f; box-shadow: 0 4px 6px rgba(176, 30, 142, 0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20" class="inline-block mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                    Imprimer Brother
                </button>
                <a href="<?php echo e(route('chantiers.show', $chantier->id)); ?>" class="btn btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>
            </div>
        </div>
        
        <!-- Aperçu de l'étiquette -->
        <div class="etiquette-container">
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
                        <p class="reference-code">
                            <?php if(isset($chantier->reference)): ?>
                                <?php echo e($chantier->reference); ?>

                            <?php else: ?>
                                GMAO-<?php echo e(str_pad($chantier->id, 3, '0', STR_PAD_LEFT)); ?>

                            <?php endif; ?>
                        </p>
                        <p class="date-info">
                            Créé le: <?php echo e(date('d/m/Y', strtotime($chantier->created_at))); ?>

                        </p>
                        <?php if(isset($chantier->deadline) && !empty($chantier->deadline)): ?>
                            <p class="deadline">
                                Butoir: <?php echo e(date('d/m/Y', strtotime($chantier->deadline))); ?>

                            </p>
                        <?php elseif(isset($chantier->date_butoir) && !empty($chantier->date_butoir)): ?>
                            <p class="deadline">
                                Butoir: <?php echo e(date('d/m/Y', strtotime($chantier->date_butoir))); ?>

                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="etiquette-qrcode">
                        <img src="<?php echo e($qrCode); ?>" alt="QR Code du chantier">
                    </div>
                </div>
                
                <!-- Section client et adresse -->
                <div class="etiquette-client-section">
                    <div class="client-info">
                        <p class="label">Client:</p>
                        <p class="client-name">
                            <?php if(isset($chantier->client) && isset($chantier->client->name)): ?>
                                <?php echo e($chantier->client->name); ?>

                            <?php elseif(isset($chantier->client) && isset($chantier->client->societe)): ?>
                                <?php echo e($chantier->client->societe); ?>

                            <?php else: ?>
                                Non défini
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="address-info">
                        <p class="label">Adresse:</p>
                        <p class="address">
                            <?php if(isset($chantier->adresse) && !empty($chantier->adresse)): ?>
                                <?php echo e($chantier->adresse); ?>

                            <?php elseif(isset($chantier->address) && !empty($chantier->address)): ?>
                                <?php echo e($chantier->address); ?>

                            <?php elseif(isset($chantier->location) && !empty($chantier->location)): ?>
                                <?php echo e($chantier->location); ?>

                            <?php else: ?>
                                Non définie
                            <?php endif; ?>
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
                                <?php if(isset($chantier->produits)): ?>
                                    <?php echo e($chantier->produits->count()); ?>

                                <?php else: ?>
                                    0
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="count-item">
                            <p class="count-label">Dalles</p>
                            <p class="count-value">
                                <?php if(isset($chantier->produits)): ?>
                                    <?php echo e($chantier->produits->sum(function($produit) { 
                                        return isset($produit->dalles) ? $produit->dalles->count() : 0; 
                                    })); ?>

                                <?php else: ?>
                                    0
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="count-item">
                            <p class="count-label">Modules</p>
                            <p class="count-value">
                                <?php if(isset($chantier->produits)): ?>
                                    <?php echo e($chantier->produits->sum(function($produit) { 
                                        return isset($produit->dalles) ? $produit->dalles->sum(function($dalle) { 
                                            return isset($dalle->modules) ? $dalle->modules->count() : 0; 
                                        }) : 0; 
                                    })); ?>

                                <?php else: ?>
                                    0
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Références produit si présentes -->
                    <?php if(isset($chantier->produits) && $chantier->produits->count() > 0): ?>
                        <div class="product-references">
                            <p class="label">Références produit:</p>
                            <div class="references-list">
                                <?php $__currentLoopData = $chantier->produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="reference-item">
                                        <?php if(isset($produit->reference) && !empty($produit->reference)): ?>
                                            <?php echo e($produit->reference); ?>

                                        <?php elseif(isset($produit->ref) && !empty($produit->ref)): ?>
                                            <?php echo e($produit->ref); ?>

                                        <?php elseif(isset($produit->produit_reference) && !empty($produit->produit_reference)): ?>
                                            <?php echo e($produit->produit_reference); ?>

                                        <?php elseif(isset($produit->code) && !empty($produit->code)): ?>
                                            <?php echo e($produit->code); ?>

                                        <?php elseif(isset($produit->name) && !empty($produit->name)): ?>
                                            <?php echo e($produit->name); ?>

                                        <?php elseif(isset($produit->nom) && !empty($produit->nom)): ?>
                                            <?php echo e($produit->nom); ?>

                                        <?php elseif(isset($produit->product_name) && !empty($produit->product_name)): ?>
                                            <?php echo e($produit->product_name); ?>

                                        <?php elseif(isset($produit->model) && !empty($produit->model)): ?>
                                            <?php echo e($produit->model); ?>

                                        <?php else: ?>
                                            ID:<?php echo e($produit->id); ?>

                                        <?php endif; ?>
                                        <?php if(!$loop->last): ?>, <?php endif; ?>
                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Pied de page -->
                <div class="etiquette-footer">
                    <p>Scannez le QR code pour accéder aux détails</p>
                </div>
            </div>
        </div>
        
        <div class="instructions">
            <h3>Instructions d'impression</h3>
            <ol>
                <li>Cliquez sur le bouton "Imprimer l'étiquette"</li>
                <li>Dans les options d'impression, sélectionnez votre imprimante d'étiquettes Brother</li>
                <li>Paramètres importants:
                    <ul>
                        <li>Format papier: <strong>Rouleau continu 62mm</strong> (DK-22205)</li>
                        <li>Longueur étiquette: <strong>100mm</strong></li>
                        <li>Marges: <strong>Aucune</strong></li>
                        <li>Mise à l'échelle: <strong>100%</strong> (ne pas ajuster à la page)</li>
                        <li>Désactivez les "En-têtes et pieds de page"</li>
                    </ul>
                </li>
                <li>Pour imprimantes Brother spécifiquement:
                    <ul>
                        <li>Dans "Propriétés de l'imprimante", sélectionnez "Options d'impression avancées"</li>
                        <li>Type de papier: <strong>Rouleau continu</strong></li>
                        <li>Type de support: <strong>Standard</strong></li>
                        <li>Qualité: <strong>Standard</strong></li>
                        <li>Cochez l'option "Rouleau continu" si disponible</li>
                    </ul>
                </li>
            </ol>
            
            <p style="margin-top: 10px; font-weight: bold;">Problèmes connus:</p>
            <ul>
                <li>Message "Type de rouleau incorrect": Dans les propriétés de l'imprimante, vérifiez que vous avez choisi "Rouleau continu" et non une étiquette prédécoupée</li>
                <li>Impression trop grande: Vérifiez que la mise à l'échelle est à 100% et non "Ajuster à la page"</li>
                <li>Impression tronquée: Augmentez manuellement la longueur de coupe à 100mm</li>
            </ul>
            
            <div style="margin-top: 10px; padding: 8px; background-color: #fadcf1; border-left: 4px solid #b01e8e; border-radius: 4px;">
                <p><strong>Recommandation pour Brother:</strong> Utilisez le bouton P-touch ci-dessus pour télécharger l'image QR code, puis créez votre modèle dans P-touch Editor avec le format de votre choix.</p>
            </div>
        </div>
    </div>
    
    <!-- Version pure pour l'impression, sans éléments d'interface -->
    <div class="print-only">
        <div class="etiquette-container">
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
                        <p class="reference-code">
                            <?php if(isset($chantier->reference)): ?>
                                <?php echo e($chantier->reference); ?>

                            <?php else: ?>
                                GMAO-<?php echo e(str_pad($chantier->id, 3, '0', STR_PAD_LEFT)); ?>

                            <?php endif; ?>
                        </p>
                        <p class="date-info">
                            Créé le: <?php echo e(date('d/m/Y', strtotime($chantier->created_at))); ?>

                        </p>
                        <?php if(isset($chantier->deadline) && !empty($chantier->deadline)): ?>
                            <p class="deadline">
                                Butoir: <?php echo e(date('d/m/Y', strtotime($chantier->deadline))); ?>

                            </p>
                        <?php elseif(isset($chantier->date_butoir) && !empty($chantier->date_butoir)): ?>
                            <p class="deadline">
                                Butoir: <?php echo e(date('d/m/Y', strtotime($chantier->date_butoir))); ?>

                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="etiquette-qrcode">
                        <img src="<?php echo e($qrCode); ?>" alt="QR Code du chantier">
                    </div>
                </div>
                
                <!-- Section client et adresse -->
                <div class="etiquette-client-section">
                    <div class="client-info">
                        <p class="label">Client:</p>
                        <p class="client-name">
                            <?php if(isset($chantier->client) && isset($chantier->client->name)): ?>
                                <?php echo e($chantier->client->name); ?>

                            <?php elseif(isset($chantier->client) && isset($chantier->client->societe)): ?>
                                <?php echo e($chantier->client->societe); ?>

                            <?php else: ?>
                                Non défini
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="address-info">
                        <p class="label">Adresse:</p>
                        <p class="address">
                            <?php if(isset($chantier->adresse) && !empty($chantier->adresse)): ?>
                                <?php echo e($chantier->adresse); ?>

                            <?php elseif(isset($chantier->address) && !empty($chantier->address)): ?>
                                <?php echo e($chantier->address); ?>

                            <?php elseif(isset($chantier->location) && !empty($chantier->location)): ?>
                                <?php echo e($chantier->location); ?>

                            <?php else: ?>
                                Non définie
                            <?php endif; ?>
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
                                <?php if(isset($chantier->produits)): ?>
                                    <?php echo e($chantier->produits->count()); ?>

                                <?php else: ?>
                                    0
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="count-item">
                            <p class="count-label">Dalles</p>
                            <p class="count-value">
                                <?php if(isset($chantier->produits)): ?>
                                    <?php echo e($chantier->produits->sum(function($produit) { 
                                        return isset($produit->dalles) ? $produit->dalles->count() : 0; 
                                    })); ?>

                                <?php else: ?>
                                    0
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="count-item">
                            <p class="count-label">Modules</p>
                            <p class="count-value">
                                <?php if(isset($chantier->produits)): ?>
                                    <?php echo e($chantier->produits->sum(function($produit) { 
                                        return isset($produit->dalles) ? $produit->dalles->sum(function($dalle) { 
                                            return isset($dalle->modules) ? $dalle->modules->count() : 0; 
                                        }) : 0; 
                                    })); ?>

                                <?php else: ?>
                                    0
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Références produit si présentes -->
                    <?php if(isset($chantier->produits) && $chantier->produits->count() > 0): ?>
                        <div class="product-references">
                            <p class="label">Références produit:</p>
                            <div class="references-list">
                                <?php $__currentLoopData = $chantier->produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="reference-item">
                                        <?php if(isset($produit->reference) && !empty($produit->reference)): ?>
                                            <?php echo e($produit->reference); ?>

                                        <?php elseif(isset($produit->ref) && !empty($produit->ref)): ?>
                                            <?php echo e($produit->ref); ?>

                                        <?php elseif(isset($produit->produit_reference) && !empty($produit->produit_reference)): ?>
                                            <?php echo e($produit->produit_reference); ?>

                                        <?php elseif(isset($produit->code) && !empty($produit->code)): ?>
                                            <?php echo e($produit->code); ?>

                                        <?php elseif(isset($produit->name) && !empty($produit->name)): ?>
                                            <?php echo e($produit->name); ?>

                                        <?php elseif(isset($produit->nom) && !empty($produit->nom)): ?>
                                            <?php echo e($produit->nom); ?>

                                        <?php elseif(isset($produit->product_name) && !empty($produit->product_name)): ?>
                                            <?php echo e($produit->product_name); ?>

                                        <?php elseif(isset($produit->model) && !empty($produit->model)): ?>
                                            <?php echo e($produit->model); ?>

                                        <?php else: ?>
                                            ID:<?php echo e($produit->id); ?>

                                        <?php endif; ?>
                                        <?php if(!$loop->last): ?>, <?php endif; ?>
                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Pied de page -->
                <div class="etiquette-footer">
                    <p>Scannez le QR code pour accéder aux détails</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Inclure jsPDF pour la génération de PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configurez les boutons
            const printButton = document.querySelector('.btn-print');
            const pdfButton = document.getElementById('create-pdf');
            const brotherPrintButton = document.getElementById('brother-print-btn');
            
            if (printButton) {
                printButton.addEventListener('click', function() {
                    // Attendez que tout soit chargé avant d'imprimer
                    setTimeout(printLabel, 300);
                });
            }
            
            if (pdfButton) {
                pdfButton.addEventListener('click', function() {
                    createAndDownloadPDF();
                });
            }
            
            if (brotherPrintButton) {
                brotherPrintButton.addEventListener('click', function() {
                    printBrother();
                });
            }
            
            // Fonction d'impression optimisée pour l'étiquette
            function printLabel() {
                try {
                    // Configurer l'impression avant d'ouvrir la boîte de dialogue
                    const printSettings = {
                        mediaSize: { 
                            width: 62, 
                            height: 100,
                            unit: 'mm'
                        },
                        margins: { 
                            top: 0, 
                            right: 0, 
                            bottom: 0, 
                            left: 0, 
                            unit: 'mm'
                        },
                        shouldPrintBackgrounds: true,
                        shouldPrintSelectionOnly: false
                    };
                    
                    // Essayer de configurer l'impression si le navigateur prend en charge printSettings
                    if (window.print && window.matchMedia) {
                        console.log("Configuration des paramètres d'impression...");
                        const mediaQueryList = window.matchMedia('print');
                        mediaQueryList.addEventListener('change', function(mql) {
                            if (!mql.matches) {
                                console.log("Impression terminée ou annulée");
                            }
                        });
                    }
                    
                    // Ouvrir la boîte de dialogue d'impression
                    window.print();
                } catch (error) {
                    console.error("Erreur lors de l'impression:", error);
                    // Fallback au cas où les paramètres avancés échouent
                    window.print();
                }
            }
            
            // Fonction pour créer et télécharger un PDF
            function createAndDownloadPDF() {
                // Afficher un indicateur de chargement
                const createStatus = document.createElement('div');
                createStatus.style.position = 'fixed';
                createStatus.style.top = '50%';
                createStatus.style.left = '50%';
                createStatus.style.transform = 'translate(-50%, -50%)';
                createStatus.style.padding = '20px';
                createStatus.style.backgroundColor = 'rgba(0,0,0,0.8)';
                createStatus.style.color = 'white';
                createStatus.style.borderRadius = '10px';
                createStatus.style.zIndex = '9999';
                createStatus.textContent = 'Création du PDF en cours...';
                document.body.appendChild(createStatus);
                
                // Cibler l'élément à exporter
                const element = document.querySelector('.print-only .etiquette-container');
                
                // Utiliser html2canvas et jsPDF
                try {
                    const { jsPDF } = window.jspdf;
                    
                    // Convertir l'élément en canvas
                    html2canvas(element, {
                        scale: 2, // Meilleure qualité
                        useCORS: true,
                        logging: false
                    }).then(canvas => {
                        // Créer un nouveau PDF A6 (105 x 148mm)
                        const pdf = new jsPDF({
                            orientation: 'portrait',
                            unit: 'mm',
                            format: [62, 100] // Format d'étiquette 62mm x 100mm
                        });
                        
                        // Ajouter l'image du canvas au PDF
                        const imgData = canvas.toDataURL('image/png');
                        pdf.addImage(imgData, 'PNG', 0, 0, 62, 100);
                        
                        // Télécharger le PDF
                        pdf.save('etiquette-chantier-<?php echo e($chantier->id); ?>.pdf');
                        
                        // Supprimer l'indicateur de chargement
                        document.body.removeChild(createStatus);
                    });
                } catch (error) {
                    console.error("Erreur lors de la création du PDF:", error);
                    createStatus.textContent = 'Erreur: ' + error.message;
                    setTimeout(() => {
                        document.body.removeChild(createStatus);
                    }, 3000);
                }
            }
            
            // Fonction pour imprimer directement via l'imprimante Brother
            function printBrother() {
                // Afficher un indicateur de chargement
                const loadingIndicator = document.createElement('div');
                loadingIndicator.style.position = 'fixed';
                loadingIndicator.style.top = '50%';
                loadingIndicator.style.left = '50%';
                loadingIndicator.style.transform = 'translate(-50%, -50%)';
                loadingIndicator.style.padding = '20px';
                loadingIndicator.style.backgroundColor = 'rgba(0,0,0,0.8)';
                loadingIndicator.style.color = 'white';
                loadingIndicator.style.borderRadius = '10px';
                loadingIndicator.style.zIndex = '9999';
                loadingIndicator.textContent = 'Envoi vers l\'imprimante Brother...';
                loadingIndicator.style.fontWeight = 'bold';
                loadingIndicator.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" class="animate-pulse" style="display: inline-block; margin-bottom: 10px;" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg><div>Envoi vers l\'imprimante Brother...</div></div>';
                document.body.appendChild(loadingIndicator);
                
                // Envoyer la requête à l'API
                fetch('<?php echo e(route("etiquettes.chantier.ptouch", $chantier->id)); ?>', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Supprimer l'indicateur de chargement
                    document.body.removeChild(loadingIndicator);
                    
                    // Afficher le message de succès ou d'erreur
                    const messageBox = document.createElement('div');
                    messageBox.style.position = 'fixed';
                    messageBox.style.top = '50%';
                    messageBox.style.left = '50%';
                    messageBox.style.transform = 'translate(-50%, -50%)';
                    messageBox.style.padding = '20px';
                    messageBox.style.borderRadius = '10px';
                    messageBox.style.zIndex = '9999';
                    
                    if (data.success) {
                        messageBox.style.backgroundColor = 'rgba(46, 125, 50, 0.9)';
                        messageBox.style.color = 'white';
                        messageBox.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
                        messageBox.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-bottom: 10px;" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg><div>Étiquette envoyée avec succès à l\'imprimante ' + data.printer + '</div></div>';
                    } else {
                        messageBox.style.backgroundColor = 'rgba(198, 40, 40, 0.9)';
                        messageBox.style.color = 'white';
                        messageBox.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
                        messageBox.innerHTML = '<div style="text-align: center;"><svg xmlns="http://www.w3.org/2000/svg" style="display: inline-block; margin-bottom: 10px;" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg><div>Erreur: ' + data.message + '</div></div>';
                    }
                    
                    document.body.appendChild(messageBox);
                    
                    // Supprimer le message après 5 secondes
                    setTimeout(() => {
                        document.body.removeChild(messageBox);
                    }, 5000);
                })
                .catch(error => {
                    // Supprimer l'indicateur de chargement
                    document.body.removeChild(loadingIndicator);
                    
                    // Afficher le message d'erreur
                    const errorBox = document.createElement('div');
                    errorBox.style.position = 'fixed';
                    errorBox.style.top = '50%';
                    errorBox.style.left = '50%';
                    errorBox.style.transform = 'translate(-50%, -50%)';
                    errorBox.style.padding = '20px';
                    errorBox.style.backgroundColor = 'rgba(198, 40, 40, 0.9)';
                    errorBox.style.color = 'white';
                    errorBox.style.borderRadius = '10px';
                    errorBox.style.zIndex = '9999';
                    errorBox.textContent = 'Erreur de connexion: ' + error.message;
                    
                    document.body.appendChild(errorBox);
                    
                    // Supprimer le message après 3 secondes
                    setTimeout(() => {
                        document.body.removeChild(errorBox);
                    }, 3000);
                });
            }
            
            // Pour les imprimantes spécifiques, ajouter des conseils
            const browserInfo = navigator.userAgent;
            const instructionsDiv = document.querySelector('.instructions');
            
            if (instructionsDiv) {
                // Ajouter un conseil sur l'impression Brother directe
                const brotherTip = document.createElement('div');
                brotherTip.className = 'browser-tip';
                brotherTip.style.backgroundColor = '#f9e6f4';
                brotherTip.style.borderLeftColor = '#b01e8e';
                brotherTip.innerHTML = '<p><strong>RECOMMANDÉ:</strong> Utilisez le bouton "Imprimer Brother" pour envoyer directement l\'étiquette vers votre imprimante Brother sans manipulation supplémentaire. Cette solution est optimisée pour les rouleaux DK-22205 (62mm).</p>';
                instructionsDiv.appendChild(brotherTip);
                
                // Ajouter un conseil sur l'alternative PDF
                const pdfTip = document.createElement('div');
                pdfTip.className = 'browser-tip';
                pdfTip.innerHTML = '<p><strong>Alternative:</strong> Utilisez le bouton "Télécharger PDF" et imprimez le PDF généré avec un logiciel comme Adobe Reader pour une meilleure compatibilité avec votre imprimante.</p>';
                instructionsDiv.appendChild(pdfTip);
                
                if (browserInfo.includes('Chrome')) {
                    const chromeSpecific = document.createElement('div');
                    chromeSpecific.className = 'browser-tip';
                    chromeSpecific.innerHTML = '<p><strong>Conseil pour Chrome:</strong> Dans la boîte de dialogue d\'impression, cliquez sur "Plus de paramètres" et définissez "Échelle" à 100% et "Marges" à "Aucune".</p>';
                    instructionsDiv.appendChild(chromeSpecific);
                } else if (browserInfo.includes('Firefox')) {
                    const firefoxSpecific = document.createElement('div');
                    firefoxSpecific.className = 'browser-tip';
                    firefoxSpecific.innerHTML = '<p><strong>Conseil pour Firefox:</strong> Dans la boîte de dialogue d\'impression, sélectionnez "Format et options" puis assurez-vous que "Ajuster à la page" est décoché.</p>';
                    instructionsDiv.appendChild(firefoxSpecific);
                }
            }
        });
    </script>
</body>
</html><?php /**PATH /var/www/gmao/resources/views/etiquettes/chantier.blade.php ENDPATH**/ ?>