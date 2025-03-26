@php
/**
 * REFONTE COMPLÈTE DE LA LOGIQUE DE DISPOSITION DES MODULES
 * 
 * Nouvelle approche : disposition_modules comme UNIQUE source de vérité
 * - Plus de fallbacks complexes
 * - Plus de vérifications multiples qui créent des incohérences
 * - Plus de dépendance aux dimensions
 */
function getModuleGridLayout($dalle) {
    // SIMPLIFICATION RADICALE
    // Nous utilisons UNIQUEMENT disposition_modules comme source de vérité
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
}
@endphp