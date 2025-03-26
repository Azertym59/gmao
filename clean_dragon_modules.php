<?php
/*
 * Script pour nettoyer les modules en trop sur les dalles Dragon
 */

// Charger l'application Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dalle;
use App\Models\Module;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

echo "Script de nettoyage des modules en trop pour les Dalles Dragon\n";
echo "--------------------------------------------------------------\n";

// Récupérer le produit Dragon
$dragonProduit = Produit::where('marque', 'ledtop')
                        ->where('modele', 'dragon')
                        ->first();

if (!$dragonProduit) {
    echo "Produit Dragon non trouvé dans la base de données.\n";
    exit(1);
}

echo "Produit Dragon trouvé: ID #{$dragonProduit->id}\n\n";

// Récupérer les dalles pour ce produit
$dalles = Dalle::where('produit_id', $dragonProduit->id)->get();
echo "Nombre de dalles Dragon: " . $dalles->count() . "\n\n";

$corrections = 0;
$erreurs = 0;

// Parcourir toutes les dalles Dragon
foreach ($dalles as $dalle) {
    // Calculer le nombre attendu de modules (2x2 = 4 modules)
    $nbModulesAttendus = 4;
    
    // Compter les modules existants
    $nbModulesExistants = $dalle->modules()->count();
    
    echo "Dalle #{$dalle->id} - Disposition: {$dalle->disposition_modules} - ";
    echo "Modules: {$nbModulesExistants}/{$nbModulesAttendus} - ";
    
    // Vérifier s'il y a trop de modules
    if ($nbModulesExistants > $nbModulesAttendus) {
        echo "Suppression de " . ($nbModulesExistants - $nbModulesAttendus) . " modules en trop... ";
        
        try {
            // Démarrer une transaction
            DB::beginTransaction();
            
            // Obtenir les modules qui dépassent la zone 2x2
            $modulesASupprimer = Module::where('dalle_id', $dalle->id)
                ->where(function ($query) {
                    $query->where('position_x', '>=', 2)  // Modules à partir de la colonne 2 (C, D, etc.)
                        ->orWhere('position_y', '>=', 2); // Modules à partir de la ligne 2 (3, 4, etc.)
                })
                ->get();
                
            if ($modulesASupprimer->count() > 0) {
                // Supprimer les modules identifiés
                foreach ($modulesASupprimer as $module) {
                    // Supprimer d'abord les interventions liées
                    foreach ($module->interventions as $intervention) {
                        if ($intervention->diagnostic) {
                            $intervention->diagnostic->delete();
                        }
                        if ($intervention->reparation) {
                            $intervention->reparation->delete();
                        }
                        $intervention->delete();
                    }
                    
                    // Puis supprimer le module
                    $module->delete();
                }
                
                echo "OK (" . $modulesASupprimer->count() . " modules supprimés)\n";
            } else {
                // Si aucun module n'a été identifié pour être supprimé, on doit en supprimer de manière arbitraire
                $modulesASupprimer = Module::where('dalle_id', $dalle->id)
                    ->orderBy('id', 'desc') // Supprimer les modules les plus récents (plus grands IDs)
                    ->limit($nbModulesExistants - $nbModulesAttendus)
                    ->get();
                
                foreach ($modulesASupprimer as $module) {
                    // Supprimer d'abord les interventions liées
                    foreach ($module->interventions as $intervention) {
                        if ($intervention->diagnostic) {
                            $intervention->diagnostic->delete();
                        }
                        if ($intervention->reparation) {
                            $intervention->reparation->delete();
                        }
                        $intervention->delete();
                    }
                    
                    // Puis supprimer le module
                    $module->delete();
                }
                
                echo "OK (" . $modulesASupprimer->count() . " modules supprimés, méthode arbitraire)\n";
            }
            
            // Valider la transaction
            DB::commit();
            $corrections++;
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();
            
            echo "ERREUR: " . $e->getMessage() . "\n";
            $erreurs++;
        }
    } else if ($nbModulesExistants < $nbModulesAttendus) {
        echo "ATTENTION: Pas assez de modules!\n";
    } else {
        echo "Nombre correct de modules, aucune modification nécessaire\n";
    }
}

echo "\n--------------------------------------------------------------\n";
echo "Résumé:\n";
echo "- {$corrections} dalles corrigées\n";
echo "- {$erreurs} erreurs rencontrées\n";
echo "--------------------------------------------------------------\n";
echo "Terminé! Les modules en trop ont été supprimés.\n";
echo "Pour que les changements prennent effet, redémarrez le service PHP-FPM ou le serveur de développement.\n";
?>