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
        <h1 class="font-semibold text-xl text-white leading-tight">
            <?php echo e(__('Détails du module')); ?>

        </h1>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl border border-gray-700">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-white">Module #<?php echo e($module->id); ?></h3>
                            <p class="text-sm text-gray-400">Référence: <?php echo e($module->reference); ?></p>
                        </div>
                        <div class="flex space-x-2">
                            <?php if (isset($component)) { $__componentOriginal8417baeedcb6c131165d53e37e61cc07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8417baeedcb6c131165d53e37e61cc07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.edit-button','data' => ['route' => route('modules.edit', $module)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('edit-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('modules.edit', $module))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back-button','data' => ['route' => route('dalles.show', $module->dalle)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('back-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dalles.show', $module->dalle))]); ?>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-medium text-accent-green mb-2">Informations module</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-green">
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Dimensions:</span> 
                                    <span class="text-gray-300"><?php echo e($module->largeur); ?> × <?php echo e($module->hauteur); ?> mm</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Résolution:</span> 
                                    <span class="text-gray-300"><?php echo e($module->nb_pixels_largeur ?? '-'); ?> × <?php echo e($module->nb_pixels_hauteur ?? '-'); ?> pixels</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Référence:</span> 
                                    <span class="text-gray-300"><?php echo e($module->reference ?? 'Non renseignée'); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Numéro du module:</span> 
                                    <span class="text-gray-300"><?php echo e($module->reference_module ?? 'Non renseigné'); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Numéro de série / ID Usine:</span> 
                                    <span class="text-gray-300"><?php echo e($module->numero_serie ?? 'Non renseigné'); ?></span>
                                </div>
                                <div>
                                    <span class="font-semibold text-white">État:</span>
                                    <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $module->etat]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($module->etat)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $attributes = $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $component = $__componentOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-accent-blue mb-2">Composants électroniques</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-blue">
                                <?php if($module->carte_reception): ?>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Carte de réception:</span> 
                                    <span class="text-gray-300"><?php echo e($module->carte_reception); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($module->hub): ?>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Hub:</span> 
                                    <span class="text-gray-300"><?php echo e($module->hub); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($module->driver): ?>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Driver:</span> 
                                    <span class="text-gray-300"><?php echo e($module->driver); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($module->shift_register): ?>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Shift Register:</span> 
                                    <span class="text-gray-300"><?php echo e($module->shift_register); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($module->buffer): ?>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Buffer:</span> 
                                    <span class="text-gray-300"><?php echo e($module->buffer); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if(!$module->carte_reception && !$module->hub && !$module->driver && !$module->shift_register && !$module->buffer): ?>
                                <div class="text-gray-400 italic">Aucun composant électronique spécifié</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-accent-yellow mb-2">Dalle</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-yellow">
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Dalle:</span>
                                    <a href="<?php echo e(route('dalles.show', $module->dalle)); ?>" class="text-accent-blue hover:underline">
                                        Dalle #<?php echo e($module->dalle->id); ?>

                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Dimensions dalle:</span> 
                                    <span class="text-gray-300"><?php echo e($module->dalle->largeur); ?> × <?php echo e($module->dalle->hauteur); ?> mm</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-white">Alimentation:</span> 
                                    <span class="text-gray-300"><?php echo e($module->dalle->alimentation ?? 'Non spécifiée'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-accent-purple mb-2">Produit & Chantier</h4>
                            <div class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-purple">
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Produit:</span>
                                    <a href="<?php echo e(route('produits.show', $module->dalle->produit)); ?>" class="text-accent-blue hover:underline">
                                        <?php echo e($module->dalle->produit->marque); ?> <?php echo e($module->dalle->produit->modele); ?>

                                    </a>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold text-white">Chantier:</span>
                                    <a href="<?php echo e(route('chantiers.show', $module->dalle->produit->chantier)); ?>" class="text-accent-blue hover:underline">
                                        <?php echo e($module->dalle->produit->chantier->nom); ?>

                                    </a>
                                </div>
                                <div>
                                    <span class="font-semibold text-white">Client:</span>
                                    <span class="text-gray-300"><?php echo e($module->dalle->produit->chantier->client->nom_complet); ?></span>
                                    <?php if($module->dalle->produit->chantier->client->societe): ?>
                                        <span class="text-gray-500">(<?php echo e($module->dalle->produit->chantier->client->societe); ?>)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Interventions sur ce module -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-medium text-white">Historique des interventions</h4>
                            <?php if (isset($component)) { $__componentOriginalf060402531584bf7744ab80e6dcae228 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf060402531584bf7744ab80e6dcae228 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.add-button','data' => ['route' => route('interventions.create', ['module_id' => $module->id])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('add-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('interventions.create', ['module_id' => $module->id]))]); ?>
                                <?php echo e(__('Nouvelle intervention')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf060402531584bf7744ab80e6dcae228)): ?>
