<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SetupController extends Controller
{
    public function showAdminSetup()
    {
        // Vérifier si un admin existe déjà
        if (User::where('role', 'admin')->count() > 0) {
            return redirect()->route('login')->with('info', 'Un administrateur existe déjà.');
        }

        return view('setup.admin');
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Compte administrateur créé avec succès. Bienvenue dans votre GMAO !');
    }
}