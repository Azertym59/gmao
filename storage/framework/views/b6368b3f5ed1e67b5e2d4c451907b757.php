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
            <?php echo e(__('Finalisation de l\'intervention')); ?>

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
                                    <div class="absolute top-0 right-0 -mr-2">
                                        <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-gray-300">Réparation</div>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-gray-300 font-bold">Finalisation</div>
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
                    </div>

                    <!-- Résumé du diagnostic -->
                    <div class="mb-4 bg-blue-900/20 p-4 rounded-lg border border-blue-500/20">
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

                    <!-- Résumé de la réparation -->
                    <div class="mb-6 bg-green-900/20 p-4 rounded-lg border border-green-500/20">
                        <h3 class="font-medium text-green-300 mb-2">Réparation effectuée</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">LEDs remplacées:</span> <?php echo e($reparation->nb_leds_remplacees); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">ICs remplacés:</span> <?php echo e($reparation->nb_ic_remplaces); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-300"><span class="font-semibold">Masques remplacés:</span> <?php echo e($reparation->nb_masques_remplaces); ?></p>
                            </div>
                        </div>
                        <?php if($reparation->remarques): ?>
                            <div class="mt-2">
                                <p class="text-gray-300"><span class="font-semibold">Remarques:</span> <?php echo e($reparation->remarques); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Temps total -->
                    <div class="mb-8 p-6 glassmorphism rounded-lg text-center border border-gray-700">
                        <h3 class="text-xl font-bold mb-4 text-white">Temps total d'intervention</h3>
                        <div id="temps-total" class="text-5xl font-mono mb-6 text-accent-yellow">
                            <?php echo e(sprintf('%02d:%02d:%02d', 
                                floor($intervention->temps_total / 3600),
                                floor(($intervention->temps_total % 3600) / 60),
                                $intervention->temps_total % 60)); ?>

                        </div>
                    </div>

                    <form id="finalisation-form" method="POST" action="<?php echo e(route('interventions.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="intervention_id" value="<?php echo e($intervention->id); ?>">
                        <input type="hidden" id="temps_total" name="temps_total" value="<?php echo e($intervention->temps_total); ?>">
                        
                        <!-- Champs cachés pour le diagnostic -->
                        <input type="hidden" name="diagnostic_nb_leds_hs" value="<?php echo e($diagnostic->nb_leds_hs); ?>">
                        <input type="hidden" name="diagnostic_nb_ic_hs" value="<?php echo e($diagnostic->nb_ic_hs); ?>">
                        <input type="hidden" name="diagnostic_nb_masques_hs" value="<?php echo e($diagnostic->nb_masques_hs); ?>">
                        <input type="hidden" name="diagnostic_remarques" value="<?php echo e($diagnostic->remarques); ?>">
                        <input type="hidden" name="diagnostic_pose_fake_pcb" value="<?php echo e($diagnostic->pose_fake_pcb ? 1 : 0); ?>">
                        
                        <!-- Champs cachés pour la réparation -->
                        <input type="hidden" name="reparation_nb_leds_remplacees" value="<?php echo e($reparation->nb_leds_remplacees); ?>">
                        <input type="hidden" name="reparation_nb_ic_remplaces" value="<?php echo e($reparation->nb_ic_remplaces); ?>">
                        <input type="hidden" name="reparation_nb_masques_remplaces" value="<?php echo e($reparation->nb_masques_remplaces); ?>">
                        <input type="hidden" name="reparation_remarques" value="<?php echo e($reparation->remarques); ?>">
                        <input type="hidden" name="reparation_fake_pcb_pose" value="<?php echo e($reparation->fake_pcb_pose ? 1 : 0); ?>">

                        <div class="grid grid-cols-1 gap-6 mb-6">
                            <div>
                                <h4 class="font-medium text-gray-300 mb-4">État final du module</h4>
                                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                    <!-- État du module après intervention -->
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'module_etat','value' => __('État du module après intervention'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'module_etat','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('État du module après intervention')),'class' => 'text-gray-300']); ?>
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
                                        <select id="module_etat" name="module_etat" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50">
                                            <option value="termine" selected>Réparé</option>
                                            <option value="defaillant">Défaillant (partiellement fonctionnel)</option>
                                            <option value="hs">Hors service (irrécupérable)</option>
                                            <option value="en_attente">En attente de pièces</option>
                                        </select>
                                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('module_etat'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('module_etat')),'class' => 'mt-2']); ?>
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
                                    
                                    <div class="mt-4">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'notes_finales','value' => __('Notes finales sur l\'intervention'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'notes_finales','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Notes finales sur l\'intervention')),'class' => 'text-gray-300']); ?>
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
                                        <textarea id="notes_finales" name="notes_finales" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" rows="3"><?php echo e(old('notes_finales')); ?></textarea>
                                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('notes_finales'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('notes_finales')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['type' => 'submit','class' => 'bg-green-600 hover:bg-green-700 flex items-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'bg-green-600 hover:bg-green-700 flex items-center']); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <?php echo e(__('Terminer l\'intervention')); ?>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier que les champs diagnostic et réparation sont bien présents
            const form = document.getElementById('finalisation-form');
            
            form.addEventListener('submit', function(e) {
                // Logs pour débogage (visibles dans la console du navigateur)
                console.log('Soumission du formulaire de finalisation:');
                console.log('diagnostic_nb_leds_hs:', form.querySelector('[name="diagnostic_nb_leds_hs"]').value);
                console.log('diagnostic_nb_ic_hs:', form.querySelector('[name="diagnostic_nb_ic_hs"]').value);
                console.log('diagnostic_nb_masques_hs:', form.querySelector('[name="diagnostic_nb_masques_hs"]').value);
                console.log('reparation_nb_leds_remplacees:', form.querySelector('[name="reparation_nb_leds_remplacees"]').value);
                console.log('reparation_nb_ic_remplaces:', form.querySelector('[name="reparation_nb_ic_remplaces"]').value);
                console.log('reparation_nb_masques_remplaces:', form.querySelector('[name="reparation_nb_masques_remplaces"]').value);
                
                // On pourrait ajouter une validation ici si nécessaire
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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/interventions/step3_finalisation.blade.php ENDPATH**/ ?>