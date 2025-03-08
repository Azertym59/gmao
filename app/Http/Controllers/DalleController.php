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
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'largeur' => 'required|numeric|min:1|max:10000',
            'hauteur' => 'required|numeric|min:1|max:10000',
            'nb_modules' => 'required|integer|min:1|max:100',
            'alimentation' => 'required|string|max:255',
            'reference_dalle' => 'nullable|string|max:255',
        ]);
        
        $dalle = Dalle::create($validated);
        
        return redirect()->route('dalles.show', $dalle)
            ->with('success', 'Dalle créée avec succès.');
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