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
            <?php echo e(__('Produits')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-lg font-semibold text-white">Liste des produits</h3>
                            <p class="text-text-secondary mt-1">Catalogue des écrans LED en maintenance</p>
                        </div>
                        <a href="<?php echo e(route('produits.create')); ?>" class="btn bg-white text-gray-900 hover:bg-gray-100 hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter un produit
                        </a>
                    </div>

                    <!-- Filtres -->
                    <div class="mb-6 p-4 rounded-lg bg-gray-800/50 border border-gray-700">
                        <div class="mb-2 flex justify-between items-center">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                <h4 class="text-gray-300 font-medium">Filtrer les produits</h4>
                            </div>
                            <div class="text-xs text-gray-400">
                                <span id="filtre-count"><?php echo e($produits->count()); ?></span> produits trouvés
                            </div>
                        </div>
                        <form id="filter-form" action="<?php echo e(route('produits.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="marque" class="block text-xs text-gray-400 mb-1">Marque</label>
                                <select name="marque" id="marque" class="w-full bg-gray-900/50 border border-gray-700 rounded-md text-sm text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Toutes les marques</option>
                                    <?php $__currentLoopData = $produits->pluck('marque')->unique()->sort(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $marque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($marque); ?>" <?php echo e(request('marque') == $marque ? 'selected' : ''); ?>><?php echo e($marque); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label for="bain_couleur" class="block text-xs text-gray-400 mb-1">Bain de couleur</label>
                                <select name="bain_couleur" id="bain_couleur" class="w-full bg-gray-900/50 border border-gray-700 rounded-md text-sm text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Tous les bains</option>
                                    <?php $__currentLoopData = $produits->pluck('bain_couleur')->filter()->unique()->sort(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($bain); ?>" <?php echo e(request('bain_couleur') == $bain ? 'selected' : ''); ?>><?php echo e($bain); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label for="reference" class="block text-xs text-gray-400 mb-1">Référence SAV</label>
                                <input type="text" name="reference" id="reference" value="<?php echo e(request('reference')); ?>" class="w-full bg-gray-900/50 border border-gray-700 rounded-md text-sm text-white focus:ring-blue-500 focus:border-blue-500" placeholder="GMAO-20250315...">
                            </div>
                            <div>
                                <label for="warranty" class="block text-xs text-gray-400 mb-1">Garantie</label>
                                <select name="warranty" id="warranty" class="w-full bg-gray-900/50 border border-gray-700 rounded-md text-sm text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Tous</option>
                                    <option value="1" <?php echo e(request('warranty') == '1' ? 'selected' : ''); ?>>Sous garantie</option>
                                    <option value="0" <?php echo e(request('warranty') == '0' ? 'selected' : ''); ?>>Hors garantie</option>
                                    <option value="client_achat_1" <?php echo e(request('warranty') == 'client_achat_1' ? 'selected' : ''); ?>>Achat chez nous</option>
                                </select>
                            </div>
                            <div class="md:col-span-2 lg:col-span-4 flex justify-end gap-2">
                                <button type="button" id="reset-filters" class="px-4 py-2 bg-transparent border border-gray-600 hover:bg-gray-800 text-gray-300 rounded-md flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Réinitialiser
                                </button>
                                <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Filtrer
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Script pour filtrage en temps réel -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Sélecteurs pour les filtres
                            const marqueInput = document.getElementById('marque');
                            const electroniqueSelect = document.getElementById('electronique');
                            const clientAchatSelect = document.getElementById('client_achat');
                            const warrantySelect = document.getElementById('warranty');
                            const resetButton = document.getElementById('reset-filters');
                            const countElement = document.getElementById('filtre-count');
                            
                            // Fonction pour mettre à jour le filtrage via API
                            const updateFilters = debounce(function() {
                                const params = new URLSearchParams();
                                
                                if (marqueInput.value) params.append('marque', marqueInput.value);
                                if (document.getElementById('bain_couleur').value) params.append('bain_couleur', document.getElementById('bain_couleur').value);
                                if (document.getElementById('reference').value) params.append('reference', document.getElementById('reference').value);
                                if (warrantySelect.value) params.append('warranty', warrantySelect.value);
                                
                                fetch(`/api/produits?${params.toString()}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Mettre à jour le compteur
                                            countElement.textContent = data.count;
                                            
                                            // On pourrait aussi mettre à jour le tableau en temps réel
                                            // mais cela nécessiterait une refonte plus poussée de l'interface
                                        }
                                    })
                                    .catch(error => console.error('Erreur lors du filtrage:', error));
                            }, 500);
                            
                            // Attacher les événements
                            marqueInput.addEventListener('change', updateFilters);
                            document.getElementById('bain_couleur').addEventListener('change', updateFilters);
                            document.getElementById('reference').addEventListener('input', updateFilters);
                            warrantySelect.addEventListener('change', updateFilters);
                            
                            // Bouton reset
                            resetButton.addEventListener('click', function() {
                                marqueInput.value = '';
                                document.getElementById('bain_couleur').value = '';
                                document.getElementById('reference').value = '';
                                warrantySelect.value = '';
                                updateFilters();
                            });
                            
                            // Fonction debounce pour limiter les appels API
                            function debounce(func, wait) {
                                let timeout;
                                return function executedFunction(...args) {
                                    const later = () => {
                                        clearTimeout(timeout);
                                        func(...args);
                                    };
                                    clearTimeout(timeout);
                                    timeout = setTimeout(later, wait);
                                };
                            }
                        });
                    </script>
                    
                    <?php if(session('success')): ?>
                        <div class="mb-4 p-4 bg-green-500/30 border border-green-500/50 text-green-400 rounded-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($produits->count() > 0): ?>
                        <div class="overflow-x-auto rounded-xl shadow-xl">
                            <table class="min-w-full table-auto border-collapse divide-y divide-gray-700">
                                <thead class="bg-gray-800">
                                    <tr class="divide-x divide-gray-700">
                                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Marque</th>
                                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Modèle</th>
                                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Pitch</th>
                                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Taille Dalle</th>
                                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Électronique</th>
                                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Carte Réception</th>
                                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Bain Couleur</th>
                                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Garantie</th>
                                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Référence SAV</th>
                                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Société</th>
                                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-200 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition duration-200 divide-x divide-gray-700">
                                            <td class="py-3 px-4 text-center font-medium"><?php echo e($produit->marque); ?></td>
                                            <td class="py-3 px-4 text-center"><?php echo e($produit->modele); ?></td>
                                            <td class="py-3 px-4 text-center">
                                                <span class="inline-block px-2 py-1 text-xs rounded-full bg-gray-700 whitespace-nowrap"><?php echo e($produit->pitch); ?> mm</span>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <?php if($produit->dalles->count() > 0): ?>
                                                    <span class="text-white"><?php echo e($produit->dalles->first()->largeur); ?>x<?php echo e($produit->dalles->first()->hauteur); ?> cm</span>
                                                <?php else: ?>
                                                    <span class="text-gray-500">Non définie</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <span class="inline-block px-2 py-1 text-xs rounded-md bg-indigo-900/30 border border-indigo-800/50 text-indigo-300 whitespace-nowrap">
                                                    <?php if($produit->electronique == 'autre'): ?>
                                                        <?php echo e($produit->electronique_detail); ?>

                                                    <?php else: ?>
                                                        <?php echo e(ucfirst($produit->electronique)); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <?php if($produit->carte_reception): ?>
                                                    <span class="inline-block px-2 py-1 text-xs rounded-md bg-blue-900/30 border border-blue-800/50 text-blue-300 whitespace-nowrap">
                                                        <?php echo e($produit->carte_reception); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-500">Non définie</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <?php if($produit->bain_couleur): ?>
                                                    <span class="inline-block px-2 py-1 text-xs rounded-md bg-green-900/30 border border-green-800/50 text-green-300 whitespace-nowrap">
                                                        <?php echo e($produit->bain_couleur); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-500">Non défini</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <?php if($produit->chantier->is_under_warranty): ?>
                                                    <div class="flex flex-col items-center">
                                                        <span class="inline-block px-2 py-1 text-xs rounded-full bg-green-900/50 border border-green-800/70 text-green-300 font-semibold whitespace-nowrap">
                                                            Sous garantie
                                                        </span>
                                                        <?php if($produit->chantier->warranty_end_date): ?>
                                                            <span class="text-xs text-gray-400 mt-0.5">
                                                                Jusqu'au <?php echo e(\Carbon\Carbon::parse($produit->chantier->warranty_end_date)->format('d/m/Y')); ?>

                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if($produit->chantier->warranty_type): ?>
                                                            <span class="inline-block px-1.5 py-0.5 text-xs rounded-full bg-gray-800 text-gray-300 mt-1">
                                                                <?php echo e(ucfirst($produit->chantier->warranty_type)); ?>

                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php elseif($produit->chantier->is_client_achat): ?>
                                                    <div class="flex flex-col items-center">
                                                        <span class="inline-block px-2 py-1 text-xs rounded-full bg-blue-900/30 border border-blue-800/50 text-blue-300 whitespace-nowrap">
                                                            Achat chez nous
                                                        </span>
                                                        <span class="text-xs text-red-400 mt-0.5">
                                                            Hors garantie
                                                        </span>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-gray-500">Non applicable</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <a href="<?php echo e(route('chantiers.show', $produit->chantier)); ?>" class="text-accent-blue hover:underline">
                                                    <?php echo e($produit->chantier->reference); ?>

                                                </a>
                                            </td>
                                            <td class="py-3 px-4 text-center font-medium">
                                                <?php echo e($produit->chantier->client->societe ?: $produit->chantier->client->nom_complet); ?>

                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <a href="<?php echo e(route('produits.show', $produit)); ?>" 
                                                   class="inline-flex items-center justify-center px-3 py-1.5 rounded-md bg-gray-800 text-white hover:bg-gray-700 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Voir
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-10 rounded-xl text-center text-gray-400 bg-gray-800/30 flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            <p class="mb-4">Aucun produit trouvé. Commencez par en créer un !</p>
                            <a href="<?php echo e(route('produits.create')); ?>" class="btn-action btn-primary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Créer mon premier produit
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($produits) && method_exists($produits, 'links')): ?>
                        <div class="mt-6">
                            <?php echo e($produits->links()); ?>

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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/produits/index.blade.php ENDPATH**/ ?>