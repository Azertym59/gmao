<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Dalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'largeur',
        'hauteur',
        'nb_modules',
        'alimentation',
        'carte_reception',
        'hub',
        'reference_dalle',
        'numero_dalle',
        'disposition_modules',
        'nb_colonnes',
        'nb_lignes',
        'disposition_type',
        'disposition_schema',
        'mode_emballage',
        'mode_emballage_detail',
        'flightcase_number',
        'dalle_number',
        'flightcase_partial',
        'dalle_type_id',
        'is_virtual'
    ];
    
    /**
     * MÉTHODE SIMPLIFIÉE POUR CALCULER LE NOMBRE DE MODULES
     * Utilise uniquement disposition_modules comme source de vérité
     */
    public function calculateNbModules()
    {
        // Utiliser uniquement disposition_modules
        if (!empty($this->disposition_modules) && strpos($this->disposition_modules, 'x') !== false) {
            $parts = explode('x', $this->disposition_modules);
            if (count($parts) == 2) {
                return (int)$parts[0] * (int)$parts[1];
            }
        }
        
        // Valeur par défaut - uniquement si disposition_modules n'est pas défini
        return 4; // 2x2 par défaut
    }
    
    /**
     * SIMPLIFICATION RADICALE DU BOOT HOOK
     * Assure que disposition_modules est toujours défini et cohérent
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($dalle) {
            // Si disposition_modules est déjà défini, l'utiliser comme source de vérité
            if (!empty($dalle->disposition_modules) && strpos($dalle->disposition_modules, 'x') !== false) {
                $parts = explode('x', $dalle->disposition_modules);
                if (count($parts) == 2) {
                    // Mettre à jour nb_colonnes, nb_lignes et nb_modules pour assurer la cohérence
                    $dalle->nb_colonnes = (int)$parts[0];
                    $dalle->nb_lignes = (int)$parts[1];
                    $dalle->nb_modules = (int)$parts[0] * (int)$parts[1];
                }
            } 
            // Si disposition_modules n'est pas défini mais nb_colonnes et nb_lignes le sont
            elseif ($dalle->nb_colonnes > 0 && $dalle->nb_lignes > 0) {
                // Définir disposition_modules à partir de nb_colonnes et nb_lignes
                $dalle->disposition_modules = $dalle->nb_colonnes . 'x' . $dalle->nb_lignes;
                $dalle->nb_modules = $dalle->nb_colonnes * $dalle->nb_lignes;
            }
            // Si rien n'est défini, utiliser une disposition par défaut 2x2
            else {
                $dalle->disposition_modules = '2x2';
                $dalle->nb_colonnes = 2;
                $dalle->nb_lignes = 2;
                $dalle->nb_modules = 4;
            }
        });
        
        // NOUVEAU: Vérifier et corriger le nombre de modules après création
        static::created(function ($dalle) {
            try {
                // 1. Charger les modules existants
                $modulesCount = $dalle->modules()->count();
                
                // 2. Calculer le nombre attendu
                $nbModulesAttendus = $dalle->calculateNbModules();
                
                // 3. Si différent, log et planifier une correction
                if ($modulesCount != $nbModulesAttendus) {
                    Log::warning("Dalle #{$dalle->id}: nombre de modules incorrect ({$modulesCount}/{$nbModulesAttendus})",
                        ['disposition' => $dalle->disposition_modules]
                    );
                    
                    // On pourrait déclencher un job ou une tâche qui créerait ou supprimerait les modules nécessaires
                    // C'est une amélioration future potentielle
                }
            } catch (\Exception $e) {
                Log::error("Erreur lors de la vérification des modules pour la dalle #{$dalle->id}: " . $e->getMessage());
            }
        });
    }

    /**
     * Le produit associé à cette dalle
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Les modules associés à cette dalle
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }
    
    /**
     * Obtenir le datasheet LED associé au produit de cette dalle
     */
    public function getLedDatasheetAttribute()
    {
        if ($this->produit && $this->produit->ledDatasheet) {
            return $this->produit->ledDatasheet;
        }
        return null;
    }
    
    /**
     * Vérifier et ajuster le nombre de modules selon la disposition
     * Cette méthode peut être appelée manuellement pour réparer une dalle
     */
    public function ajusterModules()
    {
        if (empty($this->disposition_modules)) {
            return false;
        }
        
        $nbModulesAttendus = $this->calculateNbModules();
        $nbModulesExistants = $this->modules()->count();
        
        // Si le nombre est correct, ne rien faire
        if ($nbModulesExistants == $nbModulesAttendus) {
            return true;
        }
        
        // Si trop peu de modules, en créer
        if ($nbModulesExistants < $nbModulesAttendus) {
            // Logique pour créer des modules manquants (à implémenter)
            return false;
        }
        
        // Si trop de modules, en supprimer
        if ($nbModulesExistants > $nbModulesAttendus) {
            // Logique pour supprimer des modules en trop (à implémenter)
            return false;
        }
        
        return true;
    }
}
