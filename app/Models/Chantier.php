<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chantier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id', 'nom', 'description', 'date_reception', 
        'date_butoir', 'etat', 'reference'
    ];

    protected $casts = [
        'date_reception' => 'date',
        'date_butoir' => 'date',
    ];

    /**
     * Le client associé à ce chantier
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Les produits associés à ce chantier
     */
    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }

    /**
     * Générer une référence unique pour ce chantier
     */
    public static function genererReference(): string
    {
        $prefix = 'GMAO-';
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        
        return $prefix . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}