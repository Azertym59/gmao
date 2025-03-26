<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Chantier;
use App\Models\Dalle;
use Illuminate\Support\Facades\DB;

class OrganizeFlightcasesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flightcases:organize 
                            {chantier : Référence du chantier (ex: TCA-2503-003)}
                            {--dalles-per-fc=8 : Nombre de dalles par flightcase}
                            {--produit= : ID du produit spécifique (facultatif)}
                            {--force : Force la réorganisation même si les références existent déjà}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Organise les dalles d\'un chantier en flightcases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $chantierRef = $this->argument('chantier');
        $dallesPerFc = $this->option('dalles-per-fc');
        $produitId = $this->option('produit');
        $force = $this->option('force');
        
        $chantier = Chantier::where('reference', $chantierRef)->first();
        
        if (!$chantier) {
            $this->error("Le chantier avec la référence '$chantierRef' n'a pas été trouvé.");
            return 1;
        }
        
        $this->info("Organisation des flightcases pour le chantier {$chantier->reference} ({$chantier->nom})");
        
        DB::beginTransaction();
        
        try {
            foreach ($chantier->produits as $produit) {
                if ($produitId && $produit->id != $produitId) {
                    $this->comment("Skipping produit ID {$produit->id} (filtre actif)");
                    continue;
                }
                
                $this->info("Traitement du produit {$produit->marque} {$produit->modele} (ID: {$produit->id})");
                
                // Récupérer les dalles du produit (qui n'ont pas encore de flightcase, sauf si force)
                $query = $produit->dalles();
                if (!$force) {
                    $query->whereNull('flightcase_number');
                }
                $dalles = $query->orderBy('id')->get();
                
                if ($dalles->isEmpty()) {
                    $this->warn("Aucune dalle à organiser pour ce produit.");
                    continue;
                }
                
                $this->info("Trouvé {$dalles->count()} dalles à organiser");
                
                // Vérifier si le produit catalogue a une information sur la taille de flightcase maximale
                $maxDallesPerFc = $dallesPerFc; // Valeur par défaut
                
                if ($produit->produitCatalogue && $produit->produitCatalogue->flightcase_max_dalles) {
                    $maxDallesPerFc = $produit->produitCatalogue->flightcase_max_dalles;
                    $this->comment("Utilisation de la configuration du catalogue: {$maxDallesPerFc} dalles maximum par flightcase");
                }
                
                // Calculer le nombre de flightcases nécessaires
                $fcCount = ceil($dalles->count() / $maxDallesPerFc);
                $this->info("Création de $fcCount flightcases avec $maxDallesPerFc dalles maximum chacun");
                
                $bar = $this->output->createProgressBar($dalles->count());
                $bar->start();
                
                $currentDalle = 0;
                
                // Organiser les dalles en flightcases
                for ($fcNumber = 1; $fcNumber <= $fcCount; $fcNumber++) {
                    // Pour le dernier flightcase, vérifier s'il est partiel
                    $isPartial = ($fcNumber == $fcCount && ($dalles->count() % $maxDallesPerFc) != 0);
                    
                    // Déterminer combien de dalles vont dans ce flightcase
                    $dallesInThisFc = ($isPartial) ? ($dalles->count() % $maxDallesPerFc) : $maxDallesPerFc;
                    
                    $this->comment("  - FlightCase #{$fcNumber}: {$dallesInThisFc} dalles" . ($isPartial ? " (partiel)" : ""));
                    
                    for ($dalleInFc = 1; $dalleInFc <= $dallesInThisFc; $dalleInFc++) {
                        if ($currentDalle >= $dalles->count()) {
                            break;
                        }
                        
                        $dalle = $dalles[$currentDalle];
                        $dalle->flightcase_number = $fcNumber;
                        $dalle->dalle_number = $dalleInFc;
                        
                        // Améliorer la génération de référence
                        $fcPrefix = 'FC';
                        if ($produit->reference) {
                            // Utiliser les 3 premiers caractères de la référence du produit comme préfixe
                            $prefix = substr(preg_replace('/[^A-Z0-9]/i', '', $produit->reference), 0, 3);
                            if (!empty($prefix)) {
                                $fcPrefix = $prefix;
                            }
                        }
                        
                        $dalle->reference_dalle = "{$fcPrefix}{$fcNumber}-D{$dalleInFc}";
                        
                        // Marquer les dalles des flightcases partiels
                        if ($isPartial) {
                            $dalle->flightcase_partial = true;
                        }
                        
                        $dalle->save();
                        
                        $currentDalle++;
                        $bar->advance();
                    }
                }
                
                $bar->finish();
                $this->newLine();
                $this->info("Organisation des flightcases terminée pour ce produit");
            }
            
            DB::commit();
            $this->newLine();
            $this->info("Organisation des flightcases terminée avec succès pour le chantier {$chantier->reference}");
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Une erreur s'est produite : " . $e->getMessage());
            return 1;
        }
    }
}
