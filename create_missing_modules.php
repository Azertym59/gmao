<?php
/*
 * Script pour créer les modules manquants selon la disposition
 */

// Charger l'application Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dalle;
use App\Models\Module;
use Illuminate\Support\Facades\DB;

echo "Script de création des modules manquants\n";
echo "----------------------------------------\n";

// Récupérer les dalles où le nombre de modules ne correspond pas à la disposition
$dalles = Dalle::all();
echo "Nombre total de dalles: " . $dalles->count() . "\n\n";

$corrections = 0;
$erreurs = 0;

// Parcourir toutes les dalles
foreach ($dalles as $dalle) {
    // Calculer le nombre attendu de modules
    $nbModulesAttendus = 4; // Valeur par défaut
    
    if (!empty($dalle->disposition_modules) && strpos($dalle->disposition_modules, 'x') !== false) {
        $parts = explode('x', $dalle->disposition_modules);
        if (count($parts) == 2) {
            $nbModulesAttendus = (int)$parts[0] * (int)$parts[1];
        }
    }
    
    // Compter les modules existants
    $nbModulesExistants = $dalle->modules()->count();
    
    echo "Dalle #{$dalle->id} - Disposition: {$dalle->disposition_modules} - ";
    echo "Modules: {$nbModulesExistants}/{$nbModulesAttendus} - ";
    
    // Vérifier s'il manque des modules
    if ($nbModulesExistants < $nbModulesAttendus) {
        echo "Création de " . ($nbModulesAttendus - $nbModulesExistants) . " modules manquants... ";
        
        try {
            // Extraire les dimensions depuis la disposition
            $colonnes = 2;
            $lignes = 2;
            
            if (!empty($dalle->disposition_modules) && strpos($dalle->disposition_modules, 'x') !== false) {
                $parts = explode('x', $dalle->disposition_modules);
                if (count($parts) == 2) {
                    $colonnes = (int)$parts[0];
                    $lignes = (int)$parts[1];
                }
            }
            
            // Calculer la taille de chaque module
            $moduleWidth = $dalle->largeur / $colonnes;
            $moduleHeight = $dalle->hauteur / $lignes;
            
            // Obtenir les positions existantes pour ne pas dupliquer
            $positionsExistantes = [];
            foreach ($dalle->modules as $module) {
                $positionsExistantes[$module->position_y][$module->position_x] = true;
            }
            
            // Démarrer une transaction
            DB::beginTransaction();
            
            // Créer les modules manquants
            $moduleCount = $nbModulesExistants;
            $modulesCreated = 0;
            
            for ($y = 0; $y < $lignes; $y++) {
                for ($x = 0; $x < $colonnes; $x++) {
                    // Vérifier si cette position existe déjà
                    if (isset($positionsExistantes[$y][$x])) {
                        continue; // Position déjà occupée, passer à la suivante
                    }
                    
                    $moduleCount++;
                    $positionLettre = chr(65 + $y) . ($x + 1); // A1, A2, B1, B2, etc.
                    
                    // Créer le module
                    Module::create([
                        'dalle_id' => $dalle->id,
                        'largeur' => $moduleWidth,
                        'hauteur' => $moduleHeight,
                        'nb_pixels_largeur' => 100, // Par défaut
                        'nb_pixels_hauteur' => 100, // Par défaut
                        'etat' => 'non_commence', // État par défaut pour les nouveaux modules
                        'reference_module' => $dalle->reference_dalle . '-M' . $moduleCount,
                        'position_lettre' => $positionLettre,
                        'position_x' => $x,
                        'position_y' => $y
                    ]);
                    
                    $modulesCreated++;
                    
                    // Si on a créé tous les modules manquants, sortir des boucles
                    if ($modulesCreated >= ($nbModulesAttendus - $nbModulesExistants)) {
                        break 2;
                    }
                }
            }
            
            // Valider la transaction
            DB::commit();
            
            echo "OK ({$modulesCreated} modules créés)\n";
            $corrections++;
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();
            
            echo "ERREUR: " . $e->getMessage() . "\n";
            $erreurs++;
        }
    } else if ($nbModulesExistants > $nbModulesAttendus) {
        echo "ATTENTION: Trop de modules ({$nbModulesExistants}) pour cette disposition!\n";
    } else {
        echo "Nombre correct de modules, aucune modification nécessaire\n";
    }
}

echo "\n----------------------------------------\n";
echo "Résumé:\n";
echo "- {$corrections} dalles corrigées\n";
echo "- {$erreurs} erreurs rencontrées\n";
echo "----------------------------------------\n";
echo "Terminé! Les modules manquants ont été créés.\n";
echo "Pour que les changements prennent effet, redémarrez le service PHP-FPM ou le serveur de développement.\n";
?>