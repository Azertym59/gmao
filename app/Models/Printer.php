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
        'options'
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
    
    /**
     * Accesseurs pour les propriétés stockées dans options
     */
    public function getModelAttribute()
    {
        return $this->options['model'] ?? null;
    }
    
    public function getIpAddressAttribute()
    {
        return $this->options['ip_address'] ?? null;
    }
    
    public function getPortAttribute()
    {
        return $this->options['port'] ?? null;
    }
    
    public function getDpiAttribute()
    {
        return $this->options['dpi'] ?? null;
    }
    
    public function getLabelWidthAttribute()
    {
        return $this->options['label_width'] ?? null;
    }
    
    public function getLabelHeightAttribute()
    {
        return $this->options['label_height'] ?? null;
    }
    
    public function getConnectionTypeAttribute()
    {
        return $this->options['connection_type'] ?? null;
    }
    
    public function getLabelFormatAttribute()
    {
        return $this->options['label_format'] ?? null;
    }
    
    /**
     * Accesseur pour l'ID PrintNode
     */
    public function getPrintnodeIdAttribute()
    {
        return $this->options['printnode_id'] ?? null;
    }
    
    /**
     * Accesseur pour le type d'imprimante Brother
     */
    public function isBrotherLabel()
    {
        return $this->type === 'brother_label';
    }
    
    /**
     * Vérifie si l'imprimante peut être utilisée avec PrintNode
     */
    public function hasPrintNode()
    {
        return !empty($this->printnode_id);
    }
    
    /**
     * Vérifie si l'imprimante Brother devrait utiliser le pilote b-PAC
     */
    public function shouldUseBpac()
    {
        // Si les options sont une chaîne JSON, les décoder d'abord
        $options = is_array($this->options) ? $this->options : json_decode($this->options, true) ?? [];
        
        return $this->isBrotherLabel() && 
               ($this->connection_type === 'usb' || 
                ($options['use_bpac'] ?? false));
    }
}