<?php

namespace App\Http\Controllers;

use App\Models\Dalle;
use App\Models\Produit;
use Illuminate\Http\Request;

class DalleController extends Controller
{
    /**
     * Afficher la liste des dalles
     */
    public function index()
    {
        $dalles = Dalle::with('produit.chantier')->get();
        return view('dalles.index', compact('dalles'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $produit_id = $request->input('produit_id');
        $produit = null;
        
        if ($produit_id) {
            $produit = Produit::with('chantier.client')->find($produit_id);
        } else {
            $produits = Produit::with('chantier.client')->get();
            return view('dalles.select_produit', compact('produits'));
        }
        
        return view('dalles.create', compact('produit'));
    }

    /**
     * Stocker une nouvelle dalle
     */
    public function store(Request $request)
    {
        // Valider les données de base de la dalle
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'largeur' => 'required|numeric|min:1|max:10000',
            'hauteur' => 'required|numeric|min:1|max:10000',
            'nb_modules' => 'required|integer|min:1|max:100',
            'alimentation' => 'required|string|max:255',
            'reference_dalle' => 'nullable|string|max:255',
            'carte_reception' => 'nullable|string|max:255',
            'hub' => 'nullable|string|max:255',
            'nb_colonnes' => 'required|integer|min:1|max:20',
            'nb_lignes' => 'required|integer|min:1|max:20',
            'disposition_type' => 'required|in:standard,personnalisee',
            'mode_emballage' => 'nullable|in:flightcase,carton,palette,autre',
            'mode_emballage_detail' => 'nullable|required_if:mode_emballage,autre|string|max:255',
        ]);
        
        // Vérifier que le nombre de modules correspond aux colonnes et lignes
        if ($validated['disposition_type'] === 'standard' && 
            $validated['nb_modules'] != $validated['nb_colonnes'] * $validated['nb_lignes']) {
            return back()->withInput()->withErrors([
                'nb_modules' => 'Le nombre de modules doit être égal au nombre de colonnes multipliées par le nombre de lignes.'
            ]);
        }
        
        // Créer la dalle
        $dalle = Dalle::create($validated);
        
        // Si la disposition est personnalisée, stocker le schéma
        if ($validated['disposition_type'] === 'personnalisee' && $request->has('disposition_schema')) {
            $dalle->disposition_schema = json_encode($request->input('disposition_schema'));
            $dalle->save();
        }
        
        // Générer les modules avec leurs positions et lettres
        $this->genererModules($dalle);
        
        return redirect()->route('dalles.show', $dalle)
            ->with('success', 'Dalle créée avec succès. Les modules ont été générés automatiquement.');
    }
    
    /**
     * Générer automatiquement les modules pour la dalle
     * avec attribution des lettres de position
     */
    private function genererModules(Dalle $dalle)
    {
        // Déterminer les dimensions des modules
        $largeurModule = $dalle->largeur / $dalle->nb_colonnes;
        $hauteurModule = $dalle->hauteur / $dalle->nb_lignes;
        
        // Calculer le nombre de pixels basé sur le pitch du produit
        $produit = Produit::find($dalle->produit_id);
        $nbPixelsLargeur = round($largeurModule / $produit->pitch);
        $nbPixelsHauteur = round($hauteurModule / $produit->pitch);
        
        // Générer les modules selon la disposition
        if ($dalle->disposition_type === 'standard') {
            $moduleCount = 0;
            $lettreActuelle = 'A';
            
            // Créer les modules en suivant les colonnes et les lignes
            for ($colonne = 0; $colonne < $dalle->nb_colonnes; $colonne++) {
                for ($ligne = 0; $ligne < $dalle->nb_lignes; $ligne++) {
                    if ($moduleCount < $dalle->nb_modules) {
                        // Créer le module
                        $module = new \App\Models\Module([
                            'dalle_id' => $dalle->id,
                            'largeur' => $largeurModule,
                            'hauteur' => $hauteurModule,
                            'nb_pixels_largeur' => $nbPixelsLargeur,
                            'nb_pixels_hauteur' => $nbPixelsHauteur,
                            'carte_reception' => $dalle->carte_reception,
                            'hub' => $dalle->hub,
                            'position_lettre' => $lettreActuelle,
                            'position_x' => $colonne,
                            'position_y' => $ligne,
                            'etat' => 'non_commence'
                        ]);
                        $module->save();
                        
                        // Incrémenter la lettre pour le prochain module
                        $lettreActuelle = $this->incrementerLettre($lettreActuelle);
                        $moduleCount++;
                    }
                }
            }
        } else if ($dalle->disposition_type === 'personnalisee' && $dalle->disposition_schema) {
            // Traiter la disposition personnalisée
            $schema = json_decode($dalle->disposition_schema, true);
            $lettreActuelle = 'A';
            
            if (is_array($schema)) {
                foreach ($schema as $position) {
                    if (isset($position['x'], $position['y'])) {
                        // Créer le module selon le schéma personnalisé
                        $module = new \App\Models\Module([
                            'dalle_id' => $dalle->id,
                            'largeur' => $largeurModule,
                            'hauteur' => $hauteurModule,
                            'nb_pixels_largeur' => $nbPixelsLargeur,
                            'nb_pixels_hauteur' => $nbPixelsHauteur,
                            'carte_reception' => $dalle->carte_reception,
                            'hub' => $dalle->hub,
                            'position_lettre' => $lettreActuelle,
                            'position_x' => $position['x'],
                            'position_y' => $position['y'],
                            'etat' => 'non_commence'
                        ]);
                        $module->save();
                        
                        // Incrémenter la lettre pour le prochain module
                        $lettreActuelle = $this->incrementerLettre($lettreActuelle);
                    }
                }
            }
        }
    }
    
    /**
     * Incrémenter une lettre (A->B, Z->AA, AA->AB, etc.)
     */
    private function incrementerLettre($lettre)
    {
        // Pour les lettres simples (A-Z)
        if (strlen($lettre) === 1) {
            if ($lettre === 'Z') {
                return 'AA';
            } else {
                return chr(ord($lettre) + 1);
            }
        } 
        // Pour les lettres doubles (AA-ZZ)
        else {
            $dernierCaractere = substr($lettre, -1);
            $reste = substr($lettre, 0, -1);
            
            if ($dernierCaractere === 'Z') {
                return $this->incrementerLettre($reste) . 'A';
            } else {
                return $reste . chr(ord($dernierCaractere) + 1);
            }
        }
    }

    /**
     * Afficher une dalle spécifique
     */
    public function show(Dalle $dalle)
    {
        $dalle->load(['produit.chantier.client', 'modules']);
        return view('dalles.show', compact('dalle'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Dalle $dalle)
    {
        $produits = Produit::with('chantier.client')->get();
        return view('dalles.edit', compact('dalle', 'produits'));
    }

    /**
     * Mettre à jour une dalle
     */
    public function update(Request $request, Dalle $dalle)
    {
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'largeur' => 'required|numeric|min:1|max:10000',
            'hauteur' => 'required|numeric|min:1|max:10000',
            'nb_modules' => 'required|integer|min:1|max:100',
            'alimentation' => 'required|string|max:255',
            'reference_dalle' => 'nullable|string|max:255',
        ]);
        
        $dalle->update($validated);
        
        return redirect()->route('dalles.show', $dalle)
            ->with('success', 'Dalle modifiée avec succès.');
    }

    /**
     * Supprimer une dalle
     */
    public function destroy(Dalle $dalle)
    {
        $dalle->delete();
        
        return redirect()->route('dalles.index')
            ->with('success', 'Dalle supprimée avec succès.');
    }
}