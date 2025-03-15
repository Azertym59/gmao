<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Formater les données du client pour une présentation homogène
     * 
     * @param array $data Les données à formater
     * @return array Données formatées
     */
    private function formatClientData(array $data): array
    {
        // Nom en majuscules
        if (isset($data['nom'])) {
            $data['nom'] = Str::upper($data['nom']);
        }
        
        // Prénom avec première lettre en majuscule
        if (isset($data['prenom'])) {
            $data['prenom'] = Str::ucfirst(Str::lower($data['prenom']));
        }
        
        // Société en majuscules (si non vide)
        if (isset($data['societe']) && !empty($data['societe'])) {
            $data['societe'] = Str::upper($data['societe']);
        }
        
        // Ville en majuscules
        if (isset($data['ville'])) {
            $data['ville'] = Str::upper($data['ville']);
        }
        
        // Code postal sans espaces
        if (isset($data['code_postal'])) {
            $data['code_postal'] = str_replace(' ', '', $data['code_postal']);
        }
        
        // Email en minuscules
        if (isset($data['email'])) {
            $data['email'] = Str::lower($data['email']);
        }
        
        return $data;
    }

    /**
     * Afficher la liste des clients
     */
    public function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Stocker un nouveau client
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'societe' => 'nullable|string|max:255',
            'adresse' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'telephone' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        // Formater les données
        $validated = $this->formatClientData($validated);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès.');
    }

    /**
     * Afficher un client spécifique
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Mettre à jour un client
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'societe' => 'nullable|string|max:255',
            'adresse' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,'.$client->id,
            'telephone' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        // Formater les données
        $validated = $this->formatClientData($validated);

        $client->update($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * Supprimer un client
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}