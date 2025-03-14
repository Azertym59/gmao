<?php

namespace App\Services\Chantier;

use App\Models\Chantier;
use App\Models\Client;
use App\Models\Dalle;
use App\Models\Module;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChantierService
{
    /**
     * Crée un nouveau chantier avec tous ses produits, dalles et modules
     */
    public function createChantier(array $chantierData, array $produitsData): Chantier
    {
        DB::beginTransaction();
        
        try {
            // Création du chantier
            $chantier = Chantier::create([
                'nom' => $chantierData['nom'],
                'reference' => $chantierData['reference'],
                'adresse' => $chantierData['adresse'],
                'ville' => $chantierData['ville'],
                'code_postal' => $chantierData['code_postal'],
                'pays' => $chantierData['pays'],
                'client_id' => $chantierData['client_id'],
                'commentaire' => $chantierData['commentaire'] ?? null,
                'etat' => 'En préparation',
            ]);
            
            // Création des produits, dalles et modules
            foreach ($produitsData as $produitData) {
                $this->createProduitWithDallesAndModules($chantier, $produitData);
            }
            
            DB::commit();
            return $chantier;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du chantier: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Crée un produit avec ses dalles et modules pour un chantier donné
     */
    private function createProduitWithDallesAndModules(Chantier $chantier, array $produitData): Produit
    {
        // Création du produit
        $produit = Produit::create([
            'nom' => $produitData['nom'],
            'reference' => $produitData['reference'],
            'description' => $produitData['description'] ?? null,
            'chantier_id' => $chantier->id,
            'parent_id' => $produitData['parent_id'] ?? null,
            'est_variante' => $produitData['est_variante'] ?? false,
            'variante_nom' => $produitData['variante_nom'] ?? null,
            'etat' => 'En préparation',
        ]);
        
        // Création des dalles
        if (isset($produitData['dalles'])) {
            foreach ($produitData['dalles'] as $dalleData) {
                $this->createDalleWithModules($produit, $dalleData);
            }
        }
        
        return $produit;
    }
    
    /**
     * Crée une dalle avec ses modules pour un produit donné
     */
    private function createDalleWithModules(Produit $produit, array $dalleData): Dalle
    {
        // Création de la dalle
        $dalle = Dalle::create([
            'reference' => $dalleData['reference'],
            'largeur' => $dalleData['largeur'],
            'hauteur' => $dalleData['hauteur'],
            'produit_id' => $produit->id,
            'carte_reception' => $dalleData['carte_reception'] ?? null,
            'hub' => $dalleData['hub'] ?? null,
            'etat' => 'En préparation',
        ]);
        
        // Création des modules
        if (isset($dalleData['modules'])) {
            foreach ($dalleData['modules'] as $moduleData) {
                Module::create([
                    'reference' => $moduleData['reference'],
                    'type' => $moduleData['type'],
                    'largeur' => $moduleData['largeur'],
                    'hauteur' => $moduleData['hauteur'],
                    'dalle_id' => $dalle->id,
                    'position_x' => $moduleData['position_x'] ?? null,
                    'position_y' => $moduleData['position_y'] ?? null,
                    'etat' => 'En stock',
                ]);
            }
        }
        
        return $dalle;
    }
    
    /**
     * Met à jour l'état d'un chantier et de tous ses composants
     */
    public function updateChantierStatus(Chantier $chantier, string $newStatus): void
    {
        DB::beginTransaction();
        
        try {
            $chantier->etat = $newStatus;
            $chantier->save();
            
            // Met à jour l'état des produits
            foreach ($chantier->produits as $produit) {
                $produit->etat = $newStatus;
                $produit->save();
                
                // Met à jour l'état des dalles
                foreach ($produit->dalles as $dalle) {
                    $dalle->etat = $newStatus;
                    $dalle->save();
                    
                    // Met à jour l'état des modules si nécessaire
                    if ($newStatus === 'En préparation') {
                        foreach ($dalle->modules as $module) {
                            if ($module->etat === 'En stock') {
                                $module->etat = 'En préparation';
                                $module->save();
                            }
                        }
                    }
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour du statut du chantier: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Retourne les statistiques pour un chantier
     */
    public function getChantierStats(Chantier $chantier): array
    {
        $produits = $chantier->produits()->count();
        $dalles = Dalle::whereHas('produit', function ($query) use ($chantier) {
            $query->where('chantier_id', $chantier->id);
        })->count();
        
        $modules = Module::whereHas('dalle.produit', function ($query) use ($chantier) {
            $query->where('chantier_id', $chantier->id);
        })->count();
        
        $interventions = DB::table('interventions')
            ->join('modules', 'interventions.module_id', '=', 'modules.id')
            ->join('dalles', 'modules.dalle_id', '=', 'dalles.id')
            ->join('produits', 'dalles.produit_id', '=', 'produits.id')
            ->where('produits.chantier_id', $chantier->id)
            ->count();
        
        $modulesReparation = Module::whereHas('dalle.produit', function ($query) use ($chantier) {
            $query->where('chantier_id', $chantier->id);
        })->where('etat', 'En réparation')->count();
        
        $modulesTermines = Module::whereHas('dalle.produit', function ($query) use ($chantier) {
            $query->where('chantier_id', $chantier->id);
        })->where('etat', 'Terminé')->count();
        
        return [
            'produits' => $produits,
            'dalles' => $dalles,
            'modules' => $modules,
            'interventions' => $interventions,
            'modules_reparation' => $modulesReparation,
            'modules_termines' => $modulesTermines,
            'completion_percentage' => $modules > 0 ? round(($modulesTermines / $modules) * 100) : 0,
        ];
    }
}