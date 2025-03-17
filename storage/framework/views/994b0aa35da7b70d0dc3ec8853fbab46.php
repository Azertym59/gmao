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
            <?php echo e(__('Détails du chantier')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white"><?php echo e($chantier->nom); ?></h3>
                        <div class="flex space-x-2">
                            <?php if (isset($component)) { $__componentOriginal8417baeedcb6c131165d53e37e61cc07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8417baeedcb6c131165d53e37e61cc07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.edit-button','data' => ['route' => route('chantiers.edit', $chantier)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('edit-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('chantiers.edit', $chantier))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8417baeedcb6c131165d53e37e61cc07)): ?>
<?php $attributes = $__attributesOriginal8417baeedcb6c131165d53e37e61cc07; ?>
<?php unset($__attributesOriginal8417baeedcb6c131165d53e37e61cc07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8417baeedcb6c131165d53e37e61cc07)): ?>
<?php $component = $__componentOriginal8417baeedcb6c131165d53e37e61cc07; ?>
<?php unset($__componentOriginal8417baeedcb6c131165d53e37e61cc07); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back-button','data' => ['route' => route('chantiers.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('back-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('chantiers.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687)): ?>
<?php $attributes = $__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687; ?>
<?php unset($__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687)): ?>
<?php $component = $__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687; ?>
<?php unset($__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687); ?>
<?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Boutons d'envoi d'email -->
                    <div class="mt-4 mb-6 flex space-x-2 justify-end">
                        <?php if (isset($component)) { $__componentOriginalc4709a7f0659e5bc620ccc45265cff75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc4709a7f0659e5bc620ccc45265cff75 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.email-button','data' => ['chantier' => $chantier,'type' => 'created','class' => 'btn-glow']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('email-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chantier' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chantier),'type' => 'created','class' => 'btn-glow']); ?>
                            Email création
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc4709a7f0659e5bc620ccc45265cff75)): ?>
<?php $attributes = $__attributesOriginalc4709a7f0659e5bc620ccc45265cff75; ?>
<?php unset($__attributesOriginalc4709a7f0659e5bc620ccc45265cff75); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc4709a7f0659e5bc620ccc45265cff75)): ?>
<?php $component = $__componentOriginalc4709a7f0659e5bc620ccc45265cff75; ?>
<?php unset($__componentOriginalc4709a7f0659e5bc620ccc45265cff75); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc4709a7f0659e5bc620ccc45265cff75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc4709a7f0659e5bc620ccc45265cff75 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.email-button','data' => ['chantier' => $chantier,'type' => 'started','class' => 'pulse-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('email-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chantier' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chantier),'type' => 'started','class' => 'pulse-primary']); ?>
                            Email interventions
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc4709a7f0659e5bc620ccc45265cff75)): ?>
<?php $attributes = $__attributesOriginalc4709a7f0659e5bc620ccc45265cff75; ?>
<?php unset($__attributesOriginalc4709a7f0659e5bc620ccc45265cff75); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc4709a7f0659e5bc620ccc45265cff75)): ?>
<?php $component = $__componentOriginalc4709a7f0659e5bc620ccc45265cff75; ?>
<?php unset($__componentOriginalc4709a7f0659e5bc620ccc45265cff75); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc4709a7f0659e5bc620ccc45265cff75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc4709a7f0659e5bc620ccc45265cff75 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.email-button','data' => ['chantier' => $chantier,'type' => 'completed','class' => 'btn-rainbow']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('email-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['chantier' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($chantier),'type' => 'completed','class' => 'btn-rainbow']); ?>
                            Email finalisation
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc4709a7f0659e5bc620ccc45265cff75)): ?>
<?php $attributes = $__attributesOriginalc4709a7f0659e5bc620ccc45265cff75; ?>
<?php unset($__attributesOriginalc4709a7f0659e5bc620ccc45265cff75); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc4709a7f0659e5bc620ccc45265cff75)): ?>
<?php $component = $__componentOriginalc4709a7f0659e5bc620ccc45265cff75; ?>
<?php unset($__componentOriginalc4709a7f0659e5bc620ccc45265cff75); ?>
<?php endif; ?>
                        
                        <div class="ml-2 flex space-x-1">
                            <?php if (isset($component)) { $__componentOriginal658398a0e73a18931bb7def04d911f42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal658398a0e73a18931bb7def04d911f42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon-button','data' => ['tag' => 'button','type' => 'info','tooltip' => 'Email création','tooltipPosition' => 'top','size' => 'sm','class' => 'rotate-icon','onclick' => 'document.getElementById(\'email-created-form\').submit();']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'button','type' => 'info','tooltip' => 'Email création','tooltipPosition' => 'top','size' => 'sm','class' => 'rotate-icon','onclick' => 'document.getElementById(\'email-created-form\').submit();']); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $attributes = $__attributesOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__attributesOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $component = $__componentOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__componentOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
                            <form id="email-created-form" action="<?php echo e(route('emails.chantier', $chantier)); ?>" method="POST" class="hidden">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="email_type" value="created">
                            </form>
                            
                            <?php if (isset($component)) { $__componentOriginal658398a0e73a18931bb7def04d911f42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal658398a0e73a18931bb7def04d911f42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon-button','data' => ['tag' => 'button','type' => 'warning','tooltip' => 'Email interventions','tooltipPosition' => 'top','size' => 'sm','class' => 'rotate-icon','onclick' => 'document.getElementById(\'email-started-form\').submit();']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'button','type' => 'warning','tooltip' => 'Email interventions','tooltipPosition' => 'top','size' => 'sm','class' => 'rotate-icon','onclick' => 'document.getElementById(\'email-started-form\').submit();']); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $attributes = $__attributesOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__attributesOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $component = $__componentOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__componentOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
                            <form id="email-started-form" action="<?php echo e(route('emails.chantier', $chantier)); ?>" method="POST" class="hidden">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="email_type" value="started">
                            </form>
                            
                            <?php if (isset($component)) { $__componentOriginal658398a0e73a18931bb7def04d911f42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal658398a0e73a18931bb7def04d911f42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon-button','data' => ['tag' => 'button','type' => 'success','tooltip' => 'Email finalisation','tooltipPosition' => 'top','size' => 'sm','class' => 'rotate-icon','onclick' => 'document.getElementById(\'email-completed-form\').submit();']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'button','type' => 'success','tooltip' => 'Email finalisation','tooltipPosition' => 'top','size' => 'sm','class' => 'rotate-icon','onclick' => 'document.getElementById(\'email-completed-form\').submit();']); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $attributes = $__attributesOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__attributesOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $component = $__componentOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__componentOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
                            <form id="email-completed-form" action="<?php echo e(route('emails.chantier', $chantier)); ?>" method="POST" class="hidden">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="email_type" value="completed">
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Informations générales</h4>
                            <div class="glassmorphism p-4 rounded-xl text-gray-200 border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold">Référence:</span> <?php echo e($chantier->reference); ?>

                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Client:</span> 
                                    <a href="<?php echo e(route('clients.show', $chantier->client)); ?>" class="text-accent-blue hover:underline">
                                        <?php echo e($chantier->client->nom_complet); ?>

                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Société:</span> <?php echo e($chantier->client->societe ?? 'Non spécifié'); ?>

                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold mr-2">État:</span> 
                                    <?php if($chantier->etat == 'non_commence'): ?>
                                        <span class="badge badge-info">Non commencé</span>
                                    <?php elseif($chantier->etat == 'en_cours'): ?>
                                        <span class="badge badge-warning">En cours</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Terminé</span>
                                    <?php endif; ?>
                                    
                                    <!-- Boutons de changement d'état rapide -->
                                    <div class="ml-4 flex space-x-1">
                                        <?php if($chantier->etat != 'non_commence'): ?>
                                            <form action="<?php echo e(route('chantiers.update.state', $chantier)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <input type="hidden" name="etat" value="non_commence">
                                                <button type="submit" class="text-xs px-2 py-1 bg-gray-600 hover:bg-gray-500 text-white rounded">
                                                    Non commencé
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if($chantier->etat != 'en_cours'): ?>
                                            <form action="<?php echo e(route('chantiers.update.state', $chantier)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <input type="hidden" name="etat" value="en_cours">
                                                <button type="submit" class="text-xs px-2 py-1 bg-yellow-600 hover:bg-yellow-500 text-white rounded">
                                                    En cours
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if($chantier->etat != 'termine'): ?>
                                            <form action="<?php echo e(route('chantiers.update.state', $chantier)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <input type="hidden" name="etat" value="termine">
                                                <button type="submit" class="text-xs px-2 py-1 bg-green-600 hover:bg-green-500 text-white rounded">
                                                    Terminé
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Dates</h4>
                            <div class="glassmorphism p-4 rounded-xl text-gray-200 border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold">Date de réception:</span> <?php echo e($chantier->date_reception->format('d/m/Y')); ?>

                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Date butoir:</span> <?php echo e($chantier->date_butoir->format('d/m/Y')); ?>

                                </div>
                                <div>
                                    <span class="font-semibold">Créé le:</span> <?php echo e($chantier->created_at->format('d/m/Y')); ?>

                                </div>
                            </div>
                        </div>

                        <?php if($chantier->description): ?>
                        <div class="md:col-span-2">
                            <h4 class="font-medium text-gray-300 mb-2">Description</h4>
                            <div class="glassmorphism p-4 rounded-xl text-gray-200 border border-gray-700">
                                <?php echo e($chantier->description); ?>

                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Produits et dalles du chantier -->
                    <div class="mt-8">
                        <h4 class="font-medium text-gray-300 mb-4">Avancement du chantier</h4>
                        
                        <?php if($chantier->produits->count() > 0): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Résumé de l'avancement -->
                                <div class="glassmorphism p-4 rounded-xl border border-gray-700">
                                    <h5 class="font-medium text-lg text-white mb-3">Avancement global</h5>
                                    <?php
                                        $totalModules = 0;
                                        $modulesTermines = 0;
                                        $modulesEnCours = 0;
                                        $modulesDefaillants = 0;
                                        $modulesNonCommences = 0;
                                        
                                        foreach($chantier->produits as $produit) {
                                            foreach($produit->dalles as $dalle) {
                                                $totalModules += $dalle->modules->count();
                                                $modulesTermines += $dalle->modules->where('etat', 'termine')->count();
                                                $modulesEnCours += $dalle->modules->where('etat', 'en_cours')->count();
                                                $modulesDefaillants += $dalle->modules->where('etat', 'defaillant')->count();
                                                $modulesNonCommences += $dalle->modules->where('etat', 'non_commence')->count();
                                            }
                                        }
                                        
                                        $pourcentageTermines = $totalModules > 0 ? round(($modulesTermines / $totalModules) * 100) : 0;
                                    ?>
                                    
                                    <!-- Barre de progression -->
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm mb-1 text-gray-300">
                                            <span>Progression: <?php echo e($pourcentageTermines); ?>%</span>
                                            <span><?php echo e($modulesTermines); ?>/<?php echo e($totalModules); ?> modules</span>
                                        </div>
                                        <div class="h-4 w-full bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-accent-green rounded-full" style="width: <?php echo e($pourcentageTermines); ?>%"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Statistiques des modules -->
                                    <div class="grid grid-cols-2 gap-3 text-sm text-gray-300">
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 rounded-full bg-accent-green mr-2"></span>
                                            <span>Terminés: <?php echo e($modulesTermines); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 rounded-full bg-accent-yellow mr-2"></span>
                                            <span>En cours: <?php echo e($modulesEnCours); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 rounded-full bg-accent-red mr-2"></span>
                                            <span>Défaillants: <?php echo e($modulesDefaillants); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 rounded-full bg-gray-500 mr-2"></span>
                                            <span>Non commencés: <?php echo e($modulesNonCommences); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Informations générales -->
                                <div class="glassmorphism p-4 rounded-xl border border-gray-700">
                                    <h5 class="font-medium text-lg text-white mb-3">Détails</h5>
                                    <p class="text-gray-300"><span class="font-medium">Nombre de produits:</span> <?php echo e($chantier->produits->count()); ?></p>
                                    <p class="text-gray-300"><span class="font-medium">Nombre de dalles:</span> <?php echo e($chantier->produits->sum(function($produit) { return $produit->dalles->count(); })); ?></p>
                                    <p class="text-gray-300"><span class="font-medium">Nombre de modules:</span> <?php echo e($totalModules); ?></p>
                                    <p class="mt-2 text-gray-300"><span class="font-medium">Date réception:</span> <?php echo e($chantier->date_reception->format('d/m/Y')); ?></p>
                                    <p class="text-gray-300"><span class="font-medium">Date butoir:</span> <?php echo e($chantier->date_butoir->format('d/m/Y')); ?></p>
                                    <p class="mt-2 text-gray-300">
                                        <span class="font-medium">Temps restant:</span>
                                        <span class="<?php echo e(\App\Helpers\DateHelper::getTimeRemainingClass($chantier->date_butoir)); ?> font-semibold">
                                            <?php echo e(\App\Helpers\DateHelper::formatTimeRemaining($chantier->date_butoir)); ?>

                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Liste des produits avec leur avancement -->
                            <?php $__currentLoopData = $chantier->produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="glassmorphism p-4 rounded-xl border border-gray-700 mb-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <h5 class="font-medium text-lg text-white"><?php echo e($produit->marque); ?> <?php echo e($produit->modele); ?></h5>
                                        <?php if (isset($component)) { $__componentOriginale91f24f192f79f911477623e23179d51 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale91f24f192f79f911477623e23179d51 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.view-button','data' => ['route' => route('produits.show', $produit)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('view-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('produits.show', $produit))]); ?>
                                            Détails
                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale91f24f192f79f911477623e23179d51)): ?>
