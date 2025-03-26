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
        'disposition_modules',
        'flightcase_max_dalles',
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
     * Obtenir la valeur du bain de couleur principal (pour la compatibilité avec le champ 'bain_couleur')
     */
    public function getBainCouleurAttribute(): string
    {
        $bains = $this->getBainsCouleurAttribute();
        if (!empty($bains) && is_array($bains)) {
            return $bains[0] ?? '';
        }
        
        // Pour la rétrocompatibilité, vérifier si les bains_couleur_disponibles est une chaîne simple
        if (!empty($this->bains_couleur_disponibles) && is_string($this->bains_couleur_disponibles)) {
            $decoded = json_decode($this->bains_couleur_disponibles);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Ce n'est pas du JSON valide, donc c'est probablement déjà une chaîne
                return $this->bains_couleur_disponibles;
            }
        }
        
        return '';
    }
    
    /**
     * Définir le bain de couleur principal comme partie de bains_couleur_disponibles
     */
    public function setBainCouleurAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['bains_couleur_disponibles'] = null;
            return;
        }
        
        // Stocker comme un tableau JSON avec un seul élément
        $this->attributes['bains_couleur_disponibles'] = json_encode([$value]);
    }
    
    /**
     * Obtenir la taille maximale du flightcase avec une valeur par défaut
     */
    public function getFlightcaseMaxDallesAttribute($value)
    {
        return $value ?? 8; // Valeur par défaut: 8 dalles
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
        
        // Ajouter la configuration de flightcase si définie
        if ($this->flightcase_max_dalles) {
            $specs['Max. dalles par flightcase'] = $this->flightcase_max_dalles;
        }
        
        return $specs;
    }
}
