<?php

namespace App\Http\Controllers;

use App\Models\ProduitCatalogue;
use Illuminate\Http\Request;

class ProduitCatalogueController extends Controller
{
    /**
     * Afficher la liste des produits du catalogue
     */
    public function index()
    {
        $produits = ProduitCatalogue::all();
        return view('produits_catalogue.index', compact('produits'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('produits_catalogue.create');
    }

    /**
     * Stocker un nouveau produit dans le catalogue
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'pitch' => 'required|numeric|min:0.1|max:100',
            'utilisation' => 'required|in:indoor,outdoor',
            'electronique' => 'required|in:nova,linsn,dbstar,brompton,autre',
            'electronique_detail' => 'nullable|required_if:electronique,autre|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        $produit = ProduitCatalogue::create($validated);

        return redirect()->route('produits-catalogue.index')
            ->with('success', 'Produit ajouté au catalogue avec succès.');
    }

    /**
     * Afficher un produit du catalogue
     */
    public function show(ProduitCatalogue $produitsCatalogue)
    {
        return view('produits_catalogue.show', ['produit' => $produitsCatalogue]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(ProduitCatalogue $produitsCatalogue)
    {
        return view('produits_catalogue.edit', ['produit' => $produitsCatalogue]);
    }

    /**
     * Mettre à jour un produit du catalogue
     */
    public function update(Request $request, ProduitCatalogue $produitsCatalogue)
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'pitch' => 'required|numeric|min:0.1|max:100',
            'utilisation' => 'required|in:indoor,outdoor',
            'electronique' => 'required|in:nova,linsn,dbstar,brompton,autre',
            'electronique_detail' => 'nullable|required_if:electronique,autre|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        $produitsCatalogue->update($validated);

        return redirect()->route('produits-catalogue.index')
            ->with('success', 'Produit du catalogue mis à jour avec succès.');
    }

    /**
     * Supprimer un produit du catalogue
     */
    public function destroy(ProduitCatalogue $produitsCatalogue)
    {
        $produitsCatalogue->delete();

        return redirect()->route('produits-catalogue.index')
            ->with('success', 'Produit supprimé du catalogue.');
    }
}