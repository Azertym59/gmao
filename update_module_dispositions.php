<?php
/*
 * Script de mise à jour des dispositions de modules
 * 
 * Ce script résout le problème d'incohérence d'affichage des modules
 * en mettant à jour tous les records existants pour utiliser 
 * disposition_modules comme source unique de vérité.
 */

// Charger l'application Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dalle;
use Illuminate\Database\Eloquent\Collection;

echo "Script de mise à jour des dispositions de modules\n";
echo "------------------------------------------------\n";

// Récupérer toutes les dalles
$dalles = Dalle::all();
echo "Nombre total de dalles: " . $dalles->count() . "\n\n";

$updates = 0;
$errors = 0;

// Parcourir toutes les dalles
foreach ($dalles as $dalle) {
    echo "Dalle ID: " . $dalle->id . " - ";
    
    try {
        // Récupérer le nombre de modules
        $nbModules = $dalle->modules->count();
        
        // Disposition actuelle
        $currentDisposition = $dalle->disposition_modules;
        echo "Disposition actuelle: " . ($currentDisposition ?: "Non définie") . " - ";
        
        // Calculer la nouvelle disposition en fonction du nombre de modules
        $newDisposition = null;
        
        // Si une disposition est déjà définie et au format valide, la conserver
        if (!empty($currentDisposition) && preg_match('/^\d+x\d+$/', $currentDisposition)) {
            $parts = explode('x', $currentDisposition);
            $columns = (int)$parts[0];
            $rows = (int)$parts[1];
            
            // Vérifier que la disposition correspond au nombre de modules
            if ($columns * $rows >= $nbModules) {
                $newDisposition = $currentDisposition;
                echo "Disposition valide conservée\n";
                continue; // Passer à la dalle suivante
            }
        }
        
        // Calculer une disposition basée sur le nombre de modules
        if ($nbModules <= 4) {
            $newDisposition = "2x2"; // Disposition par défaut
        } elseif ($nbModules <= 6) {
            $newDisposition = "3x2";
        } elseif ($nbModules <= 9) {
            $newDisposition = "3x3";
        } elseif ($nbModules <= 12) {
            $newDisposition = "4x3";
        } elseif ($nbModules <= 16) {
            $newDisposition = "4x4";
        } else {
            // Pour un plus grand nombre de modules, calculer la disposition optimale
            $sqrt = sqrt($nbModules);
            $columns = ceil($sqrt);
            $rows = ceil($nbModules / $columns);
            $newDisposition = $columns . "x" . $rows;
        }
        
        // Mettre à jour la dalle si la disposition a changé
        if ($newDisposition !== $currentDisposition) {
            $dalle->disposition_modules = $newDisposition;
            $dalle->save();
            $updates++;
            echo "Mise à jour vers: " . $newDisposition . "\n";
        } else {
            echo "Aucune mise à jour nécessaire\n";
        }
    } catch (\Exception $e) {
        $errors++;
        echo "ERREUR: " . $e->getMessage() . "\n";
    }
}

echo "\n------------------------------------------------\n";
echo "Résumé:\n";
echo "- Dalles analysées: " . $dalles->count() . "\n";
echo "- Mises à jour effectuées: " . $updates . "\n";
echo "- Erreurs: " . $errors . "\n";
echo "------------------------------------------------\n";

echo "\nTerminé! Tous les records de dalles ont été vérifiés.\n";
echo "Pour que les changements prennent effet, redémarrez le service PHP-FPM ou le serveur de développement.\n";
?>