/**
 * Fichier JavaScript pour la page de création de chantier - Étape 2
 * Gère les formulaires dynamiques pour la configuration des produits
 * Version: 2.1.0 - Date: 2025-03-14
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script externe chargé - Chantier create step 2');
    
    // Appliquer un style moderne au conteneur de pads
    const padsContainer = document.getElementById('pads_container');
    if (padsContainer) {
        padsContainer.classList.add('scrollbar-thin', 'scrollbar-thumb-gray-600', 'scrollbar-track-gray-800');
    }
    
    // Écouter l'événement personnalisé pour forcer la mise à jour de la prévisualisation lors de changements spécifiques
    document.addEventListener('update_preview', function(e) {
        console.log('Événement update_preview reçu, détails:', e.detail);
        // Déclencher directement la mise à jour de la prévisualisation 
        // en utilisant la fonction updatePreview qui sera définie plus tard
        setTimeout(function() {
            if (typeof updatePreview === 'function') {
                updatePreview();
            }
        }, 50);
    });
    
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
    
    // Créer le container des pads s'il n'existe pas
    if (!document.getElementById('pads_container') && ledCanvas) {
        const padsContainer = document.createElement('div');
        padsContainer.id = 'pads_container';
        padsContainer.className = 'my-4';
        ledCanvas.parentNode.parentNode.insertBefore(padsContainer, ledCanvas.parentNode.nextSibling);
    }
    
    // Vérifier si les éléments sont présents dans le DOM
    if (ledCanvas) {
        console.log('Éléments datasheet LED trouvés');
        
        // Vérifier si ledPadsSelect est défini, sinon le récupérer
        if (!ledPadsSelect) {
            ledPadsSelect = document.getElementById('led_pads');
            console.log('ledPadsSelect récupéré:', ledPadsSelect ? 'trouvé' : 'non trouvé');
        }
        
        // Fonction pour mettre à jour l'affichage des pads
        function updatePadConfigs() {
            if (!ledPadsSelect) {
                console.error('ledPadsSelect est toujours null');
                ledPadsSelect = document.getElementById('led_pads');
                if (!ledPadsSelect) {
                    console.error('Impossible de trouver l\'élément led_pads même après réessai');
                    return;
                }
            }
            
            const numPads = parseInt(ledPadsSelect.value || '4');
            console.log('Changement du nombre de pads à:', numPads);
            
            // Créer les configurations de pads si elles n'existent pas
            if (padConfigs.length === 0) {
                const padsContainer = document.getElementById('pads_container');
                if (!padsContainer) return;
                
                // Créer les configs pour différents nombres de pads
                const padCounts = [2, 4, 6, 8];
                
                padCounts.forEach(count => {
                    const configDiv = document.createElement('div');
                    configDiv.id = `pad-config-${count}`;
                    configDiv.className = 'pad-configs grid grid-cols-2 md:grid-cols-4 gap-4 my-3';
                    configDiv.style.display = 'none';
                    
                    // Ajouter les inputs pour chaque pad
                    for (let i = 1; i <= count; i++) {
                        const id = count === 2 ? `pad_${i}` : `pad_${i}_${count}`;
                        const padDiv = document.createElement('div');
                        padDiv.innerHTML = `
                            <label for="${id}" class="block text-sm text-gray-300 mb-1">Pad ${i}</label>
                            <select id="${id}" class="pad-select block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                                <option value="R">R (Rouge)</option>
                                <option value="G">G (Vert)</option>
                                <option value="B">B (Bleu)</option>
                                <option value="+">+ (Positif)</option>
                                <option value="-">- (Négatif)</option>
                            </select>
                        `;
                        configDiv.appendChild(padDiv);
                    }
                    
                    padsContainer.appendChild(configDiv);
                });
                
                // Réassigner padConfigs après leur création
                const newPadConfigs = document.querySelectorAll('.pad-configs');
                padConfigs.length = 0;
                newPadConfigs.forEach(config => padConfigs.push(config));
            }
            
            // Masquer toutes les configurations
            document.querySelectorAll('.pad-configs').forEach(config => {
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
            const centerX = width / 2;
            const centerY = height / 2;
            
            // Récupérer la rotation actuelle (en degrés)
            const rotationDegrees = parseInt(ledRotationSelect?.value || 0);
            const rotationRadians = (rotationDegrees * Math.PI) / 180;
            
            // Effacer le canvas complet
            ctx.clearRect(0, 0, width, height);
            
            // Sauvegarder l'état du contexte
            ctx.save();
            
            // Déplacer le point d'origine au centre du canvas
            ctx.translate(centerX, centerY);
            
            // Appliquer la rotation
            ctx.rotate(rotationRadians);
            
            // Déplacer le point d'origine au coin supérieur gauche du carré de la LED
            ctx.translate(-centerX, -centerY);
            
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
            
            // Ajouter un indicateur de sens (une petite marque pour montrer l'orientation)
            ctx.fillStyle = '#999';
            ctx.beginPath();
            ctx.arc(padding + 15, padding + 15, 3, 0, Math.PI * 2);
            ctx.fill();
            
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
            
            // Restaurer l'état du contexte (annuler la rotation)
            ctx.restore();
            
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
            if (!ledDatasheetName || !ledDatasheetImage || !ledCanvas) {
                console.error('Éléments datasheet manquants', {
                    ledDatasheetName: !!ledDatasheetName,
                    ledDatasheetImage: !!ledDatasheetImage,
                    ledCanvas: !!ledCanvas
                });
                alert('Erreur: Impossible de générer le datasheet. Veuillez réessayer ou recharger la page.');
                return;
            }
            
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
                const numPads = parseInt(ledPadsSelect?.value || '4');
                let padValues = '';
                
                // Construire une liste de valeurs de pads par défaut
                if (numPads === 2) {
                    padValues = 'R+';
                } else if (numPads === 4) {
                    padValues = 'RGB+';
                } else if (numPads === 6) {
                    padValues = 'RGBRGB';
                } else if (numPads === 8) {
                    padValues = 'RGBRGB++';
                }
                
                // Essayer de récupérer les valeurs réelles des pads si disponibles
                try {
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
                    
                    // Si tous les éléments sont trouvés, utiliser leurs valeurs
                    if (padElements.every(el => el !== null)) {
                        padValues = '';
                        padElements.forEach(element => {
                            if (element && element.value) {
                                padValues += element.value;
                            } else if (element) {
                                padValues += 'X'; // Valeur par défaut si value est manquante
                            }
                        });
                    }
                } catch (e) {
                    console.error('Erreur lors de la récupération des valeurs de pads:', e);
                }
                
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
        } else if (document.getElementById('led_pads')) {
            // Réessayer d'obtenir l'élément si null au début
            setTimeout(() => {
                const ledPadsSelectRetry = document.getElementById('led_pads');
                if (ledPadsSelectRetry) {
                    ledPadsSelectRetry.addEventListener('change', updatePadConfigs);
                }
            }, 500);
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
        
        // Gestion spéciale pour la rotation
        if (ledRotationSelect) {
            ledRotationSelect.addEventListener('change', function() {
                console.log('Rotation changée à:', this.value);
                updatePreview();
            });
            
            // Vérifier le bouton de rotation et l'initialiser s'il existe
            const rotateButton = document.getElementById('rotate_led');
            if (rotateButton) {
                rotateButton.addEventListener('click', function() {
                    console.log('Bouton rotation cliqué');
                    if (ledRotationSelect) {
                        // Obtenir l'index actuel
                        const currentIndex = ledRotationSelect.selectedIndex;
                        // Calculer le prochain index (avec bouclage)
                        const nextIndex = (currentIndex + 1) % ledRotationSelect.options.length;
                        // Mettre à jour la sélection
                        ledRotationSelect.selectedIndex = nextIndex;
                        
                        // Déclencher un événement pour mettre à jour la prévisualisation
                        updatePreview();
                    }
                });
            }
        }
        
        // Configuration des événements pour les pads de manière dynamique
        function setupPadSelectors() {
            const padsContainer = document.getElementById('pads_container');
            if (padsContainer) {
                padsContainer.querySelectorAll('select').forEach(select => {
                    select.addEventListener('change', updatePreview);
                });
                console.log('Écouteurs ajoutés pour les sélecteurs de pads');
            } else {
                console.warn('Container de pads non trouvé pour les écouteurs');
            }
        }
        
        // Configurer les écouteurs après création des pads
        setTimeout(setupPadSelectors, 500);
        
        // Délégation d'événements pour gérer les sélecteurs de pads créés dynamiquement
        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('pad-select')) {
                console.log('Changement de valeur de pad détecté');
                updatePreview();
            }
        });
        
        // Bouton de génération du datasheet
        if (generateButton) {
            generateButton.addEventListener('click', generateDatasheet);
        } else if (document.getElementById('generate_datasheet')) {
            // Réessayer d'obtenir le bouton
            setTimeout(() => {
                const generateButtonRetry = document.getElementById('generate_datasheet');
                if (generateButtonRetry) {
                    generateButtonRetry.addEventListener('click', generateDatasheet);
                }
            }, 500);
        }
        
        // Initialiser l'affichage
        updatePadConfigs();
        
        // Initialiser une seconde fois après un délai pour s'assurer que tous les éléments sont chargés
        setTimeout(updatePadConfigs, 1000);
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