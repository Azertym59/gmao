/**
 * Module Status Tracker
 * 
 * Ce script gère la vérification en temps réel des statuts des modules
 * en utilisant l'API sans avoir besoin de recharger la page entière.
 */

class ModuleStatusTracker {
    constructor(options = {}) {
        // Options par défaut
        this.options = {
            checkInterval: options.checkInterval || 10000, // 10 secondes par défaut
            moduleSelector: options.moduleSelector || '.module-grid-item, [data-module-id]',
            moduleIdAttribute: options.moduleIdAttribute || 'data-module-id',
            showIndicator: options.showIndicator !== undefined ? options.showIndicator : true,
            debug: options.debug || false
        };

        // État interne
        this.moduleElements = [];
        this.moduleIds = [];
        this.intervalId = null;
        this.isRunning = false;
        this.indicatorElement = null;
        
        // Bind des méthodes
        this.checkModuleStatus = this.checkModuleStatus.bind(this);
        this.start = this.start.bind(this);
        this.stop = this.stop.bind(this);
        this.log = this.log.bind(this);
        this.updateModuleElementStatus = this.updateModuleElementStatus.bind(this);
        this.showLoadingIndicator = this.showLoadingIndicator.bind(this);
        this.hideLoadingIndicator = this.hideLoadingIndicator.bind(this);
        
        // Créer l'indicateur de chargement si l'option est activée
        if (this.options.showIndicator) {
            this.createLoadingIndicator();
        }
    }
    
