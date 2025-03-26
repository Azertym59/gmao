@props(['module', 'x', 'y'])

@php
// Vérification en temps réel si le module est vraiment occupé
// Utiliser la nouvelle méthode optimisée
$isOccupe = $module->isReallyOccupied();

// Obtenir l'intervention active s'il y en a une
$activeIntervention = $module->getActiveIntervention();

if ($activeIntervention) {
    $technicien = App\Models\User::find($activeIntervention->technicien_id);
    $activeTechName = $technicien ? $technicien->name : 'Inconnu';
} else {
    $activeTechName = null;
}
@endphp

<div class="relative module-container" 
    data-x-pos="{{ $x }}" 
    data-grid-width="{{ isset($gridWidth) ? $gridWidth : 4 }}"
    data-module-id="{{ $module->id }}">
    
    <!-- Module principal avec menu contextuel natif (clic droit) -->
    <div class="module-item-wrapper">
        <!-- Bouton pour afficher le menu (trois points) -->
        <button type="button" 
            onclick="toggleModuleMenu('module-menu-{{ $module->id }}')"
            class="absolute top-0 right-0 z-20 bg-gray-800/80 rounded-bl-sm hover:bg-gray-700/80 p-0.5"
            style="width: 16px; height: 16px;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
            </svg>
        </button>
        
        <!-- Module -->
        <a href="{{ route('modules.show', $module) }}" 
            class="block aspect-square w-10 h-10 module-grid-item
            @if($module->etat == 'termine') bg-accent-green
            @elseif($module->etat == 'stand_by') bg-accent-yellow
            @elseif($module->etat == 'defaillant') bg-accent-red
            @else bg-gray-600
            @endif
            @if($isOccupe) module-busy @endif
            rounded-sm @if(!$isOccupe) border border-black/10 @endif 
            hover:brightness-110 transition-all hover:shadow-lg duration-150 transform hover:scale-105"
            title="{{ $isOccupe ? '⚠️ MODULE OCCUPÉ' . ($activeTechName ? ' - Par: '.$activeTechName : '') : 'Module #' . $module->id . ' - ' . ucfirst(str_replace('_', ' ', $module->etat)) }}">
            
            <div class="flex items-center justify-center h-full text-xs font-medium text-white/90">
                {{ $x }},{{ $y }}
            </div>
        </a>
    </div>

    <!-- Menu contextuel fixe (affiché au clic sur le bouton) -->
    <div id="module-menu-{{ $module->id }}" class="module-menu-container hidden absolute top-0 z-50 module-side-menu">
        <div class="bg-gray-800 border border-gray-700 rounded-md shadow-lg p-0.5 min-w-[80px]">
            <!-- Menu compact avec juste les icônes -->
            <div class="flex flex-col space-y-1 p-0.5">
                <!-- Titre -->
                <div class="text-[8px] text-gray-400 text-center mb-0.5">{{ $module->reference_module }}</div>
                
                <!-- Bouton "Détails" -->
                <a href="{{ route('modules.show', $module) }}" class="text-[8px] px-1.5 py-1 bg-purple-600 hover:bg-purple-500 text-white rounded flex items-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Détails
                </a>
                
                <!-- Marquer comme OK -->
                @if($module->etat != 'termine' && !$isOccupe)
                    <form action="{{ route('modules.mark-as-completed', $module) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-[8px] px-1.5 py-1 bg-green-600 hover:bg-green-500 text-white rounded flex items-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            OK
                        </button>
                    </form>
                @endif
                
                <!-- Intervention -->
                @if(!$isOccupe)
                    <a href="{{ route('interventions.create', $module->id) }}" class="text-[8px] px-1.5 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded flex items-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Interv.
                    </a>
                @endif
                
                <!-- Voir intervention en cours -->
                @if($isOccupe && $activeIntervention)
                    <a href="{{ route('interventions.diagnostic', $activeIntervention) }}" class="text-[8px] px-1.5 py-1 bg-yellow-600 hover:bg-yellow-500 text-white rounded flex items-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Voir
                    </a>
                @endif
                
                @if($isOccupe && auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'technicien'))
                    <a href="{{ route('maintenance.clean-modules', ['module_id' => $module->id]) }}" 
                        class="text-[8px] px-1.5 py-1 bg-red-700 hover:bg-red-600 text-white rounded flex items-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        Déverr.
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Style pour le menu latéral */
.module-container {
    position: relative;
}

/* Styles pour la classe module-item-wrapper (pour le bouton dans le coin) */
.module-item-wrapper {
    position: relative;
}

.module-menu-container {
    position: absolute;
    /* Menu positionné en flottant au-dessus du module */
    left: -5px;
    top: -65px;
    max-width: 90px;
    z-index: 100;
}

/* Style pour le module occupé */
.module-busy {
    background-image: repeating-linear-gradient(
        45deg,
        transparent,
        transparent 5px,
        rgba(0, 0, 0, 0.2) 5px,
        rgba(0, 0, 0, 0.2) 10px
    );
}
</style>