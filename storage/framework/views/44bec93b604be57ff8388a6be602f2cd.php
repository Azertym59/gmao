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
                                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'driver','value' => __('Driver (IC de commande)'),'class' => 'text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'driver','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Driver (IC de commande)')),'class' => 'text-gray-300']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'driver','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'driver','value' => old('driver')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'driver','class' => 'block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50','type' => 'text','name' => 'driver','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('driver'))]); ?>
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
                                                <option value="SMD1515RGB+0">SMD1515RGB+0</option>
                                                <option value="SMD2121RGB+0">SMD2121RGB+0</option>
                                            </select>
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
                                <div class="mb-4">
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
                ledNouveau.addEventListener('change', function() {
                    if (this.checked) {
                        ledNouveauContainer.classList.remove('hidden');
                        ledExistantContainer.classList.add('hidden');
                    }
                });
                
                ledExistant.addEventListener('change', function() {
                    if (this.checked) {
                        ledNouveauContainer.classList.add('hidden');
                        ledExistantContainer.classList.remove('hidden');
                    }
                });
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