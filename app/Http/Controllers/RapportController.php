<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Module;
use App\Models\Intervention;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

class RapportController extends Controller
{
    public function index()
    {
        $chantiers = Chantier::with('client')->get();
        $interventions = Intervention::with(['module.dalle.produit.chantier', 'technicien'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        return view('rapports.index', compact('chantiers', 'interventions'));
    }
    
    public function genererRapportChantier(Chantier $chantier)
    {
        $chantier->load(['client', 'produits.dalles.modules.interventions.reparation', 'produits.dalles.modules.interventions.diagnostic']);
        
        // Calcul des statistiques
        $totalModules = 0;
        $modulesTermines = 0;
        $modulesEnCours = 0;
        $modulesDefaillants = 0;
        $modulesNonCommences = 0;
        $tempsTotal = 0;
        $totalLEDsRemplacees = 0;
        $totalICsRemplaces = 0;
        $totalMasquesRemplaces = 0;
        $totalInterventions = 0;
        $causes = [
            'usure_normale' => 0,
            'choc' => 0,
            'defaut_usine' => 0,
            'autre' => 0,
        ];
        $composantsDefectueux = [];
        
        foreach($chantier->produits as $produit) {
            foreach($produit->dalles as $dalle) {
                $totalModules += $dalle->modules->count();
                $modulesTermines += $dalle->modules->where('etat', 'termine')->count();
                $modulesEnCours += $dalle->modules->where('etat', 'en_cours')->count();
                $modulesDefaillants += $dalle->modules->where('etat', 'defaillant')->count();
                $modulesNonCommences += $dalle->modules->where('etat', 'non_commence')->count();
                
                foreach($dalle->modules as $module) {
                    foreach($module->interventions as $intervention) {
                        $totalInterventions++;
                        $tempsTotal += $intervention->temps_total;
                        
                        if($intervention->diagnostic) {
                            // Comptage des causes
                            if(!empty($intervention->diagnostic->cause)) {
                                $causes[$intervention->diagnostic->cause]++;
                            }
                            
                            // Comptage des composants défectueux
                            if(!empty($intervention->diagnostic->composant_defectueux)) {
                                if(!isset($composantsDefectueux[$intervention->diagnostic->composant_defectueux])) {
                                    $composantsDefectueux[$intervention->diagnostic->composant_defectueux] = 0;
                                }
                                $composantsDefectueux[$intervention->diagnostic->composant_defectueux]++;
                            }
                        }
                        
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
        
        // Calcul du temps moyen par intervention
        $tempsMoyen = $totalInterventions > 0 ? $tempsTotal / $totalInterventions : 0;
        $heuresMoyen = floor($tempsMoyen / 3600);
        $minutesMoyen = floor(($tempsMoyen % 3600) / 60);
        $secondesMoyen = floor($tempsMoyen % 60);
        $tempsMoyenFormate = sprintf('%dh %02dm %02ds', $heuresMoyen, $minutesMoyen, $secondesMoyen);
        
        // Préparer les données pour les graphiques
        $dataEtatModules = [
            'labels' => ['Terminés', 'En cours', 'Défaillants', 'Non commencés'],
            'data' => [$modulesTermines, $modulesEnCours, $modulesDefaillants, $modulesNonCommences]
        ];
        
        $dataCauses = [
            'labels' => ['Usure normale', 'Dommage physique', 'Défaut de fabrication', 'Autre'],
            'data' => [$causes['usure_normale'], $causes['choc'], $causes['defaut_usine'], $causes['autre']]
        ];
        
        $dataComposants = [
            'labels' => ['LEDs', 'ICs', 'Masques'],
            'data' => [$totalLEDsRemplacees, $totalICsRemplaces, $totalMasquesRemplaces]
        ];
        
        // Trier les composants défectueux par fréquence
        arsort($composantsDefectueux);
        $topComposantsDefectueux = array_slice($composantsDefectueux, 0, 5);
        
        $dataComposantsDefectueux = [
            'labels' => array_keys($topComposantsDefectueux),
            'data' => array_values($topComposantsDefectueux)
        ];
        
        $pdf = PDF::loadView('rapports.chantier', compact(
            'chantier',
            'totalModules',
            'modulesTermines',
            'modulesEnCours',
            'modulesDefaillants',
            'modulesNonCommences',
            'pourcentageTermines',
            'tempsTotal',
            'tempsFormate',
            'tempsMoyenFormate',
            'totalLEDsRemplacees',
            'totalICsRemplaces',
            'totalMasquesRemplaces',
            'totalInterventions',
            'causes',
            'composantsDefectueux',
            'dataEtatModules',
            'dataCauses',
            'dataComposants',
            'dataComposantsDefectueux'
        ));
        
        // Définir le format paysage pour avoir plus d'espace pour les graphiques
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('rapport-chantier-' . $chantier->reference . '.pdf');
    }
    
    public function genererFicheIntervention(Intervention $intervention)
    {
        $intervention->load(['module.dalle.produit.chantier.client', 'technicien', 'diagnostic', 'reparation']);
        
        // Formatage du temps total
        $heures = floor($intervention->temps_total / 3600);
        $minutes = floor(($intervention->temps_total % 3600) / 60);
        $secondes = $intervention->temps_total % 60;
        $tempsFormate = sprintf('%dh %02dm %02ds', $heures, $minutes, $secondes);
        
        $pdf = PDF::loadView('rapports.intervention', compact('intervention', 'tempsFormate'));
        
        return $pdf->download('fiche-intervention-' . $intervention->id . '.pdf');
    }
    
    public function genererRapportPerformance()
    {
        // Pour le moment, simple redirection - à implémenter plus tard
        return redirect()->route('rapports.index')->with('info', 'Fonctionnalité en cours de développement');
    }
    
    public function genererRapportInventaire()
    {
        // Pour le moment, simple redirection - à implémenter plus tard
        return redirect()->route('rapports.index')->with('info', 'Fonctionnalité en cours de développement');
    }
    
    public function genererRapportStatistiques()
    {
        // Pour le moment, simple redirection - à implémenter plus tard
        return redirect()->route('rapports.index')->with('info', 'Fonctionnalité en cours de développement');
    }
}