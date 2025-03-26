@php
    // Utiliser la fonction getModuleGridLayout du helper
    $gridLayout = getModuleGridLayout($dalle);
    // Vérification des clés retournées pour la compatibilité 
    $nbColonnes = $gridLayout['colonnes'] ?? $gridLayout['cols'] ?? 2;
    $nbLignes = $gridLayout['lignes'] ?? $gridLayout['rows'] ?? 2;
    $modules = $dalle->modules->all();
    // Vérification que $chantier est défini
    if (!isset($chantier) && isset($dalle->produit) && isset($dalle->produit->chantier)) {
        $chantier = $dalle->produit->chantier;
    }
    
    // Créer la grille organisée
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
    $total = $dalle->modules->count();
    $completed = $dalle->modules->where('etat', 'termine')->count();
    $percent = $total > 0 ? round(($completed / $total) * 100) : 0;
    
    // Vérifier le statut des interventions en temps réel
    foreach ($modules as $module) {
        try {
            $isOccupe = method_exists($module, 'isReallyOccupied') ? $module->isReallyOccupied() : false;
        } catch (\Exception $e) {
            $isOccupe = false;
        }
        $module->isOccupe = $isOccupe;
    }
@endphp

<div class="modules-wrapper">
    <!-- Grille de modules organisée -->
    <div class="modules-grid" style="grid-template-columns: repeat({{ $nbColonnes }}, minmax(0, 1fr));">
        @for ($y = 1; $y <= $nbLignes; $y++)
            @for ($x = 1; $x <= $nbColonnes; $x++)
                @if (isset($grille[$y][$x]))
                    @php $module = $grille[$y][$x]; @endphp
                    <div class="relative module-container">
                        <a href="{{ isset($chantier) && $chantier->token_suivi ? route('suivi.module', $chantier->token_suivi).'?module='.$module->id : '#' }}" 
                            class="module-item @if(isset($module->isOccupe) && $module->isOccupe) module-busy @endif"
                            style="background-color: 
                                @if($module->etat == 'termine') var(--success)
                                @elseif($module->etat == 'en_cours') var(--primary)  
                                @elseif($module->etat == 'defaillant') var(--danger)
                                @else var(--neutral)
                                @endif"
                            title="Module {{ $module->reference_module }} - {{ ucfirst(str_replace('_', ' ', $module->etat)) }}">
                            <span class="module-ref">{{ $x }},{{ $y }}</span>
                            <div class="module-overlay">
                                <span class="module-id">{{ $module->reference_module }}</span>
                                <span class="module-state">
                                    @if($module->etat == 'termine')
                                        OK
                                    @elseif($module->etat == 'en_cours')
                                        EN COURS
                                    @elseif($module->etat == 'defaillant')
                                        DÉFAUT
                                    @else
                                        À FAIRE
                                    @endif
                                </span>
                            </div>
                        </a>
                    </div>
                @else
                    <div class="module-empty">
                        <span>{{ $x }},{{ $y }}</span>
                    </div>
                @endif
            @endfor
        @endfor
    </div>
    
    <!-- Barre de progression sur toute la largeur -->
    <div class="progress-container compact">
        <div class="progress-text">
            <span><strong>{{ $completed }}/{{ $total }}</strong> modules</span>
            <span><strong>{{ $percent }}%</strong> terminés</span>
        </div>
        <div class="progress-bar-bg">
            <div class="progress-bar-fill" style="width: {{ $percent }}%;
                background-color: 
                @if ($percent >= 75) var(--success)
                @elseif ($percent >= 40) var(--primary)
                @else var(--warning)
                @endif">
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
    gap: 0.25rem;
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

.module-busy {
    background-image: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 5px,
        rgba(0, 0, 0, 0.2) 5px,
        rgba(0, 0, 0, 0.2) 10px
    );
    border: 1px solid rgba(255, 152, 0, 0.5) !important;
    box-shadow: 0 0 0 1px rgba(255, 152, 0, 0.3), 0 0 5px rgba(255, 152, 0, 0.3);
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
</style>