<?php
    // Utiliser une fonction anonyme pour éviter les conflits de redéclaration
    $getGridLayout = function($dalle) {
        $disposition = $dalle->disposition_modules;
        
        // Valeurs par défaut si aucune disposition n'est définie
        $nbColonnes = 2;
        $nbLignes = 2;
        
        // Si disposition est définie et au format valide (ex: "2x2")
        if (!empty($disposition) && strpos($disposition, 'x') !== false) {
            $parts = explode('x', $disposition);
            if (count($parts) == 2) {
                $nbColonnes = (int)$parts[0];
                $nbLignes = (int)$parts[1];
            }
        }
        
        return ['colonnes' => $nbColonnes, 'lignes' => $nbLignes];
    };
    
    // Utiliser la fonction locale
    $gridLayout = $getGridLayout($dalle);
    $nbColonnes = $gridLayout['colonnes'] ?? 2;
    $nbLignes = $gridLayout['lignes'] ?? 2;
    
    // Vérifier s'il y a des modules
    $modules = $dalle->modules->all();
    
    // Vérification que $chantier est défini
    if (!isset($chantier) && isset($dalle->produit) && isset($dalle->produit->chantier)) {
        $chantier = $dalle->produit->chantier;
    }
    
    // Créer une grille pour les modules
    $grille = [];
    
    // Remplir la grille ligne par ligne
    $moduleIndex = 0;
    for ($y = 1; $y <= $nbLignes; $y++) {
        for ($x = 1; $x <= $nbColonnes; $x++) {
            if ($moduleIndex < count($modules)) {
                $grille[$y][$x] = $modules[$moduleIndex];
                $moduleIndex++;
            } else {
                $grille[$y][$x] = null;
            }
        }
    }
    
    // Calcul des statistiques
    $total = count($modules);
    $completed = 0;
    $defaillant = 0;
    $standBy = 0;
    $enCours = 0;
    $hs = 0;
    
    foreach ($modules as $module) {
        if ($module->etat === 'termine') {
            $completed++;
        } else if ($module->etat === 'defaillant') {
            $defaillant++;
        } else if ($module->etat === 'stand_by') {
            $standBy++;
        } else if ($module->etat === 'en_cours') {
            $enCours++;
        } else if ($module->etat === 'hs') {
            $hs++;
        }
    }
    $percent = $total > 0 ? round(($completed / $total) * 100) : 0;
?>

<div class="modules-wrapper">
    <!-- Grille de modules organisée -->
    <div class="modules-grid" style="grid-template-columns: repeat(<?php echo $nbColonnes; ?>, minmax(0, 1fr));">
        <?php for ($y = 1; $y <= $nbLignes; $y++): ?>
            <?php for ($x = 1; $x <= $nbColonnes; $x++): ?>
                <?php if (isset($grille[$y][$x])): ?>
                    <?php 
                        $module = $grille[$y][$x]; 
                        // Vérifier si le module est occupé (en intervention)
                        $isOccupe = method_exists($module, 'isReallyOccupied') ? $module->isReallyOccupied() : false;
                    ?>
                    <div class="relative module-container" data-module-id="<?php echo $module->id; ?>">
                        <a href="javascript:void(0)" 
                            onclick="showModuleDetails('<?php echo $module->id; ?>', '<?php echo $module->reference_module ?? $module->id; ?>', '<?php echo $module->etat; ?>', '<?php echo $dalle->reference_dalle ?? $dalle->id; ?>', '<?php echo $x; ?>,<?php echo $y; ?>')" 
                            class="module-item <?php if($isOccupe): ?>module-busy<?php endif; ?>"
                            style="background-color: 
                                <?php if($module->etat == 'termine'): ?> #00d624
                                <?php elseif($module->etat == 'en_cours'): ?> #3b82f6  
                                <?php elseif($module->etat == 'stand_by'): ?> #ffc107
                                <?php elseif($module->etat == 'defaillant'): ?> #19918d
                                <?php elseif($module->etat == 'hs'): ?> #dc3545
                                <?php else: ?> #6b7280
                                <?php endif; ?>"
                            title="Module <?php echo $module->reference_module ?? $module->id; ?> - <?php echo ucfirst(str_replace('_', ' ', $module->etat)); ?>">
                            <span class="module-ref"><?php echo $x; ?>,<?php echo $y; ?></span>
                            <div class="module-overlay">
                                <span class="module-id"><?php echo $module->reference_module ?? $module->id; ?></span>
                                <span class="module-state">
                                    <?php if($module->etat == 'termine'): ?>
                                        OK
                                    <?php elseif($module->etat == 'en_cours'): ?>
                                        EN COURS
                                    <?php elseif($module->etat == 'stand_by'): ?>
                                        EN ATTENTE
                                    <?php elseif($module->etat == 'defaillant'): ?>
                                        DÉFAUT
                                    <?php elseif($module->etat == 'hs'): ?>
                                        HS
                                    <?php else: ?>
                                        À FAIRE
                                    <?php endif; ?>
                                </span>
                            </div>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="module-empty">
                        <span><?php echo $x; ?>,<?php echo $y; ?></span>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        <?php endfor; ?>
    </div>
    
    <!-- Barre de progression améliorée avec statistiques détaillées -->
    <div class="progress-container compact">
        <div class="progress-text">
            <span><strong><?php echo $completed; ?>/<?php echo $total; ?></strong> modules</span>
            <span><strong><?php echo $percent; ?>%</strong> terminés</span>
            <?php if ($hs > 0): ?><span><strong><?php echo $hs; ?></strong> HS</span><?php endif; ?>
        </div>
        <div class="progress-bar-bg">
            <div class="progress-bar-fill" style="width: <?php echo $percent; ?>%;
                background-color: 
                <?php if ($percent >= 75): ?> #00d624
                <?php elseif ($percent >= 40): ?> #3b82f6
                <?php else: ?> #ffc107
                <?php endif; ?>">
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour la disposition des modules en grille */
.modules-wrapper {
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

.modules-grid {
    display: grid;
    gap: 0.35rem;
    margin-bottom: 0.75rem;
    justify-content: center;
    max-width: 100%;
}

.module-container {
    position: relative;
    width: 40px;
    height: 40px;
}

.module-item {
    aspect-ratio: 1/1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.25rem;
    color: white;
    font-size: 0.75rem;
    text-decoration: none;
    position: relative;
    transition: transform 0.15s, box-shadow 0.15s;
    border: 1px solid rgba(255, 255, 255, 0.1);
    width: 40px;
    height: 40px;
    min-width: 40px;
    min-height: 40px;
    cursor: pointer;
}

.module-item:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    z-index: 10;
}

