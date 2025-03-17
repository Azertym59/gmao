/**
 * Composant pour afficher les images des fiches LED
 * Ce script permet de charger dynamiquement les images des fiches LED via l'API
 * et gère l'affichage/masquage des formulaires de création de LED
 */
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les éléments du DOM
    const ledExistantSelect = document.getElementById('led_existant_select');
    const ledImage = document.getElementById('led_image');
    const noLedSelected = document.getElementById('no_led_selected');
    const ledNouveau = document.getElementById('led_nouveau');
    const ledExistant = document.getElementById('led_existant');
    
    // Conteneur du formulaire de sélection de LED existante
    const ledExistantContainer = document.getElementById('led_existant_container');
    
    // Récupérer les éléments à masquer (uniquement ceux pour créer une nouvelle LED)
    const ledNouveauContainer = document.getElementById('led_nouveau_container');
    const padsContainer = document.getElementById('pads_container');
    const ledRotationContainer = document.getElementById('led_rotation_container');
    
    if (!ledExistantSelect || !ledImage || !noLedSelected) {
        console.warn('LED Datasheet Viewer: Éléments HTML nécessaires non trouvés.');
        return;
    }
    
    // Fonction pour masquer les éléments de création de LED
    function hideNewLedElements() {
        // Ne masquer que les éléments de création de LED, pas ceux de sélection
        if (ledNouveauContainer) ledNouveauContainer.classList.add('hidden');
        if (padsContainer) padsContainer.classList.add('hidden');
        if (ledRotationContainer) ledRotationContainer.classList.add('hidden');
        
        // S'assurer que le conteneur de sélection de LED existante est visible
        if (ledExistantContainer) ledExistantContainer.classList.remove('hidden');
    }
    
    // Fonction pour afficher les éléments de création de LED
    function showNewLedElements() {
        if (ledNouveauContainer) ledNouveauContainer.classList.remove('hidden');
        if (padsContainer) padsContainer.classList.remove('hidden');
        if (ledRotationContainer) ledRotationContainer.classList.remove('hidden');
    }
    
    // Fonction pour charger l'image du datasheet
    function loadLedDatasheetImage(id) {
        if (!id) {
            ledImage.classList.add('hidden');
            noLedSelected.innerHTML = 'Veuillez sélectionner une LED pour voir son aperçu.';
            noLedSelected.classList.remove('hidden');
            return;
        }
        
        // Afficher un message de chargement
        ledImage.classList.add('hidden');
        noLedSelected.innerHTML = 'Chargement de l\'image...';
        noLedSelected.classList.remove('hidden');
        
        // Récupérer les détails du datasheet depuis l'API
        fetch(`/api/led-datasheet/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau lors de la récupération de l\'image');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.image_data) {
                    // Afficher l'image
                    ledImage.src = data.image_data;
                    ledImage.classList.remove('hidden');
                    noLedSelected.classList.add('hidden');
                } else {
                    // Pas d'image disponible
                    ledImage.classList.add('hidden');
                    noLedSelected.innerHTML = 'Aucune image disponible pour cette LED.';
                    noLedSelected.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement de l\'image:', error);
                ledImage.classList.add('hidden');
                noLedSelected.innerHTML = 'Erreur lors du chargement de l\'image.';
                noLedSelected.classList.remove('hidden');
            });
    }
    
    // Écouter les changements sur le select des LED
    ledExistantSelect.addEventListener('change', function() {
        const selectedLedId = this.value;
        loadLedDatasheetImage(selectedLedId);
    });
    
    // Écouter les changements de choix entre nouvelle LED et LED existante
    if (ledNouveau && ledExistant) {
        ledNouveau.addEventListener('change', function() {
            if (this.checked) {
                showNewLedElements();
                // Masquer le conteneur de sélection de LED existante
                if (ledExistantContainer) ledExistantContainer.classList.add('hidden');
            }
        });
        
        ledExistant.addEventListener('change', function() {
            if (this.checked) {
                hideNewLedElements();
                // S'assurer que le conteneur de sélection de LED existante est visible
                if (ledExistantContainer) ledExistantContainer.classList.remove('hidden');
            }
        });
    }
    
    // Charger l'image initiale si une LED est déjà sélectionnée
    if (ledExistant && ledExistant.checked) {
        hideNewLedElements();
        if (ledExistantSelect.value) {
            loadLedDatasheetImage(ledExistantSelect.value);
        }
    }
    
    // Si on crée une nouvelle LED, masquer le conteneur de sélection
    if (ledNouveau && ledNouveau.checked) {
        if (ledExistantContainer) ledExistantContainer.classList.add('hidden');
    }
    
    console.log('LED Datasheet Viewer: Version simplifiée chargée');
});