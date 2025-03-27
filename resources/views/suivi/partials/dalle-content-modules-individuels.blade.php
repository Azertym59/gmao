<?php
    // Récupérer tous les modules de la dalle
    $modules = $dalle->modules->all();
    
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
    <!-- Affichage des modules individuels identique à la vue détaillée du chantier -->
    <div class="flex flex-wrap gap-2 justify-center mt-2 mb-4">
        <?php foreach ($modules as $module): ?>
            <?php 
                // Vérifier si le module est occupé (en intervention)
                $isOccupe = method_exists($module, 'isReallyOccupied') ? $module->isReallyOccupied() : false;
                
                // Récupérer le numéro du module (uniquement les chiffres)
                $moduleNumber = preg_replace('/[^0-9]/', '', $module->reference_module ?? $module->id);
            ?>
            <div class="relative group">
                <a href="javascript:void(0)" 
                   onclick="showModuleDetails('<?php echo $module->id; ?>', '<?php echo $module->reference_module ?? $module->id; ?>', '<?php echo $module->etat; ?>', '<?php echo $dalle->reference_dalle ?? $dalle->id; ?>', '-')"
                   class="block aspect-square w-10 h-10 
                        <?php if($module->etat == 'termine'): ?> bg-accent-green
                        <?php elseif($module->etat == 'en_cours'): ?> bg-accent-blue  
                        <?php elseif($module->etat == 'stand_by'): ?> bg-accent-yellow
                        <?php elseif($module->etat == 'defaillant'): ?> bg-accent-defaillant
                        <?php elseif($module->etat == 'hs'): ?> bg-accent-hs
                        <?php else: ?> bg-gray-600
                        <?php endif; ?>
                        <?php if($isOccupe): ?> module-busy <?php endif; ?>
                        rounded-sm border border-black/10 transition-all hover:shadow-lg 
                        duration-150 transform hover:scale-105 cursor-pointer"
                   title="Module <?php echo $module->reference_module ?? $module->id; ?> - <?php echo ucfirst(str_replace('_', ' ', $module->etat)); ?>">
                    <div class="flex items-center justify-center h-full text-xs font-medium text-white/90">
                        <?php echo $moduleNumber; ?>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
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
/* Styles pour les modules */
.modules-wrapper {
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

/* Couleurs identiques à module-busy.css */
.bg-accent-green {
    background-color: #00d624 !important; /* Vert - Terminé */
}

.bg-accent-blue {
    background-color: #3b82f6 !important; /* Bleu - En cours */
}

.bg-accent-yellow {
    background-color: #ffc107 !important; /* Jaune - Stand by */
}

.bg-accent-defaillant {
    background-color: #19918d !important; /* Turquoise - État défaillant */
}

.bg-accent-hs {
    background-color: #dc3545 !important; /* Rouge - État HS */
}

.bg-gray-600 {
    background-color: #4B5563 !important; /* Gris - Non commencé / Par défaut */
}

/* Style pour les modules occupés - Reprend le style de module-busy.css */
.module-busy {
    border: 2px solid #ff7b00 !important;
    box-shadow: 0 0 0 2px rgba(255, 123, 0, 0.8), 0 0 8px rgba(255, 123, 0, 0.6) !important;
    position: relative;
    z-index: 5;
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

/* Style pour la barre de progression */
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

/* Utility classes identiques à TailwindCSS */
.flex {
    display: flex;
}

.flex-wrap {
    flex-wrap: wrap;
}

.gap-2 {
    gap: 0.5rem;
}

.justify-center {
    justify-content: center;
}

.mt-2 {
    margin-top: 0.5rem;
}

.mb-4 {
    margin-bottom: 1rem;
}

.relative {
    position: relative;
}

.group {
    position: relative;
}

.block {
    display: block;
}

.aspect-square {
    aspect-ratio: 1 / 1;
}

.w-10 {
    width: 2.5rem;
}

.h-10 {
    height: 2.5rem;
}

.rounded-sm {
    border-radius: 0.125rem;
}

.border {
    border-width: 1px;
}

.border-black\/10 {
    border-color: rgba(0, 0, 0, 0.1);
}

.transition-all {
    transition-property: all;
}

.hover\:shadow-lg:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.duration-150 {
    transition-duration: 150ms;
}

.transform {
    transform: translateX(0) translateY(0) rotate(0) skewX(0) skewY(0) scaleX(1) scaleY(1);
}

.hover\:scale-105:hover {
    transform: scale(1.05);
}

.cursor-pointer {
    cursor: pointer;
}

.items-center {
    align-items: center;
}

.justify-center {
    justify-content: center;
}

.h-full {
    height: 100%;
}

.text-xs {
    font-size: 0.75rem;
    line-height: 1rem;
}

.font-medium {
    font-weight: 500;
}

.text-white\/90 {
    color: rgba(255, 255, 255, 0.9);
}
</style>

<!-- Script pour la modal d'affichage des détails de module -->
<script>
// Créer une fonction globale pour afficher les détails des modules si elle n'existe pas déjà
if (typeof showModuleDetails !== 'function') {
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
                ${position !== '-' ? `
                <div class="detail-row">
                    <span class="detail-label">Position:</span>
                    <span class="detail-value">${position}</span>
                </div>` : ''}
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
            alert(`Module: ${moduleRef}\nÉtat: ${stateText}\n${position !== '-' ? `Position: ${position}\n` : ''}Dalle: ${dalleRef}`);
        }
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
</script>