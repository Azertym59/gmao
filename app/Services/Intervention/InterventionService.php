<?php

namespace App\Services\Intervention;

use App\Models\Diagnostic;
use App\Models\Intervention;
use App\Models\Module;
use App\Models\Reparation;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InterventionService
{
    protected $notificationService;
    
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    /**
     * Démarre une nouvelle intervention
     */
    public function startIntervention(Module $module, ?int $technicienId = null): Intervention
    {
        DB::beginTransaction();
        
        try {
            // Vérifier si le module est occupé par un technicien actuellement
            if ($module->est_occupe && $module->technicien_id !== null && $module->technicien_id !== ($technicienId ?? Auth::id())) {
                throw new \Exception('Ce module est déjà en cours d\'intervention par un autre technicien');
            }
            
            // Créer l'intervention
            $intervention = Intervention::create([
                'module_id' => $module->id,
                'technicien_id' => $technicienId ?? Auth::id(),
                'date_debut' => now(),
                // 'etat' => 'En cours', // Supprimé car la colonne n'existe pas
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
            if ($chantier->etat !== 'en_cours') {
                $chantier->etat = 'en_cours';
                $chantier->save();
                
                // Log l'action pour référence
                Log::info('État du chantier #' . $chantier->id . ' automatiquement mis à jour: non_commence → en_cours');
            }
            
            DB::commit();
            
            // Notifier le démarrage de l'intervention
            $this->notificationService->notifyInterventionStarted($intervention);
            
            return $intervention;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du démarrage de l\'intervention: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Enregistre un diagnostic pour une intervention
     */
    public function createDiagnostic(Intervention $intervention, array $diagnosticData): Diagnostic
    {
        DB::beginTransaction();
        
        try {
            // Créer le diagnostic
            $diagnostic = Diagnostic::create([
                'intervention_id' => $intervention->id,
                'description' => $diagnosticData['description'],
                'conclusion' => $diagnosticData['conclusion'],
                'composant_defectueux' => $diagnosticData['composant_defectueux'] ?? null,
            ]);
            
            // Mettre à jour l'état de l'intervention
            // $intervention->etat = 'Diagnostic terminé'; // Supprimé car la colonne n'existe pas
            // On garde is_completed à false car le diagnostic est fait mais pas la réparation
            $intervention->save();
            
            // Mettre à jour l'état du module
            $module = $intervention->module;
            $module->etat = 'en_cours'; // Utilisation d'une valeur valide de l'enum
            $module->save();
            
            DB::commit();
            
            return $diagnostic;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du diagnostic: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Enregistre une réparation pour une intervention
     */
    public function createReparation(Intervention $intervention, array $reparationData): Reparation
    {
        DB::beginTransaction();
        
        try {
            // Créer la réparation
            $reparation = Reparation::create([
                'intervention_id' => $intervention->id,
                'description' => $reparationData['description'],
                'actions' => $reparationData['actions'],
                'pieces_remplacees' => $reparationData['pieces_remplacees'] ?? null,
                'resultat' => $reparationData['resultat'],
            ]);
            
            // Calculer le temps total de l'intervention
            $tempsTotal = now()->diffInMinutes($intervention->date_debut);
            
            // Mettre à jour l'intervention
            // $intervention->etat = 'Terminée'; // Supprimé car la colonne n'existe pas
            $intervention->is_completed = true; // À la place, on utilise is_completed
            $intervention->date_fin = now();
            $intervention->temps_total = $tempsTotal;
            $intervention->save();
            
            // Mettre à jour le module
            $module = $intervention->module;
            $module->est_occupe = false;
            $module->etat = $reparationData['resultat'] === 'Réparé' ? 'termine' : 'defaillant';
            $module->save();
            
            // Récupérer le chantier
            $chantier = $module->dalle->produit->chantier;
            
            // Vérifier si tous les modules sont terminés
            $allModulesCompleted = $this->checkIfAllModulesCompleted($chantier);
            
            DB::commit();
            
            // Notifier la fin de l'intervention
            $this->notificationService->notifyInterventionCompleted($intervention);
            
            return $reparation;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de la réparation: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Vérifie si tous les modules d'un chantier sont terminés
     * 
     * @param Chantier $chantier Le chantier à vérifier
     * @return bool True si tous les modules sont terminés ou défaillants, false sinon
     */
    public function checkIfAllModulesCompleted(Chantier $chantier): bool
    {
        $totalModules = 0;
        $completedModules = 0;
        
        foreach ($chantier->produits as $produit) {
            foreach ($produit->dalles as $dalle) {
                foreach ($dalle->modules as $module) {
                    $totalModules++;
                    if ($module->etat === 'termine' || $module->etat === 'defaillant') {
                        $completedModules++;
                    }
                }
            }
        }
        
        // Si pas de modules, retourne false
        if ($totalModules === 0) {
            return false;
        }
        
        // Vérifier si tous les modules sont terminés
        $allCompleted = ($completedModules === $totalModules);
        
        // Log uniquement si tous les modules sont terminés et que le chantier n'est pas déjà marqué comme terminé
        if ($allCompleted && $chantier->etat !== 'termine') {
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
                            'total_modules' => $totalModules,
                            'modules_termines' => $completedModules
                        ],
                        'is_read' => false
                    ]);
                }
            }
        }
        
        return $allCompleted;
    }
    
    /**
     * Récupère les statistiques des interventions pour un technicien
     */
    public function getTechnicienStats(?int $technicienId = null): array
    {
        $technicienId = $technicienId ?? Auth::id();
        
        $totalInterventions = Intervention::where('technicien_id', $technicienId)->count();
        
        $interventionsTerminees = Intervention::where('technicien_id', $technicienId)
            ->where('is_completed', true)
            ->count();
        
        $tempsMoyen = Intervention::where('technicien_id', $technicienId)
            ->where('is_completed', true)
            ->where('temps_total', '>', 0)
            ->avg('temps_total');
        
        $diagnosticsAujourdhui = Intervention::where('technicien_id', $technicienId)
            ->whereDate('date_debut', now()->toDateString())
            ->count();
        
        $reparationsReussies = Intervention::where('technicien_id', $technicienId)
            ->whereHas('reparation', function ($query) {
                $query->where('resultat', 'Réparé');
            })
            ->count();
        
        $tauxReussite = $interventionsTerminees > 0 
            ? round(($reparationsReussies / $interventionsTerminees) * 100) 
            : 0;
        
        return [
            'total_interventions' => $totalInterventions,
            'interventions_terminees' => $interventionsTerminees,
            'temps_moyen' => $tempsMoyen ? round($tempsMoyen) : 0,
            'diagnostics_aujourdhui' => $diagnosticsAujourdhui,
            'reparations_reussies' => $reparationsReussies,
            'taux_reussite' => $tauxReussite,
        ];
    }
}