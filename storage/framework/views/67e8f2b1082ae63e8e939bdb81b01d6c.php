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
            <?php echo e(__('Gestion des utilisateurs')); ?>

        </h1>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">Liste des utilisateurs</h3>
                        <a href="<?php echo e(route('admin.users.create')); ?>" class="px-4 py-2 bg-accent-yellow text-white rounded-lg hover:bg-yellow-500 transition duration-150 ease-in-out flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter un utilisateur
                        </a>
                    </div>

                    <?php if(session('success')): ?>
                        <div class="mb-4 p-4 bg-green-500/30 border border-green-500/50 text-green-400 rounded-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="mb-4 p-4 bg-red-500/30 border border-red-500/50 text-red-400 rounded-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="overflow-x-auto rounded-lg shadow-lg">
                        <table class="min-w-full table-styled border border-gray-700 bg-gray-800/50 text-gray-300">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Nom</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Email</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Rôle</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Date de création</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition duration-200">
                                        <td class="py-3 px-4"><?php echo e($user->name); ?></td>
                                        <td class="py-3 px-4"><?php echo e($user->email); ?></td>
                                        <td class="py-3 px-4">
                                            <?php if($user->role === 'admin'): ?>
                                                <span class="badge badge-danger">Admin</span>
                                            <?php else: ?>
                                                <span class="badge badge-info">Technicien</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4"><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                                        <td class="py-3 px-4 flex gap-2">
                                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>" 
                                               class="btn-action btn-primary flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Modifier
                                            </a>
                                            
                                            <?php if($user->id !== auth()->id()): ?>
                                                <form method="POST" action="<?php echo e(route('admin.users.delete', $user)); ?>" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn-action bg-red-600 hover:bg-red-700 text-white flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Supprimer
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/admin/users/index.blade.php ENDPATH**/ ?>