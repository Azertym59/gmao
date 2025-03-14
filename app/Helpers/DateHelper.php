<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Calcule et formate les jours et heures restants jusqu'à une date cible
     * Affiche le retard si la date est dépassée
     *
     * @param string|Carbon $targetDate La date cible
     * @return string Le texte formaté (ex: "3j 5h restants" ou "Retard de 2j 3h")
     */
    public static function formatTimeRemaining($targetDate): string
    {
        // Convertir en Carbon si ce n'est pas déjà le cas
        if (!($targetDate instanceof Carbon)) {
            $targetDate = Carbon::parse($targetDate);
        }
        
        $now = Carbon::now();
        
        // Si la date est dépassée
        if ($now->gt($targetDate)) {
            $diffHours = $now->diffInHours($targetDate, false); // Valeur négative
            $days = floor(abs($diffHours) / 24);
            $hours = abs($diffHours) % 24;
            
            if ($days > 0) {
                return "Retard de {$days}j " . ($hours > 0 ? "{$hours}h" : "");
            } else {
                return "Retard de {$hours}h";
            }
        } 
        // Si la date n'est pas dépassée
        else {
            $diffHours = $now->diffInHours($targetDate); // Valeur positive
            $days = floor($diffHours / 24);
            $hours = $diffHours % 24;
            
            if ($days > 0) {
                return "{$days}j " . ($hours > 0 ? "{$hours}h restants" : "restants");
            } else {
                return "{$hours}h restantes";
            }
        }
    }
    
    /**
     * Renvoie une classe CSS pour colorer l'affichage en fonction du temps restant
     *
     * @param string|Carbon $targetDate La date cible
     * @return string La classe CSS à appliquer
     */
    public static function getTimeRemainingClass($targetDate): string
    {
        // Convertir en Carbon si ce n'est pas déjà le cas
        if (!($targetDate instanceof Carbon)) {
            $targetDate = Carbon::parse($targetDate);
        }
        
        $now = Carbon::now();
        
        // Si la date est dépassée
        if ($now->gt($targetDate)) {
            return 'text-danger';
        }
        
        // Si moins de 3 jours restants
        $diffDays = $now->diffInDays($targetDate);
        if ($diffDays < 3) {
            return 'text-warning';
        }
        
        // Sinon tout va bien
        return 'text-success';
    }
}