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
            checkInterval: options.checkInterval || 15000, // 15 secondes par défaut
            moduleSelector: options.moduleSelector || '.module-grid-item',
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
        // Sélectionner tous les éléments correspondant au sélecteur
        const modulesWithAttributes = document.querySelectorAll(`[${this.options.moduleIdAttribute}]`);
        
        // Collecter les éléments et leurs IDs
        modulesWithAttributes.forEach(element => {
            const moduleId = element.getAttribute(this.options.moduleIdAttribute);
            if (moduleId) {
                this.moduleElements.push(element);
                this.moduleIds.push(moduleId);
            }
        });
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
        
        // Créer l'URL avec les IDs des modules
        const apiUrl = `/api/check-modules-status?ids=${this.moduleIds.join(',')}`;
        
        // Effectuer la requête à l'API
        fetch(apiUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
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
        // Mettre à jour la classe d'occupation
        if (moduleData.is_occupied) {
            element.classList.add('module-busy');
        } else {
            element.classList.remove('module-busy');
        }
        
        // Mettre à jour l'état du module (couleur de fond)
        element.classList.remove('bg-accent-green', 'bg-accent-yellow', 'bg-accent-red', 'bg-gray-600');
        
        switch(moduleData.state) {
            case 'termine':
                element.classList.add('bg-accent-green');
                break;
            case 'en_cours':
                element.classList.add('bg-accent-yellow');
                break;
            case 'defaillant':
                element.classList.add('bg-accent-red');
                break;
            default:
                element.classList.add('bg-gray-600');
        }
        
        // Mettre à jour le titre (tooltip)
        let tooltipText = '';
        if (moduleData.is_occupied) {
            tooltipText = `⚠️ MODULE OCCUPÉ${moduleData.technician_name ? ' - Par: ' + moduleData.technician_name : ''}`;
        } else {
            tooltipText = `Module #${moduleData.id} - ${moduleData.state.charAt(0).toUpperCase() + moduleData.state.slice(1)}`;
        }
        element.setAttribute('title', tooltipText);
        
        // Mettre à jour le contenu de l'overlay si nécessaire
        const overlay = element.querySelector('.module-overlay-content');
        if (overlay) {
            // Cette partie est plus spécifique et peut nécessiter une personnalisation
            // selon la structure de l'overlay dans le HTML
            // Pour l'instant, nous nous contentons de cacher/afficher les boutons appropriés
            
            const buttons = overlay.querySelectorAll('.module-btn');
            buttons.forEach(btn => {
                // Si c'est un bouton "OK" ou "Interv." et que le module est occupé, le cacher
                if ((btn.textContent.includes('OK') || btn.textContent.includes('Interv.')) && moduleData.is_occupied) {
                    btn.closest('form, a').style.display = 'none';
                } else if ((btn.textContent.includes('OK') || btn.textContent.includes('Interv.')) && !moduleData.is_occupied) {
                    btn.closest('form, a').style.display = '';
                }
                
                // Si c'est un bouton "Voir" et que le module n'est pas occupé, le cacher
                if (btn.textContent.includes('Voir') && !moduleData.is_occupied) {
                    btn.closest('a').style.display = 'none';
                } else if (btn.textContent.includes('Voir') && moduleData.is_occupied) {
                    btn.closest('a').style.display = '';
                    // Mettre à jour le lien vers l'intervention active
                    if (moduleData.active_intervention_id) {
                        btn.closest('a').href = `/interventions/${moduleData.active_intervention_id}/diagnostic`;
                    }
                }
            });
            
            // Mettre à jour le message d'occupation
            const techNameElement = overlay.querySelector('.text-orange-400');
            if (techNameElement) {
                if (moduleData.is_occupied) {
                    techNameElement.textContent = moduleData.technician_name || 'OCCUPÉ';
                    techNameElement.style.display = '';
                } else {
                    techNameElement.style.display = 'none';
                }
            }
        }
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
    if (document.querySelector('[data-module-id]')) {
        const tracker = new ModuleStatusTracker({
            checkInterval: 10000, // Vérifier toutes les 10 secondes
            debug: false // Activer les logs pour le débogage si nécessaire
        });
        
        tracker.init();
        
        // Rendre l'instance disponible globalement si nécessaire
        window.moduleStatusTracker = tracker;
    }
});