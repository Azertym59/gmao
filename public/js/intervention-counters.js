/**
 * Intervention Counters - Module pour la gestion des compteurs d'interventions
 * 
 * Ce script gère:
 * - Les compteurs de LEDs, ICs, masques et Fake PCBs
 * - La synchronisation avec les champs cachés
 * - Les raccourcis clavier
 * - L'affichage des notifications
 */

document.addEventListener('DOMContentLoaded', () => {
    initializeCounters();
    setupKeyboardShortcuts();
});

/**
 * Initialise tous les compteurs sur la page
 */
function initializeCounters() {
    // Diagnostic
    setupCounter('leds-counter', 'diagnostic_nb_leds_hs');
    setupCounter('ics-counter', 'diagnostic_nb_ic_hs');
    setupCounter('masques-counter', 'diagnostic_nb_masques_hs');
    
    // Réparation
    setupCounter('leds-replaced-counter', 'reparation_nb_leds_remplacees');
    setupCounter('ics-replaced-counter', 'reparation_nb_ic_remplaces');
    setupCounter('masques-replaced-counter', 'reparation_nb_masques_remplaces');
    setupCounter('fake-pcb-counter', 'reparation_fake_pcb_nb');
    
    // Bouton "Remplacer Tout"
    const replaceAllBtn = document.getElementById('replace-all-btn');
    if (replaceAllBtn) {
        replaceAllBtn.addEventListener('click', copyFromDiagnostic);
    }
}

/**
 * Configure un compteur individuel
 * @param {string} counterContainerId - ID du conteneur HTML du compteur
 * @param {string} inputFieldId - ID du champ caché à synchroniser
 */
function setupCounter(counterContainerId, inputFieldId) {
    const container = document.getElementById(counterContainerId);
    if (!container) return;
    
    const inputField = document.getElementById(inputFieldId);
    if (!inputField) return;
    
    const display = container.querySelector('.counter-display');
    const incrementBtn = container.querySelector('.increment-btn');
    const decrementBtn = container.querySelector('.decrement-btn');
    
    // Initialiser avec la valeur du champ caché
    let value = parseInt(inputField.value) || 0;
    display.textContent = value;
    
    // Increment
    incrementBtn.addEventListener('click', () => {
        value++;
        updateCounter();
    });
    
    // Decrement
    decrementBtn.addEventListener('click', () => {
        if (value > 0) {
            value--;
            updateCounter();
        }
    });
    
    // Mettre à jour l'affichage et le champ caché
    function updateCounter() {
        display.textContent = value;
        inputField.value = value;
        
        // Afficher une notification
        showNotification(`${inputFieldId.replace('_', ' ')} mis à jour: ${value}`);
        
        // Enregistrer automatiquement (si nécessaire dans le futur)
        // autoSave();
    }
}

/**
 * Configure les raccourcis clavier pour tous les compteurs
 */
function setupKeyboardShortcuts() {
    document.addEventListener('keydown', (event) => {
        // Ignorer les événements clavier si l'on est dans un champ texte
        const target = event.target;
        if (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA') {
            return;
        }
        
        // Increment shortcuts
        if (event.key === '1') {
            triggerClick('leds-counter .increment-btn');
        } else if (event.key === '2') {
            triggerClick('ics-counter .increment-btn');
        } else if (event.key === '3') {
            triggerClick('masques-counter .increment-btn');
        } else if (event.key === '4') {
            triggerClick('fake-pcb-counter .increment-btn');
        }
        
        // Decrement shortcuts (Alt + Number)
        if (event.altKey) {
            if (event.key === '1') {
                triggerClick('leds-counter .decrement-btn');
            } else if (event.key === '2') {
                triggerClick('ics-counter .decrement-btn');
            } else if (event.key === '3') {
                triggerClick('masques-counter .decrement-btn');
            } else if (event.key === '4') {
                triggerClick('fake-pcb-counter .decrement-btn');
            }
        }
        
        // Submit form on Enter
        if (event.key === 'Enter') {
            const form = document.querySelector('form');
            if (form) form.submit();
        }
    });
}

/**
 * Déclenche un clic sur un élément
 * @param {string} selector - Sélecteur CSS pour trouver l'élément
 */
function triggerClick(selector) {
    const btn = document.querySelector(selector);
    if (btn) btn.click();
}

/**
 * Copie les valeurs du diagnostic aux champs de réparation
 */
function copyFromDiagnostic() {
    // Récupérer les valeurs du diagnostic depuis les éléments HTML d'info
    const ledsHs = document.querySelector('#diagnostic-summary .leds-hs-value');
    const icsHs = document.querySelector('#diagnostic-summary .ics-hs-value');
    const masquesHs = document.querySelector('#diagnostic-summary .masques-hs-value');
    
    // Récupérer les champs de saisie pour la réparation
    const ledsReparation = document.getElementById('reparation_nb_leds_remplacees');
    const icsReparation = document.getElementById('reparation_nb_ic_remplaces');
    const masquesReparation = document.getElementById('reparation_nb_masques_remplaces');
    
    // Copier les valeurs
    if (ledsHs && ledsReparation) {
        ledsReparation.value = ledsHs.textContent;
        document.querySelector('#leds-replaced-counter .counter-display').textContent = ledsHs.textContent;
    }
    
    if (icsHs && icsReparation) {
        icsReparation.value = icsHs.textContent;
        document.querySelector('#ics-replaced-counter .counter-display').textContent = icsHs.textContent;
    }
    
    if (masquesHs && masquesReparation) {
        masquesReparation.value = masquesHs.textContent;
        document.querySelector('#masques-replaced-counter .counter-display').textContent = masquesHs.textContent;
    }
    
    // Vérifier si fake PCB est nécessaire et cocher la case
    const fakePcbNeeded = document.querySelector('#diagnostic-summary .fake-pcb-needed');
    if (fakePcbNeeded && fakePcbNeeded.textContent === 'Oui') {
        const fakePcbCheckbox = document.getElementById('reparation_fake_pcb_pose');
        if (fakePcbCheckbox) fakePcbCheckbox.checked = true;
    }
    
    showNotification('Toutes les valeurs du diagnostic ont été copiées');
}

/**
 * Affiche une notification temporaire
 * @param {string} message - Message à afficher
 */
function showNotification(message) {
    // Chercher un conteneur de notifications existant ou en créer un
    let notifContainer = document.getElementById('notification-container');
    if (!notifContainer) {
        notifContainer = document.createElement('div');
        notifContainer.id = 'notification-container';
        notifContainer.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 1000;';
        document.body.appendChild(notifContainer);
    }
    
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = 'notification bg-indigo-600 text-white py-2 px-4 rounded shadow-lg mb-2 flex items-center';
    notification.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        ${message}
    `;
    
    // Ajouter au conteneur
    notifContainer.appendChild(notification);
    
    // Faire disparaître après 3 secondes
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.5s';
        
        // Supprimer du DOM après la transition
        setTimeout(() => {
            notifContainer.removeChild(notification);
        }, 500);
    }, 3000);
}