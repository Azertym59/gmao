<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Client;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuiviController extends Controller
{
    /**
     * Analyse les remarques pour trouver des problèmes spécifiques et générer des recommandations
     * 
     * @param string $remarques Le texte des remarques à analyser
     * @param array &$detailsProblemes Tableau de problèmes à mettre à jour
     * @return void
     */
    private function analyzeRemarquesForSpecificProblems($remarques, &$detailsProblemes)
    {
        // Motifs à rechercher dans les remarques
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
        
        // Si la clé "recommandations" n'existe pas dans le tableau, on la crée
        if (!isset($detailsProblemes['recommandations'])) {
            $detailsProblemes['recommandations'] = [];
        }
        
        foreach ($patterns as $key => $data) {
            foreach ($data['patterns'] as $pattern) {
                if (preg_match($pattern, $remarques)) {
                    if (!isset($detailsProblemes['recommandations'][$key])) {
                        $detailsProblemes['recommandations'][$key] = [
                            'count' => 0,
                            'recommendation' => $data['recommendation']
                        ];
                    }
                    $detailsProblemes['recommandations'][$key]['count']++;
                    break;
                }
            }
        }
    }
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
        $modulesEnCours = 0;
        $modulesDefaillants = 0;
        $modulesNonCommences = 0;
        $tempsTotal = 0;
        $interventionsEnCours = 0;
        $totalLEDsRemplacees = 0;
        $totalICsRemplaces = 0;
        $totalMasquesRemplaces = 0;
        $causes = [
            'usure_normale' => 0,
            'choc' => 0,
            'defaut_usine' => 0,
            'autre' => 0,
        ];
        
        // Analyse détaillée des problèmes les plus courants
        $detailsProblemes = [
            'perte_couleur' => [
                'rouge' => 0,
                'vert' => 0,
                'bleu' => 0
            ],
            'ic_defaillants' => [
                'rouge' => 0,
                'vert' => 0,
                'bleu' => 0,
                'controleur' => 0
            ],
            'problemes_alimentation' => 0,
            'pixels_morts' => 0,
            'lignes_defaillantes' => 0
        ];
        
        foreach ($chantier->produits as $produit) {
            foreach ($produit->dalles as $dalle) {
                $totalModules += $dalle->modules->count();
                $modulesTermines += $dalle->modules->where('etat', 'termine')->count();
                $modulesEnCours += $dalle->modules->where('etat', 'en_cours')->count();
                $modulesDefaillants += $dalle->modules->where('etat', 'defaillant')->count();
                $modulesNonCommences += $dalle->modules->where('etat', 'non_commence')->count();
                
                foreach ($dalle->modules as $module) {
                    foreach ($module->interventions as $intervention) {
                        $tempsTotal += $intervention->temps_total;
                        if (!$intervention->is_completed) {
                            $interventionsEnCours++;
                        }
                        
                        // Compter les composants remplacés
                        if ($intervention->reparation) {
                            $totalLEDsRemplacees += $intervention->reparation->nb_leds_remplacees;
                            $totalICsRemplaces += $intervention->reparation->nb_ic_remplaces;
                            $totalMasquesRemplaces += $intervention->reparation->nb_masques_remplaces;
                        }
                        
                        // Compter les causes
                        if ($intervention->diagnostic && $intervention->diagnostic->cause) {
                            $causes[$intervention->diagnostic->cause]++;
                            
                            // Analyse détaillée basée sur les diagnostics et remarques
                            if ($intervention->diagnostic->remarques) {
                                $remarques = strtolower($intervention->diagnostic->remarques);
                                
                                // Analyse des remarques pour recommandations spécifiques
                                $this->analyzeRemarquesForSpecificProblems($remarques, $detailsProblemes);
                                
                                // Perte de couleur
                                if (strpos($remarques, 'rouge') !== false || strpos($remarques, 'red') !== false) {
                                    $detailsProblemes['perte_couleur']['rouge']++;
                                }
                                if (strpos($remarques, 'vert') !== false || strpos($remarques, 'green') !== false) {
                                    $detailsProblemes['perte_couleur']['vert']++;
                                }
                                if (strpos($remarques, 'bleu') !== false || strpos($remarques, 'blue') !== false) {
                                    $detailsProblemes['perte_couleur']['bleu']++;
                                }
                                
                                // ICs défaillants
                                if ((strpos($remarques, 'ic') !== false || strpos($remarques, 'circuit intégré') !== false) && strpos($remarques, 'rouge') !== false) {
                                    $detailsProblemes['ic_defaillants']['rouge']++;
                                }
                                if ((strpos($remarques, 'ic') !== false || strpos($remarques, 'circuit intégré') !== false) && strpos($remarques, 'vert') !== false) {
                                    $detailsProblemes['ic_defaillants']['vert']++;
                                }
                                if ((strpos($remarques, 'ic') !== false || strpos($remarques, 'circuit intégré') !== false) && strpos($remarques, 'bleu') !== false) {
                                    $detailsProblemes['ic_defaillants']['bleu']++;
                                }
                                if ((strpos($remarques, 'ic') !== false || strpos($remarques, 'circuit') !== false) && (strpos($remarques, 'control') !== false || strpos($remarques, 'principal') !== false)) {
                                    $detailsProblemes['ic_defaillants']['controleur']++;
                                }
                                
                                // Autres problèmes courants
                                if (strpos($remarques, 'alim') !== false || strpos($remarques, 'tension') !== false || strpos($remarques, 'power') !== false) {
                                    $detailsProblemes['problemes_alimentation']++;
                                }
                                if (strpos($remarques, 'pixel') !== false && (strpos($remarques, 'mort') !== false || strpos($remarques, 'dead') !== false)) {
                                    $detailsProblemes['pixels_morts']++;
                                }
                                if (strpos($remarques, 'ligne') !== false || strpos($remarques, 'row') !== false || strpos($remarques, 'colonne') !== false || strpos($remarques, 'column') !== false) {
                                    $detailsProblemes['lignes_defaillantes']++;
                                }
                            }
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
            'modulesEnCours',
            'modulesDefaillants',
            'modulesNonCommences',
            'pourcentageTermines',
            'tempsFormate',
            'interventionsEnCours',
            'totalLEDsRemplacees',
            'totalICsRemplaces',
            'totalMasquesRemplaces',
            'causes',
            'detailsProblemes'
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
    
    /**
     * Affiche tous les chantiers d'un client
     */
    public function clientChantiers(Request $request)
    {
        // Vérifier si le client est connecté
        if (!$request->session()->has('client_id')) {
            return redirect()->route('client.login');
        }
        
        $clientId = $request->session()->get('client_id');
        
        $client = Client::with(['chantiers' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($clientId);
        
        return view('suivi.client_chantiers', compact('client'));
    }
}