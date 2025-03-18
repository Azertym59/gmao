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
            <!-- Debug: Type de projet = <?php echo e($type ?? 'non défini'); ?> -->
            <?php if(isset($type) && $type === 'vente'): ?>
                <?php echo e(__('Créer un projet de vente - Étape 1/5')); ?>

            <?php else: ?>
                <?php echo e(__('Créer un projet de maintenance - Étape 1/5')); ?>

            <?php endif; ?>
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-5xl mx-auto">
            <!-- Étapes de progression -->
            <div class="mb-10">
                <div class="flex items-center justify-between">
                    <div class="w-full flex items-center">
                        <div class="relative flex flex-col items-center">
                            <div class="step-button active">
                                <span class="relative z-10 font-bold">1</span>
                            </div>
                            <div class="text-center mt-1 w-32">
                                <span class="step-text text-accent-primary font-semibold">Client</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-600/30"></div>
                        <div class="relative flex flex-col items-center">
                            <div class="step-button inactive">
                                <span class="relative z-10">2</span>
                            </div>
                            <div class="text-center mt-1 w-32">
                                <span class="step-text text-gray-400">Chantier</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-600/30"></div>
                        <div class="relative flex flex-col items-center">
                            <div class="step-button inactive">
                                <span class="relative z-10">3</span>
                            </div>
                            <div class="text-center mt-1 w-32">
                                <span class="step-text text-gray-400">Produit</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-600/30"></div>
                        <div class="relative flex flex-col items-center">
                            <div class="step-button inactive">
                                <span class="relative z-10">4</span>
                            </div>
                            <div class="text-center mt-1 w-32">
                                <span class="step-text text-gray-400">Interventions</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-600/30"></div>
                        <div class="relative flex flex-col items-center">
                            <div class="step-button inactive">
                                <span class="relative z-10">5</span>
                            </div>
                            <div class="text-center mt-1 w-32">
                                <span class="step-text text-gray-400">Rapports</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-2xl border border-white/5">
                <div class="p-8 border-b border-gray-700/50">
                    <h3 class="text-xl font-medium text-white mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informations client
                    </h3>
                    
                    <form method="POST" action="<?php echo e(route('chantiers.store.step1')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Client avec autocomplétion -->
                            <div>
                                <?php if (isset($component)) { $__componentOriginal3b16423b28e47448bdc577b76a12e67c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b16423b28e47448bdc577b76a12e67c = $attributes; } ?>
