/**
 * Système de Heartbeat pour les interventions
 * 
 * Ce script envoie périodiquement un signal de "heartbeat" pour indiquer
 * qu'un utilisateur est activement présent sur la page d'intervention d'un module.
 * Cela permet de libérer les modules lorsqu'aucun utilisateur n'y est réellement actif.
 */

class InterventionHeartbeat {
    constructor(options = {}) {
        // Options par défaut
        this.options = {
            intervalSeconds: options.intervalSeconds || 30, // Envoyer un heartbeat toutes les 30 secondes
            interventionId: options.interventionId || null,
            moduleId: options.moduleId || null,
            csrfToken: options.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '',
            debug: options.debug || false
        };

        // États internes
        this.isActive = false;
        this.intervalId = null;
        this.lastHeartbeatTime = null;

        // Lier les méthodes
        this.sendHeartbeat = this.sendHeartbeat.bind(this);
        this.start = this.start.bind(this);
        this.stop = this.stop.bind(this);
        this.log = this.log.bind(this);
    }

    /**
     * Démarre l'envoi de heartbeats
     */
    start() {
        if (this.isActive) return;

        // Vérifier que les IDs nécessaires sont présents
        if (!this.options.interventionId || !this.options.moduleId) {
            this.log('Impossible de démarrer: interventionId ou moduleId manquant');
            return;
        }

        // Envoyer un premier heartbeat immédiatement
        this.sendHeartbeat();

        // Programmer les heartbeats suivants
        this.intervalId = setInterval(this.sendHeartbeat, this.options.intervalSeconds * 1000);
        this.isActive = true;

        this.log(`Heartbeat démarré pour l'intervention #${this.options.interventionId}, module #${this.options.moduleId}`);
        
        // Configurer les événements pour détecter l'activité et l'inactivité de l'utilisateur
        this.setupVisibilityTracking();
    }

    /**
     * Configure le suivi de la visibilité de la page et l'activité de l'utilisateur
     */
    setupVisibilityTracking() {
        // Arrêter les heartbeats lorsque l'utilisateur quitte la page
        window.addEventListener('beforeunload', () => this.stop());
        
        // Gérer la visibilité de la page (onglet actif/inactif)
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                // L'utilisateur a quitté la page ou changé d'onglet
                this.log('Page cachée, arrêt des heartbeats');
                this.stop();
            } else if (document.visibilityState === 'visible' && !this.isActive) {
                // L'utilisateur est revenu, redémarrer les heartbeats
                this.log('Page visible à nouveau, reprise des heartbeats');
                this.start();
            }
        });
    }

    /**
     * Arrête l'envoi de heartbeats
     */
    stop() {
        if (!this.isActive) return;

        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }

        this.isActive = false;
        this.log('Heartbeat arrêté');
    }

    /**
     * Envoie un signal de heartbeat au serveur
     */
    sendHeartbeat() {
        fetch('/api/intervention-heartbeat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.options.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                intervention_id: this.options.interventionId,
                module_id: this.options.moduleId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                this.lastHeartbeatTime = data.timestamp;
                this.log(`Heartbeat envoyé à ${new Date().toLocaleTimeString()}`);
            } else {
                this.log(`Erreur heartbeat: ${data.message || 'Erreur inconnue'}`);
            }
        })
        .catch(error => {
            console.error('Erreur lors de l\'envoi du heartbeat:', error);
        });
    }

    /**
     * Journalisation conditionnelle pour le débogage
     */
    log(...args) {
        if (this.options.debug) {
            console.log('[InterventionHeartbeat]', ...args);
        }
    }
}

// Initialisation du heartbeat lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si nous sommes sur une page d'intervention
    const interventionIdElement = document.getElementById('intervention_id');
    const moduleIdElement = document.getElementById('module_id');
    
    if (interventionIdElement && moduleIdElement) {
        const interventionId = interventionIdElement.value;
        const moduleId = moduleIdElement.value;
        
        if (interventionId && moduleId) {
            const heartbeat = new InterventionHeartbeat({
                interventionId: interventionId,
                moduleId: moduleId,
                intervalSeconds: 30,
                debug: false  // Activer pour le débogage
            });
            
            heartbeat.start();
            
            // Rendre l'instance disponible globalement si nécessaire
            window.interventionHeartbeat = heartbeat;
        }
    }
});