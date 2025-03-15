<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Chantier;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Afficher la liste des produits
     */
    public function index()
    {
        // Mettre à jour tous les produits qui ont INCONNU comme marque ou modèle
        $this->fixUnknownProducts();
        
        $produits = Produit::with('chantier.client')->get();
        return view('produits.index', compact('produits'));
    }
    
    /**
     * Méthode pour corriger tous les produits avec valeurs INCONNU
     */
    private function fixUnknownProducts()
    {
        $produits = Produit::where('marque', 'INCONNU')->orWhere('modele', 'INCONNU')->get();
        
        foreach($produits as $produit) {
            $chantier = $produit->chantier;
            $client = $chantier->client;
            
            // Définir des valeurs par défaut significatives
            $defaultMarque = $client->societe ? strtoupper($client->societe) : 'Écran LED';
            $defaultModele = 'Réparation ' . date('Y');
            
            // Mettre à jour uniquement les champs nécessaires
            if ($produit->marque === 'INCONNU') {
                $produit->marque = $defaultMarque;
            }
            
            if ($produit->modele === 'INCONNU') {
                $produit->modele = $defaultModele;
            }
            
            $produit->save();
        }
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $chantier_id = $request->input('chantier_id');
        $chantier = null;
        
        if ($chantier_id) {
            $chantier = Chantier::with('client')->find($chantier_id);
        } else {
            $chantiers = Chantier::with('client')->get();
            return view('produits.select_chantier', compact('chantiers'));
        }
        
        return view('produits.create', compact('chantier'));
    }

    /**
     * Stocker un nouveau produit
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'chantier_id' => 'required|exists:chantiers,id',
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'pitch' => 'required|numeric|min:0.1|max:100',
            'utilisation' => 'required|in:indoor,outdoor',
            'electronique' => 'required|in:nova,linsn,dbstar,brompton,autre',
            'electronique_detail' => 'nullable|required_if:electronique,autre|string|max:255',
            'carte_reception' => 'nullable|string|max:255',
            'hub' => 'nullable|string|max:255',
            'bain_couleur' => 'nullable|string|max:255',
            'create_variantes' => 'nullable|boolean',
            'variantes' => 'nullable|array',
            'variantes.*.carte_reception' => 'required|string|max:255',
            'variantes.*.hub' => 'required|string|max:255',
            'variantes.*.bain_couleur' => 'required|string|max:255',
            'variantes.*.variante_nom' => 'required|string|max:255',
        ]);
        
        // Créer le produit principal
        $produit = Produit::create([
            'chantier_id' => $validated['chantier_id'],
            'marque' => $validated['marque'],
            'modele' => $validated['modele'],
            'pitch' => $validated['pitch'],
            'utilisation' => $validated['utilisation'],
            'electronique' => $validated['electronique'],
            'electronique_detail' => $validated['electronique_detail'] ?? null,
            'carte_reception' => $validated['carte_reception'] ?? null,
            'hub' => $validated['hub'] ?? null,
            'bain_couleur' => $validated['bain_couleur'] ?? null,
            'is_variante' => false
        ]);
        
        // Créer les variantes si elles existent
        if (isset($validated['create_variantes']) && $validated['create_variantes'] && isset($validated['variantes'])) {
            foreach ($validated['variantes'] as $variante) {
                Produit::create([
                    'chantier_id' => $validated['chantier_id'],
                    'marque' => $validated['marque'],
                    'modele' => $validated['modele'],
                    'pitch' => $validated['pitch'],
                    'utilisation' => $validated['utilisation'],
                    'electronique' => $validated['electronique'],
                    'electronique_detail' => $validated['electronique_detail'] ?? null,
                    'carte_reception' => $variante['carte_reception'],
                    'hub' => $variante['hub'],
                    'bain_couleur' => $variante['bain_couleur'],
                    'variante_id' => $produit->id,
                    'is_variante' => true,
                    'variante_nom' => $variante['variante_nom']
                ]);
            }
        }
        
        return redirect()->route('produits.show', $produit)
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Afficher un produit spécifique
     */
    public function show(Produit $produit)
    {
        // Charger les relations nécessaires
        $produit->load(['chantier.client', 'dalles.modules']);
        
        // Si c'est un produit principal, charger aussi les variantes
        if (!$produit->is_variante) {
            $produit->load('variantes');
        } 
        // Si c'est une variante, charger le produit parent
        else {
            $produit->load('produitParent');
        }
        
        return view('produits.show', compact('produit'));
    }
    
    /**
     * Afficher le formulaire pour créer une variante de produit
     */
    public function createVariante(Produit $produit)
    {
        // S'assurer que ce n'est pas déjà une variante
        if ($produit->is_variante) {
            return redirect()->route('produits.show', $produit->variante_id)
                ->with('error', 'Impossible de créer une variante d\'une variante.');
        }
        
        return view('produits.create_variante', compact('produit'));
    }
    
    /**
     * Stocker une nouvelle variante de produit
     */
    public function storeVariante(Request $request, Produit $produit)
    {
        // S'assurer que ce n'est pas déjà une variante
        if ($produit->is_variante) {
            return redirect()->route('produits.show', $produit->variante_id)
                ->with('error', 'Impossible de créer une variante d\'une variante.');
        }
        
        $validated = $request->validate([
            'carte_reception' => 'required|string|max:255',
            'hub' => 'required|string|max:255',
            'bain_couleur' => 'required|string|max:255',
            'variante_nom' => 'required|string|max:255',
        ]);
        
        // Créer la variante
        $variante = Produit::create([
            'chantier_id' => $produit->chantier_id,
            'marque' => $produit->marque,
            'modele' => $produit->modele,
            'pitch' => $produit->pitch,
            'utilisation' => $produit->utilisation,
            'electronique' => $produit->electronique,
            'electronique_detail' => $produit->electronique_detail,
            'carte_reception' => $validated['carte_reception'],
            'hub' => $validated['hub'],
            'bain_couleur' => $validated['bain_couleur'],
            'variante_id' => $produit->id,
            'is_variante' => true,
            'variante_nom' => $validated['variante_nom']
        ]);
        
        return redirect()->route('produits.show', $produit)
            ->with('success', 'Variante créée avec succès.');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Produit $produit)
    {
        $chantiers = Chantier::with('client')->get();
        return view('produits.edit', compact('produit', 'chantiers'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'chantier_id' => 'required|exists:chantiers,id',
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'pitch' => 'required|numeric|min:0.1|max:100',
            'utilisation' => 'required|in:indoor,outdoor',
            'electronique' => 'required|in:nova,linsn,dbstar,brompton,autre',
            'electronique_detail' => 'nullable|required_if:electronique,autre|string|max:255',
            'carte_reception' => 'nullable|string|max:255',
            'hub' => 'nullable|string|max:255',
            'bain_couleur' => 'nullable|string|max:255',
            'variante_nom' => $produit->is_variante ? 'required|string|max:255' : 'nullable|string|max:255',
        ]);
        
        // Mettre à jour le produit
        $produit->update($validated);
        
        // Si c'est une variante, vérifier si nous devons mettre à jour les variantes associées
        if (!$produit->is_variante && $request->has('update_variantes') && $request->input('update_variantes')) {
            // Mettre à jour les informations de base des variantes (sauf les champs spécifiques à la variante)
            $produit->variantes()->update([
                'marque' => $validated['marque'],
                'modele' => $validated['modele'],
                'pitch' => $validated['pitch'],
                'utilisation' => $validated['utilisation'],
                'electronique' => $validated['electronique'],
                'electronique_detail' => $validated['electronique_detail'] ?? null,
            ]);
        }
        
        return redirect()->route('produits.show', $produit)
            ->with('success', 'Produit modifié avec succès.');
    }
    
    /**
     * Modifier une variante
     */
    public function editVariante(Produit $variante)
    {
        // S'assurer que c'est bien une variante
        if (!$variante->is_variante) {
            return redirect()->route('produits.edit', $variante->id)
                ->with('error', 'Ce produit n\'est pas une variante.');
        }
        
        // Charger le produit parent
        $variante->load('produitParent');
        
        return view('produits.edit_variante', compact('variante'));
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Produit $produit)
    {
        $produit->delete();
        
        return redirect()->route('produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}