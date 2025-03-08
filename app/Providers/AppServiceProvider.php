<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Http\Kernel;
use App\Http\Middleware\CheckForAdminSetup;
use App\Http\Middleware\AdminMiddleware;

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
        
        // Ajouter le middleware de vérification admin au groupe web
        $this->app->make(Kernel::class)->appendMiddlewareToGroup('web', CheckForAdminSetup::class);
    }
}