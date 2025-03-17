<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Définir un temps de cache par défaut pour toute l'application
        Cache::setDefaultCacheTime(3600); // 1 heure par défaut
        
        // Enregistrer les écouteurs d'événements pour vider le cache automatiquement
        $this->registerCacheInvalidationListeners();
    }
    
    /**
     * Enregistre les écouteurs d'événements pour vider le cache lors de modifications
     */
    protected function registerCacheInvalidationListeners(): void
    {
        // Vider le cache des statistiques d'intervention lorsqu'une intervention est créée/modifiée/supprimée
        \App\Models\Intervention::saved(function () {
            Cache::forget('intervention_stats');
            // Idéalement, nous devrions aussi vider les caches spécifiques par technicien
        });
        
        \App\Models\Intervention::deleted(function () {
            Cache::forget('intervention_stats');
        });
        
        // Vider le cache des statistiques lorsqu'un diagnostic ou une réparation est mise à jour
        \App\Models\Diagnostic::saved(function () {
            Cache::forget('intervention_stats');
        });
        
        \App\Models\Reparation::saved(function () {
            Cache::forget('intervention_stats');
        });
        
        // Vider les caches liés aux chantiers lorsqu'un module change d'état
        \App\Models\Module::saved(function ($module) {
            if ($module->isDirty('etat')) {
                // Déterminer le chantier associé et vider son cache
                $chantierId = $module->dalle->produit->chantier->id ?? null;
                if ($chantierId) {
                    Cache::forget("chantier_stats_{$chantierId}");
                }
            }
        });
    }
}
