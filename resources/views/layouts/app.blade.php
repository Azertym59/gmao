@php
use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GMAO TecaLED') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/fixes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/module-busy.css') }}?v={{ time() }}">
    
    <!-- QZ Tray -->
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.0/qz-tray.js"></script>
    <script src="{{ asset('js/qz-tray.js') }}?v={{ time() }}"></script>
    
    <!-- Module Status Tracker -->
    <script src="{{ asset('js/components/module-status-tracker.js') }}?v={{ time() }}"></script>
    
    <!-- Intervention Heartbeat -->
    <script src="{{ asset('js/components/intervention-heartbeat.js') }}?v={{ time() }}"></script>
    
    <!-- Module Menu Functions -->
    <script>
        // Variables globales pour le système de menu des modules
        let activeModuleMenu = null;
        
        // Styles pour les boutons et menus des modules
        const moduleMenuStyles = `
            /* Style pour le conteneur du module */
            .module-container {
                position: relative;
            }
            
            /* Style pour le bouton du menu */
            .module-menu-button {
                position: absolute;
                top: 0;
                right: 0;
                width: 16px;
                height: 16px;
                background-color: rgba(31, 41, 55, 0.8);
                border-radius: 0 0 0 4px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 20;
                transition: background-color 0.2s;
            }
            
            .module-menu-button:hover {
                background-color: rgba(55, 65, 81, 0.8);
            }
            
            .module-menu-button svg {
                width: 12px;
                height: 12px;
                color: white;
            }
            
            /* Style pour le menu contextuel */
            .module-context-menu {
                position: absolute;
                top: -5px;
                right: -5px;
                background-color: #1f2937;
                border: 1px solid #374151;
                border-radius: 6px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
                padding: 8px;
                z-index: 100;
                min-width: 140px;
                display: none;
            }
            
            /* Titre du menu */
            .context-menu-title {
                font-size: 0.75rem;
                color: #9ca3af;
                font-weight: 600;
                text-align: center;
                margin-bottom: 0.5rem;
                padding-bottom: 0.25rem;
                border-bottom: 1px solid #374151;
            }
            
            /* Conteneur des actions */
            .context-menu-actions {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            /* Style des boutons d'action */
            .context-menu-action {
                width: 100%;
                font-size: 0.75rem;
                padding: 0.375rem 0.5rem;
                border-radius: 0.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                transition: background-color 0.2s;
                text-decoration: none;
            }
            
            .context-menu-action svg {
                height: 0.75rem;
                width: 0.75rem;
                margin-right: 0.375rem;
            }
            
            /* Couleurs spécifiques pour les actions */
            .action-ok {
                background-color: #059669;
            }
            .action-ok:hover {
                background-color: #10b981;
            }
            
            .action-intervention {
                background-color: #2563eb;
            }
            .action-intervention:hover {
                background-color: #3b82f6;
            }
            
            .action-details {
                background-color: #7c3aed;
            }
            .action-details:hover {
                background-color: #8b5cf6;
            }
        `;
        
        // Fonction pour positionner le menu flottant des modules
        function moduleMenuPosition(menuId, trigger) {
            // Fermer tous les menus existants
            document.querySelectorAll('.module-menu-floating').forEach(menu => {
                if (menu.id !== menuId) {
                    menu.classList.add('hidden');
                }
            });
            
            // Obtenir le menu
            const menu = document.getElementById(menuId);
            if (!menu) return;
            
            // Vérifier si le menu est dans le DOM
            if (!document.body.contains(menu)) {
                return;
            }
            
            // Obtenir la position du déclencheur
            const triggerRect = trigger.getBoundingClientRect();
            
            // S'assurer que le menu est placé au niveau du body
            if (menu.parentElement !== document.body) {
                document.body.appendChild(menu);
            }
            
            // Définir la position du menu
            menu.style.position = 'fixed';
            menu.style.zIndex = '9999';
            
            // Positionnement à droite du module
            menu.style.left = (triggerRect.right + 10) + 'px';
            
            // Positionnement vertical centré avec le module
            menu.style.top = (triggerRect.top - 5) + 'px';
            
            // Afficher le menu
            menu.classList.remove('hidden');
            
            // Ajouter un gestionnaire de clic global pour fermer le menu
            setTimeout(() => {
                const closeMenu = function(e) {
                    if (!menu.contains(e.target) && !trigger.contains(e.target)) {
                        menu.classList.add('hidden');
                        document.removeEventListener('click', closeMenu);
                    }
                };
                
                document.addEventListener('click', closeMenu);
            }, 50);
        }
        
        // Injecter les styles dans la page
        function injectModuleMenuStyles() {
            const styleElement = document.createElement('style');
            styleElement.textContent = moduleMenuStyles;
            document.head.appendChild(styleElement);
        }
        
        // Fermer tous les menus de module
        function closeAllModuleMenus() {
            document.querySelectorAll('.module-context-menu').forEach(menu => {
                menu.style.display = 'none';
            });
            
            // Pour la rétro-compatibilité
            document.querySelectorAll('[data-module-menu]').forEach(menu => {
                menu.classList.add('hidden');
            });
            
            activeModuleMenu = null;
        }
        
        // Ajouter des boutons de menu aux modules
        function initModuleMenus() {
            // Sélectionner tous les modules sans menus
            const moduleElements = document.querySelectorAll('.aspect-square.w-10.h-10');
            
            moduleElements.forEach(moduleElement => {
                // Vérifier si le module a déjà un menu
                if (moduleElement.querySelector('.module-menu-button') || 
                    moduleElement.closest('.module-container')?.querySelector('.module-menu-button')) {
                    return;
                }
                
                // Ajouter la classe pour position relative au parent
                const parent = moduleElement.closest('a') || moduleElement;
                const parentWrapper = parent.parentElement;
                
                if (!parentWrapper.classList.contains('module-container')) {
                    parentWrapper.classList.add('module-container');
                }
                
                // Extraire l'ID du module et son état
                let moduleId, isTermine;
                
                // Essayer d'extraire depuis le titre
                if (moduleElement.hasAttribute('title')) {
                    const titleParts = moduleElement.getAttribute('title').split(' - ');
                    if (titleParts[0].includes('Module #')) {
                        moduleId = titleParts[0].replace('Module #', '');
                        const moduleState = titleParts[1] || '';
                        isTermine = moduleState.toLowerCase().includes('termin');
                    }
                }
                
                // Sinon essayer d'autres moyens
                if (!moduleId) {
                    // Chercher dans un lien parent
                    const parentLink = moduleElement.closest('a[href*="/modules/"]');
                    if (parentLink) {
                        const href = parentLink.getAttribute('href');
                        moduleId = href.split('/modules/')[1];
                    }
                    
                    // Déterminer l'état par les classes
                    isTermine = moduleElement.classList.contains('bg-accent-green');
                }
                
                // Si on n'a pas pu identifier le module, ne rien faire
                if (!moduleId) return;
                
                // Créer le bouton du menu
                const menuButton = document.createElement('button');
                menuButton.type = 'button';
                menuButton.className = 'module-menu-button';
                menuButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                    </svg>
                `;
                
                // Créer le menu contextuel
                const contextMenu = document.createElement('div');
                contextMenu.className = 'module-context-menu';
                contextMenu.innerHTML = `
                    <div class="context-menu-title">Module #${moduleId}</div>
                    <div class="context-menu-actions">
                        ${!isTermine ? `
                        <button class="context-menu-action action-ok" data-module-id="${moduleId}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            OK
                        </button>
                        ` : ''}
                        <a href="/interventions/create?module_id=${moduleId}" class="context-menu-action action-intervention">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Intervention
                        </a>
                        <a href="/modules/${moduleId}" class="context-menu-action action-details">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Détails
                        </a>
                    </div>
                `;
                
                // Gérer le cas où le parent est un lien <a>
                if (parent.tagName.toLowerCase() === 'a') {
                    // Remplacer le lien par un div
                    const newContainer = document.createElement('div');
                    newContainer.className = parent.className;
                    newContainer.style.cssText = parent.style.cssText;
                    newContainer.innerHTML = parent.innerHTML;
                    
                    // Remplacer le lien
                    parent.parentNode.replaceChild(newContainer, parent);
                    
                    // Ajouter nos éléments
                    newContainer.appendChild(menuButton);
                    newContainer.appendChild(contextMenu);
                } else {
                    // Ajouter directement
                    parent.appendChild(menuButton);
                    parent.appendChild(contextMenu);
                }
                
                // Gérer le clic sur le bouton du menu
                menuButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Fermer tous les autres menus
                    document.querySelectorAll('.module-context-menu').forEach(menu => {
                        if (menu !== contextMenu) {
                            menu.style.display = 'none';
                        }
                    });
                    
                    // Afficher ou masquer le menu actuel
                    if (contextMenu.style.display === 'block') {
                        contextMenu.style.display = 'none';
                    } else {
                        contextMenu.style.display = 'block';
                        activeModuleMenu = contextMenu;
                    }
                });
                
                // Gérer l'action "OK"
                const okButton = contextMenu.querySelector('.action-ok');
                if (okButton) {
                    okButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Afficher un indicateur de chargement
                        contextMenu.innerHTML = `
                            <div class="context-menu-title">Module #${moduleId}</div>
                            <div class="text-center p-2">
                                <svg class="animate-spin h-5 w-5 mx-auto text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="mt-1 text-xs">Mise à jour...</p>
                            </div>
                        `;
                        
                        // Appel AJAX pour marquer le module terminé
                        fetch(`/modules/${moduleId}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                etat: 'termine'
                            })
                        })
                        .then(response => {
                            if (response.ok) {
                                // Mettre à jour visuellement l'état du module
                                moduleElement.classList.remove('bg-gray-600', 'bg-accent-yellow', 'bg-accent-red');
                                moduleElement.classList.add('bg-accent-green');
                                
                                // Fermer le menu
                                contextMenu.style.display = 'none';
                                
                                // Mettre à jour les statistiques
                                updateModuleStats();
                            } else {
                                contextMenu.style.display = 'none';
                                alert('Erreur lors de la mise à jour du module.');
                            }
                        })
                        .catch(error => {
                            contextMenu.style.display = 'none';
                            console.error('Erreur:', error);
                        });
                    });
                }
            });
            
            // Gérer les fermetures de menu pour tous les menus
            document.addEventListener('click', function(e) {
                if (activeModuleMenu && !activeModuleMenu.contains(e.target) && 
                    !e.target.closest('.module-menu-button')) {
                    activeModuleMenu.style.display = 'none';
                    activeModuleMenu = null;
                }
            });
            
            // Fermeture avec Echap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && activeModuleMenu) {
                    activeModuleMenu.style.display = 'none';
                    activeModuleMenu = null;
                }
            });
        }
        
        // Mise à jour des compteurs et statistiques
        function updateModuleStats() {
            // Mettre à jour les compteurs par dalle
            const dalleContainers = document.querySelectorAll('.glassmorphism.border.border-gray-700.rounded-xl.p-4');
            
            dalleContainers.forEach(container => {
                const modulesTotal = container.querySelectorAll('.aspect-square.w-10.h-10').length;
                const modulesTermines = container.querySelectorAll('.aspect-square.w-10.h-10.bg-accent-green').length;
                
                if (modulesTotal > 0) {
                    // Mettre à jour le badge
                    const progressBadge = container.querySelector('.badge.badge-success');
                    if (progressBadge) {
                        progressBadge.textContent = `${modulesTermines}/${modulesTotal}`;
                    }
                    
                    // Mettre à jour la barre de progression
                    const percentage = Math.round((modulesTermines / modulesTotal) * 100);
                    const progressBar = container.querySelector('.bg-accent-green.rounded-full');
                    if (progressBar) {
                        progressBar.style.width = `${percentage}%`;
                    }
                    
                    // Mettre à jour les textes de pourcentage
                    const percentText = container.querySelector('.flex.justify-between.text-xs.mb-1.text-gray-300 span:first-child');
                    if (percentText) {
                        percentText.textContent = `${percentage}%`;
                    }
                    
                    const countText = container.querySelector('.flex.justify-between.text-xs.mb-1.text-gray-300 span:last-child');
                    if (countText) {
                        countText.textContent = `${modulesTermines}/${modulesTotal} modules`;
                    }
                }
            });
            
            // Mettre à jour les statistiques globales
            const totalModules = document.querySelectorAll('.aspect-square.w-10.h-10').length;
            const modulesTermines = document.querySelectorAll('.aspect-square.w-10.h-10.bg-accent-green').length;
            const modulesEnCours = document.querySelectorAll('.aspect-square.w-10.h-10.bg-accent-yellow').length;
            const modulesDefaillants = document.querySelectorAll('.aspect-square.w-10.h-10.bg-accent-red').length;
            const modulesNonCommences = totalModules - modulesTermines - modulesEnCours - modulesDefaillants;
            
            // Mettre à jour la barre de progression globale
            const globalProgressBar = document.querySelector('.h-4.w-full.bg-gray-700.rounded-full.overflow-hidden .bg-accent-green');
            if (globalProgressBar) {
                const percentage = Math.round((modulesTermines / totalModules) * 100);
                globalProgressBar.style.width = `${percentage}%`;
                
                // Mettre à jour le texte
                const progressText = document.querySelector('.flex.justify-between.text-sm.mb-1.text-gray-300 span:first-child');
                if (progressText) {
                    progressText.textContent = `Progression: ${percentage}%`;
                }
                
                const countText = document.querySelector('.flex.justify-between.text-sm.mb-1.text-gray-300 span:last-child');
                if (countText) {
                    countText.textContent = `${modulesTermines}/${totalModules} modules`;
                }
            }
            
            // Mise à jour des compteurs détaillés
            const statsContainer = document.querySelector('.grid.grid-cols-2.gap-3.text-sm.text-gray-300');
            if (statsContainer) {
                const statDivs = statsContainer.querySelectorAll('div');
                
                if (statDivs.length >= 4) {
                    // Terminés
                    const terminesEl = statDivs[0].querySelector('span:last-child');
                    if (terminesEl) terminesEl.textContent = `Terminés: ${modulesTermines}`;
                    
                    // En cours
                    const enCoursEl = statDivs[1].querySelector('span:last-child');
                    if (enCoursEl) enCoursEl.textContent = `En cours: ${modulesEnCours}`;
                    
                    // Défaillants
                    const defaillantsEl = statDivs[2].querySelector('span:last-child');
                    if (defaillantsEl) defaillantsEl.textContent = `Défaillants: ${modulesDefaillants}`;
                    
                    // Non commencés
                    const nonCommencesEl = statDivs[3].querySelector('span:last-child');
                    if (nonCommencesEl) nonCommencesEl.textContent = `Non commencés: ${modulesNonCommences}`;
                }
            }
        }
        
        // Pour la compatibilité avec l'ancien système
        function toggleModuleMenu(menuId) {
            const menu = document.getElementById(menuId);
            if (menu) {
                if (menu.classList.contains('hidden')) {
                    document.querySelectorAll('[data-module-menu]').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    menu.classList.remove('hidden');
                    activeModuleMenu = menu;
                } else {
                    menu.classList.add('hidden');
                    activeModuleMenu = null;
                }
            }
            if (event) event.stopPropagation();
        }
        
        // Initialisation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            // Injecter les styles
            injectModuleMenuStyles();
            
            // Initialiser les menus
            initModuleMenus();
            
            // Ouvrir les accordions de flightcase par défaut
            document.querySelectorAll('[x-data="{ open: false }"]').forEach(accordion => {
                if (accordion.hasAttribute('x-data') && 
                    accordion.closest('.mb-6') && 
                    accordion.querySelector('.flex.items-center svg')) {
                    accordion.setAttribute('x-data', '{ open: true }');
                }
            });
            
            // Observer les changements dans le DOM pour les modules ajoutés dynamiquement
            const observer = new MutationObserver(function(mutations) {
                let needsUpdate = false;
                
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        for (let i = 0; i < mutation.addedNodes.length; i++) {
                            const node = mutation.addedNodes[i];
                            if (node.nodeType === 1) {  // ELEMENT_NODE
                                if (node.classList && 
                                    (node.classList.contains('aspect-square') || 
                                     node.querySelector && node.querySelector('.aspect-square'))) {
                                    needsUpdate = true;
                                    break;
                                }
                            }
                        }
                    }
                });
                
                if (needsUpdate) {
                    setTimeout(initModuleMenus, 100);
                }
            });
            
            observer.observe(document.body, { childList: true, subtree: true });
            
            // Compatibilité avec l'ancien système
            document.querySelectorAll('.module-trigger').forEach(trigger => {
                trigger.addEventListener('mouseenter', function(e) {
                    const menuId = this.getAttribute('data-module-id');
                    if (menuId) moduleMenuPosition('module-menu-' + menuId, this);
                });
            });
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
    
    <!-- Styles globaux pour les menus des modules -->
    <style>
    /* Style pour le menu déporté des modules */
    .module-menu-floating {
        position: absolute;
        left: calc(100% + 10px);
        top: -5px;
        z-index: 100;
        min-width: 130px;
        border-radius: 6px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    }
    
    /* Flèche du menu */
    .module-menu-floating::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 10px;
        width: 0;
        height: 0;
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
        border-right: 6px solid #1f2937;
    }
    </style>
    
    @stack('meta')
</head>
<body class="font-sans antialiased h-full bg-app-bg text-text-primary">
    <!-- Overlay de fond pour le menu mobile -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden md:hidden transition-opacity duration-300"></div>
    
    <div class="min-h-screen">
        @include('layouts.navigation')
        
        <!-- Contenu principal avec marge pour accommoder la sidebar en desktop et pas en mobile -->
        <div class="min-h-screen md:ml-64 transition-all duration-300">
            <!-- Header avec bouton hamburger en version mobile -->
            <header class="bg-sidebar border-b border-border-light shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 pl-16 md:pl-4 sm:px-6 lg:px-8">
                    @if (isset($header))
                        {{ $header }}
                    @else
                        <h1 class="text-2xl font-semibold text-white">
                            @yield('header', 'Dashboard')
                        </h1>
                    @endif
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-4">
                <!-- Flash Messages -->
                @if (session('success'))
                <div id="successMessage" class="mb-6 alert-success">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if (session('error'))
                <div id="errorMessage" class="mb-6 alert-error">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
                @endif
                
                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
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
</html>