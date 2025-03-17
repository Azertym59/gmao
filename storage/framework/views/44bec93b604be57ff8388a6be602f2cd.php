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
            <?php echo e(__('Création de projet - Étape 2/5 : Produit')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Indicateur de progression -->
            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="ml-2 font-medium text-green-500">Client</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-green-500 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-accent-blue text-white font-bold">2</div>
                        <span class="ml-2 font-medium text-white">Produit</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-600 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-gray-400 font-bold">3</div>
                        <span class="ml-2 font-medium text-gray-400">Configuration</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-600 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-gray-400 font-bold">4</div>
                        <span class="ml-2 font-medium text-gray-400">Interventions</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-600 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-gray-400 font-bold">5</div>
                        <span class="ml-2 font-medium text-gray-400">Rapports</span>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    
                    <!-- En-tête avec informations client -->
                    <div class="mb-6 bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                        <h3 class="font-medium text-blue-300 mb-2">Informations du client</h3>
                        <p class="text-gray-300"><span class="font-medium">Nom:</span> <?php echo e($client->nom_complet); ?></p>
                        <p class="text-gray-300"><span class="font-medium">Société:</span> <?php echo e($client->societe ?: 'Non spécifiée'); ?></p>
                        <p class="text-gray-300"><span class="font-medium">Adresse:</span> <?php echo e($client->adresse); ?>, <?php echo e($client->code_postal); ?> <?php echo e($client->ville); ?></p>
                    </div>

                    <form id="productForm" method="POST" action="<?php echo e(route('chantiers.store.step2')); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Choix entre produit existant et nouveau produit -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-white mb-4">Sélection du produit</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="from_catalogue_1" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50 <?php $__errorArgs = ['from_catalogue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="catalogue_container">
                                        <input type="radio" name="from_catalogue" id="from_catalogue_1" value="1" class="mr-2 accent-accent-blue" <?php echo e(old('from_catalogue', 1) == '1' ? 'checked' : ''); ?>>
                                        <div>
                                            <span class="font-medium text-white">Utiliser un produit existant</span>
                                            <p class="text-sm text-gray-400">Sélectionnez un produit déjà défini dans le catalogue</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <div>
                                    <label for="from_catalogue_0" class="flex items-center p-4 border border-gray-700 rounded-xl cursor-pointer transition-all hover:border-accent-blue hover:bg-gray-800/50 <?php $__errorArgs = ['from_catalogue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nouveau_container">
                                        <input type="radio" name="from_catalogue" id="from_catalogue_0" value="0" class="mr-2 accent-accent-blue" <?php echo e(old('from_catalogue') == '0' ? 'checked' : ''); ?>>
                                        <div>
                                            <span class="font-medium text-white">Créer un nouveau produit</span>
                                            <p class="text-sm text-gray-400">Définir un nouveau produit qui n'existe pas encore dans le catalogue</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <?php $__errorArgs = ['from_catalogue'];
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
                        
                        <!-- Sélection du produit du catalogue avec moteur de recherche avancé -->
                        <div id="catalogue_form" class="mb-6 p-4 border border-gray-700 rounded-xl bg-gray-800/30 <?php echo e(old('from_catalogue', 1) == '1' ? '' : 'hidden'); ?>">
                            <h3 class="text-lg section-title mb-4">Recherche avancée de produit</h3>
                            
                            <div x-data="advancedProductSearch()" class="mb-4">
                                <!-- Filtres de recherche -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'search_marque','value' => __('Marque'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'search_marque','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Marque')),'class' => 'text-gray-300']); ?>
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
                                        <input type="text" id="search_marque" x-model="filters.marque" placeholder="Filtrer par marque..." 
                                            class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    </div>
                                    
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'search_modele','value' => __('Modèle/Référence'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'search_modele','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Modèle/Référence')),'class' => 'text-gray-300']); ?>
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
                                        <input type="text" id="search_modele" x-model="filters.modele" placeholder="Filtrer par modèle..." 
                                            class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    </div>
                                    
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'search_pitch','value' => __('Pitch (mm)'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'search_pitch','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Pitch (mm)')),'class' => 'text-gray-300']); ?>
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
                                        <input type="number" id="search_pitch" x-model="filters.pitch" placeholder="Filtrer par pitch..." step="0.1" min="0.1" max="100"
                                            class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    </div>
                                    
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'search_bain','value' => __('Bain de couleur'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'search_bain','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Bain de couleur')),'class' => 'text-gray-300']); ?>
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
                                        <input type="text" id="search_bain" x-model="filters.bain" placeholder="Filtrer par type de bain..." 
                                            class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    </div>
                                    
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'search_utilisation','value' => __('Utilisation'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'search_utilisation','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Utilisation')),'class' => 'text-gray-300']); ?>
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
                                        <select id="search_utilisation" x-model="filters.utilisation"
                                            class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="">Tous</option>
                                            <option value="indoor">Indoor</option>
                                            <option value="outdoor">Outdoor</option>
                                        </select>
                                    </div>
                                    
                                    <div class="flex items-end">
                                        <button type="button" @click="resetFilters()" class="btn-action btn-secondary mr-2 h-10">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Réinitialiser
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Résultats de recherche -->
                                <div class="bg-gray-900/30 border border-gray-700 rounded-lg p-4">
                                    <!-- Statut de la recherche -->
                                    <div class="flex justify-between mb-3">
                                        <p class="text-sm text-gray-300">
                                            <span x-text="filteredProducts.length"></span> produits trouvés
                                        </p>
                                        <div x-show="isSearching" class="flex items-center">
                                            <svg class="animate-spin h-4 w-4 text-accent-blue mr-2" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-300">Recherche en cours...</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Table des résultats -->
                                    <div class="max-h-72 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
                                        <table class="min-w-full divide-y divide-gray-700">
                                            <thead class="bg-gray-800 sticky top-0">
                                                <tr>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Marque</th>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Modèle</th>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Pitch</th>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Util.</th>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Bain</th>
                                                    <th scope="col" class="px-3 py-2 text-xs font-medium text-gray-400 uppercase tracking-wider">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-gray-800/50 divide-y divide-gray-700">
                                                <template x-if="filteredProducts.length === 0">
                                                    <tr>
                                                        <td colspan="6" class="px-3 py-4">
                                                            <div class="text-center py-4">
                                                                <p class="text-gray-300 text-sm mb-2">Aucun produit ne correspond à ces critères.</p>
                                                                <button type="button" @click="suggestNewProduct()" class="text-accent-blue hover:text-blue-400 text-sm flex items-center mx-auto">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                                    </svg>
                                                                    Créer un nouveau produit avec ces critères
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <template x-for="product in filteredProducts" :key="product.id">
                                                    <tr class="hover:bg-gray-700">
                                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-300" x-text="product.marque"></td>
                                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-300" x-text="product.modele"></td>
                                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-300" x-text="product.pitch + ' mm'"></td>
                                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-300 capitalize" x-text="product.utilisation"></td>
                                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-300" x-text="product.bain_couleur || '-'"></td>
                                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-right">
                                                            <button type="button" @click="selectProduct(product)" 
                                                                class="text-accent-blue hover:text-blue-400 font-medium">
                                                                Sélectionner
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Produit sélectionné -->
                                <div x-show="selectedProduct" class="mt-4 bg-blue-900/20 p-4 rounded-lg border border-blue-500/30">
                                    <div class="flex justify-between">
                                        <h4 class="text-md font-medium text-blue-300 mb-2">Produit sélectionné</h4>
                                        <button type="button" @click="clearSelection()" class="text-gray-400 hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-gray-300 text-sm mb-1">Marque: <span class="font-medium" x-text="selectedProduct?.marque"></span></p>
                                            <p class="text-gray-300 text-sm mb-1">Modèle: <span class="font-medium" x-text="selectedProduct?.modele"></span></p>
                                            <p class="text-gray-300 text-sm mb-1">Pitch: <span class="font-medium" x-text="selectedProduct?.pitch + ' mm'"></span></p>
                                        </div>
                                        <div>
                                            <p class="text-gray-300 text-sm mb-1">Utilisation: <span class="font-medium capitalize" x-text="selectedProduct?.utilisation"></span></p>
                                            <p class="text-gray-300 text-sm mb-1">Électronique: <span class="font-medium capitalize" x-text="selectedProduct?.electronique"></span></p>
                                            <p class="text-gray-300 text-sm mb-1">Bain de couleur: <span class="font-medium" x-text="selectedProduct?.bain_couleur || 'Non spécifié'"></span></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Champ caché pour stocker l'ID -->
                                <input type="hidden" id="catalogue_id" name="catalogue_id" :value="selectedProduct?.id">
                            </div>
                            <?php $__errorArgs = ['catalogue_id'];
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
                        
                        <!-- Formulaire nouveau produit - visible seulement si from_catalogue=0 -->
                        <div id="nouveau_form" class="mb-6 <?php echo e(old('from_catalogue') == '0' ? '' : 'hidden'); ?>">
                            <!-- Partie 1: Description de la dalle -->
                            <div class="p-4 border border-gray-700 rounded-xl bg-gray-800/30 mb-4">
                                <h3 class="text-lg section-title mb-4">Description de la dalle</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginalba8e118b42ee6b0526ffc1f2f5715610 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalba8e118b42ee6b0526ffc1f2f5715610 = $attributes; } ?>
