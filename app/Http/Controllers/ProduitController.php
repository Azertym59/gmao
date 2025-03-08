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
        $produits = Produit::with('chantier.client')->get();
        return view('produits.index', compact('produits'));
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
        ]);
        
        $produit = Produit::create($validated);
        
        return redirect()->route('produits.show', $produit)
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Afficher un produit spécifique
     */
    public function show(Produit $produit)
    {
        $produit->load(['chantier.client', 'dalles.modules']);
        return view('produits.show', compact('produit'));
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
        ]);
        
        $produit->update($validated);
        
        return redirect()->route('produits.show', $produit)
            ->with('success', 'Produit modifié avec succès.');
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