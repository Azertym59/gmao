<?php
/*
 * Script pour vérifier le nombre de modules par dalle
 */

// Charger l'application Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dalle;
use App\Models\Produit;
use App\Models\ProduitCatalogue;

echo "Vérification du nombre de modules par dalle\n";
echo "------------------------------------------------\n";

// Récupérer toutes les dalles
$dalles = Dalle::all();
echo "Nombre total de dalles: " . $dalles->count() . "\n\n";

echo "| ID dalle | Disposition | Nb Modules | Colonnes x Lignes | Modules calculés |\n";
echo "|----------|-------------|------------|-------------------|------------------|\n";

// Parcourir toutes les dalles
foreach ($dalles as $dalle) {
    $nbModules = $dalle->modules()->count();
    $disposition = $dalle->disposition_modules;
    
    // Extraire colonnes et lignes de la disposition
    $colonnes = 2;
    $lignes = 2;
    $modulesCalcules = 4;
    
    if (!empty($disposition) && strpos($disposition, 'x') !== false) {
        $parts = explode('x', $disposition);
        if (count($parts) == 2) {
            $colonnes = (int)$parts[0];
            $lignes = (int)$parts[1];
            $modulesCalcules = $colonnes * $lignes;
        }
    }
    
    echo "| {$dalle->id} | {$disposition} | {$nbModules} | {$colonnes}x{$lignes} | {$modulesCalcules} |\n";
}

echo "\n";

// Vérifier les produits catalogue
echo "Vérification des produits du catalogue\n";
echo "------------------------------------------------\n";

$produitsCatalogue = ProduitCatalogue::all();
echo "Nombre total de produits catalogue: " . $produitsCatalogue->count() . "\n\n";

echo "| ID | Marque | Modèle | Disposition |\n";
echo "|-------|----------|----------|------------|\n";

foreach ($produitsCatalogue as $produitCat) {
    echo "| {$produitCat->id} | {$produitCat->marque} | {$produitCat->modele} | {$produitCat->disposition_modules} |\n";
}

echo "\n";

// Vérifier les produits
echo "Vérification des produits\n";
echo "------------------------------------------------\n";

$produits = Produit::all();
echo "Nombre total de produits: " . $produits->count() . "\n\n";

echo "| ID | Produit | Catalogue ID | Disposition Produit | Disposition Catalogue |\n";
echo "|-------|----------|------------|-------------------|----------------------|\n";

foreach ($produits as $produit) {
    $catalogueDisposition = "";
    if ($produit->produit_catalogue_id) {
        $catalogueProduit = ProduitCatalogue::find($produit->produit_catalogue_id);
        if ($catalogueProduit) {
            $catalogueDisposition = $catalogueProduit->disposition_modules;
        }
    }
    
    echo "| {$produit->id} | {$produit->marque} {$produit->modele} | {$produit->produit_catalogue_id} | {$produit->disposition_modules} | {$catalogueDisposition} |\n";
}

echo "\nTerminé!\n";