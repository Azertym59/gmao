<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetupController extends Controller
{
    public function showAdminSetup()
    {
        // Ne pas vérifier le rôle pour le moment, juste vérifier s'il y a des utilisateurs
        if (User::count() > 0) {
            return redirect()->route('login')->with('info', 'Un utilisateur existe déjà.');
        }

        return view('setup.admin');
    }

    public function createAdmin(Request $request)
    {
        // Journaliser les données reçues (sans le mot de passe)
        $debug = [
            'name' => $request->name,
            'email' => $request->email,
            'password_length' => strlen($request->password),
            'confirmation_matches' => $request->password === $request->password_confirmation,
        ];
        
        \Illuminate\Support\Facades\Log::info('Tentative de création d\'administrateur', $debug);
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            \Illuminate\Support\Facades\Log::info('Validation réussie');
            
            // Vérifier si la table users existe
            if (!Schema::hasTable('users')) {
                \Illuminate\Support\Facades\Log::error('La table users n\'existe pas');
                // Créer la table
                DB::statement('
                    CREATE TABLE IF NOT EXISTS users (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        name TEXT,
                        email TEXT UNIQUE,
                        role TEXT DEFAULT "technicien",
                        email_verified_at TIMESTAMP NULL,
                        password TEXT,
                        remember_token TEXT,
                        created_at TIMESTAMP,
                        updated_at TIMESTAMP
                    )
                ');
                \Illuminate\Support\Facades\Log::info('Table users créée');
            }
            
            // Vérifier si l'utilisateur existe déjà
            $existingUser = DB::table('users')->where('email', $request->email)->first();
            if ($existingUser) {
                \Illuminate\Support\Facades\Log::error('Utilisateur existe déjà');
                return back()->withInput()->with('error', 'Un utilisateur avec cet email existe déjà.');
            }
            
            // Insérer l'utilisateur directement dans la base de données
            DB::insert('
                INSERT INTO users (name, email, password, role, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?)', 
                [
                    $request->name, 
                    $request->email, 
                    Hash::make($request->password),
                    'admin',
                    now(),
                    now()
                ]
            );
            
            \Illuminate\Support\Facades\Log::info('Utilisateur inséré');
            
            // Récupérer l'utilisateur pour l'authentification
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                \Illuminate\Support\Facades\Log::warning('Utilisateur non trouvé après insertion, création d\'un objet temporaire');
                // Créer un objet User temporaire pour l'authentification
                $user = new User([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'admin'
                ]);
                $user->id = 1; // ID temporaire
            }

            auth()->login($user);
            \Illuminate\Support\Facades\Log::info('Utilisateur connecté');

            return redirect()->route('dashboard')
                ->with('success', 'Compte administrateur créé avec succès. Bienvenue dans votre GMAO !');
        } catch (\Exception $e) {
            // En cas d'erreur, retourner à la page précédente avec le message d'erreur
            \Illuminate\Support\Facades\Log::error('Erreur: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}