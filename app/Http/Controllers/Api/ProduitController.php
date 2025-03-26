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
                // Calculer le nombre de modules en fonction de la disposition
                $moduleCount = 4; // Par défaut 2x2 (4 modules)
                $moduleConfig = "2x2";
                
                if (!empty($item->disposition_modules)) {
                    $configParts = explode('x', $item->disposition_modules);
                    if (count($configParts) === 2) {
                        $cols = intval($configParts[0]);
                        $rows = intval($configParts[1]);
                        if ($cols > 0 && $rows > 0) {
                            $moduleCount = $cols * $rows;
                            $moduleConfig = $item->disposition_modules;
                        }
                    }
                }
                
                // Transformer l'objet pour inclure des propriétés formatées
                return [
                    'id' => $item->id,
                    'marque' => $item->marque,
                    'modele' => $item->modele,
                    'pitch' => $item->pitch,
                    'utilisation' => $item->utilisation,
                    'electronique' => $item->electronique,
                    'electronique_detail' => $item->electronique_detail,
                    'bain_couleur' => $item->bain_couleur, // Utilise l'accesseur défini dans le modèle
                    'carte_reception' => $item->carte_reception,
                    'hub' => $item->hub,
                    'disposition_modules' => $item->disposition_modules ?? $moduleConfig,
                    'moduleCount' => $moduleCount,
                    'moduleConfig' => $moduleConfig,
                    // Pour formater l'affichage
                    'display_name' => "{$item->marque} {$item->modele} - {$item->pitch}mm"
                ];
            });
            
        return response()->json($produits);
    }
    
    /**
     * S'assure que tous les produits qui ont été créés avec "add_to_catalogue"
     * sont bien présents dans le catalogue
     */
    public function syncProduitsCatalogue()
    {
        // 1. Récupérer les produits qui ont une référence de catalogue mais dont l'entrée pourrait manquer
        $produits = Produit::whereNotNull('produit_catalogue_id')
            ->get();
            
        $count = 0;
        
        foreach ($produits as $produit) {
            // Vérifier si l'entrée du catalogue existe
            $catalogueExists = ProduitCatalogue::where('id', $produit->produit_catalogue_id)->exists();
            
            // Si elle n'existe pas, créer une nouvelle entrée
            if (!$catalogueExists) {
                $produitCatalogue = ProduitCatalogue::create([
                    'marque' => $produit->marque,
                    'modele' => $produit->modele,
                    'pitch' => $produit->pitch,
                    'utilisation' => $produit->utilisation ?? 'indoor',
                    'electronique' => $produit->electronique ?? 'nova',
                    'electronique_detail' => $produit->electronique_detail ?? '',
                    'description' => 'Produit synchronisé depuis chantier existant',
                    'bain_couleur' => $produit->bain_couleur ?? '',
                ]);
                
                // Mettre à jour l'ID du catalogue sur le produit
                $produit->produit_catalogue_id = $produitCatalogue->id;
                $produit->save();
                
                $count++;
            }
        }
        
        // 2. Ajouter au catalogue les produits pertinents qui n'ont pas d'ID de catalogue
        // mais qui pourraient être des doublons
        $produitsUniques = Produit::whereNull('produit_catalogue_id')
            ->where('pitch', '>', 0) // On ne veut que les produits avec un pitch valide
            ->get()
            ->groupBy(function($item) {
                // Créer une clé unique pour chaque combinaison marque/modèle/pitch
                return strtolower($item->marque) . '|' . strtolower($item->modele) . '|' . $item->pitch;
            });
            
        foreach ($produitsUniques as $key => $groupe) {
            // Vérifier si cette combinaison existe déjà dans le catalogue
            list($marque, $modele, $pitch) = explode('|', $key);
            
            // Chercher dans le catalogue
            $existingCatalogue = ProduitCatalogue::where('marque', 'like', $marque)
                ->where('modele', 'like', $modele)
                ->where('pitch', $pitch)
                ->first();
            
            if ($existingCatalogue) {
                // Si ce produit existe déjà, mettez à jour les produits avec cet ID de catalogue
                foreach ($groupe as $produit) {
                    $produit->produit_catalogue_id = $existingCatalogue->id;
                    $produit->save();
                    $count++;
                }
            } else {
                // Sinon, créez une nouvelle entrée dans le catalogue et liez-la
                $produitSample = $groupe->first();
                // Créer un nouveau produit catalogue avec le bain de couleur
                $produitCatalogue = new ProduitCatalogue([
                    'marque' => $produitSample->marque,
                    'modele' => $produitSample->modele,
                    'pitch' => $produitSample->pitch,
                    'utilisation' => $produitSample->utilisation ?? 'indoor',
                    'electronique' => $produitSample->electronique ?? 'nova',
                    'electronique_detail' => $produitSample->electronique_detail ?? '',
                    'description' => 'Produit synchronisé depuis chantiers existants',
                ]);
                
                // Utiliser l'accesseur pour définir le bain_couleur s'il existe
                if (!empty($produitSample->bain_couleur)) {
                    $produitCatalogue->bain_couleur = $produitSample->bain_couleur;
                }
                
                $produitCatalogue->save();
                
                // Mettre à jour tous les produits de ce groupe
                foreach ($groupe as $produit) {
                    $produit->produit_catalogue_id = $produitCatalogue->id;
                    $produit->save();
                    $count++;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "{$count} produits synchronisés avec le catalogue"
        ]);
    }
}
