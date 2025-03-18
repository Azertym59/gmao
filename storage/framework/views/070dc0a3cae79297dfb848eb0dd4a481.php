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
            <?php echo e(__('Tableau de bord administrateur')); ?>

        </h1>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Statistiques en cards flexibles -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <!-- Utilisateurs -->
                <div class="glassmorphism-static rounded-2xl p-5 border border-indigo-500/20 hover:border-indigo-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="mr-4 bg-indigo-500/10 p-3 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-indigo-300 text-sm font-medium">Utilisateurs</h3>
                            <p class="text-white text-2xl font-bold"><?php echo e($stats['users_count']); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Clients -->
                <div class="glassmorphism-static rounded-2xl p-5 border border-blue-500/20 hover:border-blue-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="mr-4 bg-blue-500/10 p-3 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-blue-300 text-sm font-medium">Clients</h3>
                            <p class="text-white text-2xl font-bold"><?php echo e($stats['clients_count']); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Chantiers -->
                <div class="glassmorphism-static rounded-2xl p-5 border border-amber-500/20 hover:border-amber-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="mr-4 bg-amber-500/10 p-3 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-amber-300 text-sm font-medium">Chantiers</h3>
                            <p class="text-white text-2xl font-bold"><?php echo e($stats['chantiers_count']); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Produits Catalogue -->
                <div class="glassmorphism-static rounded-2xl p-5 border border-green-500/20 hover:border-green-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="mr-4 bg-green-500/10 p-3 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-green-300 text-sm font-medium">Produits</h3>
                            <p class="text-white text-2xl font-bold"><?php echo e($stats['produits_catalogue_count']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions principales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <!-- Gestion des utilisateurs -->
                <div class="glassmorphism-static rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-lg border border-indigo-500/20 hover:border-indigo-500/40">
                    <div class="px-5 py-4 border-b border-gray-700 bg-gradient-to-r from-indigo-900/30 to-transparent">
                        <h3 class="text-white font-medium flex items-center text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Gestion utilisateurs
                        </h3>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-300 mb-5 text-sm">
                            Gérez les comptes utilisateurs, assignez les rôles et définissez les permissions d'accès au système.
                        </p>
                        <div class="flex justify-end">
                            <a href="<?php echo e(route('admin.users')); ?>" class="btn-action btn-primary inline-flex items-center transition-transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Gérer
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Suivi des chantiers -->
                <div class="glassmorphism-static rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-lg border border-amber-500/20 hover:border-amber-500/40">
                    <div class="px-5 py-4 border-b border-gray-700 bg-gradient-to-r from-amber-900/30 to-transparent">
                        <h3 class="text-white font-medium flex items-center text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Gestion chantiers
                        </h3>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-300 mb-5 text-sm">
                            Suivez l'avancement des chantiers en cours, consultez les statistiques et gérez les interventions.
                        </p>
                        <div class="flex justify-end">
                            <a href="<?php echo e(route('chantiers.index')); ?>" class="btn-action btn-primary inline-flex items-center transition-transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Gérer
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Base de données -->
                <div class="glassmorphism-static rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-lg border border-cyan-500/20 hover:border-cyan-500/40">
                    <div class="px-5 py-4 border-b border-gray-700 bg-gradient-to-r from-cyan-900/30 to-transparent">
                        <h3 class="text-white font-medium flex items-center text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                            </svg>
                            Base de données
                        </h3>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-300 mb-5 text-sm">
                            Effectuez des sauvegardes, restaurez des données ou réinitialisez la base de données si nécessaire.
                        </p>
                        <div class="flex justify-end">
                            <a href="<?php echo e(route('admin.database.manager')); ?>" class="btn-action btn-primary inline-flex items-center transition-transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Gérer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Accès rapides -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <!-- Clients -->
                <a href="<?php echo e(route('clients.index')); ?>" class="glassmorphism-static flex flex-col items-center p-5 rounded-2xl border border-blue-500/20 hover:border-blue-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl text-center">
                    <div class="bg-blue-500/10 p-3 rounded-full mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span class="text-white font-medium">Clients</span>
                </a>
                
                <!-- Produits -->
                <a href="<?php echo e(route('produits.index')); ?>" class="glassmorphism-static flex flex-col items-center p-5 rounded-2xl border border-green-500/20 hover:border-green-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl text-center">
                    <div class="bg-green-500/10 p-3 rounded-full mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                    <span class="text-white font-medium">Produits</span>
                </a>
                
                <!-- Dalles -->
                <a href="<?php echo e(route('dalles.index')); ?>" class="glassmorphism-static flex flex-col items-center p-5 rounded-2xl border border-cyan-500/20 hover:border-cyan-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl text-center">
                    <div class="bg-cyan-500/10 p-3 rounded-full mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <span class="text-white font-medium">Dalles</span>
                </a>
                
                <!-- Modules -->
                <a href="<?php echo e(route('modules.index')); ?>" class="glassmorphism-static flex flex-col items-center p-5 rounded-2xl border border-rose-500/20 hover:border-rose-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl text-center">
                    <div class="bg-rose-500/10 p-3 rounded-full mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <span class="text-white font-medium">Modules</span>
                </a>
                
                <!-- Interventions -->
                <a href="<?php echo e(route('interventions.index')); ?>" class="glassmorphism-static flex flex-col items-center p-5 rounded-2xl border border-purple-500/20 hover:border-purple-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl text-center">
                    <div class="bg-purple-500/10 p-3 rounded-full mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <span class="text-white font-medium">Interventions</span>
                </a>
                
                <!-- Rapports -->
                <a href="<?php echo e(route('rapports.index')); ?>" class="glassmorphism-static flex flex-col items-center p-5 rounded-2xl border border-amber-500/20 hover:border-amber-500/40 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl text-center">
                    <div class="bg-amber-500/10 p-3 rounded-full mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-white font-medium">Rapports</span>
                </a>
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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>