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
    
    <!-- Module principal avec menu flottant au survol -->
    <div class="module-item-wrapper">
        <!-- Module -->
        <div class="module-trigger"
            data-module-id="{{ $module->id }}"
            onmouseover="moduleMenuPosition('module-menu-{{ $module->id }}', this)">
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
    </div>
</div>

<!-- Script pour créer les menus au niveau du body à la fin de la page -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vérifie si le menu existe déjà au niveau global
    if (!document.getElementById('module-menu-{{ $module->id }}')) {
        // Crée le menu au niveau du body
        const menuDiv = document.createElement('div');
        menuDiv.id = 'module-menu-{{ $module->id }}';
        menuDiv.className = 'module-menu-floating hidden';
        menuDiv.setAttribute('data-module-menu', '{{ $module->id }}');
        
        menuDiv.innerHTML = `
            <div class="bg-gray-800 border border-gray-700 rounded-md shadow-lg p-1.5 min-w-[100px]">
                <div class="flex flex-col space-y-2">
                    <div class="text-xs text-gray-300 font-semibold text-center mb-1">
                        {{ $module->reference_module ?: 'Module #' . $module->id }}
                    </div>
                    
                    <div class="grid grid-cols-1 gap-2">
                        @if($module->etat != 'termine' && !$isOccupe)
                            <form action="{{ route('modules.mark-as-completed', $module) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-xs px-2 py-1.5 bg-green-600 hover:bg-green-500 text-white rounded flex items-center justify-center transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    OK
                                </button>
                            </form>
                        @endif
                        
                        @if(!$isOccupe)
                            <a href="{{ route('interventions.create', $module->id) }}" class="w-full text-xs px-2 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Intervention
                            </a>
                        @endif
                        
                        @if($isOccupe && $activeIntervention)
                            <a href="{{ route('interventions.diagnostic', $activeIntervention) }}" class="w-full text-xs px-2 py-1.5 bg-yellow-600 hover:bg-yellow-500 text-white rounded flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Voir Intervention
                            </a>
                        @endif
                        
                        <a href="{{ route('modules.show', $module) }}" class="w-full text-xs px-2 py-1.5 bg-purple-600 hover:bg-purple-500 text-white rounded flex items-center justify-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Détails
                        </a>
                        
                        @if($isOccupe && auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'technicien'))
                            <a href="{{ route('maintenance.clean-modules', ['module_id' => $module->id]) }}" 
                                class="w-full text-xs px-2 py-1.5 bg-red-700 hover:bg-red-600 text-white rounded flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                Déverrouiller
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(menuDiv);
    }
});
</script>

<style>
/* Style pour le module container */
.module-container {
    position: relative;
}

/* Style pour le wrapper du module */
.module-item-wrapper {
    position: relative;
}

/* Style pour le menu flottant */
.module-menu-floating {
    position: fixed; 
    z-index: 9999; /* Très élevé pour être sûr qu'il est au-dessus de tout */
    min-width: 130px;
    border-radius: 6px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
    background-color: rgba(31, 41, 55, 0.95); /* Pour s'assurer que le fond est visible */
    pointer-events: auto; /* S'assurer que les clics fonctionnent */
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

/* Flèche du menu */
.module-menu-floating::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 10px;
    width: 0;
    height: 0;
    border-top: 6px solid transparent;
    border-bottom: 6px solid transparent;
    border-right: 6px solid #1f2937;
}
</style>