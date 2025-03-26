<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dalle;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class UpdateFlightcasesCommand extends Command
{
    protected $signature = 'flightcases:update {--produit=} {--chantier=}';
    protected $description = 'Met à jour les informations de flightcase pour toutes les dalles';

    public function handle()
    {
        // Début de la transaction pour assurer l'intégrité des données
        DB::beginTransaction();

        try {
            $produitId = $this->option('produit');
            $chantierRef = $this->option('chantier');
            
            $this->info("Début de la mise à jour des flightcases" . 
                ($produitId ? " (Produit ID: $produitId)" : "") . 
                ($chantierRef ? " (Chantier: $chantierRef)" : ""));
            
            // Traiter toutes les dalles avec une référence mais sans flightcase_number
            $query = Dalle::whereNotNull('reference_dalle')
                          ->whereNull('flightcase_number');
            
            // Filtrer par produit si spécifié
            if ($produitId) {
                $query->where('produit_id', $produitId);
            }
            
            // Filtrer par chantier si spécifié
            if ($chantierRef) {
                $query->whereHas('produit', function ($q) use ($chantierRef) {
                    $q->whereHas('chantier', function ($q) use ($chantierRef) {
                        $q->where('reference', $chantierRef);
                    });
                });
            }
            
            $dalles = $query->get();
            
            $updated = 0;
            $patterns = 0;
            
            $this->info("Mise à jour des flightcases pour " . $dalles->count() . " dalles...");
            
            foreach ($dalles as $dalle) {
                $ref = $dalle->reference_dalle;
                $fcNumber = null;
                $dalleNumber = null;
                $isPartial = false;
                
                // Motif 1: FCx-Dy (ex: FC1-D2)
                if (preg_match('/^([A-Z0-9]+)(\d+)[- _]?D(\d+)$/i', $ref, $matches)) {
                    $prefix = $matches[1];
                    $fcNumber = $matches[2];
                    $dalleNumber = $matches[3];
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
                // Motif 4: Recherche de "partiel" ou "partial" pour les flightcases partiels
                if (preg_match('/(partiel|partial)/i', $ref)) {
                    $isPartial = true;
                }
                
                // Si on a identifié un flightcase, mettre à jour la dalle
                if ($fcNumber !== null) {
                    $dalle->flightcase_number = $fcNumber;
                    if ($dalleNumber !== null) {
                        $dalle->dalle_number = $dalleNumber;
                    }
                    $dalle->flightcase_partial = $isPartial;
                    $dalle->save();
                    $updated++;
                }
            }
            
            // Analyser les produits pour organiser les dalles sans référence mais groupées par produit
            $query = Produit::whereHas('dalles', function ($q) {
                $q->whereNull('flightcase_number');
            });
            
            // Filtrer par produit ou chantier si spécifié
            if ($produitId) {
                $query->where('id', $produitId);
            }
            
            if ($chantierRef) {
                $query->whereHas('chantier', function ($q) use ($chantierRef) {
                    $q->where('reference', $chantierRef);
                });
            }
            
            $produits = $query->get();
            
            $autoGrouped = 0;
            
            $this->info("Analyse de {$produits->count()} produits pour groupement automatique...");
            
            foreach ($produits as $produit) {
                $dallesSansFc = Dalle::where('produit_id', $produit->id)
                               ->whereNull('flightcase_number')
                               ->get();
                
                if ($dallesSansFc->isEmpty()) {
                    continue;
                }
                
                $this->comment("Produit {$produit->id}: {$produit->marque} {$produit->modele} - {$dallesSansFc->count()} dalles sans flightcase");
                
                // Déterminer la taille maximale de flightcase
                $maxDallesPerFc = 8; // Valeur par défaut
                
                if ($produit->produitCatalogue && $produit->produitCatalogue->flightcase_max_dalles) {
                    $maxDallesPerFc = $produit->produitCatalogue->flightcase_max_dalles;
                }
                
                // Calculer le nombre de flightcases nécessaires
                $fcCount = ceil($dallesSansFc->count() / $maxDallesPerFc);
                
                // Préfixe pour les références de flightcase
                $fcPrefix = 'FC';
                if ($produit->reference) {
                    $prefix = substr(preg_replace('/[^A-Z0-9]/i', '', $produit->reference), 0, 3);
                    if (!empty($prefix)) {
                        $fcPrefix = $prefix;
                    }
                }
                
                $dalleIndex = 0;
                
                // Créer les flightcases
                for ($fcNumber = 1; $fcNumber <= $fcCount; $fcNumber++) {
                    // Déterminer si ce flightcase est partiel
                    $isPartial = ($fcNumber == $fcCount && ($dallesSansFc->count() % $maxDallesPerFc) != 0);
                    
                    // Nombre de dalles dans ce flightcase
                    $dallesInThisFc = ($isPartial) ? ($dallesSansFc->count() % $maxDallesPerFc) : $maxDallesPerFc;
                    
                    $this->line("  - Flightcase #{$fcNumber}: {$dallesInThisFc} dalles" . ($isPartial ? " (partiel)" : ""));
                    
                    // Attribuer les dalles à ce flightcase
                    for ($dalleInFc = 1; $dalleInFc <= $dallesInThisFc; $dalleInFc++) {
                        if ($dalleIndex >= $dallesSansFc->count()) {
                            break;
                        }
                        
                        $dalle = $dallesSansFc[$dalleIndex];
                        $dalle->flightcase_number = $fcNumber;
                        $dalle->dalle_number = $dalleInFc;
                        $dalle->reference_dalle = "{$fcPrefix}{$fcNumber}-D{$dalleInFc}";
                        $dalle->flightcase_partial = $isPartial;
                        $dalle->save();
                        
                        $dalleIndex++;
                        $autoGrouped++;
                    }
                }
            }
            
            DB::commit();
            
            $this->info("Mise à jour terminée.");
            $this->info("- $updated dalles mises à jour à partir de leurs références");
            $this->info("- $patterns motifs de référence reconnus");
            $this->info("- $autoGrouped dalles automatiquement groupées par produit");
            
            return 0;
            
        } catch (Exception $e) {
            DB::rollBack();
            $this->error("Erreur: " . $e->getMessage());
            return 1;
        }
    }
}