<?php $attributes = $__attributesOriginale91f24f192f79f911477623e23179d51; ?>
<?php unset($__attributesOriginale91f24f192f79f911477623e23179d51); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale91f24f192f79f911477623e23179d51)): ?>
<?php $component = $__componentOriginale91f24f192f79f911477623e23179d51; ?>
<?php unset($__componentOriginale91f24f192f79f911477623e23179d51); ?>
<?php endif; ?>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-4 text-sm text-gray-300">
                                        <div><span class="font-medium">Pitch:</span> <?php echo e($produit->pitch); ?> mm</div>
                                        <div><span class="font-medium">Utilisation:</span> <?php echo e($produit->utilisation == 'indoor' ? 'Indoor' : 'Outdoor'); ?></div>
                                        <div>
                                            <span class="font-medium">Électronique:</span> 
                                            <?php if($produit->electronique == 'autre'): ?>
                                                <?php echo e($produit->electronique_detail); ?>

                                            <?php else: ?>
                                                <?php echo e(ucfirst($produit->electronique)); ?>

                                            <?php endif; ?>
                                        </div>
                                        <div><span class="font-medium">Dalles:</span> <?php echo e($produit->dalles->count()); ?></div>
                                    </div>
                                    
                                    <!-- Liste des dalles avec modules -->
                                    <?php if($produit->dalles->count() > 0): ?>
                                        <h6 class="font-medium text-gray-300 mb-2">Dalles et modules</h6>
                                        
                                        <?php
                                            // Organiser les dalles par flightcase et modules individuels
                                            $dallesGrouped = [
                                                'individuel' => [],
                                                'flightcases' => []
                                            ];
                                            
                                            foreach($produit->dalles as $dalle) {
                                                if($dalle->reference_dalle == "INDIVIDUEL") {
                                                    $dallesGrouped['individuel'][] = $dalle;
                                                } elseif(preg_match('/^FC(\d+)-D\d+$/', $dalle->reference_dalle, $matches)) {
                                                    $fcNumber = $matches[1];
                                                    if(!isset($dallesGrouped['flightcases'][$fcNumber])) {
                                                        $dallesGrouped['flightcases'][$fcNumber] = [];
                                                    }
                                                    $dallesGrouped['flightcases'][$fcNumber][] = $dalle;
                                                } else {
                                                    // Si aucun format reconnu, traiter comme une dalle indépendante
                                                    if(!isset($dallesGrouped['autres'])) {
                                                        $dallesGrouped['autres'] = [];
                                                    }
                                                    $dallesGrouped['autres'][] = $dalle;
                                                }
                                            }
                                            
                                            // Trier les flightcases par numéro
                                            ksort($dallesGrouped['flightcases']);
                                        ?>
                                        
                                        <!-- Modules individuels -->
                                        <?php if(!empty($dallesGrouped['individuel'])): ?>
                                            <div class="glassmorphism border border-gray-700 rounded-xl p-4 mb-6">
                                                <div class="flex justify-between items-center mb-3">
                                                    <h6 class="font-medium text-white flex items-center">
                                                        <span class="text-amber-400">Modules individuels</span>
                                                        
                                                        <?php $__currentLoopData = $dallesGrouped['individuel']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <!-- Affichage du numéro de dalle ou formulaire de saisie rapide -->
                                                        <span class="ml-2" x-data="{ 
                                                            isEditing: false,
                                                            numeroValue: '<?php echo e($dalle->numero_dalle); ?>',
                                                            initialValue: '<?php echo e($dalle->numero_dalle); ?>',
                                                            toggleEdit() { this.isEditing = !this.isEditing; },
                                                            save() {
                                                                if (this.numeroValue.trim() === this.initialValue) {
                                                                    this.isEditing = false;
                                                                    return;
                                                                }
                                                                
                                                                fetch('<?php echo e(route('dalles.update.numero', $dalle->id)); ?>', {
                                                                    method: 'POST',
                                                                    headers: {
                                                                        'Content-Type': 'application/json',
                                                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                                    },
                                                                    body: JSON.stringify({ numero_dalle: this.numeroValue })
                                                                })
                                                                .then(response => response.json())
                                                                .then(data => {
                                                                    if (data.success) {
                                                                        this.isEditing = false;
                                                                        this.initialValue = this.numeroValue;
                                                                    }
                                                                });
                                                            },
                                                            cancel() {
                                                                this.numeroValue = this.initialValue;
                                                                this.isEditing = false;
                                                            }
                                                        }">
                                                            <!-- Affichage si on a déjà un numéro -->
                                                            <template x-if="!isEditing && initialValue">
                                                                <span @click="toggleEdit()" class="cursor-pointer group">
                                                                    <span class="text-xs text-accent-blue">[N° <span x-text="initialValue"></span>]</span>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline ml-1 text-gray-400 group-hover:text-accent-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                    </svg>
                                                                </span>
                                                            </template>
                                                            
                                                            <!-- Bouton pour ajouter un numéro si on n'en a pas -->
                                                            <template x-if="!isEditing && !initialValue">
                                                                <button @click="toggleEdit()" class="text-xs px-2 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded flex items-center shadow-md transition-all duration-150 transform hover:scale-105 border border-purple-500">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                    </svg>
                                                                    N° dalle
                                                                </button>
                                                            </template>
                                                            
                                                            <!-- Formulaire de saisie -->
                                                            <template x-if="isEditing">
                                                                <span class="flex items-center">
                                                                    <input 
                                                                        type="text" 
                                                                        x-model="numeroValue" 
                                                                        class="text-xs px-1 py-0.5 w-28 bg-gray-700 border border-accent-blue rounded" 
                                                                        placeholder="N° dalle"
                                                                        @keydown.enter="save()"
                                                                        @keydown.escape="cancel()"
                                                                    />
                                                                    <button @click="save()" class="ml-1 text-accent-green">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                    </button>
                                                                    <button @click="cancel()" class="ml-1 text-accent-red">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>
                                                                </span>
                                                            </template>
                                                        </span>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </h6>
                                                    <div class="flex space-x-4 items-center">
                                                        <?php $__currentLoopData = $dallesGrouped['individuel']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="text-sm text-gray-300">
                                                                <span class="mr-2">Progrès:</span>
                                                                <span class="badge badge-success">
                                                                    <?php echo e($dalle->modules->where('etat', 'termine')->count()); ?>/<?php echo e($dalle->modules->count()); ?>

                                                                </span>
                                                            </div>
                                                            <a href="<?php echo e(route('dalles.show', $dalle)); ?>" class="text-xs text-accent-blue hover:text-blue-400">Détails</a>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                                
                                                <?php $__currentLoopData = $dallesGrouped['individuel']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="flex flex-wrap gap-2 justify-center mt-3">
                                                        <?php $__currentLoopData = $dalle->modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <a href="<?php echo e(route('modules.show', $module)); ?>" 
                                                               class="relative group">
                                                                <div class="aspect-square w-10 h-10 
                                                                    <?php if($module->etat == 'termine'): ?> bg-accent-green
                                                                    <?php elseif($module->etat == 'en_cours'): ?> bg-accent-yellow
                                                                    <?php elseif($module->etat == 'defaillant'): ?> bg-accent-red
                                                                    <?php else: ?> bg-gray-600
                                                                    <?php endif; ?>
                                                                    rounded-sm border border-black/10 hover:brightness-110 transition-all hover:shadow-lg duration-150 transform hover:scale-105"
                                                                    title="Module #<?php echo e($module->id); ?> - <?php echo e(ucfirst($module->etat)); ?>"
                                                                >
                                                                    <div class="flex items-center justify-center h-full text-xs font-medium text-white/90">
                                                                        <?php echo e(preg_replace('/[^0-9]/', '', $module->reference_module)); ?>

                                                                    </div>
                                                                </div>
                                                                <div class="hidden group-hover:block absolute top-full mt-1 left-1/2 transform -translate-x-1/2 bg-gray-800 text-xs text-white p-1 rounded whitespace-nowrap z-10">
                                                                    Module #<?php echo e($module->id); ?>

                                                                </div>
                                                            </a>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                    <div class="w-full mt-3">
                                                        <div class="flex justify-between text-xs mb-1 text-gray-300">
                                                            <span><?php echo e(round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100)); ?>%</span>
                                                            <span><?php echo e($dalle->modules->where('etat', 'termine')->count()); ?>/<?php echo e($dalle->modules->count()); ?> modules</span>
                                                        </div>
                                                        <div class="h-2 w-full bg-gray-700 rounded-full overflow-hidden">
                                                            <div class="h-full bg-accent-green rounded-full" style="width: <?php echo e(round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100)); ?>%"></div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- FlightCases -->
                                        <?php $__currentLoopData = $dallesGrouped['flightcases']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fcNumber => $dalles): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="mb-6">
                                                <div class="flex items-center bg-gray-800/50 p-2 rounded-t-xl border-t border-l border-r border-gray-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8" />
                                                    </svg>
                                                    <h6 class="font-medium text-white">Flight Case #<?php echo e($fcNumber); ?> - <?php echo e(count($dalles)); ?> dalles</h6>
                                                </div>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-900/30 rounded-b-xl border-b border-l border-r border-gray-700">
                                                    <?php $__currentLoopData = $dalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="glassmorphism border border-gray-700 rounded-xl p-4">
                                                            <div class="flex justify-between items-center mb-3">
                                                                <h6 class="font-medium text-white flex items-center flex-wrap">
                                                                    <?php echo e($dalle->reference_dalle); ?> 
                                                                    <span class="text-xs text-gray-400 ml-1">(<?php echo e($dalle->largeur); ?>×<?php echo e($dalle->hauteur); ?> mm)</span>
                                                                    
                                                                    <!-- Affichage du numéro de dalle ou formulaire de saisie rapide -->
                                                                    <span class="ml-2" x-data="{ 
                                                                        isEditing: false,
                                                                        numeroValue: '<?php echo e($dalle->numero_dalle); ?>',
                                                                        initialValue: '<?php echo e($dalle->numero_dalle); ?>',
                                                                        toggleEdit() { this.isEditing = !this.isEditing; },
                                                                        save() {
                                                                            if (this.numeroValue.trim() === this.initialValue) {
                                                                                this.isEditing = false;
                                                                                return;
                                                                            }
                                                                            
                                                                            fetch('<?php echo e(route('dalles.update.numero', $dalle->id)); ?>', {
                                                                                method: 'POST',
                                                                                headers: {
                                                                                    'Content-Type': 'application/json',
                                                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                                                },
                                                                                body: JSON.stringify({ numero_dalle: this.numeroValue })
                                                                            })
                                                                            .then(response => response.json())
                                                                            .then(data => {
                                                                                if (data.success) {
                                                                                    this.isEditing = false;
                                                                                    this.initialValue = this.numeroValue;
                                                                                }
                                                                            });
                                                                        },
                                                                        cancel() {
                                                                            this.numeroValue = this.initialValue;
                                                                            this.isEditing = false;
                                                                        }
                                                                    }">
                                                                        <!-- Affichage si on a déjà un numéro -->
                                                                        <template x-if="!isEditing && initialValue">
                                                                            <span @click="toggleEdit()" class="cursor-pointer group">
                                                                                <span class="text-xs text-accent-blue">[N° <span x-text="initialValue"></span>]</span>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline ml-1 text-gray-400 group-hover:text-accent-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                                </svg>
                                                                            </span>
                                                                        </template>
                                                                        
                                                                        <!-- Bouton pour ajouter un numéro si on n'en a pas -->
                                                                        <template x-if="!isEditing && !initialValue">
                                                                            <button @click="toggleEdit()" class="text-xs px-2 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded flex items-center shadow-md transition-all duration-150 transform hover:scale-105 border border-purple-500">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                                </svg>
                                                                                N° dalle
                                                                            </button>
                                                                        </template>
                                                                        
                                                                        <!-- Formulaire de saisie -->
                                                                        <template x-if="isEditing">
                                                                            <span class="flex items-center">
                                                                                <input 
                                                                                    type="text" 
                                                                                    x-model="numeroValue" 
                                                                                    class="text-xs px-1 py-0.5 w-28 bg-gray-700 border border-accent-blue rounded" 
                                                                                    placeholder="N° dalle"
                                                                                    @keydown.enter="save()"
                                                                                    @keydown.escape="cancel()"
                                                                                />
                                                                                <button @click="save()" class="ml-1 text-accent-green">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                                    </svg>
                                                                                </button>
                                                                                <button @click="cancel()" class="ml-1 text-accent-red">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                                    </svg>
                                                                                </button>
                                                                            </span>
                                                                        </template>
                                                                    </span>
                                                                </h6>
                                                                <div class="flex space-x-3 items-center">
                                                                    <div class="text-xs">
                                                                        <span class="badge badge-success">
                                                                            <?php echo e($dalle->modules->where('etat', 'termine')->count()); ?>/<?php echo e($dalle->modules->count()); ?>

                                                                        </span>
                                                                    </div>
                                                                    <a href="<?php echo e(route('dalles.show', $dalle)); ?>" class="text-xs text-accent-blue hover:text-blue-400">Détails</a>
                                                                </div>
                                                            </div>
                                                            
                                                            <?php
                                                                // Extraire les dimensions depuis disposition_modules
                                                                $nbColonnes = 2; // Par défaut 2x2
                                                                $nbLignes = 2;
                                                                
                                                                // Récupérer disposition_modules depuis l'objet dalle ou la session
                                                                $disposition = $dalle->disposition_modules ?? session('disposition_modules_dalle_' . $dalle->id);
                                                                
                                                                // Si disposition_modules est définie et au format AxB
                                                                if (!empty($disposition) && strpos($disposition, 'x') !== false) {
                                                                    $parts = explode('x', $disposition);
                                                                    if (count($parts) == 2) {
                                                                        $nbColonnes = (int)$parts[0];
                                                                        $nbLignes = (int)$parts[1];
                                                                    }
                                                                } else {
                                                                    // Essayer de calculer la disposition en fonction du nombre de modules
                                                                    $nbModules = $dalle->modules->count();
                                                                    if ($nbModules == 4) {
                                                                        $nbColonnes = 2;
                                                                        $nbLignes = 2;
                                                                    } elseif ($nbModules == 6) {
                                                                        $nbColonnes = 3;
                                                                        $nbLignes = 2;
                                                                    } elseif ($nbModules == 9) {
                                                                        $nbColonnes = 3;
                                                                        $nbLignes = 3;
                                                                    }
                                                                }
                                                                
                                                                // Créer une grille pour les modules
                                                                $modules = $dalle->modules->all();
                                                                $grille = [];
                                                                
                                                                // Remplir la grille dans l'ordre ligne par ligne
                                                                $moduleIndex = 0;
                                                                for ($y = 1; $y <= $nbLignes; $y++) {
                                                                    for ($x = 1; $x <= $nbColonnes; $x++) {
                                                                        if ($moduleIndex < count($modules)) {
                                                                            $grille[$y][$x] = $modules[$moduleIndex];
                                                                            $moduleIndex++;
                                                                        } else {
                                                                            $grille[$y][$x] = null;
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                            
                                                            <div class="flex justify-center">
                                                                <div class="grid max-w-xs mx-auto" style="grid-template-columns: repeat(<?php echo e($nbColonnes); ?>, minmax(0, 1fr)); gap: 0.25rem;">
                                                                    <?php for($y = 1; $y <= $nbLignes; $y++): ?>
                                                                        <?php for($x = 1; $x <= $nbColonnes; $x++): ?>
                                                                            <?php if(isset($grille[$y][$x])): ?>
                                                                                <?php $module = $grille[$y][$x]; ?>
                                                                                <a href="<?php echo e(route('modules.show', $module)); ?>" 
                                                                                   class="relative group">
                                                                                    <div class="aspect-square w-10 h-10 
                                                                                        <?php if($module->etat == 'termine'): ?> bg-accent-green
                                                                                        <?php elseif($module->etat == 'en_cours'): ?> bg-accent-yellow
                                                                                        <?php elseif($module->etat == 'defaillant'): ?> bg-accent-red
                                                                                        <?php else: ?> bg-gray-600
                                                                                        <?php endif; ?>
                                                                                        rounded-sm border border-black/10 hover:brightness-110 transition-all hover:shadow-lg duration-150 transform hover:scale-105"
                                                                                        title="Module #<?php echo e($module->id); ?> - <?php echo e(ucfirst($module->etat)); ?>"
                                                                                    >
                                                                                        <div class="flex items-center justify-center h-full text-xs font-medium text-white/90">
                                                                                            <?php echo e($x); ?>,<?php echo e($y); ?>

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="hidden group-hover:block absolute top-full mt-1 left-1/2 transform -translate-x-1/2 bg-gray-800 text-xs text-white p-1 rounded whitespace-nowrap z-10">
                                                                                        Module #<?php echo e($module->id); ?>

                                                                                    </div>
                                                                                </a>
                                                                            <?php else: ?>
                                                                                <div class="aspect-square w-10 h-10 bg-gray-800/50 rounded-sm border border-gray-700" title="Emplacement vide">
                                                                                    <div class="flex items-center justify-center h-full text-xs text-gray-600">
                                                                                        <?php echo e($x); ?>,<?php echo e($y); ?>

                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        <?php endfor; ?>
                                                                    <?php endfor; ?>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="w-full mt-3">
                                                                <div class="flex justify-between text-xs mb-1 text-gray-300">
                                                                    <span><?php echo e(round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100)); ?>%</span>
                                                                    <span><?php echo e($dalle->modules->where('etat', 'termine')->count()); ?>/<?php echo e($dalle->modules->count()); ?> modules</span>
                                                                </div>
                                                                <div class="h-2 w-full bg-gray-700 rounded-full overflow-hidden">
                                                                    <div class="h-full bg-accent-green rounded-full" style="width: <?php echo e(round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100)); ?>%"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        
                                        <!-- Autres dalles -->
                                        <?php if(!empty($dallesGrouped['autres'])): ?>
                                            <div class="mb-6">
                                                <div class="flex items-center bg-gray-800/50 p-2 rounded-t-xl border-t border-l border-r border-gray-700">
                                                    <h6 class="font-medium text-white">Dalles indépendantes (<?php echo e(count($dallesGrouped['autres'])); ?>)</h6>
                                                </div>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-900/30 rounded-b-xl border-b border-l border-r border-gray-700">
                                                    <?php $__currentLoopData = $dallesGrouped['autres']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="glassmorphism border border-gray-700 rounded-xl p-4">
                                                            <div class="flex justify-between items-center mb-3">
                                                                <h6 class="font-medium text-white flex items-center flex-wrap">
                                                                    Dalle #<?php echo e($dalle->id); ?> 
                                                                    <?php if($dalle->reference_dalle): ?>
                                                                        <span class="text-gray-400 ml-1">(<?php echo e($dalle->reference_dalle); ?>)</span>
                                                                    <?php endif; ?>
                                                                    <span class="text-xs text-gray-400 ml-1">(<?php echo e($dalle->largeur); ?>×<?php echo e($dalle->hauteur); ?> mm)</span>
                                                                    
                                                                    <!-- Affichage du numéro de dalle ou formulaire de saisie rapide -->
                                                                    <span class="ml-2" x-data="{ 
                                                                        isEditing: false,
                                                                        numeroValue: '<?php echo e($dalle->numero_dalle); ?>',
                                                                        initialValue: '<?php echo e($dalle->numero_dalle); ?>',
                                                                        toggleEdit() { this.isEditing = !this.isEditing; },
                                                                        save() {
                                                                            if (this.numeroValue.trim() === this.initialValue) {
                                                                                this.isEditing = false;
                                                                                return;
                                                                            }
                                                                            
                                                                            fetch('<?php echo e(route('dalles.update.numero', $dalle->id)); ?>', {
                                                                                method: 'POST',
                                                                                headers: {
                                                                                    'Content-Type': 'application/json',
                                                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                                                },
                                                                                body: JSON.stringify({ numero_dalle: this.numeroValue })
                                                                            })
                                                                            .then(response => response.json())
                                                                            .then(data => {
                                                                                if (data.success) {
                                                                                    this.isEditing = false;
                                                                                    this.initialValue = this.numeroValue;
                                                                                }
                                                                            });
                                                                        },
                                                                        cancel() {
                                                                            this.numeroValue = this.initialValue;
                                                                            this.isEditing = false;
                                                                        }
                                                                    }">
                                                                        <!-- Affichage si on a déjà un numéro -->
                                                                        <template x-if="!isEditing && initialValue">
                                                                            <span @click="toggleEdit()" class="cursor-pointer group">
                                                                                <span class="text-xs text-accent-blue">[N° <span x-text="initialValue"></span>]</span>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline ml-1 text-gray-400 group-hover:text-accent-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                                </svg>
                                                                            </span>
                                                                        </template>
                                                                        
                                                                        <!-- Bouton pour ajouter un numéro si on n'en a pas -->
                                                                        <template x-if="!isEditing && !initialValue">
                                                                            <button @click="toggleEdit()" class="text-xs px-2 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded flex items-center shadow-md transition-all duration-150 transform hover:scale-105 border border-purple-500">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                                </svg>
                                                                                N° dalle
                                                                            </button>
                                                                        </template>
                                                                        
                                                                        <!-- Formulaire de saisie -->
                                                                        <template x-if="isEditing">
                                                                            <span class="flex items-center">
                                                                                <input 
                                                                                    type="text" 
                                                                                    x-model="numeroValue" 
                                                                                    class="text-xs px-1 py-0.5 w-28 bg-gray-700 border border-accent-blue rounded" 
                                                                                    placeholder="N° dalle"
                                                                                    @keydown.enter="save()"
                                                                                    @keydown.escape="cancel()"
                                                                                />
                                                                                <button @click="save()" class="ml-1 text-accent-green">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                                    </svg>
                                                                                </button>
                                                                                <button @click="cancel()" class="ml-1 text-accent-red">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                                    </svg>
                                                                                </button>
                                                                            </span>
                                                                        </template>
                                                                    </span>
                                                                </h6>
                                                                <div class="flex space-x-3 items-center">
                                                                    <div class="text-xs">
                                                                        <span class="badge badge-success">
                                                                            <?php echo e($dalle->modules->where('etat', 'termine')->count()); ?>/<?php echo e($dalle->modules->count()); ?>

                                                                        </span>
                                                                    </div>
                                                                    <a href="<?php echo e(route('dalles.show', $dalle)); ?>" class="text-xs text-accent-blue hover:text-blue-400">Détails</a>
                                                                </div>
                                                            </div>
                                                            
                                                            <?php
                                                                // Extraire les dimensions depuis disposition_modules
                                                                $nbColonnes = 2; // Par défaut 2x2
                                                                $nbLignes = 2;
                                                                
                                                                // Récupérer disposition_modules depuis l'objet dalle ou la session
                                                                $disposition = $dalle->disposition_modules ?? session('disposition_modules_dalle_' . $dalle->id);
                                                                
                                                                // Si disposition_modules est définie et au format AxB
                                                                if (!empty($disposition) && strpos($disposition, 'x') !== false) {
                                                                    $parts = explode('x', $disposition);
                                                                    if (count($parts) == 2) {
                                                                        $nbColonnes = (int)$parts[0];
                                                                        $nbLignes = (int)$parts[1];
                                                                    }
                                                                } else {
                                                                    // Essayer de calculer la disposition en fonction du nombre de modules
                                                                    $nbModules = $dalle->modules->count();
                                                                    if ($nbModules == 4) {
                                                                        $nbColonnes = 2;
                                                                        $nbLignes = 2;
                                                                    } elseif ($nbModules == 6) {
                                                                        $nbColonnes = 3;
                                                                        $nbLignes = 2;
                                                                    } elseif ($nbModules == 9) {
                                                                        $nbColonnes = 3;
                                                                        $nbLignes = 3;
                                                                    }
                                                                }
                                                                
                                                                // Créer une grille pour les modules
                                                                $modules = $dalle->modules->all();
                                                                $grille = [];
                                                                
                                                                // Remplir la grille dans l'ordre ligne par ligne
                                                                $moduleIndex = 0;
                                                                for ($y = 1; $y <= $nbLignes; $y++) {
                                                                    for ($x = 1; $x <= $nbColonnes; $x++) {
                                                                        if ($moduleIndex < count($modules)) {
                                                                            $grille[$y][$x] = $modules[$moduleIndex];
                                                                            $moduleIndex++;
                                                                        } else {
                                                                            $grille[$y][$x] = null;
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                            
                                                            <div class="flex justify-center">
                                                                <div class="grid max-w-xs mx-auto" style="grid-template-columns: repeat(<?php echo e($nbColonnes); ?>, minmax(0, 1fr)); gap: 0.25rem;">
                                                                    <?php for($y = 1; $y <= $nbLignes; $y++): ?>
                                                                        <?php for($x = 1; $x <= $nbColonnes; $x++): ?>
                                                                            <?php if(isset($grille[$y][$x])): ?>
                                                                                <?php $module = $grille[$y][$x]; ?>
                                                                                <a href="<?php echo e(route('modules.show', $module)); ?>" 
                                                                                   class="relative group">
                                                                                    <div class="aspect-square w-10 h-10 
                                                                                        <?php if($module->etat == 'termine'): ?> bg-accent-green
                                                                                        <?php elseif($module->etat == 'en_cours'): ?> bg-accent-yellow
                                                                                        <?php elseif($module->etat == 'defaillant'): ?> bg-accent-red
                                                                                        <?php else: ?> bg-gray-600
                                                                                        <?php endif; ?>
                                                                                        rounded-sm border border-black/10 hover:brightness-110 transition-all hover:shadow-lg duration-150 transform hover:scale-105"
                                                                                        title="Module #<?php echo e($module->id); ?> - <?php echo e(ucfirst($module->etat)); ?>"
                                                                                    >
                                                                                        <div class="flex items-center justify-center h-full text-xs font-medium text-white/90">
                                                                                            <?php echo e($x); ?>,<?php echo e($y); ?>

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="hidden group-hover:block absolute top-full mt-1 left-1/2 transform -translate-x-1/2 bg-gray-800 text-xs text-white p-1 rounded whitespace-nowrap z-10">
                                                                                        Module #<?php echo e($module->id); ?>

                                                                                    </div>
                                                                                </a>
                                                                            <?php else: ?>
                                                                                <div class="aspect-square w-10 h-10 bg-gray-800/50 rounded-sm border border-gray-700" title="Emplacement vide">
                                                                                    <div class="flex items-center justify-center h-full text-xs text-gray-600">
                                                                                        <?php echo e($x); ?>,<?php echo e($y); ?>

                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        <?php endfor; ?>
                                                                    <?php endfor; ?>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="w-full mt-3">
                                                                <div class="flex justify-between text-xs mb-1 text-gray-300">
                                                                    <span><?php echo e(round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100)); ?>%</span>
                                                                    <span><?php echo e($dalle->modules->where('etat', 'termine')->count()); ?>/<?php echo e($dalle->modules->count()); ?> modules</span>
                                                                </div>
                                                                <div class="h-2 w-full bg-gray-700 rounded-full overflow-hidden">
                                                                    <div class="h-full bg-accent-green rounded-full" style="width: <?php echo e(round(($dalle->modules->where('etat', 'termine')->count() / max($dalle->modules->count(), 1)) * 100)); ?>%"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="p-4 rounded-xl text-center text-gray-400 bg-gray-800/30">
                                            Ce produit n'a pas encore de dalles.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="p-10 rounded-xl text-center text-gray-400 bg-gray-800/30 flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="mb-4">Ce chantier n'a pas encore de produits. Ajoutez d'abord un produit pour commencer.</p>
                                <a href="<?php echo e(route('produits.create')); ?>" class="btn-action btn-primary">
                                    Ajouter un produit
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Bouton QR Code -->
                    <div class="text-center mt-6">
                        <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['tag' => 'a','href' => ''.e(route('qrcode.chantier.print', $chantier->id)).'','class' => 'flex items-center btn-3d']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'a','href' => ''.e(route('qrcode.chantier.print', $chantier->id)).'','class' => 'flex items-center btn-3d']); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4M4 8h16V5a1 1 0 00-1-1H5a1 1 0 00-1 1v3zm16 4v7a1 1 0 01-1 1H5a1 1 0 01-1-1v-7" />
                            </svg>
                            Générer QR Code
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                        
                        <?php if (isset($component)) { $__componentOriginal658398a0e73a18931bb7def04d911f42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal658398a0e73a18931bb7def04d911f42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon-button','data' => ['tag' => 'a','href' => ''.e(route('qrcode.chantier.print', $chantier->id)).'','type' => 'primary','tooltip' => 'Générer QR Code','tooltipPosition' => 'top','size' => 'lg','class' => 'ml-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'a','href' => ''.e(route('qrcode.chantier.print', $chantier->id)).'','type' => 'primary','tooltip' => 'Générer QR Code','tooltipPosition' => 'top','size' => 'lg','class' => 'ml-3']); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4M4 8h16V5a1 1 0 00-1-1H5a1 1 0 00-1 1v3zm16 4v7a1 1 0 01-1 1H5a1 1 0 01-1-1v-7" />
                            </svg>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $attributes = $__attributesOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__attributesOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $component = $__componentOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__componentOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/chantiers/show.blade.php ENDPATH**/ ?>