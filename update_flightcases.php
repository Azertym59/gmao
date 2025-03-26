<?php

// Script d'utilitaire pour mettre à jour les flightcases dans tous les chantiers
// Exécuter avec: php update_flightcases.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dalle;
use Illuminate\Support\Facades\DB;

// Début de la transaction pour assurer l'intégrité des données
DB::beginTransaction();

try {
    // Traiter toutes les dalles avec une référence mais sans flightcase_number
    $dalles = Dalle::whereNotNull('reference_dalle')
                    ->whereNull('flightcase_number')
                    ->get();
    
    $updated = 0;
    $patterns = 0;
    
    echo "Mise à jour des flightcases pour " . $dalles->count() . " dalles...\n";
    
    foreach ($dalles as $dalle) {
        $ref = $dalle->reference_dalle;
        $fcNumber = null;
        $dalleNumber = null;
        
        // Motif 1: FCx-Dy (ex: FC1-D2)
        if (preg_match('/^FC(\d+)[- _]?D(\d+)$/i', $ref, $matches)) {
            $fcNumber = $matches[1];
            $dalleNumber = $matches[2];
            $patterns++;
        } 
        // Motif 2: FCx quelque part dans la référence
        elseif (preg_match('/FC[- _]?(\d+)/i', $ref, $matches)) {
            $fcNumber = $matches[1];
            
            // Essayer de trouver un numéro de dalle
            if (preg_match('/[- _]?D(\d+)/i', $ref, $matchesDalle)) {
                $dalleNumber = $matchesDalle[1];
            }
            $patterns++;
        }
        // Motif 3: Numéro FC en premier
        elseif (preg_match('/^(\d+)[- _](FC|FLIGHTCASE|FLIGHT)/i', $ref, $matches)) {
            $fcNumber = $matches[1];
            $patterns++;
        }
        
        // Si on a identifié un flightcase, mettre à jour la dalle
        if ($fcNumber !== null) {
            $dalle->flightcase_number = $fcNumber;
            if ($dalleNumber !== null) {
                $dalle->dalle_number = $dalleNumber;
            }
            $dalle->save();
            $updated++;
        }
    }
    
    // Mettre à jour les dalles sans référence mais groupées par produit
    // Logique: si un produit a moins de 9 dalles, probablement un seul FlightCase
    $produits = DB::table('produits')
                    ->select('id')
                    ->get();
    
    $autoGrouped = 0;
    
    foreach ($produits as $produit) {
        $dallesSansFc = Dalle::where('produit_id', $produit->id)
                           ->whereNull('flightcase_number')
                           ->whereNull('reference_dalle')
                           ->get();
        
        // Si on a entre 2 et 8 dalles, c'est probablement un flightcase
        if ($dallesSansFc->count() >= 2 && $dallesSansFc->count() <= 8) {
            $fcNumber = 'AUTO' . $produit->id; // Créer un numéro de FC unique basé sur le produit
            $i = 1;
            
            foreach ($dallesSansFc as $dalle) {
                $dalle->flightcase_number = $fcNumber;
                $dalle->dalle_number = $i++;
                $dalle->reference_dalle = "FC{$fcNumber}-D" . ($i-1);
                $dalle->save();
                $autoGrouped++;
            }
        }
    }
    
    DB::commit();
    
    echo "Mise à jour terminée.\n";
    echo "- $updated dalles mises à jour à partir de leurs références\n";
    echo "- $patterns motifs de référence reconnus\n";
    echo "- $autoGrouped dalles automatiquement groupées par produit\n";
    
} catch (Exception $e) {
    DB::rollBack();
    echo "Erreur: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Succès! Les flightcases ont été mis à jour dans la base de données.\n";
