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
        <h1 class="text-2xl font-bold text-text-primary leading-tight">
            <?php echo e(__('Registre SAV & Ventes')); ?>

        </h1>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- En-tête avec titre et bouton d'action (design modernisé) -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 px-2">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-accent-green to-accent-blue rounded-lg p-2 mr-3 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-text-primary">Liste des projets</h2>
                            <p class="text-text-secondary mt-1">Gérez efficacement vos projets de vente et SAV</p>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="<?php echo e(route('chantiers.create')); ?>" 
                       class="btn-modern btn-primary inline-flex items-center px-5 py-3 border-accent-green text-white font-semibold rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter un projet
                    </a>
                </div>
            </div>

            <!-- Message de succès (design modernisé) -->
            <?php if(session('success')): ?>
                <div class="bg-gradient-to-r from-green-500/10 to-green-600/10 border-l-4 border-l-green-500 rounded-lg shadow-md px-6 py-4 mb-6 mx-2 relative">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-full p-1.5 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="font-medium text-text-primary"><?php echo e(session('success')); ?></p>
                    </div>
                    <button onclick="this.parentElement.style.display = 'none'" class="absolute top-3 right-3 text-text-secondary hover:text-text-primary transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            <?php endif; ?>
            
            <!-- Tableau des chantiers (design modernisé) -->
            <div class="bg-gradient-to-b from-gray-900 to-gray-800 rounded-xl shadow-xl overflow-hidden border border-gray-700 mx-2">
                <div class="overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-700/40">
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-white tracking-wider uppercase">Référence</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-white tracking-wider uppercase">Client</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-white tracking-wider uppercase">Date butoir</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-white tracking-wider uppercase">Temps restant</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-white tracking-wider uppercase">Avancement</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-white tracking-wider uppercase">État</th>
                                    <th class="py-4 px-6 text-left text-sm font-semibold text-white tracking-wider uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/30">
                                <?php $__empty_1 = true; $__currentLoopData = $chantiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chantier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-700/20 transition-colors duration-150">
                                        <td class="py-4 px-6 whitespace-nowrap font-medium text-text-primary"><?php echo e($chantier->reference); ?></td>
                                        <td class="py-4 px-6 whitespace-nowrap text-text-secondary"><?php echo e($chantier->client->societe ?: $chantier->client->nom_complet); ?></td>
                                        <td class="py-4 px-6 whitespace-nowrap text-text-secondary"><?php echo e($chantier->date_butoir->format('d/m/Y')); ?></td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <span class="<?php echo e(\App\Helpers\DateHelper::getTimeRemainingClass($chantier->date_butoir)); ?> px-3 py-1 rounded-full text-xs font-medium">
                                                <?php echo e(\App\Helpers\DateHelper::formatTimeRemaining($chantier->date_butoir)); ?>

                                            </span>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="w-full flex items-center">
                                                <div class="w-24 bg-gray-700 rounded-full h-2 mr-2">
                                                    <div class="h-2 rounded-full 
                                                    <?php
                                                        $progress = $chantier->pourcentage_avancement ?? 0;
                                                        if ($progress < 30) {
                                                            echo 'bg-red-500';
                                                        } elseif ($progress < 70) {
                                                            echo 'bg-yellow-500';
                                                        } else {
                                                            echo 'bg-green-500';
                                                        }
                                                    ?>
                                                    " style="width: <?php echo e($chantier->pourcentage_avancement ?? 0); ?>%"></div>
                                                </div>
                                                <span class="text-xs text-gray-400"><?php echo e($chantier->pourcentage_avancement ?? 0); ?>%</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <?php if($chantier->etat == 'non_commence'): ?>
                                                <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-medium">Non commencé</span>
                                            <?php elseif($chantier->etat == 'en_cours'): ?>
                                                <span class="px-3 py-1 bg-amber-500/20 text-amber-400 rounded-full text-xs font-medium">En cours</span>
                                            <?php elseif($chantier->etat == 'stand_by'): ?>
                                                <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-medium">En attente</span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium">Terminé</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <div class="flex gap-2">
                                                <a href="<?php echo e(route('chantiers.show', $chantier)); ?>" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-accent-blue/10 text-accent-blue hover:bg-accent-blue/20 rounded-lg transition-colors duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Voir
                                                </a>
                                                <a href="<?php echo e(route('chantiers.edit', $chantier)); ?>" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-gray-600/10 text-gray-300 hover:bg-gray-600/20 rounded-lg transition-colors duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Modifier
                                                </a>
                                                <button type="button"
                                                    onclick="openDeleteModal('<?php echo e($chantier->id); ?>', '<?php echo e($chantier->reference); ?>', '<?php echo e($chantier->client->societe ?: $chantier->client->nom_complet); ?>')"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-600/10 text-red-400 hover:bg-red-600/20 rounded-lg transition-colors duration-200" 
                                                    title="Supprimer le chantier">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-4">
                                                <div class="bg-gradient-to-r from-accent-blue to-accent-green rounded-full p-4 w-20 h-20 flex items-center justify-center shadow-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-bold text-text-primary">Aucun projet trouvé</h3>
                                                    <p class="text-text-secondary text-sm mt-1">Créez votre premier projet pour démarrer</p>
                                                </div>
                                                <a href="<?php echo e(route('chantiers.create')); ?>" 
                                                   class="mt-2 btn-modern btn-primary inline-flex items-center px-5 py-2.5 border-accent-green text-white font-semibold rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 ease-in-out">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Créer mon premier projet
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if(isset($chantiers) && method_exists($chantiers, 'links')): ?>
                        <div class="p-6 border-t border-gray-700/50 bg-gray-800/50">
                            <div class="pagination-container">
                                <?php echo e($chantiers->links()); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmation de suppression -->
    <div id="deleteModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        
        <div class="bg-gradient-to-b from-gray-900 to-gray-800 border border-gray-700 rounded-xl shadow-2xl p-6 w-full max-w-md z-10 relative transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
            <div class="flex justify-between items-center border-b border-gray-700 pb-4 mb-4">
                <h3 class="text-xl font-semibold text-white">Confirmation de suppression</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="mb-6">
                <p class="text-red-400 font-semibold mb-4">Attention : Cette action est irréversible !</p>
                <p class="text-gray-300 mb-4">Vous êtes sur le point de supprimer définitivement ce chantier et toutes ses données associées (dalles, modules, interventions, etc.).</p>
                
                <div class="bg-red-900/20 border border-red-700/30 rounded-lg p-4 mt-4 mb-6">
                    <p class="text-gray-300 mb-2">Pour confirmer la suppression, veuillez saisir :</p>
                    <ul class="list-disc list-inside text-gray-300 mb-4">
                        <li>La référence du chantier : <span id="referenceToConfirm" class="font-bold text-red-400"></span></li>
                        <li>Le nom du client : <span id="clientToConfirm" class="font-bold text-red-400"></span></li>
                    </ul>
                </div>
                
                <form id="deleteForm" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    
                    <div class="mb-4">
                        <label for="reference_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Référence du chantier</label>
                        <input type="text" id="reference_confirmation" name="reference_confirmation" 
                               class="w-full px-4 py-2 bg-gray-700/70 border border-gray-600 rounded-lg text-white focus:ring-accent-blue focus:border-accent-blue"
                               placeholder="Entrez la référence exacte" required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="client_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Nom du client</label>
                        <input type="text" id="client_confirmation" name="client_confirmation" 
                               class="w-full px-4 py-2 bg-gray-700/70 border border-gray-600 rounded-lg text-white focus:ring-accent-blue focus:border-accent-blue"
                               placeholder="Entrez le nom exact du client" required>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDeleteModal()" 
                                class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                            Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Variable pour stocker l'état d'initialisation du modal
        let modalInitialized = false;
        
        // Fonction pour ouvrir le modal de suppression
        function openDeleteModal(chantierID, reference, client) {
            // Définir l'action du formulaire
            document.getElementById('deleteForm').action = '/chantiers/' + chantierID;
            
            // Afficher les informations à confirmer
            document.getElementById('referenceToConfirm').textContent = reference;
            document.getElementById('clientToConfirm').textContent = client;
            
            // Afficher le modal avec animation
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('modalContent');
            
            // Initialiser les listeners une seule fois
            if (!modalInitialized) {
                initializeModalListeners();
                modalInitialized = true;
            }
            
            // Empêcher que le modal ne se ferme immédiatement à cause de l'événement click
            // en utilisant un timeout pour l'afficher au prochain cycle d'événements
            setTimeout(() => {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 50);
            }, 0);
            
            // Empêcher la propagation de l'événement click qui a déclenché l'ouverture
            event.stopPropagation();
        }
        
        // Fonction pour fermer le modal
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('modalContent');
            
            // Animation de fermeture
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                // Réinitialiser les champs du formulaire
                document.getElementById('reference_confirmation').value = '';
                document.getElementById('client_confirmation').value = '';
            }, 300);
        }
        
        // Fonction d'initialisation des listeners du modal
        function initializeModalListeners() {
            // Capturer l'événement click au niveau du backdrop pour fermer le modal
            document.getElementById('deleteModal').addEventListener('mousedown', function(event) {
                const modalContent = document.getElementById('modalContent');
                
                // Si le clic est sur le backdrop (pas sur le contenu du modal)
                if (event.target === this && !modalContent.contains(event.target)) {
                    closeDeleteModal();
                }
            });
            
            // Empêcher la propagation des clics à l'intérieur du modal
            document.getElementById('modalContent').addEventListener('mousedown', function(event) {
                event.stopPropagation();
            });
            
            // Ajouter un gestionnaire pour la touche Escape
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const modal = document.getElementById('deleteModal');
                    if (!modal.classList.contains('hidden')) {
                        closeDeleteModal();
                    }
                }
            });
        }
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
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/chantiers/index.blade.php ENDPATH**/ ?>