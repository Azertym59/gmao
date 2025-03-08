<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'is_read',
        'data'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthode pour marquer comme lue
    public function markAsRead()
    {
        $this->is_read = true;
        $this->save();
        
        return $this;
    }

    // Créer une notification d'assignation de module
    public static function createAssignmentNotification($user_id, $module)
    {
        return self::create([
            'user_id' => $user_id,
            'type' => 'assignment',
            'title' => 'Nouveau module assigné',
            'message' => "Le module {$module->reference_module} vous a été assigné pour réparation.",
            'link' => route('modules.show', $module),
            'data' => [
                'module_id' => $module->id,
                'chantier_id' => $module->dalle->produit->chantier->id,
                'chantier_nom' => $module->dalle->produit->chantier->nom
            ]
        ]);
    }

    // Créer une notification de date limite approchante
    public static function createDeadlineNotification($user_id, $chantier)
    {
        $joursRestants = now()->diffInDays($chantier->date_butoir, false);
        
        return self::create([
            'user_id' => $user_id,
            'type' => 'deadline',
            'title' => 'Date limite approchante',
            'message' => "Le chantier {$chantier->nom} arrive à échéance dans {$joursRestants} jours.",
            'link' => route('chantiers.show', $chantier),
            'data' => [
                'chantier_id' => $chantier->id,
                'date_butoir' => $chantier->date_butoir->format('Y-m-d')
            ]
        ]);
    }

    // Créer une notification de commentaire
    public static function createCommentNotification($user_id, $intervention, $comment)
    {
        return self::create([
            'user_id' => $user_id,
            'type' => 'comment',
            'title' => 'Nouveau commentaire',
            'message' => "Un nouveau commentaire a été ajouté sur votre intervention: \"{$comment}\"",
            'link' => route('interventions.show', $intervention),
            'data' => [
                'intervention_id' => $intervention->id,
                'module_id' => $intervention->module->id
            ]
        ]);
    }
}