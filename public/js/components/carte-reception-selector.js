/**
 * Gestionnaire de sélection des cartes de réception
 * Version: 1.0.0 - Date: 2025-03-15
 * 
 * Permet de gérer une liste déroulante évolutive des cartes de réception
 * en fonction du système électronique sélectionné
 */

class CarteReceptionSelector {
    constructor() {
        // Éléments DOM
        this.electroniqueSelectorId = 'electronique';
        this.carteSelectorId = 'carte_reception';
        this.customToggleId = 'toggle_custom_carte';
        this.customInputId = 'custom_carte_reception';
        this.customContainerId = 'custom_carte_container';
        
        // Stockage des cartes connues par système
        this.cartesBySystem = {
            'nova': [
                { value: 'novastar_taurus', label: 'Novastar Taurus' },
                { value: 'nova_mctrl300', label: 'Novastar MCTRL300' },
                { value: 'nova_mctrl660', label: 'Novastar MCTRL660' },
                { value: 'nova_mctrl4k', label: 'Novastar MCTRL4K' },
                { value: 'nova_a5s_plus', label: 'Novastar A5s Plus' },
                { value: 'nova_a8s_plus', label: 'Novastar A8s Plus' }
            ],
            'linsn': [
                { value: 'linsn_ts802', label: 'Linsn TS802' },
                { value: 'linsn_ts852', label: 'Linsn TS852' },
                { value: 'linsn_ts902', label: 'Linsn TS902' },
                { value: 'linsn_rv908', label: 'Linsn RV908' },
                { value: 'linsn_rv908m', label: 'Linsn RV908M' }
            ],
            'colorlight': [
                { value: 'colorlight_z6', label: 'Colorlight Z6' },
                { value: 'colorlight_s6', label: 'Colorlight S6' },
                { value: 'colorlight_m9', label: 'Colorlight M9' },
                { value: 'colorlight_x8', label: 'Colorlight X8' }
            ],
            'dbstar': [
                { value: 'dbstar_hvt11in', label: 'DBstar HVT11IN' },
                { value: 'dbstar_mrf4in', label: 'DBstar MRF4IN' }
            ],
            'barco': [
                { value: 'barco_e2', label: 'Barco E2' },
                { value: 'barco_s3', label: 'Barco S3' },
                { value: 'barco_eventmaster', label: 'Barco EventMaster' }
            ],
            'brompton': [
                { value: 'brompton_tessera_s4', label: 'Brompton Tessera S4' },
                { value: 'brompton_tessera_m2', label: 'Brompton Tessera M2' },
                { value: 'brompton_tessera_sb40', label: 'Brompton Tessera SB40' },
                { value: 'brompton_tessera_r2', label: 'Brompton Tessera R2' }
            ],
            'autre': []
        };
        
        // Stockage des données
        this.customValue = '';
        
        // Initialiser
        this.init();
    }
    
    init() {
        // Récupérer les éléments DOM
        this.electroniqueSelector = document.getElementById(this.electroniqueSelectorId);
        this.carteSelector = document.getElementById(this.carteSelectorId);
        this.customToggle = document.getElementById(this.customToggleId);
        this.customInput = document.getElementById(this.customInputId);
        this.customContainer = document.getElementById(this.customContainerId);
        
        // Vérifier que tous les éléments sont présents
        if (!this.electroniqueSelector || !this.carteSelector) {
            console.error('Éléments de sélection de cartes non trouvés');
            return;
        }
        
        // Ajouter les écouteurs d'événements
        this.bindEvents();
        
        // Mettre à jour la liste initiale
        this.updateCartesList();
    }
    
    bindEvents() {
        // Changement du système électronique
        this.electroniqueSelector.addEventListener('change', () => {
            this.updateCartesList();
        });
        
        // Toggle pour la saisie manuelle
        if (this.customToggle) {
            this.customToggle.addEventListener('change', () => {
                this.toggleCustomInput();
            });
        }
        
        // Saisie manuelle
        if (this.customInput) {
            this.customInput.addEventListener('input', () => {
                this.updateCustomValue();
            });
            
            // Pour s'assurer que la valeur est mise à jour avant la soumission
            this.customInput.addEventListener('change', () => {
                this.updateCustomValue(true);
            });
        }
    }
    
