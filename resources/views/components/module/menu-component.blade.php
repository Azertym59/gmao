<div class="module-item-wrapper relative">
    <!-- Bouton du module avec attribut data-module-id pour le tracker -->
    <button 
        type="button"
        onclick="toggleModuleMenu('module-menu-{{ $module->id }}')"
        data-module-id="{{ $module->id }}"
        class="aspect-square w-10 h-10 module-grid-item
        @if($module->etat == 'termine') bg-accent-green
        @elseif($module->etat == 'en_cours') bg-accent-yellow
        @elseif($module->etat == 'defaillant') bg-accent-red
        @else bg-gray-600
        @endif
        @if($module->isOccupiedByAnotherTechnician()) module-busy @endif
        rounded-sm border border-black/10 hover:brightness-110 transition-all hover:shadow-lg duration-150 transform hover:scale-105"
        title="Module #{{ $module->id }} - {{ ucfirst($module->etat) }} @if($module->isOccupiedByAnotherTechnician()) - OCCUPÉ @endif"
    >
        <div class="flex items-center justify-center h-full text-xs font-medium text-white/90">
            {{ $x }},{{ $y }}
        </div>
    </button>
    
    <!-- Menu contextuel -->
    <div id="module-menu-{{ $module->id }}" class="module-menu-container hidden absolute top-full mt-1 left-1/2 transform -translate-x-1/2 bg-gray-800 text-xs text-white p-2 rounded whitespace-nowrap z-10 shadow-lg">
        <div class="module-overlay-content">
            <div class="mb-1 font-semibold">Module #{{ $module->id }}</div>
            <div class="mb-2">
                <span class="text-gray-400">État: </span>
                <span class="
                    @if($module->etat == 'termine') text-green-400 
                    @elseif($module->etat == 'en_cours') text-yellow-400
                    @elseif($module->etat == 'defaillant') text-red-400 
                    @else text-gray-400 
                    @endif
                ">{{ ucfirst($module->etat) }}</span>
            </div>
            @if($module->isOccupiedByAnotherTechnician())
                <div class="mb-2 text-orange-400">
                    {{ $module->getOccupiedByTechnicianName() ?: 'OCCUPÉ' }}
                </div>
            @endif
            <div class="grid grid-cols-1 gap-1">
                <!-- Détails du module -->
                <a href="{{ route('modules.show', $module) }}" class="module-btn bg-blue-700 hover:bg-blue-800 text-white">
                    Détails
                </a>
                
                @if(!$module->isOccupiedByAnotherTechnician())
                    <!-- Actions conditionnelles selon l'état du module -->
                    @if($module->etat == 'defaillant')
                        <a href="{{ route('interventions.create', $module) }}" class="module-btn bg-yellow-600 hover:bg-yellow-700 text-white">
                            Interv.
                        </a>
                    @elseif($module->etat == 'non_commence')
                        <form action="{{ route('modules.mark-as-completed', $module) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="module-btn w-full bg-green-700 hover:bg-green-800 text-white">
                                OK
                            </button>
                        </form>
                    @elseif($module->etat == 'en_cours' && $module->activeIntervention)
                        <a href="{{ route('interventions.'.$module->activeIntervention->currentStep, $module->activeIntervention) }}" class="module-btn bg-amber-600 hover:bg-amber-700 text-white">
                            Voir
                        </a>
                    @endif
                @else
                    <!-- Si le module est occupé par un autre technicien -->
                    @if($module->activeIntervention)
                        <a href="{{ route('interventions.'.$module->activeIntervention->currentStep, $module->activeIntervention) }}" class="module-btn bg-blue-700 hover:bg-blue-800 text-white">
                            Voir
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>