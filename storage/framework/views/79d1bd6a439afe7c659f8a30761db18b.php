<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-white leading-tight">
            <?php echo e(__('Impression d\'étiquette QR Code - Chantier')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Première section: Informations et aperçu -->
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <!-- Bannière d'information -->
                    <div class="bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl mb-6">
                        <h3 class="text-lg font-medium text-blue-300 mb-2">Impression de l'étiquette</h3>
                        <p class="text-gray-300">Un QR code pour le chantier <strong><?php echo e($chantier->reference ?? 'GMAO-' . str_pad($chantier->id, 3, '0', STR_PAD_LEFT)); ?></strong> est en cours de préparation.</p>
                    </div>

                    <!-- Aperçu de l'étiquette -->
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Aperçu de l'étiquette -->
                        <div class="w-full md:w-1/2">
                            <h4 class="font-medium text-accent-blue mb-4">Aperçu de l'étiquette</h4>
                            <div class="mx-auto label-container bg-white rounded-lg border border-gray-700 p-1 w-full max-w-xs">
                                <!-- En-tête -->
                                <div class="bg-black text-white p-2 flex items-center justify-between">
                                    <!-- Logo TecaLED -->
                                    <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair.png" alt="TecaLED Logo" class="h-8">
                                    <!-- Titre -->
                                    <h1 class="text-xl font-bold text-red-500">FICHE CHANTIER</h1>
                                </div>
                                
                                <!-- Référence et QR Code -->
                                <div class="flex p-2 border-b border-gray-700">
                                    <!-- Informations de référence -->
                                    <div class="w-1/2 mr-2">
                                        <p class="font-bold text-xs text-black">Référence:</p>
                                        <p class="text-lg font-bold text-red-500">
                                            <?php echo e($chantier->reference ?? 'GMAO-' . str_pad($chantier->id, 3, '0', STR_PAD_LEFT)); ?>

                                        </p>
                                        <p class="text-xs mt-1 text-black">
                                            Créé le: <?php echo e(date('d/m/Y', strtotime($chantier->created_at))); ?>

                                        </p>
                                        <?php if(isset($chantier->deadline) || isset($chantier->date_butoir)): ?>
                                        <p class="text-xs mt-1 text-red-500 font-bold">
                                            Butoir: <?php echo e(date('d/m/Y', strtotime($chantier->deadline ?? $chantier->date_butoir))); ?>

                                        </p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- QR Code -->
                                    <div class="w-1/2 flex justify-center items-center">
                                        <div class="bg-white p-1 border border-gray-700 qr-container">
                                            <img src="<?php echo e($printData['imageData']); ?>" alt="QR Code" class="w-full max-w-[80px]">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Informations client -->
                                <div class="p-2 border-b border-gray-700">
                                    <div class="flex justify-between">
                                        <div class="w-1/2 pr-1">
                                            <p class="font-bold text-xs text-black">Client:</p>
                                            <p class="text-sm text-black">
                                                <?php echo e($chantier->client->name ?? $chantier->client->societe ?? 'Non défini'); ?>

                                            </p>
                                        </div>
                                        <div class="w-1/2 pl-1">
                                            <p class="font-bold text-xs text-black">Adresse:</p>
                                            <p class="text-xs text-black">
                                                <?php echo e($chantier->adresse ?? $chantier->address ?? $chantier->location ?? 'Non définie'); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Compteurs et composition -->
                                <div class="p-2">
                                    <h2 class="text-sm font-bold text-black mb-2">Composition</h2>
                                    <div class="flex justify-between text-center">
                                        <!-- Compteur produits -->
                                        <div class="border border-gray-700 rounded-sm p-1 w-1/3 mx-1">
                                            <p class="text-xs font-semibold text-black">Produits</p>
                                            <p class="text-lg font-bold text-black">
                                                <?php echo e(isset($chantier->produits) ? $chantier->produits->count() : 0); ?>

                                            </p>
                                        </div>
                                        
                                        <!-- Compteur dalles -->
                                        <div class="border border-gray-700 rounded-sm p-1 w-1/3 mx-1">
                                            <p class="text-xs font-semibold text-black">Dalles</p>
                                            <p class="text-lg font-bold text-black">
                                                <?php if(isset($chantier->produits)): ?>
                                                    <?php echo e($chantier->produits->sum(function($produit) { 
                                                        return isset($produit->dalles) ? $produit->dalles->count() : 0; 
                                                    })); ?>

                                                <?php else: ?>
                                                    0
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        
                                        <!-- Compteur modules -->
                                        <div class="border border-gray-700 rounded-sm p-1 w-1/3 mx-1">
                                            <p class="text-xs font-semibold text-black">Modules</p>
                                            <p class="text-lg font-bold text-black">
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
                                    
                                    <!-- Références produit si disponibles -->
                                    <?php if(isset($chantier->produits) && $chantier->produits->count() > 0): ?>
                                        <div class="mt-2 border-t border-gray-700 pt-1">
                                            <p class="text-xs font-semibold text-black">Références produit:</p>
                                            <div class="text-xs text-black">
                                                <?php $__currentLoopData = $chantier->produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="inline-block mr-1">
                                                        <?php echo e($produit->reference ?? $produit->ref ?? $produit->produit_reference ?? 
                                                          $produit->code ?? $produit->name ?? $produit->nom ?? 
                                                          $produit->product_name ?? $produit->model ?? 'ID:'.$produit->id); ?>

                                                        <?php if(!$loop->last): ?>, <?php endif; ?>
                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Pied de page avec instructions -->
                                <div class="bg-gray-200 p-1 text-center border-t border-gray-700">
                                    <p class="text-xs">Scannez le QR code pour les détails</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Détails du chantier -->
                        <div class="w-full md:w-1/2">
                            <h4 class="font-medium text-accent-purple mb-4">Détails du chantier</h4>
                            <div class="glassmorphism p-5 rounded-xl">
                                <dl class="grid grid-cols-1 gap-3">
                                    <div class="flex flex-col">
                                        <dt class="text-sm font-medium text-gray-400">Référence</dt>
                                        <dd class="text-base text-white"><?php echo e($chantier->reference ?? 'GMAO-' . str_pad($chantier->id, 3, '0', STR_PAD_LEFT)); ?></dd>
                                    </div>
                                    
                                    <div class="flex flex-col">
                                        <dt class="text-sm font-medium text-gray-400">Client</dt>
                                        <dd class="text-base text-white"><?php echo e($chantier->client->name ?? $chantier->client->societe ?? 'Non défini'); ?></dd>
                                    </div>
                                    
                                    <div class="flex flex-col">
                                        <dt class="text-sm font-medium text-gray-400">Date de création</dt>
                                        <dd class="text-base text-white"><?php echo e(date('d/m/Y', strtotime($chantier->created_at))); ?></dd>
                                    </div>
                                    
                                    <?php if(isset($chantier->deadline) || isset($chantier->date_butoir)): ?>
                                    <div class="flex flex-col">
                                        <dt class="text-sm font-medium text-gray-400">Date butoir</dt>
                                        <dd class="text-base text-white"><?php echo e(date('d/m/Y', strtotime($chantier->deadline ?? $chantier->date_butoir))); ?></dd>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="flex flex-col">
                                        <dt class="text-sm font-medium text-gray-400">Composition</dt>
                                        <dd class="text-base text-white">
                                            <?php echo e(isset($chantier->produits) ? $chantier->produits->count() : 0); ?> produits,
                                            <?php if(isset($chantier->produits)): ?>
                                                <?php echo e($chantier->produits->sum(function($produit) { return isset($produit->dalles) ? $produit->dalles->count() : 0; })); ?> dalles,
                                                <?php echo e($chantier->produits->sum(function($produit) { return isset($produit->dalles) ? $produit->dalles->sum(function($dalle) { return isset($dalle->modules) ? $dalle->modules->count() : 0; }) : 0; })); ?> modules
                                            <?php else: ?>
                                                0 dalles, 0 modules
                                            <?php endif; ?>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section de l'impression -->
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl mt-6">
                <div class="p-6">
                    <h4 class="font-medium text-accent-green mb-4">Options d'impression</h4>
                    
                    <!-- Statut et messages -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-800/30 p-4 rounded-xl">
                            <h5 class="text-sm font-medium text-gray-300 mb-2">Informations d'impression</h5>
                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-xs text-gray-400">Imprimante:</span>
                                    <span class="text-xs text-white"><?php echo e($printer->name); ?></span>
                                </div>
                                <?php if($printer->hasPrintNode()): ?>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-600 text-white">PrintNode activé</span>
                                </div>
                                <?php else: ?>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-600 text-white">PrintNode non configuré</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if(session('success')): ?>
                        <div class="bg-green-900/30 border border-green-500/30 p-4 rounded-xl">
                            <h5 class="text-sm font-medium text-green-300 mb-1">Impression réussie</h5>
                            <p class="text-xs text-green-400"><?php echo e(session('success')); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(session('error')): ?>
                        <div class="bg-red-900/30 border border-red-500/30 p-4 rounded-xl">
                            <h5 class="text-sm font-medium text-red-300 mb-1">Erreur d'impression</h5>
                            <p class="text-xs text-red-400"><?php echo e(session('error')); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="flex flex-wrap gap-4">
                        <form action="<?php echo e(route('print.qrcode.chantier', $chantier->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn-action btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Imprimer avec PrintNode
                            </button>
                        </form>
                        
                        <a href="<?php echo e(route('chantiers.show', $chantier->id)); ?>" class="btn-action btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Retour au chantier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Styles pour le QR code */
        .qr-container img {
            width: 100%;
            height: auto;
            max-width: 80px;
        }
        
        /* Styles pour l'étiquette */
        .label-container {
            max-width: 62mm; /* Largeur du rouleau Brother */
        }
        
        /* Styles d'impression */
        @media print {
            body {
                margin: 0;
                padding: 0;
                background-color: white;
            }
            
            .container {
                max-width: 62mm;
                padding: 0;
                margin: 0;
            }
            
            .label-container {
                border: none;
                box-shadow: none;
                width: 62mm;
                max-width: 62mm;
            }
            
            button, a, .btn-action {
                display: none !important;
            }
            
            /* Optimisations pour Brother */
            .qr-container {
                border: 1px solid black !important;
                background-color: white !important;
            }
            
            .qr-container img {
                max-width: 60px; /* Légèrement plus petit pour l'impression */
            }
        }

        /* Styles spécifiques pour les étiquettes Brother */
        @page {
            size: 62mm 100mm; /* Taille du rouleau Brother */
            margin: 0;
        }
    </style>

    <!-- No scripts needed for PrintNode direct submission -->
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/qrcodes/chantier/label.blade.php ENDPATH**/ ?>