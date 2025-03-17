<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// Nous n'utilisons plus le façade Session, nous utilisons $request->session() à la place
use Illuminate\Support\Facades\Log;

class ClientAuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion client
     */
    public function showLoginForm()
    {
        Log::info('ClientAuthController: affichage du formulaire de connexion');
        return view('client-login-test');
    }
    
    /**
     * Afficher le formulaire d'inscription client
     */
    public function showRegisterForm()
    {
        Log::info('ClientAuthController: affichage du formulaire d\'inscription');
        return view('client-register');
    }

    /**
     * Traiter la demande de connexion client
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Vérifier si le client existe
        $client = Client::where('email', $request->email)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return back()->withErrors([
                'email' => 'Les informations de connexion fournies sont incorrectes.',
            ]);
        }

        // Connexion manuelle du client
        $request->session()->put('client_id', $client->id);
        $request->session()->put('client_name', $client->nom_complet);

        return redirect()->route('client.dashboard');
    }

    /**
     * D�connecter le client
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['client_id', 'client_name']);
        
        return redirect()->route('client.login');
    }

    /**
     * Afficher le tableau de bord client
     */
    public function dashboard(Request $request)
    {
        // Vérifier si le client est connecté
        if (!$request->session()->has('client_id')) {
            return redirect()->route('client.login');
        }

        $client = Client::with(['chantiers' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($request->session()->get('client_id'));
        
        // Rediriger directement vers la liste des chantiers du client
        return redirect()->route('client.chantiers');
    }

    /**
     * D�finir un mot de passe pour un client (utilis� par l'admin)
     */
    public function setPassword(Request $request, Client $client)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $client->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Mot de passe du client d�fini avec succ�s.');
    }
    
    /**
     * Traiter la demande d'inscription client
     */
    public function register(Request $request)
    {
        // Vérifions d'abord si le client existe déjà avec cet email
        $existingClient = Client::where('email', $request->email)->first();

        // Validation des champs sans la contrainte d'unicité d'email
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
        ]);
        
        Log::info('ClientAuthController: traitement de l\'inscription client');
        
        // Si le client existe déjà
        if ($existingClient) {
            // On vérifie si le client a déjà un mot de passe défini
            if ($existingClient->password) {
                // Le client a déjà un compte
                return back()->withErrors([
                    'email' => 'Ce compte existe déjà. Veuillez vous connecter.',
                ]);
            } else {
                // Le client existe mais n'a pas encore de mot de passe, on l'active
                $existingClient->update([
                    'password' => Hash::make($request->password),
                    // Ne pas écraser les données existantes s'il s'agit d'un client déjà présent
                    // Mais mettre à jour si le champ est vide
                    'nom' => $existingClient->nom ?: $request->nom,
                    'prenom' => $existingClient->prenom ?: $request->prenom,
                    'telephone' => $existingClient->telephone ?: $request->telephone,
                ]);
                
                // Connecter le client
                $request->session()->put('client_id', $existingClient->id);
                $request->session()->put('client_name', $existingClient->nom_complet);
                
                return redirect()->route('client.dashboard')->with('success', 'Votre compte a été activé avec succès!');
            }
        }
        
        // Créer un nouveau client
        $client = Client::create([
            'civilite' => $request->civilite ?? 'M.',
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
        ]);
        
        // Connecter le client
        $request->session()->put('client_id', $client->id);
        $request->session()->put('client_name', $client->nom_complet);
        
        return redirect()->route('client.dashboard')->with('success', 'Votre compte a été créé avec succès!');
    }
}