    /**
     * Crée l'indicateur de chargement et l'ajoute au DOM
     */
    createLoadingIndicator() {
        // Créer l'élément s'il n'existe pas déjà
        if (!this.indicatorElement) {
            this.indicatorElement = document.createElement('div');
            this.indicatorElement.className = 'status-update-indicator hidden';
            this.indicatorElement.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 6v6l4 2"></path>
                </svg>
                <span>Mise à jour des statuts...</span>
            `;
            document.body.appendChild(this.indicatorElement);
        }
    }

    /**
     * Initialise le traqueur et démarre la surveillance
     */
    init() {
        // Identifier tous les modules sur la page
        this.findModuleElements();
        
        // Si des modules sont trouvés, démarrer la surveillance
        if (this.moduleIds.length > 0) {
            this.start();
            this.log(`Surveillance démarrée pour ${this.moduleIds.length} modules`);
        } else {
            this.log('Aucun module trouvé sur cette page');
        }
    }

    /**
     * Trouve tous les éléments de module sur la page et extrait leurs IDs
     */
    findModuleElements() {
        // Réinitialiser les tableaux pour éviter les doublons lors des réexécutions
        this.moduleElements = [];
        this.moduleIds = [];
        
        // Sélectionner tous les éléments correspondant au sélecteur
        const elements = document.querySelectorAll(this.options.moduleSelector);
        
        // Collecter les éléments et leurs IDs
        elements.forEach(element => {
            let moduleId;
            
            // Vérifier si l'élément a l'attribut moduleIdAttribute
            if (element.hasAttribute(this.options.moduleIdAttribute)) {
                moduleId = element.getAttribute(this.options.moduleIdAttribute);
            } 
            // Vérifier si un parent proche a l'attribut moduleIdAttribute
            else {
                const parentWithId = element.closest(`[${this.options.moduleIdAttribute}]`);
                if (parentWithId) {
                    moduleId = parentWithId.getAttribute(this.options.moduleIdAttribute);
                }
            }
            
            // Ajouter l'élément et son ID s'il est valide et unique
            if (moduleId && !this.moduleIds.includes(moduleId)) {
                this.moduleElements.push(element);
                this.moduleIds.push(moduleId);
            }
        });
        
        // Log le nombre de modules trouvés
        this.log(`Trouvé ${this.moduleIds.length} modules à surveiller`);
    }

    /**
     * Démarre la vérification périodique des statuts de module
     */
    start() {
        if (this.isRunning) return;
        
        // Vérifier immédiatement au démarrage
        this.checkModuleStatus();
        
        // Puis configurer l'intervalle pour les vérifications régulières
        this.intervalId = setInterval(this.checkModuleStatus, this.options.checkInterval);
        this.isRunning = true;
    }

    /**
     * Arrête la vérification périodique
     */
    stop() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
            this.isRunning = false;
        }
    }

    /**
     * Affiche l'indicateur de chargement
     */
    showLoadingIndicator() {
        if (this.indicatorElement) {
            this.indicatorElement.classList.remove('hidden');
        }
    }
    
    /**
     * Cache l'indicateur de chargement
     */
    hideLoadingIndicator() {
        if (this.indicatorElement) {
            this.indicatorElement.classList.add('hidden');
        }
    }
    
    /**
     * Effectue une requête à l'API pour vérifier le statut des modules
     */
    checkModuleStatus() {
        if (this.moduleIds.length === 0) return;
        
        this.log('Vérification du statut des modules...');
        this.showLoadingIndicator();
        
        // Ajouter une classe à tous les modules pour indiquer la mise à jour
        this.moduleElements.forEach(element => {
            element.classList.add('module-status-updating');
        });
        
        // Créer l'URL avec les IDs des modules et un brise-cache pour forcer la réponse fraîche
        const apiUrl = `/api/check-modules-status?ids=${this.moduleIds.join(',')}&cache_buster=${Date.now()}`;
        
        // Effectuer la requête à l'API
        fetch(apiUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                this.log('Modules mis à jour:', data.modules);
                this.updateModulesStatuses(data.modules);
            } else {
                console.error('Erreur API:', data.message);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification des statuts:', error);
        })
        .finally(() => {
            // Cacher l'indicateur et retirer la classe d'animation
            this.hideLoadingIndicator();
            this.moduleElements.forEach(element => {
                element.classList.remove('module-status-updating');
            });
        });
    }

    /**
     * Met à jour le statut visuel de tous les modules
     */
    updateModulesStatuses(moduleData) {
        // Pour chaque élément de module
        this.moduleElements.forEach(element => {
            const moduleId = element.getAttribute(this.options.moduleIdAttribute);
            if (moduleId && moduleData[moduleId]) {
                this.updateModuleElementStatus(element, moduleData[moduleId]);
            }
        });
    }

    /**
     * Met à jour l'état visuel d'un élément de module spécifique
     */
    updateModuleElementStatus(element, moduleData) {
        // Trouver l'élément principal du module (qui contient les classes de couleur)
        const mainElement = element.classList.contains('module-grid-item') ? 
            element : 
            element.querySelector('.module-grid-item') || element;
        
        // Nous avons déplacé cette logique dans le bloc suivant pour qu'elle soit toujours appliquée
        
        // Obtenir et stocker l'état actuel du module venant du serveur
        const serverState = moduleData.state || 'non_commence';
        
        // Toujours sauvegarder l'état actuel de la base de données 
        // Ce sera l'état auquel on reviendra quand le module n'est plus occupé
        if (serverState && serverState !== 'en_cours') {
            mainElement.setAttribute('data-saved-state', serverState);
        }
        
        // Ajouter la classe d'occupation si le module est occupé
        if (moduleData.is_occupied) {
            // Appliquer l'état visuel "occupé"
            mainElement.classList.add('module-busy');
            
            // Conserver l'état actuel coloré en orange pour les modules occupés
            mainElement.classList.remove('bg-accent-green', 'bg-accent-yellow', 'bg-accent-red', 'bg-gray-600');
            mainElement.classList.add('bg-orange-500');
            
            // Ce log aide à diagnostiquer les problèmes de mise à jour
            this.log(`Module #${moduleData.id} marqué comme occupé, état sous-jacent: ${mainElement.getAttribute('data-saved-state') || 'non défini'}`);
        } else {
            // Le module n'est pas occupé, retirer les classes d'occupation
            mainElement.classList.remove('module-busy', 'bg-orange-500');
            
            // Retirer toutes les classes d'état pour éviter les conflits
            mainElement.classList.remove('bg-accent-green', 'bg-accent-yellow', 'bg-accent-defaillant', 'bg-accent-hs', 'bg-accent-red', 'bg-gray-600');
            
            // Appliquer la classe correspondant à l'état serveur
            switch(serverState) {
                case 'termine':
                    mainElement.classList.add('bg-accent-green');
                    break;
                case 'stand_by':
                case 'en_cours':
                    mainElement.classList.add('bg-accent-yellow');
                    break;
                case 'defaillant':
                    mainElement.classList.add('bg-accent-defaillant'); // Turquoise pour défaillant
                    break;
                case 'hs':
                    mainElement.classList.add('bg-accent-hs'); // Rouge pour HS
                    break;
                case 'non_commence':
                default:
                    mainElement.classList.add('bg-gray-600');
            }
            
            // Logging pour le débogage des couleurs
            this.log(`État appliqué: ${serverState} → ${mainElement.className.split(' ').filter(c => c.startsWith('bg-')).join(', ')}`);
            
            this.log(`Module #${moduleData.id} restauré à l'état: ${serverState}`);
        }
        
        // Mettre à jour le titre (tooltip) pour tous les éléments possibles
        const allModuleElements = [element];
        const parentModule = element.closest('.module-container');
        if (parentModule) {
            allModuleElements.push(parentModule);
            
            // Rechercher les enfants potentiels (comme les liens)
            const childElements = parentModule.querySelectorAll('a, div.module-trigger');
            childElements.forEach(child => allModuleElements.push(child));
        }
        
        // Mettre à jour les titres de tous les éléments
        let tooltipText = '';
        if (moduleData.is_occupied) {
            tooltipText = `⚠️ MODULE OCCUPÉ${moduleData.technician_name ? ' - Par: ' + moduleData.technician_name : ''}`;
        } else {
            const stateDisplay = moduleData.state.replace('_', ' ');
            tooltipText = `Module #${moduleData.id} - ${stateDisplay.charAt(0).toUpperCase() + stateDisplay.slice(1)}`;
        }
        
        allModuleElements.forEach(el => {
            if (el && el.hasAttribute) {
                el.setAttribute('title', tooltipText);
            }
        });
        
        // Mettre à jour les liens de menu dans les menus flottants associés
        const menuId = `module-menu-${moduleData.id}`;
        const menuElement = document.getElementById(menuId);
        if (menuElement) {
            // Si le module est occupé, désactiver certains boutons
            const actionButtons = menuElement.querySelectorAll('button, a');
            actionButtons.forEach(button => {
                // Pour les boutons "OK" et "Intervention", les désactiver si occupé
                if ((button.textContent.trim() === 'OK' || button.textContent.trim() === 'Intervention') && moduleData.is_occupied) {
                    button.style.opacity = '0.5';
                    button.style.pointerEvents = 'none';
                } else if ((button.textContent.trim() === 'OK' || button.textContent.trim() === 'Intervention') && !moduleData.is_occupied) {
                    button.style.opacity = '1';
                    button.style.pointerEvents = 'auto';
                }
                
                // Pour le bouton "Voir Intervention", l'activer si occupé
                if (button.textContent.trim() === 'Voir Intervention') {
                    if (moduleData.is_occupied && moduleData.active_intervention_id) {
                        button.style.display = '';
                        button.href = `/interventions/${moduleData.active_intervention_id}/diagnostic`;
                    } else {
                        button.style.display = 'none';
                    }
                }
                
                // Pour le bouton "Déverrouiller", l'activer si occupé
                if (button.textContent.trim() === 'Déverrouiller') {
                    button.style.display = moduleData.is_occupied ? '' : 'none';
                }
            });
        }
        
        // Loguer les changements d'état si le mode debug est activé
        this.log(`Module #${moduleData.id} mis à jour: état=${moduleData.state}, occupé=${moduleData.is_occupied}`);
    }

