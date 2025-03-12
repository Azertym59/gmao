<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EtiquetteController extends Controller
{
    public function imprimerEtiquette($id)
    {
        // Exemple de données, à remplacer par tes données réelles (DB, etc.)
        $contenu = "Étiquette Chantier #{$id}\nClient : TECALED\nDate : " . now()->format('d-m-Y');

        return response()->json(['contenu' => $contenu]);
    }
}
