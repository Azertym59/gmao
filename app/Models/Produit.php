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
        'electronique_detail'
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
}