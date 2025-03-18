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
        <h1 class="text-2xl font-bold text-white leading-tight">
            <?php echo e(__('Créer un nouveau projet')); ?>

        </h1>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-xl font-semibold text-white mb-2">Quel type de projet souhaitez-vous créer ?</h2>
                <p class="text-gray-300">Choisissez le type de projet qui correspond à votre besoin</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-4">
                <!-- Option Maintenance / SAV -->
                <div class="glassmorphism rounded-xl overflow-hidden hover:shadow-lg hover:border-accent-blue border border-gray-700 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="relative">
                        <div class="absolute inset-0 bg-blue-900/40"></div>
                        <div class="h-48 bg-gradient-to-r from-blue-900/80 to-blue-700/80 p-6 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white mb-2">Maintenance / SAV</h3>
                        <p class="text-gray-300 mb-6">
                            Créez un projet de maintenance ou de service après-vente pour un produit existant. 
                            Idéal pour les réparations, diagnostics et interventions sur site.
                        </p>
                        <ul class="text-gray-300 mb-6 space-y-2">
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Générez automatiquement les modules à réparer
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Suivi complet des interventions
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Date butoir de réparation obligatoire
                            </li>
                        </ul>
                        <a href="<?php echo e(route('chantiers.maintenance.create')); ?>" class="btn-action btn-primary w-full">
                            Créer un projet de maintenance
                        </a>
                    </div>
                </div>
                
                <!-- Option Vente -->
                <div class="glassmorphism rounded-xl overflow-hidden hover:shadow-lg hover:border-accent-green border border-gray-700 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="relative">
                        <div class="absolute inset-0 bg-green-900/40"></div>
                        <div class="h-48 bg-gradient-to-r from-green-900/80 to-green-700/80 p-6 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white mb-2">Vente</h3>
                        <p class="text-gray-300 mb-6">
                            Créez un projet de vente pour un nouveau produit. 
                            Parfait pour les nouvelles commandes, devis et préparation de livraison.
                        </p>
                        <ul class="text-gray-300 mb-6 space-y-2">
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Gestion des garanties automatisée
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Préparation des documents de livraison
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Date butoir de livraison optionnelle
                            </li>
                        </ul>
                        <a href="<?php echo e(route('chantiers.vente.create')); ?>" class="btn-action btn-success w-full">
                            Créer un projet de vente
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="<?php echo e(route('chantiers.index')); ?>" class="text-gray-300 hover:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Retour à la liste des projets
                </a>
            </div>
        </div>
    </div>
    
    <style>
        .glassmorphism {
            background: rgba(23, 28, 46, 0.7);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }
        
        .btn-action {
            @apply flex items-center justify-center px-4 py-3 rounded-lg font-medium transition duration-150 ease-in-out;
        }
        
        .btn-primary {
            @apply bg-blue-600 text-white hover:bg-blue-700;
        }
        
        .btn-success {
            @apply bg-green-600 text-white hover:bg-green-700;
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/chantiers/type_choix.blade.php ENDPATH**/ ?>