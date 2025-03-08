<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Client;
use App\Models\Module;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques des modules
        $statsModules = $this->getModulesStats();
        
        // Chantiers urgents
        $chantiersUrgents = $this->getChantiersUrgents();
        
        // Statistiques des interventions
        $statsInterventions = $this->getInterventionsStats();
        
        // Interventions récentes
        $interventionsRecentes = $this->getInterventionsRecentes();
        
        // Chantiers actifs
        $chantiersActifs = $this->getChantiersActifs();
        
        return view('dashboard.index', compact(
            'statsModules',
            'chantiersUrgents',
            'statsInterventions',
            'interventionsRecentes',
            'chantiersActifs'
        ));
    }
    
    private function getModulesStats()
    {
        $total = Module::count();
        $termines = Module::where('etat', 'termine')->count();
        $en_cours = Module::where('etat', 'en_cours')->count();
        $defaillants = Module::where('etat', 'defaillant')->count();
        $non_commences = Module::where('etat', 'non_commence')->count();
        $pourcentage = $total > 0 ? round(($termines / $total) * 100) : 0;
        
        return [
            'total' => $total,
            'termines' => $termines,
            'en_cours' => $en_cours,
            'defaillants' => $defaillants,
            'non_commences' => $non_commences,
            'pourcentage' => $pourcentage
        ];
    }
    
    private function getChantiersUrgents()
    {
        return Chantier::with('client')
            ->where('etat', '!=', 'termine')
            ->whereDate('date_butoir', '<=', Carbon::now()->addDays(7))
            ->orderBy('date_butoir', 'asc')
            ->take(5)
            ->get()
            ->map(function ($chantier) {
                // Calculer le nombre de jours restants
                $chantier->jours_restants = Carbon::now()->diffInDays(Carbon::parse($chantier->date_butoir), false);
                
                // Calculer le pourcentage d'avancement
                if (isset($chantier->nb_modules_total) && $chantier->nb_modules_total > 0) {
                    $chantier->pourcentage_avancement = round(($chantier->nb_modules_termines / $chantier->nb_modules_total) * 100);
                } else {
                    // Si les propriétés nb_modules_total/nb_modules_termines n'existent pas directement, calculer
                    $nbModulesTotal = 0;
                    $nbModulesTermines = 0;
                    
                    // Vérifier si la relation produits existe
                    if ($chantier->produits) {
                        foreach ($chantier->produits as $produit) {
                            if ($produit->dalles) {
                                foreach ($produit->dalles as $dalle) {
                                    if ($dalle->modules) {
                                        $nbModulesTotal += $dalle->modules->count();
                                        $nbModulesTermines += $dalle->modules->where('etat', 'termine')->count();
                                    }
                                }
                            }
                        }
                    }
                    
                    $chantier->nb_modules_total = $nbModulesTotal;
                    $chantier->nb_modules_termines = $nbModulesTermines;
                    $chantier->pourcentage_avancement = $nbModulesTotal > 0 ? round(($nbModulesTermines / $nbModulesTotal) * 100) : 0;
                }
                
                return $chantier;
            });
    }
    
    private function getInterventionsStats()
    {
        // Nombre total d'interventions
        $totalInterventions = Intervention::count();
        
        // Temps moyen par intervention
        $tempsTotal = Intervention::where('is_completed', true)->sum('temps_total') ?: 0;
        $tempsMoyen = $totalInterventions > 0 ? ($tempsTotal / $totalInterventions) : 0;
        $tempsMoyenMinutes = $tempsMoyen / 60;
        
        // Formater le temps moyen pour l'affichage (HH:MM:SS)
        $heures = floor($tempsMoyen / 3600);
        $minutes = floor(($tempsMoyen % 3600) / 60);
        $secondes = $tempsMoyen % 60;
        $tempsMoyenFormat = sprintf('%02dh %02dm %02ds', $heures, $minutes, $secondes);
        
        // Taux de réussite des réparations
        $interventionsTerminees = Intervention::where('is_completed', true)->count();
        $interventionsReussies = Intervention::whereHas('module', function ($query) {
            $query->where('etat', 'termine');
        })->count();
        
        $tauxReussite = $interventionsTerminees > 0 ? round(($interventionsReussies / $interventionsTerminees) * 100) : 0;
        
        // Nombre de composants remplacés
        $nbComposants = 0;
        $interventionsAvecReparations = Intervention::with('reparation')->has('reparation')->get();
        
        foreach ($interventionsAvecReparations as $intervention) {
            $nbComposants += $intervention->reparation->nb_leds_remplacees ?? 0;
            $nbComposants += $intervention->reparation->nb_ic_remplaces ?? 0;
            $nbComposants += $intervention->reparation->nb_masques_remplaces ?? 0;
        }
        
        // Charge de travail (basée sur le nombre d'interventions récentes par rapport à la capacité)
        $interventionsRecentes = Intervention::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $capaciteEstimee = 50; // Nombre estimé d'interventions possibles par semaine
        $chargeTravail = min(100, round(($interventionsRecentes / $capaciteEstimee) * 100));
        
        return [
            'temps_moyen' => $tempsMoyen,
            'temps_moyen_minutes' => $tempsMoyenMinutes,
            'temps_moyen_format' => $tempsMoyenFormat,
            'taux_reussite' => $tauxReussite,
            'nb_composants' => $nbComposants,
            'charge_travail' => $chargeTravail
        ];
    }
    
    private function getInterventionsRecentes()
    {
        return Intervention::with(['module.dalle.produit.chantier', 'technicien'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }
    
    private function getChantiersActifs()
    {
        return Chantier::with('client')
            ->where('etat', '!=', 'termine')
            ->orderBy('date_butoir', 'asc')
            ->take(5)
            ->get()
            ->map(function ($chantier) {
                // Si les propriétés nb_modules_total/nb_modules_termines n'existent pas directement, calculer
                if (!isset($chantier->nb_modules_total) || !isset($chantier->nb_modules_termines)) {
                    $nbModulesTotal = 0;
                    $nbModulesTermines = 0;
                    
                    // Vérifier si la relation produits existe
                    if ($chantier->produits) {
                        foreach ($chantier->produits as $produit) {
                            if ($produit->dalles) {
                                foreach ($produit->dalles as $dalle) {
                                    if ($dalle->modules) {
                                        $nbModulesTotal += $dalle->modules->count();
                                        $nbModulesTermines += $dalle->modules->where('etat', 'termine')->count();
                                    }
                                }
                            }
                        }
                    }
                    
                    $chantier->nb_modules_total = $nbModulesTotal;
                    $chantier->nb_modules_termines = $nbModulesTermines;
                }
                
                $chantier->pourcentage_avancement = $chantier->nb_modules_total > 0 
                    ? round(($chantier->nb_modules_termines / $chantier->nb_modules_total) * 100) 
                    : 0;
                
                return $chantier;
            });
    }
    
    private function formatTemps($secondes)
    {
        if (!$secondes) return '00:00:00';
        
        $heures = floor($secondes / 3600);
        $minutes = floor(($secondes % 3600) / 60);
        $secondes = $secondes % 60;
        
        return sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
    }
}