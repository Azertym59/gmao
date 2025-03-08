<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Module;
use App\Models\Diagnostic;
use App\Models\Reparation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InterventionController extends Controller
{
    /**
     * Afficher la liste des interventions
     */
    public function index()
    {
        $interventions = Intervention::with(['module.dalle.produit.chantier', 'technicien', 'diagnostic', 'reparation'])->get();
        return view('interventions.index', compact('interventions'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $module_id = $request->input('module_id');
        $module = null;
        
        if ($module_id) {
            $module = Module::with('dalle.produit.chantier')->find($module_id);
            
            // Vérifier si le module est déjà occupé
            if ($module->est_occupe) {
                return redirect()->back()->with('error', "Ce module est déjà en cours d'intervention par un autre technicien.");
            }
            
            // Marquer le module comme occupé et démarrer l'intervention
            $module->est_occupe = true;
            $module->technicien_id = auth()->id();
            $module->etat = 'en_cours';
            $module->save();
            
            // Créer une nouvelle intervention avec chronométrage
            $intervention = new Intervention();
            $intervention->module_id = $module->id;
            $intervention->technicien_id = auth()->id();
            $intervention->date_debut = now();
            $intervention->temps_total = 0;
            $intervention->save();
            
            // Créer un diagnostic vide
            $diagnostic = new Diagnostic();
            $diagnostic->intervention_id = $intervention->id;
            $diagnostic->nb_leds_hs = 0;
            $diagnostic->nb_ic_hs = 0;
            $diagnostic->nb_masques_hs = 0;
            $diagnostic->save();
            
            // Créer une réparation vide
            $reparation = new Reparation();
            $reparation->intervention_id = $intervention->id;
            $reparation->nb_leds_remplacees = 0;
            $reparation->nb_ic_remplaces = 0;
            $reparation->nb_masques_remplaces = 0;
            $reparation->save();
            
            return view('interventions.edit_with_chrono', compact('intervention', 'module'));
        } else {
            $modules = Module::with('dalle.produit.chantier')
                ->where('etat', '!=', 'termine')
                ->where(function ($query) {
                    $query->where('est_occupe', false)
                          ->orWhere('technicien_id', auth()->id());
                })
                ->get();
            return view('interventions.select_module', compact('modules'));
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
     * Stocker une intervention (avec résultats du diagnostic et réparation)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'intervention_id' => 'required|exists:interventions,id',
            'temps_total' => 'required|integer|min:0',
            'diagnostic_nb_leds_hs' => 'required|integer|min:0',
            'diagnostic_nb_ic_hs' => 'required|integer|min:0',
            'diagnostic_nb_masques_hs' => 'required|integer|min:0',
            'diagnostic_remarques' => 'nullable|string',
            'reparation_nb_leds_remplacees' => 'required|integer|min:0',
            'reparation_nb_ic_remplaces' => 'required|integer|min:0',
            'reparation_nb_masques_remplaces' => 'required|integer|min:0',
            'reparation_remarques' => 'nullable|string',
        ]);
        
        // Récupérer l'intervention
        $intervention = Intervention::with(['module', 'diagnostic', 'reparation'])->findOrFail($validated['intervention_id']);
        
        // Finaliser l'intervention
        $intervention->temps_total = $validated['temps_total'];
        $intervention->date_fin = now();
        $intervention->is_completed = true;
        $intervention->save();
        
        // Mettre à jour le diagnostic
        $intervention->diagnostic->update([
            'nb_leds_hs' => $validated['diagnostic_nb_leds_hs'],
            'nb_ic_hs' => $validated['diagnostic_nb_ic_hs'],
            'nb_masques_hs' => $validated['diagnostic_nb_masques_hs'],
            'remarques' => $validated['diagnostic_remarques'],
        ]);
        
        // Mettre à jour la réparation
        $intervention->reparation->update([
            'nb_leds_remplacees' => $validated['reparation_nb_leds_remplacees'],
            'nb_ic_remplaces' => $validated['reparation_nb_ic_remplaces'],
            'nb_masques_remplaces' => $validated['reparation_nb_masques_remplaces'],
            'remarques' => $validated['reparation_remarques'],
        ]);
        
        // Mettre à jour l'état du module
        $module = $intervention->module;
        $module->est_occupe = false;
        $module->technicien_id = null;
        
        if ($validated['reparation_nb_leds_remplacees'] > 0 || 
            $validated['reparation_nb_ic_remplaces'] > 0 || 
            $validated['reparation_nb_masques_remplaces'] > 0) {
            $module->etat = 'termine';
        } else {
            $module->etat = 'defaillant';
        }
        
        $module->save();
        
        return redirect()->route('modules.show', $module)
            ->with('success', 'Intervention terminée avec succès. Temps total: ' . $this->formatTemps($intervention->temps_total));
    }

    /**
     * Afficher une intervention spécifique
     */
    public function show(Intervention $intervention)
    {
        $intervention->load(['module.dalle.produit.chantier', 'technicien', 'diagnostic', 'reparation']);
        return view('interventions.show', compact('intervention'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Intervention $intervention)
    {
        $intervention->load(['module.dalle.produit.chantier', 'diagnostic', 'reparation']);
        return view('interventions.edit', compact('intervention'));
    }

    /**
     * Mettre à jour une intervention
     */
    public function update(Request $request, Intervention $intervention)
{
    $validated = $request->validate([
        'diagnostic_nb_leds_hs' => 'required|integer|min:0',
        'diagnostic_nb_ic_hs' => 'required|integer|min:0',
        'diagnostic_nb_masques_hs' => 'required|integer|min:0',
        'diagnostic_remarques' => 'nullable|string',
        'reparation_nb_leds_remplacees' => 'required|integer|min:0',
        'reparation_nb_ic_remplaces' => 'required|integer|min:0',
        'reparation_nb_masques_remplaces' => 'required|integer|min:0',
        'reparation_remarques' => 'nullable|string',
        'temps_total' => 'required|integer|min:0',
        'module_etat' => 'required|in:non_commence,en_cours,defaillant,termine',
    ]);
    
    // Mettre à jour l'intervention
    $intervention->temps_total = $validated['temps_total'];
    $intervention->save();
    
    // Mettre à jour le diagnostic
    if ($intervention->diagnostic) {
        $intervention->diagnostic->update([
            'nb_leds_hs' => $validated['diagnostic_nb_leds_hs'],
            'nb_ic_hs' => $validated['diagnostic_nb_ic_hs'],
            'nb_masques_hs' => $validated['diagnostic_nb_masques_hs'],
            'remarques' => $validated['diagnostic_remarques'],
        ]);
    }
    
    // Mettre à jour la réparation
    if ($intervention->reparation) {
        $intervention->reparation->update([
            'nb_leds_remplacees' => $validated['reparation_nb_leds_remplacees'],
            'nb_ic_remplaces' => $validated['reparation_nb_ic_remplaces'],
            'nb_masques_remplaces' => $validated['reparation_nb_masques_remplaces'],
            'remarques' => $validated['reparation_remarques'],
        ]);
    }
    
    // Mettre à jour l'état du module explicitement
    $module = $intervention->module;
    $module->etat = $validated['module_etat'];
    $module->save();
    
    return redirect()->route('interventions.show', $intervention)
        ->with('success', 'Intervention mise à jour avec succès.');
}

    /**
     * Supprimer une intervention
     */
    public function destroy(Intervention $intervention)
    {
        $module = $intervention->module;
        $intervention->delete();
        
        return redirect()->route('modules.show', $module)
            ->with('success', 'Intervention supprimée avec succès.');
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