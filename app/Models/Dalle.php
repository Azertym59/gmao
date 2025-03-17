<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'mode_emballage_detail'
    ];

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
}
