<?php
// Dans app/Models/PrintJob.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'printer_id',
        'content',
        'name',
        'status',
        'message',
        'job_token'
    ];

    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }
}