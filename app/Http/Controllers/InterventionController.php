<?php

namespace App\Http\Controllers;

use App\DTOs\DiagnosticDTO;
use App\DTOs\ReparationDTO;
use App\Http\Requests\InterventionRequest;
use App\Models\Intervention;
use App\Models\Module;
use App\Repositories\InterventionRepository;
use App\Repositories\ModuleRepository;
use App\Services\Intervention\InterventionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InterventionController extends Controller
{
    protected $interventionService;
    protected $interventionRepository;
    protected $moduleRepository;

    public function __construct(
        InterventionService $interventionService,
        InterventionRepository $interventionRepository,
        ModuleRepository $moduleRepository
    ) {
        $this->interventionService = $interventionService;
        $this->interventionRepository = $interventionRepository;
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * Afficher la liste des interventions
     */
    public function index(Request $request)
    {
        // Si le paramètre selectModule est présent, rediriger vers la sélection de module
        if ($request->has('selectModule')) {
            $modules = $this->moduleRepository->getModulesNeedingIntervention();
            return view('interventions.select_module', compact('modules'));
        }
        
        // Construire les filtres à partir des paramètres de la requête
        $filters = [];
        
        if ($request->has('filter_status')) {
            $filters['status'] = $request->input('filter_status');
        }
        
        if ($request->has('date_debut')) {
            $filters['date_debut'] = $request->input('date_debut');
        }
        
        if ($request->has('date_fin')) {
            $filters['date_fin'] = $request->input('date_fin');
        }
        
        if ($request->has('technicien_id')) {
            $filters['technicien_id'] = $request->input('technicien_id');
        }
        
        // Obtenir les interventions paginées via le repository
        $interventions = $this->interventionRepository->getPaginated(20, $filters);
        $stats = $this->interventionRepository->getStats();
        
        return view('interventions.index', compact('interventions', 'stats', 'filters'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request, $module = null)
    {
        // Si l'ID du module est passé dans l'URL, l'utiliser. Sinon, chercher dans les paramètres de requête.
        $moduleId = $module ?? $request->input('module_id');
        
        if ($moduleId) {
            $module = $this->moduleRepository->findById($moduleId);
            
            if (!$module) {
                return redirect()->route('interventions.index')->with('error', "Module non trouvé.");
            }
            
            // Double vérification des interventions actives
            // Vérifier à la fois l'état occupé ET les interventions actives pour être sûr
            if ($module->est_occupe || $module->hasActiveIntervention()) {
                // Obtenir l'intervention active
                $existingIntervention = $module->getActiveIntervention();
                
                if ($existingIntervention) {
                    // Si l'intervention est par un autre technicien, afficher un message explicite
                    if ($existingIntervention->technicien_id !== Auth::id()) {
                        $technicien = \App\Models\User::find($existingIntervention->technicien_id);
                        $techName = $technicien ? $technicien->name : 'Un autre utilisateur';
                        
                        // Rediriger avec un message d'erreur
                        return redirect()->route('interventions.index')
                            ->with('error', "Ce module est déjà en cours d'intervention par {$techName}. Veuillez choisir un autre module.");
                    }
                    
                    // Rediriger vers la page appropriée de l'intervention existante
                    return redirect()->route('interventions.diagnostic', $existingIntervention)
                        ->with('info', 'Vous avez déjà une intervention en cours sur ce module.');
                }
                
                // Nettoyage de l'état incohérent (occupé mais sans intervention active)
                $module->est_occupe = false;
                $module->technicien_id = null;
                $module->save();
                
                // Log de l'incohérence pour analyse ultérieure
                \Illuminate\Support\Facades\Log::warning("État incohérent détecté sur le module #{$module->id}: marqué comme occupé sans intervention active.");
            }
            
            // VERROU D'EXCLUSION MUTUELLE AVEC UN FICHIER
            // Cette méthode est beaucoup plus solide pour éviter les doubles interventions
            $lockFile = storage_path('app/locks/module_' . $module->id . '.lock');
            
            // Créer le dossier des verrous s'il n'existe pas
            if (!file_exists(dirname($lockFile))) {
                mkdir(dirname($lockFile), 0777, true);
            }
            
            // Essayer d'obtenir un verrou exclusif sur le fichier
            $lockHandle = fopen($lockFile, 'w+');
            
            if (!$lockHandle || !flock($lockHandle, LOCK_EX | LOCK_NB)) {
                // Impossible d'obtenir le verrou, un autre processus est déjà en train d'intervenir
                if ($lockHandle) {
                    fclose($lockHandle);
                }
                
                // Vérifier une dernière fois si une intervention est en cours
                $activeIntervention = $module->getActiveIntervention();
                
                if ($activeIntervention) {
                    // Si c'est notre propre intervention, on redirige vers celle-ci
                    if ($activeIntervention->technicien_id === Auth::id()) {
                        return redirect()->route('interventions.diagnostic', $activeIntervention)
                            ->with('info', 'Vous avez déjà une intervention en cours sur ce module.');
                    }
                    
                    $technicien = \App\Models\User::find($activeIntervention->technicien_id);
                    $techName = $technicien ? $technicien->name : 'Un autre technicien';
                    
                    return redirect()->route('interventions.index')
                        ->with('error', "⚠️ MODULE OCCUPÉ ⚠️ - Ce module est déjà en cours d'intervention par {$techName}. Veuillez choisir un autre module.");
                }
                
                return redirect()->route('interventions.index')
                    ->with('error', "Ce module est actuellement verrouillé, une autre personne y accède probablement. Veuillez essayer à nouveau plus tard.");
            }
            
            try {
                // Créer l'intervention avec le verrou
                $intervention = $this->interventionService->startIntervention($module, Auth::id());
                
                // Libérer le verrou
                flock($lockHandle, LOCK_UN);
                fclose($lockHandle);
                
                return redirect()->route('interventions.diagnostic', $intervention);
            } catch (\Exception $e) {
                // Libérer le verrou en cas d'erreur
                if ($lockHandle) {
                    flock($lockHandle, LOCK_UN);
                    fclose($lockHandle);
                }
                
                // Nettoyer tous les verrous datant de plus de 10 minutes
                $this->cleanOldLocks();
                
                return redirect()->route('interventions.index')->with('error', $e->getMessage());
            }
        } else {
            // Afficher la sélection de module
            $modules = $this->moduleRepository->getModulesNeedingIntervention();
            return view('interventions.select_module', compact('modules'));
        }
    }
    
    /**
     * Enregistrer une nouvelle intervention complète (diagnostic + réparation)
     */
    public function store(Request $request)
    {
        try {
            // Récupérer l'intervention existante
            $interventionId = $request->input('intervention_id');
            $intervention = $this->interventionRepository->findById($interventionId);
            
            if (!$intervention) {
                return redirect()->route('interventions.index')
                    ->with('error', 'Intervention non trouvée.');
            }
            
            // Vérifier si l'intervention est déjà terminée pour éviter les boucles
            if ($intervention->is_completed) {
                return redirect()->route('interventions.show', $intervention)
                    ->with('info', 'Cette intervention est déjà terminée.');
            }
            
            // Finaliser l'intervention avec l'état du module spécifié (mais jamais "en_cours")
            $moduleEtat = $request->input('module_etat', 'termine');
            if ($moduleEtat === 'en_cours') {
                $moduleEtat = 'stand_by'; // Plus d'état "en_cours", on utilise "stand_by" à la place
            }
            $success = $this->interventionService->finalizeIntervention($intervention, $moduleEtat);
            
            if (!$success) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Erreur lors de la finalisation de l\'intervention.');
            }
            
            // Récupérer l'ID du chantier associé à cette intervention
            $chantierId = $intervention->module->dalle->produit->chantier->id;
            
            // Rediriger vers la page du chantier
            return redirect()->route('chantiers.show', $chantierId)
                ->with('success', 'Intervention terminée avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de l\'intervention: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'enregistrement de l\'intervention: ' . $e->getMessage());
        }
    }

    /**
     * Mettre en pause une intervention
     */
    public function pause(Intervention $intervention)
    {
        $result = $this->interventionService->pauseIntervention($intervention);
        return response()->json($result);
    }

    /**
     * Reprendre une intervention en pause
     */
    public function resume(Intervention $intervention)
    {
        $result = $this->interventionService->resumeIntervention($intervention);
        return response()->json($result);
    }

    /**
     * Enregistrer le diagnostic d'une intervention
     */
    public function storeDiagnostic(Request $request, Intervention $intervention)
    {
        // Valider les données
        $validated = $request->validate([
            'diagnostic_nb_leds_hs' => 'required|integer|min:0',
            'diagnostic_nb_ic_hs' => 'required|integer|min:0',
            'diagnostic_nb_masques_hs' => 'required|integer|min:0',
            'diagnostic_remarques' => 'nullable|string',
            'diagnostic_pose_fake_pcb' => 'nullable|boolean',
            'diagnostic_cause' => 'nullable|string|in:usure_normale,choc,defaut_usine,autre',
            'numero_serie' => 'nullable|string|max:255',
            'temps_total' => 'required|integer|min:0',
        ]);
        
        try {
            // Créer un DTO à partir des données validées
            $diagnosticDTO = DiagnosticDTO::fromRequest($request);
            
            // Utiliser le service pour sauvegarder le diagnostic
            $diagnostic = $this->interventionService->saveDiagnostic($intervention, $diagnosticDTO);
            
            return redirect()->route('interventions.reparation', $intervention)
                ->with('success', 'Diagnostic enregistré avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Enregistrer la réparation d'une intervention
     */
    public function storeReparation(Request $request, Intervention $intervention)
    {
        // Valider les données
        $validated = $request->validate([
            'reparation_nb_leds_remplacees' => 'required|integer|min:0',
            'reparation_nb_ic_remplaces' => 'required|integer|min:0',
            'reparation_nb_masques_remplaces' => 'required|integer|min:0',
            'reparation_fake_pcb_nb' => 'required|integer|min:0',
            'reparation_remarques' => 'nullable|string',
            'reparation_fake_pcb_pose' => 'nullable|boolean',
            'temps_total' => 'required|integer|min:0',
        ]);
        
        try {
            // Créer un DTO à partir des données validées
            $reparationDTO = ReparationDTO::fromRequest($request);
            
            // Utiliser le service pour sauvegarder la réparation
            $reparation = $this->interventionService->saveReparation($intervention, $reparationDTO);
            
            return redirect()->route('interventions.finalisation', $intervention)
                ->with('success', 'Réparation enregistrée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Afficher une intervention spécifique
     */
    public function show(Intervention $intervention)
    {
        $intervention = $this->interventionRepository->findById($intervention->id);
        
        if (!$intervention) {
            return redirect()->route('interventions.index')
                ->with('error', 'Intervention non trouvée.');
        }
        
        return view('interventions.show', compact('intervention'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Intervention $intervention)
    {
        $intervention = $this->interventionRepository->findById($intervention->id);
        
        if (!$intervention) {
            return redirect()->route('interventions.index')
                ->with('error', 'Intervention non trouvée.');
        }
        
        // Vérifier si l'intervention a un diagnostic associé
        if (!$intervention->diagnostic) {
            // Créer un diagnostic vide pour cette intervention via le service
            $diagnosticDTO = new DiagnosticDTO();
            $diagnosticDTO->intervention_id = $intervention->id;
            $diagnosticDTO->nb_leds_hs = 0;
            $diagnosticDTO->nb_ic_hs = 0;
            $diagnosticDTO->nb_masques_hs = 0;
            $diagnosticDTO->remarques = '';
            $diagnosticDTO->pose_fake_pcb = false;
            
            $this->interventionService->saveDiagnostic($intervention, $diagnosticDTO);
            
            // Recharger l'intervention pour inclure le nouveau diagnostic
            $intervention = $this->interventionRepository->findById($intervention->id);
        }
        
        // Vérifier si l'intervention a une réparation associée
        if (!$intervention->reparation) {
            // Créer une réparation vide pour cette intervention via le service
            $reparationDTO = new ReparationDTO();
            $reparationDTO->intervention_id = $intervention->id;
            $reparationDTO->nb_leds_remplacees = 0;
            $reparationDTO->nb_ic_remplaces = 0;
            $reparationDTO->nb_masques_remplaces = 0;
            $reparationDTO->fake_pcb_nb = 0;
            $reparationDTO->remarques = '';
            $reparationDTO->fake_pcb_pose = false;
            
            $this->interventionService->saveReparation($intervention, $reparationDTO);
            
            // Recharger l'intervention pour inclure la nouvelle réparation
            $intervention = $this->interventionRepository->findById($intervention->id);
        }
        
        return view('interventions.edit', compact('intervention'));
    }

    /**
     * Mettre à jour une intervention
     */
    public function update(Request $request, Intervention $intervention)
    {
        try {
            // Créer les DTOs à partir des données de la requête
            if ($intervention->diagnostic) {
                $diagnosticDTO = DiagnosticDTO::fromRequest($request);
                $this->interventionService->saveDiagnostic($intervention, $diagnosticDTO);
            }
            
            if ($intervention->reparation) {
                $reparationDTO = ReparationDTO::fromRequest($request);
                $this->interventionService->saveReparation($intervention, $reparationDTO);
            }
            
            // Si l'intervention est terminée, finaliser via le service
            if ($request->input('etat') === 'Terminée') {
                $moduleEtat = $request->input('module_etat', 'termine');
                $this->interventionService->finalizeIntervention($intervention, $moduleEtat);
            }
            
            // Journaliser l'action
            Log::info('Intervention #' . $intervention->id . ' mise à jour par l\'utilisateur #' . Auth::id());
            
            return redirect()->route('interventions.show', $intervention)
                ->with('success', 'Intervention mise à jour avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'intervention: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour de l\'intervention: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une intervention
     */
    public function destroy(Intervention $intervention)
    {
        try {
            $this->interventionService->cancelIntervention($intervention);
            return redirect()->route('interventions.index')
                ->with('success', 'Intervention supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'intervention: ' . $e->getMessage());
        }
    }
    
    /**
     * Annuler une intervention et libérer le module
     */
    public function cancel(Intervention $intervention)
    {
        try {
            $result = $this->interventionService->cancelIntervention($intervention);
            
            if ($result) {
                return redirect()->route('interventions.index')
                    ->with('success', 'L\'intervention a été annulée et le module a été libéré.');
            } else {
                return redirect()->back()
                    ->with('error', 'Erreur lors de l\'annulation de l\'intervention.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'annulation de l\'intervention: ' . $e->getMessage());
        }
    }
    
    /**
     * Afficher le dashboard des interventions pour un technicien
     */
    public function dashboard()
    {
        $technicienId = Auth::id();
        $stats = $this->interventionRepository->getTechnicienStats($technicienId);
        $interventionsEnCours = $this->interventionRepository->getByState('En cours', 10)
            ->where('technicien_id', $technicienId);
        $interventionsRecentes = $this->interventionRepository->getByTechnicien($technicienId, 10);
        
        return view('technicien.dashboard', compact('stats', 'interventionsEnCours', 'interventionsRecentes'));
    }
    
    /**
     * Afficher le formulaire de diagnostic (étape 1)
     */
    public function showDiagnosticStep(Intervention $intervention)
    {
        $intervention = $this->interventionRepository->findById($intervention->id);
        $module = $intervention->module;
        
        if (!$intervention) {
            return redirect()->route('interventions.index')
                ->with('error', 'Intervention non trouvée.');
        }
        
        return view('interventions.step1_diagnostic', compact('intervention', 'module'));
    }

    /**
     * Afficher le formulaire de réparation (étape 2)
     */
    public function showReparationStep(Intervention $intervention)
    {
        $intervention = $this->interventionRepository->findById($intervention->id);
        $module = $intervention->module;
        $diagnostic = $intervention->diagnostic;
        
        if (!$intervention) {
            return redirect()->route('interventions.index')
                ->with('error', 'Intervention non trouvée.');
        }
        
        if (!$diagnostic) {
            return redirect()->route('interventions.diagnostic', $intervention)
                ->with('error', 'Vous devez d\'abord compléter le diagnostic.');
        }
        
        return view('interventions.step2_reparation', compact('intervention', 'module', 'diagnostic'));
    }

    /**
     * Afficher le formulaire de finalisation (étape 3)
     */
    public function showFinalisationStep(Intervention $intervention)
    {
        $intervention = $this->interventionRepository->findById($intervention->id);
        $module = $intervention->module;
        $diagnostic = $intervention->diagnostic;
        $reparation = $intervention->reparation;
        
        if (!$intervention) {
            return redirect()->route('interventions.index')
                ->with('error', 'Intervention non trouvée.');
        }
        
        if (!$diagnostic) {
            return redirect()->route('interventions.diagnostic', $intervention)
                ->with('error', 'Vous devez d\'abord compléter le diagnostic.');
        }
        
        if (!$reparation) {
            return redirect()->route('interventions.reparation', $intervention)
                ->with('error', 'Vous devez d\'abord compléter la réparation.');
        }
        
        return view('interventions.step3_finalisation', compact('intervention', 'module', 'diagnostic', 'reparation'));
    }
    
    /**
     * Enregistrer la finalisation d'une intervention
     */
    public function storeFinalisation(Request $request, Intervention $intervention)
    {
        try {
            // Vérifier si l'intervention est déjà terminée
            if ($intervention->is_completed) {
                return redirect()->route('interventions.show', $intervention)
                    ->with('info', 'Cette intervention est déjà terminée.');
            }
            
            // Vérifier que le diagnostic et la réparation existent
            if (!$intervention->diagnostic || !$intervention->reparation) {
                return redirect()->back()
                    ->with('error', 'Le diagnostic et la réparation doivent être complétés avant de finaliser l\'intervention.');
            }
            
            // Récupérer l'état final du module (mais jamais "en_cours")
            $moduleEtat = $request->input('module_etat', 'termine');
            if ($moduleEtat === 'en_cours') {
                $moduleEtat = 'stand_by'; // Plus d'état "en_cours", on utilise "stand_by" à la place
            }
            
            // Finaliser l'intervention avec le service
            $success = $this->interventionService->finalizeIntervention($intervention, $moduleEtat);
            
            if (!$success) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Erreur lors de la finalisation de l\'intervention.');
            }
            
            // Récupérer l'ID du chantier associé à cette intervention
            $chantierId = $intervention->module->dalle->produit->chantier->id;
            
            // Rediriger vers la page du chantier
            return redirect()->route('chantiers.show', $chantierId)
                ->with('success', 'Intervention terminée avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la finalisation de l\'intervention: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la finalisation de l\'intervention: ' . $e->getMessage());
        }
    }
    
    /**
     * Nettoie les vieux verrous et corrige l'état incohérent des modules
     * 
     * Cette méthode:
     * 1. Supprime les fichiers de verrou datant de plus de 10 minutes
     * 2. Corrige les modules marqués comme occupés sans intervention active
     */
    private function cleanOldLocks()
    {
        // 1. Nettoyer les fichiers de verrou
        $lockDir = storage_path('app/locks');
        
        if (!file_exists($lockDir)) {
            return;
        }
        
        $now = time();
        foreach (glob($lockDir . '/module_*.lock') as $lockFile) {
            // Si le fichier de verrou date de plus de 10 minutes
            if ($now - filemtime($lockFile) > 600) {
                @unlink($lockFile);
            }
        }
        
        // 2. Corriger les incohérences de la base de données
        try {
            // Identifier les modules marqués comme occupés sans intervention active
            $modulesIds = \App\Models\Module::where('est_occupe', true)
                ->get()
                ->filter(function ($module) {
                    return !$module->hasActiveIntervention();
                })
                ->pluck('id')
                ->toArray();
                
            if (count($modulesIds) > 0) {
                // Nettoyer les modules incohérents
                \App\Models\Module::whereIn('id', $modulesIds)
                    ->update([
                        'est_occupe' => false,
                        'technicien_id' => null
                    ]);
                    
                // Journaliser l'opération
                \Illuminate\Support\Facades\Log::info('Nettoyage auto: ' . count($modulesIds) . ' modules incohérents corrigés: ' . implode(', ', $modulesIds));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors du nettoyage des modules incohérents: ' . $e->getMessage());
        }
    }
}
