<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reparation extends Model
{
    use HasFactory;

    protected $fillable = [
        'intervention_id',
        'nb_leds_remplacees',
        'nb_ic_remplaces',
        'nb_masques_remplaces',
        'remarques',
        'description',
        'actions',
        'pieces_remplacees',
        'resultat',
        'fake_pcb_pose',
        'fake_pcb_nb'
    ];

    /**
     * L'intervention associée à cette réparation
     */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }
}
