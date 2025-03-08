<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitCatalogue extends Model
{
    use HasFactory;

    protected $table = 'produits_catalogue';

    protected $fillable = [
        'marque',
        'modele',
        'pitch',
        'utilisation',
        'electronique',
        'electronique_detail',
        'image_url',
        'description',
    ];

    /**
     * Récupérer le nom complet du produit (marque + modèle)
     */
    public function getNomCompletAttribute()
    {
        return "{$this->marque} {$this->modele}";
    }

    /**
     * Récupérer le détail de l'électronique formaté
     */
    public function getElectroniqueFormattedAttribute()
    {
        if ($this->electronique === 'autre' && $this->electronique_detail) {
            return $this->electronique_detail;
        }
        
        return ucfirst($this->electronique);
    }

    /**
     * Récupérer l'utilisation formatée (Indoor/Outdoor)
     */
    public function getUtilisationFormattedAttribute()
    {
        return $this->utilisation === 'indoor' ? 'Indoor' : 'Outdoor';
    }
}