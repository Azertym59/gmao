<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    use HasFactory;

    /**
     * Les statuts possibles pour un job d'impression
     */
    const STATUS_PENDING = 'pending';    // En attente d'impression
    const STATUS_PRINTING = 'printing';  // En cours d'impression
    const STATUS_COMPLETED = 'completed'; // Impression terminée
    const STATUS_FAILED = 'failed';      // Échec de l'impression
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'printer_id',
        'content',
        'name',
        'status',
        'message',
        'job_token',
        'content_type',
        'options',
        'user_id',
        'error_message',
        'attempts',
        'completed_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * Relation avec l'imprimante
     */
    public function printer()
    {
        return $this->belongsTo(Printer::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Vérifier si le job peut être relancé
     * 
     * @return bool
     */
    public function canRetry()
    {
        return $this->status === self::STATUS_FAILED && ($this->attempts < 3 || is_null($this->attempts));
    }
    
    /**
     * Marquer comme en cours d'impression
     * 
     * @return $this
     */
    public function markAsPrinting()
    {
        $this->status = self::STATUS_PRINTING;
        $this->attempts = ($this->attempts ?? 0) + 1;
        $this->save();
        
        return $this;
    }
    
    /**
     * Marquer comme terminé
     * 
     * @return $this
     */
    public function markAsCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
        $this->completed_at = now();
        $this->save();
        
        return $this;
    }
    
    /**
     * Marquer comme échoué
     * 
     * @param string $error Message d'erreur
     * @return $this
     */
    public function markAsFailed($error = null)
    {
        $this->status = self::STATUS_FAILED;
        if ($error) {
            $this->error_message = $error;
        }
        $this->save();
        
        return $this;
    }
}