    /**
     * Fonction de journalisation conditionnelle
     */
    log(...args) {
        if (this.options.debug) {
            console.log('[ModuleStatusTracker]', ...args);
        }
    }
}

// Initialiser le traqueur lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si la page contient des modules à surveiller
    if (document.querySelector('[data-module-id], .module-grid-item, .module-trigger')) {
        const tracker = new ModuleStatusTracker({
            checkInterval: 3000, // Vérifier toutes les 3 secondes pour une réactivité maximale
            debug: true, // Activer les logs pour le débogage des problèmes
            moduleSelector: '.module-grid-item, [data-module-id], .module-trigger, .module-container'
        });
        
        tracker.init();
        
        // Rendre l'instance disponible globalement pour permettre des vérifications manuelles
        window.moduleStatusTracker = tracker;
        
        // Force une vérification initiale après un court délai pour s'assurer que les données sont à jour
        setTimeout(() => {
            tracker.findModuleElements();
            tracker.checkModuleStatus();
        }, 1000);
        
        // Force une autre vérification après un délai plus long pour attraper les éléments qui pourraient
        // avoir été ajoutés dynamiquement par des frameworks comme Alpine.js
        setTimeout(() => {
            tracker.findModuleElements();
            tracker.checkModuleStatus();
        }, 3000);
        
        // Ajouter un bouton de rafraîchissement manuel (pour le débogage)
        const refreshButton = document.createElement('button');
        refreshButton.textContent = '🔄';
        refreshButton.title = 'Rafraîchir le statut des modules';
        refreshButton.style.position = 'fixed';
        refreshButton.style.bottom = '10px';
        refreshButton.style.left = '10px';
        refreshButton.style.zIndex = '9999';
        refreshButton.style.padding = '5px 10px';
        refreshButton.style.borderRadius = '5px';
        refreshButton.style.backgroundColor = 'rgba(0, 0, 0, 0.6)';
        refreshButton.style.color = 'white';
        refreshButton.style.cursor = 'pointer';
        refreshButton.addEventListener('click', () => {
            tracker.findModuleElements();
            tracker.checkModuleStatus();
        });
        document.body.appendChild(refreshButton);
    }
});