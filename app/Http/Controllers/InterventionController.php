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
    public function create(Request $request)
    {
        $moduleId = $request->input('module_id');
        
        if ($moduleId) {
            $module = $this->moduleRepository->findById($moduleId);
            
            if (!$module) {
                return redirect()->route('interventions.index')->with('error', "Module non trouvé.");
            }
            
            // Vérifier si le module est disponible
            if ($module->est_occupe) {
                // Vérifier si une intervention en cours existe déjà pour ce module
                $existingIntervention = Intervention::where('module_id', $module->id)
                    ->where('is_completed', false)
                    ->latest()
                    ->first();
                
                if ($existingIntervention) {
                    // Rediriger vers la page d'étape 1 (diagnostic) de l'intervention existante
                    return redirect()->route('interventions.step1.diagnostic', $existingIntervention);
                }
                
                return redirect()->route('interventions.index')->with('error', "Ce module est déjà en cours d'intervention.");
            }
            
            // Créer une nouvelle intervention
            try {
                $intervention = $this->interventionService->startIntervention($module, Auth::id());
                return redirect()->route('interventions.step1.diagnostic', $intervention);
            } catch (\Exception $e) {
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
            
            // Finaliser l'intervention avec l'état du module spécifié
            $moduleEtat = $request->input('module_etat', 'termine');
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
            
            return redirect()->route('interventions.step2.reparation', $intervention)
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
            
            return redirect()->route('interventions.step3.finalisation', $intervention)
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
            return redirect()->route('interventions.step1.diagnostic', $intervention)
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
            return redirect()->route('interventions.step1.diagnostic', $intervention)
                ->with('error', 'Vous devez d\'abord compléter le diagnostic.');
        }
        
        if (!$reparation) {
            return redirect()->route('interventions.step2.reparation', $intervention)
                ->with('error', 'Vous devez d\'abord compléter la réparation.');
        }
        
        return view('interventions.step3_finalisation', compact('intervention', 'module', 'diagnostic', 'reparation'));
    }
}
