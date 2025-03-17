<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LedDatasheet extends Model
{
    protected $fillable = [
        'type',
        'color',
        'reference',
        'nb_poles',
        'disposition',
        'position_chanfrein',
        'configuration_poles',
        'notes',
        'user_id',
        'image_data'
    ];

    protected $casts = [
        'configuration_poles' => 'array'
    ];

    /**
     * Get the user that created the datasheet
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the produits that use this datasheet
     */
    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }
}
