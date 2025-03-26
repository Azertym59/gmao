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
            <?php echo e(__('Création de chantier - Étape 4/4 : Récapitulatif et rapports')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Indicateur de progression -->
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
                        <div class="relative flex flex-col items-center text-green-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-green-500 border-green-500 text-white font-bold flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-green-500">
                                <span class="font-bold">Produit</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-500"></div>
                        <div class="relative flex flex-col items-center text-accent-blue">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-accent-blue border-accent-blue text-white font-bold flex items-center justify-center">
                                4
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-accent-blue">
                                <span class="font-bold">Rapports</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Récapitulatif du chantier</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Informations client -->
                        <div class="bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                            <h4 class="font-medium text-blue-300 mb-2">Client</h4>
                            <p class="text-gray-300"><span class="font-semibold">Nom:</span> <?php echo e($client->nom_complet); ?></p>
                            <?php if($client->societe): ?>
                                <p class="text-gray-300"><span class="font-semibold">Société:</span> <?php echo e($client->societe); ?></p>
                            <?php endif; ?>
                            <?php if($client->email): ?>
                                <p class="text-gray-300"><span class="font-semibold">Email:</span> <?php echo e($client->email); ?></p>
                            <?php endif; ?>
                            <?php if($client->telephone): ?>
                                <p class="text-gray-300"><span class="font-semibold">Téléphone:</span> <?php echo e($client->telephone); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Informations chantier -->
                        <div class="bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                            <h4 class="font-medium text-blue-300 mb-2">Chantier</h4>
                            <p class="text-gray-300"><span class="font-semibold">Nom:</span> Réparation écran - <?php echo e($client->societe ?: $client->nom_complet); ?> - <?php echo e(date('d/m/Y')); ?></p>
                            <p class="text-gray-300"><span class="font-semibold">Date de réception:</span> <?php echo e(date('d/m/Y', strtotime($step1Data['date_reception']))); ?></p>
                            <p class="text-gray-300"><span class="font-semibold">Date butoir:</span> <?php echo e(date('d/m/Y', strtotime($step1Data['date_butoir']))); ?></p>
                            <p class="text-gray-300"><span class="font-semibold">État:</span> 
                                <?php if($step1Data['etat'] == 'non_commence'): ?>
                                    Non commencé
                                <?php elseif($step1Data['etat'] == 'en_cours'): ?>
                                    En cours
                                <?php else: ?>
                                    Terminé
                                <?php endif; ?>
                            </p>
                        </div>
                        
                        <!-- Informations produit -->
                        <div class="bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                            <h4 class="font-medium text-blue-300 mb-2">Produit</h4>
                            <?php if($step2Data['selection_type'] == 'existant' && $produitRef): ?>
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
                                <p class="text-gray-300"><span class="font-semibold">Produit:</span> <?php echo e($step2Data['marque']); ?> <?php echo e($step2Data['modele']); ?> (Nouveau)</p>
                                <p class="text-gray-300"><span class="font-semibold">Pitch:</span> <?php echo e($step2Data['pitch']); ?> mm</p>
                                <p class="text-gray-300"><span class="font-semibold">Utilisation:</span> <?php echo e($step2Data['utilisation'] == 'indoor' ? 'Indoor' : 'Outdoor'); ?></p>
                                <p class="text-gray-300"><span class="font-semibold">Électronique:</span> 
                                    <?php if($step2Data['electronique'] == 'autre'): ?>
                                        <?php echo e($step2Data['electronique_detail']); ?>

                                    <?php else: ?>
                                        <?php echo e(ucfirst($step2Data['electronique'])); ?>

                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Informations structure -->
                        <div class="bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                            <h4 class="font-medium text-blue-300 mb-2">Structure</h4>
                            <?php if($step3Data['mode'] == 'flightcase'): ?>
                                <p class="text-gray-300"><span class="font-semibold">Mode:</span> Flight Case</p>
                                <p class="text-gray-300"><span class="font-semibold">Nombre de flight cases:</span> <?php echo e($step3Data['nb_flightcases']); ?></p>
                                
                                <!-- Détails de chaque flightcase -->
                                <?php if(!empty($step3Data['flightcase_details'])): ?>
                                    <div class="ml-4 mt-1 mb-2">
                                        <?php $__currentLoopData = $step3Data['flightcase_details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <p class="text-gray-300 text-sm"><?php echo e($detail); ?></p>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(isset($step3Data['disposition_modules']) && !empty($step3Data['disposition_modules'])): ?>
                                    <p class="text-gray-300">
                                        <span class="font-semibold">Modules par dalle:</span> 
                                        <?php
                                            // Calculer le nombre de modules à partir de la disposition
                                            $dispositionText = $step3Data['disposition_modules'];
                                            $moduleCount = 4; // Valeur par défaut
                                            
                                            if (preg_match('/^(\d+)x(\d+)$/', $dispositionText, $matches)) {
                                                $moduleCount = (int)$matches[1] * (int)$matches[2];
                                            }
                                        ?>
                                        <?php echo e($moduleCount); ?>

                                        <span class="text-gray-400">(Disposition <?php echo e($step3Data['disposition_modules']); ?>)</span>
                                    </p>
                                <?php else: ?>
                                    <p class="text-gray-300"><span class="font-semibold">Modules par dalle:</span> <?php echo e($step3Data['nb_modules_par_dalle']); ?></p>
                                <?php endif; ?>
                                <p class="text-gray-300"><span class="font-semibold">Total modules:</span> 
                                    <?php echo e($step3Data['theorique'] ?? $step3Data['nb_modules_total']); ?>

                                </p>
                            <?php else: ?>
                                <p class="text-gray-300"><span class="font-semibold">Mode:</span> Modules individuels</p>
                                <p class="text-gray-300"><span class="font-semibold">Total modules:</span> <?php echo e($step3Data['theorique'] ?? $step3Data['nb_modules_total']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Note sur les interventions -->
                    <div class="mb-6 p-4 bg-gray-900/30 border border-gray-500/30 rounded-xl">
                        <h4 class="font-medium text-gray-300 mb-2">Interventions</h4>
                        <p class="text-gray-300">
                            Après la création du chantier, vous pourrez créer et gérer les interventions manuellement depuis la page de détail du chantier.
                        </p>
                        <p class="text-gray-300 mt-2">
                            <span class="font-semibold">Total modules à traiter:</span> <?php echo e($step3Data['nb_modules_total'] ?? 0); ?>

                        </p>
                    </div>
                    
                    <form method="POST" action="<?php echo e(route('chantiers.store.step4', $chantier)); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-6">
                            <label for="generate_report" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50">
                                <input type="checkbox" name="generate_report" id="generate_report" value="1" class="mr-2 accent-accent-blue">
                                <div>
                                    <span class="font-medium text-white">Générer un rapport</span>
                                    <p class="text-sm text-gray-400">Générer automatiquement un rapport PDF après la création du chantier</p>
                                </div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between mt-8">
                            <a href="<?php echo e(route('chantiers.create.step3', $chantier)); ?>" class="btn-action btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
                                <?php echo e(__('Retour')); ?>

                            </a>
                            <button type="submit" class="btn-action btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <?php echo e(__('Finaliser le chantier')); ?>

                            </button>
                        </div>
                    </form>
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
<?php /**PATH /var/www/gmao/resources/views/chantiers/create_step4.blade.php ENDPATH**/ ?>