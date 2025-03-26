<?php
/*
 * Script de correction des dispositions pour les produits Phoenix
 */

// Charger l'application Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dalle;
use App\Models\Produit;
use App\Models\ProduitCatalogue;

echo "Script de correction des dispositions pour les produits Phoenix\n";
echo "------------------------------------------------\n";

// 1. Corriger les produits catalogue
$phoenixCatalogue = ProduitCatalogue::where('marque', 'ledtop')->where('modele', 'phoenix 2')->first();
if ($phoenixCatalogue) {
    echo "Mise à jour du produit catalogue Phoenix...\n";
    $phoenixCatalogue->disposition_modules = '4x4';
    $phoenixCatalogue->save();
    echo "Produit catalogue Phoenix mis à jour avec disposition 4x4\n\n";
} else {
    echo "Produit catalogue Phoenix non trouvé dans la base\n\n";
}

// 2. Corriger les produits Phoenix
$phoenixProduits = Produit::where('marque', 'ledtop')
                        ->where(function($query) {
                            $query->where('modele', 'like', 'phoenix%')
                                  ->orWhere('modele', 'like', 'Phoenix%');
                        })->get();

echo "Nombre de produits Phoenix trouvés: " . $phoenixProduits->count() . "\n";

foreach ($phoenixProduits as $produit) {
    echo "Mise à jour du produit Phoenix {$produit->id} ({$produit->marque} {$produit->modele})...\n";
    $produit->disposition_modules = '4x4';
    $produit->save();
    
    // Mettre à jour toutes les dalles associées à ce produit
    $dalles = Dalle::where('produit_id', $produit->id)->get();
    
    echo "Nombre de dalles associées à ce produit: " . $dalles->count() . "\n";
    
    foreach ($dalles as $dalle) {
        echo "  - Mise à jour de la dalle {$dalle->id}...\n";
        
        // Mettre à jour la disposition
        $dalle->disposition_modules = '4x4';
        $dalle->nb_colonnes = 4;
        $dalle->nb_lignes = 4;
        $dalle->nb_modules = 16;
        $dalle->save();
        
        // Vérifier que le nombre de modules correspond bien à la disposition
        $nbModules = $dalle->modules()->count();
        if ($nbModules != 16) {
            echo "    ATTENTION: La dalle a {$nbModules} modules au lieu de 16!\n";
            
            // Recréer les modules manquants si nécessaire
            if ($nbModules < 16) {
                echo "    Création de " . (16 - $nbModules) . " modules manquants...\n";
                
                // Logique de création de modules
                // Cette partie est complexe et dépend de votre logique métier
                // On devrait idéalement utiliser le même code que celui dans ChantierController::createModulesForDalle
            }
        }
    }
    
    echo "Produit Phoenix et ses dalles mis à jour\n";
}

// 3. Corriger spécifiquement les produits Dragon
$dragonProduits = Produit::where('marque', 'ledtop')
                        ->where(function($query) {
                            $query->where('modele', 'like', 'dragon%')
                                  ->orWhere('modele', 'like', 'Dragon%');
                        })->get();

echo "\nNombre de produits Dragon trouvés: " . $dragonProduits->count() . "\n";

foreach ($dragonProduits as $produit) {
    echo "Vérification du produit Dragon {$produit->id} ({$produit->marque} {$produit->modele})...\n";
    
    if ($produit->disposition_modules != '2x2') {
        echo "Mise à jour du produit Dragon pour définir la disposition 2x2...\n";
        $produit->disposition_modules = '2x2';
        $produit->save();
    }
    
    // Vérifier toutes les dalles associées
    $dalles = Dalle::where('produit_id', $produit->id)->get();
    
    echo "Nombre de dalles associées à ce produit: " . $dalles->count() . "\n";
    
    foreach ($dalles as $dalle) {
        if ($dalle->disposition_modules != '2x2') {
            echo "  - Mise à jour de la dalle {$dalle->id} vers 2x2...\n";
            
            // Mettre à jour la disposition
            $dalle->disposition_modules = '2x2';
            $dalle->nb_colonnes = 2;
            $dalle->nb_lignes = 2;
            $dalle->nb_modules = 4;
            $dalle->save();
        }
    }
}

echo "\n------------------------------------------------\n";
echo "Terminé! Les dispositions ont été corrigées.\n";
echo "Pour que les changements prennent effet, redémarrez le service PHP-FPM ou le serveur de développement.\n";
?>