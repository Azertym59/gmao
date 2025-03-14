<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Dalle;
use App\Models\Produit;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Afficher la liste des modules
     */
    public function index()
    {
        $modules = Module::with('dalle.produit.chantier')->get();
        return view('modules.index', compact('modules'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $dalle_id = $request->input('dalle_id');
        $dalle = null;
        
        if ($dalle_id) {
            $dalle = Dalle::with('produit.chantier.client')->find($dalle_id);
        } else {
            $dalles = Dalle::with('produit.chantier.client')->get();
            return view('modules.select_dalle', compact('dalles'));
        }
        
        return view('modules.create', compact('dalle'));
    }

    /**
     * Stocker un nouveau module
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dalle_id' => 'required|exists:dalles,id',
            'largeur' => 'required|numeric|min:1|max:1000',
            'hauteur' => 'required|numeric|min:1|max:1000',
            'nb_pixels_largeur' => 'required|integer|min:1|max:1000',
            'nb_pixels_hauteur' => 'required|integer|min:1|max:1000',
            'carte_reception' => 'nullable|string|max:255',
            'hub' => 'nullable|string|max:255',
            'driver' => 'nullable|string|max:255',
            'shift_register' => 'nullable|string|max:255',
            'buffer' => 'nullable|string|max:255',
            'reference_module' => 'nullable|string|max:255',
            'position_lettre' => 'nullable|string|max:10',
            'position_x' => 'nullable|integer|min:0',
            'position_y' => 'nullable|integer|min:0',
            'etat' => 'nullable|in:non_commence,en_cours,defaillant,termine',
        ]);
        
        // Si un numéro de référence est fourni dans le champ 'reference', l'utiliser pour reference_module
        if ($request->has('reference') && !empty($request->input('reference'))) {
            $validated['reference_module'] = $request->input('reference');
        }
        
        // Par défaut, l'état est "non_commence" s'il n'est pas fourni
        if (!isset($validated['etat'])) {
            $validated['etat'] = 'non_commence';
        }
        
        $module = Module::create($validated);
        
        return redirect()->route('modules.show', $module)
            ->with('success', 'Module créé avec succès.');
    }

    /**
     * Afficher un module spécifique
     */
    public function show(Module $module)
    {
        $module->load(['dalle.produit.chantier.client', 'interventions.diagnostic', 'interventions.reparation', 'interventions.technicien']);
        
        // Préparer les données des interventions pour éviter l'erreur "Attempt to read property 'name' on null"
        $interventions = [];
        foreach($module->interventions as $intervention) {
            $intervention->technicienName = $intervention->technicien ? $intervention->technicien->name : 'Non assigné';
            $interventions[] = $intervention;
        }
        
        return view('modules.show', [
            'module' => $module,
            'interventions' => $interventions,
            'technicienErrorFixed' => true, // Flag pour le template
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Module $module)
    {
        $dalles = Dalle::with('produit.chantier.client')->get();
        return view('modules.edit', compact('module', 'dalles'));
    }

    /**
 * Mettre à jour un module
 */
public function update(Request $request, Module $module)
{
    $validated = $request->validate([
        'dalle_id' => 'required|exists:dalles,id',
        'largeur' => 'required|numeric|min:1|max:1000',
        'hauteur' => 'required|numeric|min:1|max:1000',
        'nb_pixels_largeur' => 'required|integer|min:1|max:1000',
        'nb_pixels_hauteur' => 'required|integer|min:1|max:1000',
        'carte_reception' => 'nullable|string|max:255',
        'hub' => 'nullable|string|max:255',
        'driver' => 'nullable|string|max:255',
        'shift_register' => 'nullable|string|max:255',
        'buffer' => 'nullable|string|max:255',
        'etat' => 'required|in:non_commence,en_cours,defaillant,termine',
        'reference_module' => 'nullable|string|max:255',
        'technicien_id' => 'nullable|exists:users,id',
        'position_lettre' => 'nullable|string|max:10',
        'position_x' => 'nullable|integer|min:0',
        'position_y' => 'nullable|integer|min:0',
    ]);
    
    // Si un numéro de référence est fourni dans le champ 'reference', l'utiliser pour reference_module
    if ($request->has('reference') && !empty($request->input('reference'))) {
        $validated['reference_module'] = $request->input('reference');
    }
    
    // Sauvegarder l'ancien technicien_id pour comparaison
    $oldTechnicienId = $module->technicien_id;
    
    // Mettre à jour le module
    $module->update($validated);
    
    // Si un nouveau technicien est assigné, envoyer une notification
    if (isset($validated['technicien_id']) && $validated['technicien_id'] != $oldTechnicienId && $validated['technicien_id'] != null) {
        // Importer le service de notification si vous n'avez pas encore créé l'import
        if (class_exists(\App\Services\NotificationService::class)) {
            \App\Services\NotificationService::notifyModuleAssignment(
                $module,
                \App\Models\User::find($validated['technicien_id'])
            );
        }
    }
    
    return redirect()->route('modules.show', $module)
        ->with('success', 'Module modifié avec succès.');
}

    /**
     * Supprimer un module
     */
    public function destroy(Module $module)
    {
        $module->delete();
        
        return redirect()->route('modules.index')
            ->with('success', 'Module supprimé avec succès.');
    }

    /**
 * Afficher le formulaire de création en masse
 */
public function showMassCreateForm(Produit $produit)
{
    return view('modules.mass-create', compact('produit'));
}

/**
 * Traiter la création en masse
 */
public function processMassCreate(Request $request, Produit $produit)
{
    $validated = $request->validate([
        'mode' => 'required|in:flightcase,individuel',
        'nb_flightcases' => 'required_if:mode,flightcase|integer|min:1',
        'nb_dalles_par_flightcase' => 'required_if:mode,flightcase|integer|min:1',
        'nb_modules_par_dalle' => 'required_if:mode,flightcase|integer|min:1',
        'nb_modules_total' => 'required_if:mode,individuel|integer|min:1',
        // Caractéristiques communes des dalles
        'largeur_dalle' => 'required|numeric|min:1',
        'hauteur_dalle' => 'required|numeric|min:1',
        'alimentation_dalle' => 'required|string',
        // Caractéristiques communes des modules
        'largeur_module' => 'required|numeric|min:1',
        'hauteur_module' => 'required|numeric|min:1',
        'nb_pixels_largeur' => 'required|integer|min:1',
        'nb_pixels_hauteur' => 'required|integer|min:1',
        'carte_reception' => 'nullable|string',
        'hub' => 'nullable|string',
        'driver' => 'nullable|string',
        'shift_register' => 'nullable|string',
        'buffer' => 'nullable|string',
    ]);
    
    $count = 0;
    
    if ($validated['mode'] == 'flightcase') {
        // Création structurée Flight case > Dalle > Module
        for ($f = 1; $f <= $validated['nb_flightcases']; $f++) {
            for ($d = 1; $d <= $validated['nb_dalles_par_flightcase']; $d++) {
                // Créer la dalle
                $dalle = Dalle::create([
                    'produit_id' => $produit->id,
                    'largeur' => $validated['largeur_dalle'],
                    'hauteur' => $validated['hauteur_dalle'],
                    'nb_modules' => $validated['nb_modules_par_dalle'],
                    'alimentation' => $validated['alimentation_dalle'],
                    'carte_reception' => $validated['carte_reception'], // Nouveau champ
                    'hub' => $validated['hub'], // Nouveau champ
                    'reference_dalle' => "FC{$f}-D{$d}" // Reference automatique
                ]);

                // Créer les modules pour cette dalle
                for ($m = 1; $m <= $validated['nb_modules_par_dalle']; $m++) {
                    Module::create([
                        'dalle_id' => $dalle->id,
                        'largeur' => $validated['largeur_module'],
                        'hauteur' => $validated['hauteur_module'],
                        'nb_pixels_largeur' => $validated['nb_pixels_largeur'],
                        'nb_pixels_hauteur' => $validated['nb_pixels_hauteur'],
                        'driver' => $validated['driver'],
                        'shift_register' => $validated['shift_register'],
                        'buffer' => $validated['buffer'],
                        'etat' => 'non_commence',
                        'reference_module' => "FC{$f}-D{$d}-M{$m}"
                    ]);
                    $count++;
                }
            }
        }
    } else {
        // Création de modules individuels
        // Créer une dalle "virtuelle" pour contenir tous les modules individuels
        $dalle = Dalle::create([
            'produit_id' => $produit->id,
            'largeur' => $validated['largeur_dalle'],
            'hauteur' => $validated['hauteur_dalle'],
            'nb_modules' => $validated['nb_modules_total'],
            'alimentation' => $validated['alimentation_dalle'],
            'reference_dalle' => "INDIVIDUEL"
        ]);

        // Créer tous les modules
        for ($m = 1; $m <= $validated['nb_modules_total']; $m++) {
            Module::create([
                'dalle_id' => $dalle->id,
                'largeur' => $validated['largeur_module'],
                'hauteur' => $validated['hauteur_module'],
                'nb_pixels_largeur' => $validated['nb_pixels_largeur'],
                'nb_pixels_hauteur' => $validated['nb_pixels_hauteur'],
                'carte_reception' => $validated['carte_reception'],
                'hub' => $validated['hub'],
                'driver' => $validated['driver'],
                'shift_register' => $validated['shift_register'],
                'buffer' => $validated['buffer'],
                'etat' => 'non_commence',
                'reference_module' => "IND-{$m}"
            ]);
            $count++;
        }
    }

    return redirect()->route('produits.show', $produit)
        ->with('success', "{$count} modules créés avec succès.");
}
}