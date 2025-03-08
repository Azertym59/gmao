<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CheckForAdminSetup
{
    public function handle(Request $request, Closure $next)
    {
        // Ne pas exécuter si nous sommes déjà sur la page de configuration ou de login
        if ($request->is('setup/*') || $request->is('login') || $request->is('register')) {
            return $next($request);
        }

        // Vérifier si la base de données est configurée
        try {
            if (Schema::hasTable('users')) {
                // Vérifier si au moins un admin existe
                if (User::where('role', 'admin')->count() === 0) {
                    return redirect()->route('setup.admin');
                }
            }
        } catch (\Exception $e) {
            // La base de données n'est pas encore configurée ou une erreur s'est produite
            return redirect()->route('setup.admin');
        }

        return $next($request);
    }
}