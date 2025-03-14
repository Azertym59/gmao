<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnostic extends Model
{
    use HasFactory;

    protected $fillable = [
        'intervention_id',
        'nb_leds_hs',
        'nb_ic_hs',
        'nb_masques_hs',
        'remarques',
        'description',
        'conclusion',
        'composant_defectueux',
        'pose_fake_pcb'
    ];

    /**
     * L'intervention associée à ce diagnostic
     */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }
}
