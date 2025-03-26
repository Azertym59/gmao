{{-- 
COMPOSANT SIMPLIFIÉ DE GRILLE DE MODULES
Utilise désormais uniquement disposition_modules comme source de vérité
--}}
@php
// Utiliser le grid helper pour obtenir la disposition
include(resource_path('views/components/module/grid-helper.blade.php'));
$grid = getModuleGridLayout($dalle);
$nbColonnes = $grid['colonnes'];
$nbLignes = $grid['lignes'];

// Créer une grille pour les modules
$modules = $dalle->modules->all();
$grille = [];

// Remplir la grille dans l'ordre ligne par ligne
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
@endphp

<div class="flex justify-center">
    <div class="grid max-w-xs mx-auto" style="grid-template-columns: repeat({{ $nbColonnes }}, minmax(0, 1fr)); gap: 0.25rem;">
        @for ($y = 1; $y <= $nbLignes; $y++)
            @for ($x = 1; $x <= $nbColonnes; $x++)
                @if (isset($grille[$y][$x]))
                    @php $module = $grille[$y][$x]; @endphp
                    @include('components.module.grid-item', ['module' => $module, 'x' => $x, 'y' => $y])
                @else
                    <div class="aspect-square w-10 h-10 bg-gray-800/50 rounded-sm border border-gray-700" title="Emplacement vide">
                        <div class="flex items-center justify-center h-full text-xs text-gray-600">
                            {{ $x }},{{ $y }}
                        </div>
                    </div>
                @endif
            @endfor
        @endfor
    </div>
</div>