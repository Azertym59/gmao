<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Chantier;
use App\Models\ProduitCatalogue;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // Nous n'avons pas besoin d'appliquer le middleware ici car
        // nous l'appliquons déjà dans les routes
    }

    public function dashboard()
    {
        $stats = [
            'users_count' => User::count(),
            'clients_count' => Client::count(),
            'chantiers_count' => Chantier::count(),
            'produits_catalogue_count' => ProduitCatalogue::count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
    
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,technicien',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès.');
    }
    
    public function createUser()
    {
        return view('admin.users.create');
    }
    
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,technicien',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => bcrypt($validated['password']),
        ]);
        
        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
    }
    
    public function deleteUser(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
    }
}