<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'civilite', 'nom', 'prenom', 'societe', 'adresse', 'code_postal', 
        'ville', 'pays', 'email', 'telephone', 'notes', 'password', 'remember_token'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
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
        return $this->civilite . ' ' . $this->nom . ' ' . $this->prenom;
    }
    
    /**
     * Obtenir le nom complet sans redondance de civilité
     */
    public function getNomCompletSansDoublonAttribute(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }
}