    updateCartesList() {
        // Récupérer le système électronique sélectionné
        const system = this.electroniqueSelector.value;
        
        // Vider la liste actuelle
        this.carteSelector.innerHTML = '<option value="">Sélectionnez une carte</option>';
        
        // Ajouter les options correspondantes
        if (this.cartesBySystem[system]) {
            this.cartesBySystem[system].forEach(carte => {
                const option = document.createElement('option');
                option.value = carte.value;
                option.textContent = carte.label;
                this.carteSelector.appendChild(option);
            });
        }
        
        // Ajouter une option "Autre" pour permettre la saisie manuelle
        const otherOption = document.createElement('option');
        otherOption.value = 'autre';
        otherOption.textContent = 'Autre (saisir manuellement)';
        this.carteSelector.appendChild(otherOption);
        
        // Écouter les changements sur le sélecteur de carte
        this.carteSelector.addEventListener('change', () => {
            if (this.carteSelector.value === 'autre') {
                if (this.customToggle) {
                    this.customToggle.checked = true;
                }
                this.toggleCustomInput();
            } else {
                if (this.customToggle) {
                    this.customToggle.checked = false;
                }
                this.toggleCustomInput();
            }
        });
    }
    
    toggleCustomInput() {
        if (!this.customContainer || !this.customToggle) return;
        
        const visible = this.customToggle.checked;
        this.customContainer.style.display = visible ? 'block' : 'none';
        
        // Si on masque la saisie personnalisée, réappliquer la valeur du sélecteur
        if (!visible) {
            this.carteSelector.disabled = false;
        } else {
            // Si c'est visible, on désactive temporairement le sélecteur principal
            this.carteSelector.disabled = true;
            
            // Si une valeur est déjà dans le champ custom, l'utiliser
            this.updateCustomValue();
            
            // Focus sur le champ de saisie
            if (this.customInput) {
                this.customInput.focus();
            }
        }
    }
    
    updateCustomValue(applyNow = false) {
        if (!this.customInput) return;
        
        this.customValue = this.customInput.value;
        
        // Si demandé, appliquer immédiatement la valeur au champ caché
        if (applyNow && this.customValue) {
            // Ici, on pourrait avoir un champ caché pour stocker la vraie valeur
            // Pour cette implémentation, on va simplement mettre à jour l'option "autre"
            let otherOption = Array.from(this.carteSelector.options).find(opt => opt.value === 'autre');
            if (otherOption) {
                otherOption.value = 'autre:' + this.customValue;
            }
            
            // Mettre à jour la valeur du sélecteur
            this.carteSelector.value = 'autre:' + this.customValue;
        }
    }
    
    // Méthode pour ajouter une nouvelle carte au système
    addCard(system, value, label) {
        if (!this.cartesBySystem[system]) {
            this.cartesBySystem[system] = [];
        }
        
        // Vérifier que la carte n'existe pas déjà
        const exists = this.cartesBySystem[system].some(card => card.value === value);
        if (!exists) {
            this.cartesBySystem[system].push({ value, label });
            console.info(`Carte ajoutée: ${label} pour le système ${system}`);
            
            // Si le système actuel est sélectionné, mettre à jour la liste
            if (this.electroniqueSelector.value === system) {
                this.updateCartesList();
            }
            
            return true;
        }
        
        return false;
    }
}

// Initialisation du sélecteur une fois que le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si les éléments nécessaires sont présents
    if (document.getElementById('electronique') && document.getElementById('carte_reception')) {
        window.carteReceptionSelector = new CarteReceptionSelector();
    } else {
        console.warn('Éléments requis non trouvés pour le sélecteur de cartes de réception');
    }
});