<?php

namespace App\Repositories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ModuleRepository
{
    /**
     * Récupère un module par son ID
     */
    public function findById(int $id): ?Module
    {
        return Module::with(['dalle.produit.chantier.client', 'interventions'])->find($id);
    }
    
    /**
     * Récupère tous les modules
     */
    public function getAll(): Collection
    {
        return Module::with(['dalle.produit.chantier'])->get();
    }
    
    /**
     * Récupère les modules paginés
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Module::with(['dalle.produit.chantier'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
    
    /**
     * Recherche des modules par référence
     */
    public function searchByReference(string $reference): Collection
    {
        return Module::with(['dalle.produit.chantier'])
            ->where('reference', 'like', "%{$reference}%")
            ->get();
    }
    
    /**
     * Récupère les modules par état
     */
    public function getByState(string $state): Collection
    {
        return Module::with(['dalle.produit.chantier'])
            ->where('etat', $state)
            ->get();
    }
    
    /**
     * Récupère les modules d'un chantier
     */
    public function getByChantier(int $chantierId): Collection
    {
        return Module::with(['dalle.produit.chantier'])
            ->whereHas('dalle.produit.chantier', function ($query) use ($chantierId) {
                $query->where('id', $chantierId);
            })
            ->get();
    }
    
    /**
     * Récupère les modules par dalle
     */
    public function getByDalle(int $dalleId): Collection
    {
        return Module::with(['dalle.produit.chantier'])
            ->where('dalle_id', $dalleId)
            ->orderBy('position_y')
            ->orderBy('position_x')
            ->get();
    }
    
    /**
     * Récupère les modules nécessitant une intervention
     */
    public function getModulesNeedingIntervention(): Collection
    {
        return Module::with(['dalle.produit.chantier'])
            ->where('etat', 'En diagnostic')
            ->orWhere('etat', 'En réparation')
            ->get();
    }
    
    /**
     * Récupère les statistiques des modules par état
     */
    public function getModuleStats(): array
    {
        $stats = [
            'total' => 0,
            'en_stock' => 0,
            'en_preparation' => 0,
            'en_diagnostic' => 0,
            'en_reparation' => 0,
            'termines' => 0,
            'defectueux' => 0,
        ];
        
        $results = Module::selectRaw('etat, count(*) as count')
            ->groupBy('etat')
            ->get();
        
        $total = 0;
        
        foreach ($results as $result) {
            $total += $result->count;
            
            switch ($result->etat) {
                case 'En stock':
                    $stats['en_stock'] = $result->count;
                    break;
                case 'En préparation':
                    $stats['en_preparation'] = $result->count;
                    break;
                case 'En diagnostic':
                    $stats['en_diagnostic'] = $result->count;
                    break;
                case 'En réparation':
                    $stats['en_reparation'] = $result->count;
                    break;
                case 'Terminé':
                    $stats['termines'] = $result->count;
                    break;
                case 'Défectueux':
                    $stats['defectueux'] = $result->count;
                    break;
            }
        }
        
        $stats['total'] = $total;
        
        return $stats;
    }
}