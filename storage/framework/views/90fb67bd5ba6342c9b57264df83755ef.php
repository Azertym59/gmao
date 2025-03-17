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
            <?php echo e(__('Détails de l\'intervention')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-lg font-semibold text-white">Intervention #<?php echo e($intervention->id); ?></h3>
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('interventions.edit', $intervention)); ?>" class="btn-action btn-primary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <?php echo e(__('Modifier')); ?>

                            </a>
                            <a href="<?php echo e(route('modules.show', $intervention->module)); ?>" class="btn-action btn-secondary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                <?php echo e(__('Retour au module')); ?>

                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Informations générales</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Date:</span> <span class="text-gray-400"><?php echo e($intervention->date_debut->format('d/m/Y H:i')); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Technicien:</span> <span class="text-gray-400"><?php echo e($intervention->technicien ? $intervention->technicien->name : "Non assigné"); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Durée:</span>
                                    <span class="text-gray-400">
                                    <?php
                                        $heures = floor($intervention->temps_total / 3600);
                                        $minutes = floor(($intervention->temps_total % 3600) / 60);
                                        $secondes = $intervention->temps_total % 60;
                                        echo sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
                                    ?>
                                    </span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-300">État:</span> 
                                    <?php if($intervention->is_completed): ?>
                                        <span class="badge badge-success">Terminée</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">En cours</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Module</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Module:</span>
                                    <a href="<?php echo e(route('modules.show', $intervention->module)); ?>" class="text-indigo-400 hover:text-indigo-300 hover:underline">
                                        Module #<?php echo e($intervention->module->id); ?>

                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Produit:</span>
                                    <span class="text-gray-400"><?php echo e($intervention->module->dalle->produit->marque); ?> <?php echo e($intervention->module->dalle->produit->modele); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Chantier:</span>
                                    <span class="text-gray-400"><?php echo e($intervention->module->dalle->produit->chantier->nom); ?></span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-300">Client:</span>
                                    <span class="text-gray-400"><?php echo e($intervention->module->dalle->produit->chantier->client->nom_complet); ?></span>
                                    <?php if($intervention->module->dalle->produit->chantier->client->societe): ?>
                                        <span class="text-gray-500">(<?php echo e($intervention->module->dalle->produit->chantier->client->societe); ?>)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if($intervention->diagnostic): ?>
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Diagnostic</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">LEDs HS:</span> <span class="text-gray-400"><?php echo e($intervention->diagnostic->nb_leds_hs); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">ICs HS:</span> <span class="text-gray-400"><?php echo e($intervention->diagnostic->nb_ic_hs); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Masques HS:</span> <span class="text-gray-400"><?php echo e($intervention->diagnostic->nb_masques_hs); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Fake PCB nécessaire:</span> 
                                    <span class="text-gray-400"><?php echo e($intervention->diagnostic->pose_fake_pcb ? 'Oui' : 'Non'); ?></span>
                                </div>
                                <?php if($intervention->diagnostic->remarques): ?>
                                <div>
                                    <span class="font-semibold text-gray-300">Remarques:</span><br>
                                    <span class="text-gray-400"><?php echo e($intervention->diagnostic->remarques); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($intervention->reparation): ?>
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Réparation</h4>
                            <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">LEDs remplacées:</span> <span class="text-gray-400"><?php echo e($intervention->reparation->nb_leds_remplacees); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">ICs remplacés:</span> <span class="text-gray-400"><?php echo e($intervention->reparation->nb_ic_remplaces); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Masques remplacés:</span> <span class="text-gray-400"><?php echo e($intervention->reparation->nb_masques_remplaces); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-gray-300">Fake PCB posé:</span> 
                                    <span class="text-gray-400"><?php echo e($intervention->reparation->fake_pcb_pose ? 'Oui' : 'Non'); ?></span>
                                </div>
                                <?php if($intervention->reparation->remarques): ?>
                                <div>
                                    <span class="font-semibold text-gray-300">Remarques:</span><br>
                                    <span class="text-gray-400"><?php echo e($intervention->reparation->remarques); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/interventions/show.blade.php ENDPATH**/ ?>