<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProduitCatalogue;
use Illuminate\Http\Request;

class CartesReceptionController extends Controller
{
    /**
     * Récupérer les cartes de réception disponibles pour un système électronique donné
     */
    public function getCartesForElectronique(Request $request)
    {
        $electronique = $request->input('electronique');
        
        if (\!$electronique) {
            return response()->json([
                'error' => 'Le paramètre electronique est requis'
            ], 400);
        }
        
        // Récupérer toutes les cartes de réception utilisées pour ce système
        $cartes = ProduitCatalogue::where('electronique', $electronique)
                    ->whereNotNull('cartes_reception_disponibles')
                    ->get()
                    ->pluck('cartes_reception_disponibles')
                    ->map(function ($item) {
                        return json_decode($item, true) ?: [];
                    })
                    ->flatten()
                    ->unique()
                    ->values()
                    ->all();
        
        return response()->json([
            'cartes' => $cartes
        ]);
    }
}
