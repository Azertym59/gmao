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
        Gestion des imprimantes
     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        <!-- En-tête avec titre et bouton d'ajout -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-white">Gestion des imprimantes</h1>
                <?php
                $defaultPrinter = \App\Models\Printer::getDefault();
                ?>
                <?php if($defaultPrinter): ?>
                    <p class="text-sm text-gray-300 mt-1">Imprimante par défaut : <span class="font-medium text-green-300"><?php echo e($defaultPrinter->name); ?></span></p>
                <?php else: ?>
                    <p class="text-sm text-yellow-300 mt-1">Aucune imprimante définie par défaut</p>
                <?php endif; ?>
            </div>
            <div class="flex space-x-2">
                <a href="<?php echo e(url('/etiquettes/test')); ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Tester étiquettes
                </a>
                <a href="<?php echo e(route('printers.create')); ?>" class="bg-accent-blue hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Ajouter une imprimante
                </a>
            </div>
        </div>

        <!-- Tableau principal -->
        <div class="glassmorphism rounded-lg overflow-hidden shadow-lg">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="px-6 py-4 text-text-secondary">NOM</th>
                        <th class="px-6 py-4 text-text-secondary">MODÈLE</th>
                        <th class="px-6 py-4 text-text-secondary">CONNEXION</th>
                        <th class="px-6 py-4 text-text-secondary">STATUT</th>
                        <th class="px-6 py-4 text-text-secondary">FORMAT D'ÉTIQUETTE</th>
                        <th class="px-6 py-4 text-text-secondary">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($printers) > 0): ?>
                        <?php $__currentLoopData = $printers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $printer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-800/40 transition-colors">
                            <td class="px-6 py-4"><?php echo e($printer->name); ?></td>
                            <td class="px-6 py-4"><?php echo e($printer->model); ?></td>
                            <td class="px-6 py-4"><?php echo e($printer->connection_type); ?></td>
                            <td class="px-6 py-4">
                                <?php if($printer->status === 'online'): ?>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-300 rounded-full text-xs">En ligne</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-red-500/20 text-red-300 rounded-full text-xs">Hors ligne</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4"><?php echo e($printer->label_format); ?></td>
                            <td class="px-6 py-4 flex space-x-2">
                                <a href="<?php echo e(route('printers.edit', $printer->id)); ?>" class="p-2 bg-blue-500/20 text-blue-300 rounded-lg hover:bg-blue-500/40 transition-colors" title="Modifier l'imprimante">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="<?php echo e(route('printers.destroy', $printer->id)); ?>" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette imprimante?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-2 bg-red-500/20 text-red-300 rounded-lg hover:bg-red-500/40 transition-colors" title="Supprimer l'imprimante">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                <a href="<?php echo e(route('printers.test', ['id' => $printer->id])); ?>" class="p-2 bg-green-500/20 text-green-300 rounded-lg hover:bg-green-500/40 transition-colors" title="Tester l'imprimante">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </a>
                                <a href="<?php echo e(url('/etiquettes/test?printer_id=' . $printer->id)); ?>" class="p-2 bg-purple-500/20 text-purple-300 rounded-lg hover:bg-purple-500/40 transition-colors" title="Tester les étiquettes">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                </a>
                                <?php if(!$printer->is_default): ?>
                                <form method="POST" action="<?php echo e(route('printers.set-default', $printer->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="p-2 bg-yellow-500/20 text-yellow-300 rounded-lg hover:bg-yellow-500/40 transition-colors" title="Définir comme imprimante par défaut">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                    </button>
                                </form>
                                <?php else: ?>
                                <span class="p-2 bg-yellow-500/50 text-yellow-200 rounded-lg" title="Imprimante par défaut">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <p class="text-lg mb-4">Aucune imprimante configurée</p>
                                <a href="<?php echo e(route('printers.create')); ?>" class="bg-accent-blue hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-300 inline-flex items-center">
                                    Configurer une imprimante
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Guide d'aide en respectant le thème -->
        <div class="glassmorphism border border-blue-500/20 rounded-lg overflow-hidden">
            <div class="bg-blue-500/10 px-6 py-4 border-b border-blue-500/20">
                <h2 class="text-lg font-semibold text-blue-300">Comment configurer votre imprimante Brother QL-820NWBc?</h2>
            </div>
            <div class="p-6 text-text-secondary">
                <p class="mb-4">La configuration de votre imprimante Brother requiert quelques étapes simples :</p>
                <ol class="list-decimal pl-6 space-y-2">
                    <li>Connectez l'imprimante à votre réseau Wi-Fi local</li>
                    <li>Notez l'adresse IP attribuée à l'imprimante</li>
                    <li>Utilisez le bouton "Ajouter une imprimante" ci-dessus</li>
                    <li>Renseignez l'adresse IP et les autres détails demandés</li>
                    <li>Sélectionnez le format d'étiquette par défaut</li>
                </ol>
                <p class="mt-4">
                    Pour plus d'informations, consultez le 
                    <a href="#" class="text-accent-blue hover:text-blue-400 transition-colors">
                        manuel d'utilisation Brother
                    </a>
                </p>
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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/printers/index.blade.php ENDPATH**/ ?>