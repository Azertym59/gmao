<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'technicien_id',
        'date_debut',
        'date_fin',
        'date_reprise',
        'date_pause',
        'temps_total',
        'is_completed'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'date_reprise' => 'datetime',
        'date_pause' => 'datetime',
        'is_completed' => 'boolean'
    ];

    /**
     * Le module associé à cette intervention
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Le technicien qui a effectué cette intervention
     */
    public function technicien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }

    /**
     * Le diagnostic associé à cette intervention
     */
    public function diagnostic(): HasOne
    {
        return $this->hasOne(Diagnostic::class);
    }

    /**
     * La réparation associée à cette intervention
     */
    public function reparation(): HasOne
    {
        return $this->hasOne(Reparation::class);
    }
}
