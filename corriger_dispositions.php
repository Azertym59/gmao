<?php
/*
 * Script de correction des dispositions de modules
 * 
 * Ce script assure que toutes les dalles utilisent la bonne valeur
 * dans disposition_modules en fonction du nombre réel de modules.
 */

// Charger l'application Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dalle;
use Illuminate\Database\Eloquent\Collection;

echo "Script de correction des dispositions de modules\n";
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
        echo "Modules: " . $nbModules . " - ";
        
        // Disposition actuelle
        $currentDisposition = $dalle->disposition_modules;
        echo "Disposition actuelle: " . ($currentDisposition ?: "Non définie") . " - ";
        
        // Déterminer la disposition correcte basée sur le nombre de modules
        $newDisposition = null;
        
        // Calculer la disposition appropriée
        if ($nbModules == 4) {
            $newDisposition = "2x2"; // La bonne disposition pour 4 modules
        } elseif ($nbModules == 8) {
            $newDisposition = "2x4"; // La bonne disposition pour 8 modules
        } elseif ($nbModules == 6) {
            $newDisposition = "3x2";
        } elseif ($nbModules == 9) {
            $newDisposition = "3x3";
        } elseif ($nbModules == 12) {
            $newDisposition = "4x3";
        } elseif ($nbModules == 16) {
            $newDisposition = "4x4";
        } else {
            // Pour un autre nombre de modules, calculer la disposition optimale
            $sqrt = sqrt($nbModules);
            $columns = ceil($sqrt);
            $rows = ceil($nbModules / $columns);
            $newDisposition = $columns . "x" . $rows;
        }
        
        // Mettre à jour seulement si la disposition est différente
        if ($newDisposition !== $currentDisposition) {
            $dalle->disposition_modules = $newDisposition;
            $dalle->save();
            $updates++;
            echo "Mise à jour vers: " . $newDisposition . "\n";
        } else {
            echo "Disposition correcte (inchangée)\n";
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

echo "\nTerminé! Toutes les dispositions de modules ont été vérifiées et corrigées si nécessaire.\n";
echo "Pour que les changements prennent effet, redémarrez le service PHP-FPM ou le serveur de développement.\n";
