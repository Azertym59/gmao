<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Client;
use App\Models\ProduitCatalogue; 
use App\Models\Produit;
use App\Models\Dalle;
use App\Models\Module;
use App\Models\User;
use App\Models\Intervention;
use App\Models\Diagnostic;
use App\Models\Reparation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ChantierController extends Controller
{
    /**
     * Afficher la liste des chantiers
     */
    public function index(Request $request)
    {
        $query = Chantier::with(['client', 'produits.dalles.modules']);
        
        // Filtrage par état
        if ($request->has('filter_etat')) {
            $etat = $request->input('filter_etat');
            $query->where('etat', $etat);
        }
        
        // Filtrage des chantiers urgents (date butoir dans moins de 7 jours)
        if ($request->has('filter_urgent') && $request->input('filter_urgent') == 'true') {
            $query->where('etat', '!=', 'termine')
                 ->whereDate('date_butoir', '<=', now()->addDays(7));
        }
        
        $chantiers = $query->get();
        
        // Calculer le pourcentage d'avancement pour chaque chantier
        foreach ($chantiers as $chantier) {
            $chantier->pourcentage_avancement = $chantier->getCompletionPercentage();
        }
        
        // Trier les chantiers par urgence (date butoir la plus proche en premier)
        // Seulement pour les chantiers non terminés
        $chantiersTermines = $chantiers->where('etat', 'termine');
        $chantiersNonTermines = $chantiers->where('etat', '!=', 'termine');
        
        $chantiersNonTermines = $chantiersNonTermines->sortBy(function ($chantier) {
            return $chantier->date_butoir;
        });
        
        // Fusionner les collections triées
        $chantiers = $chantiersNonTermines->merge($chantiersTermines);
        
        // Passer les filtres à la vue pour marquer les onglets actifs
        $activeFilters = [
            'etat' => $request->input('filter_etat'),
            'urgent' => $request->input('filter_urgent') == 'true'
        ];
        
        return view('chantiers.index', compact('chantiers', 'activeFilters'));
    }

    /**
     * Afficher le formulaire de création (ancienne méthode, maintenant redirige vers choix du type)
     */
    public function create(Request $request)
    {
        return redirect()->route('nouveau.projet');
    }
    
    /**
     * Afficher la page de choix du type de projet (maintenance ou vente)
     */
    public function typeChoix()
    {
        // Ajout d'un log pour vérifier si cette méthode est appelée
        Log::info('Méthode typeChoix appelée');
        
        // S'assurer que la vue existe et est accessible
        if (view()->exists('chantiers.type_choix')) {
            return view('chantiers.type_choix');
        } else {
            Log::error('Vue type_choix.blade.php introuvable');
            return redirect()->route('chantiers.index')
                ->with('error', 'La page de choix du type de projet est indisponible. Veuillez contacter l\'administrateur.');
        }
    }
    
    /**
     * Créer un projet de maintenance
     */
    public function createMaintenanceProject()
    {
        // Réinitialiser toutes les données de session pour éviter les problèmes
        session()->forget(['chantier_data_step1', 'chantier_data_step2', 'chantier_data_step3', 'chantier_data_step4']);
        
        // Stocker le type dans la session
        session(['projet_type' => 'maintenance']);
        
        // Récupérer tous les clients pour la liste déroulante
        $clients = Client::all();
        $type = 'maintenance';
        
        return view('chantiers.create_step1', compact('clients', 'type'));
    }
    
    /**
     * Créer un projet de vente
     */
    public function createVenteProject()
    {
        // Réinitialiser toutes les données de session pour éviter les problèmes
        session()->forget(['chantier_data_step1', 'chantier_data_step2', 'chantier_data_step3', 'chantier_data_step4']);
        
        // Stocker le type dans la session
        session(['projet_type' => 'vente']);
        
        // Récupérer tous les clients pour la liste déroulante
        $clients = Client::all();
        $type = 'vente';
        
        return view('chantiers.create_step1', compact('clients', 'type'));
    }

    /**
     * Stocker un nouveau client via AJAX lors de la création d'un chantier
     */
    public function storeClientAjax(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'societe' => 'nullable|string|max:255',
            'adresse' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'telephone' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        // Formatage des données pour l'homogénéité
        $formatted = [
            'nom' => Str::upper($validated['nom']),
            'prenom' => Str::ucfirst(Str::lower($validated['prenom'])),
            'societe' => !empty($validated['societe']) ? Str::upper($validated['societe']) : $validated['societe'],
            'adresse' => $validated['adresse'],
            'code_postal' => str_replace(' ', '', $validated['code_postal']),
            'ville' => Str::upper($validated['ville']),
            'pays' => $validated['pays'],
            'email' => Str::lower($validated['email']),
            'telephone' => $validated['telephone'],
            'notes' => $validated['notes'],
        ];

        $client = Client::create($formatted);
        
        // Pour l'affichage dans le select
        $client->nom_complet = $client->nom . ' ' . $client->prenom;
        $client->display_text = $client->nom_complet;
        if (!empty($client->societe)) {
            $client->display_text .= ' (' . $client->societe . ')';
        }

        return response()->json([
            'success' => true,
            'client' => $client,
            'message' => 'Client créé avec succès'
        ]);
    }
    
    /**
     * Afficher le formulaire de création - Étape 1
     */
    public function createStep1(Request $request)
    {
        // Récupérer tous les clients
        $clients = Client::all();
        
        // Déterminer le type de projet depuis la session
        $type = session('projet_type', 'maintenance');
        
        return view('chantiers.create_step1', compact('clients', 'type'));
    }
    
    /**
     * Traiter les données pour l'étape 1
     */
    public function storeStep1(Request $request)
    {
        // Définir des règles de validation de base
        $rules = [
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'date_reception' => 'required|date',
            'etat' => 'required|in:non_commence,en_cours,termine',
            'is_client_achat' => 'nullable|boolean',
            'is_under_warranty' => 'nullable|boolean',
            'warranty_end_date' => 'nullable|date|after_or_equal:date_reception',
            'warranty_type' => 'nullable|string',
        ];
        
        // Validation conditionnelle pour date_butoir selon le type de projet
        if ($request->input('is_client_achat') == 0) { // SAV / Réparation
            $rules['date_butoir'] = 'required|date|after_or_equal:date_reception';
        } else { // Vente / Achat client
            $rules['date_butoir'] = 'nullable|date|after_or_equal:date_reception';
        }
        
        $validated = $request->validate($rules);
        
        // Créer un chantier avec les données de base
        $client = Client::find($validated['client_id']);
        
        // Générer le nom du chantier automatiquement
        $societe = $client->societe ? $client->societe : $client->nom_complet;
        $date = date('d/m/Y');
        $nom = "Réparation écran - {$societe} - {$date}";
        if ($request->input('is_client_achat') == 1) {
            $nom = "Commande écran - {$societe} - {$date}";
        }
        
        try {
            // Utiliser une transaction pour éviter les problèmes de concurrence
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            // Générer une référence unique avec débogage
            \Illuminate\Support\Facades\Log::info('Génération de référence pour nouveau chantier', [
                'client' => $societe,
                'nom' => $nom
            ]);
            
            $reference = Chantier::genererReference();
            
            \Illuminate\Support\Facades\Log::info('Référence générée', [
                'reference' => $reference
            ]);
            
            // Créer le chantier
            $chantier = Chantier::create([
                'nom' => $nom,
                'reference' => $reference,
                'client_id' => $validated['client_id'],
                'description' => $validated['description'],
                'date_reception' => $validated['date_reception'],
                'date_butoir' => $validated['date_butoir'] ?? null,
                'etat' => $validated['etat'],
                'is_client_achat' => $validated['is_client_achat'] ?? false,
                'is_under_warranty' => $validated['is_under_warranty'] ?? false,
                'warranty_end_date' => $validated['warranty_end_date'] ?? null,
                'warranty_type' => $validated['warranty_type'] ?? null,
            ]);
            
            \Illuminate\Support\Facades\DB::commit();
            
            // Rediriger vers l'étape 2
            return redirect()->route('chantiers.create.step2', $chantier);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Erreur lors de la création du chantier', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Retourner à la page précédente avec l'erreur
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du chantier: ' . $e->getMessage());
        }
    }
    
    /**
     * Afficher le formulaire de création - Étape 2 : Sélection du produit
     */
    public function createStep2(Chantier $chantier)
    {
        // Charger le chantier avec son client
        $chantier->load('client');
        
        // Récupérer le client directement
        $client = $chantier->client;
        
        // Récupérer les produits du catalogue pour la sélection
        $produitsCatalogue = ProduitCatalogue::all();
        
        // Récupérer les datasheets de LED pour le formulaire
        $ledDatasheets = \App\Models\LedDatasheet::all();
        
        // Utiliser le template create_step2_product qui est le bon template pour l'étape 2
        return view('chantiers.create_step2_product', compact('chantier', 'produitsCatalogue', 'client', 'ledDatasheets'));
    }
    
    /**
     * Traiter les données pour l'étape 2
     */
    public function storeStep2(Request $request, Chantier $chantier)
    {
        // Ajouter un log pour voir les données reçues
        Log::info('Données reçues dans storeStep2', [
            'request_data' => $request->all(),
            'chantier_id' => $chantier->id
        ]);
        
        // Validation des données adaptée au formulaire
        $validated = $request->validate([
            'from_catalogue' => 'required|in:0,1',
            'catalogue_id' => 'required_if:from_catalogue,1|nullable|exists:produits_catalogue,id',
            'marque' => 'required_if:from_catalogue,0|nullable|string|max:255',
            'modele' => 'required_if:from_catalogue,0|nullable|string|max:255',
            'largeur_dalle' => 'nullable|numeric|min:1',
            'hauteur_dalle' => 'nullable|numeric|min:1',
            'disposition_modules' => 'nullable|string',
            'add_to_catalogue' => 'nullable|boolean',
        ]);
        
        try {
            // Enregistrer les données du produit dans le chantier
            if ($validated['from_catalogue'] == '1') {
                // Récupérer le produit catalogue
                $produitCatalogue = ProduitCatalogue::find($validated['catalogue_id']);
                
                if (!$produitCatalogue) {
                    throw new \Exception('Produit du catalogue introuvable');
                }
                
                $produit = Produit::create([
                    'nom' => $produitCatalogue->marque . ' ' . $produitCatalogue->modele,
                    'chantier_id' => $chantier->id,
                    'description' => 'Produit principal du chantier',
                    'marque' => $produitCatalogue->marque,
                    'modele' => $produitCatalogue->modele,
                    'pitch' => $produitCatalogue->pitch,
                    'produit_catalogue_id' => $produitCatalogue->id,
                    'utilisation' => $produitCatalogue->utilisation,
                    'electronique' => $produitCatalogue->electronique,
                    'bain_couleur' => $produitCatalogue->bain_couleur,
                    'disposition_modules' => $produitCatalogue->disposition_modules,
                    'etat' => $chantier->etat,
                ]);
                
            } else {
                // Créer un nouveau produit
                
                // Traiter le cas de la disposition personnalisée
                if ($request->input('disposition_modules') == 'personnalise' && $request->has('modules_largeur') && $request->has('modules_hauteur')) {
                    // Construire une disposition au format "largeur x hauteur"
                    $validated['disposition_modules'] = $request->input('modules_largeur') . 'x' . $request->input('modules_hauteur');
                }
                
                $produit = Produit::create([
                    'nom' => $validated['marque'] . ' ' . $validated['modele'],
                    'chantier_id' => $chantier->id,
                    'description' => 'Produit principal du chantier',
                    'marque' => $validated['marque'],
                    'modele' => $validated['modele'],
                    'pitch' => $request->input('pitch'),
                    'largeur_dalle' => $validated['largeur_dalle'] ?? null,
                    'hauteur_dalle' => $validated['hauteur_dalle'] ?? null,
                    'utilisation' => $request->input('utilisation'),
                    'electronique' => $request->input('electronique'),
                    'bain_couleur' => $request->input('bain_couleur'),
                    'disposition_modules' => $validated['disposition_modules'] ?? '2x2',
                    'etat' => $chantier->etat,
                ]);
                
                // Ajouter le produit au catalogue si demandé
                if ($request->has('add_to_catalogue')) {
                    // Créer le produit catalogue avec le bain de couleur
                    $bainCouleur = $request->input('bain_couleur');
                    
                    $produitCatalogue = new ProduitCatalogue([
                        'marque' => $validated['marque'],
                        'modele' => $validated['modele'],
                        'pitch' => $request->input('pitch'),
                        'utilisation' => $request->input('utilisation'),
                        'electronique' => $request->input('electronique'),
                        'electronique_detail' => $request->input('electronique_detail'),
                        'disposition_modules' => $validated['disposition_modules'] ?? '2x2',
                        'description' => 'Produit ajouté depuis la création d\'un chantier',
                    ]);
                    
                    // Utiliser l'accesseur pour définir le bain_couleur
                    if (!empty($bainCouleur)) {
                        $produitCatalogue->bain_couleur = $bainCouleur;
                    }
                    
                    $produitCatalogue->save();
                    
                    // Mettre à jour la référence du produit vers le catalogue
                    $produit->produit_catalogue_id = $produitCatalogue->id;
                    $produit->save();
                    
                    Log::info('Produit ajouté au catalogue', [
                        'produit_catalogue_id' => $produitCatalogue->id,
                        'produit_id' => $produit->id
                    ]);
                }
            }
            
            Log::info('Produit créé avec succès', [
                'produit_id' => $produit->id,
                'chantier_id' => $chantier->id
            ]);
            
            // Rediriger vers l'étape 3
            return redirect()->route('chantiers.create.step3', $chantier);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du produit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du produit: ' . $e->getMessage());
        }
    }
    
    /**
     * Afficher le formulaire de création - Étape 3
     */
    public function createStep3(Chantier $chantier)
    {
        // Charger le chantier avec ses produits et son client
        $chantier->load(['produits', 'client']);
        
        // Vérifier si des produits existent
        if ($chantier->produits->isEmpty()) {
            return redirect()->route('chantiers.create.step2', $chantier)
                ->with('error', 'Veuillez d\'abord créer au moins un produit pour ce chantier.');
        }
        
        // Récupérer le produit principal et le client
        $produit = $chantier->produits->first();
        $client = $chantier->client;
        
        // Déterminer si le produit vient du catalogue
        $from_catalogue = !empty($produit->produit_catalogue_id);
        $produitRef = null;
        
        if ($from_catalogue) {
            $produitRef = ProduitCatalogue::find($produit->produit_catalogue_id);
        }
        
        // Créer un tableau de données pour le produit avec toutes les informations nécessaires
        $produitData = [
            'selection_type' => $from_catalogue ? 'existant' : 'personnalise',
            'marque' => $produit->marque,
            'modele' => $produit->modele,
            'pitch' => $produit->pitch,
            'utilisation' => $produit->utilisation ?? 'indoor',
            'electronique' => $produit->electronique ?? 'nova',
            'electronique_detail' => $produit->electronique_detail ?? '',
            'disposition_modules' => $produit->disposition_modules ?? ($from_catalogue && $produitRef ? $produitRef->disposition_modules : '2x2'),
            'largeur_dalle' => $produit->largeur_dalle,
            'hauteur_dalle' => $produit->hauteur_dalle,
        ];
        
        // Log des données produit récupérées pour débogage
        Log::info('Données produit récupérées pour l\'étape 3', [
            'produit_id' => $produit->id,
            'disposition_modules' => $produitData['disposition_modules'],
            'from_catalogue' => $from_catalogue,
            'largeur_dalle' => $produitData['largeur_dalle'],
            'hauteur_dalle' => $produitData['hauteur_dalle']
        ]);
        
        // Créer des types de dalles par défaut
        // Si l'utilisateur a déjà choisi une disposition, utiliser celle-ci comme format par défaut
        $defaultDisposition = $produitData['disposition_modules'] ?? '2x2';
        
        // Analyser la disposition pour extraire les colonnes et lignes
        $parts = explode('x', $defaultDisposition);
        $defaultColonnes = count($parts) == 2 ? (int)$parts[0] : 2;
        $defaultLignes = count($parts) == 2 ? (int)$parts[1] : 2;
        
        Log::info('Disposition par défaut pour dalles', [
            'disposition' => $defaultDisposition,
            'colonnes' => $defaultColonnes,
            'lignes' => $defaultLignes
        ]);
        
        $dalleTypes = [
            [
                'id' => 1,
                'nom' => 'Standard 500x500',
                'largeur' => 500,
                'hauteur' => 500,
                'disposition' => $defaultDisposition // Utiliser la disposition choisie à l'étape 2
            ],
            [
                'id' => 2,
                'nom' => 'Format allongé 500x1000',
                'largeur' => 500,
                'hauteur' => 1000,
                'disposition' => '2x4'
            ],
            [
                'id' => 3,
                'nom' => 'Format large 1000x500',
                'largeur' => 1000,
                'hauteur' => 500,
                'disposition' => '4x2'
            ]
        ];
        
        return view('chantiers.create_step3', compact('chantier', 'produit', 'client', 'produitRef', 'produitData', 'dalleTypes'));
    }
    
    /**
     * Traiter les données pour l'étape 3
     */
    public function storeStep3(Request $request, Chantier $chantier)
    {
        // Ajouter des logs pour comprendre les données reçues
        Log::info('Données reçues dans storeStep3', [
            'request_data' => $request->all(),
            'chantier_id' => $chantier->id
        ]);
        
        try {
            // Validation des données
            $validated = $request->validate([
                'mode_configuration' => 'required|in:flightcase,individuel',
                'nb_flightcases' => 'required_if:mode_configuration,flightcase|integer|min:1',
                'nb_dalles_par_flightcase' => 'required_if:mode_configuration,flightcase|integer|min:1',
                'nb_modules_par_dalle' => 'required_if:mode_configuration,flightcase|integer|min:1',
                'flights' => 'nullable|array',
                'fc_partiel' => 'nullable|array',
                'fc_nb_dalles' => 'nullable|array',
                'nb_modules_total' => 'required_if:mode_configuration,individuel|integer|min:1',
                'custom_dalle_types' => 'nullable|array',
            ]);
            
            // Stocker les données importantes temporairement (nous les mettrons à jour
            // après la création des modules pour refléter le nombre réel créé)
            $nb_modules_par_dalle = $validated['nb_modules_par_dalle'] ?? 0;
            $nb_modules_total_theorique = 0;
            
            // Calculer le nombre théorique de modules selon le mode choisi
            // (nous vérifierons et mettrons à jour ce nombre après création réelle)
            if ($validated['mode_configuration'] === 'flightcase') {
                $nbFlightcases = $validated['nb_flightcases'] ?? 0;
                $nbDallesParFC = $validated['nb_dalles_par_flightcase'] ?? 0;
                $nbModulesDalles = $nb_modules_par_dalle;
                
                // Calculer le total théorique
                $nb_modules_total_theorique = $nbFlightcases * $nbDallesParFC * $nbModulesDalles;
            } else {
                $nb_modules_total_theorique = $validated['nb_modules_total'] ?? 0;
            }
            
            // Juste une information temporaire pour la création des modules
            // Nous stockerons les données réelles après la création
            $sessionDataTemp = [
                'mode_configuration' => $validated['mode_configuration'],
                'nb_flightcases' => $validated['nb_flightcases'] ?? 0,
                'nb_dalles_par_flightcase' => $validated['nb_dalles_par_flightcase'] ?? 0,
                'nb_modules_par_dalle' => $nb_modules_par_dalle,
                'nb_modules_total_theorique' => $nb_modules_total_theorique,
            ];
            
            Log::info('Calcul théorique du nombre de modules', [
                'temp_data' => $sessionDataTemp
            ]);
            
            // Récupérer le produit principal du chantier
            $produit = $chantier->produits()->first();
            
            if (!$produit) {
                throw new \Exception('Aucun produit trouvé pour ce chantier');
            }
            
            // Vérifier si des dalles et modules existent déjà pour ce chantier
            // Si c'est le cas, les supprimer pour éviter la duplication
            // lorsque l'utilisateur revient en arrière dans le processus
            $existingDalles = $produit->dalles()->count();
            if ($existingDalles > 0) {
                Log::info('Dalles existantes détectées, suppression avant recréation', [
                    'produit_id' => $produit->id,
                    'dalles_count' => $existingDalles
                ]);
                
                try {
                    // Supprimer en utilisant une transaction pour garantir la cohérence
                    \Illuminate\Support\Facades\DB::beginTransaction();
                    
                    // Supprimer d'abord les interventions liées aux modules
                    $dalleIds = $produit->dalles()->pluck('id')->toArray();
                    $moduleIds = Module::whereIn('dalle_id', $dalleIds)->pluck('id')->toArray();
                    
                    // Supprimer les diagnostics et réparations liés aux interventions
                    $interventionIds = \App\Models\Intervention::whereIn('module_id', $moduleIds)->pluck('id')->toArray();
                    \App\Models\Diagnostic::whereIn('intervention_id', $interventionIds)->delete();
                    \App\Models\Reparation::whereIn('intervention_id', $interventionIds)->delete();
                    
                    // Supprimer les interventions
                    \App\Models\Intervention::whereIn('module_id', $moduleIds)->delete();
                    
                    // Maintenant supprimer les modules
                    Module::whereIn('dalle_id', $dalleIds)->delete();
                    
                    // Finalement supprimer les dalles
                    $produit->dalles()->delete();
                    
                    \Illuminate\Support\Facades\DB::commit();
                    Log::info('Dalles et modules supprimés avec succès');
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\DB::rollBack();
                    Log::error('Erreur lors de la suppression des dalles et modules', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continuer malgré l'erreur, mais noter le problème
                }
            }
            
            // Traitement selon le mode (FlightCase ou Individuel)
            if ($request->input('mode_configuration') === 'flightcase') {
                // Mode FlightCase
                $nbFlightcases = $request->input('nb_flightcases', 1);
                $nbDallesParFlightcase = $request->input('nb_dalles_par_flightcase', 8);
                $nbModulesParDalle = $request->input('nb_modules_par_dalle', 4);
                $usesMultipleSizes = $request->has('multiple_sizes_mode');
                
                // Procéder différemment selon si on utilise des tailles multiples ou non
                if ($usesMultipleSizes) {
                    // Mode tailles multiples (gestion spéciale des configurations)
                    Log::info('Mode tailles multiples activé');
                    $config1Data = $request->input('config1', []);
                    $config2Data = $request->input('config2', []);
                    
                    // Configuration 1 (500x500)
                    $config1FlightCases = $config1Data['nb_flightcases'] ?? 0;
                    $config1DallesPerFC = $config1Data['nb_dalles'] ?? 0;
                    $config1ModulesConfig = $config1Data['modules_config'] ?? '2x2';
                    
                    // Configuration 2 (500x1000)
                    $config2FlightCases = $config2Data['nb_flightcases'] ?? 0;
                    $config2DallesPerFC = $config2Data['nb_dalles'] ?? 0;
                    $config2ModulesConfig = $config2Data['modules_config'] ?? '2x4';
                    
                    // Créer les dalles pour chaque configuration
                    $this->createDallesForMultipleSizes(
                        $chantier, 
                        $produit, 
                        $config1FlightCases, 
                        $config1DallesPerFC, 
                        $config1ModulesConfig,
                        1
                    );
                    
                    $this->createDallesForMultipleSizes(
                        $chantier, 
                        $produit, 
                        $config2FlightCases, 
                        $config2DallesPerFC, 
                        $config2ModulesConfig,
                        2
                    );
                } else {
                    // Mode standard (un seul type de FlightCase)
                    Log::info('Mode standard', [
                        'nbFlightcases' => $nbFlightcases,
                        'nbDallesParFlightcase' => $nbDallesParFlightcase, 
                    ]);
                    
                    // Pour chaque FlightCase
                    for ($f = 1; $f <= $nbFlightcases; $f++) {
                        // Vérifier si ce FlightCase est partiel
                        $isPartial = $request->has('fc_partiel.' . $f);
                        $effectiveNbDalles = $isPartial 
                            ? (int)$request->input('fc_nb_dalles.' . $f, $nbDallesParFlightcase)
                            : $nbDallesParFlightcase;
                            
                        // Récupérer le type de dalle pour ce FlightCase
                        $dalleTypeId = $request->input('flights.' . $f . '.dalle_type_id', 1);
                        $modulesConfig = $request->input('flights.' . $f . '.modules_config', '2x2');
                        
                        // Créer les dalles pour ce FlightCase
                        for ($d = 1; $d <= $effectiveNbDalles; $d++) {
                            // Déterminer les dimensions selon le type
                            $largeur = 500;
                            $hauteur = 500;
                            $nbColonnes = 2;
                            $nbLignes = 2;
                            
                            // Vérifier si c'est un type personnalisé (commence par "custom_")
                            if (strpos($dalleTypeId, 'custom_') === 0) {
                                // C'est un type personnalisé créé dynamiquement
                                $customTypeData = $request->input('custom_dalle_types.' . $dalleTypeId);
                                
                                if ($customTypeData) {
                                    $largeur = $customTypeData['largeur'] ?? 1000;
                                    $hauteur = $customTypeData['hauteur'] ?? 1000;
                                    $modulesConfig = $customTypeData['disposition'] ?? '4x4';
                                    $nbColonnes = $customTypeData['colonnes'] ?? 4;
                                    $nbLignes = $customTypeData['lignes'] ?? 4;
                                    
                                    Log::info('Utilisation d\'un type de dalle personnalisé', [
                                        'type_id' => $dalleTypeId,
                                        'largeur' => $largeur,
                                        'hauteur' => $hauteur,
                                        'disposition' => $modulesConfig
                                    ]);
                                }
                            } else if ($dalleTypeId === 'custom') {
                                // Utiliser les dimensions personnalisées du produit
                                $largeur = $produit->largeur_dalle ?? 500;
                                $hauteur = $produit->hauteur_dalle ?? 500;
                                
                                Log::info('Utilisation des dimensions personnalisées pour la dalle', [
                                    'largeur' => $largeur,
                                    'hauteur' => $hauteur
                                ]);
                            } else if ($dalleTypeId == 2) {
                                $largeur = 500;
                                $hauteur = 1000;
                            } else if ($dalleTypeId == 3) {
                                $largeur = 1000;
                                $hauteur = 500;
                            }
                            
                            // IMPORTANT: Toujours utiliser la configuration des modules du produit
                            // Cette configuration est celle choisie par l'utilisateur à l'étape 2
                            if ($produit && $produit->disposition_modules) {
                                // Vérifier si la disposition est au format valide AxB
                                if (preg_match('/^\d+x\d+$/', $produit->disposition_modules)) {
                                    $modulesConfig = $produit->disposition_modules;
                                    Log::info('Utilisation de la disposition des modules du produit (choix utilisateur)', [
                                        'produit_id' => $produit->id,
                                        'disposition' => $modulesConfig
                                    ]);
                                } else {
                                    // Si format invalide, utiliser 2x2 par défaut
                                    $modulesConfig = '2x2';
                                    Log::warning('Disposition des modules du produit invalide, utilisation de 2x2 par défaut', [
                                        'produit_id' => $produit->id,
                                        'disposition_invalide' => $produit->disposition_modules
                                    ]);
                                }
                            } else {
                                // Si aucune disposition définie, utiliser 2x2 par défaut
                                $modulesConfig = '2x2';
                                Log::warning('Aucune disposition des modules définie pour le produit, utilisation de 2x2 par défaut', [
                                    'produit_id' => $produit ? $produit->id : 'null'
                                ]);
                            }
                            
                            // Log de la configuration qui sera utilisée pour la création des modules
                            Log::info('Configuration des modules qui sera utilisée', [
                                'largeur_dalle' => $largeur,
                                'hauteur_dalle' => $hauteur,
                                'disposition_modules' => $modulesConfig
                            ]);
                            // Calculer le nombre de modules en fonction de la configuration
                            $modulesConfigParts = explode('x', $modulesConfig);
                            $nbModules = 4; // Par défaut
                            if (count($modulesConfigParts) == 2) {
                                $nbModules = (int)$modulesConfigParts[0] * (int)$modulesConfigParts[1];
                            }
                            
                            // Création de la dalle avec une référence structurée incluant le numéro de flightcase
                            $reference_dalle = 'FC' . $f . '-D' . $d; // Format: FC1-D1, FC1-D2, etc.
                            $dalle = Dalle::create([
                                'largeur' => $largeur,
                                'hauteur' => $hauteur,
                                'produit_id' => $produit->id,
                                'reference_dalle' => $reference_dalle, // Référence structurée pour organisation
                                'dalle_type_id' => is_numeric($dalleTypeId) ? $dalleTypeId : null, // Seulement les IDs numériques sont valides pour dalle_type_id
                                'disposition_modules' => $modulesConfig,
                                'flightcase_number' => $f,
                                'dalle_number' => $d,
                                'nb_modules' => $nbModules,
                                'alimentation' => '5V', // Valeur par défaut
                                'nb_colonnes' => (int)$modulesConfigParts[0], // Ajouter les dimensions
                                'nb_lignes' => (int)$modulesConfigParts[1]
                            ]);
                            // S'assurer que la disposition des modules est enregistrée correctement sur la dalle
                            $dalle->disposition_modules = $modulesConfig;
                            $dalle->save();
                            
                            // Créer les modules pour cette dalle
                            $this->createModulesForDalle($dalle, $modulesConfig);
                            
                            // Log pour vérifier exactement ce qui a été créé
                            Log::info('Dalle créée avec les paramètres finaux', [
                                'dalle_id' => $dalle->id,
                                'largeur' => $dalle->largeur,
                                'hauteur' => $dalle->hauteur,
                                'disposition' => $dalle->disposition_modules,
                                'nb_colonnes' => $dalle->nb_colonnes,
                                'nb_lignes' => $dalle->nb_lignes,
                                'nb_modules' => $dalle->nb_modules
                            ]);
                            // S'assurer que la disposition des modules est enregistrée correctement sur la dalle
                            $dalle->disposition_modules = $modulesConfig;
                            $dalle->save();
                            
                            // Créer les modules pour cette dalle
                            $this->createModulesForDalle($dalle, $modulesConfig);
                        }
                    }
                }
            } else {
                // Mode Individuel: créer un nombre spécifique de modules individuels
                $nbModulesTotal = $request->input('nb_modules_total', 0);
                
                // Créer une seule dalle pour tous les modules
                $dalle = Dalle::create([
                    'largeur' => 500,
                    'hauteur' => 500,
                    'produit_id' => $produit->id,
                    'reference_dalle' => 'INDIVIDUEL', // Référence spéciale pour les modules individuels
                    'disposition_modules' => '1x' . $nbModulesTotal, // Indiquer que c'est une dalle virtuelle
                    'is_virtual' => true,
                    'nb_modules' => $nbModulesTotal,
                    'alimentation' => '5V', // Valeur par défaut
                    'nb_colonnes' => 1,
                    'nb_lignes' => $nbModulesTotal
                ]);
                
                Log::info('Dalle virtuelle créée pour modules individuels', [
                    'dalle_id' => $dalle->id,
                    'nb_modules' => $nbModulesTotal
                ]);
                
                // Créer les modules pour cette dalle
                $this->createModulesForDalle($dalle, '1x' . $nbModulesTotal);
            }
            
            // IMPORTANT: À ce stade, tous les modules ont été créés
            // Nous devons calculer les nombres RÉELS plutôt que théoriques
            
            // Recharger le produit avec ses dalles et modules
            $produit->load('dalles.modules');
            
            // Calculer le nombre réel de modules et de dalles
            $totalDalles = $produit->dalles->count();
            $totalModules = 0;
            $modulesParDalle = [];
            
            foreach ($produit->dalles as $dalle) {
                $modulesCount = $dalle->modules->count();
                $totalModules += $modulesCount;
                $modulesParDalle[] = $modulesCount;
            }
            
            // Calculer le nombre moyen de modules par dalle (arrondi)
            $avgModulesParDalle = 0;
            if ($totalDalles > 0) {
                $avgModulesParDalle = round($totalModules / $totalDalles);
            }
            
            // Récupérer la disposition des modules depuis les données produit
            $dispositionModules = $produit->disposition_modules;
            
            // Maintenant, stocker les valeurs RÉELLES dans la session
            $sessionData = [
                'mode_configuration' => $validated['mode_configuration'],
                'nb_flightcases' => $validated['nb_flightcases'] ?? 0,
                'nb_dalles_par_flightcase' => $validated['nb_dalles_par_flightcase'] ?? 0,
                'nb_modules_par_dalle' => $avgModulesParDalle,
                'nb_modules_total' => $totalModules,
                'disposition_modules' => $dispositionModules, // Ajout explicite de la disposition
                // Ajouter des infos supplémentaires pour le débogage
                'total_dalles' => $totalDalles,
                'modules_par_dalle_details' => $modulesParDalle,
                'theorique' => $nb_modules_total_theorique,
            ];
            
            Log::info('Nombre RÉEL de modules calculé et stocké en session', [
                'session_data' => $sessionData,
                'difference' => $totalModules - $nb_modules_total_theorique
            ]);
            
            session(['chantier_data_step3' => $sessionData]);
            
            // Redirection directe vers l'étape 4 (finale)
            return redirect()->route('chantiers.create.step4', $chantier);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans storeStep3', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création des dalles: ' . $e->getMessage());
        }
    }
    
    /**
     * Méthode pour créer des modules pour une dalle
     * Version simplifiée qui respecte strictement la configuration spécifiée
     */
    private function createModulesForDalle(Dalle $dalle, string $modulesConfig)
    {
        // Utiliser en priorité la configuration passée en paramètre
        $configToUse = $modulesConfig;
        
        // Si la configuration n'est pas valide, on essaie d'utiliser celle de la dalle
        $dimensions = explode('x', $configToUse);
        if (count($dimensions) != 2) {
            // Essayer d'utiliser la configuration stockée sur la dalle
            if (!empty($dalle->disposition_modules) && strpos($dalle->disposition_modules, 'x') !== false) {
                $configToUse = $dalle->disposition_modules;
                $dimensions = explode('x', $configToUse);
            } else {
                // Si toujours pas valide, utiliser 2x2 par défaut
                $configToUse = '2x2';
                $dimensions = [2, 2];
            }
        }
        
        // Définir les colonnes et lignes à partir de la configuration choisie (JAMAIS des dimensions de la dalle)
        $colonnes = (int)$dimensions[0];
        $lignes = (int)$dimensions[1];
        $totalModules = $colonnes * $lignes;
        
        // Log pour traçabilité
        Log::info('Configuration de modules utilisée pour la dalle', [
            'dalle_id' => $dalle->id,
            'config_passée' => $modulesConfig,
            'config_utilisée' => $configToUse,
            'colonnes' => $colonnes,
            'lignes' => $lignes,
            'modules_attendus' => $totalModules
        ]);
        
        // Vérifier rapidement si des modules existent encore pour cette dalle
        // et les supprimer pour éviter les doublons
        $existingModules = \App\Models\Module::where('dalle_id', $dalle->id)->count();
        if ($existingModules > 0) {
            Log::warning('Des modules existent encore pour cette dalle, suppression forcée', [
                'dalle_id' => $dalle->id,
                'existing_count' => $existingModules
            ]);
            
            // Supprimer tous les modules existants
            \App\Models\Module::where('dalle_id', $dalle->id)->delete();
        }
        
        // Mettre à jour les propriétés de la dalle pour assurer la cohérence
        $dalle->disposition_modules = $configToUse;
        $dalle->nb_colonnes = $colonnes;
        $dalle->nb_lignes = $lignes;
        $dalle->nb_modules = $totalModules;
        $dalle->save();
        
        // Calculer la taille de chaque module en fonction des dimensions de la dalle
        // et de la configuration choisie
        $moduleWidth = $dalle->largeur / $colonnes;
        $moduleHeight = $dalle->hauteur / $lignes;
        
        // Créer les modules
        $moduleCount = 0;
        for ($y = 0; $y < $lignes; $y++) {
            for ($x = 0; $x < $colonnes; $x++) {
                $moduleCount++;
                $positionLettre = chr(65 + $y) . ($x + 1); // A1, A2, B1, B2, etc.
                
                // Créer le module
                \App\Models\Module::create([
                    'dalle_id' => $dalle->id,
                    'largeur' => $moduleWidth,
                    'hauteur' => $moduleHeight,
                    'nb_pixels_largeur' => 100, // Par défaut
                    'nb_pixels_hauteur' => 100, // Par défaut
                    'etat' => 'non_commence', // État par défaut pour les nouveaux modules
                    'reference_module' => $dalle->reference_dalle . '-M' . $moduleCount,
                    'position_lettre' => $positionLettre,
                    'position_x' => $x,
                    'position_y' => $y
                ]);
            }
        }
        
        Log::info('Modules créés pour la dalle', [
            'dalle_id' => $dalle->id,
            'nb_modules_créés' => $moduleCount,
            'config_finale' => $configToUse
        ]);
        
        return $moduleCount;
    }
    
    /**
     * Méthode auxiliaire pour créer des dalles de différents types
     */
    private function createDallesForMultipleSizes($chantier, $produit, $nbFlightcases, $nbDallesPerFC, $modulesConfig, $configType)
    {
        // Définir les dimensions de la dalle selon le type de configuration
        $largeur = 500;
        $hauteur = $configType == 1 ? 500 : 1000;
        
        // IMPORTANT: Dans un premier temps, vérifier si le produit a une configuration définie
        // Si oui, priorité au choix utilisateur plutôt qu'à la configuration passée en paramètre
        $configurationAUtiliser = $modulesConfig;
        if ($produit && !empty($produit->disposition_modules) && preg_match('/^\d+x\d+$/', $produit->disposition_modules)) {
            $configurationAUtiliser = $produit->disposition_modules;
            Log::info('Priorité donnée à la configuration du produit dans createDallesForMultipleSizes', [
                'produit_id' => $produit->id,
                'config_produit' => $produit->disposition_modules,
                'config_passée' => $modulesConfig
            ]);
        }
        
        // Assurer que la configuration est au format valide NxM
        if (!preg_match('/^\d+x\d+$/', $configurationAUtiliser)) {
            $configurationAUtiliser = '2x2'; // Configuration par défaut si invalide
            Log::warning('Configuration invalide détectée dans createDallesForMultipleSizes, utilisation de 2x2', [
                'config_invalide' => $configurationAUtiliser
            ]);
        }
        
        // Créer les dalles pour chaque flightcase
        for ($f = 1; $f <= $nbFlightcases; $f++) {
            for ($d = 1; $d <= $nbDallesPerFC; $d++) {
                // Calculer le nombre de modules en fonction de la configuration
                $modulesConfigParts = explode('x', $configurationAUtiliser);
                $nbModules = (int)$modulesConfigParts[0] * (int)$modulesConfigParts[1];
                
                $reference_dalle = 'FC' . $f . '-D' . $d; // Format: FC1-D1, FC1-D2, etc.
                $dalle = Dalle::create([
                    'largeur' => $largeur,
                    'hauteur' => $hauteur,
                    'produit_id' => $produit->id,
                    'reference_dalle' => $reference_dalle, // Référence structurée pour organisation
                    'dalle_type_id' => $configType,
                    'disposition_modules' => $configurationAUtiliser, // Utilisation de la bonne configuration
                    'flightcase_number' => $f,
                    'dalle_number' => $d,
                    'nb_modules' => $nbModules,
                    'alimentation' => '5V', // Valeur par défaut
                    'nb_colonnes' => (int)$modulesConfigParts[0],
                    'nb_lignes' => (int)$modulesConfigParts[1]
                ]);
                
                Log::info('Dalle multi-taille créée', [
                    'dalle_id' => $dalle->id,
                    'type' => $configType,
                    'disposition' => $configurationAUtiliser,
                    'nb_modules' => $nbModules
                ]);
                
                // Créer les modules pour cette dalle en utilisant la même configuration
                $this->createModulesForDalle($dalle, $configurationAUtiliser);
            }
        }
    }
    
    /**
     * Afficher le formulaire de création - Étape 4 (finale)
     */
    public function createStep4(Chantier $chantier)
    {
        // Charger le chantier avec toutes ses relations pour la vue finale
        $chantier->load(['client', 'produits.dalles.modules']);
        
        // Récupérer le client et les stats
        $client = $chantier->client;
        $produit = $chantier->produits->first();
        $produitCatalogueId = $produit->produit_catalogue_id ?? null;
        $produitRef = $produitCatalogueId ? \App\Models\ProduitCatalogue::find($produitCatalogueId) : null;
        
        // Calculer quelques statistiques pour le récapitulatif
        $stats = [
            'produits' => $chantier->produits->count(),
            'dalles' => 0,
            'modules' => 0,
        ];
        
        foreach ($chantier->produits as $produit) {
            $stats['dalles'] += $produit->dalles->count();
            foreach ($produit->dalles as $dalle) {
                $stats['modules'] += $dalle->modules->count();
            }
        }
        
        // Récupérer les techniciens (pour compatibilité avec le template)
        $techniciens = \App\Models\User::where('role', 'technicien')->orWhere('role', 'admin')->get();
        
        // Construire les données des étapes pour la vue (basé sur le modèle)
        $step1Data = [
            'date_reception' => $chantier->date_reception,
            'date_butoir' => $chantier->date_butoir,
            'etat' => $chantier->etat
        ];
        
        $step2Data = [
            'selection_type' => $produitCatalogueId ? 'existant' : 'personnalise',
            'marque' => $produit->marque,
            'modele' => $produit->modele,
            'pitch' => $produit->pitch,
            'utilisation' => $produit->utilisation ?? 'indoor',
            'electronique' => $produit->electronique ?? 'nova',
            'electronique_detail' => $produit->electronique_detail ?? ''
        ];
        
        // Déterminer le mode de configuration à partir des dalles
        $dalle = $chantier->produits->first()->dalles->first();
        $isFlightCase = $dalle && !($dalle->is_virtual ?? false);
        
        // Calcul amélioré des statistiques de flightcases et dalles
        $nb_flightcases = 0;
        $flightcaseDetails = [];
        $nb_modules_par_dalle = 0;
        
        if ($isFlightCase) {
            // Récupérer tous les numéros de flightcases uniques
            $flightcaseNumbers = $chantier->produits->first()->dalles
                ->pluck('flightcase_number')
                ->filter() // Filtrer les valeurs null ou vides
                ->unique()
                ->sort()
                ->values();
                
            // Si une configuration valide a été trouvée dans la session depuis l'étape 3
            if (session()->has('chantier_data_step3') && 
                isset(session('chantier_data_step3')['nb_flightcases']) && 
                session('chantier_data_step3')['nb_flightcases'] > 0) {
                
                $nb_flightcases = session('chantier_data_step3')['nb_flightcases'];
                
                // Pour la cohérence, s'assurer que le nombre de flightcases n'est pas inférieur 
                // au nombre détecté dans la base de données
                if ($flightcaseNumbers->count() > $nb_flightcases) {
                    $nb_flightcases = $flightcaseNumbers->count();
                }
            } else {
                $nb_flightcases = $flightcaseNumbers->count();
                if ($nb_flightcases == 0) {
                    $nb_flightcases = 1; // Garantir qu'il y a au moins 1 flightcase
                }
            }
            
            // Pour chaque flightcase, calculer son nombre de dalles
            foreach ($flightcaseNumbers as $fcNumber) {
                if (!empty($fcNumber)) { // Ignorer les nulls
                    $dallesCount = $chantier->produits->first()->dalles->where('flightcase_number', $fcNumber)->count();
                    $flightcaseDetails[] = "FC#$fcNumber: $dallesCount dalles";
                }
            }
            
            // Déterminer le nombre exact de modules par dalle en fonction de la disposition
            // Priorité 1: Utiliser la disposition des modules du produit associé
            if ($produit->disposition_modules && strpos($produit->disposition_modules, 'x') !== false) {
                $parts = explode('x', $produit->disposition_modules);
                if (count($parts) == 2) {
                    $nb_modules_par_dalle = (int)$parts[0] * (int)$parts[1];
                    
                    Log::info('Calcul exact du nombre de modules par dalle depuis la disposition du produit', [
                        'disposition' => $produit->disposition_modules,
                        'nb_modules_par_dalle' => $nb_modules_par_dalle
                    ]);
                }
            }
            // Priorité 2: Utiliser la disposition stockée en session
            else if (session()->has('chantier_data_step3') && 
                isset(session('chantier_data_step3')['disposition_modules']) && 
                strpos(session('chantier_data_step3')['disposition_modules'], 'x') !== false) {
                
                $parts = explode('x', session('chantier_data_step3')['disposition_modules']);
                if (count($parts) == 2) {
                    $nb_modules_par_dalle = (int)$parts[0] * (int)$parts[1];
                    
                    Log::info('Calcul exact du nombre de modules par dalle depuis la session', [
                        'disposition' => session('chantier_data_step3')['disposition_modules'],
                        'nb_modules_par_dalle' => $nb_modules_par_dalle
                    ]);
                }
            }
            // Priorité 3: Utiliser le nombre moyen de modules par dalle stocké en session
            else if (session()->has('chantier_data_step3') && 
                isset(session('chantier_data_step3')['nb_modules_par_dalle']) && 
                session('chantier_data_step3')['nb_modules_par_dalle'] > 0) {
                
                $nb_modules_par_dalle = session('chantier_data_step3')['nb_modules_par_dalle'];
                
                Log::info('Utilisation des données de session pour modules par dalle', [
                    'session_nb_modules_par_dalle' => $nb_modules_par_dalle
                ]);
            } 
            // Priorité 4: Calculer à partir des données de la base
            else if ($stats['dalles'] > 0) {
                $nb_modules_par_dalle = round($stats['modules'] / $stats['dalles']);
                
                Log::info('Calcul de la moyenne de modules par dalle', [
                    'total_modules' => $stats['modules'],
                    'total_dalles' => $stats['dalles'],
                    'moyenne' => $nb_modules_par_dalle
                ]);
            }
        }
        
        // Calculer le nombre EXACT de modules à partir du nombre de dalles et du nombre de modules par dalle
        // Cette approche est beaucoup plus précise qu'utiliser le compte de la base de données
        if ($isFlightCase) {
            // Pour le mode flightcase, calculer Total modules = Nombre de dalles × Nombre de modules par dalle
            $nb_modules_total = $stats['dalles'] * $nb_modules_par_dalle;
            
            Log::info('Calcul déterministe du nombre total de modules', [
                'nb_dalles' => $stats['dalles'],
                'nb_modules_par_dalle' => $nb_modules_par_dalle,
                'calcul' => "{$stats['dalles']} × {$nb_modules_par_dalle} = {$nb_modules_total}",
                'ancien_calcul' => $stats['modules']
            ]);
        } else {
            // Pour le mode individuel, utiliser le nombre spécifié
            // ou le nombre réel dans la base de données si disponible
            $nb_modules_total = session()->has('chantier_data_step3') && 
                isset(session('chantier_data_step3')['nb_modules_total']) ? 
                session('chantier_data_step3')['nb_modules_total'] : $stats['modules'];
        }
        
        // Récupérer la disposition depuis la session ou du produit
        $disposition_modules = session()->has('chantier_data_step3') && isset(session('chantier_data_step3')['disposition_modules']) 
            ? session('chantier_data_step3')['disposition_modules'] 
            : $produit->disposition_modules;
            
        Log::info('Utilisation du nombre réel de modules depuis la base de données', [
            'db_modules_count' => $nb_modules_total,
            'session_value' => session()->has('chantier_data_step3') ? (session('chantier_data_step3')['nb_modules_total'] ?? 'non défini') : 'session vide',
            'disposition_modules' => $disposition_modules,
            'produit_disposition' => $produit->disposition_modules
        ]);
        
        $step3Data = [
            'mode' => $isFlightCase ? 'flightcase' : 'individuel',
            'nb_flightcases' => $nb_flightcases,
            'flightcase_details' => $flightcaseDetails,
            'nb_modules_par_dalle' => $nb_modules_par_dalle,
            'nb_modules_total' => $nb_modules_total,
            'disposition_modules' => $disposition_modules // Ajouter explicitement la disposition
        ];
        
        // Log pour débogage des valeurs finales utilisées dans l'affichage
        Log::info('Valeurs finales pour l\'étape 4 (finale)', [
            'nb_flightcases' => $nb_flightcases,
            'nb_modules_par_dalle' => $nb_modules_par_dalle,
            'nb_modules_total' => $nb_modules_total,
            'stats_modules' => $stats['modules'],
            'disposition_modules' => $disposition_modules,
            'disposition_produit' => $produit->disposition_modules
        ]);
        
        return view('chantiers.create_step4', compact(
            'chantier', 'client', 'stats', 'step1Data', 'step2Data', 
            'step3Data', 'produitRef', 'techniciens'
        ));
    }
    
    /**
     * Finaliser la création du chantier (étape 4)
     */
    public function storeStep4(Request $request, Chantier $chantier)
    {
        $validated = $request->validate([
            'generate_report' => 'nullable|boolean',
        ]);
        
        // Marquer le chantier comme non commencé (état initial)
        $chantier->update(['etat' => 'non_commence']);
        
        // Si demandé, générer un rapport
        if ($request->has('generate_report')) {
            try {
                // Vérifier si la méthode existe dans le contrôleur de rapports
                $rapportController = app(\App\Http\Controllers\RapportController::class);
                if (method_exists($rapportController, 'genererRapportChantier')) {
                    $rapportController->genererRapportChantier($chantier->id);
                }
                
                return redirect()->route('chantiers.show', $chantier)
                    ->with('success', 'Le chantier a été créé avec succès et un rapport a été généré!');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Erreur lors de la génération du rapport', [
                    'chantier_id' => $chantier->id,
                    'error' => $e->getMessage()
                ]);
                
                return redirect()->route('chantiers.show', $chantier)
                    ->with('warning', 'Le chantier a été créé avec succès, mais la génération du rapport a échoué.')
                    ->with('error_details', $e->getMessage());
            }
        }
        
        // Notification de création de chantier
        try {
            // Envoyer une notification au client si configuré
            if (config('app.send_chantier_notifications', false)) {
                $client = $chantier->client;
                $emailService = app(\App\Services\EmailService::class);
                $emailService->sendChantierCreatedEmail($chantier, $client);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors de l\'envoi de la notification de création de chantier', [
                'chantier_id' => $chantier->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Le chantier a été créé avec succès!');
    }
    
    /**
     * Finaliser la création du chantier
     */
    
    /**
     * Afficher un chantier spécifique
     */
    public function show(Chantier $chantier)
    {
        try {
            // Charger toutes les relations importantes
            $chantier->load([
                'client', 
                'produits.dalles.modules.interventions.diagnostic',
                'produits.dalles.modules.interventions.reparation'
            ]);
            
            // Récupérer le client directement
            $client = $chantier->client;
            
            // Calculer quelques statistiques pour l'aperçu
            $stats = [
                'produits' => $chantier->produits->count(),
                'dalles' => 0,
                'modules' => 0,
                'modules_repares' => 0,
                'modules_en_cours' => 0,
                'interventions' => 0,
            ];
            
            // Nettoyer les éventuelles interventions vides qui auraient été créées automatiquement
            $emptyInterventionsRemoved = 0;
            foreach ($chantier->produits as $produit) {
                foreach ($produit->dalles as $dalle) {
                    foreach ($dalle->modules as $module) {
                        // Supprimer les interventions vides sans diagnostic ni réparation
                        foreach ($module->interventions as $intervention) {
                            if (!$intervention->diagnostic && !$intervention->reparation) {
                                // Cette intervention est vide, on va la supprimer
                                $intervention->delete();
                                $emptyInterventionsRemoved++;
                            }
                        }
                    }
                }
            }
            
            if ($emptyInterventionsRemoved > 0) {
                Log::info('Interventions vides supprimées:', [
                    'chantier_id' => $chantier->id,
                    'count' => $emptyInterventionsRemoved
                ]);
                
                // Recharger le chantier pour avoir les données à jour
                $chantier->load([
                    'client', 
                    'produits.dalles.modules.interventions.diagnostic',
                    'produits.dalles.modules.interventions.reparation'
                ]);
            }
            
            foreach ($chantier->produits as $produit) {
                $stats['dalles'] += $produit->dalles->count();
                foreach ($produit->dalles as $dalle) {
                    $stats['modules'] += $dalle->modules->count();
                    
                    foreach ($dalle->modules as $module) {
                        if ($module->etat === 'Terminé') {
                            $stats['modules_repares']++;
                        } elseif ($module->etat === 'En réparation') {
                            $stats['modules_en_cours']++;
                        }
                        
                        $stats['interventions'] += $module->interventions->count();
                    }
                }
            }
            
            // Calculer le pourcentage de progression
            $stats['progression'] = $stats['modules'] > 0 
                ? round(($stats['modules_repares'] / $stats['modules']) * 100) 
                : 0;
                
            return view('chantiers.show', compact('chantier', 'client', 'stats'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors de l\'affichage du chantier', [
                'chantier_id' => $chantier->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('chantiers.index')
                ->with('error', 'Une erreur est survenue lors de l\'affichage du chantier. Veuillez réessayer ou contacter l\'administrateur.');
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Chantier $chantier)
    {
        // Charger la relation client
        $chantier->load('client');
        
        // Récupérer tous les clients pour le sélecteur
        $clients = Client::all();
        
        // Récupérer le client actuel
        $client = $chantier->client;
        
        return view('chantiers.edit', compact('chantier', 'clients', 'client'));
    }

    /**
     * Mettre à jour un chantier
     */
    public function update(Request $request, Chantier $chantier)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'date_reception' => 'required|date',
            'date_butoir' => 'required|date|after_or_equal:date_reception',
            'etat' => 'required|in:non_commence,en_cours,stand_by,termine',
        ]);

        $chantier->update($validated);

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Chantier mis à jour avec succès.');
    }

    /**
     * Supprimer un chantier
     */
    public function destroy(Request $request, Chantier $chantier)
    {
        // Valider que la référence et la société/client fournis correspondent
        $validated = $request->validate([
            'reference_confirmation' => 'required|string',
            'client_confirmation' => 'required|string',
        ]);
        
        // Vérifier que la référence correspond
        if ($validated['reference_confirmation'] !== $chantier->reference) {
            return redirect()->back()
                ->with('error', 'La référence saisie ne correspond pas à celle du chantier.');
        }
        
        // Vérifier que le nom du client/société correspond
        $clientName = $chantier->client->societe ?: $chantier->client->nom_complet;
        if ($validated['client_confirmation'] !== $clientName) {
            return redirect()->back()
                ->with('error', 'Le nom du client/société saisi ne correspond pas à celui du chantier.');
        }
        
        try {
            // Supprimer le chantier et toutes ses dépendances en utilisant une transaction
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            // Charger toutes les relations pour pouvoir les supprimer
            $chantier->load(['produits.dalles.modules.interventions.diagnostic', 'produits.dalles.modules.interventions.reparation']);
            
            // Supprimer les diagnostics, réparations, interventions, modules et dalles
            foreach ($chantier->produits as $produit) {
                foreach ($produit->dalles as $dalle) {
                    foreach ($dalle->modules as $module) {
                        foreach ($module->interventions as $intervention) {
                            // Supprimer le diagnostic associé
                            if ($intervention->diagnostic) {
                                $intervention->diagnostic->delete();
                            }
                            
                            // Supprimer la réparation associée
                            if ($intervention->reparation) {
                                $intervention->reparation->delete();
                            }
                            
                            // Supprimer l'intervention
                            $intervention->delete();
                        }
                        
                        // Supprimer le module
                        $module->delete();
                    }
                    
                    // Supprimer la dalle
                    $dalle->delete();
                }
                
                // Supprimer le produit
                $produit->delete();
            }
            
            // Enfin, supprimer le chantier lui-même
            $chantier->delete();
            
            \Illuminate\Support\Facades\DB::commit();
            
            return redirect()->route('chantiers.index')
                ->with('success', 'Chantier supprimé avec succès.');
                
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            
            \Illuminate\Support\Facades\Log::error('Erreur lors de la suppression du chantier', [
                'chantier_id' => $chantier->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression du chantier : ' . $e->getMessage());
        }
    }
    
    /**
     * Mettre à jour l'état d'un chantier rapidement
     */
    public function updateState(Request $request, Chantier $chantier)
    {
        $validated = $request->validate([
            'etat' => 'required|in:non_commence,stand_by,termine,defaillant',
        ]);
        
        $chantier->update(['etat' => $validated['etat']]);
        
        return redirect()->back()->with('success', 'État du chantier mis à jour avec succès.');
    }
    
    /**
     * Afficher le suivi d'un chantier avec un token (accès public)
     */
    public function suivi(string $token)
    {
        $chantier = Chantier::with([
                'client', 
                'produits.dalles.modules.interventions.diagnostic',
                'produits.dalles.modules.interventions.reparation'
            ])
            ->where('token_suivi', $token)
            ->first();
        
        if (!$chantier) {
            return redirect('/')->with('error', 'Chantier introuvable. Vérifiez le QR code ou l\'URL.');
        }
        
        // Calculer les statistiques pour le chantier
        $totalModules = 0;
        $modulesTermines = 0;
        $modulesEnCours = 0;
        $modulesStandBy = 0;
        $modulesDefaillants = 0;
        $modulesNonCommences = 0;
        $tempsTotal = 0;
        $interventionsEnCours = 0;
        
        // Pour l'analyse des causes
        $causes = [
            'usure_normale' => 0,
            'choc' => 0,
            'defaut_usine' => 0,
            'autre' => 0
        ];
        
        foreach ($chantier->produits as $produit) {
            foreach ($produit->dalles as $dalle) {
                $totalModules += $dalle->modules->count();
                $modulesTermines += $dalle->modules->where('etat', 'termine')->count();
                $modulesEnCours += $dalle->modules->where('etat', 'en_cours')->count();
                $modulesStandBy += $dalle->modules->where('etat', 'stand_by')->count();
                $modulesDefaillants += $dalle->modules->where('etat', 'defaillant')->count();
                $modulesNonCommences += $dalle->modules->where('etat', 'non_commence')->count();
                
                // Compter les interventions en cours et le temps total
                foreach ($dalle->modules as $module) {
                    foreach ($module->interventions as $intervention) {
                        $tempsTotal += $intervention->temps_total ?? 0;
                        
                        if (!$intervention->is_completed) {
                            $interventionsEnCours++;
                        }
                        
                        // Récupérer les causes des diagnostics si disponibles
                        if ($intervention->diagnostic && $intervention->diagnostic->cause) {
                            if (isset($causes[$intervention->diagnostic->cause])) {
                                $causes[$intervention->diagnostic->cause]++;
                            } else {
                                $causes['autre']++;
                            }
                        }
                    }
                }
            }
        }
        
        // Formater le temps total
        $heures = floor($tempsTotal / 3600);
        $minutes = floor(($tempsTotal % 3600) / 60);
        $secondes = $tempsTotal % 60;
        $tempsFormate = sprintf('%dh %02dm', $heures, $minutes);
        
        // Calculer le pourcentage de modules terminés
        $pourcentageTermines = $totalModules > 0 ? round(($modulesTermines / $totalModules) * 100) : 0;
        
        // Date de début des travaux (première intervention)
        $dateDebutTravaux = now()->format('d/m/Y');
        
        // Date prévue de fin
        $dateFinPrevue = $chantier->date_butoir ? $chantier->date_butoir->format('d/m/Y') : 'Non définie';
        
        // Analyser les causes
        $totalCauses = array_sum($causes);
        $causePrincipale = 'autre';
        $pourcentagePrincipal = 0;
        $causeSecondaire = '';
        $pourcentageSecondaire = 0;
        $ecartFaible = false;
        
        if ($totalCauses > 0) {
            // Trouver la cause principale
            $causePrincipale = array_keys($causes, max($causes))[0];
            $pourcentagePrincipal = round(($causes[$causePrincipale] / $totalCauses) * 100);
            
            // Calculer le reste pour cause secondaire
            $causesTmp = $causes;
            unset($causesTmp[$causePrincipale]);
            $secondMaxValue = !empty($causesTmp) ? max($causesTmp) : 0;
            $causeSecondaire = !empty($causesTmp) ? array_search($secondMaxValue, $causesTmp) : '';
            $pourcentageSecondaire = $totalCauses > 0 ? round(($secondMaxValue / $totalCauses) * 100) : 0;
            
            // Déterminer si les causes sont assez proches (moins de 20% d'écart)
            $ecartFaible = ($pourcentagePrincipal - $pourcentageSecondaire) <= 20 && $pourcentageSecondaire >= 30;
        }
        
        // Initialiser les variables pour les composants remplacés
        $totalLEDsRemplacees = 0;
        $totalICsRemplaces = 0;
        $totalMasquesRemplaces = 0;
        
        // Calculer le nombre de composants remplacés à partir des réparations
        foreach ($chantier->produits as $produit) {
            foreach ($produit->dalles as $dalle) {
                foreach ($dalle->modules as $module) {
                    foreach ($module->interventions as $intervention) {
                        if ($intervention->reparation) {
                            $totalLEDsRemplacees += $intervention->reparation->nb_leds_remplacees ?? 0;
                            $totalICsRemplaces += $intervention->reparation->nb_ic_remplaces ?? 0;
                            $totalMasquesRemplaces += $intervention->reparation->nb_masques_remplaces ?? 0;
                        }
                    }
                }
            }
        }

        return view('suivi.chantier', compact(
            'chantier', 
            'totalModules', 
            'modulesTermines', 
            'modulesEnCours',
            'modulesStandBy', 
            'modulesDefaillants',
            'modulesNonCommences',
            'pourcentageTermines',
            'tempsFormate',
            'interventionsEnCours',
            'dateDebutTravaux',
            'dateFinPrevue',
            'causes',
            'totalCauses',
            'causePrincipale',
            'pourcentagePrincipal',
            'causeSecondaire',
            'pourcentageSecondaire',
            'ecartFaible',
            'totalLEDsRemplacees',
            'totalICsRemplaces',
            'totalMasquesRemplaces'
        ));
    }
    
    /**
     * Stocker un nouveau chantier (méthode simple, pour compatibilité)
     */
    public function store(Request $request)
    {
        // Définir des règles de validation de base
        $rules = [
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'date_reception' => 'required|date',
            'etat' => 'required|in:non_commence,en_cours,stand_by,termine',
        ];
        
        // Validation conditionnelle pour date_butoir selon le type de projet
        if ($request->input('is_client_achat') == 0 || $request->input('is_client_achat') === null) {
            // SAV / Réparation ou cas par défaut (ancienne méthode)
            $rules['date_butoir'] = 'required|date|after_or_equal:date_reception';
        } else {
            // Vente / Achat client
            $rules['date_butoir'] = 'nullable|date|after_or_equal:date_reception';
        }
        
        $validated = $request->validate($rules);
        
        // Récupérer le client pour générer le nom automatiquement
        $client = Client::find($validated['client_id']);
        
        // Générer le nom du chantier automatiquement
        $societe = $client->societe ? $client->societe : $client->nom_complet;
        $date = date('d/m/Y');
        $validated['nom'] = "Réparation écran - {$societe} - {$date}";
        
        // Générer une référence unique
        $validated['reference'] = Chantier::genererReference();
        
        $chantier = Chantier::create($validated);
        
        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Chantier créé avec succès.');
    }
}