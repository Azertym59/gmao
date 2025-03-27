<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'dalle_id',
        'largeur',
        'hauteur',
        'nb_pixels_largeur',
        'nb_pixels_hauteur',
        'carte_reception',
        'hub',
        'driver',
        'shift_register',
        'buffer',
        'etat',
        'etat_precedent',
        'technicien_id',
        'est_occupe',
        'reference_module',
        'numero_serie',
        'position_lettre',
        'position_x',
        'position_y'
    ];

    /**
     * La dalle associée à ce module
     */
    public function dalle(): BelongsTo
    {
        return $this->belongsTo(Dalle::class);
    }

    /**
     * Le technicien qui travaille sur ce module (si applicable)
     */
    public function technicien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }

    /**
     * Les interventions sur ce module
     */
    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class);
    }
    
    /**
     * Vérifie si le module a une intervention active
     */
    public function hasActiveIntervention(): bool
    {
        // Vérification en cache pour optimiser les performances
        if (app()->has('moduleActiveInterventionCache.'.$this->id)) {
            return app()->get('moduleActiveInterventionCache.'.$this->id);
        }
        
        $result = $this->interventions()->where('is_completed', false)->exists();
        
        // Mettre en cache pour cette requête HTTP
        app()->instance('moduleActiveInterventionCache.'.$this->id, $result);
        
        return $result;
    }
    
    /**
     * Récupère la dernière intervention active
     */
    public function getActiveIntervention()
    {
        // Vérification en cache pour optimiser les performances
        if (app()->has('moduleActiveIntervention.'.$this->id)) {
            return app()->get('moduleActiveIntervention.'.$this->id);
        }
        
        $intervention = $this->interventions()
            ->where('is_completed', false)
            ->latest()
            ->first();
            
        // Mettre en cache pour cette requête HTTP
        app()->instance('moduleActiveIntervention.'.$this->id, $intervention);
        
        return $intervention;
    }
    
    /**
     * Vérifie si le module est réellement occupé en vérifiant toutes les conditions
     * (intervention active, heartbeat récent).
     * 
     * Un module est occupé seulement si:
     * 1. Il a le flag est_occupe actif
     * 2. Il a une intervention active non terminée
     * 3. Il y a un heartbeat récent (moins de 2 minutes) indiquant que quelqu'un est activement sur la page
     */
    public function isReallyOccupied(): bool
    {
        // Si le module n'est pas marqué comme occupé, pas besoin de vérifications
        if (!$this->est_occupe) {
            return false;
        }
        
        // Vérifier s'il y a une intervention active
        $activeIntervention = $this->getActiveIntervention();
        if (!$activeIntervention) {
            // Pas d'intervention active, le module ne devrait pas être marqué comme occupé
            $this->nettoieEtatInconsistant();
            return false;
        }
        
        // Vérifier si le fichier de verrouillage existe et est récent (heartbeat)
        $lockFile = storage_path('app/locks/module_' . $this->id . '.lock');
        
        // Si le verrou n'existe pas, le module n'est pas vraiment occupé
        if (!file_exists($lockFile)) {
            $this->nettoieEtatInconsistant();
            return false;
        }
        
        // Vérifier si le verrou est récent (moins de 2 minutes)
        // Un heartbeat actif signifie que quelqu'un est réellement sur la page d'intervention
        $tempsEcoule = time() - filemtime($lockFile);
        if ($tempsEcoule > 120) { // 2 minutes
            $this->nettoieEtatInconsistant();
            return false;
        }
        
        // Toutes les conditions sont remplies, le module est vraiment occupé
        return true;
    }
    
    /**
     * Nettoie l'état inconsistant d'un module
     */
    private function nettoieEtatInconsistant(): void
    {
        try {
            // Conserver l'état précédent si disponible
            $etatPrecedent = $this->etat_precedent;
            
            $this->est_occupe = false;
            $this->technicien_id = null;
            
            // Restaurer l'état précédent si existant et valide, sinon laisser l'état actuel
            if ($etatPrecedent && $etatPrecedent !== 'en_cours') {
                // Vérifier que l'état est dans une valeur valide
                $validStates = ['termine', 'defaillant', 'hs', 'stand_by', 'non_commence'];
                if (in_array($etatPrecedent, $validStates)) {
                    $this->etat = $etatPrecedent;
                } else {
                    // Si l'état précédent n'est pas valide, utiliser "non_commence"
                    $this->etat = 'non_commence';
                }
            }
            
            $this->save();
            
            // Supprimer le fichier de verrou s'il existe
            $lockFile = storage_path('app/locks/module_' . $this->id . '.lock');
            if (file_exists($lockFile)) {
                @unlink($lockFile);
            }
            
            \Illuminate\Support\Facades\Log::info("Auto-nettoyage depuis isReallyOccupied: module #{$this->id} libéré automatiquement");
        } catch (\Exception $e) {
            // Ignorer l'erreur mais la journaliser
            \Illuminate\Support\Facades\Log::error("Erreur lors de l'auto-nettoyage du module #{$this->id}: " . $e->getMessage());
        }
    }
    
    /**
     * Récupère le nom du technicien qui travaille sur ce module
     */
    public function getActiveTechnicianName(): ?string
    {
        if (!$this->technicien_id) {
            return null;
        }
        
        if ($intervention = $this->getActiveIntervention()) {
            $technicien = User::find($intervention->technicien_id);
            return $technicien ? $technicien->name : 'Technicien inconnu';
        }
        
        return null;
    }
    
    /**
     * Vérifie si le module est occupé par un autre technicien
     */
    public function isOccupiedByAnotherTechnician(): bool
    {
        // Si le module n'est pas marqué comme occupé, il n'est pas occupé
        if (!$this->est_occupe) {
            return false;
        }
        
        // Vérifier si le module est vraiment occupé (intervention active + heartbeat récent)
        if (!$this->isReallyOccupied()) {
            return false;
        }
        
        // Si l'utilisateur actuel est connecté
        if (auth()->check()) {
            // Si le module est occupé par l'utilisateur actuel, il n'est pas considéré comme occupé
            return $this->technicien_id != auth()->id();
        }
        
        // Si l'utilisateur n'est pas connecté, le module est considéré comme occupé
        return true;
    }
    
    /**
     * Récupère le nom du technicien qui occupe le module
     */
    public function getOccupiedByTechnicianName(): ?string
    {
        if (!$this->est_occupe || !$this->technicien_id) {
            return null;
        }
        
        $technicien = User::find($this->technicien_id);
        return $technicien ? $technicien->name : null;
    }
    
    /**
     * Accesseur pour l'intervention active
     */
    public function getActiveInterventionAttribute()
    {
        return $this->getActiveIntervention();
    }
}
