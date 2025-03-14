<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'chantier_id',
        'marque',
        'modele',
        'pitch',
        'utilisation',
        'electronique',
        'electronique_detail',
        'carte_reception', 
        'hub',
        'bain_couleur',
        'variante_id',
        'is_variante',
        'variante_nom'
    ];

    /**
     * Le chantier associé à ce produit
     */
    public function chantier(): BelongsTo
    {
        return $this->belongsTo(Chantier::class);
    }

    /**
     * Les dalles associées à ce produit
     */
    public function dalles(): HasMany
    {
        return $this->hasMany(Dalle::class);
    }
    
    /**
     * Le produit parent de cette variante
     */
    public function produitParent(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'variante_id');
    }
    
    /**
     * Les variantes de ce produit
     */
    public function variantes(): HasMany
    {
        return $this->hasMany(Produit::class, 'variante_id')->where('is_variante', true);
    }
    
    /**
     * Vérifier si ce produit a des variantes
     */
    public function hasVariantes(): bool
    {
        return $this->variantes()->count() > 0;
    }
    
    /**
     * Obtenir le nom complet du produit (avec la variante si applicable)
     */
    public function getNomCompletAttribute(): string
    {
        $nom = "{$this->marque} {$this->modele}";
        
        if ($this->is_variante && $this->variante_nom) {
            $nom .= " ({$this->variante_nom})";
        }
        
        return $nom;
    }
    
    /**
     * Obtenir les spécifications détaillées sous forme de tableau
     */
    public function getSpecificationsAttribute(): array
    {
        return [
            'Marque' => $this->marque,
            'Modèle' => $this->modele,
            'Pitch' => $this->pitch . ' mm',
            'Utilisation' => $this->utilisation === 'indoor' ? 'Indoor' : 'Outdoor',
            'Électronique' => $this->electronique === 'autre' ? $this->electronique_detail : ucfirst($this->electronique),
            'Carte de réception' => $this->carte_reception ?: 'Non spécifiée',
            'Hub' => $this->hub ?: 'Non spécifié',
            'Bain de couleur' => $this->bain_couleur ?: 'Non spécifié',
        ];
    }
}