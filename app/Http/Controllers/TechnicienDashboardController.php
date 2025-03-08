<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Module;
use App\Models\Intervention;
use App\Models\Diagnostic;
use App\Models\Reparation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TechnicienDashboardController extends Controller
{
    public function index()
    {
        $technicien = Auth::user();
        
        // 1. Les 5 derniers chantiers en cours
        $chantiersEnCours = $this->getLatestChantiers('en_cours', 5);
        
        // 2. Les chantiers terminés
        $chantiersTermines = $this->getLatestChantiers('termine', 5);
        
        // 3. Nombre de modules réparés au global par ce technicien
        $statsModules = $this->getModulesStats($technicien->id);
        
        // 4. Nombre de composants remplacés
        $statsComposants = $this->getComposantsStats($technicien->id);
        
        // 5. Temps passé au total
        $statsTempsTotaux = $this->getTempsTotaux($technicien->id);
        
        // 6. Temps moyen par module
        $tempsMoyenParModule = $this->getTempsMoyenParModule($technicien->id);
        
        // 7. Modules en cours de réparation
        $modulesEnCours = $this->getModulesEnCours($technicien->id);
        
        // 8. Activité récente des interventions
        $interventionsRecentes = $this->getInterventionsRecentes($technicien->id);
        
        return view('technicien.dashboard', compact(
            'chantiersEnCours',
            'chantiersTermines',
            'statsModules',
            'statsComposants',
            'statsTempsTotaux',
            'tempsMoyenParModule',
            'modulesEnCours',
            'interventionsRecentes'
        ));
    }
    
    private function getLatestChantiers($etat, $limit)
    {
        return Chantier::with('client')
            ->where('etat', $etat)
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($chantier) {
                // Calcul de progression pour chaque chantier
                $totalModules = 0;
                $modulesTermines = 0;
                
                foreach ($chantier->produits as $produit) {
                    foreach ($produit->dalles as $dalle) {
                        $totalModules += $dalle->modules->count();
                        $modulesTermines += $dalle->modules->where('etat', 'termine')->count();
                    }
                }
                
                $chantier->progression = $totalModules > 0 ? round(($modulesTermines / $totalModules) * 100) : 0;
                
                return $chantier;
            });
    }
    
    private function getModulesStats($technicienId)
    {
        $interventions = Intervention::where('technicien_id', $technicienId)->get();
        
        $moduleIds = $interventions->pluck('module_id')->unique();
        
        $total = $moduleIds->count();
        $termines = Module::whereIn('id', $moduleIds)->where('etat', 'termine')->count();
        $enCours = Module::whereIn('id', $moduleIds)->where('etat', 'en_cours')->count();
        $defaillants = Module::whereIn('id', $moduleIds)->where('etat', 'defaillant')->count();
        
        return [
            'total' => $total,
            'termines' => $termines,
            'en_cours' => $enCours,
            'defaillants' => $defaillants,
            'pourcentage_succes' => $total > 0 ? round(($termines / $total) * 100) : 0
        ];
    }
    
    private function getComposantsStats($technicienId)
    {
        $interventionsIds = Intervention::where('technicien_id', $technicienId)
            ->pluck('id');
            
        $reparations = Reparation::whereIn('intervention_id', $interventionsIds)->get();
        
        return [
            'leds' => $reparations->sum('nb_leds_remplacees'),
            'ics' => $reparations->sum('nb_ic_remplaces'),
            'masques' => $reparations->sum('nb_masques_remplaces'),
            'total' => $reparations->sum('nb_leds_remplacees') + 
                       $reparations->sum('nb_ic_remplaces') + 
                       $reparations->sum('nb_masques_remplaces')
        ];
    }
    
    private function getTempsTotaux($technicienId)
    {
        $tempsTotal = Intervention::where('technicien_id', $technicienId)
            ->sum('temps_total');
            
        $interventionCount = Intervention::where('technicien_id', $technicienId)
            ->count();
            
        // Formatage du temps total (secondes en heures:minutes)
        $heures = floor($tempsTotal / 3600);
        $minutes = floor(($tempsTotal % 3600) / 60);
        $secondes = $tempsTotal % 60;
        
        return [
            'temps_total' => $tempsTotal,
            'temps_formate' => sprintf('%02dh %02dm %02ds', $heures, $minutes, $secondes),
            'nb_interventions' => $interventionCount
        ];
    }
    
    private function getTempsMoyenParModule($technicienId)
    {
        $interventions = Intervention::where('technicien_id', $technicienId)
            ->where('is_completed', true);
            
        $count = $interventions->count();
        $tempsTotal = $interventions->sum('temps_total');
        
        if ($count > 0) {
            $tempsMoyen = $tempsTotal / $count;
        } else {
            $tempsMoyen = 0;
        }
        
        // Formatage du temps moyen
        $heures = floor($tempsMoyen / 3600);
        $minutes = floor(($tempsMoyen % 3600) / 60);
        $secondes = floor($tempsMoyen % 60);
        
        return [
            'temps_moyen' => $tempsMoyen,
            'temps_formate' => sprintf('%02dh %02dm %02ds', $heures, $minutes, $secondes)
        ];
    }
    
    private function getModulesEnCours($technicienId = null)
    {
        $query = Module::with(['dalle.produit.chantier', 'technicien', 'interventions' => function ($q) {
            $q->where('is_completed', false);
        }])
        ->where('etat', 'en_cours');
        
        if ($technicienId) {
            $query->where('technicien_id', $technicienId);
        }
        
        return $query->get();
    }
    
    private function getInterventionsRecentes($technicienId)
    {
        return Intervention::with(['module.dalle.produit.chantier'])
            ->where('technicien_id', $technicienId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }
}