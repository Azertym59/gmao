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
            <?php echo e(__('Réparation du module')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <!-- Barre de progression de l'intervention -->
                    <div class="mb-6">
                        <div class="flex mb-4">
                            <div class="w-1/3 text-center">
                                <div class="relative mb-2">
                                    <div class="w-10 h-10 mx-auto rounded-full text-lg flex items-center justify-center bg-blue-500 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div class="absolute top-0 right-0 -mr-2">
                                        <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-gray-300">Diagnostic</div>
                            </div>
                            <div class="w-1/3 text-center">
                                <div class="relative mb-2">
                                    <div class="absolute top-0 -ml-10 text-center mt-4 w-32">
                                        <div class="flex items-center justify-center">
                                            <div class="w-full bg-gray-600 rounded items-center align-middle">
                                                <div class="bg-blue-500 h-1 rounded" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 mx-auto rounded-full text-lg flex items-center justify-center bg-blue-500 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    </div>
                                    <!-- Pas de coche verte, nous sommes à cette étape -->
                                </div>
                                <div class="text-gray-300 font-bold">Réparation</div>
                            </div>
                            <div class="w-1/3 text-center">
                                <div class="relative mb-2">
                                    <div class="absolute top-0 -ml-10 text-center mt-4 w-32">
                                        <div class="flex items-center justify-center">
                                            <div class="w-full bg-gray-600 rounded items-center align-middle">
                                                <div class="bg-blue-500 h-1 rounded" style="width: 0%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-10 h-10 mx-auto rounded-full text-lg flex items-center justify-center bg-gray-600 text-white opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-gray-500">Finalisation</div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations du module -->
                    <div class="mb-6 bg-indigo-900/30 p-4 rounded-lg border border-indigo-500/30">
                        <h3 class="font-medium text-indigo-300 mb-2">Module #<?php echo e($module->id); ?></h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">Dimensions:</span> <?php echo e($module->largeur); ?>×<?php echo e($module->hauteur); ?> mm</p>
                                <p class="text-gray-300"><span class="font-semibold">Résolution:</span> <?php echo e($module->nb_pixels_largeur); ?>×<?php echo e($module->nb_pixels_hauteur); ?> pixels</p>
                            </div>
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">Produit:</span> <?php echo e($module->dalle->produit->marque); ?> <?php echo e($module->dalle->produit->modele); ?></p>
                                <p class="text-gray-300"><span class="font-semibold">Dalle:</span> #<?php echo e($module->dalle->id); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">Chantier:</span> <?php echo e($module->dalle->produit->chantier->nom); ?></p>
                                <p class="text-gray-300"><span class="font-semibold">Client:</span> <?php echo e($module->dalle->produit->chantier->client->nom_complet); ?></p>
                            </div>
                        </div>
                        
                        <?php if($module->dalle->produit->ledDatasheet): ?>
                        <div class="mt-4 pt-4 border-t border-indigo-500/30 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-indigo-300 mb-2">LED Datasheet</h4>
                                <p class="text-gray-300"><span class="font-semibold">Type:</span> <?php echo e($module->dalle->produit->ledDatasheet->type); ?> <?php echo e($module->dalle->produit->ledDatasheet->reference); ?></p>
                                <p class="text-gray-300"><span class="font-semibold">Pôles:</span> <?php echo e($module->dalle->produit->ledDatasheet->nb_poles); ?></p>
                                <p class="text-gray-300"><span class="font-semibold">Notes:</span> <?php echo e($module->dalle->produit->ledDatasheet->notes); ?></p>
                            </div>
                            <?php if($module->dalle->produit->ledDatasheet->image_data): ?>
                            <div class="flex justify-center">
                                <div class="bg-white p-2 rounded">
                                    <img src="<?php echo e($module->dalle->produit->ledDatasheet->image_data); ?>" alt="LED Datasheet" class="h-60 w-auto">
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Résumé du diagnostic -->
                    <div class="mb-6 bg-blue-900/20 p-4 rounded-lg border border-blue-500/20">
                        <h3 class="font-medium text-blue-300 mb-2">Diagnostic effectué</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">LEDs HS:</span> <?php echo e($diagnostic->nb_leds_hs); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">ICs HS:</span> <?php echo e($diagnostic->nb_ic_hs); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">Masques HS:</span> <?php echo e($diagnostic->nb_masques_hs); ?></p>
                            </div>
                        </div>
                        <?php if($diagnostic->remarques): ?>
                            <div class="mt-2">
                                <p class="text-gray-300"><span class="font-semibold">Remarques:</span> <?php echo e($diagnostic->remarques); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Chronomètre -->
                    <div class="mb-8 p-6 glassmorphism rounded-lg text-center border border-gray-700">
                        <h3 class="text-xl font-bold mb-4 text-white">Temps d'intervention</h3>
                        <div id="chronometre" class="text-5xl font-mono mb-6 text-accent-yellow">00:00:00</div>
                        <div class="flex justify-center space-x-4">
                            <button id="btn-pause" class="px-6 py-3 bg-yellow-600 text-white rounded-lg shadow-lg hover:bg-yellow-700 transition-colors flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Pause
                            </button>
                            <button id="btn-resume" class="px-6 py-3 bg-green-600 text-white rounded-lg shadow-lg hover:bg-green-700 transition-colors hidden flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                </svg>
                                Reprendre
                            </button>
                        </div>
                    </div>

                    <form id="reparation-form" method="POST" action="<?php echo e(route('interventions.store.reparation', $intervention->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="intervention_id" value="<?php echo e($intervention->id); ?>">
                        <input type="hidden" id="temps_total" name="temps_total" value="<?php echo e($intervention->temps_total); ?>">

                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <div>
                                <h4 class="font-medium text-gray-300 mb-4">Réparations effectuées</h4>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'reparation_nb_leds_remplacees','value' => __('Nombre de LEDs remplacées'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'reparation_nb_leds_remplacees','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre de LEDs remplacées')),'class' => 'text-gray-300']); ?>
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
                                            <input id="reparation_nb_leds_remplacees" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="reparation_nb_leds_remplacees" value="<?php echo e(old('reparation_nb_leds_remplacees', $intervention->reparation->nb_leds_remplacees ?? $diagnostic->nb_leds_hs ?? 0)); ?>" min="0" required />
                                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('reparation_nb_leds_remplacees'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('reparation_nb_leds_remplacees')),'class' => 'mt-2']); ?>
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

                                        <div>
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'reparation_nb_ic_remplaces','value' => __('Nombre d\'ICs remplacés'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'reparation_nb_ic_remplaces','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre d\'ICs remplacés')),'class' => 'text-gray-300']); ?>
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
                                            <input id="reparation_nb_ic_remplaces" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="reparation_nb_ic_remplaces" value="<?php echo e(old('reparation_nb_ic_remplaces', $intervention->reparation->nb_ic_remplaces ?? $diagnostic->nb_ic_hs ?? 0)); ?>" min="0" required />
                                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('reparation_nb_ic_remplaces'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('reparation_nb_ic_remplaces')),'class' => 'mt-2']); ?>
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

                                        <div>
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'reparation_nb_masques_remplaces','value' => __('Nombre de masques remplacés'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'reparation_nb_masques_remplaces','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre de masques remplacés')),'class' => 'text-gray-300']); ?>
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
                                            <input id="reparation_nb_masques_remplaces" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="reparation_nb_masques_remplaces" value="<?php echo e(old('reparation_nb_masques_remplaces', $intervention->reparation->nb_masques_remplaces ?? $diagnostic->nb_masques_hs ?? 0)); ?>" min="0" required />
                                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('reparation_nb_masques_remplaces'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('reparation_nb_masques_remplaces')),'class' => 'mt-2']); ?>
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
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="inline-flex items-center text-gray-300">
                                            <input type="checkbox" id="reparation_fake_pcb_pose" name="reparation_fake_pcb_pose" value="1" class="rounded bg-gray-700 border-gray-600 text-indigo-600 focus:ring-indigo-500" 
                                                <?php echo e(old('reparation_fake_pcb_pose', $intervention->reparation->fake_pcb_pose ?? $diagnostic->pose_fake_pcb ?? false) ? 'checked' : ''); ?>>
                                            <span class="ml-2">Fake PCB posé</span>
                                        </label>
                                    </div>

                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'reparation_remarques','value' => __('Remarques (actions effectuées)'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'reparation_remarques','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Remarques (actions effectuées)')),'class' => 'text-gray-300']); ?>
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
                                        <textarea id="reparation_remarques" name="reparation_remarques" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" rows="3"><?php echo e(old('reparation_remarques', $intervention->reparation->remarques ?? '')); ?></textarea>
                                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('reparation_remarques'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('reparation_remarques')),'class' => 'mt-2']); ?>
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
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['type' => 'submit','class' => 'bg-blue-600 hover:bg-blue-700 flex items-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'bg-blue-600 hover:bg-blue-700 flex items-center']); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?php echo e(__('Passer à la finalisation')); ?>

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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script du chronomètre -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const interventionId = <?php echo e($intervention->id); ?>;
            const chronometre = document.getElementById('chronometre');
            const btnPause = document.getElementById('btn-pause');
            const btnResume = document.getElementById('btn-resume');
            const tempsTotal = document.getElementById('temps_total');
            
            let secondes = <?php echo e($intervention->temps_total); ?>;
            let interval;
            let enPause = false;
            
            // Fonction pour formater le temps
            function formatTemps(totalSecondes) {
                const heures = Math.floor(totalSecondes / 3600);
                const minutes = Math.floor((totalSecondes % 3600) / 60);
                const secondes = totalSecondes % 60;
                
                return `${String(heures).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secondes).padStart(2, '0')}`;
            }
            
            // Fonction pour mettre à jour l'affichage du chronomètre
            function mettreAJourChronometre() {
                secondes++;
                chronometre.textContent = formatTemps(secondes);
                tempsTotal.value = secondes;
            }
            
            // Démarrer le chronomètre
            function demarrerChronometre() {
                interval = setInterval(mettreAJourChronometre, 1000);
            }
            
            // Mettre en pause le chronomètre
            function mettreEnPause() {
                clearInterval(interval);
                enPause = true;
                btnPause.classList.add('hidden');
                btnResume.classList.remove('hidden');
                
                // Appel Ajax pour enregistrer la pause
                fetch(`/interventions/${interventionId}/pause`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        secondes = data.temps_total;
                        chronometre.textContent = data.temps_formate;
                        tempsTotal.value = secondes;
                    }
                })
                .catch(error => console.error('Erreur:', error));
            }
            
            // Reprendre le chronomètre
            function reprendreChronometre() {
                demarrerChronometre();
                enPause = false;
                btnResume.classList.add('hidden');
                btnPause.classList.remove('hidden');
                
                // Appel Ajax pour enregistrer la reprise
                fetch(`/interventions/${interventionId}/resume`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reprise réussie
                    }
                })
                .catch(error => console.error('Erreur:', error));
            }
            
            // Événements des boutons
            btnPause.addEventListener('click', mettreEnPause);
            btnResume.addEventListener('click', reprendreChronometre);
            
            // Initialiser l'affichage
            chronometre.textContent = formatTemps(secondes);
            
            // Démarrer le chronomètre au chargement de la page
            demarrerChronometre();
            
            // S'assurer que le temps total est à jour lors de la soumission du formulaire
            document.getElementById('reparation-form').addEventListener('submit', function(e) {
                // Assurer que le temps total est à jour
                tempsTotal.value = secondes;
                
                // Forcer la conversion des champs numériques en nombres entiers
                const nbLedsRemplacees = document.getElementById('reparation_nb_leds_remplacees');
                const nbIcRemplaces = document.getElementById('reparation_nb_ic_remplaces');
                const nbMasquesRemplaces = document.getElementById('reparation_nb_masques_remplaces');
                
                // Assurer que les valeurs sont des nombres positifs
                nbLedsRemplacees.value = Math.max(0, parseInt(nbLedsRemplacees.value) || 0);
                nbIcRemplaces.value = Math.max(0, parseInt(nbIcRemplaces.value) || 0);
                nbMasquesRemplaces.value = Math.max(0, parseInt(nbMasquesRemplaces.value) || 0);
                
                // Debugger avec console
                console.log('Soumission du formulaire de réparation avec valeurs:');
                console.log('LEDs remplacées:', nbLedsRemplacees.value);
                console.log('ICs remplacés:', nbIcRemplaces.value);
                console.log('Masques remplacés:', nbMasquesRemplaces.value);
            });
            
            // Toggle datasheet
            const toggleDatasheetBtn = document.getElementById('toggle-datasheet');
            const datasheetContent = document.getElementById('datasheet-content');
            
            if (toggleDatasheetBtn && datasheetContent) {
                toggleDatasheetBtn.addEventListener('click', function() {
                    datasheetContent.classList.toggle('hidden');
                    const isHidden = datasheetContent.classList.contains('hidden');
                    
                    toggleDatasheetBtn.innerHTML = isHidden 
                        ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                           </svg> Afficher`
                        : `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                           </svg> Masquer`;
                });
            }
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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/interventions/step2_reparation.blade.php ENDPATH**/ ?>