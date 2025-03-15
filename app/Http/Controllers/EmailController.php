<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Intervention;
use App\Services\EmailService;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Envoyer manuellement un email pour le chantier créé
     */
    public function sendChantierCreatedEmail(Request $request, Chantier $chantier)
    {
        try {
            $this->emailService->sendChantierCreatedEmail($chantier);
            return redirect()->back()->with('success', 'Email de création du chantier envoyé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer manuellement un email pour le démarrage des interventions
     */
    public function sendInterventionsStartedEmail(Request $request, Chantier $chantier)
    {
        try {
            $this->emailService->sendInterventionsStartedEmail($chantier);
            return redirect()->back()->with('success', 'Email de démarrage des interventions envoyé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer manuellement un email pour la finalisation du chantier
     */
    public function sendChantierCompletedEmail(Request $request, Chantier $chantier)
    {
        try {
            $this->emailService->sendChantierCompletedEmail($chantier);
            return redirect()->back()->with('success', 'Email de finalisation du chantier envoyé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }
    
    /**
     * Envoyer l'email approprié selon l'état du chantier
     */
    public function sendChantierEmail(Request $request, Chantier $chantier)
    {
        $emailType = $request->input('email_type');
        
        try {
            switch ($emailType) {
                case 'created':
                    $this->emailService->sendChantierCreatedEmail($chantier);
                    $message = 'Email de création du chantier envoyé avec succès.';
                    break;
                case 'started':
                    $this->emailService->sendInterventionsStartedEmail($chantier);
                    $message = 'Email de démarrage des interventions envoyé avec succès.';
                    break;
                case 'completed':
                    $this->emailService->sendChantierCompletedEmail($chantier);
                    $message = 'Email de finalisation du chantier envoyé avec succès.';
                    break;
                default:
                    return redirect()->back()->with('error', 'Type d\'email inconnu.');
            }
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }
}