.module-ref {
    font-weight: 500;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

.module-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.2s;
    border-radius: 0.25rem;
    padding: 4px;
    text-align: center;
    z-index: 100;
}

.module-item:hover .module-overlay {
    opacity: 1;
}

.module-id {
    font-size: 0.7rem;
    font-weight: bold;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

.module-state {
    font-size: 0.65rem;
    background: rgba(255, 255, 255, 0.2);
    padding: 2px 4px;
    border-radius: 2px;
}

.module-empty {
    aspect-ratio: 1/1;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(31, 41, 55, 0.5);
    border-radius: 0.25rem;
    color: rgba(255, 255, 255, 0.3);
    font-size: 0.75rem;
    border: 1px dashed rgba(255, 255, 255, 0.1);
    width: 40px;
    height: 40px;
    min-width: 40px;
    min-height: 40px;
}

.progress-container {
    margin-top: 0.5rem;
    width: 100%;
    max-width: 300px;
}

.progress-container.compact {
    width: 100%;
}

.progress-text {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: #d1d5db;
    margin-bottom: 4px;
}

.progress-bar-bg {
    height: 6px;
    width: 100%;
    background-color: #374151;
    border-radius: 9999px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    border-radius: 9999px;
    transition: width 0.5s ease;
}

/* Style pour les modules occupés - Reprend le style de module-busy.css */
.module-busy {
    border: 2px solid #ff7b00 !important;
    box-shadow: 0 0 0 2px rgba(255, 123, 0, 0.8), 0 0 8px rgba(255, 123, 0, 0.6) !important;
    position: relative;
    z-index: 5;
    transform: scale(1.05);
}

.module-busy::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: repeating-linear-gradient(
        45deg,
        rgba(255, 123, 0, 0.4),
        rgba(255, 123, 0, 0.4) 5px,
        rgba(255, 123, 0, 0.2) 5px,
        rgba(255, 123, 0, 0.2) 10px
    );
    z-index: 1;
    pointer-events: none;
}

/* Responsive pour les petits écrans */
@media (max-width: 640px) {
    .module-container, .module-item, .module-empty {
        width: 35px;
        height: 35px;
        min-width: 35px;
        min-height: 35px;
    }
    
    .module-ref, .module-id, .module-state {
        font-size: 0.65rem;
    }
}
</style>