<?php $component = App\View\Components\MarqueAutocomplete::resolve(['label' => 'Marque','placeholder' => 'Recherchez ou saisissez une marque...','required' => 'false'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('marque-autocomplete'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\MarqueAutocomplete::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalba8e118b42ee6b0526ffc1f2f5715610)): ?>
<?php $attributes = $__attributesOriginalba8e118b42ee6b0526ffc1f2f5715610; ?>
<?php unset($__attributesOriginalba8e118b42ee6b0526ffc1f2f5715610); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalba8e118b42ee6b0526ffc1f2f5715610)): ?>
<?php $component = $__componentOriginalba8e118b42ee6b0526ffc1f2f5715610; ?>
<?php unset($__componentOriginalba8e118b42ee6b0526ffc1f2f5715610); ?>
<?php endif; ?>
                                        <input type="hidden" id="marque" name="marque" value="<?php echo e(old('marque')); ?>">
                                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('marque'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('marque')),'class' => 'mt-2']); ?>
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
                                        <?php if (isset($component)) { $__componentOriginal636c249dc18dbaa5e43aa9de05262879 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal636c249dc18dbaa5e43aa9de05262879 = $attributes; } ?>
<?php $component = App\View\Components\ModeleAutocomplete::resolve(['label' => 'Modèle','placeholder' => 'Recherchez ou saisissez un modèle...','required' => 'false'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modele-autocomplete'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\ModeleAutocomplete::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal636c249dc18dbaa5e43aa9de05262879)): ?>
<?php $attributes = $__attributesOriginal636c249dc18dbaa5e43aa9de05262879; ?>
<?php unset($__attributesOriginal636c249dc18dbaa5e43aa9de05262879); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal636c249dc18dbaa5e43aa9de05262879)): ?>
<?php $component = $__componentOriginal636c249dc18dbaa5e43aa9de05262879; ?>
<?php unset($__componentOriginal636c249dc18dbaa5e43aa9de05262879); ?>
<?php endif; ?>
                                        <input type="hidden" id="modele" name="modele" value="<?php echo e(old('modele')); ?>">
                                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('modele'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('modele')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'largeur_dalle','value' => __('Largeur de dalle (mm)'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'largeur_dalle','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Largeur de dalle (mm)')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'largeur_dalle','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'largeur_dalle','value' => old('largeur_dalle', 500),'min' => '1','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'largeur_dalle','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'largeur_dalle','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('largeur_dalle', 500)),'min' => '1','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('largeur_dalle'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('largeur_dalle')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'hauteur_dalle','value' => __('Hauteur de dalle (mm)'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'hauteur_dalle','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Hauteur de dalle (mm)')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'hauteur_dalle','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'hauteur_dalle','value' => old('hauteur_dalle', 500),'min' => '1','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'hauteur_dalle','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'hauteur_dalle','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('hauteur_dalle', 500)),'min' => '1','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('hauteur_dalle'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('hauteur_dalle')),'class' => 'mt-2']); ?>
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
                            
                            <!-- Partie 2: Configuration du module -->
                            <div class="p-4 border border-gray-700 rounded-xl bg-gray-800/30 mb-4">
                                <h3 class="text-lg section-title mb-4">Configuration du module</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'pitch','value' => __('Pitch (mm)'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'pitch','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Pitch (mm)')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'pitch','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'pitch','value' => old('pitch'),'step' => '0.1','min' => '0.1','max' => '100','placeholder' => 'Ex: 2.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'pitch','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'pitch','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('pitch')),'step' => '0.1','min' => '0.1','max' => '100','placeholder' => 'Ex: 2.5']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('pitch'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('pitch')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'utilisation','value' => __('Utilisation'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'utilisation','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Utilisation')),'class' => 'text-gray-300']); ?>
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
                                        <select id="utilisation" name="utilisation" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="indoor">Indoor</option>
                                            <option value="outdoor">Outdoor</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'bain_couleur','value' => __('Bain de couleur'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'bain_couleur','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Bain de couleur')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'bain_couleur','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'bain_couleur','placeholder' => 'Ex: SMD 3-en-1, COB, etc.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'bain_couleur','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'bain_couleur','placeholder' => 'Ex: SMD 3-en-1, COB, etc.']); ?>
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
                                </div>
                                
                                <!-- Sous-section: Taille du module -->
                                <div class="mt-6 p-3 border border-gray-700 rounded-lg bg-gray-800/50">
                                    <h4 class="text-md subsection-title mb-3">Taille du module</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'largeur_module','value' => __('Largeur du module (mm)'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'largeur_module','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Largeur du module (mm)')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'largeur_module','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'largeur_module','value' => old('largeur_module', 250),'min' => '1','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'largeur_module','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'largeur_module','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('largeur_module', 250)),'min' => '1','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('largeur_module'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('largeur_module')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'hauteur_module','value' => __('Hauteur du module (mm)'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'hauteur_module','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Hauteur du module (mm)')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'hauteur_module','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'hauteur_module','value' => old('hauteur_module', 250),'min' => '1','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'hauteur_module','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'hauteur_module','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('hauteur_module', 250)),'min' => '1','required' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('hauteur_module'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('hauteur_module')),'class' => 'mt-2']); ?>
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
                                
                                <!-- Sous-section: Résolution du module -->
                                <div class="mt-4 p-3 border border-gray-700 rounded-lg bg-gray-800/50">
                                    <h4 class="text-md subsection-title mb-3">
                                        Résolution du module
                                        <span id="resolution_auto_info" class="ml-2 text-accent-blue text-xs italic hidden">
                                            (Calculée automatiquement)
                                        </span>
                                    </h4>
                                    
                                    <div class="mb-3">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="auto_resolution" name="auto_resolution" class="mr-2 rounded bg-gray-700 border-gray-600 text-accent-blue" checked>
                                            <label for="auto_resolution" class="text-gray-300">Calculer automatiquement la résolution</label>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">La résolution sera calculée à partir du pitch et des dimensions du module</p>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'nb_pixels_largeur','value' => __('Pixels en largeur'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'nb_pixels_largeur','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Pixels en largeur')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'nb_pixels_largeur','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_pixels_largeur','value' => old('nb_pixels_largeur', 64),'min' => '1','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'nb_pixels_largeur','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_pixels_largeur','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('nb_pixels_largeur', 64)),'min' => '1','required' => true]); ?>
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
                                                <span id="auto_largeur_icon" class="absolute top-1/2 right-3 transform -translate-y-1/2 text-accent-blue">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('nb_pixels_largeur'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('nb_pixels_largeur')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'nb_pixels_hauteur','value' => __('Pixels en hauteur'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'nb_pixels_hauteur','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Pixels en hauteur')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'nb_pixels_hauteur','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_pixels_hauteur','value' => old('nb_pixels_hauteur', 64),'min' => '1','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'nb_pixels_hauteur','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'nb_pixels_hauteur','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('nb_pixels_hauteur', 64)),'min' => '1','required' => true]); ?>
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
                                                <span id="auto_hauteur_icon" class="absolute top-1/2 right-3 transform -translate-y-1/2 text-accent-blue">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('nb_pixels_hauteur'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('nb_pixels_hauteur')),'class' => 'mt-2']); ?>
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
                                
                                <!-- Autres composants électroniques -->
                                <div class="mt-4 p-3 border border-gray-700 rounded-lg bg-gray-800/50">
                                    <h4 class="text-md subsection-title mb-3">Composants électroniques</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <?php if (isset($component)) { $__componentOriginal1268156e315f5530e556d97bf1215e11 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1268156e315f5530e556d97bf1215e11 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.driver-autocomplete','data' => ['label' => 'Driver (IC de commande)','placeholder' => 'Recherchez ou saisissez un driver...','required' => 'false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('driver-autocomplete'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Driver (IC de commande)','placeholder' => 'Recherchez ou saisissez un driver...','required' => 'false']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1268156e315f5530e556d97bf1215e11)): ?>
<?php $attributes = $__attributesOriginal1268156e315f5530e556d97bf1215e11; ?>
<?php unset($__attributesOriginal1268156e315f5530e556d97bf1215e11); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1268156e315f5530e556d97bf1215e11)): ?>
<?php $component = $__componentOriginal1268156e315f5530e556d97bf1215e11; ?>
<?php unset($__componentOriginal1268156e315f5530e556d97bf1215e11); ?>
<?php endif; ?>
                                            <input type="hidden" id="driver" name="driver" value="<?php echo e(old('driver')); ?>">
                                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('driver'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('driver')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'shift_register','value' => __('Shift Register'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'shift_register','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Shift Register')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'shift_register','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'shift_register','value' => old('shift_register')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'shift_register','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'shift_register','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('shift_register'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('shift_register'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('shift_register')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'buffer','value' => __('Buffer'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'buffer','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Buffer')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'buffer','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'buffer','value' => old('buffer')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'buffer','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'buffer','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('buffer'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('buffer'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('buffer')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'alimentation','value' => __('Alimentation'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'alimentation','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Alimentation')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'alimentation','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'alimentation','placeholder' => 'Ex: 5V, 110-220V, etc.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'alimentation','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'alimentation','placeholder' => 'Ex: 5V, 110-220V, etc.']); ?>
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
                                    </div>
                                </div>
                                
                                <!-- Disposition des modules dans la dalle -->
                                <div class="mt-6">
                                    <h4 class="text-md subsection-title mb-4">Disposition des modules</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div class="md:col-span-2">
                                            <p class="text-gray-300 mb-2">Sélectionnez la disposition des modules dans la dalle</p>
                                            
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-4">
                                                <label for="disposition_2x2" class="disposition-option flex flex-col items-center justify-center p-2 border border-gray-700 rounded-lg cursor-pointer transition-all hover:border-accent-blue">
                                                    <input type="radio" id="disposition_2x2" name="disposition_modules" value="2x2" class="sr-only" <?php echo e(old('disposition_modules', '2x2') == '2x2' ? 'checked' : ''); ?>>
                                                    <div class="grid grid-cols-2 gap-1 w-20 h-20 bg-gray-800 p-2 rounded">
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                    </div>
                                                    <span class="text-xs text-gray-300 mt-1">2×2 (4 modules)</span>
                                                </label>
                                                
                                                <label for="disposition_3x3" class="disposition-option flex flex-col items-center justify-center p-2 border border-gray-700 rounded-lg cursor-pointer transition-all hover:border-accent-blue">
                                                    <input type="radio" id="disposition_3x3" name="disposition_modules" value="3x3" class="sr-only" <?php echo e(old('disposition_modules') == '3x3' ? 'checked' : ''); ?>>
                                                    <div class="grid grid-cols-3 gap-1 w-20 h-20 bg-gray-800 p-2 rounded">
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                    </div>
                                                    <span class="text-xs text-gray-300 mt-1">3×3 (9 modules)</span>
                                                </label>
                                                
                                                <label for="disposition_4x4" class="disposition-option flex flex-col items-center justify-center p-2 border border-gray-700 rounded-lg cursor-pointer transition-all hover:border-accent-blue">
                                                    <input type="radio" id="disposition_4x4" name="disposition_modules" value="4x4" class="sr-only" <?php echo e(old('disposition_modules') == '4x4' ? 'checked' : ''); ?>>
                                                    <div class="grid grid-cols-4 gap-1 w-20 h-20 bg-gray-800 p-1 rounded">
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                        <div class="bg-accent-blue rounded"></div>
                                                    </div>
                                                    <span class="text-xs text-gray-300 mt-1">4×4 (16 modules)</span>
                                                </label>
                                                
                                                <label for="disposition_personnalise" class="disposition-option flex flex-col items-center justify-center p-2 border border-gray-700 rounded-lg cursor-pointer transition-all hover:border-accent-blue">
                                                    <input type="radio" id="disposition_personnalise" name="disposition_modules" value="personnalise" class="sr-only" <?php echo e(old('disposition_modules') == 'personnalise' ? 'checked' : ''); ?>>
                                                    <div class="w-20 h-20 bg-gray-800 p-2 rounded flex flex-col items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-accent-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                        </svg>
                                                        <span class="text-xs text-accent-blue">Personnalisé</span>
                                                    </div>
                                                    <span class="text-xs text-gray-300 mt-1">Grille personnalisée</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div id="disposition_personnalisee" class="md:col-span-2 p-4 border border-gray-700 rounded-lg bg-gray-800/50 <?php echo e(old('disposition_modules') == 'personnalise' ? '' : 'hidden'); ?>">
                                            <h5 class="text-sm font-medium text-white mb-3">Dimensions personnalisées</h5>
                                            
                                            <div class="grid grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modules_largeur','value' => __('Modules en largeur'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modules_largeur','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Modules en largeur')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modules_largeur','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'modules_largeur','value' => old('modules_largeur', 2),'min' => '1','max' => '10']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modules_largeur','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'modules_largeur','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('modules_largeur', 2)),'min' => '1','max' => '10']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'modules_hauteur','value' => __('Modules en hauteur'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'modules_hauteur','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Modules en hauteur')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'modules_hauteur','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'modules_hauteur','value' => old('modules_hauteur', 2),'min' => '1','max' => '10']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modules_hauteur','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'number','name' => 'modules_hauteur','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('modules_hauteur', 2)),'min' => '1','max' => '10']); ?>
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
                                            </div>
                                            
                                            <div>
                                                <h6 class="text-xs font-medium text-blue-300 mb-2">Prévisualisation</h6>
                                                <div id="grid_preview" class="w-full gap-1 p-2 bg-gray-900 rounded"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Partie 3: Cerveau / Electronique -->
                            <div class="p-4 border border-gray-700 rounded-xl bg-gray-800/30 mb-4">
                                <h3 class="text-lg section-title mb-4">Cerveau / Electronique</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'electronique','value' => __('Système électronique'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'electronique','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Système électronique')),'class' => 'text-gray-300']); ?>
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
                                        <select id="electronique" name="electronique" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="nova">Nova</option>
                                            <option value="linsn">Linsn</option>
                                            <option value="dbstar">DBstar</option>
                                            <option value="colorlight">Colorlight</option>
                                            <option value="barco">Barco</option>
                                            <option value="brompton">Brompton</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                    </div>
                                    
                                    <div id="electronique_detail_container" class="hidden">
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'electronique_detail','value' => __('Précisez l\'électronique'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'electronique_detail','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Précisez l\'électronique')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'electronique_detail','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'electronique_detail']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'electronique_detail','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'electronique_detail']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'carte_reception','value' => __('Carte de Réception'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'carte_reception','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Carte de Réception')),'class' => 'text-gray-300']); ?>
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
                                        <select id="carte_reception" name="carte_reception" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                            <option value="">Sélectionnez une carte</option>
                                            <!-- Options dynamiques selon l'électronique -->
                                        </select>
                                        <div class="mt-2 flex items-center">
                                            <input type="checkbox" id="toggle_custom_carte" class="mr-2 rounded bg-gray-700 border-gray-600 text-accent-blue">
                                            <label for="toggle_custom_carte" class="text-sm text-gray-300">Saisir manuellement</label>
                                        </div>
                                        <div id="custom_carte_container" class="mt-2 hidden">
                                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'custom_carte_reception','class' => 'block w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','placeholder' => 'Ex: Novastar Taurus TB6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'custom_carte_reception','class' => 'block w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','placeholder' => 'Ex: Novastar Taurus TB6']); ?>
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
                                    </div>
                                    
                                    <div>
                                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'hub','value' => __('Hub'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'hub','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Hub')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'hub','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'hub','placeholder' => 'Ex: HUB75, HUB75E, etc.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'hub','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'hub','placeholder' => 'Ex: HUB75, HUB75E, etc.']); ?>
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
                                </div>
                            </div>
                            
                            <!-- Partie 4: Configuration de la LED -->
                            <div class="p-4 border border-gray-700 rounded-xl bg-gray-800/30">
                                <h3 class="text-lg section-title mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-accent-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                    Configuration de la LED
                                </h3>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                                    <div>
                                        <div class="mb-3 flex items-center">
                                            <input type="radio" id="led_nouveau" name="led_selection" value="nouveau" class="mr-2 rounded bg-gray-700 border-gray-600 text-accent-blue" checked>
                                            <label for="led_nouveau" class="text-gray-300">Définir une nouvelle LED</label>
                                        </div>
                                        
                                        <div class="mb-3 flex items-center">
                                            <input type="radio" id="led_existant" name="led_selection" value="existant" class="mr-2 rounded bg-gray-700 border-gray-600 text-accent-blue">
                                            <label for="led_existant" class="text-gray-300">Sélectionner une configuration existante</label>
                                        </div>
                                        
                                        <div id="led_existant_container" class="mb-4 hidden">
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'led_existant_select','value' => __('Sélectionnez une LED'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'led_existant_select','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Sélectionnez une LED')),'class' => 'text-gray-300']); ?>
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
                                            <select id="led_existant_select" name="led_existant_select" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="">-- Sélectionnez une LED --</option>
                                                <?php $__currentLoopData = $ledDatasheets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $datasheet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        // Construction d'une description plus claire
                                                        $description = $datasheet->type;
                                                        if (!empty($datasheet->reference)) {
                                                            // Extraire les composants individuels de la référence
                                                            $parts = explode('_', $datasheet->reference);
                                                            if (count($parts) > 1) {
                                                                $size = $parts[1] ?? '';
                                                                $description .= (!empty($size)) ? " {$size}" : "";
                                                            }
                                                        }
                                                        
                                                        $pcbColor = ucfirst($datasheet->color ?? 'black');
                                                        $description .= " {$pcbColor}";
                                                        
                                                        // Format des pads
                                                        $padCount = $datasheet->nb_poles ?? 4;
                                                        $description .= " ({$padCount} pads)";
                                                    ?>
                                                    <option value="<?php echo e($datasheet->id); ?>" 
                                                            data-reference="<?php echo e($datasheet->reference); ?>"
                                                            data-type="<?php echo e($datasheet->type); ?>"
                                                            data-size="<?php echo e(!empty($parts[1]) ? $parts[1] : ''); ?>"
                                                            data-color="<?php echo e($datasheet->color ?? 'black'); ?>"
                                                            data-pads="<?php echo e($datasheet->nb_poles ?? 4); ?>"
                                                            data-config="<?php echo e(json_encode($datasheet->configuration_poles ?? [])); ?>">
                                                        <?php echo e($description); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            
                                            <div class="mt-4 p-3 border border-blue-500/30 rounded-lg bg-blue-900/20">
                                                <h4 class="text-sm font-medium text-blue-300 mb-2">Aperçu de la LED</h4>
                                                <div id="led_existant_preview" class="text-center">
                                                    <img id="led_image" src="" alt="Aperçu LED" class="max-w-full h-auto mx-auto hidden" style="max-height: 200px; background-color: #1f2937; padding: 10px; border-radius: 5px;">
                                                    <p id="no_led_selected" class="text-sm text-gray-300">Veuillez sélectionner une LED pour voir son aperçu.</p>
                                                </div>
                                                <div id="led_details_preview" class="text-sm text-gray-300 mt-3">
                                                </div>
                                            </div>
                                            
                                            <input type="hidden" id="led_datasheet_id" name="led_datasheet_id" value="">
                                        </div>
                                    </div>
                                    
                                    <div id="led_nouveau_container" class="grid grid-cols-1 md:grid-cols-3 gap-4 md:col-span-2">
                                        <div>
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'led_type','value' => __('Type de LED'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'led_type','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Type de LED')),'class' => 'text-gray-300']); ?>
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
                                            <select id="led_type" name="led_type" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="SMD" <?php echo e(old('led_type') == 'SMD' ? 'selected' : ''); ?>>SMD</option>
                                                <option value="DIP" <?php echo e(old('led_type') == 'DIP' ? 'selected' : ''); ?>>DIP</option>
                                                <option value="COB" <?php echo e(old('led_type') == 'COB' ? 'selected' : ''); ?>>COB</option>
                                                <option value="GOB" <?php echo e(old('led_type') == 'GOB' ? 'selected' : ''); ?>>GOB</option>
                                                <option value="HOB" <?php echo e(old('led_type') == 'HOB' ? 'selected' : ''); ?>>HOB</option>
                                                <option value="MiniLED" <?php echo e(old('led_type') == 'MiniLED' ? 'selected' : ''); ?>>MiniLED</option>
                                            </select>
                                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('led_type'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('led_type')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'led_color','value' => __('Couleur du PCB'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'led_color','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Couleur du PCB')),'class' => 'text-gray-300']); ?>
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
                                            <select id="led_color" name="led_color" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="black" <?php echo e(old('led_color') == 'black' ? 'selected' : ''); ?>>Black (Noir)</option>
                                                <option value="white" <?php echo e(old('led_color') == 'white' ? 'selected' : ''); ?>>White (Blanc)</option>
                                            </select>
                                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('led_color'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('led_color')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'led_size','value' => __('Taille de la LED'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'led_size','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Taille de la LED')),'class' => 'text-gray-300']); ?>
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
                                            <div class="flex">
                                                <select id="led_size_preset" class="block mt-1 w-full rounded-l-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                    <option value="">Personnalisé</option>
                                                    <option value="1010">1010</option>
                                                    <option value="1515">1515</option>
                                                    <option value="2020">2020</option>
                                                    <option value="2121">2121</option>
                                                    <option value="2727">2727</option>
                                                    <option value="3030">3030</option>
                                                    <option value="3535">3535</option>
                                                    <option value="5050">5050</option>
                                                </select>
                                                <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'led_size','class' => 'block mt-1 w-full rounded-r-md border-l-0 bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'led_size','value' => old('led_size'),'placeholder' => 'Ex: 1515']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'led_size','class' => 'block mt-1 w-full rounded-r-md border-l-0 bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'led_size','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('led_size')),'placeholder' => 'Ex: 1515']); ?>
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
                                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('led_size'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('led_size')),'class' => 'mt-2']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'led_pads','value' => __('Nombre de pads'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'led_pads','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Nombre de pads')),'class' => 'text-gray-300']); ?>
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
                                            <select id="led_pads" name="led_pads" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                                <option value="2" <?php echo e(old('led_pads') == '2' ? 'selected' : ''); ?>>2</option>
                                                <option value="4" <?php echo e(old('led_pads') == '4' ? 'selected' : ''); ?>>4</option>
                                                <option value="6" <?php echo e(old('led_pads') == '6' ? 'selected' : ''); ?>>6</option>
                                                <option value="8" <?php echo e(old('led_pads') == '8' ? 'selected' : ''); ?>>8</option>
                                            </select>
                                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('led_pads'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('led_pads')),'class' => 'mt-2']); ?>
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
                                
                                <!-- Configuration des pads -->
                                <div id="pads_container" class="my-4">
                                    <!-- Configuration pour 2 pads -->
                                    <div id="pad-config-2" class="pad-configs grid grid-cols-2 md:grid-cols-4 gap-4 my-3" style="display: none;">
                                        <div>
                                            <label for="pad_1" class="block text-sm text-gray-300 mb-1">Pad 1</label>
                                            <select id="pad_1" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_2" class="block text-sm text-gray-300 mb-1">Pad 2</label>
                                            <select id="pad_2" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+" selected>+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Configuration pour 4 pads -->
                                    <div id="pad-config-4" class="pad-configs grid grid-cols-2 md:grid-cols-4 gap-4 my-3" style="display: none;">
                                        <div>
                                            <label for="pad_1_4" class="block text-sm text-gray-300 mb-1">Pad 1</label>
                                            <select id="pad_1_4" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R" selected>R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_2_4" class="block text-sm text-gray-300 mb-1">Pad 2</label>
                                            <select id="pad_2_4" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G" selected>G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_3_4" class="block text-sm text-gray-300 mb-1">Pad 3</label>
                                            <select id="pad_3_4" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B" selected>B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_4_4" class="block text-sm text-gray-300 mb-1">Pad 4</label>
                                            <select id="pad_4_4" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+" selected>+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Configuration pour 6 pads -->
                                    <div id="pad-config-6" class="pad-configs grid grid-cols-2 md:grid-cols-3 gap-4 my-3" style="display: none;">
                                        <!-- Gauche, pads 1-3 -->
                                        <div>
                                            <label for="pad_1_6" class="block text-sm text-gray-300 mb-1">Pad 1</label>
                                            <select id="pad_1_6" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R" selected>R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_2_6" class="block text-sm text-gray-300 mb-1">Pad 2</label>
                                            <select id="pad_2_6" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G" selected>G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_3_6" class="block text-sm text-gray-300 mb-1">Pad 3</label>
                                            <select id="pad_3_6" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B" selected>B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <!-- Droite, pads 4-6 -->
                                        <div>
                                            <label for="pad_4_6" class="block text-sm text-gray-300 mb-1">Pad 4</label>
                                            <select id="pad_4_6" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R" selected>R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_5_6" class="block text-sm text-gray-300 mb-1">Pad 5</label>
                                            <select id="pad_5_6" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G" selected>G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_6_6" class="block text-sm text-gray-300 mb-1">Pad 6</label>
                                            <select id="pad_6_6" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+" selected>+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Configuration pour 8 pads -->
                                    <div id="pad-config-8" class="pad-configs grid grid-cols-2 md:grid-cols-4 gap-4 my-3" style="display: none;">
                                        <!-- Côté gauche, pads 1-4 -->
                                        <div>
                                            <label for="pad_1_8" class="block text-sm text-gray-300 mb-1">Pad 1</label>
                                            <select id="pad_1_8" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R" selected>R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_2_8" class="block text-sm text-gray-300 mb-1">Pad 2</label>
                                            <select id="pad_2_8" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G" selected>G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_3_8" class="block text-sm text-gray-300 mb-1">Pad 3</label>
                                            <select id="pad_3_8" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B" selected>B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_4_8" class="block text-sm text-gray-300 mb-1">Pad 4</label>
                                            <select id="pad_4_8" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+" selected>+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <!-- Côté droit, pads 5-8 -->
                                        <div>
                                            <label for="pad_5_8" class="block text-sm text-gray-300 mb-1">Pad 5</label>
                                            <select id="pad_5_8" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R" selected>R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_6_8" class="block text-sm text-gray-300 mb-1">Pad 6</label>
                                            <select id="pad_6_8" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G" selected>G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_7_8" class="block text-sm text-gray-300 mb-1">Pad 7</label>
                                            <select id="pad_7_8" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B" selected>B (Bleu)</option>
                                                <option value="+">+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="pad_8_8" class="block text-sm text-gray-300 mb-1">Pad 8</label>
                                            <select id="pad_8_8" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                                <option value="R">R (Rouge)</option>
                                                <option value="G">G (Vert)</option>
                                                <option value="B">B (Bleu)</option>
                                                <option value="+" selected>+ (Positif)</option>
                                                <option value="-">- (Négatif)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Rotation de la LED -->
                                <div id="led_rotation_container" class="mb-4">
                                    <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'led_rotation','value' => __('Rotation de la LED'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'led_rotation','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Rotation de la LED')),'class' => 'text-gray-300']); ?>
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
                                    <select id="led_rotation" name="led_rotation" class="block mt-1 w-full md:w-1/3 rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="0" <?php echo e(old('led_rotation') == '0' ? 'selected' : ''); ?>>0° (Normal)</option>
                                        <option value="90" <?php echo e(old('led_rotation') == '90' ? 'selected' : ''); ?>>90°</option>
                                        <option value="180" <?php echo e(old('led_rotation') == '180' ? 'selected' : ''); ?>>180°</option>
                                        <option value="270" <?php echo e(old('led_rotation') == '270' ? 'selected' : ''); ?>>270°</option>
                                    </select>
                                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('led_rotation'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('led_rotation')),'class' => 'mt-2']); ?>
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
                                
                                <!-- Prévisualisation de la LED -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-900/50 p-4 rounded-xl">
                                        <div class="flex justify-between items-center mb-2">
                                            <h5 class="text-white font-medium">Prévisualisation</h5>
                                            <div class="text-gray-300 text-sm" id="led_name_preview">SMD1515RGB+0</div>
                                        </div>
                                        
                                        <div class="flex justify-center">
                                            <div class="relative">
                                                <canvas id="led_preview" width="200" height="200" class="border border-gray-600 rounded bg-gray-800"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <p class="text-gray-300 mb-4">Le datasheet de LED est un document qui décrit la configuration et les caractéristiques électriques de la LED utilisée dans le module.</p>
                                        
                                        <button type="button" id="generate_datasheet" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                            Générer le datasheet
                                        </button>
                                        <input type="hidden" id="led_datasheet_name" name="led_datasheet_name" value="">
                                        <input type="hidden" id="led_datasheet_image" name="led_datasheet_image" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Option d'ajout au catalogue -->
                        <div id="add_to_catalogue_container" class="mb-6 p-4 border border-gray-600 rounded-xl bg-blue-900/20 <?php echo e(old('from_catalogue') == '0' ? '' : 'hidden'); ?>">
                            <div class="flex items-center">
                                <input type="checkbox" id="add_to_catalogue" name="add_to_catalogue" value="1" <?php echo e(old('add_to_catalogue') ? 'checked' : ''); ?> class="mr-2 rounded bg-gray-700 border-gray-600 text-blue-500 focus:ring-blue-500">
                                <label for="add_to_catalogue" class="text-white font-medium">Ajouter ce produit au catalogue</label>
                            </div>
                            <p class="text-sm text-gray-400 mt-1">Le produit sera disponible pour d'autres chantiers</p>
                        </div>
                        
                        <div class="flex items-center justify-between mt-6">
                            <a href="<?php echo e(route('chantiers.create.step1')); ?>" class="btn-action btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
                                <?php echo e(__('Retour')); ?>

                            </a>
                            <button type="submit" class="btn-action btn-primary">
                                <?php echo e(__('Continuer')); ?>

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .glassmorphism {
            background: rgba(23, 28, 46, 0.7);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }
        
        .btn-action {
            @apply flex items-center justify-center px-4 py-2 rounded-lg font-medium transition duration-150 ease-in-out;
        }
        
        .btn-primary {
            @apply bg-accent-blue text-white hover:bg-blue-600;
        }
        
        .btn-secondary {
            @apply bg-gray-700 text-gray-300 hover:bg-gray-600;
        }
        
        .disposition-option.selected {
            border-color: #3B82F6 !important;
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        /* Styles pour la section LED */
        .led-pads-container {
            scrollbar-width: thin;
            scrollbar-color: rgba(75, 85, 99, 0.5) rgba(31, 41, 55, 0.5);
        }
        
        .led-pads-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .led-pads-container::-webkit-scrollbar-track {
            background: rgba(31, 41, 55, 0.5);
            border-radius: 3px;
        }
        
        .led-pads-container::-webkit-scrollbar-thumb {
            background-color: rgba(75, 85, 99, 0.5);
            border-radius: 3px;
        }
        
        .pad-config-item {
            transition: all 0.2s ease;
        }
        
        .pad-config-item:hover {
            transform: translateY(-2px);
        }
        
        /* Titre des sections en vert - Plus haute priorité ! */
        .section-title {
            color: #22C55E !important; /* bright green */
            font-weight: 600 !important;
            text-shadow: 0 0 1px rgba(34, 197, 94, 0.3) !important;
        }
        
        .subsection-title {
            color: #22C55E !important; /* bright green */
            font-weight: 500 !important;
            text-shadow: 0 0 1px rgba(34, 197, 94, 0.3) !important;
        }
        
        /* Style injecté directement pour être sûr */
        h3.section-title, h4.subsection-title {
            color: #22C55E !important;
        }
    </style>
    <script>
        // Script d'initialisation du datasheet LED
        document.addEventListener('DOMContentLoaded', function() {
            // Ce script s'exécute AVANT le script principal pour préparer le terrain
            console.log('Script d\'initialisation du datasheet LED');
            
            // Sélectionner l'option 4 pads par défaut
            setTimeout(function() {
                const ledPadsSelect = document.getElementById('led_pads');
                if (ledPadsSelect) {
                    ledPadsSelect.value = '4';
                    
                    // Afficher la bonne configuration de pads
                    document.querySelectorAll('.pad-configs').forEach(config => {
                        config.style.display = 'none';
                    });
                    const padConfig4 = document.getElementById('pad-config-4');
                    if (padConfig4) {
                        padConfig4.style.display = 'grid';
                    }
                }
                
                // Ajouter un gestionnaire pour le bouton de rotation
                let rotateButton = document.getElementById('rotate_led');
                if (!rotateButton) {
                    // Créer le bouton de rotation s'il n'existe pas
                    const ledRotation = document.getElementById('led_rotation');
                    if (ledRotation && ledRotation.parentNode) {
                        rotateButton = document.createElement('button');
                        rotateButton.id = 'rotate_led';
                        rotateButton.type = 'button';
                        rotateButton.className = 'ml-2 mt-1 px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors';
                        rotateButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>';
                        
                        // Créer un conteneur flex pour le select et le bouton
                        const flexContainer = document.createElement('div');
                        flexContainer.className = 'flex';
                        
                        // Replacer le select par le flex container
                        const parent = ledRotation.parentNode;
                        parent.replaceChild(flexContainer, ledRotation);
                        
                        // Ajouter le select et le bouton au container
                        flexContainer.appendChild(ledRotation);
                        flexContainer.appendChild(rotateButton);
                    }
                }
                
                // Gestionnaire pour le bouton de rotation
                if (rotateButton) {
                    rotateButton.addEventListener('click', function() {
                        const ledRotation = document.getElementById('led_rotation');
                        if (ledRotation) {
                            // Obtenir l'index actuel
                            const currentIndex = ledRotation.selectedIndex;
                            // Passer à la rotation suivante (avec bouclage)
                            const nextIndex = (currentIndex + 1) % ledRotation.options.length;
                            ledRotation.selectedIndex = nextIndex;
                            
                            // Déclencher l'événement change pour mettre à jour la prévisualisation
                            const event = new Event('change');
                            ledRotation.dispatchEvent(event);
                        }
                    });
                }

                // Assurer que l'événement change du select de rotation est traité correctement
                const ledRotation = document.getElementById('led_rotation');
                if (ledRotation) {
                    ledRotation.addEventListener('change', function() {
                        // Obtenir l'élément canvas et son contexte
                        const canvas = document.getElementById('led_preview');
                        if (canvas) {
                            // Forcer une mise à jour de la prévisualisation
                            const previewUpdateEvent = new CustomEvent('update_preview', {
                                detail: { rotation: this.value }
                            });
                            document.dispatchEvent(previewUpdateEvent);
                        }
                    });
                }
            }, 100);
        });
    </script>
    <script src="<?php echo e(asset('js/pages/chantier-create-step2.js')); ?>"></script>
    <script src="<?php echo e(asset('js/components/carte-reception-selector.js')); ?>"></script>
    <script src="<?php echo e(asset('js/components/autocomplete.js')); ?>"></script>
    <script src="<?php echo e(asset('js/components/advanced-product-search.js')); ?>"></script>
    <script src="<?php echo e(asset('js/components/led-datasheet-viewer.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Script inline chargé - Formulaire création chantier étape 2');
            
            // Forcer les couleurs des titres
            document.querySelectorAll('.section-title, .subsection-title').forEach(el => {
                // Forcer la couleur verte sur tous les éléments avec ces classes
                el.style.setProperty('color', '#22C55E', 'important');
                
                // Ajouter un léger text-shadow pour améliorer la visibilité
                el.style.setProperty('text-shadow', '0 0 1px rgba(34, 197, 94, 0.3)', 'important');
            });
            
            // Gestion du basculement entre catalogue et nouveau produit
            const fromCatalogue1Radio = document.getElementById('from_catalogue_1');
            const fromCatalogue0Radio = document.getElementById('from_catalogue_0');
            const catalogueForm = document.getElementById('catalogue_form');
            const nouveauForm = document.getElementById('nouveau_form');
            const catalogueContainer = document.getElementById('catalogue_container');
            const nouveauContainer = document.getElementById('nouveau_container');
            const addToCatalogueContainer = document.getElementById('add_to_catalogue_container');
            
            fromCatalogue1Radio.addEventListener('change', function() {
                if (this.checked) {
                    catalogueForm.classList.remove('hidden');
                    nouveauForm.classList.add('hidden');
                    catalogueContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
                    nouveauContainer.classList.remove('border-accent-blue', 'bg-gray-800/50');
                    if (addToCatalogueContainer) addToCatalogueContainer.classList.add('hidden');
                }
            });
            
            fromCatalogue0Radio.addEventListener('change', function() {
                if (this.checked) {
                    catalogueForm.classList.add('hidden');
                    nouveauForm.classList.remove('hidden');
                    catalogueContainer.classList.remove('border-accent-blue', 'bg-gray-800/50');
                    nouveauContainer.classList.add('border-accent-blue', 'bg-gray-800/50');
                    if (addToCatalogueContainer) addToCatalogueContainer.classList.remove('hidden');
                }
            });
            
            // Gestion de l'affichage du détail électronique
            const electroniqueSelect = document.getElementById('electronique');
            const electroniqueDetailContainer = document.getElementById('electronique_detail_container');
            
            if (electroniqueSelect && electroniqueDetailContainer) {
                electroniqueSelect.addEventListener('change', function() {
                    if (this.value === 'autre') {
                        electroniqueDetailContainer.classList.remove('hidden');
                    } else {
                        electroniqueDetailContainer.classList.add('hidden');
                    }
                });
                
                // Initialisation du champ détail électronique
                if (electroniqueSelect.value === 'autre') {
                    electroniqueDetailContainer.classList.remove('hidden');
                }
            }
            
            // Gestion de la sélection de disposition
            const dispositionRadios = document.querySelectorAll('input[name="disposition_modules"]');
            const dispositionOptions = document.querySelectorAll('.disposition-option');
            const dispositionPersonnalisee = document.getElementById('disposition_personnalisee');
            
            dispositionRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Retirer la classe selected de toutes les options
                    dispositionOptions.forEach(opt => opt.classList.remove('selected'));
                    
                    // Ajouter la classe selected à l'option sélectionnée
                    if (this.checked) {
                        this.closest('.disposition-option').classList.add('selected');
                    }
                    
                    // Afficher/masquer les options de personnalisation
                    if (this.value === 'personnalise' && dispositionPersonnalisee) {
                        dispositionPersonnalisee.classList.remove('hidden');
                    } else if (dispositionPersonnalisee) {
                        dispositionPersonnalisee.classList.add('hidden');
                    }
                });
                
                // Si l'option est sélectionnée, ajouter la classe selected
                if (radio.checked) {
                    radio.closest('.disposition-option').classList.add('selected');
                }
            });
            
            // Gestion de la sélection du type de LED
            const ledNouveau = document.getElementById('led_nouveau');
            const ledExistant = document.getElementById('led_existant');
            const ledNouveauContainer = document.getElementById('led_nouveau_container');
            const ledExistantContainer = document.getElementById('led_existant_container');
            
            if (ledNouveau && ledExistant && ledNouveauContainer && ledExistantContainer) {
                // Fonction pour masquer/afficher tous les éléments liés au datasheet
                function toggleDatasheetContainers(visible) {
                    // Tous les éléments à cacher lors du choix d'une LED existante
                    const containersToHide = [
                        document.getElementById('pads_container'),
                        document.getElementById('led_rotation_container'),
                        document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.gap-4'), // Prévisualisation et bouton générer
                        document.querySelector('.grid.grid-cols-1.md\\:grid-cols-3.gap-4')  // Type, couleur, taille, pads
                    ];
                    
                    containersToHide.forEach(el => {
                        if (el) {
                            if (visible) {
                                el.classList.remove('hidden');
                            } else {
                                el.classList.add('hidden');
                            }
                        }
                    });
                }
                
                ledNouveau.addEventListener('change', function() {
                    if (this.checked) {
                        ledNouveauContainer.classList.remove('hidden');
                        ledExistantContainer.classList.add('hidden');
                        toggleDatasheetContainers(true);
                    }
                });
                
                ledExistant.addEventListener('change', function() {
                    if (this.checked) {
                        ledNouveauContainer.classList.add('hidden');
                        ledExistantContainer.classList.remove('hidden');
                        toggleDatasheetContainers(false);
                    }
                });
                
                // Ajout d'un écouteur d'événements pour le sélecteur de LED existante
                const ledExistantSelect = document.getElementById('led_existant_select');
                const ledDetailsPreview = document.getElementById('led_details_preview');
                const ledImage = document.getElementById('led_image');
                const noLedSelected = document.getElementById('no_led_selected');
                const ledDatasheetId = document.getElementById('led_datasheet_id');
                
                if (ledExistantSelect && ledDetailsPreview && ledImage && noLedSelected) {
                    ledExistantSelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const selectedLedId = this.value;
                        
                        if (selectedLedId) {
                            // Masquer le message "aucune LED sélectionnée"
                            noLedSelected.classList.add('hidden');
                            
                            // Récupérer directement les informations depuis les attributs data
                            const type = selectedOption.getAttribute('data-type') || '';
                            const size = selectedOption.getAttribute('data-size') || '';
                            const color = selectedOption.getAttribute('data-color') || 'black';
                            const pads = selectedOption.getAttribute('data-pads') || '4';
                            let padConfig = {};
                            
                            try {
                                // Récupérer la configuration des pads si elle existe
                                const padConfigStr = selectedOption.getAttribute('data-config');
                                if (padConfigStr) {
                                    padConfig = JSON.parse(padConfigStr);
                                }
                            } catch (e) {
                                console.error("Erreur lors du parsing de la configuration des pads:", e);
                            }
                            
                            // Mettre à jour l'ID du datasheet sélectionné
                            ledDatasheetId.value = selectedLedId;
                            
                            // Construction des détails de la LED avec format amélioré
                            let padConfigHtml = '';
                            if (Object.keys(padConfig).length > 0) {
                                padConfigHtml = '<div class="mt-2 py-1 px-2 bg-gray-800 rounded">';
                                for (const [padNum, padValue] of Object.entries(padConfig)) {
                                    padConfigHtml += `<span class="inline-block px-1 mr-1 mb-1 bg-gray-700 rounded">Pad ${padNum}: ${padValue}</span>`;
                                }
                                padConfigHtml += '</div>';
                            }
                            
                            // Construction des détails de la LED avec format corrigé
                            let ledDetails = `
                                <p><span class="font-medium">Type:</span> ${type}</p>
                                <p><span class="font-medium">Taille:</span> ${size}</p>
                                <p><span class="font-medium">Nombre de pads:</span> ${pads}</p>
                                <p><span class="font-medium">Couleur du PCB:</span> ${color === 'black' ? 'Noir' : 'Blanc'}</p>
                                ${padConfigHtml}
                            `;
                            
                            // Afficher les détails
                            ledDetailsPreview.innerHTML = ledDetails;
                            
                            // Démarrer le chargement de l'image
                            ledImage.classList.remove('hidden');
                            
                            // Récupérer l'image du datasheet
                            if (selectedLedId === '1') {
                                ledImage.src = "data:image/png;base64,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";
                            } else if (selectedLedId === '2') {
                                ledImage.src = "data:image/png;base64,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";
                            } else {
                                ledImage.src = "";
                                ledImage.classList.add('hidden');
                                noLedSelected.classList.remove('hidden');
                            }
                        } else {
                            // Aucune LED sélectionnée, masquer l'image
                            ledImage.classList.add('hidden');
                            noLedSelected.classList.remove('hidden');
                            ledDetailsPreview.innerHTML = '';
                            ledDatasheetId.value = '';
                        }
                    });
                }
            }
            
            // Détails du produit du catalogue
            const catalogueIdSelect = document.getElementById('catalogue_id');
            const detailMarque = document.getElementById('detail_marque');
            const detailModele = document.getElementById('detail_modele');
            const detailPitch = document.getElementById('detail_pitch');
            const detailElectronique = document.getElementById('detail_electronique');
            
            if (catalogueIdSelect) {
                catalogueIdSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    
                    if (selectedOption && selectedOption.value) {
                        // Simuler un chargement des détails (dans une impl. réelle, utiliserait une requête AJAX)
                        const produitText = selectedOption.text;
                        const parts = produitText.split(' - ');
                        
                        if (parts.length > 0) {
                            const nameParts = parts[0].split(' ');
                            const marque = nameParts.shift();
                            const modele = nameParts.join(' ');
                            const pitch = parts[1] || '---';
                            
                            if (detailMarque) detailMarque.innerHTML = `Marque: <span class="font-medium">${marque}</span>`;
                            if (detailModele) detailModele.innerHTML = `Modèle: <span class="font-medium">${modele}</span>`;
                            if (detailPitch) detailPitch.innerHTML = `Pitch: <span class="font-medium">${pitch}</span>`;
                            // L'électronique nécessiterait une requête API réelle
                            if (detailElectronique) detailElectronique.innerHTML = `Électronique: <span class="font-medium">Nova</span>`;
                        }
                    } else {
                        // Réinitialiser les détails
                        if (detailMarque) detailMarque.innerHTML = 'Marque: <span class="font-medium">---</span>';
                        if (detailModele) detailModele.innerHTML = 'Modèle: <span class="font-medium">---</span>';
                        if (detailPitch) detailPitch.innerHTML = 'Pitch: <span class="font-medium">---</span>';
                        if (detailElectronique) detailElectronique.innerHTML = 'Électronique: <span class="font-medium">---</span>';
                    }
                });
                
                // Déclencher l'événement change pour initialiser les valeurs
                if (catalogueIdSelect.value) {
                    const event = new Event('change');
                    catalogueIdSelect.dispatchEvent(event);
                }
            }
            
            // Script pour le calcul automatique de la résolution
            function setupAutoResolution() {
                const autoResolution = document.getElementById('auto_resolution');
                const pitchInput = document.getElementById('pitch');
                const largeurModuleInput = document.getElementById('largeur_module');
                const hauteurModuleInput = document.getElementById('hauteur_module');
                const nbPixelsLargeurInput = document.getElementById('nb_pixels_largeur');
                const nbPixelsHauteurInput = document.getElementById('nb_pixels_hauteur');
                const autoLargeurIcon = document.getElementById('auto_largeur_icon');
                const autoHauteurIcon = document.getElementById('auto_hauteur_icon');
                const resolutionAutoInfo = document.getElementById('resolution_auto_info');
                
                // Fonction pour calculer la résolution
                function calculateResolution() {
                    if (!autoResolution.checked) {
                        // Si automatique désactivé, ne rien faire
                        resolutionAutoInfo.classList.add('hidden');
                        autoLargeurIcon.classList.add('hidden');
                        autoHauteurIcon.classList.add('hidden');
                        nbPixelsLargeurInput.removeAttribute('readonly');
                        nbPixelsHauteurInput.removeAttribute('readonly');
                        return;
                    }
                    
                    const pitch = parseFloat(pitchInput.value);
                    const largeurModule = parseFloat(largeurModuleInput.value);
                    const hauteurModule = parseFloat(hauteurModuleInput.value);
                    
                    // Si les valeurs ne sont pas valides, ne pas calculer
                    if (isNaN(pitch) || isNaN(largeurModule) || isNaN(hauteurModule) || 
                        pitch <= 0 || largeurModule <= 0 || hauteurModule <= 0) {
                        return;
                    }
                    
                    // Calcul de la résolution
                    let pixelsLargeur = Math.round(largeurModule / pitch);
                    let pixelsHauteur = Math.round(hauteurModule / pitch);
                    
                    // Afficher le résultat
                    nbPixelsLargeurInput.value = pixelsLargeur;
                    nbPixelsHauteurInput.value = pixelsHauteur;
                    
                    // Afficher les indicateurs visuels
                    resolutionAutoInfo.classList.remove('hidden');
                    autoLargeurIcon.classList.remove('hidden');
                    autoHauteurIcon.classList.remove('hidden');
                    
                    // Rendre les champs readonly quand automatique
                    nbPixelsLargeurInput.setAttribute('readonly', 'readonly');
                    nbPixelsHauteurInput.setAttribute('readonly', 'readonly');
                    
                    console.log(`Résolution calculée: ${pixelsLargeur}x${pixelsHauteur} pixels (basé sur pitch=${pitch}mm, dimensions=${largeurModule}x${hauteurModule}mm)`);
                }
                
                // Associer les événements
                autoResolution.addEventListener('change', function() {
                    if (!this.checked) {
                        // Si désactivé, enlever le readonly
                        nbPixelsLargeurInput.removeAttribute('readonly');
                        nbPixelsHauteurInput.removeAttribute('readonly');
                        resolutionAutoInfo.classList.add('hidden');
                        autoLargeurIcon.classList.add('hidden');
                        autoHauteurIcon.classList.add('hidden');
                    } else {
                        // Si activé, recalculer
                        calculateResolution();
                    }
                });
                
                // Écouter les changements de valeurs
                pitchInput.addEventListener('input', calculateResolution);
                largeurModuleInput.addEventListener('input', calculateResolution);
                hauteurModuleInput.addEventListener('input', calculateResolution);
                
                // Calcul initial
                calculateResolution();
            }
            
            // Initialiser le calcul automatique
            setupAutoResolution();
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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/chantiers/create_step2_product.blade.php ENDPATH**/ ?>