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
        'cartes_reception_disponibles',
        'hubs_disponibles',
        'bains_couleur_disponibles',
        'carte_reception',
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
    
    /**
     * Obtenir les options de cartes de réception sous forme de tableau
     */
    public function getCartesReceptionAttribute(): array
    {
        if (empty($this->cartes_reception_disponibles)) {
            return [];
        }
        
        return json_decode($this->cartes_reception_disponibles, true) ?: [];
    }
    
    /**
     * Obtenir les options de hubs sous forme de tableau
     */
    public function getHubsAttribute(): array
    {
        if (empty($this->hubs_disponibles)) {
            return [];
        }
        
        return json_decode($this->hubs_disponibles, true) ?: [];
    }
    
    /**
     * Obtenir les options de bains de couleur sous forme de tableau
     */
    public function getBainsCouleurAttribute(): array
    {
        if (empty($this->bains_couleur_disponibles)) {
            return [];
        }
        
        return json_decode($this->bains_couleur_disponibles, true) ?: [];
    }
    
    /**
     * Obtenir les spécifications détaillées sous forme de tableau
     */
    public function getSpecificationsAttribute(): array
    {
        $specs = [
            'Marque' => $this->marque,
            'Modèle' => $this->modele,
            'Pitch' => $this->pitch . ' mm',
            'Utilisation' => $this->utilisation === 'indoor' ? 'Indoor' : 'Outdoor',
            'Électronique' => $this->electronique === 'autre' ? $this->electronique_detail : ucfirst($this->electronique),
        ];
        
        // Ajouter les options disponibles s'il y en a
        $cartes = $this->cartes_reception;
        if (!empty($cartes)) {
            $specs['Cartes de réception disponibles'] = implode(', ', $cartes);
        }
        
        $hubs = $this->hubs;
        if (!empty($hubs)) {
            $specs['Hubs disponibles'] = implode(', ', $hubs);
        }
        
        $bains = $this->bains_couleur;
        if (!empty($bains)) {
            $specs['Bains de couleur disponibles'] = implode(', ', $bains);
        }
        
        return $specs;
    }
}