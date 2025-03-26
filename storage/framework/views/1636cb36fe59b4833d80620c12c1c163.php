<?php
use Illuminate\Support\Facades\Auth;
?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'GMAO TecaLED')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/fixes.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/module-busy.css')); ?>?v=<?php echo e(time()); ?>">
    
    <!-- QZ Tray -->
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.0/qz-tray.js"></script>
    <script src="<?php echo e(asset('js/qz-tray.js')); ?>?v=<?php echo e(time()); ?>"></script>
    
    <!-- Module Status Tracker -->
    <script src="<?php echo e(asset('js/components/module-status-tracker.js')); ?>?v=<?php echo e(time()); ?>"></script>
    
    <!-- Intervention Heartbeat -->
    <script src="<?php echo e(asset('js/components/intervention-heartbeat.js')); ?>?v=<?php echo e(time()); ?>"></script>
    
    <!-- Module Menu Toggle Function -->
    <script>
        // Ferme tous les menus de module ouverts
        function closeAllModuleMenus() {
            document.querySelectorAll('.module-menu-container').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
        
        // Bascule un menu spécifique
        function toggleModuleMenu(menuId) {
            closeAllModuleMenus();
            const menu = document.getElementById(menuId);
            if (menu) {
                menu.classList.toggle('hidden');
            }
            
            // Ajoute un gestionnaire global de clic pour fermer le menu lors d'un clic à l'extérieur
            setTimeout(() => {
                document.addEventListener('click', function closeMenuOnClickOutside(e) {
                    if (!menu.contains(e.target) && !e.target.closest('.module-item-wrapper button')) {
                        menu.classList.add('hidden');
                        document.removeEventListener('click', closeMenuOnClickOutside);
                    }
                });
            }, 10);
            
            // Empêche la propagation du clic
            event.stopPropagation();
        }
        
        // Gestionnaire d'événement pour la touche Echap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAllModuleMenus();
            }
        });
    </script>
    
    <!-- Rafraîchissement automatique des états des modules et auto-nettoyage -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction d'auto-nettoyage silencieux qui s'exécute à chaque chargement de page
            function silentAutoCleanModules() {
                // Utiliser une requête AJAX pour nettoyer les modules en arrière-plan
                fetch('/maintenance/auto-clean-modules', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => {
                    // Pas besoin de faire quoi que ce soit avec la réponse
                    // L'opération est silencieuse et ne perturbe pas l'utilisateur
                });
            }
            
            // Exécuter l'auto-nettoyage immédiatement
            silentAutoCleanModules();
            
            // Puis toutes les 5 minutes
            setInterval(silentAutoCleanModules, 300000);
            
            // Note: Le rechargement de la page ci-dessous est désormais remplacé par
            // le ModuleStatusTracker qui met à jour les modules sans recharger la page
        });
    </script>

    <!-- Script pour le menu hamburger mobile -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileCloseButton = document.getElementById('mobile-close-button');
            const overlay = document.getElementById('mobile-menu-overlay');
            
            // Fonction pour ouvrir le menu
            function openMenu() {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                if (overlay) overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden', 'md:overflow-auto'); // Empêcher le défilement sur mobile
            }
            
            // Fonction pour fermer le menu
            function closeMenu() {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden', 'md:overflow-auto'); // Permettre le défilement
            }
            
            // Écouteurs d'événements
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', openMenu);
            }
            
            if (mobileCloseButton) {
                mobileCloseButton.addEventListener('click', closeMenu);
            }
            
            if (overlay) {
                overlay.addEventListener('click', closeMenu);
            }
            
            // Fermer le menu lors d'un clic sur un lien (pour mobile)
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Attendre que la navigation se produise avant de fermer
                    setTimeout(closeMenu, 300);
                });
            });
            
            // Ajustement de la taille de l'écran
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    // Si on passe en vue desktop, on réinitialise
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    if (overlay) overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    // Si on passe en vue mobile et que le menu n'est pas ouvert
                    if (!sidebar.classList.contains('translate-x-0')) {
                        sidebar.classList.add('-translate-x-full');
                    }
                }
            });
        });
    </script>
    
    <?php echo $__env->yieldPushContent('meta'); ?>
</head>
<body class="font-sans antialiased h-full bg-app-bg text-text-primary">
    <!-- Overlay de fond pour le menu mobile -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden md:hidden transition-opacity duration-300"></div>
    
    <div class="min-h-screen">
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <!-- Contenu principal avec marge pour accommoder la sidebar en desktop et pas en mobile -->
        <div class="min-h-screen md:ml-64 transition-all duration-300">
            <!-- Header avec bouton hamburger en version mobile -->
            <header class="bg-sidebar border-b border-border-light shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 pl-16 md:pl-4 sm:px-6 lg:px-8">
                    <?php if(isset($header)): ?>
                        <?php echo e($header); ?>

                    <?php else: ?>
                        <h1 class="text-2xl font-semibold text-white">
                            <?php echo $__env->yieldContent('header', 'Dashboard'); ?>
                        </h1>
                    <?php endif; ?>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-4">
                <!-- Flash Messages -->
                <?php if(session('success')): ?>
                <div id="successMessage" class="mb-6 alert-success">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p><?php echo e(session('success')); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div id="errorMessage" class="mb-6 alert-error">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p><?php echo e(session('error')); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(isset($slot)): ?>
                    <?php echo e($slot); ?>

                <?php else: ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php endif; ?>
            </main>
        </div>
    </div>
    
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }
            
            // Auto-hide flash messages after 5 seconds
            setTimeout(function() {
                const messages = document.querySelectorAll('#successMessage, #errorMessage, #infoMessage, #warningMessage');
                messages.forEach(function(message) {
                    if (message) {
                        message.style.display = 'none';
                    }
                });
            }, 5000);
        });
    </script>
</body>
</html><?php /**PATH /var/www/gmao/resources/views/layouts/app.blade.php ENDPATH**/ ?>