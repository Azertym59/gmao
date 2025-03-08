<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom', 'prenom', 'societe', 'adresse', 'code_postal', 
        'ville', 'pays', 'email', 'telephone', 'notes'
    ];

    /**
     * Les chantiers liés à ce client
     */
    public function chantiers(): HasMany
    {
        return $this->hasMany(Chantier::class);
    }

    /**
     * Obtenir le nom complet du client
     */
    public function getNomCompletAttribute(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }
}