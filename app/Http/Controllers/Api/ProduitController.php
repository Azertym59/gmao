<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Chantier;
use App\Models\ProduitCatalogue;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Récupérer la liste des produits filtrée par divers critères
     */
    public function index(Request $request)
    {
        $query = Produit::with(['chantier.client', 'dalles']);
        
        // Filtre par marque
        if ($request->filled('marque')) {
            $query->where('marque', 'like', '%' . $request->input('marque') . '%');
        }
        
        // Filtre par bain de couleur
        if ($request->filled('bain_couleur')) {
            $query->where('bain_couleur', $request->input('bain_couleur'));
        }
        
        // Filtre par référence de chantier
        if ($request->filled('reference')) {
            $reference = $request->input('reference');
            $query->whereHas('chantier', function($q) use ($reference) {
                $q->where('reference', 'like', '%' . $reference . '%');
            });
        }
        
        // Filtre par garantie/achat
        if ($request->filled('warranty')) {
            $warranty = $request->input('warranty');
            
            if ($warranty === 'client_achat_1') {
                // Si on cherche les achats chez nous
                $query->whereHas('chantier', function($q) {
                    $q->where('is_client_achat', true);
                });
            } else {
                // Sinon on filtre par garantie
                $isUnderWarranty = $warranty == '1';
                $query->whereHas('chantier', function($q) use ($isUnderWarranty) {
                    $q->where('is_under_warranty', $isUnderWarranty);
                });
            }
        }
        
        $produits = $query->get();
        
        // Transformer les produits en un format plus adapté pour l'API
        $transformedProduits = $produits->map(function($produit) {
            $dalle = $produit->dalles->first();
            
            return [
                'id' => $produit->id,
                'marque' => $produit->marque,
                'modele' => $produit->modele,
                'pitch' => $produit->pitch,
                'taille_dalle' => $dalle ? $dalle->largeur . 'x' . $dalle->hauteur . ' cm' : 'Non définie',
                'electronique' => $produit->electronique === 'autre' ? $produit->electronique_detail : ucfirst($produit->electronique),
                'carte_reception' => $produit->carte_reception ?: 'Non définie',
                'bain_couleur' => $produit->bain_couleur ?: 'Non défini',
                'reference_sav' => $produit->chantier->reference,
                'societe' => $produit->chantier->client->societe ?: $produit->chantier->client->nom_complet,
                'garantie' => [
                    'is_client_achat' => (bool)$produit->chantier->is_client_achat,
                    'is_under_warranty' => (bool)$produit->chantier->is_under_warranty,
                    'warranty_end_date' => $produit->chantier->warranty_end_date,
                    'warranty_type' => $produit->chantier->warranty_type
                ],
                'url' => route('produits.show', $produit->id)
            ];
        });
        
        return response()->json([
            'success' => true,
            'count' => $transformedProduits->count(),
            'produits' => $transformedProduits
        ]);
    }
    
    /**
     * Récupère la liste des produits du catalogue
     */
    public function catalogue()
    {
        $produits = ProduitCatalogue::orderBy('marque')
            ->orderBy('modele')
            ->get()
            ->map(function($item) {
                // Transformer l'objet pour inclure des propriétés formatées
                return [
                    'id' => $item->id,
                    'marque' => $item->marque,
                    'modele' => $item->modele,
                    'pitch' => $item->pitch,
                    'utilisation' => $item->utilisation,
                    'electronique' => $item->electronique,
                    'electronique_detail' => $item->electronique_detail,
                    'bain_couleur' => $item->bain_couleur,
                    'carte_reception' => $item->carte_reception,
                    'hub' => $item->hub,
                    // Pour formater l'affichage
                    'display_name' => "{$item->marque} {$item->modele} - {$item->pitch}mm"
                ];
            });
            
        return response()->json($produits);
    }
}
