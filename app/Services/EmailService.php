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
        
        // Envoyer l'email
        Mail::send('emails.chantier_created', [
            'chantier' => $chantier, 
            'client' => $client,
            'produits' => $produits,
            'totalModules' => $totalModules
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
        
        // Envoyer l'email
        Mail::send('emails.interventions_started', [
            'chantier' => $chantier, 
            'client' => $client,
            'interventionsStarted' => $interventionsStarted,
            'totalModules' => $totalModules
        ], function ($message) use ($email, $nomClient, $chantier) {
            $message->to($email, $nomClient)
                ->subject('Les réparations ont commencé sur votre chantier #' . $chantier->reference);
        });
    }
    
    /**
     * Envoyer un email au client lorsque le chantier est terminé avec le rapport en pièce jointe
     */
    public function sendChantierCompletedEmail(Chantier $chantier)
    {
        $chantier->load(['client', 'produits.dalles.modules.interventions.reparation']);
        
        // Préparer les données pour l'email
        $client = $chantier->client;
        $email = $client->email;
        $nomClient = $client->nom_complet;
        
        // Calcul des statistiques pour le PDF (même logique que dans RapportController)
        $totalModules = 0;
        $modulesTermines = 0;
        $tempsTotal = 0;
        $totalLEDsRemplacees = 0;
        $totalICsRemplaces = 0;
        $totalMasquesRemplaces = 0;
        
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
                        
                        if($intervention->reparation) {
                            $totalLEDsRemplacees += $intervention->reparation->nb_leds_remplacees;
                            $totalICsRemplaces += $intervention->reparation->nb_ic_remplaces;
                            $totalMasquesRemplaces += $intervention->reparation->nb_masques_remplaces;
                        }
                    }
                }
            }
        }
        
        $pourcentageTermines = $totalModules > 0 ? round(($modulesTermines / $totalModules) * 100) : 0;
        
        // Formatage du temps total
        $heures = floor($tempsTotal / 3600);
        $minutes = floor(($tempsTotal % 3600) / 60);
        $secondes = $tempsTotal % 60;
        $tempsFormate = sprintf('%dh %02dm %02ds', $heures, $minutes, $secondes);
        
        // Générer le PDF du rapport
        $pdf = PDF::loadView('rapports.chantier', compact(
            'chantier',
            'totalModules',
            'modulesTermines',
            'pourcentageTermines',
            'tempsTotal',
            'tempsFormate',
            'totalLEDsRemplacees',
            'totalICsRemplaces',
            'totalMasquesRemplaces'
        ));
        
        $pdfContent = $pdf->output();
        $filename = 'rapport-chantier-' . $chantier->reference . '.pdf';
        
        // Envoyer l'email avec la pièce jointe
        Mail::send('emails.chantier_completed', [
            'chantier' => $chantier, 
            'client' => $client,
            'totalModules' => $totalModules,
            'modulesTermines' => $modulesTermines,
            'tempsFormate' => $tempsFormate
        ], function ($message) use ($email, $nomClient, $chantier, $pdfContent, $filename) {
            $message->to($email, $nomClient)
                ->subject('Votre chantier #' . $chantier->reference . ' est terminé')
                ->attachData($pdfContent, $filename, [
                    'mime' => 'application/pdf',
                ]);
        });
    }
}