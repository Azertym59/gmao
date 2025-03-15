<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Module;
use Illuminate\Http\Request;

class SuiviController extends Controller
{
    /**
     * Affiche la page de suivi du chantier pour un client
     * Cette page est accessible sans authentification avec un token unique
     */
    public function show(string $token)
    {
        // Trouver le chantier avec ce token de suivi
        $chantier = Chantier::where('token_suivi', $token)->first();
        
        if (!$chantier) {
            abort(404, 'Ce lien de suivi n\'est pas valide.');
        }
        
        // Mettre à jour la date de dernière utilisation du token
        $chantier->token_suivi_last_used_at = now();
        $chantier->save();
        
        // Charger les relations nécessaires pour l'affichage
        $chantier->load(['client', 'produits.dalles.modules.interventions.technicien']);
        
        // Calculer les statistiques
        $totalModules = 0;
        $modulesTermines = 0;
        $tempsTotal = 0;
        $interventionsEnCours = 0;
        
        foreach ($chantier->produits as $produit) {
            foreach ($produit->dalles as $dalle) {
                $totalModules += $dalle->modules->count();
                $modulesTermines += $dalle->modules->where('etat', 'termine')->count();
                
                foreach ($dalle->modules as $module) {
                    foreach ($module->interventions as $intervention) {
                        $tempsTotal += $intervention->temps_total;
                        if (!$intervention->is_completed) {
                            $interventionsEnCours++;
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
        
        return view('suivi.chantier', compact(
            'chantier',
            'totalModules',
            'modulesTermines',
            'pourcentageTermines',
            'tempsFormate',
            'interventionsEnCours'
        ));
    }
    
    /**
     * Affiche les détails d'un module spécifique
     */
    public function showModule(string $token, int $moduleId)
    {
        // Vérifier le token de suivi
        $chantier = Chantier::where('token_suivi', $token)->first();
        
        if (!$chantier) {
            abort(404, 'Ce lien de suivi n\'est pas valide.');
        }
        
        // Trouver le module et vérifier qu'il appartient bien à ce chantier
        $module = Module::with(['dalle.produit.chantier', 'interventions.diagnostic', 'interventions.reparation', 'interventions.technicien'])
            ->whereHas('dalle.produit', function ($query) use ($chantier) {
                $query->where('chantier_id', $chantier->id);
            })
            ->findOrFail($moduleId);
        
        // Mettre à jour la date de dernière utilisation du token
        $chantier->token_suivi_last_used_at = now();
        $chantier->save();
        
        return view('suivi.module', compact('module', 'token'));
    }
    
    /**
     * Génère des tokens pour tous les chantiers qui n'en ont pas encore
     */
    public function generateTokensForAll()
    {
        $chantiers = Chantier::whereNull('token_suivi')->get();
        $count = 0;
        
        foreach ($chantiers as $chantier) {
            $chantier->genererTokenSuivi();
            $count++;
        }
        
        return response()->json([
            'success' => true,
            'message' => $count . ' tokens de suivi générés avec succès'
        ]);
    }
}