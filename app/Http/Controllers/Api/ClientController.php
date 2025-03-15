<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Recherche des clients en fonction d'un terme de recherche (pour l'autocomplétion)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->get('term');
        
        // Si le terme de recherche est vide ou trop court, retourner un tableau vide
        if (empty($term) || strlen($term) < 2) {
            return response()->json([]);
        }
        
        // Recherche dans différents champs
        $clients = Client::where('nom', 'like', "%{$term}%")
            ->orWhere('prenom', 'like', "%{$term}%")
            ->orWhere('societe', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('ville', 'like', "%{$term}%")
            ->orWhere('telephone', 'like', "%{$term}%")
            ->select('id', 'nom', 'prenom', 'societe', 'email', 'telephone', 'adresse', 'code_postal', 'ville', 'pays')
            ->limit(10)
            ->get();
        
        // Ajouter le nom complet pour l'affichage
        $clients->each(function ($client) {
            $client->nom_complet = $client->nom . ' ' . $client->prenom;
            $client->display_text = $client->nom_complet;
            
            if (!empty($client->societe)) {
                $client->display_text .= ' (' . $client->societe . ')';
            }
        });
        
        return response()->json($clients);
    }
}