<?php $component = App\View\Components\ClientAutocomplete::resolve(['required' => true,'label' => 'Client'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('client-autocomplete'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\ClientAutocomplete::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b16423b28e47448bdc577b76a12e67c)): ?>
<?php $attributes = $__attributesOriginal3b16423b28e47448bdc577b76a12e67c; ?>
<?php unset($__attributesOriginal3b16423b28e47448bdc577b76a12e67c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b16423b28e47448bdc577b76a12e67c)): ?>
<?php $component = $__componentOriginal3b16423b28e47448bdc577b76a12e67c; ?>
<?php unset($__componentOriginal3b16423b28e47448bdc577b76a12e67c); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('client_id'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('client_id')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                                <div class="mt-3">
                                    <button type="button" id="openCreateClientModal" class="text-sm text-accent-tertiary hover:text-accent-blue transition-colors flex items-center font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Créer un nouveau client
                                    </button>
                                </div>
                            </div>

                            <!-- Création automatique du nom -->
                            <div class="bg-accent-blue/10 border border-accent-blue/20 p-4 rounded-xl flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-tertiary mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-200">Le nom du chantier sera généré automatiquement à partir des informations du client.</span>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'description','value' => __('Description'),'class' => 'text-gray-200 font-medium']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'description','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Description')),'class' => 'text-gray-200 font-medium']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                <textarea id="description" name="description" class="block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30 resize-none" rows="3" placeholder="Description détaillée du projet..."><?php echo e(old('description')); ?></textarea>
                                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('description'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('description')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                            </div>

                            <!-- Date de réception -->
                            <div>
                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'date_reception','value' => __('Date de réception'),'class' => 'text-gray-200 font-medium']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'date_reception','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Date de réception')),'class' => 'text-gray-200 font-medium']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'date_reception','class' => 'block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30','type' => 'date','name' => 'date_reception','value' => old('date_reception', date('Y-m-d')),'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'date_reception','class' => 'block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30','type' => 'date','name' => 'date_reception','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('date_reception', date('Y-m-d'))),'required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('date_reception'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('date_reception')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                            </div>

                            <!-- Date butoir -->
                            <div>
                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'date_butoir','value' => __('Date butoir'),'class' => 'text-gray-200 font-medium']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'date_butoir','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Date butoir')),'class' => 'text-gray-200 font-medium']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'date_butoir','class' => 'block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30','type' => 'date','name' => 'date_butoir','value' => old('date_butoir')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'date_butoir','class' => 'block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30','type' => 'date','name' => 'date_butoir','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('date_butoir'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                                <p class="text-xs text-accent-tertiary mt-2">Obligatoire pour SAV / Réparation, optionnel pour Vente / Achat client</p>
                                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('date_butoir'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('date_butoir')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                            </div>

                            <!-- État -->
                            <div>
                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'etat','value' => __('État'),'class' => 'text-gray-200 font-medium']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'etat','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('État')),'class' => 'text-gray-200 font-medium']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                <select id="etat" name="etat" class="block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30">
                                    <option value="non_commence" <?php echo e(old('etat') == 'non_commence' ? 'selected' : ''); ?>>Non commencé</option>
                                    <option value="en_cours" <?php echo e(old('etat') == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                                    <option value="termine" <?php echo e(old('etat') == 'termine' ? 'selected' : ''); ?>>Terminé</option>
                                </select>
                                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('etat'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('etat')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                            </div>
                            
                            <!-- Séparateur -->
                            <div class="md:col-span-2 border-t border-gray-700/30 my-6"></div>
                            
                            <!-- Type de projet (prédéfini selon la sélection) -->
                            <div>
                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'type_projet','value' => __('Type de projet'),'class' => 'text-gray-200 font-medium']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'type_projet','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Type de projet')),'class' => 'text-gray-200 font-medium']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                <select id="type_projet" name="is_client_achat" class="block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30">
                                    <?php if(isset($type) && $type === 'vente'): ?>
                                        <option value="1" selected>Vente / Achat client</option>
                                    <?php else: ?>
                                        <option value="0" selected>SAV / Réparation</option>
                                    <?php endif; ?>
                                </select>
                                <p class="text-xs text-accent-tertiary mt-2">Le type de projet est prédéfini selon votre choix précédent</p>
                                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('is_client_achat'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('is_client_achat')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                            </div>
                            
                            <!-- Garantie -->
                            <div>
                                <div class="flex items-center mt-7 p-2 rounded-lg hover:bg-gray-700/20 transition-colors">
                                    <input type="checkbox" id="is_under_warranty" name="is_under_warranty" value="1" <?php echo e(old('is_under_warranty') ? 'checked' : ''); ?> class="rounded bg-gray-700/50 border-gray-600/50 text-accent-primary focus:ring-accent-primary/30 h-5 w-5">
                                    <label for="is_under_warranty" class="ml-3 text-gray-200 font-medium">Produit sous garantie</label>
                                </div>
                            </div>
                            
                            <!-- Options de garantie (affichées seulement si sous garantie) -->
                            <div id="warranty_options" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 mt-2 bg-accent-blue/5 p-6 rounded-xl border border-accent-blue/20" style="display: none;">
                                <div>
                                    <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'warranty_end_date','value' => __('Date de fin de garantie'),'class' => 'text-gray-200 font-medium']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'warranty_end_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Date de fin de garantie')),'class' => 'text-gray-200 font-medium']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'warranty_end_date','class' => 'block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30','type' => 'date','name' => 'warranty_end_date','value' => old('warranty_end_date')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'warranty_end_date','class' => 'block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30','type' => 'date','name' => 'warranty_end_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('warranty_end_date'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                                </div>
                                
                                <div>
                                    <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'warranty_type','value' => __('Type de garantie'),'class' => 'text-gray-200 font-medium']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'warranty_type','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Type de garantie')),'class' => 'text-gray-200 font-medium']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                    <select id="warranty_type" name="warranty_type" class="block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30">
                                        <option value="">-- Sélectionnez --</option>
                                        <option value="standard" <?php echo e(old('warranty_type') == 'standard' ? 'selected' : ''); ?>>Standard (1 an)</option>
                                        <option value="extended" <?php echo e(old('warranty_type') == 'extended' ? 'selected' : ''); ?>>Étendue (2 ans)</option>
                                        <option value="premium" <?php echo e(old('warranty_type') == 'premium' ? 'selected' : ''); ?>>Premium (3 ans)</option>
                                        <option value="custom" <?php echo e(old('warranty_type') == 'custom' ? 'selected' : ''); ?>>Personnalisée</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <?php if (isset($component)) { $__componentOriginald4c6978101b1c254eb70511d3c21c03f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald4c6978101b1c254eb70511d3c21c03f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.action-button','data' => ['tag' => 'a','href' => ''.e(route('chantiers.index')).'','color' => 'dark','class' => 'mr-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('action-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'a','href' => ''.e(route('chantiers.index')).'','color' => 'dark','class' => 'mr-3']); ?>
                                <?php echo e(__('Annuler')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald4c6978101b1c254eb70511d3c21c03f)): ?>
<?php $attributes = $__attributesOriginald4c6978101b1c254eb70511d3c21c03f; ?>
<?php unset($__attributesOriginald4c6978101b1c254eb70511d3c21c03f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald4c6978101b1c254eb70511d3c21c03f)): ?>
<?php $component = $__componentOriginald4c6978101b1c254eb70511d3c21c03f; ?>
<?php unset($__componentOriginald4c6978101b1c254eb70511d3c21c03f); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginald4c6978101b1c254eb70511d3c21c03f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald4c6978101b1c254eb70511d3c21c03f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.action-button','data' => ['tag' => 'button','type' => 'submit','color' => 'primary','class' => 'flex items-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('action-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'button','type' => 'submit','color' => 'primary','class' => 'flex items-center']); ?>
                                <?php echo e(__('Suivant')); ?>

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald4c6978101b1c254eb70511d3c21c03f)): ?>
<?php $attributes = $__attributesOriginald4c6978101b1c254eb70511d3c21c03f; ?>
<?php unset($__attributesOriginald4c6978101b1c254eb70511d3c21c03f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald4c6978101b1c254eb70511d3c21c03f)): ?>
<?php $component = $__componentOriginald4c6978101b1c254eb70511d3c21c03f; ?>
<?php unset($__componentOriginald4c6978101b1c254eb70511d3c21c03f); ?>
<?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour créer un client -->
    <div id="createClientModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom glassmorphism rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/10">
                <div class="px-6 pt-6 pb-4 sm:p-8">
                    <h3 class="text-xl font-medium text-white mb-5 flex items-center" id="modal-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Créer un nouveau client
                    </h3>
                    <form id="createClientForm" class="mt-4">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-200">Nom</label>
                                <input type="text" name="nom" id="nom" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-200">Prénom</label>
                                <input type="text" name="prenom" id="prenom" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div>
                                <label for="societe" class="block text-sm font-medium text-gray-200">Société</label>
                                <input type="text" name="societe" id="societe" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30">
                            </div>
                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-200">Téléphone</label>
                                <input type="text" name="telephone" id="telephone" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-200">Email</label>
                                <input type="email" name="email" id="email" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="adresse" class="block text-sm font-medium text-gray-200">Adresse</label>
                                <input type="text" name="adresse" id="adresse" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div>
                                <label for="code_postal" class="block text-sm font-medium text-gray-200">Code Postal</label>
                                <input type="text" name="code_postal" id="code_postal" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div>
                                <label for="ville" class="block text-sm font-medium text-gray-200">Ville</label>
                                <input type="text" name="ville" id="ville" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="pays" class="block text-sm font-medium text-gray-200">Pays</label>
                                <input type="text" name="pays" id="pays" value="France" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-200">Notes</label>
                                <textarea name="notes" id="notes" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30 resize-none" rows="2"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="px-6 py-4 sm:px-8 sm:py-5 border-t border-gray-700/30 sm:flex sm:flex-row-reverse">
                    <?php if (isset($component)) { $__componentOriginald4c6978101b1c254eb70511d3c21c03f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald4c6978101b1c254eb70511d3c21c03f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.action-button','data' => ['tag' => 'button','id' => 'submitCreateClient','color' => 'primary','class' => 'w-full sm:w-auto flex items-center justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('action-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'button','id' => 'submitCreateClient','color' => 'primary','class' => 'w-full sm:w-auto flex items-center justify-center']); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Créer le client
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald4c6978101b1c254eb70511d3c21c03f)): ?>
<?php $attributes = $__attributesOriginald4c6978101b1c254eb70511d3c21c03f; ?>
<?php unset($__attributesOriginald4c6978101b1c254eb70511d3c21c03f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald4c6978101b1c254eb70511d3c21c03f)): ?>
<?php $component = $__componentOriginald4c6978101b1c254eb70511d3c21c03f; ?>
<?php unset($__componentOriginald4c6978101b1c254eb70511d3c21c03f); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginald4c6978101b1c254eb70511d3c21c03f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald4c6978101b1c254eb70511d3c21c03f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.action-button','data' => ['tag' => 'button','id' => 'closeClientModal','color' => 'dark','class' => 'mt-3 sm:mt-0 sm:mr-3 w-full sm:w-auto flex items-center justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('action-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'button','id' => 'closeClientModal','color' => 'dark','class' => 'mt-3 sm:mt-0 sm:mr-3 w-full sm:w-auto flex items-center justify-center']); ?>
                        Annuler
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald4c6978101b1c254eb70511d3c21c03f)): ?>
<?php $attributes = $__attributesOriginald4c6978101b1c254eb70511d3c21c03f; ?>
<?php unset($__attributesOriginald4c6978101b1c254eb70511d3c21c03f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald4c6978101b1c254eb70511d3c21c03f)): ?>
<?php $component = $__componentOriginald4c6978101b1c254eb70511d3c21c03f; ?>
<?php unset($__componentOriginald4c6978101b1c254eb70511d3c21c03f); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteneur pour les notifications toast -->
    <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

    <!-- Scripts pour la modal et les notifications -->
    <script>
        // Fonction pour afficher une notification toast
        function showToast(message, type = 'success', duration = 3000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            // Déterminer la classe de couleur en fonction du type
            let bgColor, textColor, icon;
            if (type === 'success') {
                bgColor = 'bg-accent-primary/90';
                textColor = 'text-white';
                icon = '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else if (type === 'error') {
                bgColor = 'bg-error/90';
                textColor = 'text-white';
                icon = '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            } else {
                bgColor = 'bg-accent-blue/90';
                textColor = 'text-white';
                icon = '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }
            
            // Construire le HTML du toast
            toast.className = `${bgColor} ${textColor} px-4 py-3 rounded-xl shadow-lg mb-3 flex items-center transform transition-all duration-300 ease-in-out translate-x-full opacity-0 backdrop-blur-sm`;
            toast.innerHTML = `
                ${icon}
                <div>${message}</div>
            `;
            
            // Ajouter au container
            container.appendChild(toast);
            
            // Animation d'entrée
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            // Animation de sortie et suppression
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    container.removeChild(toast);
                }, 300);
            }, duration);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des options de garantie
            const isUnderWarrantyCheckbox = document.getElementById('is_under_warranty');
            const warrantyOptionsContainer = document.getElementById('warranty_options');
            const typeProjetSelect = document.getElementById('type_projet');
            
            // Initialisation spécifique au type de projet
            <?php if(isset($type) && $type === 'vente'): ?>
                // Pour un projet de vente, pré-cocher la garantie
                if (!isUnderWarrantyCheckbox.checked) {
                    isUnderWarrantyCheckbox.checked = true;
                }
                
                // Suggérer une date de fin de garantie par défaut (+1 an)
                const warrantyEndDateInput = document.getElementById('warranty_end_date');
                if (!warrantyEndDateInput.value) {
                    const today = new Date();
                    today.setFullYear(today.getFullYear() + 1);
                    warrantyEndDateInput.value = today.toISOString().split('T')[0];
                }
                
                // Afficher les options de garantie
                warrantyOptionsContainer.style.display = 'grid';
            <?php endif; ?>
            
            // Fonction pour gérer l'affichage des options de garantie
            function toggleWarrantyOptions() {
                if (isUnderWarrantyCheckbox.checked) {
                    warrantyOptionsContainer.style.display = 'grid';
                    
                    // Si c'est une vente client, suggérer une date de fin de garantie par défaut
                    if (typeProjetSelect.value === '1') {
                        const warrantyEndDateInput = document.getElementById('warranty_end_date');
                        if (!warrantyEndDateInput.value) {
                            const today = new Date();
                            // Par défaut, garantie d'un an pour les nouveaux achats
                            today.setFullYear(today.getFullYear() + 1);
                            warrantyEndDateInput.value = today.toISOString().split('T')[0];
                        }
                    }
                } else {
                    warrantyOptionsContainer.style.display = 'none';
                }
            }
            
            // Initialiser l'état à partir de l'état actuel du checkbox
            toggleWarrantyOptions();
            
            // Ajouter les écouteurs d'événements
            isUnderWarrantyCheckbox.addEventListener('change', toggleWarrantyOptions);
            
            // Gérer le champ date_butoir
            const dateButoir = document.getElementById('date_butoir');
            
            // Fonction pour gérer l'obligation du champ date_butoir en fonction du type de projet
            function toggleDateButoir() {
                if (typeProjetSelect.value === '0') { // SAV / Réparation
                    dateButoir.setAttribute('required', 'required');
                } else { // Vente / Achat client
                    dateButoir.removeAttribute('required');
                }
            }
            
            // Initialiser l'état du champ date_butoir
            toggleDateButoir();
            
            // Définir un comportement conditionnel pour le type de projet
            typeProjetSelect.addEventListener('change', function() {
                // Pour un achat client, présélectionner automatiquement la garantie
                if (this.value === '1' && !isUnderWarrantyCheckbox.checked) {
                    isUnderWarrantyCheckbox.checked = true;
                    toggleWarrantyOptions();
                }
                
                // Mettre à jour l'obligation du champ date_butoir
                toggleDateButoir();
            });
            
            // Gestion du modal client
            const clientModal = document.getElementById('createClientModal');
            const openModalBtn = document.getElementById('openCreateClientModal');
            const closeModalBtn = document.getElementById('closeClientModal');
            const submitBtn = document.getElementById('submitCreateClient');
            const clientForm = document.getElementById('createClientForm');
            const clientSelect = document.getElementById('client_id');

            // Ouvrir la modal
            openModalBtn.addEventListener('click', function(e) {
                e.preventDefault();
                clientModal.classList.remove('hidden');
            });

            // Fermer la modal
            closeModalBtn.addEventListener('click', function() {
                clientModal.classList.add('hidden');
            });

            // Soumettre le formulaire
            submitBtn.addEventListener('click', function() {
                const formData = new FormData(clientForm);
                
                fetch('<?php echo e(route("chantiers.store.client-ajax")); ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Ajouter le nouveau client au sélecteur
                        const newOption = document.createElement('option');
                        newOption.value = data.client.id;
                        newOption.textContent = data.client.nom_complet + ' (' + (data.client.societe || 'Sans société') + ')';
                        newOption.selected = true;
                        clientSelect.appendChild(newOption);
                        
                        // Fermer la modal
                        clientModal.classList.add('hidden');
                        
                        // Réinitialiser le formulaire
                        clientForm.reset();
                        
                        // Notification élégante
                        showToast(data.message, 'success');
                    } else {
                        showToast('Erreur: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showToast('Une erreur est survenue lors de la création du client', 'error');
                });
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/chantiers/create_step1.blade.php ENDPATH**/ ?>