<?php $attributes = $__attributesOriginalf060402531584bf7744ab80e6dcae228; ?>
<?php unset($__attributesOriginalf060402531584bf7744ab80e6dcae228); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf060402531584bf7744ab80e6dcae228)): ?>
<?php $component = $__componentOriginalf060402531584bf7744ab80e6dcae228; ?>
<?php unset($__componentOriginalf060402531584bf7744ab80e6dcae228); ?>
<?php endif; ?>
                        </div>
                        
                        <?php if($module->interventions->count() > 0): ?>
                            <div class="overflow-x-auto rounded-lg shadow-lg">
                                <table class="min-w-full table-styled">
                                    <thead>
                                        <tr>
                                            <th class="py-3 px-4 text-left">Date</th>
                                            <th class="py-3 px-4 text-left">Technicien</th>
                                            <th class="py-3 px-4 text-left">Durée</th>
                                            <th class="py-3 px-4 text-left">Diagnostic</th>
                                            <th class="py-3 px-4 text-left">Réparation</th>
                                            <th class="py-3 px-4 text-left">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $module->interventions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $intervention): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="py-3 px-4">
                                                    <?php echo e($intervention->date_debut->format('d/m/Y H:i')); ?>

                                                </td>
                                                <td class="py-3 px-4">
                                                    <?php echo e($intervention->technicien ? $intervention->technicien->name : "Non assigné"); ?>

                                                </td>
                                                <td class="py-3 px-4">
                                                    <?php
                                                        $heures = floor($intervention->temps_total / 3600);
                                                        $minutes = floor(($intervention->temps_total % 3600) / 60);
                                                        $secondes = $intervention->temps_total % 60;
                                                        echo sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
                                                    ?>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <?php if($intervention->diagnostic): ?>
                                                        <?php echo e((int)($intervention->diagnostic->nb_leds_hs)); ?> LEDs HS,
                                                        <?php echo e((int)($intervention->diagnostic->nb_ic_hs)); ?> ICs HS,
                                                        <?php echo e((int)($intervention->diagnostic->nb_masques_hs)); ?> masques HS
                                                        <?php if($intervention->diagnostic->pose_fake_pcb): ?>
                                                            <br><span class="text-green-500">Fake PCB nécessaire</span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <?php if($intervention->reparation): ?>
                                                        <?php echo e((int)($intervention->reparation->nb_leds_remplacees)); ?> LEDs,
                                                        <?php echo e((int)($intervention->reparation->nb_ic_remplaces)); ?> ICs,
                                                        <?php echo e((int)($intervention->reparation->nb_masques_remplaces)); ?> masques
                                                        <?php if($intervention->reparation->fake_pcb_pose): ?>
                                                            <br><span class="text-green-500">Fake PCB posé</span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <a href="<?php echo e(route('interventions.show', $intervention)); ?>" 
                                                      class="text-accent-blue hover:underline">
                                                        Détails
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="glassmorphism p-8 rounded-lg text-center text-gray-400 border border-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p>Aucune intervention n'a encore été effectuée sur ce module.</p>
                                <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['tag' => 'a','href' => ''.e(route('interventions.create', ['module_id' => $module->id])).'','class' => 'mt-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'a','href' => ''.e(route('interventions.create', ['module_id' => $module->id])).'','class' => 'mt-3']); ?>
                                    <?php echo e(__('Commencer une intervention')); ?>

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
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex justify-center mt-8">
                        <?php if (isset($component)) { $__componentOriginal653ad55244738a059739a51a5163a501 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal653ad55244738a059739a51a5163a501 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.print-button','data' => ['tag' => 'a','route' => route('qrcode.module.print', $module->id),'type' => 'qrcode','buttonStyle' => 'font-semibold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('print-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'a','route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('qrcode.module.print', $module->id)),'type' => 'qrcode','buttonStyle' => 'font-semibold']); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4M4 8h16V5a1 1 0 00-1-1H5a1 1 0 00-1 1v3zm16 4v7a1 1 0 01-1 1H5a1 1 0 01-1-1v-7" />
                            </svg>
                            Générer QR Code
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal653ad55244738a059739a51a5163a501)): ?>
<?php $attributes = $__attributesOriginal653ad55244738a059739a51a5163a501; ?>
<?php unset($__attributesOriginal653ad55244738a059739a51a5163a501); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal653ad55244738a059739a51a5163a501)): ?>
<?php $component = $__componentOriginal653ad55244738a059739a51a5163a501; ?>
<?php unset($__componentOriginal653ad55244738a059739a51a5163a501); ?>
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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/modules/show.blade.php ENDPATH**/ ?>