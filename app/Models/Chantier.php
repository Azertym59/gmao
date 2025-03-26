<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Chantier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id', 'nom', 'description', 'date_reception', 
        'date_butoir', 'etat', 'reference', 'token_suivi', 'token_suivi_last_used_at',
        'is_client_achat', 'is_under_warranty', 'warranty_end_date', 'warranty_type',
        'intervention_data'
    ];

    protected $casts = [
        'date_reception' => 'date',
        'date_butoir' => 'date',
        'warranty_end_date' => 'date',
        'is_client_achat' => 'boolean',
        'is_under_warranty' => 'boolean',
        'intervention_data' => 'json',
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
        $format = $prefix . $year . $month . '-%';
        
        // Trouver directement le maximum actuel dans la base de données avec une requête SQL brute pour éviter les bugs
        try {
            // Obtenir la référence avec le numéro le plus élevé pour ce format
            $maxRef = DB::select(
                "SELECT reference FROM chantiers 
                WHERE reference LIKE ? 
                ORDER BY CAST(SUBSTR(reference, -3) AS INTEGER) DESC LIMIT 1", 
                [$format . '%']
            );
            
            $count = 1; // Valeur par défaut
            
            if (!empty($maxRef)) {
                // Extraire le numéro de séquence de la référence
                $reference = $maxRef[0]->reference;
                $parts = explode('-', $reference);
                $num = (int) end($parts);
                $count = $num + 1;
                
                \Illuminate\Support\Facades\Log::info('Référence max trouvée', [
                    'max_ref' => $reference,
                    'new_count' => $count
                ]);
            }
            
            // Générer la nouvelle référence
            $newRef = $prefix . $year . $month . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            
            // Vérifier que cette référence n'existe pas déjà (double vérification)
            while (DB::table('chantiers')->where('reference', $newRef)->exists()) {
                $count++;
                $newRef = $prefix . $year . $month . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
                
                \Illuminate\Support\Facades\Log::info('Collision détectée, incrément de la référence', [
                    'new_ref' => $newRef
                ]);
                
                // Sécurité pour éviter une boucle infinie
                if ($count > 999) {
                    throw new \Exception("Impossible de générer une référence unique après 999 tentatives");
                }
            }
            
            \Illuminate\Support\Facades\Log::info('Référence générée avec succès', [
                'reference' => $newRef
            ]);
            
            return $newRef;
            
        } catch (\Exception $e) {
            // En cas d'erreur, utiliser une référence unique basée sur le timestamp
            \Illuminate\Support\Facades\Log::error('Erreur lors de la génération de référence', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback sécurisé
            $timestamp = now()->format('ymdHis');
            $newRef = $prefix . $timestamp;
            
            return $newRef;
        }
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
     * Obtenir le nombre théorique de modules requis pour le chantier
     * SIMPLIFIÉ : basé uniquement sur disposition_modules
     */
    public function getTotalModulesNeeded(): int
    {
        $totalModules = 0;
        
        foreach ($this->produits as $produit) {
            foreach ($produit->dalles as $dalle) {
                // Utiliser UNIQUEMENT le calcul basé sur disposition_modules
                $totalModules += $dalle->calculateNbModules();
            }
        }
        
        return $totalModules;
    }
    
    /**
     * Obtenir le pourcentage de complétion du chantier
     * Utilisera le nombre théorique de modules maintenant
     */
    public function getCompletionPercentage(): int
    {
        $totalModules = $this->getTotalModulesNeeded();
        $modulesTermines = 0;
        
        foreach ($this->produits as $produit) {
            foreach ($produit->dalles as $dalle) {
                $modulesTermines += $dalle->modules->where('etat', 'termine')->count();
            }
        }
        
        if ($totalModules === 0) {
            return 0;
        }
        
        return round(($modulesTermines / $totalModules) * 100);
    }
}