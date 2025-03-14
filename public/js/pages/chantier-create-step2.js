/**
 * Fichier JavaScript pour la page de création de chantier - Étape 2
 * Gère les formulaires dynamiques pour la configuration des produits
 * Version: 2.1.0 - Date: 2025-03-14
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script externe chargé - Chantier create step 2');
    
    // PARTIE 1: GESTION DU DATASHEET LED
    
    // Récupérer les éléments relatifs au datasheet LED
    const ledTypeSelect = document.getElementById('led_type');
    const ledSizeSelect = document.getElementById('led_size');
    const ledSizePreset = document.getElementById('led_size_preset');
    const ledPadsSelect = document.getElementById('led_pads');
    const ledRotationSelect = document.getElementById('led_rotation');
    const ledNamePreview = document.getElementById('led_name_preview');
    const ledCanvas = document.getElementById('led_preview');
    const generateButton = document.getElementById('generate_datasheet');
    const ledDatasheetName = document.getElementById('led_datasheet_name');
    const ledDatasheetImage = document.getElementById('led_datasheet_image');
    
    // Récupérer les configurations de pads
    const padConfigs = document.querySelectorAll('.pad-configs');
    
    // Vérifier si les éléments sont présents dans le DOM
    if (ledPadsSelect && padConfigs.length > 0) {
        console.log('Éléments datasheet LED trouvés');
        
        // Fonction pour mettre à jour l'affichage des pads
        function updatePadConfigs() {
            const numPads = parseInt(ledPadsSelect.value);
            console.log('Changement du nombre de pads à:', numPads);
            
            // Masquer toutes les configurations
            padConfigs.forEach(config => {
                config.style.display = 'none';
            });
            
            // Afficher la configuration correspondante
            const activeConfig = document.getElementById(`pad-config-${numPads}`);
            if (activeConfig) {
                activeConfig.style.display = 'grid';
                console.log('Configuration de pad affichée:', numPads);
            } else {
                console.error('Configuration de pad non trouvée pour', numPads);
            }
            
            // Mettre à jour la prévisualisation
            if (ledCanvas) {
                updatePreview();
            }
        }
        
        // Fonction pour dessiner la prévisualisation LED
        function updatePreview() {
            if (!ledCanvas) return;
            
            console.log('Mise à jour de la prévisualisation LED');
            
            const ctx = ledCanvas.getContext('2d');
            const width = ledCanvas.width;
            const height = ledCanvas.height;
            const padding = 20;
            const size = width - (padding * 2);
            
            // Effacer le canvas
            ctx.clearRect(0, 0, width, height);
            
            // Dessiner le corps de la LED
            ctx.fillStyle = '#e0e0e0';
            ctx.beginPath();
            
            // Dessiner avec un biseau dans le coin supérieur gauche
            ctx.moveTo(padding + 15, padding); // Point de départ avec biseau
            ctx.lineTo(padding + size, padding); // Haut
            ctx.lineTo(padding + size, padding + size); // Droite
            ctx.lineTo(padding, padding + size); // Bas
            ctx.lineTo(padding, padding + 15); // Gauche
            ctx.lineTo(padding + 15, padding); // Retour au point biseauté
            
            ctx.closePath();
            ctx.fill();
            
            // Ajouter une bordure
            ctx.strokeStyle = '#888';
            ctx.lineWidth = 1;
            ctx.stroke();
            
            // Dessiner les pads selon la configuration
            const numPads = parseInt(ledPadsSelect.value);
            const padSize = 20;
            const padPositions = [];
            
            // Déterminer les positions des pads selon le nombre et le type de LED
            const ledType = ledTypeSelect?.value || 'SMD';
            const isMiniLED = ledType === 'MiniLED';
            
            switch(numPads) {
                case 2:
                    // 2 pads: 1 à gauche, 1 à droite
                    padPositions.push(
                        {x: padding + 20, y: padding + size/2, num: 1, value: document.getElementById('pad_1')?.value || 'R'},
                        {x: padding + size - 20, y: padding + size/2, num: 2, value: document.getElementById('pad_2')?.value || '+'}
                    );
                    break;
                case 4:
                    if (isMiniLED) {
                        // MiniLED: 2 pads par côté (gauche/droite)
                        padPositions.push(
                            {x: padding + 20, y: padding + size/3, num: 1, value: document.getElementById('pad_1_4')?.value || 'R'},
                            {x: padding + 20, y: padding + 2*size/3, num: 2, value: document.getElementById('pad_2_4')?.value || 'G'},
                            {x: padding + size - 20, y: padding + size/3, num: 3, value: document.getElementById('pad_3_4')?.value || 'B'},
                            {x: padding + size - 20, y: padding + 2*size/3, num: 4, value: document.getElementById('pad_4_4')?.value || '+'}
                        );
                    } else {
                        // Standard: 2 pads à gauche, 2 pads à droite
                        padPositions.push(
                            {x: padding + 20, y: padding + size/3, num: 1, value: document.getElementById('pad_1_4')?.value || 'R'},
                            {x: padding + 20, y: padding + 2*size/3, num: 2, value: document.getElementById('pad_2_4')?.value || 'G'},
                            {x: padding + size - 20, y: padding + size/3, num: 3, value: document.getElementById('pad_3_4')?.value || 'B'},
                            {x: padding + size - 20, y: padding + 2*size/3, num: 4, value: document.getElementById('pad_4_4')?.value || '+'}
                        );
                    }
                    break;
                case 6:
                    if (isMiniLED) {
                        // MiniLED: 3 pads par côté (gauche/droite)
                        padPositions.push(
                            {x: padding + 20, y: padding + size/4, num: 1, value: document.getElementById('pad_1_6')?.value || 'R'},
                            {x: padding + 20, y: padding + size/2, num: 2, value: document.getElementById('pad_2_6')?.value || 'G'},
                            {x: padding + 20, y: padding + 3*size/4, num: 3, value: document.getElementById('pad_3_6')?.value || 'B'},
                            {x: padding + size - 20, y: padding + size/4, num: 4, value: document.getElementById('pad_4_6')?.value || 'R'},
                            {x: padding + size - 20, y: padding + size/2, num: 5, value: document.getElementById('pad_5_6')?.value || 'G'},
                            {x: padding + size - 20, y: padding + 3*size/4, num: 6, value: document.getElementById('pad_6_6')?.value || '+'}
                        );
                    } else {
                        // Standard: 3 pads à gauche, 3 pads à droite
                        padPositions.push(
                            {x: padding + 20, y: padding + size/4, num: 1, value: document.getElementById('pad_1_6')?.value || 'R'},
                            {x: padding + 20, y: padding + size/2, num: 2, value: document.getElementById('pad_2_6')?.value || 'G'},
                            {x: padding + 20, y: padding + 3*size/4, num: 3, value: document.getElementById('pad_3_6')?.value || 'B'},
                            {x: padding + size - 20, y: padding + size/4, num: 4, value: document.getElementById('pad_4_6')?.value || 'R'},
                            {x: padding + size - 20, y: padding + size/2, num: 5, value: document.getElementById('pad_5_6')?.value || 'G'},
                            {x: padding + size - 20, y: padding + 3*size/4, num: 6, value: document.getElementById('pad_6_6')?.value || '+'}
                        );
                    }
                    break;
                case 8:
                    if (isMiniLED) {
                        // MiniLED: 2 pads par côté (haut, droite, bas, gauche)
                        padPositions.push(
                            // Haut : 2 pads
                            {x: padding + size/3, y: padding + 20, num: 1, value: document.getElementById('pad_1_8')?.value || 'R'},
                            {x: padding + 2*size/3, y: padding + 20, num: 2, value: document.getElementById('pad_2_8')?.value || 'G'},
                            
                            // Droite : 2 pads
                            {x: padding + size - 20, y: padding + size/3, num: 3, value: document.getElementById('pad_3_8')?.value || 'B'},
                            {x: padding + size - 20, y: padding + 2*size/3, num: 4, value: document.getElementById('pad_4_8')?.value || '+'},
                            
                            // Bas : 2 pads
                            {x: padding + size/3, y: padding + size - 20, num: 5, value: document.getElementById('pad_5_8')?.value || 'R'},
                            {x: padding + 2*size/3, y: padding + size - 20, num: 6, value: document.getElementById('pad_6_8')?.value || 'G'},
                            
                            // Gauche : 2 pads
                            {x: padding + 20, y: padding + size/3, num: 7, value: document.getElementById('pad_7_8')?.value || 'B'},
                            {x: padding + 20, y: padding + 2*size/3, num: 8, value: document.getElementById('pad_8_8')?.value || '+'}
                        );
                    } else {
                        // Standard: 4 pads à gauche, 4 pads à droite
                        padPositions.push(
                            {x: padding + 20, y: padding + size*0.2, num: 1, value: document.getElementById('pad_1_8')?.value || 'R'},
                            {x: padding + 20, y: padding + size*0.4, num: 2, value: document.getElementById('pad_2_8')?.value || 'G'},
                            {x: padding + 20, y: padding + size*0.6, num: 3, value: document.getElementById('pad_3_8')?.value || 'B'},
                            {x: padding + 20, y: padding + size*0.8, num: 4, value: document.getElementById('pad_4_8')?.value || '+'},
                            {x: padding + size - 20, y: padding + size*0.2, num: 5, value: document.getElementById('pad_5_8')?.value || 'R'},
                            {x: padding + size - 20, y: padding + size*0.4, num: 6, value: document.getElementById('pad_6_8')?.value || 'G'},
                            {x: padding + size - 20, y: padding + size*0.6, num: 7, value: document.getElementById('pad_7_8')?.value || 'B'},
                            {x: padding + size - 20, y: padding + size*0.8, num: 8, value: document.getElementById('pad_8_8')?.value || '+'}
                        );
                    }
                    break;
            }
            
            // Couleurs pour les différents types de pads
            const padColors = {
                'R': '#ff0000',  // Rouge
                'G': '#00ff00',  // Vert
                'B': '#0000ff',  // Bleu
                '+': '#ffcc00',  // Commun +
                '-': '#666666',  // Commun -
            };
            
            // Dessiner chaque pad
            padPositions.forEach(pad => {
                // Couleur du pad
                ctx.fillStyle = padColors[pad.value] || '#999';
                
                // Dessiner le cercle
                ctx.beginPath();
                ctx.arc(pad.x, pad.y, padSize/2, 0, Math.PI * 2);
                ctx.fill();
                
                // Ajouter une bordure
                ctx.strokeStyle = '#444';
                ctx.lineWidth = 1;
                ctx.stroke();
                
                // Ajouter deux lignes de texte : le numéro et la valeur
                // Numéro du pad (en haut du pad)
                ctx.fillStyle = '#000';
                ctx.font = 'bold 8px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(pad.num, pad.x, pad.y - 5);
                
                // Valeur du pad (en bas du pad)
                ctx.fillStyle = '#fff';
                ctx.font = 'bold 10px Arial';
                ctx.fillText(pad.value, pad.x, pad.y + 5);
            });
            
            // Mettre à jour le nom du datasheet
            if (ledNamePreview) {
                const type = ledTypeSelect?.value || 'SMD';
                const size = ledSizeSelect?.value || '2121';
                const rotation = ledRotationSelect?.value || '0';
                
                // Récupérer les valeurs des pads pour le nom du datasheet
                let padValuesString = '';
                
                // Construire la chaîne de valeurs à partir des positions
                padPositions.forEach(pad => {
                    padValuesString += pad.value;
                });
                
                // Générer le nom complet du datasheet
                ledNamePreview.textContent = `${type}${size}${padValuesString}${rotation}`;
            }
        }
        
        // Fonction pour générer le datasheet final
        function generateDatasheet() {
            if (!ledDatasheetName || !ledDatasheetImage || !ledCanvas) return;
            
            // Récupérer directement le nom de la prévisualisation si elle est définie
            let dataSheetName = '';
            if (ledNamePreview) {
                dataSheetName = ledNamePreview.textContent;
            } else {
                // Créer le nom du datasheet manuellement si la prévisualisation n'est pas disponible
                const type = ledTypeSelect?.value || 'SMD';
                const size = ledSizeSelect?.value || '2121';
                const rotation = ledRotationSelect?.value || '0';
                
                // Récupérer les valeurs des pads actuellement sélectionnées
                const numPads = parseInt(ledPadsSelect?.value || '2');
                let padValues = '';
                
                // Construire une liste des éléments de pad
                const padElements = [];
                if (numPads === 2) {
                    padElements.push(document.getElementById('pad_1'), document.getElementById('pad_2'));
                } else if (numPads === 4) {
                    padElements.push(
                        document.getElementById('pad_1_4'), document.getElementById('pad_2_4'),
                        document.getElementById('pad_3_4'), document.getElementById('pad_4_4')
                    );
                } else if (numPads === 6) {
                    for (let i = 1; i <= 6; i++) {
                        padElements.push(document.getElementById(`pad_${i}_6`));
                    }
                } else if (numPads === 8) {
                    for (let i = 1; i <= 8; i++) {
                        padElements.push(document.getElementById(`pad_${i}_8`));
                    }
                }
                
                // Assembler les valeurs des pads
                padElements.forEach(element => {
                    if (element && element.value) {
                        padValues += element.value;
                    }
                });
                
                dataSheetName = `${type}${size}${padValues}${rotation}`;
            }
            
            // Enregistrer le nom du datasheet
            ledDatasheetName.value = dataSheetName;
            
            // Capturer l'image du canvas
            ledDatasheetImage.value = ledCanvas.toDataURL('image/png');
            
            console.log('Datasheet généré:', dataSheetName);
            
            // Notifier l'utilisateur
            alert('Datasheet LED généré avec succès: ' + dataSheetName);
        }
        
        // Ajouter les écouteurs d'événements
        if (ledPadsSelect) {
            ledPadsSelect.addEventListener('change', updatePadConfigs);
        }
        
        if (ledTypeSelect) ledTypeSelect.addEventListener('change', updatePreview);
        if (ledSizeSelect) ledSizeSelect.addEventListener('change', updatePreview);
        if (ledSizePreset) {
            ledSizePreset.addEventListener('change', function() {
                if (this.value && ledSizeSelect) {
                    ledSizeSelect.value = this.value;
                    updatePreview();
                }
            });
        }
        if (ledRotationSelect) ledRotationSelect.addEventListener('change', updatePreview);
        
        // Écouteurs pour tous les sélecteurs de pads
        document.querySelectorAll('#pads_container select').forEach(select => {
            select.addEventListener('change', updatePreview);
        });
        
        // Bouton de génération du datasheet
        if (generateButton) {
            generateButton.addEventListener('click', generateDatasheet);
        }
        
        // Initialiser l'affichage
        updatePadConfigs();
    } else {
        console.warn('Éléments datasheet LED manquants');
    }
    
    // PARTIE 2: GESTION DE LA DISPOSITION DES MODULES
    
    // Récupérer les éléments relatifs à la disposition
    const dispositionRadios = document.querySelectorAll('input[name="disposition_modules"]');
    const dispositionPersonnalisee = document.getElementById('disposition_personnalisee');
    const modulesLargeur = document.getElementById('modules_largeur');
    const modulesHauteur = document.getElementById('modules_hauteur');
    const gridPreview = document.getElementById('grid_preview');
    
    if (dispositionRadios.length > 0 && dispositionPersonnalisee) {
        console.log('Éléments disposition modules trouvés');
        
        // Fonction pour mettre à jour l'affichage selon la disposition sélectionnée
        function updateDisposition() {
            const selectedRadio = document.querySelector('input[name="disposition_modules"]:checked');
            
            if (!selectedRadio) return;
            
            console.log('Disposition sélectionnée:', selectedRadio.value);
            
            // Afficher/masquer les options de personnalisation
            if (selectedRadio.value === 'personnalise') {
                dispositionPersonnalisee.style.display = 'block';
                // Mettre à jour la prévisualisation
                if (modulesLargeur && modulesHauteur && gridPreview) {
                    updateGridPreview();
                }
            } else {
                dispositionPersonnalisee.style.display = 'none';
                
                // Extraire les dimensions depuis la valeur (ex: "2x2")
                const dimensions = selectedRadio.value.split('x');
                if (dimensions.length === 2 && modulesLargeur && modulesHauteur) {
                    modulesLargeur.value = dimensions[0];
                    modulesHauteur.value = dimensions[1];
                }
            }
        }
        
        // Fonction pour mettre à jour la prévisualisation de la grille
        function updateGridPreview() {
            if (!gridPreview || !modulesLargeur || !modulesHauteur) return;
            
            const largeur = parseInt(modulesLargeur.value) || 2;
            const hauteur = parseInt(modulesHauteur.value) || 2;
            
            console.log('Mise à jour prévisualisation grille:', largeur, 'x', hauteur);
            
            // Mettre à jour le style pour la grille
            gridPreview.style.display = 'grid';
            gridPreview.style.gridTemplateColumns = `repeat(${largeur}, 1fr)`;
            
            // Recréer les cellules
            gridPreview.innerHTML = '';
            for (let i = 0; i < largeur * hauteur; i++) {
                const cell = document.createElement('div');
                cell.className = 'bg-accent-blue rounded';
                cell.style.aspectRatio = '1/1';
                gridPreview.appendChild(cell);
            }
        }
        
        // Ajouter les écouteurs d'événements pour les boutons radio
        dispositionRadios.forEach(radio => {
            radio.addEventListener('change', updateDisposition);
        });
        
        // Ajouter les écouteurs pour les champs de dimensions
        if (modulesLargeur) modulesLargeur.addEventListener('change', updateGridPreview);
        if (modulesHauteur) modulesHauteur.addEventListener('change', updateGridPreview);
        
        // Initialiser l'affichage
        updateDisposition();
    } else {
        console.warn('Éléments disposition modules manquants');
    }
    
    // Ajouter une classe au corps du document pour indiquer que le script est chargé
    document.body.classList.add('step2-script-loaded');
});