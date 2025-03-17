<?php

namespace App\Http\Controllers;

use App\Http\Requests\InterventionRequest;
use App\Models\Intervention;
use App\Models\Module;
use App\Repositories\InterventionRepository;
use App\Repositories\ModuleRepository;
use App\Services\Intervention\InterventionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Utiliser le repository existant mais lui passer les paramètres de filtrage
        $filter = null;
        if ($request->has('filter_status')) {
            $filter = [
                'status' => $request->input('filter_status')
            ];
        }
        
        // Obtenir les interventions paginées via le repository
        $interventions = $this->interventionRepository->getPaginated(20, $filter);
        $stats = $this->interventionRepository->getStats();
        
        // Passer les filtres à la vue pour marquer les onglets actifs
        $activeFilters = [
            'status' => $request->input('filter_status'),
        ];
        
        return view('interventions.index', compact('interventions', 'stats', 'activeFilters'));
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
            
            // Vérifier si l'intervention a un diagnostic associé
            if (!$intervention->diagnostic) {
                // Créer un diagnostic vide
                $diagnostic = new \App\Models\Diagnostic([
                    'intervention_id' => $intervention->id,
                    'nb_leds_hs' => 0,
                    'nb_ic_hs' => 0,
                    'nb_masques_hs' => 0,
                    'remarques' => '' // Utilisation de remarques au lieu de description/conclusion
                ]);
                $diagnostic->save();
                
                // Recharger l'intervention pour inclure le nouveau diagnostic
                $intervention = $this->interventionRepository->findById($intervention->id);
            }
            
            // Vérifier si l'intervention a une réparation associée
            if (!$intervention->reparation) {
                // Créer une réparation vide
                $reparation = new \App\Models\Reparation([
                    'intervention_id' => $intervention->id,
                    'nb_leds_remplacees' => 0,
                    'nb_ic_remplaces' => 0,
                    'nb_masques_remplaces' => 0,
                    'remarques' => '' // Utilisation de remarques au lieu de description/actions
                ]);
                $reparation->save();
                
                // Recharger l'intervention pour inclure la nouvelle réparation
                $intervention = $this->interventionRepository->findById($intervention->id);
            }
            
            // Mettre à jour le temps total
            $intervention->temps_total = $request->input('temps_total', 0);
            
            // Mise à jour du diagnostic avec les champs du formulaire
            $diagnostic = $intervention->diagnostic;
            if ($diagnostic) {
                $diagnostic->nb_leds_hs = (int) $request->input('diagnostic_nb_leds_hs', 0);
                $diagnostic->nb_ic_hs = (int) $request->input('diagnostic_nb_ic_hs', 0);
                $diagnostic->nb_masques_hs = (int) $request->input('diagnostic_nb_masques_hs', 0);
                $diagnostic->remarques = $request->input('diagnostic_remarques', '');
                $diagnostic->pose_fake_pcb = $request->has('diagnostic_pose_fake_pcb') ? true : false;
                $diagnostic->save();
                
                // Log pour déboguer
                \Illuminate\Support\Facades\Log::info('Mise à jour finale du diagnostic avec valeurs: ', [
                    'nb_leds_hs' => $diagnostic->nb_leds_hs,
                    'nb_ic_hs' => $diagnostic->nb_ic_hs,
                    'nb_masques_hs' => $diagnostic->nb_masques_hs
                ]);
            }
            
            // Mise à jour de la réparation avec les champs du formulaire
            $reparation = $intervention->reparation;
            if ($reparation) {
                $reparation->nb_leds_remplacees = (int) $request->input('reparation_nb_leds_remplacees', 0);
                $reparation->nb_ic_remplaces = (int) $request->input('reparation_nb_ic_remplaces', 0);
                $reparation->nb_masques_remplaces = (int) $request->input('reparation_nb_masques_remplaces', 0);
                $reparation->remarques = $request->input('reparation_remarques', '');
                $reparation->fake_pcb_pose = $request->has('reparation_fake_pcb_pose') ? true : false;
                $reparation->save();
                
                // Log pour déboguer
                \Illuminate\Support\Facades\Log::info('Mise à jour finale de la réparation avec valeurs: ', [
                    'nb_leds_remplacees' => $reparation->nb_leds_remplacees,
                    'nb_ic_remplaces' => $reparation->nb_ic_remplaces,
                    'nb_masques_remplaces' => $reparation->nb_masques_remplaces
                ]);
            }
            
            // Finaliser l'intervention
            $intervention->date_fin = now();
            // $intervention->etat = 'Terminée'; // Colonne non existante
            $intervention->is_completed = true; // Utiliser is_completed à la place
            $intervention->save();
            
            // Mettre à jour le module avec l'état sélectionné
            $module = $intervention->module;
            $module->est_occupe = false;
            $module->etat = $request->input('module_etat', 'termine'); // Utiliser l'état sélectionné par l'utilisateur
            $module->save();
            
            // Log l'action
            \Illuminate\Support\Facades\Log::info('Intervention #' . $intervention->id . ' terminée par l\'utilisateur #' . Auth::id());
            
            // Récupérer l'ID du chantier associé à cette intervention
            $chantierId = $intervention->module->dalle->produit->chantier->id;
            
            // Rediriger vers la page du chantier
            return redirect()->route('chantiers.show', $chantierId)
                ->with('success', 'Intervention terminée avec succès.');
                
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors de l\'enregistrement de l\'intervention: ' . $e->getMessage());
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
        // Calculer le temps écoulé depuis le début ou la dernière reprise
        $debut = $intervention->date_reprise ?? $intervention->date_debut;
        $maintenant = now();
        $tempsEcoule = $debut->diffInSeconds($maintenant);
        
        // Mettre à jour le temps total
        $intervention->temps_total += $tempsEcoule;
        $intervention->date_pause = $maintenant;
        $intervention->date_reprise = null;
        $intervention->save();
        
        return response()->json([
            'success' => true,
            'temps_total' => $intervention->temps_total,
            'temps_formate' => $this->formatTemps($intervention->temps_total)
        ]);
    }

    /**
     * Reprendre une intervention en pause
     */
    public function resume(Intervention $intervention)
    {
        $intervention->date_reprise = now();
        $intervention->save();
        
        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Enregistrer le diagnostic d'une intervention
     */
    public function storeDiagnostic(Request $request, Intervention $intervention)
    {
        // Log toutes les données du formulaire pour le débogage
        \Illuminate\Support\Facades\Log::info('Données reçues dans storeDiagnostic:', [
            'all_request_data' => $request->all(),
            'diagnostic_nb_leds_hs' => $request->input('diagnostic_nb_leds_hs'),
            'diagnostic_nb_ic_hs' => $request->input('diagnostic_nb_ic_hs'),
            'diagnostic_nb_masques_hs' => $request->input('diagnostic_nb_masques_hs'),
            'intervention_id' => $intervention->id
        ]);
        
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
        
        \Illuminate\Support\Facades\Log::info('Données validées dans storeDiagnostic:', $validated);
        
        try {
            // Mettre à jour le numéro de série du module si fourni
            if ($request->filled('numero_serie')) {
                $module = $intervention->module;
                $module->update([
                    'numero_serie' => $request->input('numero_serie')
                ]);
            }
            
            // Créer ou mettre à jour le diagnostic
            $diagnostic = $intervention->diagnostic;
            
            // Créer un diagnostic directement en SQL
            $nbLedsHs = max(0, (int) $request->input('diagnostic_nb_leds_hs', 0));
            $nbIcHs = max(0, (int) $request->input('diagnostic_nb_ic_hs', 0));
            $nbMasquesHs = max(0, (int) $request->input('diagnostic_nb_masques_hs', 0));
            $remarques = $request->input('diagnostic_remarques', '');
            $poseFakePcb = $request->has('diagnostic_pose_fake_pcb') ? 1 : 0;
            $cause = $request->input('diagnostic_cause');
            
            \Illuminate\Support\Facades\Log::info('Préparation des valeurs pour le diagnostic:', [
                'diagnostic_exists' => $diagnostic ? 'oui' : 'non',
                'nb_leds_hs' => $nbLedsHs,
                'nb_ic_hs' => $nbIcHs, 
                'nb_masques_hs' => $nbMasquesHs,
                'pose_fake_pcb' => $poseFakePcb,
                'cause' => $cause
            ]);
            
            if (!$diagnostic) {
                // Insertion directe en SQL
                \Illuminate\Support\Facades\DB::statement("
                    INSERT INTO diagnostics (intervention_id, nb_leds_hs, nb_ic_hs, nb_masques_hs, remarques, pose_fake_pcb, cause, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ", [
                    $intervention->id,
                    $nbLedsHs,
                    $nbIcHs,
                    $nbMasquesHs,
                    $remarques,
                    $poseFakePcb,
                    $cause,
                    now(),
                    now()
                ]);
                
                // Récupérer le diagnostic qui vient d'être créé
                $diagnostic = \Illuminate\Support\Facades\DB::table('diagnostics')
                    ->where('intervention_id', $intervention->id)
                    ->latest('id')
                    ->first();
                
                \Illuminate\Support\Facades\Log::info('Diagnostic créé avec SQL:', (array) $diagnostic);
            } else {
                // Mise à jour directe en SQL
                \Illuminate\Support\Facades\DB::statement("
                    UPDATE diagnostics
                    SET nb_leds_hs = ?,
                        nb_ic_hs = ?,
                        nb_masques_hs = ?,
                        remarques = ?,
                        pose_fake_pcb = ?,
                        cause = ?,
                        updated_at = ?
                    WHERE id = ?
                ", [
                    $nbLedsHs,
                    $nbIcHs,
                    $nbMasquesHs,
                    $remarques,
                    $poseFakePcb,
                    $cause,
                    now(),
                    $diagnostic->id
                ]);
                
                // Récupérer le diagnostic mis à jour
                $diagnostic = \Illuminate\Support\Facades\DB::table('diagnostics')
                    ->where('id', $diagnostic->id)
                    ->first();
                
                \Illuminate\Support\Facades\Log::info('Diagnostic mis à jour avec SQL:', (array) $diagnostic);
            }
            
            // Vérifier que les données sont bien dans la base de données
            $diagnosticVerif = \Illuminate\Support\Facades\DB::table('diagnostics')
                ->where('intervention_id', $intervention->id)
                ->latest('id')
                ->first();
                
            \Illuminate\Support\Facades\Log::info('Vérification après enregistrement:', [
                'diagnosticVerif' => (array) $diagnosticVerif
            ]);
            
            // Mettre à jour le temps total
            $intervention->temps_total = $request->input('temps_total', 0);
            $intervention->save();
            
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
        // Log toutes les données du formulaire pour le débogage
        \Illuminate\Support\Facades\Log::info('Données reçues dans storeReparation:', [
            'all_request_data' => $request->all(),
            'reparation_nb_leds_remplacees' => $request->input('reparation_nb_leds_remplacees'),
            'reparation_nb_ic_remplaces' => $request->input('reparation_nb_ic_remplaces'),
            'reparation_nb_masques_remplaces' => $request->input('reparation_nb_masques_remplaces'),
            'intervention_id' => $intervention->id
        ]);
        
        $validated = $request->validate([
            'reparation_nb_leds_remplacees' => 'required|integer|min:0',
            'reparation_nb_ic_remplaces' => 'required|integer|min:0',
            'reparation_nb_masques_remplaces' => 'required|integer|min:0',
            'reparation_remarques' => 'nullable|string',
            'reparation_fake_pcb_pose' => 'nullable|boolean',
            'temps_total' => 'required|integer|min:0',
        ]);
        
        \Illuminate\Support\Facades\Log::info('Données validées dans storeReparation:', $validated);
        
        try {
            // Créer ou mettre à jour la réparation
            $reparation = $intervention->reparation;
            
            // Créer une réparation directement en SQL
            $nbLedsRemplacees = max(0, (int) $request->input('reparation_nb_leds_remplacees', 0));
            $nbIcRemplaces = max(0, (int) $request->input('reparation_nb_ic_remplaces', 0));
            $nbMasquesRemplaces = max(0, (int) $request->input('reparation_nb_masques_remplaces', 0));
            $remarques = $request->input('reparation_remarques', '');
            $fakePcbPose = $request->has('reparation_fake_pcb_pose') ? 1 : 0;
            
            \Illuminate\Support\Facades\Log::info('Préparation des valeurs pour la réparation:', [
                'reparation_exists' => $reparation ? 'oui' : 'non',
                'nb_leds_remplacees' => $nbLedsRemplacees,
                'nb_ic_remplaces' => $nbIcRemplaces, 
                'nb_masques_remplaces' => $nbMasquesRemplaces,
                'fake_pcb_pose' => $fakePcbPose
            ]);
            
            if (!$reparation) {
                // Insertion directe en SQL
                \Illuminate\Support\Facades\DB::statement("
                    INSERT INTO reparations (intervention_id, nb_leds_remplacees, nb_ic_remplaces, nb_masques_remplaces, remarques, fake_pcb_pose, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ", [
                    $intervention->id,
                    $nbLedsRemplacees,
                    $nbIcRemplaces,
                    $nbMasquesRemplaces,
                    $remarques,
                    $fakePcbPose,
                    now(),
                    now()
                ]);
                
                // Récupérer la réparation qui vient d'être créée
                $reparation = \Illuminate\Support\Facades\DB::table('reparations')
                    ->where('intervention_id', $intervention->id)
                    ->latest('id')
                    ->first();
                
                \Illuminate\Support\Facades\Log::info('Réparation créée avec SQL:', (array) $reparation);
            } else {
                // Mise à jour directe en SQL
                \Illuminate\Support\Facades\DB::statement("
                    UPDATE reparations
                    SET nb_leds_remplacees = ?,
                        nb_ic_remplaces = ?,
                        nb_masques_remplaces = ?,
                        remarques = ?,
                        fake_pcb_pose = ?,
                        updated_at = ?
                    WHERE id = ?
                ", [
                    $nbLedsRemplacees,
                    $nbIcRemplaces,
                    $nbMasquesRemplaces,
                    $remarques,
                    $fakePcbPose,
                    now(),
                    $reparation->id
                ]);
                
                // Récupérer la réparation mise à jour
                $reparation = \Illuminate\Support\Facades\DB::table('reparations')
                    ->where('id', $reparation->id)
                    ->first();
                
                \Illuminate\Support\Facades\Log::info('Réparation mise à jour avec SQL:', (array) $reparation);
            }
            
            // Vérifier que les données sont bien dans la base de données
            $reparationVerif = \Illuminate\Support\Facades\DB::table('reparations')
                ->where('intervention_id', $intervention->id)
                ->latest('id')
                ->first();
                
            \Illuminate\Support\Facades\Log::info('Vérification après enregistrement de réparation:', [
                'reparationVerif' => (array) $reparationVerif
            ]);
            
            // Mettre à jour le temps total
            $intervention->temps_total = $request->input('temps_total', 0);
            $intervention->save();
            
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
            // Créer un diagnostic vide pour cette intervention
            $diagnostic = new \App\Models\Diagnostic([
                'intervention_id' => $intervention->id,
                'nb_leds_hs' => 0,
                'nb_ic_hs' => 0,
                'nb_masques_hs' => 0,
                'description' => '',
                'conclusion' => ''
            ]);
            $diagnostic->save();
            
            // Recharger l'intervention pour inclure le nouveau diagnostic
            $intervention = $this->interventionRepository->findById($intervention->id);
        }
        
        // Vérifier si l'intervention a une réparation associée
        if (!$intervention->reparation) {
            // Créer une réparation vide pour cette intervention
            $reparation = new \App\Models\Reparation([
                'intervention_id' => $intervention->id,
                'nb_leds_remplacees' => 0,
                'nb_ic_remplaces' => 0,
                'nb_masques_remplaces' => 0,
                'description' => '',
                'actions' => ''
            ]);
            $reparation->save();
            
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
            // Récupérer le diagnostic et la réparation
            $diagnostic = $intervention->diagnostic;
            $reparation = $intervention->reparation;
            
            // Mise à jour de l'intervention principale
            $intervention->update([
                'temps_total' => $request->input('temps_total'),
                // Si le champ etat n'est pas présent, conserver l'état actuel
                'etat' => $request->input('etat', $intervention->etat),
            ]);
            
            // Mise à jour du diagnostic
            if ($diagnostic) {
                $diagnostic->nb_leds_hs = (int) $request->input('diagnostic_nb_leds_hs', 0);
                $diagnostic->nb_ic_hs = (int) $request->input('diagnostic_nb_ic_hs', 0);
                $diagnostic->nb_masques_hs = (int) $request->input('diagnostic_nb_masques_hs', 0);
                $diagnostic->remarques = $request->input('diagnostic_remarques', '');
                $diagnostic->pose_fake_pcb = $request->has('diagnostic_pose_fake_pcb') ? true : false;
                $diagnostic->save();
                
                // Log pour déboguer
                \Illuminate\Support\Facades\Log::info('Update - Diagnostic mis à jour: ', [
                    'diagnostic_id' => $diagnostic->id,
                    'nb_leds_hs' => $diagnostic->nb_leds_hs,
                    'nb_ic_hs' => $diagnostic->nb_ic_hs,
                    'nb_masques_hs' => $diagnostic->nb_masques_hs
                ]);
            }
            
            // Mise à jour de la réparation
            if ($reparation) {
                $reparation->nb_leds_remplacees = (int) $request->input('reparation_nb_leds_remplacees', 0);
                $reparation->nb_ic_remplaces = (int) $request->input('reparation_nb_ic_remplaces', 0);
                $reparation->nb_masques_remplaces = (int) $request->input('reparation_nb_masques_remplaces', 0);
                $reparation->remarques = $request->input('reparation_remarques', '');
                $reparation->fake_pcb_pose = $request->has('reparation_fake_pcb_pose') ? true : false;
                $reparation->save();
                
                // Log pour déboguer
                \Illuminate\Support\Facades\Log::info('Update - Réparation mise à jour: ', [
                    'reparation_id' => $reparation->id,
                    'nb_leds_remplacees' => $reparation->nb_leds_remplacees,
                    'nb_ic_remplaces' => $reparation->nb_ic_remplaces,
                    'nb_masques_remplaces' => $reparation->nb_masques_remplaces
                ]);
            }
            
            // Si l'intervention est terminée, mettre à jour le statut du module
            if ($intervention->etat === 'Terminée') {
                $module = $intervention->module;
                $module->est_occupe = false;
                
                // Vérifier si un état de module est spécifié dans le formulaire
                if ($request->has('module_etat')) {
                    $module->etat = $request->input('module_etat');
                } else if ($reparation && $reparation->resultat) {
                    // Si nous avons une réparation, utiliser son résultat (si disponible)
                    if ($reparation->resultat === 'Réparé') {
                        $module->etat = 'termine';
                    } else {
                        $module->etat = 'defaillant';
                    }
                } else {
                    // Par défaut, marquer comme terminé
                    $module->etat = 'termine';
                }
                
                $module->save();
            }
            
            // Journaliser l'action
            \Illuminate\Support\Facades\Log::info('Intervention #' . $intervention->id . ' mise à jour par l\'utilisateur #' . Auth::id());
            
            return redirect()->route('interventions.show', $intervention)
                ->with('success', 'Intervention mise à jour avec succès.');
                
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors de la mise à jour de l\'intervention: ' . $e->getMessage());
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
        $module = $intervention->module;
        
        // Si l'intervention est en cours, libérer le module
        if ($intervention->etat === 'En cours') {
            $module->est_occupe = false;
            $module->save();
        }
        
        $intervention->delete();
        
        return redirect()->route('interventions.index')
            ->with('success', 'Intervention supprimée avec succès.');
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
        $interventionsEnCours = $this->interventionRepository->getByState('En cours')
            ->where('technicien_id', $technicienId);
        $interventionsRecentes = $this->interventionRepository->getByTechnicien($technicienId)
            ->take(10);
        
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