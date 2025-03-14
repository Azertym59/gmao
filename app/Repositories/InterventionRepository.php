<?php

namespace App\Repositories;

use App\Models\Intervention;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InterventionRepository
{
    /**
     * Récupère une intervention par son ID
     */
    public function findById(int $id): ?Intervention
    {
        return Intervention::with([
            'module.dalle.produit.chantier.client',
            'technicien',
            'diagnostic',
            'reparation'
        ])->find($id);
    }
    
    /**
     * Récupère toutes les interventions
     */
    public function getAll(): Collection
    {
        return Intervention::with([
            'module.dalle.produit.chantier',
            'technicien'
        ])->orderBy('created_at', 'desc')->get();
    }
    
    /**
     * Récupère les interventions paginées
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Intervention::with([
            'module.dalle.produit.chantier',
            'technicien'
        ])->orderBy('created_at', 'desc')->paginate($perPage);
    }
    
    /**
     * Récupère les interventions d'un technicien
     */
    public function getByTechnicien(int $technicienId = null): Collection
    {
        $technicienId = $technicienId ?? Auth::id();
        
        return Intervention::with([
            'module.dalle.produit.chantier',
            'diagnostic',
            'reparation'
        ])
        ->where('technicien_id', $technicienId)
        ->orderBy('created_at', 'desc')
        ->get();
    }
    
    /**
     * Récupère les interventions en cours
     */
    public function getEnCours(): Collection
    {
        return Intervention::with([
            'module.dalle.produit.chantier',
            'technicien'
        ])
        ->where('etat', 'En cours')
        ->orderBy('date_debut', 'desc')
        ->get();
    }
    
    /**
     * Récupère les interventions d'un chantier
     */
    public function getByChantier(int $chantierId): Collection
    {
        return Intervention::with([
            'module.dalle.produit.chantier',
            'technicien',
            'diagnostic',
            'reparation'
        ])
        ->whereHas('module.dalle.produit.chantier', function ($query) use ($chantierId) {
            $query->where('id', $chantierId);
        })
        ->orderBy('created_at', 'desc')
        ->get();
    }
    
    /**
     * Récupère les interventions par état
     */
    public function getByState(string $state): Collection
    {
        return Intervention::with([
            'module.dalle.produit.chantier',
            'technicien'
        ])
        ->where('etat', $state)
        ->orderBy('created_at', 'desc')
        ->get();
    }
    
    /**
     * Récupère les statistiques d'intervention
     */
    public function getStats(): array
    {
        $total = Intervention::count();
        $enCours = Intervention::where('etat', 'En cours')->count();
        $diagnosticTermine = Intervention::where('etat', 'Diagnostic terminé')->count();
        $terminees = Intervention::where('etat', 'Terminée')->count();
        
        $tempsMoyen = Intervention::where('etat', 'Terminée')
            ->where('temps_total', '>', 0)
            ->avg('temps_total') ?? 0;
        
        $reparationsReussies = Intervention::whereHas('reparation', function ($query) {
            $query->where('resultat', 'Réparé');
        })->count();
        
        $tauxReussite = $terminees > 0 ? round(($reparationsReussies / $terminees) * 100) : 0;
        
        $interventionsParChantier = DB::table('interventions')
            ->join('modules', 'interventions.module_id', '=', 'modules.id')
            ->join('dalles', 'modules.dalle_id', '=', 'dalles.id')
            ->join('produits', 'dalles.produit_id', '=', 'produits.id')
            ->join('chantiers', 'produits.chantier_id', '=', 'chantiers.id')
            ->select('chantiers.nom', DB::raw('count(*) as total'))
            ->groupBy('chantiers.nom')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        return [
            'total' => $total,
            'en_cours' => $enCours,
            'diagnostic_termine' => $diagnosticTermine,
            'terminees' => $terminees,
            'temps_moyen' => round($tempsMoyen),
            'reparations_reussies' => $reparationsReussies,
            'taux_reussite' => $tauxReussite,
            'par_chantier' => $interventionsParChantier,
        ];
    }
    
    /**
     * Récupère les statistiques d'intervention pour un technicien
     */
    public function getTechnicienStats(int $technicienId = null): array
    {
        $technicienId = $technicienId ?? Auth::id();
        
        $total = Intervention::where('technicien_id', $technicienId)->count();
        $terminees = Intervention::where('technicien_id', $technicienId)
            ->where('etat', 'Terminée')
            ->count();
        
        $tempsMoyen = Intervention::where('technicien_id', $technicienId)
            ->where('etat', 'Terminée')
            ->where('temps_total', '>', 0)
            ->avg('temps_total') ?? 0;
        
        $diagnosticsAujourdhui = Intervention::where('technicien_id', $technicienId)
            ->whereDate('date_debut', now()->toDateString())
            ->count();
        
        $reparationsReussies = Intervention::where('technicien_id', $technicienId)
            ->whereHas('reparation', function ($query) {
                $query->where('resultat', 'Réparé');
            })
            ->count();
        
        $tauxReussite = $terminees > 0 ? round(($reparationsReussies / $terminees) * 100) : 0;
        
        return [
            'total' => $total,
            'terminees' => $terminees,
            'temps_moyen' => round($tempsMoyen),
            'diagnostics_aujourdhui' => $diagnosticsAujourdhui,
            'reparations_reussies' => $reparationsReussies,
            'taux_reussite' => $tauxReussite,
        ];
    }
}