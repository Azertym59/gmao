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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Test d\'impression')); ?>

            </h2>
            <div class="flex space-x-2">
                <a href="<?php echo e(url('/etiquettes/test')); ?>" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    Tester étiquettes
                </a>
                <a href="<?php echo e(route('printers.index')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour aux imprimantes
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-gray-800/70 to-gray-900/70 overflow-hidden shadow-xl sm:rounded-xl border border-blue-600/20">
                <div class="p-6 text-gray-200">

                    <!-- Alert qui indique que nous utilisons PrintNode -->
                    <div class="mb-6 p-4 bg-blue-600/20 rounded-xl border border-blue-500/30 shadow-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-blue-300">Test d'impression PrintNode</h3>
                                <p class="mt-1 text-sm text-blue-200/80">Utilisez le formulaire ci-dessous pour tester l'impression d'un QR code avec l'imprimante sélectionnée.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <!-- Left column: Current printer & form -->
                        <div>
                            <!-- Current Printer Card -->
                            <?php if(isset($selectedPrinter) && $selectedPrinter): ?>
                            <div class="mb-6 p-5 bg-gradient-to-r from-blue-700/30 to-indigo-700/30 rounded-xl border border-blue-500/30 shadow-lg">
                                <div class="flex items-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    <h3 class="text-xl font-semibold text-white">Imprimante sélectionnée</h3>
                                </div>
                                
                                <div class="ml-8 bg-gray-800/50 rounded-lg p-4 border border-gray-700/50">
                                    <div class="flex items-center mb-2">
                                        <span class="text-gray-400 w-24">Nom:</span>
                                        <span class="text-white font-semibold"><?php echo e($selectedPrinter->name); ?></span>
                                        <?php if($selectedPrinter->is_default): ?> 
                                            <span class="ml-3 px-2 py-1 bg-yellow-500/50 text-yellow-100 rounded-full text-xs font-medium">
                                                Par défaut
                                            </span> 
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex items-center mb-2">
                                        <span class="text-gray-400 w-24">Type:</span>
                                        <span class="text-white"><?php echo e($selectedPrinter->type); ?></span>
                                    </div>
                                    <?php if($selectedPrinter->hasPrintNode()): ?>
                                    <div class="flex items-center">
                                        <span class="text-gray-400 w-24">PrintNode:</span>
                                        <span class="px-2 py-1 bg-green-600/40 text-green-200 text-xs rounded-full">
                                            Activé (ID: <?php echo e($selectedPrinter->printnode_id); ?>)
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Print Test Form -->
                            <form method="POST" action="<?php echo e(route('printers.print-test')); ?>" class="mb-6 p-5 bg-gradient-to-r from-purple-700/20 to-purple-900/20 rounded-xl border border-purple-500/30 shadow-lg">
                                <?php echo csrf_field(); ?>
                                <div class="flex items-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="text-xl font-semibold text-white">Tester l'impression</h3>
                                </div>
                                
                                <div class="ml-8 space-y-4">
                                    <div>
                                        <label for="printer_id" class="block text-sm font-medium text-purple-300 mb-2">Sélectionner une imprimante</label>
                                        <select class="form-select w-full rounded-lg border-purple-500/40 bg-gray-800 text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500/30" id="printer_id" name="printer_id" required>
                                            <option value="">Sélectionner...</option>
                                            <?php $__currentLoopData = $printers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $printer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($printer->id); ?>" <?php echo e(isset($selectedPrinter) && $selectedPrinter && $selectedPrinter->id == $printer->id ? 'selected' : ''); ?>>
                                                    <?php echo e($printer->name); ?> (<?php echo e($printer->type); ?>)
                                                    <?php if($printer->is_default): ?> [Par défaut] <?php endif; ?>
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 text-white rounded-lg shadow-lg transition transform hover:scale-105 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            Imprimer QR Code de test
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Brother Specific Options -->
                            <?php if(isset($selectedPrinter) && $selectedPrinter->isBrotherLabel()): ?>
                            <div class="mb-6 p-5 bg-gradient-to-r from-green-700/20 to-green-900/20 rounded-xl border border-green-500/30 shadow-lg">
                                <div class="flex items-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                    </svg>
                                    <h3 class="text-xl font-semibold text-white">Options Brother</h3>
                                </div>
                                
                                <div class="ml-8 space-y-4">
                                    <a href="<?php echo e(route('printers.test-brother', $selectedPrinter->id)); ?>" 
                                       class="block w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-green-600 to-green-800 hover:from-green-700 hover:to-green-900 text-white rounded-lg shadow-lg transition transform hover:scale-105 text-center sm:inline-flex sm:items-center sm:justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Test d'impression Brother
                                    </a>
                                    
                                    <?php if($selectedPrinter->shouldUseBpac()): ?>
                                    <div class="p-4 bg-gradient-to-r from-green-800/50 to-green-900/50 rounded-lg border border-green-800/40">
                                        <div class="flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            <h4 class="font-medium text-green-300">Imprimante avec b-PAC SDK</h4>
                                        </div>
                                        <p class="text-sm text-gray-300 ml-6">Cette imprimante est configurée pour utiliser le SDK Brother b-PAC pour une meilleure compatibilité.</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right column: Printers Grid -->
                        <div>
                            <div class="p-5 bg-gradient-to-r from-gray-700/50 to-gray-800/50 rounded-xl border border-gray-600/30 shadow-lg h-full">
                                <div class="flex items-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="text-xl font-semibold text-white">Imprimantes disponibles</h3>
                                </div>
                                
                                <div class="overflow-hidden rounded-lg border border-gray-700/70">
                                    <table class="min-w-full divide-y divide-gray-700/70">
                                        <thead class="bg-gray-800/70">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nom</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-gray-800/40 divide-y divide-gray-700/50">
                                            <?php $__currentLoopData = $printers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $printer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="hover:bg-gray-700/30 transition-colors">
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-white"><?php echo e($printer->name); ?></div>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        <div class="text-sm text-gray-300"><?php echo e(ucfirst($printer->type)); ?></div>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                                        <div class="flex justify-center gap-1">
                                                            <?php if($printer->is_default): ?> 
                                                                <span class="px-2 py-1 inline-flex text-xs leading-none rounded-full bg-yellow-500/50 text-yellow-100">Par défaut</span> 
                                                            <?php endif; ?>
                                                            <?php if($printer->hasPrintNode()): ?> 
                                                                <span class="px-2 py-1 inline-flex text-xs leading-none rounded-full bg-green-600/40 text-green-200">PrintNode</span> 
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                                        <a href="<?php echo e(route('printers.test', ['id' => $printer->id])); ?>" 
                                                           class="inline-flex items-center justify-center px-3 py-1 border border-blue-500 text-blue-300 rounded-lg hover:bg-blue-700/40 hover:text-blue-100 transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Tester
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Success Message -->
                    <div id="print-success" class="mt-6 p-4 rounded-xl bg-gradient-to-r from-green-700/30 to-green-900/30 border border-green-500/30 shadow-lg hidden">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-green-300 font-medium">Impression envoyée avec succès!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll to selected printer if available
            const selectedPrinterOption = document.querySelector('#printer_id option[selected]');
            if (selectedPrinterOption) {
                selectedPrinterOption.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/printers/test.blade.php ENDPATH**/ ?>