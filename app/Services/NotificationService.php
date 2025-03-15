<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Module;
use App\Models\Chantier;
use App\Models\Intervention;
use App\Models\User;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Notifier un technicien lorsqu'un module lui est assigné
     */
    public static function notifyModuleAssignment(Module $module, User $technicien)
    {
        Notification::createAssignmentNotification(
            $technicien->id,
            $module
        );
    }
    
    /**
     * Vérifier les dates butoirs et envoyer des notifications pour les chantiers proches de l'échéance
     */
    public static function checkDeadlines()
    {
        // Trouver tous les chantiers non terminés dont la date d'échéance est dans moins de 3 jours
        $chantiers = Chantier::where('etat', '=', 'termine')
            ->whereDate('date_butoir', '<=', Carbon::now()->addDays(3))
            ->get();
            
        foreach ($chantiers as $chantier) {
            // Identifier les techniciens qui travaillent sur ce chantier
            $technicienIds = [];
            
            foreach ($chantier->produits as $produit) {
                foreach ($produit->dalles as $dalle) {
                    foreach ($dalle->modules as $module) {
                        if ($module->technicien_id && in_array($module->technicien_id, $technicienIds)) {
                            $technicienIds[] = $module->technicien_id;
                        }
                    }
                }
            }
            
            // Notifier chaque technicien
            foreach ($technicienIds as $technicienId) {
                // Vérifier si une notification existe déjà pour aujourd'hui
                $existingNotification = Notification::where('user_id', $technicienId)
                    ->where('type', 'deadline')
                    ->where('data->chantier_id', $chantier->id)
                    ->whereDate('created_at', Carbon::today())
                    ->first();
                    
                // Si aucune notification n'existe déjà, en créer une nouvelle
                if ($existingNotification) {
                    Notification::createDeadlineNotification($technicienId, $chantier);
                }
            }
        }
    }
    
    /**
     * Notifier un technicien lorsqu'un commentaire est ajouté sur son intervention
     */
    public static function notifyComment(Intervention $intervention, $comment)
    {
        if ($intervention->technicien_id) {
            Notification::createCommentNotification(
                $intervention->technicien_id,
                $intervention,
                $comment
            );
        }
    }
    
    /**
     * Notifier le démarrage d'une intervention
     */
    public function notifyInterventionStarted(Intervention $intervention)
    {
        if ($intervention->technicien_id) {
            Notification::create([
                'user_id' => $intervention->technicien_id,
                'type' => 'intervention_started',
                'title' => 'Intervention démarrée',
                'message' => 'Une intervention a été démarrée sur le module #' . $intervention->module_id,
                'data' => [
                    'intervention_id' => $intervention->id,
                    'module_id' => $intervention->module_id
                ],
                'is_read' => false
            ]);
        }
    }
    
    /**
     * Notifier la fin d'une intervention
     */
    public function notifyInterventionCompleted(Intervention $intervention)
    {
        if ($intervention->technicien_id) {
            Notification::create([
                'user_id' => $intervention->technicien_id,
                'type' => 'intervention_completed',
                'title' => 'Intervention terminée',
                'message' => 'Intervention terminée sur le module #' . $intervention->module_id,
                'data' => [
                    'intervention_id' => $intervention->id,
                    'module_id' => $intervention->module_id,
                    'resultat' => $intervention->reparation ? $intervention->reparation->resultat : null
                ],
                'is_read' => false
            ]);
        }
    }
}