<!-- Script pour la modal d'affichage des détails de module -->
<script>
// Créer une fonction globale pour afficher les détails des modules
function showModuleDetails(moduleId, moduleRef, moduleState, dalleRef, position) {
    // Convertir les états en texte lisible
    const states = {
        'termine': 'Terminé',
        'en_cours': 'En cours',
        'stand_by': 'En attente',
        'defaillant': 'Défaillant',
        'hs': 'Hors service',
        'non_commence': 'Non commencé'
    };
    
    const stateText = states[moduleState] || 'État inconnu';
    const title = `Module ${moduleRef}`;
    
    // Créer un contenu HTML pour la modal
    const content = `
        <div class="module-details">
            <div class="detail-row">
                <span class="detail-label">Référence:</span>
                <span class="detail-value">${moduleRef}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Dalle:</span>
                <span class="detail-value">${dalleRef}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Position:</span>
                <span class="detail-value">${position}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">État:</span>
                <span class="detail-value state-${moduleState}">${stateText}</span>
            </div>
        </div>
    `;
    
    // Si la fonction alert est disponible, l'utiliser
    if (typeof showCustomAlert === 'function') {
        showCustomAlert(title, content);
    } else {
        // Sinon utiliser une alerte standard
        alert(`Module: ${moduleRef}\nÉtat: ${stateText}\nPosition: ${position}\nDalle: ${dalleRef}`);
    }
}

// Vérifier si la fonction d'alerte personnalisée existe déjà
if (typeof showCustomAlert !== 'function') {
    // Définir la fonction si elle n'existe pas
    function showCustomAlert(title, content) {
        // Vérifier si la modal existe déjà
        let modal = document.getElementById('custom-alert-modal');
        
        // Créer la modal si elle n'existe pas
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'custom-alert-modal';
            modal.className = 'custom-alert-modal';
            modal.innerHTML = `
                <div class="custom-alert-container">
                    <div class="custom-alert-header">
                        <h3 id="custom-alert-title"></h3>
                        <button onclick="closeCustomAlert()" class="custom-alert-close">&times;</button>
                    </div>
                    <div id="custom-alert-content" class="custom-alert-content"></div>
                    <div class="custom-alert-footer">
                        <button onclick="closeCustomAlert()" class="custom-alert-button">Fermer</button>
                    </div>
                </div>
                <style>
                    .custom-alert-modal {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background-color: rgba(0, 0, 0, 0.5);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 9999;
                    }
                    .custom-alert-container {
                        background-color: #1f2937;
                        border-radius: 0.5rem;
                        width: 90%;
                        max-width: 400px;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        overflow: hidden;
                    }
                    .custom-alert-header {
                        padding: 1rem;
                        border-bottom: 1px solid #374151;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    }
                    .custom-alert-header h3 {
                        margin: 0;
                        font-size: 1.125rem;
                        color: #f9fafb;
                    }
                    .custom-alert-close {
                        background: none;
                        border: none;
                        color: #9ca3af;
                        font-size: 1.5rem;
                        cursor: pointer;
                    }
                    .custom-alert-content {
                        padding: 1rem;
                        color: #e5e7eb;
                    }
                    .custom-alert-footer {
                        padding: 1rem;
                        border-top: 1px solid #374151;
                        display: flex;
                        justify-content: flex-end;
                    }
                    .custom-alert-button {
                        background-color: #3b82f6;
                        color: white;
                        border: none;
                        padding: 0.5rem 1rem;
                        border-radius: 0.25rem;
                        cursor: pointer;
                    }
                    .custom-alert-button:hover {
                        background-color: #2563eb;
                    }
                    .module-details {
                        display: flex;
                        flex-direction: column;
                        gap: 0.75rem;
                    }
                    .detail-row {
                        display: flex;
                        justify-content: space-between;
                    }
                    .detail-label {
                        font-weight: 500;
                        color: #9ca3af;
                    }
                    .detail-value {
                        font-weight: 600;
                    }
                    .state-termine { color: #00d624; }
                    .state-en_cours { color: #3b82f6; }
                    .state-stand_by { color: #ffc107; }
                    .state-defaillant { color: #19918d; }
                    .state-hs { color: #dc3545; }
                    .state-non_commence { color: #6b7280; }
                </style>
            `;
            
            document.body.appendChild(modal);
        }
        
        // Mettre à jour le contenu de la modal
        document.getElementById('custom-alert-title').innerText = title;
        document.getElementById('custom-alert-content').innerHTML = content;
        
        // Afficher la modal
        modal.style.display = 'flex';
    }
    
    // Fonction pour fermer la modal
    function closeCustomAlert() {
        const modal = document.getElementById('custom-alert-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    }
    
    // Ajouter la fonction au scope global
    window.showCustomAlert = showCustomAlert;
    window.closeCustomAlert = closeCustomAlert;
}
</script><?php /**PATH /var/www/gmao/resources/views/suivi/partials/dalle-content.blade.php ENDPATH**/ ?>