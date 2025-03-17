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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Modules')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-semibold">Liste des modules</h3>
                        <a href="<?php echo e(route('modules.create')); ?>" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Ajouter un module
                        </a>
                    </div>

                    <?php if(session('success')): ?>
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <!-- Filtres -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-700 mb-2">Filtres</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <select id="filter-etat" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tous les états</option>
                                    <option value="non_commence">Non commencé</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="defaillant">Défaillant</option>
                                    <option value="termine">Terminé</option>
                                </select>
                            </div>
                            <div>
                                <input type="text" id="filter-search" placeholder="Rechercher..." class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>

                    <?php if($modules->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border rounded-lg p-4 hover:bg-gray-50 <?php echo e($module->etat == 'defaillant' ? 'border-red-300 bg-red-50' : ''); ?> <?php echo e($module->etat == 'termine' ? 'border-green-300 bg-green-50' : ''); ?>">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-medium">Module #<?php echo e($module->id); ?></h5>
                                        <?php if($module->etat == 'non_commence'): ?>
                                            <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-xs">Non commencé</span>
                                        <?php elseif($module->etat == 'en_cours'): ?>
                                            <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs">En cours</span>
                                        <?php elseif($module->etat == 'defaillant'): ?>
                                            <span class="px-2 py-1 bg-red-200 text-red-800 rounded-full text-xs">Défaillant</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-xs">Terminé</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="text-sm mb-3">
                                        <p><span class="font-medium">Dimensions:</span> <?php echo e($module->largeur); ?>×<?php echo e($module->hauteur); ?> mm</p>
                                        <p><span class="font-medium">Résolution:</span> <?php echo e($module->nb_pixels_largeur); ?>×<?php echo e($module->nb_pixels_hauteur); ?> px</p>
                                        <p><span class="font-medium">Produit:</span> <?php echo e($module->dalle->produit->marque); ?> <?php echo e($module->dalle->produit->modele); ?></p>
                                        <p><span class="font-medium">Chantier:</span> <?php echo e($module->dalle->produit->chantier->nom); ?></p>
                                    </div>
                                    
                                    <div class="mt-4 flex justify-end">
                                        <a href="<?php echo e(route('modules.show', $module)); ?>" 
                                           class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                                            Détails
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-gray-50 p-4 rounded-md text-center text-gray-500">
                            Aucun module trouvé. Commencez par en créer un!
                        </div>
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
<?php endif; ?>
<?php /**PATH /var/www/gmao/resources/views/modules/index.blade.php ENDPATH**/ ?>