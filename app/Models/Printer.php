<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'is_default',
        'options',
        'external_id',  // ID externe (PrintNode ou ezeep)
        'status'        // État de l'imprimante (active, inactive, error)
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean',
        'options' => 'array'
    ];
    
    /**
     * Obtenir l'imprimante par défaut
     * 
     * @return Printer|null L'imprimante par défaut ou null si aucune n'est définie
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first();
    }
}