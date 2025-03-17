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
    
    /**
     * Analyse les remarques d'une intervention pour générer des recommandations spécifiques
     * 
     * @param Intervention $intervention
     * @return array
     */
    private function analyzerInterventionForRecommendations($intervention)
    {
        $recommendations = [];
        
        // Motifs à rechercher dans les remarques pour générer des recommandations spécifiques
        $patterns = [
            'perte_couleur_rouge' => [
                'patterns' => ['/perte.*couleur rouge/i', '/perte.*rouge/i', '/led.*rouge.*morte/i', '/défaut.*rouge/i'],
                'recommendation' => 'La perte de couleur rouge est souvent liée à une surchauffe des LED rouges, qui ont une durée de vie plus courte que les autres LED. Un suivi thermique régulier est recommandé.'
            ],
            'perte_couleur_verte' => [
                'patterns' => ['/perte.*couleur verte/i', '/perte.*vert/i', '/led.*verte.*morte/i', '/défaut.*vert/i'],
                'recommendation' => 'La perte de couleur verte peut indiquer un problème d\'alimentation électrique ou de tension instable. Un contrôle du système d\'alimentation est conseillé.'
            ],
            'ic_remplacement' => [
                'patterns' => ['/remplacement.*ic/i', '/ic.*remplacé/i', '/changement.*ic/i'],
                'recommendation' => 'Le remplacement fréquent des circuits intégrés suggère une possible surtension ou une instabilité électrique. Une vérification de l\'installation électrique pourrait être nécessaire.'
            ],
            'ligne_morte' => [
                'patterns' => ['/ligne.*morte/i', '/ligne.*éteinte/i', '/rangée.*morte/i'],
                'recommendation' => 'La présence de lignes mortes indique souvent un problème de connectique ou de circuit de commande. Une inspection périodique des connexions est recommandée pour éviter la propagation du problème.'
            ],
            'humidite' => [
                'patterns' => ['/humid/i', '/infiltration/i', '/eau/i', '/humidité/i'],
                'recommendation' => 'Des signes d\'humidité ont été détectés. Cela peut affecter sérieusement la durée de vie des composants. Une vérification de l\'étanchéité et une protection supplémentaire contre l\'humidité sont vivement conseillées.'
            ]
        ];
        
        $remarques = '';
        
        if ($intervention->diagnostic && $intervention->diagnostic->remarques) {
            $remarques .= $intervention->diagnostic->remarques . ' ';
        }
        
        if ($intervention->reparation && $intervention->reparation->remarques) {
            $remarques .= $intervention->reparation->remarques . ' ';
        }
        
        if ($intervention->reparation && $intervention->reparation->actions_effectuees) {
            $remarques .= $intervention->reparation->actions_effectuees . ' ';
        }
        
        foreach ($patterns as $key => $data) {
            foreach ($data['patterns'] as $pattern) {
                if (preg_match($pattern, $remarques)) {
                    $recommendations[$key] = $data['recommendation'];
                    break;
                }
            }
        }
        
        return $recommendations;
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
        
        // Collecte des recommandations spécifiques
        $specificRecommendations = [];
        
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
                            
                            // Analyse des remarques pour des recommandations spécifiques
                            $recommendations = $this->analyzerInterventionForRecommendations($intervention);
                            foreach ($recommendations as $key => $recommendation) {
                                if (!isset($specificRecommendations[$key])) {
                                    $specificRecommendations[$key] = [
                                        'count' => 0,
                                        'recommendation' => $recommendation
                                    ];
                                }
                                $specificRecommendations[$key]['count']++;
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
            'specificRecommendations',
            'dataEtatModules',
            'dataCauses',
            'dataComposants',
            'dataComposantsDefectueux'
        ));
        
        // Définir le format portrait qui est plus adapté pour les listes et tableaux
        $pdf->setPaper('a4', 'portrait');
        
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
        
        // Analyse des remarques pour des recommandations spécifiques
        $specificRecommendations = $this->analyzerInterventionForRecommendations($intervention);
        
        $pdf = PDF::loadView('rapports.intervention', compact('intervention', 'tempsFormate', 'specificRecommendations'));
        
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