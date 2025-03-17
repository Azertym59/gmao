<?php

namespace App\Services\Intervention;

use App\DTOs\DiagnosticDTO;
use App\DTOs\ReparationDTO;
use App\Models\Diagnostic;
use App\Models\Intervention;
use App\Models\Module;
use App\Models\Reparation;
use App\Repositories\InterventionRepository;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InterventionService
{
    protected $notificationService;
    protected $interventionRepository;
    
    public function __construct(
        NotificationService $notificationService,
        InterventionRepository $interventionRepository
    ) {
        $this->notificationService = $notificationService;
        $this->interventionRepository = $interventionRepository;
    }
    
    /**
     * Démarre une nouvelle intervention
     */
    public function startIntervention(Module $module, ?int $technicienId = null): Intervention
    {
        DB::beginTransaction();
        
        try {
            // Vérifier si le module est occupé par un technicien actuellement
            if ($module->est_occupe && $module->technicien_id \!== null && $module->technicien_id \!== ($technicienId ?? Auth::id())) {
                throw new \Exception('Ce module est déjà en cours d\'intervention par un autre technicien');
            }
            
            // Créer l'intervention
            $intervention = Intervention::create([
                'module_id' => $module->id,
                'technicien_id' => $technicienId ?? Auth::id(),
                'date_debut' => now(),
                'temps_total' => 0,
                'is_completed' => false,
            ]);
            
            // Mettre à jour le statut du module
            $module->est_occupe = true;
            $module->technicien_id = $technicienId ?? Auth::id();
            $module->etat = 'en_cours'; // Utilisation d'une valeur valide de l'enum
            $module->save();
            
            // Mettre à jour l'état du chantier si nécessaire
            $chantier = $module->dalle->produit->chantier;
            if ($chantier->etat \!== 'en_cours') {
                $chantier->etat = 'en_cours';
                $chantier->save();
                
                // Log l'action pour référence
                Log::info('État du chantier #' . $chantier->id . ' automatiquement mis à jour: non_commence → en_cours');
            }
            
            DB::commit();
            
            // Notifier le démarrage de l'intervention
            $this->notificationService->notifyInterventionStarted($intervention);
            
            // Réinitialiser le cache des statistiques
            $this->interventionRepository->resetStatsCache();
            
            return $intervention;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du démarrage de l\'intervention: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Crée ou met à jour un diagnostic en utilisant un DTO
     */
    public function saveDiagnostic(Intervention $intervention, DiagnosticDTO $diagnosticDTO): Diagnostic
    {
        DB::beginTransaction();
        
        try {
            // Mettre à jour le numéro de série du module si fourni
            if (\!empty($diagnosticDTO->numero_serie)) {
                $module = $intervention->module;
                $module->update([
                    'numero_serie' => $diagnosticDTO->numero_serie
                ]);
            }
            
            // Créer ou mettre à jour le diagnostic
            $diagnostic = $intervention->diagnostic;
            
            if (\!$diagnostic) {
                // Créer un nouveau diagnostic
                $diagnostic = new Diagnostic($diagnosticDTO->toArray());
                $diagnostic->intervention_id = $intervention->id;
                $diagnostic->save();
            } else {
                // Mettre à jour le diagnostic existant
                $diagnostic->fill($diagnosticDTO->toArray());
                $diagnostic->save();
            }
            
            // Mettre à jour l'état de l'intervention
            $intervention->temps_total = max(0, (int) request()->input('temps_total', $intervention->temps_total));
            $intervention->save();
            
            // Mettre à jour l'état du module
            $module = $intervention->module;
            $module->etat = 'en_cours';
            $module->save();
            
            DB::commit();
            
            // Réinitialiser le cache des statistiques
            $this->interventionRepository->resetStatsCache();
            
            return $diagnostic;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création/mise à jour du diagnostic: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Crée ou met à jour une réparation en utilisant un DTO
     */
    public function saveReparation(Intervention $intervention, ReparationDTO $reparationDTO): Reparation
    {
        DB::beginTransaction();
        
        try {
            // Créer ou mettre à jour la réparation
            $reparation = $intervention->reparation;
            
            if (\!$reparation) {
                // Créer une nouvelle réparation
                $reparation = new Reparation($reparationDTO->toArray());
                $reparation->intervention_id = $intervention->id;
                $reparation->save();
            } else {
                // Mettre à jour la réparation existante
                $reparation->fill($reparationDTO->toArray());
                $reparation->save();
            }
            
            // Mettre à jour le temps total
            $intervention->temps_total = max(0, (int) request()->input('temps_total', $intervention->temps_total));
            $intervention->save();
            
            DB::commit();
            
            // Réinitialiser le cache des statistiques
            $this->interventionRepository->resetStatsCache();
            
            return $reparation;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création/mise à jour de la réparation: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Finalise une intervention complète
     */
    public function finalizeIntervention(Intervention $intervention, string $moduleEtat = 'termine'): bool
    {
        DB::beginTransaction();
        
        try {
            // Vérifier que l'intervention a un diagnostic et une réparation
            if (\!$intervention->diagnostic || \!$intervention->reparation) {
                throw new \Exception("L'intervention doit avoir un diagnostic et une réparation pour être finalisée.");
            }
            
            // Finaliser l'intervention
            $intervention->date_fin = now();
            $intervention->is_completed = true;
            $intervention->save();
            
            // Mettre à jour le module avec l'état sélectionné
            $module = $intervention->module;
            $module->est_occupe = false;
            $module->etat = $moduleEtat;
            $module->save();
            
            // Vérifier si tous les modules du chantier sont terminés
            $chantier = $module->dalle->produit->chantier;
            $allModulesCompleted = $this->checkIfAllModulesCompleted($chantier);
            
            if ($allModulesCompleted && $chantier->etat \!== 'termine') {
                // Notifier un administrateur que tous les modules sont terminés
                $this->notificationService->notifyChantierCompleted($chantier);
            }
            
            DB::commit();
            
            // Notifier la fin de l'intervention
            $this->notificationService->notifyInterventionCompleted($intervention);
            
            // Réinitialiser le cache des statistiques
            $this->interventionRepository->resetStatsCache();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la finalisation de l\'intervention: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Vérifie si tous les modules d'un chantier sont terminés
     */
    public function checkIfAllModulesCompleted(\App\Models\Chantier $chantier): bool
    {
        // Optimisation: utiliser une seule requête SQL pour compter les modules
        $moduleCounts = DB::table('modules')
            ->join('dalles', 'modules.dalle_id', '=', 'dalles.id')
            ->join('produits', 'dalles.produit_id', '=', 'produits.id')
            ->where('produits.chantier_id', $chantier->id)
            ->select(
                DB::raw('COUNT(*) as total_modules'),
                DB::raw('SUM(CASE WHEN modules.etat IN ("termine", "defaillant") THEN 1 ELSE 0 END) as completed_modules')
            )
            ->first();
        
        // Si pas de modules, retourne false
        if (\!$moduleCounts || $moduleCounts->total_modules === 0) {
            return false;
        }
        
        // Vérifier si tous les modules sont terminés
        $allCompleted = ($moduleCounts->completed_modules === $moduleCounts->total_modules);
        
        // Log uniquement si tous les modules sont terminés et que le chantier n'est pas déjà marqué comme terminé
        if ($allCompleted && $chantier->etat \!== 'termine') {
            Log::info('Tous les modules du chantier #' . $chantier->id . ' sont terminés ou défaillants. Considérez marquer le chantier comme terminé.');
            
            // Créer une notification pour l'administrateur
            if (class_exists(\App\Models\Notification::class)) {
                // Trouver un admin
                $adminUser = \App\Models\User::where('role', 'admin')->first();
                
                if ($adminUser) {
                    \App\Models\Notification::create([
                        'user_id' => $adminUser->id,
                        'type' => 'chantier_complete',
                        'message' => 'Tous les modules du chantier #' . $chantier->id . ' sont terminés ou défaillants.',
                        'data' => [
                            'chantier_id' => $chantier->id,
                            'chantier_nom' => $chantier->nom,
                            'total_modules' => $moduleCounts->total_modules,
                            'modules_termines' => $moduleCounts->completed_modules
                        ],
                        'is_read' => false
                    ]);
                }
            }
        }
        
        return $allCompleted;
    }
    
    /**
     * Annule une intervention et libère le module
     */
    public function cancelIntervention(Intervention $intervention): bool
    {
        DB::beginTransaction();
        
        try {
            // Vérifier si l'intervention n'est pas déjà terminée
            if ($intervention->is_completed) {
                throw new \Exception("Impossible d'annuler une intervention déjà terminée.");
            }
            
            // Libérer le module
            $module = $intervention->module;
            
            if ($module) {
                $module->est_occupe = false;
                
                // Remettre l'état précédent ou non_commence si non défini
                // Si le module était défaillant, on le maintient dans cet état
                if ($module->etat \!== 'defaillant') {
                    $module->etat = $module->etat_precedent ?? 'non_commence';
                }
                
                $module->save();
                
                Log::info('Module #' . $module->id . ' libéré suite à l\'annulation de l\'intervention #' . $intervention->id);
            }
            
            // Supprimer l'intervention
            $intervention->delete();
            
            DB::commit();
            
            // Réinitialiser le cache des statistiques
            $this->interventionRepository->resetStatsCache();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'annulation de l\'intervention: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère les statistiques des interventions pour un technicien
     * en utilisant le repository avec cache
     */
    public function getTechnicienStats(?int $technicienId = null): array
    {
        return $this->interventionRepository->getTechnicienStats($technicienId);
    }
    
    /**
     * Traite la mise en pause d'une intervention
     */
    public function pauseIntervention(Intervention $intervention): array
    {
        // Calculer le temps écoulé depuis le début ou la dernière reprise
        $debut = $intervention->date_reprise ?? $intervention->date_debut;
        $maintenant = now();
        $tempsEcoule = $debut->diffInSeconds($maintenant);
        
        // Mettre à jour le temps total
        $intervention->temps_total += $tempsEcoule;
        $intervention->date_pause = $maintenant;
        $intervention->date_reprise = null;
        $intervention->save();
        
        return [
            'success' => true,
            'temps_total' => $intervention->temps_total,
            'temps_formate' => $this->formatTemps($intervention->temps_total)
        ];
    }
    
    /**
     * Traite la reprise d'une intervention
     */
    public function resumeIntervention(Intervention $intervention): array
    {
        $intervention->date_reprise = now();
        $intervention->save();
        
        return [
            'success' => true
        ];
    }
    
    /**
     * Formater un temps en secondes en format lisible (HH:MM:SS)
     */
    private function formatTemps($secondes)
    {
        $heures = floor($secondes / 3600);
        $minutes = floor(($secondes % 3600) / 60);
        $secondes = $secondes % 60;
        
        return sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
    }
}
