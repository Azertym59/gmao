<?php

namespace App\Repositories;

use App\Models\Intervention;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class InterventionRepository
{
    /**
     * Relations communes à charger pour les interventions
     */
    protected array $defaultRelations = [
        'module.dalle.produit.chantier',
        'technicien',
    ];
    
    /**
     * Relations complètes à charger pour les interventions détaillées
     */
    protected array $detailedRelations = [
        'module.dalle.produit.chantier.client',
        'technicien',
        'diagnostic',
        'reparation'
    ];

    /**
     * Récupère une intervention par son ID
     */
    public function findById(int $id): ?Intervention
    {
        return Intervention::with($this->detailedRelations)->find($id);
    }
    
    /**
     * Récupère toutes les interventions avec pagination par défaut
     */
    public function getAll(int $perPage = 20): LengthAwarePaginator
    {
        return Intervention::with($this->defaultRelations)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
    
    /**
     * Récupère les interventions paginées avec filtrage optionnel
     */
    public function getPaginated(int $perPage = 15, ?array $filters = null): LengthAwarePaginator
    {
        $query = Intervention::with($this->defaultRelations);
        
        // Appliquer les filtres s'ils sont fournis
        if ($filters) {
            // Filtrage par statut (actif / terminé)
            if (isset($filters['status'])) {
                if ($filters['status'] === 'active') {
                    $query->where('is_completed', false);
                } elseif ($filters['status'] === 'completed') {
                    $query->where('is_completed', true);
                }
            }
            
            // Filtrage par date (si présent)
            if (isset($filters['date_debut']) && !empty($filters['date_debut'])) {
                $query->whereDate('date_debut', '>=', $filters['date_debut']);
            }
            
            if (isset($filters['date_fin']) && !empty($filters['date_fin'])) {
                $query->whereDate('date_debut', '<=', $filters['date_fin']);
            }
            
            // Filtrage par technicien
            if (isset($filters['technicien_id']) && !empty($filters['technicien_id'])) {
                $query->where('technicien_id', $filters['technicien_id']);
            }
            
            // Filtrage par chantier
            if (isset($filters['chantier_id']) && !empty($filters['chantier_id'])) {
                $query->whereHas('module.dalle.produit.chantier', function ($q) use ($filters) {
                    $q->where('id', $filters['chantier_id']);
                });
            }
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
    
    /**
     * Récupère les interventions d'un technicien avec pagination
     */
    public function getByTechnicien(int $technicienId = null, int $perPage = 15): LengthAwarePaginator
    {
        $technicienId = $technicienId ?? Auth::id();
        
        return Intervention::with($this->defaultRelations)
            ->where('technicien_id', $technicienId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
    
    /**
     * Récupère les interventions en cours avec pagination
     */
    public function getEnCours(int $perPage = 15): LengthAwarePaginator
    {
        return Intervention::with($this->defaultRelations)
            ->where('etat', 'En cours')
            ->orderBy('date_debut', 'desc')
            ->paginate($perPage);
    }
    
    /**
     * Récupère les interventions d'un chantier avec pagination
     */
    public function getByChantier(int $chantierId, int $perPage = 15): LengthAwarePaginator
    {
        return Intervention::with($this->defaultRelations)
            ->whereHas('module.dalle.produit.chantier', function ($query) use ($chantierId) {
                $query->where('id', $chantierId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
    
    /**
     * Récupère les interventions par état avec pagination
     */
    public function getByState(string $state, int $perPage = 15): LengthAwarePaginator
    {
        return Intervention::with($this->defaultRelations)
            ->where('etat', $state)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
    
    /**
     * Récupère les statistiques d'intervention avec cache
     */
    public function getStats(): array
    {
        // Mettre en cache les statistiques pour 10 minutes
        return Cache::remember('intervention_stats', 600, function () {
            // Optimisation: Regrouper plusieurs requêtes en une pour les comptages
            $stats = DB::table('interventions')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN etat = "En cours" THEN 1 ELSE 0 END) as en_cours'),
                    DB::raw('SUM(CASE WHEN etat = "Diagnostic terminé" THEN 1 ELSE 0 END) as diagnostic_termine'),
                    DB::raw('SUM(CASE WHEN etat = "Terminée" THEN 1 ELSE 0 END) as terminees')
                )
                ->first();
                
            // Optimisation: Utiliser une seule requête pour calculer le temps moyen
            $tempsMoyen = DB::table('interventions')
                ->where('etat', 'Terminée')
                ->where('temps_total', '>', 0)
                ->avg('temps_total') ?? 0;
                
            // Optimisation: Utiliser une seule requête pour les réparations réussies
            $reparationsReussies = DB::table('interventions')
                ->join('reparations', 'interventions.id', '=', 'reparations.intervention_id')
                ->where('reparations.resultat', 'Réparé')
                ->count();
                
            $tauxReussite = $stats->terminees > 0 ? round(($reparationsReussies / $stats->terminees) * 100) : 0;
            
            // Optimisation: Utiliser une seule requête avec jointures pour les interventions par chantier
            $interventionsParChantier = DB::table('interventions')
                ->join('modules', 'interventions.module_id', '=', 'modules.id')
                ->join('dalles', 'modules.dalle_id', '=', 'dalles.id')
                ->join('produits', 'dalles.produit_id', '=', 'produits.id')
                ->join('chantiers', 'produits.chantier_id', '=', 'chantiers.id')
                ->select('chantiers.nom', 'chantiers.id', DB::raw('count(*) as total'))
                ->groupBy('chantiers.id', 'chantiers.nom')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
            
            return [
                'total' => $stats->total,
                'en_cours' => $stats->en_cours,
                'diagnostic_termine' => $stats->diagnostic_termine,
                'terminees' => $stats->terminees,
                'temps_moyen' => round($tempsMoyen),
                'reparations_reussies' => $reparationsReussies,
                'taux_reussite' => $tauxReussite,
                'par_chantier' => $interventionsParChantier,
            ];
        });
    }
    
    /**
     * Récupère les statistiques d'intervention pour un technicien avec cache
     */
    public function getTechnicienStats(int $technicienId = null): array
    {
        $technicienId = $technicienId ?? Auth::id();
        
        // Mettre en cache les statistiques par technicien pour 5 minutes
        return Cache::remember("intervention_technicien_stats_{$technicienId}", 300, function () use ($technicienId) {
            // Optimisation: Regrouper plusieurs requêtes en une pour les comptages
            $stats = DB::table('interventions')
                ->where('technicien_id', $technicienId)
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN etat = "Terminée" THEN 1 ELSE 0 END) as terminees'),
                    DB::raw('SUM(CASE WHEN DATE(date_debut) = CURRENT_DATE THEN 1 ELSE 0 END) as diagnostics_aujourdhui')
                )
                ->first();
                
            // Optimisation: Utiliser une seule requête pour calculer le temps moyen
            $tempsMoyen = DB::table('interventions')
                ->where('technicien_id', $technicienId)
                ->where('etat', 'Terminée')
                ->where('temps_total', '>', 0)
                ->avg('temps_total') ?? 0;
                
            // Optimisation: Utiliser une seule requête pour les réparations réussies
            $reparationsReussies = DB::table('interventions')
                ->join('reparations', 'interventions.id', '=', 'reparations.intervention_id')
                ->where('interventions.technicien_id', $technicienId)
                ->where('reparations.resultat', 'Réparé')
                ->count();
                
            $tauxReussite = $stats->terminees > 0 ? round(($reparationsReussies / $stats->terminees) * 100) : 0;
            
            return [
                'total' => $stats->total,
                'terminees' => $stats->terminees,
                'temps_moyen' => round($tempsMoyen),
                'diagnostics_aujourdhui' => $stats->diagnostics_aujourdhui,
                'reparations_reussies' => $reparationsReussies,
                'taux_reussite' => $tauxReussite,
            ];
        });
    }
    
    /**
     * Réinitialise le cache des statistiques
     */
    public function resetStatsCache(): void
    {
        Cache::forget('intervention_stats');
        // Réinitialiser les caches par technicien nécessiterait une logique supplémentaire
        // pour déterminer tous les IDs de techniciens
    }
    
    /**
     * Utiliser une approche de rapport plus efficace pour la
     * génération de statistiques détaillées
     */
    public function generateStatsReport(array $filters = []): array
    {
        // Requête optimisée pour générer un rapport complet en une seule requête
        $query = DB::table('interventions')
            ->leftJoin('users', 'interventions.technicien_id', '=', 'users.id')
            ->leftJoin('modules', 'interventions.module_id', '=', 'modules.id')
            ->leftJoin('dalles', 'modules.dalle_id', '=', 'dalles.id')
            ->leftJoin('produits', 'dalles.produit_id', '=', 'produits.id')
            ->leftJoin('chantiers', 'produits.chantier_id', '=', 'chantiers.id')
            ->leftJoin('diagnostics', 'interventions.id', '=', 'diagnostics.intervention_id')
            ->leftJoin('reparations', 'interventions.id', '=', 'reparations.intervention_id');
            
        // Appliquer les filtres
        if (!empty($filters['date_debut'])) {
            $query->whereDate('interventions.date_debut', '>=', $filters['date_debut']);
        }
        
        if (!empty($filters['date_fin'])) {
            $query->whereDate('interventions.date_fin', '<=', $filters['date_fin']);
        }
        
        if (!empty($filters['technicien_id'])) {
            $query->where('interventions.technicien_id', $filters['technicien_id']);
        }
        
        if (!empty($filters['chantier_id'])) {
            $query->where('chantiers.id', $filters['chantier_id']);
        }
        
        // Regrouper et sélectionner les statistiques
        $statsByDate = $query->select(
                DB::raw('DATE(interventions.date_debut) as date'),
                DB::raw('COUNT(DISTINCT interventions.id) as total_interventions'),
                DB::raw('AVG(interventions.temps_total) as temps_moyen'),
                DB::raw('SUM(diagnostics.nb_leds_hs) as total_leds_hs'),
                DB::raw('SUM(diagnostics.nb_ic_hs) as total_ics_hs'),
                DB::raw('SUM(reparations.nb_leds_remplacees) as total_leds_remplacees'),
                DB::raw('SUM(reparations.nb_ic_remplaces) as total_ics_remplaces')
            )
            ->groupBy(DB::raw('DATE(interventions.date_debut)'))
            ->orderBy('date', 'desc')
            ->get();
            
        $statsByTechnicien = $query->select(
                'users.id as technicien_id',
                'users.name as technicien_nom',
                DB::raw('COUNT(DISTINCT interventions.id) as total_interventions'),
                DB::raw('AVG(interventions.temps_total) as temps_moyen'),
                DB::raw('SUM(CASE WHEN interventions.is_completed = 1 THEN 1 ELSE 0 END) as interventions_terminees')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_interventions', 'desc')
            ->get();
            
        $statsByChantier = $query->select(
                'chantiers.id as chantier_id',
                'chantiers.nom as chantier_nom',
                DB::raw('COUNT(DISTINCT interventions.id) as total_interventions'),
                DB::raw('COUNT(DISTINCT modules.id) as total_modules'),
                DB::raw('SUM(CASE WHEN modules.etat = "termine" THEN 1 ELSE 0 END) as modules_termines')
            )
            ->groupBy('chantiers.id', 'chantiers.nom')
            ->orderBy('total_interventions', 'desc')
            ->get();
            
        return [
            'par_date' => $statsByDate,
            'par_technicien' => $statsByTechnicien,
            'par_chantier' => $statsByChantier
        ];
    }
}
