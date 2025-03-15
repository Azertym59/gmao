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
        'description',
        'model',
        'type',
        'is_default',
        'options',
        'ip_address',
        'port',
        'dpi',
        'label_width',
        'label_height',
        'connection_type'
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
     * Get all print jobs for this printer
     */
    public function printJobs()
    {
        return $this->hasMany(PrintJob::class);
    }

    /**
     * Check if this is a thermal printer
     * 
     * @return bool
     */
    public function isThermal()
    {
        return $this->type === 'thermal';
    }

    /**
     * Check if this is a label printer
     * 
     * @return bool
     */
    public function isLabel()
    {
        return $this->type === 'label';
    }

    /**
     * Check if this is a standard printer
     * 
     * @return bool
     */
    public function isStandard()
    {
        return $this->type === 'standard';
    }
    
    /**
     * Obtenir l'imprimante par défaut
     * 
     * @return Printer|null L'imprimante par défaut ou null si aucune n'est définie
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first();
    }
    
    /**
     * Vérifier si l'imprimante est disponible
     * 
     * @return bool True si l'imprimante est accessible, false sinon
     */
    public function isAvailable()
    {
        // Si pas d'adresse IP, présumer que c'est une imprimante locale
        if (empty($this->ip_address)) {
            return true;
        }
        
        // Tenter de connecter au port si spécifié
        if (!empty($this->port)) {
            $fp = @fsockopen($this->ip_address, $this->port, $errno, $errstr, 2);
            if ($fp) {
                fclose($fp);
                return true;
            }
        }
        
        // Tenter un ping basique
        exec("ping -c 1 -W 1 " . escapeshellarg($this->ip_address), $output, $status);
        return $status === 0;
    }
}