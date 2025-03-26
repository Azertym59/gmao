<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtiquetteTemplate extends Model
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
        'width',
        'height',
        'size_type',
        'is_default',
        'template_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'width' => 'float',
        'height' => 'float',
        'is_default' => 'boolean',
        'template_data' => 'array',
    ];

    /**
     * Get the default template for a specified type.
     *
     * @param string $type chantier|dalle|module
     * @return EtiquetteTemplate|null
     */
    public static function getDefaultForType($type)
    {
        return self::where('type', $type)
            ->where('is_default', true)
            ->first();
    }

    /**
     * Check if this template can be safely deleted.
     *
     * @return bool
     */
    public function canBeDeleted()
    {
        // If it's the default and only template for this type, it can't be deleted
        if ($this->is_default) {
            $count = self::where('type', $this->type)->where('is_default', true)->count();
            if ($count <= 1) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get all variables available for this template type
     *
     * @return array
     */
    public function getAvailableVariables(): array
    {
        // Common variables for all types
        $common = [
            'date' => [
                'label' => 'Date',
                'description' => 'Date actuelle (format: JJ/MM/AAAA)',
                'example' => date('d/m/Y'),
            ],
            'datetime' => [
                'label' => 'Date et heure',
                'description' => 'Date et heure actuelles',
                'example' => date('d/m/Y H:i'),
            ],
            'qrcode_url' => [
                'label' => 'URL QR Code',
                'description' => 'URL pour le QR code (généré automatiquement)',
                'example' => 'https://gmao.tecaled.fr/suivi/XXX',
            ],
        ];

        // Type specific variables
        $typeVariables = [];

        switch ($this->type) {
            case 'chantier':
                $typeVariables = [
                    'client' => [
                        'label' => 'Nom du client',
                        'description' => 'Nom du client',
                        'example' => 'Client Exemple',
                    ],
                    'reference' => [
                        'label' => 'Référence',
                        'description' => 'Référence du chantier',
                        'example' => 'CH-2025-0001',
                    ],
                    'adresse' => [
                        'label' => 'Adresse',
                        'description' => 'Adresse du chantier',
                        'example' => '123 rue Exemple, 75000 Paris',
                    ],
                    'date_debut' => [
                        'label' => 'Date de début',
                        'description' => 'Date de début du chantier',
                        'example' => '01/01/2025',
                    ],
                    'date_fin' => [
                        'label' => 'Date de fin',
                        'description' => 'Date de fin du chantier',
                        'example' => '31/12/2025',
                    ],
                    'statut' => [
                        'label' => 'Statut',
                        'description' => 'Statut du chantier',
                        'example' => 'En cours',
                    ],
                    'client_email' => [
                        'label' => 'Email client',
                        'description' => 'Email du client',
                        'example' => 'client@exemple.com',
                    ],
                    'client_telephone' => [
                        'label' => 'Téléphone client',
                        'description' => 'Téléphone du client',
                        'example' => '01 23 45 67 89',
                    ],
                    'responsable' => [
                        'label' => 'Responsable',
                        'description' => 'Nom du responsable du chantier',
                        'example' => 'Jean Dupont',
                    ],
                    'nb_dalles' => [
                        'label' => 'Nombre de dalles',
                        'description' => 'Nombre total de dalles',
                        'example' => '12',
                    ],
                    'note' => [
                        'label' => 'Note',
                        'description' => 'Notes sur le chantier',
                        'example' => 'Installation prévue le...',
                    ],
                ];
                break;

            case 'dalle':
                $typeVariables = [
                    'chantier' => [
                        'label' => 'Chantier',
                        'description' => 'Nom du chantier',
                        'example' => 'Chantier Exemple',
                    ],
                    'numero' => [
                        'label' => 'Numéro',
                        'description' => 'Numéro de la dalle',
                        'example' => 'D-001',
                    ],
                    'dimensions' => [
                        'label' => 'Dimensions',
                        'description' => 'Dimensions (L x H)',
                        'example' => '1920 x 1080',
                    ],
                    'produit' => [
                        'label' => 'Produit',
                        'description' => 'Type de produit',
                        'example' => 'Dalle P2.5',
                    ],
                    'resolution' => [
                        'label' => 'Résolution',
                        'description' => 'Résolution totale',
                        'example' => '1920 x 1080',
                    ],
                    'nb_modules' => [
                        'label' => 'Nombre de modules',
                        'description' => 'Nombre total de modules',
                        'example' => '48',
                    ],
                    'flightcase' => [
                        'label' => 'Flight case',
                        'description' => 'Numéro de flight case',
                        'example' => 'FC-001',
                    ],
                    'client' => [
                        'label' => 'Client',
                        'description' => 'Nom du client',
                        'example' => 'Client Exemple',
                    ],
                    'carte_reception' => [
                        'label' => 'Carte réception',
                        'description' => 'Type de carte de réception',
                        'example' => 'Novastar',
                    ],
                    'date_installation' => [
                        'label' => 'Date installation',
                        'description' => 'Date d\'installation',
                        'example' => '01/01/2025',
                    ],
                ];
                break;

            case 'module':
                $typeVariables = [
                    'dalle' => [
                        'label' => 'Dalle',
                        'description' => 'Numéro de la dalle',
                        'example' => 'D-001',
                    ],
                    'position' => [
                        'label' => 'Position',
                        'description' => 'Position sur la dalle (X,Y)',
                        'example' => 'L1-C3',
                    ],
                    'colonne' => [
                        'label' => 'Colonne',
                        'description' => 'Numéro de colonne',
                        'example' => '3',
                    ],
                    'ligne' => [
                        'label' => 'Ligne',
                        'description' => 'Numéro de ligne',
                        'example' => '1',
                    ],
                    'numero_serie' => [
                        'label' => 'Numéro de série',
                        'description' => 'Numéro de série du module',
                        'example' => 'MOD-2025-001-A',
                    ],
                    'resolution' => [
                        'label' => 'Résolution',
                        'description' => 'Résolution du module',
                        'example' => '100 x 100',
                    ],
                    'led_type' => [
                        'label' => 'Type de LED',
                        'description' => 'Type de LED',
                        'example' => 'SMD 2121',
                    ],
                    'dimensions' => [
                        'label' => 'Dimensions',
                        'description' => 'Dimensions du module',
                        'example' => '25 x 25 cm',
                    ],
                    'chantier' => [
                        'label' => 'Chantier',
                        'description' => 'Nom du chantier',
                        'example' => 'Chantier Exemple',
                    ],
                    'statut' => [
                        'label' => 'Statut',
                        'description' => 'Statut du module',
                        'example' => 'Opérationnel',
                    ],
                    'produit' => [
                        'label' => 'Produit',
                        'description' => 'Type de produit',
                        'example' => 'Module P2.5',
                    ],
                ];
                break;
        }

        // Combine common and type-specific variables
        return [
            'common' => $common,
            'specific' => $typeVariables,
        ];
    }

    /**
     * Set this template as the default for its type
     */
    public function setAsDefault(): void
    {
        // First, unset default for all templates of this type
        self::where('type', $this->type)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        // Then set this one as default
        $this->is_default = true;
        $this->save();
    }

    /**
     * Create a duplicate of this template
     */
    public function duplicate(): self
    {
        $duplicate = $this->replicate();
        $duplicate->name = $this->name . ' (copie)';
        $duplicate->is_default = false;
        $duplicate->save();

        return $duplicate;
    }
}