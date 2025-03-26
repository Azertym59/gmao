<?php
/*
 * Script de correction des dispositions 2x4 incorrectes
 * 
 * Ce script corrige spécifiquement le problème des dalles
 * configurées comme 2x4 alors qu'elles devraient être 2x2
 */

// Charger l'application Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dalle;
use Illuminate\Database\Eloquent\Collection;

echo "Script de correction des dispositions 2x4 incorrectes\n";
echo "------------------------------------------------\n";

// Récupérer toutes les dalles avec disposition 2x4
$dalles = Dalle::where('disposition_modules', '2x4')->get();
echo "Nombre de dalles avec disposition 2x4: " . $dalles->count() . "\n\n";

$updates = 0;
$errors = 0;

// Parcourir toutes les dalles
foreach ($dalles as $dalle) {
    echo "Dalle ID: " . $dalle->id . " - ";
    
    try {
        // Récupérer le nombre de modules
        $nbModules = $dalle->modules->count();
        echo "Modules: " . $nbModules . " - ";
        
        // Corriger les dalles qui ont 4 modules mais sont configurées comme 2x4
        if ($nbModules == 4) {
            $dalle->disposition_modules = "2x2";
            $dalle->save();
            $updates++;
            echo "Corrigé de 2x4 vers 2x2\n";
        } else {
            echo "Maintenu en 2x4 (correct pour ce nombre de modules)\n";
        }
    } catch (\Exception $e) {
        $errors++;
        echo "ERREUR: " . $e->getMessage() . "\n";
    }
}

echo "\n------------------------------------------------\n";
echo "Résumé:\n";
echo "- Dalles analysées: " . $dalles->count() . "\n";
echo "- Corrections effectuées: " . $updates . "\n";
echo "- Erreurs: " . $errors . "\n";
echo "------------------------------------------------\n";

echo "\nTerminé! Toutes les dalles 2x4 incorrectes ont été corrigées.\n";
echo "Pour que les changements prennent effet, redémarrez le service PHP-FPM ou le serveur de développement.\n";
