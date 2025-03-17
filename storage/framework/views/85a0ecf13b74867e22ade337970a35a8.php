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
            <?php echo e(__('Créer un chantier - Étape 3/5 : Produit et composition')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Étapes de progression -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="w-full flex items-center">
                        <div class="relative flex flex-col items-center text-green-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-green-500 border-green-500 text-white font-bold flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-green-500">
                                <span class="font-bold">Client</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-500"></div>
                        <div class="relative flex flex-col items-center text-green-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-green-500 border-green-500 text-white font-bold flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-green-500">
                                <span class="font-bold">Chantier</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-500"></div>
                        <div class="relative flex flex-col items-center text-accent-blue">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-accent-blue border-accent-blue text-white font-bold flex items-center justify-center">
                                3
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-accent-blue">
                                <span class="font-bold">Produit</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-700"></div>
                        <div class="relative flex flex-col items-center text-gray-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-700 text-gray-400 font-bold flex items-center justify-center">
                                4
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-gray-500">
                                <span>Interventions</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-700"></div>
                        <div class="relative flex flex-col items-center text-gray-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-700 text-gray-400 font-bold flex items-center justify-center">
                                5
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-gray-500">
                                <span>Rapports</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <!-- Informations sur le produit sélectionné -->
                    <div class="mb-6 bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                        <h3 class="font-medium text-blue-300 mb-2">Produit sélectionné</h3>
                        <?php if($produitData['selection_type'] == 'existant' && $produitRef): ?>
                            <p class="text-gray-300"><span class="font-semibold">Produit:</span> <?php echo e($produitRef->marque); ?> <?php echo e($produitRef->modele); ?></p>
                            <p class="text-gray-300"><span class="font-semibold">Pitch:</span> <?php echo e($produitRef->pitch); ?> mm</p>
                            <p class="text-gray-300"><span class="font-semibold">Utilisation:</span> <?php echo e($produitRef->utilisation == 'indoor' ? 'Indoor' : 'Outdoor'); ?></p>
                            <p class="text-gray-300"><span class="font-semibold">Électronique:</span> 
                                <?php if($produitRef->electronique == 'autre'): ?>
                                    <?php echo e($produitRef->electronique_detail); ?>

                                <?php else: ?>
                                    <?php echo e(ucfirst($produitRef->electronique)); ?>

                                <?php endif; ?>
                            </p>
                        <?php else: ?>
                            <p class="text-gray-300"><span class="font-semibold">Produit:</span> <?php echo e($produitData['marque']); ?> <?php echo e($produitData['modele']); ?> (Nouveau)</p>
                            <p class="text-gray-300"><span class="font-semibold">Pitch:</span> <?php echo e($produitData['pitch']); ?> mm</p>
                            <p class="text-gray-300"><span class="font-semibold">Utilisation:</span> <?php echo e($produitData['utilisation'] == 'indoor' ? 'Indoor' : 'Outdoor'); ?></p>
                            <p class="text-gray-300"><span class="font-semibold">Électronique:</span> 
                                <?php if($produitData['electronique'] == 'autre'): ?>
                                    <?php echo e($produitData['electronique_detail']); ?>

                                <?php else: ?>
                                    <?php echo e(ucfirst($produitData['electronique'])); ?>

                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <form id="compositionForm" method="POST" action="<?php echo e(route('chantiers.store.step3')); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Sélection du mode -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-white mb-4">Mode de création</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="mode_flightcase" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50 <?php $__errorArgs = ['mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="flightcase_container">
                                        <input type="radio" name="mode" id="mode_flightcase" value="flightcase" class="mr-2 accent-accent-blue" checked>
                                        <div>
                                            <span class="font-medium text-white">Structure Flight Case</span>
                                            <p class="text-sm text-gray-400">Pour les envois structurés en flight cases contenant des dalles</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <div>
                                    <label for="mode_individuel" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50 <?php $__errorArgs = ['mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="individuel_container">
                                        <input type="radio" name="mode" id="mode_individuel" value="individuel" class="mr-2 accent-accent-blue">
                                        <div>
                                            <span class="font-medium text-white">Modules individuels</span>
                                            <p class="text-sm text-gray-400">Pour les envois de modules en pièces détachées</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <?php $__errorArgs = ['mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-sm mt-1"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <!-- Configuration Flight Case - visible seulement si mode=flightcase -->
                        <div id="flightcase_config" class="mb-6 p-4 border border-gray-700 rounded-xl bg-gray-800/30">
                            <h3 class="text-lg font-medium text-white mb-4">Configuration Flight Case</h3>
                            
                            <!-- Option pour tailles multiples -->
                            <div class="mb-4 p-3 bg-amber-900/20 border border-amber-600/30 rounded-lg">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="multiple_sizes" name="multiple_sizes" value="1" 
                                        class="rounded bg-gray-700 border-gray-600 text-accent-blue focus:ring-accent-blue focus:ring-opacity-50">
                                    <span class="ml-2 font-medium text-white">Tailles de dalles multiples</span>
                                </label>
                                <p class="mt-1 text-sm text-gray-300">Cochez cette option si vous avez différentes tailles de dalles dans le même chantier (par exemple, des dalles 500x500 et des dalles 500x1000).</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'nb_flightcases','value' => __('Nombre de Flight Cases'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'nb_flightcases','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre de Flight Cases')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'nb_flightcases','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_flightcases','value' => old('nb_flightcases', 1),'min' => '1','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'nb_flightcases','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_flightcases','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('nb_flightcases', 1)),'min' => '1','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('nb_flightcases'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('nb_flightcases')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'nb_dalles_par_flightcase','value' => __('Dalles par Flight Case'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'nb_dalles_par_flightcase','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Dalles par Flight Case')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'nb_dalles_par_flightcase','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_dalles_par_flightcase','value' => old('nb_dalles_par_flightcase', 8),'min' => '1','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'nb_dalles_par_flightcase','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_dalles_par_flightcase','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('nb_dalles_par_flightcase', 8)),'min' => '1','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('nb_dalles_par_flightcase'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('nb_dalles_par_flightcase')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'nb_modules_par_dalle','value' => __('Modules par Dalle'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'nb_modules_par_dalle','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Modules par Dalle')),'class' => 'text-gray-300']); ?>
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
                                    <div class="relative">
                                        <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'nb_modules_par_dalle','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_modules_par_dalle','value' => old('nb_modules_par_dalle', isset($produitData['disposition_modules']) ? 
                                        (preg_match('/^(\d+)x(\d+)$/', $produitData['disposition_modules'], $matches) ? ($matches[1] * $matches[2]) : 4) : 4),'min' => '1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'nb_modules_par_dalle','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_modules_par_dalle','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('nb_modules_par_dalle', isset($produitData['disposition_modules']) ? 
                                        (preg_match('/^(\d+)x(\d+)$/', $produitData['disposition_modules'], $matches) ? ($matches[1] * $matches[2]) : 4) : 4)),'min' => '1']); ?>
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
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <span class="text-xs text-amber-400">Variable selon le type de dalle</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Basé sur la disposition <?php echo e(isset($produitData['disposition_modules']) ? $produitData['disposition_modules'] : '2x2'); ?> par défaut, modifiable selon les besoins</p>
                                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('nb_modules_par_dalle'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('nb_modules_par_dalle')),'class' => 'mt-2']); ?>
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
                            
                            <!-- Configurations de flight cases par type de dalle -->
                            <div id="multiple_sizes_config" class="mt-5 pt-4 border-t border-gray-700" style="display:none;">
                                <h4 class="font-medium text-lg text-white mb-3">Configuration par type de dalle</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                                    <!-- Configuration 1 - Dalles 500x500 -->
                                    <div class="bg-blue-900/20 border border-blue-500/30 p-4 rounded-xl">
                                        <h5 class="font-medium text-blue-300 mb-3">Configuration 1 (500x500)</h5>
                                        <div class="grid grid-cols-1 gap-3">
                                            <div>
                                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'config1_nb_flightcases','value' => __('Nombre de Flight Cases'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'config1_nb_flightcases','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre de Flight Cases')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'config1_nb_flightcases','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'config1[nb_flightcases]','value' => old('config1.nb_flightcases', 1),'min' => '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'config1_nb_flightcases','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'config1[nb_flightcases]','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('config1.nb_flightcases', 1)),'min' => '0']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'config1_nb_dalles','value' => __('Dalles par Flight Case'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'config1_nb_dalles','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Dalles par Flight Case')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'config1_nb_dalles','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'config1[nb_dalles]','value' => old('config1.nb_dalles', 8),'min' => '1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'config1_nb_dalles','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'config1[nb_dalles]','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('config1.nb_dalles', 8)),'min' => '1']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'config1_modules_config','value' => __('Configuration des modules'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'config1_modules_config','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Configuration des modules')),'class' => 'text-gray-300']); ?>
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
                                                <select id="config1_modules_config" name="config1[modules_config]" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white rounded-md focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                    <option value="2x2">2x2 (4 modules par dalle)</option>
                                                    <option value="1x1">1x1 (1 module par dalle)</option>
                                                    <option value="3x3">3x3 (9 modules par dalle)</option>
                                                    <option value="4x4">4x4 (16 modules par dalle)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-400">
                                            <span id="config1_total">8 flight cases × 8 dalles × 4 modules = 256 modules</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Configuration 2 - Dalles 500x1000 -->
                                    <div class="bg-purple-900/20 border border-purple-500/30 p-4 rounded-xl">
                                        <h5 class="font-medium text-purple-300 mb-3">Configuration 2 (500x1000)</h5>
                                        <div class="grid grid-cols-1 gap-3">
                                            <div>
                                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'config2_nb_flightcases','value' => __('Nombre de Flight Cases'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'config2_nb_flightcases','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre de Flight Cases')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'config2_nb_flightcases','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'config2[nb_flightcases]','value' => old('config2.nb_flightcases', 1),'min' => '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'config2_nb_flightcases','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'config2[nb_flightcases]','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('config2.nb_flightcases', 1)),'min' => '0']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'config2_nb_dalles','value' => __('Dalles par Flight Case'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'config2_nb_dalles','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Dalles par Flight Case')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'config2_nb_dalles','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'config2[nb_dalles]','value' => old('config2.nb_dalles', 6),'min' => '1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'config2_nb_dalles','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'config2[nb_dalles]','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('config2.nb_dalles', 6)),'min' => '1']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'config2_modules_config','value' => __('Configuration des modules'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'config2_modules_config','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Configuration des modules')),'class' => 'text-gray-300']); ?>
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
                                                <select id="config2_modules_config" name="config2[modules_config]" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white rounded-md focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                    <option value="2x4">2x4 (8 modules 250x250)</option>
                                                    <option value="1x2">1x2 (2 modules 500x500)</option>
                                                    <option value="2x2">2x2 (4 modules 250x500)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-400">
                                            <span id="config2_total">1 flight cases × 6 dalles × 8 modules = 48 modules</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Configuration détaillée des FlightCases (mode standard) -->
                            <div id="standard_config" class="mt-6 bg-blue-900/20 border border-blue-500/30 p-4 rounded-xl">
                                <h4 class="font-medium text-blue-300 mb-3">Configuration détaillée des FlightCases</h4>
                                
                                <!-- Types de dalles disponibles -->
                                <?php if(isset($dalleTypes) && count($dalleTypes) > 0): ?>
                                <div class="mb-4 bg-gray-800/50 p-3 rounded-lg">
                                    <h5 class="text-sm font-medium text-white mb-2">Types de dalles disponibles</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                        <?php $__currentLoopData = $dalleTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="bg-gray-700/50 p-2 rounded border border-gray-600">
                                            <span class="block text-sm font-medium text-white"><?php echo e($type['nom']); ?></span>
                                            <span class="block text-xs text-gray-300"><?php echo e($type['largeur']); ?>x<?php echo e($type['hauteur']); ?>mm</span>
                                            <span class="block text-xs text-gray-400">Disposition: <?php echo e($type['disposition']); ?></span>
                                            <?php if($type['largeur'] == 500 && $type['hauteur'] == 1000): ?>
                                            <div class="mt-1 flex items-center">
                                                <span class="inline-block px-2 py-1 text-xs font-medium bg-amber-600/30 text-amber-300 rounded">Configuration des modules variable</span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div id="flightcases_detail_container" class="space-y-4">
                                    <!-- Ce div sera rempli dynamiquement par JavaScript -->
                                </div>
                            </div>
                            
                            <div class="mt-4 p-2 bg-gray-700/50 rounded-lg">
                                <p class="text-sm text-gray-300"><span id="total_modules_fc">32</span> modules seront créés au total.</p>
                            </div>
                        </div>
                        
                        <!-- Configuration Modules individuels - visible seulement si mode=individuel -->
                        <div id="individuel_config" class="mb-6 p-4 border border-gray-700 rounded-xl bg-gray-800/30" style="display: none;">
                            <h3 class="text-lg font-medium text-white mb-4">Configuration Modules individuels</h3>
                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'nb_modules_total','value' => __('Nombre total de modules'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'nb_modules_total','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre total de modules')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'nb_modules_total','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_modules_total','value' => old('nb_modules_total', 32),'min' => '1','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'nb_modules_total','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_modules_total','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('nb_modules_total', 32)),'min' => '1','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('nb_modules_total'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('nb_modules_total')),'class' => 'mt-2']); ?>
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
                        
                        
                        <div class="flex items-center justify-between mt-6">
                            <div>
                                <div id="total_modules_message" class="font-medium text-lg text-accent-blue">
                                    32 modules seront créés au total
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <a href="<?php echo e(route('chantiers.create.step2')); ?>" class="btn-action btn-secondary flex items-center mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    <?php echo e(__('Précédent')); ?>

                                </a>
                                <button type="submit" id="submitBtn" class="btn-action btn-primary flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    <?php echo e(__('Continuer')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modeFlightcaseRadio = document.getElementById('mode_flightcase');
            const modeIndividuelRadio = document.getElementById('mode_individuel');
            const flightcaseConfig = document.getElementById('flightcase_config');
            const individuelConfig = document.getElementById('individuel_config');
            const flightcaseContainer = document.getElementById('flightcase_container');
            const individuelContainer = document.getElementById('individuel_container');
            const totalModulesMessage = document.getElementById('total_modules_message');
            const totalModulesFc = document.getElementById('total_modules_fc');
            
            // Nouvel élément pour le mode de tailles multiples
            const multipleSizesCheckbox = document.getElementById('multiple_sizes');
            const multipleSizesConfig = document.getElementById('multiple_sizes_config');
            const standardConfig = document.getElementById('standard_config');
            
            // Champs pour le mode flight case
            const nbFlightcases = document.getElementById('nb_flightcases');
            const nbDallesParFlightcase = document.getElementById('nb_dalles_par_flightcase');
            const nbModulesParDalle = document.getElementById('nb_modules_par_dalle');
            
            // Champs pour la configuration 1 (500x500)
            const config1NbFlightcases = document.getElementById('config1_nb_flightcases');
            const config1NbDalles = document.getElementById('config1_nb_dalles');
            const config1ModulesConfig = document.getElementById('config1_modules_config');
            const config1Total = document.getElementById('config1_total');
            
            // Champs pour la configuration 2 (500x1000)
            const config2NbFlightcases = document.getElementById('config2_nb_flightcases');
            const config2NbDalles = document.getElementById('config2_nb_dalles');
            const config2ModulesConfig = document.getElementById('config2_modules_config');
            const config2Total = document.getElementById('config2_total');
            
            // Champ pour le mode individuel
            const nbModulesTotal = document.getElementById('nb_modules_total');
            
            // Champs pour la gestion des FlightCases détaillés
            const flightcasesDetailContainer = document.getElementById('flightcases_detail_container');
            
            // Récupérer la disposition des modules depuis les données du produit
            const dispositionModules = "<?php echo e(isset($produitData['disposition_modules']) ? $produitData['disposition_modules'] : '2x2'); ?>";
            let calculatedModulesPerDalle = 4; // Par défaut 2x2 = 4 modules
            
            // Calcul du nombre de modules par dalle en fonction de la disposition
            if (dispositionModules.match(/^(\d+)x(\d+)$/)) {
                const matches = dispositionModules.match(/^(\d+)x(\d+)$/);
                calculatedModulesPerDalle = parseInt(matches[1]) * parseInt(matches[2]);
            }
            
            // Gestionnaire d'événements pour le mode de tailles multiples
            multipleSizesCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    multipleSizesConfig.style.display = 'block';
                    standardConfig.style.display = 'none';
                    nbFlightcases.disabled = true;
                    nbDallesParFlightcase.disabled = true;
                    updateMultipleSizesTotal();
                } else {
                    multipleSizesConfig.style.display = 'none';
                    standardConfig.style.display = 'block';
                    nbFlightcases.disabled = false;
                    nbDallesParFlightcase.disabled = false;
                    generateFlightCaseControls();
                    updateTotalModules();
                }
            });
            
            // Fonction pour calculer et mettre à jour le total pour le mode tailles multiples
            function updateMultipleSizesTotal() {
                // Calcul du total pour la configuration 1 (500x500)
                const config1Flightcases = parseInt(config1NbFlightcases.value) || 0;
                const config1Dalles = parseInt(config1NbDalles.value) || 0;
                let config1ModulesPerDalle = 4; // Par défaut pour 2x2
                
                if (config1ModulesConfig.value === '1x1') config1ModulesPerDalle = 1;
                else if (config1ModulesConfig.value === '2x2') config1ModulesPerDalle = 4;
                else if (config1ModulesConfig.value === '3x3') config1ModulesPerDalle = 9;
                else if (config1ModulesConfig.value === '4x4') config1ModulesPerDalle = 16;
                
                const config1TotalModules = config1Flightcases * config1Dalles * config1ModulesPerDalle;
                config1Total.textContent = `${config1Flightcases} flight cases × ${config1Dalles} dalles × ${config1ModulesPerDalle} modules = ${config1TotalModules} modules`;
                
                // Calcul du total pour la configuration 2 (500x1000)
                const config2Flightcases = parseInt(config2NbFlightcases.value) || 0;
                const config2Dalles = parseInt(config2NbDalles.value) || 0;
                let config2ModulesPerDalle = 8; // Par défaut pour 2x4
                
                if (config2ModulesConfig.value === '1x2') config2ModulesPerDalle = 2;
                else if (config2ModulesConfig.value === '2x2') config2ModulesPerDalle = 4;
                else if (config2ModulesConfig.value === '2x4') config2ModulesPerDalle = 8;
                
                const config2TotalModules = config2Flightcases * config2Dalles * config2ModulesPerDalle;
                config2Total.textContent = `${config2Flightcases} flight cases × ${config2Dalles} dalles × ${config2ModulesPerDalle} modules = ${config2TotalModules} modules`;
                
                // Afficher le total global
                const grandTotal = config1TotalModules + config2TotalModules;
                totalModulesFc.textContent = grandTotal;
                totalModulesMessage.textContent = grandTotal + ' modules seront créés au total';
            }
            
            // Événements pour la mise à jour des totaux du mode tailles multiples
            config1NbFlightcases.addEventListener('input', updateMultipleSizesTotal);
            config1NbDalles.addEventListener('input', updateMultipleSizesTotal);
            config1ModulesConfig.addEventListener('change', updateMultipleSizesTotal);
            config2NbFlightcases.addEventListener('input', updateMultipleSizesTotal);
            config2NbDalles.addEventListener('input', updateMultipleSizesTotal);
            config2ModulesConfig.addEventListener('change', updateMultipleSizesTotal);
            
            // Fonction pour générer les contrôles de chaque FlightCase
            function generateFlightCaseControls() {
                const nbFlightcasesValue = parseInt(nbFlightcases.value) || 1;
                const nbDallesParFlightcaseValue = parseInt(nbDallesParFlightcase.value) || 8;
                const modulesParDalleValue = calculatedModulesPerDalle;
                
                // Vider le conteneur
                flightcasesDetailContainer.innerHTML = '';
                
                // Générer les contrôles pour chaque FlightCase
                for (let f = 1; f <= nbFlightcasesValue; f++) {
                    const fcContainer = document.createElement('div');
                    fcContainer.className = 'grid grid-cols-8 gap-4 p-3 bg-gray-800/30 rounded-lg border border-gray-700';
                    
                    // Informations du FlightCase
                    const fcInfo = document.createElement('div');
                    fcInfo.className = 'col-span-3 flex items-center';
                    fcInfo.innerHTML = `
                        <div class="bg-blue-500/20 p-2 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-white">FlightCase ${f}</p>
                            <p class="text-sm text-gray-400">Standard: ${nbDallesParFlightcaseValue} dalles</p>
                        </div>
                    `;
                    
                    // Case à cocher pour FlightCase partiel
                    const fcPartialCheck = document.createElement('div');
                    fcPartialCheck.className = 'col-span-2 flex items-center';
                    fcPartialCheck.innerHTML = `
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="fc_partiel_${f}" name="fc_partiel[${f}]" value="1" 
                                class="fc-partiel-checkbox rounded bg-gray-700 border-gray-600 text-accent-blue focus:ring-accent-blue focus:ring-opacity-50">
                            <span class="ml-2 text-amber-300">FlightCase partiel</span>
                        </label>
                    `;
                    
                    // Contrôle du nombre de dalles et types de dalles
                    const fcDallesControl = document.createElement('div');
                    fcDallesControl.className = 'col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4';
                    fcDallesControl.id = `fc_dalles_control_${f}`;
                    
                    // HTML pour le nombre de dalles
                    const nbDallesHTML = `
                        <div class="w-full">
                            <label class="block text-sm font-medium text-blue-300 mb-1">Nombre de dalles présentes</label>
                            <div class="flex items-center">
                                <input type="number" id="fc_nb_dalles_${f}" name="fc_nb_dalles[${f}]" value="${nbDallesParFlightcaseValue}"
                                    min="1" max="${nbDallesParFlightcaseValue}" 
                                    class="fc-nb-dalles w-24 rounded-lg bg-gray-700 border-gray-600 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50">
                                <span class="ml-2 text-gray-400">sur ${nbDallesParFlightcaseValue}</span>
                            </div>
                        </div>
                    `;
                    
                    // HTML pour le sélecteur de type de dalle
                    let typeDalleOptions = '';
                    <?php if(isset($dalleTypes)): ?>
                        <?php $__currentLoopData = $dalleTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            typeDalleOptions += `<option value="<?php echo e($type['id']); ?>"><?php echo e($type['nom']); ?> (<?php echo e($type['largeur']); ?>x<?php echo e($type['hauteur']); ?>mm)</option>`;
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    
                    const typeDalleHTML = `
                        <div class="w-full">
                            <label class="block text-sm font-medium text-blue-300 mb-1">Type de dalle</label>
                            <select id="fc_dalle_type_${f}" name="flights[${f}][dalle_type_id]" 
                                class="fc-dalle-type w-full rounded-lg bg-gray-700 border-gray-600 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50" onchange="checkDalleType(${f})">
                                ${typeDalleOptions}
                            </select>
                        </div>
                    `;
                    
                    // HTML pour le sélecteur de modules par dalle (pour les dalles 500x1000)
                    const modulesConfigHTML = `
                        <div id="modules_config_container_${f}" class="w-full mt-2" style="display: none;">
                            <label class="block text-sm font-medium text-amber-300 mb-1">Configuration des modules</label>
                            <select id="fc_modules_config_${f}" name="flights[${f}][modules_config]" 
                                class="fc-modules-config w-full rounded-lg bg-gray-700 border-gray-600 text-white focus:border-accent-blue focus:ring focus:ring-accent-blue focus:ring-opacity-50" onchange="updateModulesPerDalle(${f})">
                                <option value="2x4">2x4 (8 modules 250x250)</option>
                                <option value="1x2">1x2 (2 modules 500x500)</option>
                                <option value="2x2">2x2 (4 modules 250x500)</option>
                            </select>
                        </div>
                    `;
                    
                    // Assembler le contenu complet
                    fcDallesControl.innerHTML = nbDallesHTML + typeDalleHTML + modulesConfigHTML;
                    
                    // Ajouter les éléments au conteneur du FlightCase
                    fcContainer.appendChild(fcInfo);
                    fcContainer.appendChild(fcPartialCheck);
                    fcContainer.appendChild(fcDallesControl);
                    
                    // Ajouter le conteneur du FlightCase au conteneur principal
                    flightcasesDetailContainer.appendChild(fcContainer);
                    
                    // Ajouter les événements pour la case à cocher
                    const checkbox = document.getElementById(`fc_partiel_${f}`);
                    const dallesControl = document.getElementById(`fc_dalles_control_${f}`);
                    const nbDallesInput = document.getElementById(`fc_nb_dalles_${f}`);
                    
                    checkbox.addEventListener('change', function() {
                        dallesControl.style.display = this.checked ? 'grid' : 'none';
                        updateTotalModules();
                    });
                    
                    if (nbDallesInput) {
                        nbDallesInput.addEventListener('input', updateTotalModules);
                    }
                }
            }
            
            // Fonction pour calculer et mettre à jour le total des modules (mode standard)
            function updateTotalModules() {
                let totalModules = 0;
                
                if (modeFlightcaseRadio.checked) {
                    // Si mode tailles multiples activé, utiliser ce calcul
                    if (multipleSizesCheckbox.checked) {
                        updateMultipleSizesTotal();
                        return;
                    }
                    
                    // Sinon mode flightcase standard
                    const nbFlightcasesValue = parseInt(nbFlightcases.value) || 1;
                    const nbDallesParFlightcaseValue = parseInt(nbDallesParFlightcase.value) || 8;
                    
                    // Parcourir chaque FlightCase et compter ses dalles
                    for (let f = 1; f <= nbFlightcasesValue; f++) {
                        const fcPartielCheckbox = document.getElementById(`fc_partiel_${f}`);
                        
                        // Déterminer le nombre de modules par dalle pour ce flightcase
                        let modulesPerDalle = calculatedModulesPerDalle;
                        const dalleTypeSelect = document.getElementById(`fc_dalle_type_${f}`);
                        const modulesConfigSelect = document.getElementById(`fc_modules_config_${f}`);
                        
                        // Si c'est un type de dalle 500x1000 et qu'il a une config modules visible
                        if (dalleTypeSelect && dalleTypeSelect.value == "1" && 
                            document.getElementById(`modules_config_container_${f}`) && 
                            document.getElementById(`modules_config_container_${f}`).style.display !== 'none') {
                            
                            const configValue = modulesConfigSelect.value;
                            if (configValue === '2x4') modulesPerDalle = 8;
                            else if (configValue === '1x2') modulesPerDalle = 2;
                            else if (configValue === '2x2') modulesPerDalle = 4;
                        }
                        
                        if (fcPartielCheckbox && fcPartielCheckbox.checked) {
                            // FlightCase partiel: utiliser le nombre de dalles spécifié
                            const nbDallesInput = document.getElementById(`fc_nb_dalles_${f}`);
                            const nbDalles = parseInt(nbDallesInput.value) || 0;
                            totalModules += nbDalles * modulesPerDalle;
                        } else {
                            // FlightCase complet: utiliser le nombre standard de dalles
                            totalModules += nbDallesParFlightcaseValue * modulesPerDalle;
                        }
                    }
                    
                    totalModulesFc.textContent = totalModules;
                } else {
                    totalModules = parseInt(nbModulesTotal.value) || 0;
                }
                
                totalModulesMessage.textContent = totalModules + ' modules seront créés au total';
            }
            
            // Gestionnaires d'événements pour le changement de mode
            modeFlightcaseRadio.addEventListener('change', function() {
                if (this.checked) {
                    flightcaseConfig.style.display = 'block';
                    individuelConfig.style.display = 'none';
                    flightcaseContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
                    individuelContainer.classList.remove('border-accent-blue', 'bg-gray-800/50');
                    updateTotalModules();
                }
            });
            
            modeIndividuelRadio.addEventListener('change', function() {
                if (this.checked) {
                    flightcaseConfig.style.display = 'none';
                    individuelConfig.style.display = 'block';
                    flightcaseContainer.classList.remove('border-accent-blue', 'bg-gray-800/50');
                    individuelContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
                    updateTotalModules();
                }
            });
            
            // Gestionnaires d'événements pour les champs de saisie
            nbFlightcases.addEventListener('input', function() {
                generateFlightCaseControls();
                updateTotalModules();
            });
            nbDallesParFlightcase.addEventListener('input', function() {
                generateFlightCaseControls();
                updateTotalModules();
            });
            nbModulesTotal.addEventListener('input', updateTotalModules);
            
            // Initialisation
            if (modeFlightcaseRadio.checked) {
                flightcaseContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
                generateFlightCaseControls();
            } else {
                individuelContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
            }
            
            updateTotalModules();
            
            // Fonctions pour gérer les configurations spécifiques des modules
            window.checkDalleType = function(flightcaseIndex) {
                const dalleTypeSelect = document.getElementById(`fc_dalle_type_${flightcaseIndex}`);
                const modulesConfigContainer = document.getElementById(`modules_config_container_${flightcaseIndex}`);
                
                if (dalleTypeSelect && modulesConfigContainer) {
                    // Si c'est une dalle 500x1000 (type 1), afficher les options de configuration de modules
                    if (dalleTypeSelect.value == "1") {
                        modulesConfigContainer.style.display = "block";
                    } else {
                        modulesConfigContainer.style.display = "none";
                    }
                    
                    // Mettre à jour le total après changement
                    updateTotalModules();
                }
            };
            
            window.updateModulesPerDalle = function(flightcaseIndex) {
                // Cette fonction est appelée quand l'utilisateur change la configuration des modules
                // pour mettre à jour le calcul du nombre total
                updateTotalModules();
            };
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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/chantiers/create_step3.blade.php ENDPATH**/ ?>