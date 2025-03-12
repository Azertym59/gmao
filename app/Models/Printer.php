<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'model',
        'ip_address',
        'port',
        'label_width',
        'label_height',
        'is_default',
        'is_active',
        'connection_type', // 'network', 'usb', 'bluetooth'
        'driver',
        'additional_settings' // JSON pour stocker des paramètres spécifiques
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'label_width' => 'float',
        'label_height' => 'float',
        'additional_settings' => 'array',
    ];

    /**
     * Vérifier si l'imprimante est disponible
     */
    public function isAvailable()
    {
        \Log::info('Checking printer availability', [
            'printer_id' => $this->id,
            'name' => $this->name,
            'connection_type' => $this->connection_type,
            'ip_address' => $this->ip_address,
            'port' => $this->port
        ]);
    
        if ($this->connection_type === 'network' && $this->ip_address) {
            // Vérification par ping
            exec("ping -c 4 " . escapeshellarg($this->ip_address), $output, $result);
            
            \Log::info('Ping result', [
                'result' => $result,
                'output' => $output
            ]);
    
            if ($result === 0) {
                return true;
            }
    
            // Test de socket
            $socket = @fsockopen(
                $this->ip_address, 
                $this->port ?: 9100, 
                $errno, 
                $errstr, 
                2
            );
    
            \Log::info('Socket check', [
                'socket_opened' => $socket !== false,
                'errno' => $errno,
                'errstr' => $errstr
            ]);
    
            if ($socket) {
                fclose($socket);
                return true;
            }
        }
        
        return $this->is_active;
    }

    /**
     * Obtenir l'imprimante par défaut
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first();
    }

    /**
     * Obtenir la liste des modèles supportés
     */
    public static function getSupportedModels()
    {
        return [
            'brother_ql820nwb' => 'Brother QL-820NWB',
            'brother_ql720nw' => 'Brother QL-720NW',
            'generic_thermal' => 'Imprimante thermique générique',
            'other' => 'Autre',
        ];
    }

    /**
     * Obtenir les types de connexion possibles
     */
    public static function getConnectionTypes()
    {
        return [
            'network' => 'Réseau (Wi-Fi/Ethernet)',
            'usb' => 'USB',
            'bluetooth' => 'Bluetooth',
        ];
    }

    /**
     * Obtenir les formats de rouleaux pour les imprimantes Brother
     */
    public static function getBrotherRollFormats()
    {
        return [
            'dk22251' => 'DK22251 - 62mm x continu (Rouge/Noir)',
            'dk22205' => 'DK22205 - 62mm x continu (Noir)',
            'dk11204' => 'DK11204 - 17mm x 54mm (Étiquettes multiples)',
            'dk11203' => 'DK11203 - 17mm x 87mm (Adresses)',
            'custom' => 'Format personnalisé',
        ];
    }
}