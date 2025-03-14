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
        'technicien_id',
        'est_occupe',
        'reference_module',
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
}
