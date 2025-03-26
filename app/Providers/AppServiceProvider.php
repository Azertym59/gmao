<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Contracts\Http\Kernel;
use App\Http\Middleware\CheckForAdminSetup;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\TechnicienMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrer nos middlewares personnalisés
        Route::aliasMiddleware('admin', AdminMiddleware::class);
        Route::aliasMiddleware('technicien', TechnicienMiddleware::class);
        
        // Ajouter le middleware de vérification admin au groupe web
        $this->app->make(Kernel::class)->appendMiddlewareToGroup('web', CheckForAdminSetup::class);
        
        // Enregistrer les composants d'autocomplétion
        Blade::component('client-autocomplete', \App\View\Components\ClientAutocomplete::class);
        Blade::component('marque-autocomplete', \App\View\Components\MarqueAutocomplete::class);
        Blade::component('modele-autocomplete', \App\View\Components\ModeleAutocomplete::class);
        
        // Directive Blade pour déterminer la disposition correcte d'une grille de modules
        Blade::directive('dalleModulesGrid', function ($expression) {
            return "<?php
                \$dalle = {$expression};
                // Valeurs par défaut pour les colonnes et lignes
                \$nbColonnes = 2;
                \$nbLignes = 2;
                
                // 1. Priorité 1: Utiliser disposition_modules si défini (le plus précis)
                if (!empty(\$dalle->disposition_modules) && strpos(\$dalle->disposition_modules, 'x') !== false) {
                    \$parts = explode('x', \$dalle->disposition_modules);
                    if (count(\$parts) == 2) {
                        \$nbColonnes = (int)\$parts[0];
                        \$nbLignes = (int)\$parts[1];
                    }
                } 
                // 2. Priorité 2: Utiliser la configuration standard selon les dimensions 
                // uniquement si le nombre de modules correspond
                else if (\$dalle->largeur == 500 && \$dalle->hauteur == 500 && \$dalle->modules->count() == 4) {
                    \$nbColonnes = 2;
                    \$nbLignes = 2;
                } else if (\$dalle->largeur == 500 && \$dalle->hauteur == 1000 && \$dalle->modules->count() == 8) {
                    \$nbColonnes = 2;
                    \$nbLignes = 4;
                } else if (\$dalle->largeur == 1000 && \$dalle->hauteur == 500 && \$dalle->modules->count() == 8) {
                    \$nbColonnes = 4;
                    \$nbLignes = 2;
                } 
                // 3. Priorité 3: Calculer la meilleure disposition basée sur le nombre de modules
                else {
                    \$nbModules = \$dalle->modules->count();
                    // Trouver le plus proche carré ou rectangle pour afficher tous les modules
                    \$sqrt = sqrt(\$nbModules);
                    \$nbColonnes = ceil(\$sqrt);
                    \$nbLignes = ceil(\$nbModules / \$nbColonnes);
                    
                    // Ajuster pour avoir une disposition plus naturelle basée sur la forme de la dalle
                    \$ratio = \$dalle->largeur / \$dalle->hauteur;
                    if (\$ratio >= 1.5) {  // Dalle bien plus large que haute
                        // Préférer une disposition avec plus de colonnes
                        \$nbColonnes = max(\$nbColonnes, ceil(\$sqrt * 1.5));
                        \$nbLignes = ceil(\$nbModules / \$nbColonnes);
                    } else if (\$ratio <= 0.67) {  // Dalle bien plus haute que large
                        // Préférer une disposition avec plus de lignes
                        \$nbLignes = max(\$nbLignes, ceil(\$sqrt * 1.5));
                        \$nbColonnes = ceil(\$nbModules / \$nbLignes);
                    }
                }
            ?>";
        });
    }
}