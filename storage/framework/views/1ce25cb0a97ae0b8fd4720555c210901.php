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
            <h1 class="text-2xl font-semibold text-white">Gestion des imprimantes</h1>
            <a href="<?php echo e(route('printers.create')); ?>" class="bg-accent-blue hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Ajouter une imprimante
            </a>
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
                                <a href="<?php echo e(route('printers.edit', $printer->id)); ?>" class="p-2 bg-blue-500/20 text-blue-300 rounded-lg hover:bg-blue-500/40 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="<?php echo e(route('printers.destroy', $printer->id)); ?>" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette imprimante?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-2 bg-red-500/20 text-red-300 rounded-lg hover:bg-red-500/40 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                <a href="<?php echo e(route('printers.test')); ?>?printer_id=<?php echo e($printer->id); ?>" class="p-2 bg-green-500/20 text-green-300 rounded-lg hover:bg-green-500/40 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </a>
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