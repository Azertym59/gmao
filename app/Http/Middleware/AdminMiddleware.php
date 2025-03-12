<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté et a le rôle 'admin'
        if (!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isTechnicien())) {
            // Rediriger vers la page d'accueil avec un message d'erreur
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé. Vous devez être administrateur.');
        }
        
        return $next($request);
    }
}