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
        'carte_reception', // Ajoutez cette ligne
        'hub', // Ajoutez cette ligne
        'reference_dalle'
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
}
