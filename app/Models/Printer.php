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
}