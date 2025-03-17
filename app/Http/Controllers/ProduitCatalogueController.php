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
            'carte_reception' => 'nullable|string|max:255',
        ]);

        // Créer le produit de base
        $produit = ProduitCatalogue::create($validated);
        
        // Mettre à jour les options de cartes de réception disponibles en fonction du système électronique
        $this->updateCartesReceptionOptions($produit);

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
            'carte_reception' => 'nullable|string|max:255',
        ]);

        $produitsCatalogue->update($validated);
        
        // Mettre à jour les options de cartes de réception disponibles
        $this->updateCartesReceptionOptions($produitsCatalogue);

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
    
    /**
     * Met à jour les options de cartes de réception disponibles en fonction du système électronique
     */
    protected function updateCartesReceptionOptions($produit)
    {
        $systems = [
            'nova' => [
                'Novastar Taurus',
                'Novastar MCTRL300',
                'Novastar MCTRL660',
                'Novastar MCTRL4K',
                'Novastar A5s Plus',
                'Novastar A8s Plus',
            ],
            'linsn' => [
                'Linsn TS802',
                'Linsn TS852',
                'Linsn TS902',
                'Linsn RV908',
                'Linsn RV908M',
            ],
            'colorlight' => [
                'Colorlight Z6',
                'Colorlight S6',
                'Colorlight M9',
                'Colorlight X8',
            ],
            'dbstar' => [
                'DBstar HVT11IN',
                'DBstar MRF4IN',
            ],
            'barco' => [
                'Barco E2',
                'Barco S3',
                'Barco EventMaster',
            ],
            'brompton' => [
                'Brompton Tessera S4',
                'Brompton Tessera M2',
                'Brompton Tessera SB40',
                'Brompton Tessera R2',
            ],
            'autre' => [],
        ];
        
        // Si une carte de réception a été définie et que le système est connu
        if ($produit->carte_reception && isset($systems[$produit->electronique])) {
            // Mettre à jour la liste des cartes disponibles pour ce système électronique
            $currentCards = json_decode($produit->cartes_reception_disponibles ?? '[]', true) ?: [];
            
            // Si la carte actuelle n'est pas déjà dans la liste
            if (!in_array($produit->carte_reception, $currentCards) && 
                !in_array($produit->carte_reception, $systems[$produit->electronique])) {
                // Ajouter la nouvelle carte
                $systems[$produit->electronique][] = $produit->carte_reception;
            }
        }
        
        // Mettre à jour avec les cartes disponibles pour le système
        if (isset($systems[$produit->electronique])) {
            $produit->cartes_reception_disponibles = json_encode($systems[$produit->electronique]);
            $produit->save();
        }
    }
}