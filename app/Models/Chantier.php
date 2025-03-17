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
        'date_butoir', 'etat', 'reference', 'token_suivi', 'token_suivi_last_used_at',
        'is_client_achat', 'is_under_warranty', 'warranty_end_date', 'warranty_type'
    ];

    protected $casts = [
        'date_reception' => 'date',
        'date_butoir' => 'date',
        'warranty_end_date' => 'date',
        'is_client_achat' => 'boolean',
        'is_under_warranty' => 'boolean',
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
        $prefix = 'TCA-'; // TecaLED Assistance
        $year = now()->format('y'); // Année sur 2 chiffres
        $month = now()->format('m'); // Mois
        $count = self::whereDate('created_at', today())->count() + 1;
        
        return $prefix . $year . $month . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
    
    /**
     * Générer un token de suivi unique pour ce chantier
     */
    public function genererTokenSuivi(): string
    {
        $token = md5($this->id . $this->reference . $this->created_at . uniqid());
        $this->token_suivi = $token;
        $this->save();
        return $token;
    }
    
    /**
     * Obtenir ou générer un token de suivi
     */
    public function getTokenSuivi(): string
    {
        if (empty($this->token_suivi)) {
            return $this->genererTokenSuivi();
        }
        return $this->token_suivi;
    }
    
    /**
     * Obtenir le pourcentage de complétion du chantier
     */
    public function getCompletionPercentage(): int
    {
        $totalModules = 0;
        $modulesTermines = 0;
        
        foreach ($this->produits as $produit) {
            foreach ($produit->dalles as $dalle) {
                $totalModules += $dalle->modules->count();
                $modulesTermines += $dalle->modules->where('etat', 'termine')->count();
            }
        }
        
        if ($totalModules === 0) {
            return 0;
        }
        
        return round(($modulesTermines / $totalModules) * 100);
    }
}