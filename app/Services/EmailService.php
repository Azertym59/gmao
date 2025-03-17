<?php

namespace App\Services;

use App\Models\Chantier;
use App\Models\Intervention;
use Illuminate\Support\Facades\Mail;
use PDF;

class EmailService
{
    /**
     * Envoyer un email au client lors de la création d'un chantier
     */
    public function sendChantierCreatedEmail(Chantier $chantier)
    {
        $chantier->load(['client', 'produits.dalles.modules']);
        
        // Préparer les données pour l'email
        $client = $chantier->client;
        $email = $client->email;
        $nomClient = $client->nom_complet;
        $produits = $chantier->produits;
        
        // S'assurer que le chantier a un token de suivi
        if (empty($chantier->token_suivi)) {
            $chantier->token_suivi = $chantier->genererTokenSuivi();
        }
        
        // Générer le lien de suivi
        $lienSuivi = url('/suivi/' . $chantier->token_suivi);
        
        // Calculer le nombre total de modules et corriger les valeurs INCONNU
        $totalModules = 0;
        foreach ($produits as $produit) {
            // Patch for INCONNU values
            if ($produit->marque == 'INCONNU' || $produit->marque === null) {
                $produit->marque = $produit->chantier->client->societe ?? 'Écran LED';
            }
            if ($produit->modele == 'INCONNU' || $produit->modele === null) {
                $produit->modele = 'Réparation ' . date('Y');
            }
            
            foreach ($produit->dalles as $dalle) {
                $totalModules += $dalle->modules->count();
            }
        }
        
        // Générer le lien d'inscription
        $lienInscription = url(route('client.register', [], false));
        
        // Envoyer l'email
        Mail::send('emails.chantier_created', [
            'chantier' => $chantier, 
            'client' => $client,
            'produits' => $produits,
            'totalModules' => $totalModules,
            'lienSuivi' => $lienSuivi,
            'lienInscription' => $lienInscription
        ], function ($message) use ($email, $nomClient, $chantier) {
            $message->to($email, $nomClient)
                ->subject('Votre chantier de réparation #' . $chantier->reference . ' a été créé');
        });
    }
    
    /**
     * Envoyer un email au client lorsque des interventions commencent sur son chantier
     */
    public function sendInterventionsStartedEmail(Chantier $chantier)
    {
        $chantier->load(['client', 'produits.dalles.modules.interventions']);
        
        // Préparer les données pour l'email
        $client = $chantier->client;
        $email = $client->email;
        $nomClient = $client->nom_complet;
        
        // S'assurer que le chantier a un token de suivi
        if (empty($chantier->token_suivi)) {
            $chantier->token_suivi = $chantier->genererTokenSuivi();
        }
        
        // Générer le lien de suivi
        $lienSuivi = url('/suivi/' . $chantier->token_suivi);
        
        // Compter les interventions démarrées
        $interventionsStarted = 0;
        $totalModules = 0;
        
        foreach ($chantier->produits as $produit) {
            // Patch for INCONNU values
            if ($produit->marque == 'INCONNU' || $produit->marque === null) {
                $produit->marque = $produit->chantier->client->societe ?? 'Écran LED';
            }
            if ($produit->modele == 'INCONNU' || $produit->modele === null) {
                $produit->modele = 'Réparation ' . date('Y');
            }
            
            foreach ($produit->dalles as $dalle) {
                $totalModules += $dalle->modules->count();
                foreach ($dalle->modules as $module) {
                    if ($module->interventions->where('is_completed', false)->count() > 0) {
                        $interventionsStarted++;
                    }
                }
            }
        }
        
        // Générer le lien d'inscription
        $lienInscription = url(route('client.register', [], false));
        
        // Envoyer l'email
        Mail::send('emails.interventions_started', [
            'chantier' => $chantier, 
            'client' => $client,
            'interventionsStarted' => $interventionsStarted,
            'totalModules' => $totalModules,
            'lienSuivi' => $lienSuivi,
            'lienInscription' => $lienInscription
        ], function ($message) use ($email, $nomClient, $chantier) {
            $message->to($email, $nomClient)
                ->subject('Les réparations ont commencé sur votre chantier #' . $chantier->reference);
        });
    }
    
    /**
     * Envoyer un email au client lorsque le chantier est terminé
     */
    public function sendChantierCompletedEmail(Chantier $chantier)
    {
        $chantier->load(['client', 'produits.dalles.modules.interventions.reparation']);
        
        // Préparer les données pour l'email
        $client = $chantier->client;
        $email = $client->email;
        $nomClient = $client->nom_complet;
        
        // S'assurer que le chantier a un token de suivi
        if (empty($chantier->token_suivi)) {
            $chantier->token_suivi = $chantier->genererTokenSuivi();
        }
        
        // Générer le lien de suivi
        $lienSuivi = url('/suivi/' . $chantier->token_suivi);
        
        // Calcul des statistiques de base pour l'email
        $totalModules = 0;
        $modulesTermines = 0;
        $tempsTotal = 0;
        
        foreach($chantier->produits as $produit) {
            // Patch for INCONNU values
            if ($produit->marque == 'INCONNU' || $produit->marque === null) {
                $produit->marque = $produit->chantier->client->societe ?? 'Écran LED';
            }
            if ($produit->modele == 'INCONNU' || $produit->modele === null) {
                $produit->modele = 'Réparation ' . date('Y');
            }
            
            foreach($produit->dalles as $dalle) {
                $totalModules += $dalle->modules->count();
                $modulesTermines += $dalle->modules->where('etat', 'termine')->count();
                
                foreach($dalle->modules as $module) {
                    foreach($module->interventions as $intervention) {
                        $tempsTotal += $intervention->temps_total;
                    }
                }
            }
        }
        
        // Formatage du temps total
        $heures = floor($tempsTotal / 3600);
        $minutes = floor(($tempsTotal % 3600) / 60);
        $secondes = $tempsTotal % 60;
        $tempsFormate = sprintf('%dh %02dm %02ds', $heures, $minutes, $secondes);
        
        // Générer le lien d'inscription
        $lienInscription = url(route('client.register', [], false));
        
        // Envoyer l'email simple sans pièce jointe
        Mail::send('emails.chantier_completed', [
            'chantier' => $chantier, 
            'client' => $client,
            'totalModules' => $totalModules,
            'modulesTermines' => $modulesTermines,
            'tempsFormate' => $tempsFormate,
            'lienSuivi' => $lienSuivi,
            'lienInscription' => $lienInscription
        ], function ($message) use ($email, $nomClient, $chantier) {
            $message->to($email, $nomClient)
                ->subject('Votre chantier #' . $chantier->reference . ' est terminé');
        });
    }
}