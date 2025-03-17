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

class ChantierController extends Controller
{
    /**
     * Formulaire de test pour le catalogue
     */
    public function testCatalogueForm()
    {
        $produitsCatalogue = ProduitCatalogue::all();
        return view('chantiers.test_catalogue_form', compact('produitsCatalogue'));
    }
    
    /**
     * Traiter le formulaire de test pour le catalogue
     */
    public function testCatalogueFormSubmit(Request $request)
    {
        // Log pour débogage
        \Illuminate\Support\Facades\Log::info('Test form submit', [
            'all_request' => $request->all(),
            'catalogue_id' => $request->input('catalogue_id')
        ]);
        
        $validated = $request->validate([
            'catalogue_id' => 'required|exists:produits_catalogue,id',
        ]);
        
        // Récupérer le produit pour affichage
        $produit = ProduitCatalogue::find($validated['catalogue_id']);
        
        return redirect()->route('test.catalogue.form')
            ->with('success', "Formulaire soumis avec succès! Produit sélectionné: {$produit->marque} {$produit->modele}");
    }
    
    /**
     * Afficher la liste des chantiers
     */
    public function index(Request $request)
    {
        $query = Chantier::with('client');
        
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
        \Illuminate\Support\Facades\Log::info('Méthode typeChoix appelée');
        
        // S'assurer que la vue existe et est accessible
        if (view()->exists('chantiers.type_choix')) {
            return view('chantiers.type_choix');
        } else {
            \Illuminate\Support\Facades\Log::error('Vue type_choix.blade.php introuvable');
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
     * Stocker un nouveau chantier
     */
    public function store(Request $request)
    {
        // Définir des règles de validation de base
        $rules = [
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'date_reception' => 'required|date',
            'etat' => 'required|in:non_commence,en_cours,termine',
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

    /**
     * Afficher un chantier spécifique
     */
    public function show(Chantier $chantier)
    {
        $chantier->load(['client', 'produits.dalles.modules']);
        return view('chantiers.show', compact('chantier'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Chantier $chantier)
    {
        $clients = Client::all();
        return view('chantiers.edit', compact('chantier', 'clients'));
    }

    /**
     * Mettre à jour un chantier
     */
    public function update(Request $request, Chantier $chantier)
    {
        // Définir des règles de validation de base
        $rules = [
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'date_reception' => 'required|date',
            'etat' => 'required|in:non_commence,en_cours,termine',
        ];
        
        // Validation conditionnelle pour date_butoir selon le type de projet
        if ($request->input('is_client_achat') == 0 || $request->input('is_client_achat') === null) {
            // SAV / Réparation ou cas par défaut
            $rules['date_butoir'] = 'required|date|after_or_equal:date_reception';
        } else {
            // Vente / Achat client
            $rules['date_butoir'] = 'nullable|date|after_or_equal:date_reception';
        }
        
        $validated = $request->validate($rules);
        
        // Si le client a changé, mettre à jour le nom du chantier
        if ($chantier->client_id != $validated['client_id']) {
            $client = Client::find($validated['client_id']);
            $societe = $client->societe ? $client->societe : $client->nom_complet;
            
            // Récupérer la marque du premier produit du chantier (si disponible)
            $marque = '';
            $produit = $chantier->produits()->first();
            if ($produit && $produit->marque) {
                $marque = $produit->marque;
            }
            
            // Générer un nom plus professionnel
            $nom = "SAV {$societe}";
            // Éviter la redondance si la marque contient déjà le nom de la société
            if ($marque && stripos($marque, $societe) === false) {
                $nom .= " - {$marque}";
            }
            // Ajouter la référence pour faciliter l'identification
            $nom .= " ({$chantier->reference})";
            
            $chantier->nom = $nom;
        }
        
        $oldEtat = $chantier->etat;
        
        // Si l'état passe de en_cours ou non_commence à termine, vérifier l'état des modules
        if ($validated['etat'] === 'termine' && $chantier->etat !== 'termine') {
            $totalModules = 0;
            $completedModules = 0;
            
            // Compter les modules terminés et le total
            foreach ($chantier->produits as $produit) {
                foreach ($produit->dalles as $dalle) {
                    foreach ($dalle->modules as $module) {
                        $totalModules++;
                        if ($module->etat === 'termine' || $module->etat === 'defaillant') {
                            $completedModules++;
                        }
                    }
                }
            }
            
            // Si tous les modules ne sont pas terminés, ajouter un warning
            if ($totalModules > 0 && $completedModules < $totalModules) {
                session()->flash('warning', 'Attention: ' . ($totalModules - $completedModules) . ' modules sur ' . $totalModules . ' ne sont pas encore terminés ou défaillants.');
            }
        }
        
        // Mettre à jour les autres champs
        $chantier->client_id = $validated['client_id'];
        $chantier->description = $validated['description'];
        $chantier->date_reception = $validated['date_reception'];
        $chantier->date_butoir = $validated['date_butoir'];
        $chantier->etat = $validated['etat'];
        
        $chantier->save();
        
        // Notifier le client si le chantier vient d'être marqué comme terminé
        if ($validated['etat'] === 'termine' && $oldEtat !== 'termine') {
            try {
                $emailService = new \App\Services\EmailService();
                $emailService->sendChantierCompletedEmail($chantier);
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email de fin de chantier: ' . $e->getMessage());
            }
        }
        
        // Notifier le client si le chantier vient d'être marqué comme en cours
        if ($validated['etat'] === 'en_cours' && $oldEtat !== 'en_cours') {
            try {
                $emailService = new \App\Services\EmailService();
                $emailService->sendInterventionsStartedEmail($chantier);
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email de début d\'interventions: ' . $e->getMessage());
            }
        }
        
        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Chantier mis à jour avec succès.');
    }
    
    /**
     * Mettre à jour rapidement l'état d'un chantier
     */
    public function updateState(Request $request, Chantier $chantier)
    {
        $validated = $request->validate([
            'etat' => 'required|in:non_commence,en_cours,termine',
        ]);
        
        $oldEtat = $chantier->etat;
        
        // Cas spécial : si on marque comme terminé, vérifier l'état des modules
        if ($validated['etat'] === 'termine' && $chantier->etat !== 'termine') {
            // Calculer combien de modules sont terminés
            $totalModules = 0;
            $completedModules = 0;
            
            foreach ($chantier->produits as $produit) {
                foreach ($produit->dalles as $dalle) {
                    foreach ($dalle->modules as $module) {
                        $totalModules++;
                        if ($module->etat === 'termine' || $module->etat === 'defaillant') {
                            $completedModules++;
                        }
                    }
                }
            }
            
            // Si tous les modules ne sont pas terminés, ajouter un warning
            if ($totalModules > 0 && $completedModules < $totalModules) {
                session()->flash('warning', 'Attention: ' . ($totalModules - $completedModules) . ' modules sur ' . $totalModules . ' ne sont pas encore terminés ou défaillants.');
            }
        }
        
        $chantier->etat = $validated['etat'];
        $chantier->save();
        
        // Notifier le client si le chantier vient d'être marqué comme terminé
        if ($validated['etat'] === 'termine' && $oldEtat !== 'termine') {
            try {
                $emailService = new \App\Services\EmailService();
                $emailService->sendChantierCompletedEmail($chantier);
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email de fin de chantier: ' . $e->getMessage());
            }
        }
        
        // Notifier le client si le chantier vient d'être marqué comme en cours
        if ($validated['etat'] === 'en_cours' && $oldEtat !== 'en_cours') {
            try {
                $emailService = new \App\Services\EmailService();
                $emailService->sendInterventionsStartedEmail($chantier);
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email de début d\'interventions: ' . $e->getMessage());
            }
        }
        
        return redirect()->back()->with('success', 'État du chantier mis à jour avec succès.');
    }

    /**
     * Supprimer un chantier
     */
    public function destroy(Chantier $chantier)
    {
        $chantier->delete();
        
        return redirect()->route('chantiers.index')
            ->with('success', 'Chantier supprimé avec succès.');
    }

    /**
     * Afficher le formulaire de création - Étape 1 : Client
     */
    public function createStep1(Request $request)
    {
        // Réinitialiser toutes les données de session pour éviter les problèmes
        session()->forget(['chantier_data_step1', 'chantier_data_step2', 'chantier_data_step3', 'chantier_data_step4']);
        
        // Récupérer le type de projet (maintenance ou vente)
        $type = $request->query('type', 'maintenance');
        
        // Valider que le type est correct
        if (!in_array($type, ['maintenance', 'vente'])) {
            $type = 'maintenance'; // Par défaut
        }
        
        // Debug - afficher la valeur
        \Log::info('Type de projet:', ['type' => $type]);
        
        // Stocker le type dans la session
        session(['projet_type' => $type]);
        
        // Récupérer tous les clients pour la liste déroulante
        $clients = Client::all();
        
        return view('chantiers.create_step1', compact('clients', 'type'));
    }
    
    /**
     * Créer un client via AJAX pendant la création d'un chantier
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
     * Traiter les données pour l'étape 1
     */
    public function storeNewStep1(Request $request)
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
        
        // Convertir les valeurs booléennes
        $validated['is_client_achat'] = isset($validated['is_client_achat']) && $validated['is_client_achat'] ? true : false;
        $validated['is_under_warranty'] = isset($validated['is_under_warranty']) && $validated['is_under_warranty'] ? true : false;
        
        // Si ce n'est pas sous garantie, effacer les données de garantie
        if (!$validated['is_under_warranty']) {
            $validated['warranty_end_date'] = null;
            $validated['warranty_type'] = null;
        }

        // Stocker les données en session
        session(['chantier_data_step1' => $validated]);

        // Stocker un placeholder pour l'étape 2 (sera écrasé plus tard)
        session(['chantier_step2' => [
            'nom' => '',
            'reference' => '',
            'description' => $validated['description'],
            'date_reception' => $validated['date_reception'],
            'date_butoir' => $validated['date_butoir'],
            'etat' => $validated['etat'],
            'is_client_achat' => $validated['is_client_achat'],
            'is_under_warranty' => $validated['is_under_warranty'],
            'warranty_end_date' => $validated['warranty_end_date'],
            'warranty_type' => $validated['warranty_type']
        ]]);

        return redirect()->route('chantiers.create.step2');
    }

    /**
     * Afficher le formulaire de création - Étape 2 : Produit
     */
    public function createNewStep2()
    {
        // Vérifier si l'étape 1 a été complétée
        if (!session()->has('chantier_data_step1')) {
            return redirect()->route('chantiers.create.step1');
        }
        
        // Récupérer les données du client depuis l'étape 1
        $step1Data = session('chantier_data_step1');
        $client = Client::findOrFail($step1Data['client_id']);
        
        $produitsCatalogue = ProduitCatalogue::all();
        
        // Récupérer toutes les fiches techniques LED disponibles
        $ledDatasheets = \App\Models\LedDatasheet::orderBy('created_at', 'desc')->get();
        
        return view('chantiers.create_step2_product', compact('produitsCatalogue', 'client', 'ledDatasheets'));
    }

    /**
     * Traiter les données pour l'étape 2
     */
    public function storeNewStep2(Request $request)
    {
        // Debug: Enregistrer toutes les données reçues dans un fichier log
        \Illuminate\Support\Facades\Log::info('Données reçues dans storeNewStep2', [
            'all_request' => $request->all(),
            'from_catalogue' => $request->input('from_catalogue'),
            'catalogue_id' => $request->input('catalogue_id')
        ]);
        
        // Vérifier si nous avons besoin d'un catalogue_id
        if ($request->input('from_catalogue') == 1 && empty($request->input('catalogue_id'))) {
            return back()->with('error', 'Veuillez sélectionner un produit du catalogue')->withInput();
        }
        
        // Validation des données
        $validated = $request->validate([
            'marque' => 'nullable|string|max:255',
            'modele' => 'nullable|string|max:255',
            'pitch' => 'nullable|numeric|min:0.1|max:100',
            'utilisation' => 'nullable|in:indoor,outdoor',
            'electronique' => 'nullable|in:nova,linsn,dbstar,colorlight,barco,brompton,autre',
            'electronique_detail' => 'nullable|required_if:electronique,autre|string|max:255',
            'bain_couleur' => 'nullable|string|max:255',
            'alimentation' => 'nullable|string|max:255',
            'carte_reception' => 'nullable|string|max:255',
            'hub' => 'nullable|string|max:255',
            'largeur_dalle' => 'nullable|numeric|min:1',
            'hauteur_dalle' => 'nullable|numeric|min:1',
            'largeur_module' => 'nullable|numeric|min:1',
            'hauteur_module' => 'nullable|numeric|min:1',
            'disposition_modules' => 'nullable|string',
            'modules_largeur' => 'nullable|numeric|min:1|max:10',
            'modules_hauteur' => 'nullable|numeric|min:1|max:10',
            'from_catalogue' => 'required|boolean',
            'catalogue_id' => 'required_if:from_catalogue,1|nullable|exists:produits_catalogue,id',
            'led_type' => 'nullable|string',
            'led_color' => 'nullable|string|in:black,white',
            'led_size' => 'nullable|string',
            'led_pads' => 'nullable|string',
            'led_rotation' => 'nullable|string',
            'led_datasheet_name' => 'nullable|string',
            'led_datasheet_image' => 'nullable|string',
        ]);
        
        // Créer un nouveau datasheet LED si les informations sont fournies
        $ledDatasheetId = null;
        if (!empty($validated['led_datasheet_name']) && !empty($validated['led_datasheet_image'])) {
            try {
                // Récolter toutes les données du datasheet
                $dsType = $validated['led_type'] ?? 'SMD';
                $dsColor = $validated['led_color'] ?? 'black';
                $dsReference = $validated['led_datasheet_name'];
                $dsNbPoles = (int)($validated['led_pads'] ?? 4);
                $dsSize = $validated['led_size'] ?? '2121';
                
                // Logger ces informations pour le débogage
                \Illuminate\Support\Facades\Log::info('Données datasheet LED', [
                    'type' => $dsType,
                    'color' => $dsColor,
                    'reference' => $dsReference,
                    'nb_poles' => $dsNbPoles,
                    'size' => $dsSize,
                    'user_id' => auth()->id(),
                ]);
                
                // Force un format unique pour la référence, garantissant qu'elle est distincte
                $uniqueReference = $dsType . $dsSize . '_' . $dsColor . '_' . $dsNbPoles . 'P_' . uniqid();
                
                // Créer un nouveau datasheet - toujours créer un nouveau pour éviter les confusions
                $ledDatasheet = \App\Models\LedDatasheet::create([
                    'type' => $dsType,
                    'color' => $dsColor,
                    'reference' => $uniqueReference,
                    'nb_poles' => $dsNbPoles,
                    'disposition' => $dsNbPoles . ' poles',
                    'position_chanfrein' => 'haut_gauche',
                    'configuration_poles' => json_encode([]),
                    'notes' => 'Modèle: ' . $dsReference . ' - Créé le ' . date('Y-m-d H:i:s'),
                    'user_id' => auth()->id(),
                    'image_data' => $validated['led_datasheet_image']
                ]);
                
                $ledDatasheetId = $ledDatasheet->id;
                \Illuminate\Support\Facades\Log::info('Nouveau LED Datasheet créé', [
                    'id' => $ledDatasheetId,
                    'reference' => $uniqueReference,
                    'original_reference' => $dsReference
                ]);
                
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Erreur lors de la création du datasheet LED', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Convertir la marque et le modèle en majuscules
        if (isset($validated['marque'])) {
            $validated['marque'] = strtoupper($validated['marque']);
        }
        if (isset($validated['modele'])) {
            $validated['modele'] = strtoupper($validated['modele']);
        }

        // Récupérer la valeur de add_to_catalogue avec une valeur par défaut de false
        $addToCatalogue = $request->has('add_to_catalogue');

        // Si l'utilisateur veut ajouter le produit au catalogue et ne sélectionne pas depuis le catalogue
        if ($addToCatalogue && !$validated['from_catalogue']) {
            try {
                ProduitCatalogue::create([
                    'marque' => $validated['marque'],
                    'modele' => $validated['modele'],
                    'pitch' => $validated['pitch'],
                    'utilisation' => $validated['utilisation'],
                    'electronique' => $validated['electronique'],
                    'electronique_detail' => $validated['electronique_detail'] ?? null,
                    'bain_couleur' => $validated['bain_couleur'] ?? null,
                    'hub' => $validated['hub'] ?? null,
                    'carte_reception' => $validated['carte_reception'] ?? null,
                ]);
            } catch (\Exception $e) {
                // Log l'erreur mais continuer le processus
                \Illuminate\Support\Facades\Log::error('Erreur lors de l\'ajout au catalogue: ' . $e->getMessage());
            }
        }

        // Stocker les données avec le nom correct et ajouter un champ de type de sélection
        $chantier_data_step2 = [
            'selection_type' => $validated['from_catalogue'] ? 'existant' : 'nouveau',
            'produit_catalogue_id' => $validated['from_catalogue'] ? $validated['catalogue_id'] : null,
        ];

        // Si c'est un nouveau produit, ajouter tous les champs
        if (!$validated['from_catalogue']) {
            $chantier_data_step2 = array_merge($chantier_data_step2, [
                'marque' => $validated['marque'],
                'modele' => $validated['modele'],
                'pitch' => $validated['pitch'],
                'utilisation' => $validated['utilisation'],
                'electronique' => $validated['electronique'],
                'electronique_detail' => $validated['electronique_detail'] ?? null,
                'bain_couleur' => $validated['bain_couleur'] ?? null,
                'alimentation' => $validated['alimentation'] ?? null,
                'hub' => $validated['hub'] ?? null,
                'carte_reception' => $validated['carte_reception'] ?? null,
                // On garde ces valeurs pour compatibilité avec l'ancienne interface
                'largeur_dalle' => $validated['largeur_dalle'],
                'hauteur_dalle' => $validated['hauteur_dalle'],
                'largeur_module' => $validated['largeur_module'],
                'hauteur_module' => $validated['hauteur_module'],
                'disposition_modules' => $validated['disposition_modules'],
            ]);
            
            // Traiter les types de dalles multiples s'ils existent
            $dalleTypes = [];
            if (isset($request->dalle_types) && is_array($request->dalle_types)) {
                foreach ($request->dalle_types as $key => $dalleType) {
                    $typeConfig = [
                        'id' => $key,
                        'nom' => $dalleType['nom'] ?? "Type " . ($key + 1),
                        'largeur' => $dalleType['largeur'] ?? 500,
                        'hauteur' => $dalleType['hauteur'] ?? 500,
                        'largeur_module' => $dalleType['largeur_module'] ?? 250,
                        'hauteur_module' => $dalleType['hauteur_module'] ?? 250,
                        'disposition' => $dalleType['disposition'] ?? '2x2',
                    ];
                    
                    // Si la disposition est personnalisée, ajouter les dimensions personnalisées
                    if (($dalleType['disposition'] === 'personnalise' || $dalleType['disposition'] === 'custom') 
                        && isset($dalleType['modules_largeur']) && isset($dalleType['modules_hauteur'])) {
                        $typeConfig['modules_largeur'] = $dalleType['modules_largeur'];
                        $typeConfig['modules_hauteur'] = $dalleType['modules_hauteur'];
                    } else {
                        // Extraire les dimensions à partir de la valeur de disposition (ex: "2x2")
                        $dimensions = explode('x', $dalleType['disposition']);
                        if (count($dimensions) === 2) {
                            $typeConfig['modules_largeur'] = (int)$dimensions[0];
                            $typeConfig['modules_hauteur'] = (int)$dimensions[1];
                        } else {
                            $typeConfig['modules_largeur'] = 2;
                            $typeConfig['modules_hauteur'] = 2;
                        }
                    }
                    
                    $dalleTypes[] = $typeConfig;
                }
            }
            
            // Si aucun type n'est défini, créer un type par défaut avec les valeurs globales
            if (empty($dalleTypes)) {
                $dalleTypes[] = [
                    'id' => 0,
                    'nom' => 'Standard',
                    'largeur' => $validated['largeur_dalle'],
                    'hauteur' => $validated['hauteur_dalle'],
                    'largeur_module' => $validated['largeur_module'],
                    'hauteur_module' => $validated['hauteur_module'],
                    'disposition' => $validated['disposition_modules'],
                    'modules_largeur' => $chantier_data_step2['modules_largeur'] ?? 2,
                    'modules_hauteur' => $chantier_data_step2['modules_hauteur'] ?? 2,
                ];
            }
            
            // Ajouter les types de dalle à la session
            $chantier_data_step2['dalle_types'] = $dalleTypes;
            
            // Si la disposition globale est personnalisée, ajouter les dimensions personnalisées
            // (Pour compatibilité avec l'ancienne interface)
            if (($validated['disposition_modules'] === 'personnalise' || $validated['disposition_modules'] === 'custom') 
                && isset($validated['modules_largeur']) && isset($validated['modules_hauteur'])) {
                $chantier_data_step2['modules_largeur'] = $validated['modules_largeur'];
                $chantier_data_step2['modules_hauteur'] = $validated['modules_hauteur'];
            } else {
                // Extraire les dimensions à partir de la valeur de disposition (ex: "2x2")
                $dimensions = explode('x', $validated['disposition_modules']);
                if (count($dimensions) === 2) {
                    $chantier_data_step2['modules_largeur'] = (int)$dimensions[0];
                    $chantier_data_step2['modules_hauteur'] = (int)$dimensions[1];
                }
            }
        } else {
            // Si c'est un produit du catalogue, récupérer ses informations
            $produitCatalogue = ProduitCatalogue::find($validated['catalogue_id']);
            if ($produitCatalogue) {
                $chantier_data_step2 = array_merge($chantier_data_step2, [
                    'marque' => $produitCatalogue->marque,
                    'modele' => $produitCatalogue->modele,
                    'pitch' => $produitCatalogue->pitch,
                    'utilisation' => $produitCatalogue->utilisation,
                    'electronique' => $produitCatalogue->electronique,
                    'electronique_detail' => $produitCatalogue->electronique_detail,
                    'bain_couleur' => $produitCatalogue->bain_couleur ?? null,
                    'hub' => $produitCatalogue->hub ?? null,
                    'carte_reception' => $produitCatalogue->carte_reception ?? null,
                    // Valeurs par défaut pour les autres champs si non présents dans le catalogue
                    'largeur_dalle' => $produitCatalogue->largeur_dalle ?? 500,
                    'hauteur_dalle' => $produitCatalogue->hauteur_dalle ?? 500,
                    'largeur_module' => $produitCatalogue->largeur_module ?? 250,
                    'hauteur_module' => $produitCatalogue->hauteur_module ?? 250,
                    'disposition_modules' => '2x2',
                    'modules_largeur' => 2,
                    'modules_hauteur' => 2,
                ]);
            }
        }

        // Ajouter l'ID du datasheet LED s'il a été créé
        if ($ledDatasheetId) {
            $chantier_data_step2['led_datasheet_id'] = $ledDatasheetId;
        }
        
        // Débogage supplémentaire
        \Illuminate\Support\Facades\Log::info('Session stockée pour l\'étape 2', [
            'chantier_data_step2' => $chantier_data_step2
        ]);
        
        try {
            // Utiliser le nom correct pour la variable de session et assurer qu'elle est bien stockée
            session(['chantier_data_step2' => $chantier_data_step2]);
            session()->save();
            
            // Vérifier que les données de l'étape 1 sont toujours présentes
            \Illuminate\Support\Facades\Log::info('Données de session après stockage', [
                'step1_exists' => session()->has('chantier_data_step1'),
                'step2_exists' => session()->has('chantier_data_step2')
            ]);
            
            return redirect()->route('chantiers.create.step3');
        } catch (\Exception $e) {
            // Capturer toute erreur qui pourrait survenir
            \Illuminate\Support\Facades\Log::error('Erreur lors de la redirection vers étape 3', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors du traitement des données. Veuillez réessayer.')->withInput();
        }
    }

    /**
     * Afficher le formulaire de création - Étape 3 : Configuration des dalles et modules
     */
    public function createNewStep3()
    {
        // Vérifier et logger les données de session pour le débogage
        \Illuminate\Support\Facades\Log::info('Accès à étape 3 - Données de session disponibles', [
            'step1_exists' => session()->has('chantier_data_step1'),
            'step2_exists' => session()->has('chantier_data_step2'),
            'session_id' => session()->getId()
        ]);
        // Vérifier si les étapes précédentes ont été complétées
        if (!session()->has('chantier_data_step1') || !session()->has('chantier_data_step2')) {
            return redirect()->route('chantiers.create.step1');
        }

        $clientData = session('chantier_data_step1');
        $produitData = session('chantier_data_step2');
        $produitRef = null;
        
        // Récupérer le client pour l'afficher
        $client = Client::find($clientData['client_id']);

        if ($produitData['selection_type'] == 'existant' && isset($produitData['produit_catalogue_id'])) {
            $produitRef = ProduitCatalogue::find($produitData['produit_catalogue_id']);
        }
        
        // Pour la démonstration, nous allons créer deux types de dalles avec des dimensions différentes
        $dalleTypes = [
            [
                'id' => 0,
                'nom' => 'Dalle 500x500',
                'largeur' => 500,
                'hauteur' => 500,
                'disposition' => $produitData['disposition_modules'] ?? '2x2',
            ],
            [
                'id' => 1,
                'nom' => 'Dalle 500x1000',
                'largeur' => 500,
                'hauteur' => 1000,
                'disposition' => $produitData['disposition_modules'] ?? '2x2',
            ]
        ];

        return view('chantiers.create_step3', compact('client', 'clientData', 'produitRef', 'produitData', 'dalleTypes'));
    }

    /**
     * Traiter l'étape 3 et passer à l'étape 4 (interventions)
     */
    public function storeStep3(Request $request)
    {
        $validated = $request->validate([
            'mode' => 'required|in:flightcase,individuel',
            'nb_flightcases' => 'required_if:mode,flightcase|integer|min:0',
            'nb_dalles_par_flightcase' => 'required_if:mode,flightcase|integer|min:1',
            'nb_modules_par_dalle' => 'required_if:mode,flightcase|integer|min:1',
            'multiple_sizes' => 'nullable|boolean',
            'config1' => 'nullable|array',
            'config1.nb_flightcases' => 'nullable|integer|min:0',
            'config1.nb_dalles' => 'nullable|integer|min:0',
            'config1.modules_config' => 'nullable|string',
            'config2' => 'nullable|array',
            'config2.nb_flightcases' => 'nullable|integer|min:0',
            'config2.nb_dalles' => 'nullable|integer|min:0',
            'config2.modules_config' => 'nullable|string',
            'nb_modules_total' => 'required_if:mode,individuel|integer|min:1',
            'fc_partiel' => 'nullable|array',
            'fc_partiel.*' => 'nullable|boolean',
            'fc_nb_dalles' => 'nullable|array',
            'fc_nb_dalles.*' => 'nullable|integer|min:1',
            'flights' => 'nullable|array',
            'flights.*.dalle_type_id' => 'nullable|integer',
            'flights.*.modules_config' => 'nullable|string',
        ]);

        // Traiter les détails des FlightCases
        $validated['flightcases_details'] = [];
        
        if ($validated['mode'] == 'flightcase') {
            // Vérifier si le mode tailles multiples est activé
            if (isset($validated['multiple_sizes']) && $validated['multiple_sizes']) {
                // Utiliser les configurations spécifiques pour les différentes tailles
                
                // Configuration 1 - Dalles 500x500
                if (isset($request->config1) && $request->config1['nb_flightcases'] > 0) {
                    $config1 = $request->config1;
                    $nbFC = intval($config1['nb_flightcases']);
                    $nbDalles = intval($config1['nb_dalles']);
                    $modulesConfig = $config1['modules_config'];
                    
                    // Calculer le nombre de modules par dalle selon la configuration
                    $modulesPerDalle = 4; // Par défaut 2x2
                    if ($modulesConfig === '1x1') $modulesPerDalle = 1;
                    else if ($modulesConfig === '2x2') $modulesPerDalle = 4;
                    else if ($modulesConfig === '3x3') $modulesPerDalle = 9;
                    else if ($modulesConfig === '4x4') $modulesPerDalle = 16;
                    
                    // Stocker la configuration 1
                    $validated['config1'] = [
                        'nb_flightcases' => $nbFC,
                        'nb_dalles' => $nbDalles,
                        'modules_config' => $modulesConfig,
                        'modules_per_dalle' => $modulesPerDalle,
                        'dalle_type_id' => 0 // Type 500x500
                    ];
                }
                
                // Configuration 2 - Dalles 500x1000
                if (isset($request->config2) && $request->config2['nb_flightcases'] > 0) {
                    $config2 = $request->config2;
                    $nbFC = intval($config2['nb_flightcases']);
                    $nbDalles = intval($config2['nb_dalles']);
                    $modulesConfig = $config2['modules_config'];
                    
                    // Calculer le nombre de modules par dalle selon la configuration
                    $modulesPerDalle = 8; // Par défaut 2x4
                    if ($modulesConfig === '1x2') $modulesPerDalle = 2;
                    else if ($modulesConfig === '2x2') $modulesPerDalle = 4;
                    else if ($modulesConfig === '2x4') $modulesPerDalle = 8;
                    
                    // Stocker la configuration 2
                    $validated['config2'] = [
                        'nb_flightcases' => $nbFC,
                        'nb_dalles' => $nbDalles,
                        'modules_config' => $modulesConfig,
                        'modules_per_dalle' => $modulesPerDalle,
                        'dalle_type_id' => 1 // Type 500x1000
                    ];
                }
                
                // Indiquer que le mode tailles multiples est activé
                $validated['has_multiple_sizes'] = true;
            } else {
                // Mode standard (un seul type de dalle) - ancienne logique
                
                // Récupérer les types de dalles pour chaque flightcase
                if (isset($request->flights)) {
                    foreach ($request->flights as $fcIndex => $fcData) {
                        $dalleTypeId = $fcData['dalle_type_id'] ?? 0;
                        $modulesConfig = $fcData['modules_config'] ?? null;
                        $validated['flightcases_details'][$fcIndex] = [
                            'dalle_type_id' => $dalleTypeId,
                            'modules_config' => $modulesConfig
                        ];
                    }
                }
                
                // Gérer les flightcases partiels
                if (isset($request->fc_partiel)) {
                    foreach ($request->fc_partiel as $fcIndex => $isPartial) {
                        if ($isPartial) {
                            $nbDalles = $request->fc_nb_dalles[$fcIndex] ?? $validated['nb_dalles_par_flightcase'];
                            if (!isset($validated['flightcases_details'][$fcIndex])) {
                                $validated['flightcases_details'][$fcIndex] = [];
                            }
                            $validated['flightcases_details'][$fcIndex]['is_partial'] = true;
                            $validated['flightcases_details'][$fcIndex]['nb_dalles'] = $nbDalles;
                        }
                    }
                }
                
                // Indiquer que le mode tailles multiples n'est pas activé
                $validated['has_multiple_sizes'] = false;
            }
        }

        // Stocker les données validées en session pour l'étape 3
        session(['chantier_data_step3' => $validated]);

        // Rediriger vers l'étape 4
        return redirect()->route('chantiers.create.step4');
    }

    /**
     * Afficher le formulaire de création - Étape 4 : Planification des interventions
     */
    public function createStep4()
    {
        // Vérifier si les étapes précédentes ont été complétées
        if (!session()->has('chantier_data_step1') || !session()->has('chantier_data_step2') || !session()->has('chantier_data_step3')) {
            return redirect()->route('chantiers.create.step1')
                ->with('error', 'Veuillez compléter les étapes précédentes.');
        }
        
        // Récupérer les techniciens disponibles (utilisateurs ayant le rôle technicien)
        $techniciens = User::where('role', 'technicien')->orWhere('role', 'admin')->get();
        
        return view('chantiers.create_step4', compact('techniciens'));
    }

    /**
     * Traiter l'étape 4 et passer à l'étape 5
     */
    public function storeStep4(Request $request)
    {
        $validated = $request->validate([
            'planifier_interventions' => 'required|boolean',
            'technicien_id' => 'nullable|exists:users,id',
            'date_debut_interventions' => 'nullable|required_if:planifier_interventions,1|date|after_or_equal:today',
        ]);
        
        // Stocker les données validées en session pour l'étape 4
        session(['chantier_data_step4' => $validated]);
        
        // Rediriger vers l'étape 5
        return redirect()->route('chantiers.create.step5');
    }

    /**
     * Afficher le formulaire de création - Étape 5 : Récapitulatif et édition de rapports
     */
    public function createStep5()
    {
        // Vérifier si les étapes précédentes ont été complétées
        if (!session()->has('chantier_data_step1') || !session()->has('chantier_data_step2') || 
            !session()->has('chantier_data_step3') || !session()->has('chantier_data_step4')) {
            return redirect()->route('chantiers.create.step1')
                ->with('error', 'Veuillez compléter les étapes précédentes.');
        }
        
        // Récupérer toutes les données des étapes précédentes
        $step1Data = session('chantier_data_step1');
        $step2Data = session('chantier_data_step2');
        $step3Data = session('chantier_data_step3');
        $step4Data = session('chantier_data_step4');
        
        // Récupérer le client
        $client = Client::find($step1Data['client_id']);
        
        // Récupérer le produit de référence si sélectionné depuis le catalogue
        $produitRef = null;
        if ($step2Data['selection_type'] == 'existant' && isset($step2Data['produit_catalogue_id'])) {
            $produitRef = ProduitCatalogue::find($step2Data['produit_catalogue_id']);
        }
        
        // Technicien assigné si des interventions sont planifiées
        $technicienAssigne = null;
        if ($step4Data['planifier_interventions'] && isset($step4Data['technicien_id'])) {
            $technicienAssigne = User::find($step4Data['technicien_id']);
        }
        
        return view('chantiers.create_step5', compact('client', 'produitRef', 'technicienAssigne', 
            'step1Data', 'step2Data', 'step3Data', 'step4Data'));
    }

    /**
     * Finaliser la création du chantier - Étape 5
     */
    public function storeStep5(Request $request)
    {
        // Récupérer les données des étapes précédentes
        $step1Data = session('chantier_data_step1');
        $step2Data = session('chantier_data_step2');
        $step3Data = session('chantier_data_step3');
        $step4Data = session('chantier_data_step4');
        
        // Créer le chantier
        $client = Client::find($step1Data['client_id']);
        $societe = $client->societe ? $client->societe : $client->nom_complet;
        $date = date('d/m/Y');
        $reference = Chantier::genererReference();
        
        // Marque du produit (si disponible)
        $marque = '';
        if (isset($step2Data['marque']) && !empty($step2Data['marque'])) {
            $marque = $step2Data['marque'];
        } elseif (isset($step2Data['produit_catalogue_id'])) {
            $produitRef = ProduitCatalogue::find($step2Data['produit_catalogue_id']);
            if ($produitRef) {
                $marque = $produitRef->marque;
            }
        }
        
        // Générer un nom plus professionnel
        $nom = "SAV {$societe}";
        // Éviter la redondance si la marque contient déjà le nom de la société
        if ($marque && stripos($marque, $societe) === false) {
            $nom .= " - {$marque}";
        }
        // Ajouter la référence pour faciliter l'identification
        $nom .= " ({$reference})";
        
        $chantier = Chantier::create([
            'client_id' => $step1Data['client_id'],
            'nom' => $nom,
            'description' => $step1Data['description'],
            'date_reception' => $step1Data['date_reception'],
            'date_butoir' => $step1Data['date_butoir'],
            'etat' => $step1Data['etat'],
            'reference' => $reference,
            'token_suivi' => md5(uniqid() . time()),
            'is_client_achat' => isset($step2Data['is_client_achat']) ? $step2Data['is_client_achat'] : false,
            'is_under_warranty' => isset($step2Data['is_under_warranty']) ? $step2Data['is_under_warranty'] : false,
            'warranty_end_date' => isset($step2Data['warranty_end_date']) ? $step2Data['warranty_end_date'] : null,
            'warranty_type' => isset($step2Data['warranty_type']) ? $step2Data['warranty_type'] : null,
        ]);

        // Créer le produit
        if ($step2Data['selection_type'] == 'existant') {
            $produitRef = ProduitCatalogue::find($step2Data['produit_catalogue_id']);
            
            // Ajouter un log pour voir les valeurs
            \Log::info('Données du produit catalogue:', [
                'produitRef' => $produitRef ? 'trouvé' : 'non trouvé',
                'id' => $step2Data['produit_catalogue_id'] ?? 'non défini',
                'marque' => $produitRef->marque ?? 'non défini',
                'modele' => $produitRef->modele ?? 'non défini'
            ]);
            
            // Créer le produit sans les champs problématiques avec valeurs par défaut
            // Utiliser des valeurs par défaut significatives au lieu de INCONNU
            $defaultMarque = $client->societe ? strtoupper($client->societe) : 'Écran LED';
            $defaultModele = 'Réparation ' . date('Y');
            
            $produit = Produit::create([
                'chantier_id' => $chantier->id,
                'marque' => $produitRef->marque ?? $defaultMarque,
                'modele' => $produitRef->modele ?? $defaultModele,
                'pitch' => $produitRef->pitch ?? 3.0,
                'utilisation' => $produitRef->utilisation ?? 'indoor',
                'electronique' => $produitRef->electronique ?? 'autre',
                'electronique_detail' => $produitRef->electronique_detail ?? 'Non spécifié',
                'led_datasheet_id' => $step2Data['led_datasheet_id'] ?? null,
            ]);
            
            // Mettre à jour les champs problématiques avec une requête SQL brute
            if ($produitRef && !empty($produitRef->bain_couleur)) {
                try {
                    \DB::statement("UPDATE produits SET bain_couleur = ? WHERE id = ?", [
                        $produitRef->bain_couleur, 
                        $produit->id
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de la mise à jour du bain_couleur: ' . $e->getMessage());
                }
            }
            
            if (isset($step2Data['hub']) && !empty($step2Data['hub'])) {
                try {
                    \DB::statement("UPDATE produits SET hub = ? WHERE id = ?", [
                        $step2Data['hub'], 
                        $produit->id
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de la mise à jour du hub: ' . $e->getMessage());
                }
            }
        } else {
            // Ajouter un log pour voir les valeurs
            \Log::info('Données du produit avant création:', [
                'marque' => $step2Data['marque'] ?? 'non défini',
                'modele' => $step2Data['modele'] ?? 'non défini',
                'pitch' => $step2Data['pitch'] ?? 'non défini',
                'utilisation' => $step2Data['utilisation'] ?? 'non défini',
                'step2Data complet' => $step2Data
            ]);
            
            // Créer le produit avec valeurs par défaut si nécessaire
            // Utiliser des valeurs par défaut significatives au lieu de INCONNU
            $defaultMarque = $client->societe ? strtoupper($client->societe) : 'Écran LED';
            $defaultModele = 'Réparation ' . date('Y');
            
            $produit = Produit::create([
                'chantier_id' => $chantier->id,
                'marque' => $step2Data['marque'] ?? $defaultMarque,
                'modele' => $step2Data['modele'] ?? $defaultModele,
                'pitch' => $step2Data['pitch'] ?? 3.0,
                'utilisation' => $step2Data['utilisation'] ?? 'indoor',
                'electronique' => $step2Data['electronique'] ?? 'autre',
                'electronique_detail' => $step2Data['electronique_detail'] ?? 'Non spécifié',
                'carte_reception' => $step2Data['carte_reception'] ?? null,
                'hub' => $step2Data['hub'] ?? null,
                'bain_couleur' => $step2Data['bain_couleur'] ?? null,
                'led_datasheet_id' => $step2Data['led_datasheet_id'] ?? null,
            ]);
            
            // Comme nous avons déjà inclus tous les champs dans le create(), nous n'avons plus besoin de ces mises à jour
        }

        // Créer les dalles et modules
        $modulesCreated = [];
        
        // Récupérer les dimensions des dalles et modules depuis l'étape 2
        $largeurDalle = $step2Data['largeur_dalle'] ?? 500;
        $hauteurDalle = $step2Data['hauteur_dalle'] ?? 500;
        $largeurModule = $step2Data['largeur_module'] ?? 250;
        $hauteurModule = $step2Data['hauteur_module'] ?? 250;
        $alimentation = $step2Data['alimentation'] ?? 'Standard 5V';
        
        // Récupérer la disposition des modules (si disponible dans l'étape 2)
        $modulesLargeur = $step2Data['modules_largeur'] ?? 2;
        $modulesHauteur = $step2Data['modules_hauteur'] ?? 2;
        
        if ($step3Data['mode'] == 'flightcase') {
            // Définir les types de dalles disponibles
            $dalleTypes = [
                [
                    'id' => 0,
                    'nom' => 'Dalle 500x500',
                    'largeur' => 500,
                    'hauteur' => 500,
                    'disposition' => $step2Data['disposition_modules'] ?? '2x2',
                ],
                [
                    'id' => 1,
                    'nom' => 'Dalle 500x1000',
                    'largeur' => 500,
                    'hauteur' => 1000,
                    'disposition' => $step2Data['disposition_modules'] ?? '2x2',
                ]
            ];
            
            // Vérifier si le mode tailles multiples est activé
            if (isset($step3Data['has_multiple_sizes']) && $step3Data['has_multiple_sizes']) {
                \Log::info('Traitement en mode tailles multiples');
                
                // Traiter la configuration 1 (500x500)
                if (isset($step3Data['config1']) && $step3Data['config1']['nb_flightcases'] > 0) {
                    $config1 = $step3Data['config1'];
                    $nbFC = $config1['nb_flightcases'];
                    $nbDalles = $config1['nb_dalles'];
                    $modulesConfig = $config1['modules_config'];
                    $modulesPerDalle = $config1['modules_per_dalle'];
                    
                    // Extraire les dimensions de la configuration (format: "2x2")
                    if (preg_match('/^(\d+)x(\d+)$/', $modulesConfig, $matches)) {
                        $modulesLargeur = intval($matches[1]);
                        $modulesHauteur = intval($matches[2]);
                    }
                    
                    // Traiter chaque flight case de cette configuration
                    for ($f = 1; $f <= $nbFC; $f++) {
                        $flightCaseId = "FC1-{$f}"; // Identifier le flight case comme type 1 (500x500)
                        
                        // Traiter chaque dalle de ce flight case
                        for ($d = 1; $d <= $nbDalles; $d++) {
                            $dalleId = "{$flightCaseId}-D{$d}";
                            
                            // Créer la dalle
                            $dalle = Dalle::create([
                                'produit_id' => $produit->id,
                                'largeur' => 500, // 500x500
                                'hauteur' => 500,
                                'nb_modules' => $modulesPerDalle,
                                'alimentation' => $alimentation,
                                'carte_reception' => $step3Data['carte_reception'] ?? $step2Data['carte_reception'] ?? null,
                                'hub' => $step3Data['hub'] ?? $step2Data['hub'] ?? null,
                                'reference_dalle' => $dalleId,
                                'disposition_modules' => $modulesConfig
                            ]);
                            
                            // Créer les modules pour cette dalle
                            for ($m = 1; $m <= $modulesPerDalle; $m++) {
                                $row = ceil($m / $modulesLargeur);
                                $col = (($m - 1) % $modulesLargeur) + 1;
                                
                                // Calculer les dimensions du module
                                $currentLargeurModule = 500 / $modulesLargeur;
                                $currentHauteurModule = 500 / $modulesHauteur;
                                
                                $module = Module::create([
                                    'dalle_id' => $dalle->id,
                                    'largeur' => $currentLargeurModule,
                                    'hauteur' => $currentHauteurModule,
                                    'nb_pixels_largeur' => $step2Data['nb_pixels_largeur'] ?? 100,
                                    'nb_pixels_hauteur' => $step2Data['nb_pixels_hauteur'] ?? 100,
                                    'driver' => $step2Data['driver'] ?? null,
                                    'shift_register' => $step2Data['shift_register'] ?? null,
                                    'buffer' => $step2Data['buffer'] ?? null,
                                    'etat' => 'non_commence',
                                    'reference_module' => "{$dalleId}-M{$m}-R{$row}C{$col}",
                                    'position_x' => $col,
                                    'position_y' => $row
                                ]);
                                
                                $modulesCreated[] = $module;
                            }
                        }
                    }
                }
                
                // Traiter la configuration 2 (500x1000)
                if (isset($step3Data['config2']) && $step3Data['config2']['nb_flightcases'] > 0) {
                    $config2 = $step3Data['config2'];
                    $nbFC = $config2['nb_flightcases'];
                    $nbDalles = $config2['nb_dalles'];
                    $modulesConfig = $config2['modules_config'];
                    $modulesPerDalle = $config2['modules_per_dalle'];
                    
                    // Extraire les dimensions de la configuration (format: "2x4")
                    if (preg_match('/^(\d+)x(\d+)$/', $modulesConfig, $matches)) {
                        $modulesLargeur = intval($matches[1]);
                        $modulesHauteur = intval($matches[2]);
                    }
                    
                    // Traiter chaque flight case de cette configuration
                    for ($f = 1; $f <= $nbFC; $f++) {
                        $flightCaseId = "FC2-{$f}"; // Identifier le flight case comme type 2 (500x1000)
                        
                        // Traiter chaque dalle de ce flight case
                        for ($d = 1; $d <= $nbDalles; $d++) {
                            $dalleId = "{$flightCaseId}-D{$d}";
                            
                            // Créer la dalle
                            $dalle = Dalle::create([
                                'produit_id' => $produit->id,
                                'largeur' => 500, // 500x1000
                                'hauteur' => 1000,
                                'nb_modules' => $modulesPerDalle,
                                'alimentation' => $alimentation,
                                'carte_reception' => $step3Data['carte_reception'] ?? $step2Data['carte_reception'] ?? null,
                                'hub' => $step3Data['hub'] ?? $step2Data['hub'] ?? null,
                                'reference_dalle' => $dalleId,
                                'disposition_modules' => $modulesConfig
                            ]);
                            
                            // Créer les modules pour cette dalle
                            for ($m = 1; $m <= $modulesPerDalle; $m++) {
                                $row = ceil($m / $modulesLargeur);
                                $col = (($m - 1) % $modulesLargeur) + 1;
                                
                                // Calculer les dimensions du module
                                $currentLargeurModule = 500 / $modulesLargeur;
                                $currentHauteurModule = 1000 / $modulesHauteur;
                                
                                $module = Module::create([
                                    'dalle_id' => $dalle->id,
                                    'largeur' => $currentLargeurModule,
                                    'hauteur' => $currentHauteurModule,
                                    'nb_pixels_largeur' => $step2Data['nb_pixels_largeur'] ?? 100,
                                    'nb_pixels_hauteur' => $step2Data['nb_pixels_hauteur'] ?? 100,
                                    'driver' => $step2Data['driver'] ?? null,
                                    'shift_register' => $step2Data['shift_register'] ?? null,
                                    'buffer' => $step2Data['buffer'] ?? null,
                                    'etat' => 'non_commence',
                                    'reference_module' => "{$dalleId}-M{$m}-R{$row}C{$col}",
                                    'position_x' => $col,
                                    'position_y' => $row
                                ]);
                                
                                $modulesCreated[] = $module;
                            }
                        }
                    }
                }
                
            } else {
                // Mode standard (un seul type de dalle)
                // Obtenir les détails des FlightCases
                $flightcasesDetails = $step3Data['flightcases_details'] ?? [];
                
                // Ajouter un log pour le débogage
                \Log::info('Traitement FlightCase mode standard:', [
                    'flightcases_details' => $flightcasesDetails,
                ]);
                
                for ($f = 1; $f <= $step3Data['nb_flightcases']; $f++) {
                    // Déterminer combien de dalles à créer pour ce FlightCase
                    $nbDallesForThisFC = $step3Data['nb_dalles_par_flightcase'];
                    $fcDetails = $flightcasesDetails[$f] ?? null;
                    
                    if ($fcDetails && isset($fcDetails['is_partial']) && $fcDetails['is_partial']) {
                        $nbDallesForThisFC = $fcDetails['nb_dalles'];
                        \Log::info("FlightCase {$f} est partiel, création de {$nbDallesForThisFC} dalles au lieu de {$step3Data['nb_dalles_par_flightcase']}");
                    }
                    
                    // Déterminer les dimensions de dalles en fonction du type sélectionné
                    $dalleTypeId = $fcDetails['dalle_type_id'] ?? 0;
                    
                    // Rechercher le type de dalle correspondant
                    $currentDalleType = null;
                    foreach ($dalleTypes as $type) {
                        if ($type['id'] == $dalleTypeId) {
                            $currentDalleType = $type;
                            break;
                        }
                    }
                    
                    // Si on n'a pas trouvé le type, on utilise les dimensions par défaut
                    if ($currentDalleType) {
                        $currentLargeurDalle = $currentDalleType['largeur'];
                        $currentHauteurDalle = $currentDalleType['hauteur'];
                        $currentDispositionModules = $currentDalleType['disposition'];
                        \Log::info("FlightCase {$f} utilise dalle type {$currentDalleType['nom']} ({$currentLargeurDalle}x{$currentHauteurDalle}mm)");
                    } else {
                        $currentLargeurDalle = $largeurDalle;
                        $currentHauteurDalle = $hauteurDalle;
                        $currentDispositionModules = $step2Data['disposition_modules'] ?? '2x2';
                    }
                    
                    for ($d = 1; $d <= $nbDallesForThisFC; $d++) {
                        $dalleId = "FC{$f}-D{$d}";
                        
                        // Créer la dalle avec les dimensions du type sélectionné
                        $dalle = Dalle::create([
                            'produit_id' => $produit->id,
                            'largeur' => $currentLargeurDalle,
                            'hauteur' => $currentHauteurDalle,
                            'nb_modules' => $step3Data['nb_modules_par_dalle'],
                            'alimentation' => $alimentation,
                            'carte_reception' => $step3Data['carte_reception'] ?? $step2Data['carte_reception'] ?? null,
                            'hub' => $step3Data['hub'] ?? $step2Data['hub'] ?? null,
                            'reference_dalle' => $dalleId,
                            'disposition_modules' => $currentDispositionModules
                        ]);
                        
                        // Stocker la disposition des modules comme métadonnées dans la session
                        if (!empty($step2Data['disposition_modules'])) {
                            session([
                                'disposition_modules_dalle_' . $dalle->id => $step2Data['disposition_modules']
                            ]);
                        }

                        // Créer les modules en tenant compte de la disposition
                        $totalModules = $step3Data['nb_modules_par_dalle'];
                        $moduleIndex = 1;
                        
                        // Si la disposition est définie, on organise les modules selon cette disposition
                        if (isset($step2Data['disposition_modules'])) {
                            // Vérifier si une configuration spéciale des modules a été définie pour ce flightcase
                            $customModuleConfig = null;
                            $fcDetails = $flightcasesDetails[$f] ?? null;
                            if ($fcDetails && isset($fcDetails['modules_config'])) {
                                $customModuleConfig = $fcDetails['modules_config'];
                                \Log::info("FlightCase {$f} utilise une configuration de modules personnalisée: {$customModuleConfig}");
                            }
                            
                            // Si une configuration personnalisée est définie ET que c'est une dalle 500x1000
                            if ($customModuleConfig && $currentLargeurDalle == 500 && $currentHauteurDalle == 1000) {
                                // Extraire les dimensions x et y de la configuration (format: "2x4", "1x2", etc.)
                                if (preg_match('/^(\d+)x(\d+)$/', $customModuleConfig, $matches)) {
                                    $modulesLargeur = intval($matches[1]);
                                    $modulesHauteur = intval($matches[2]);
                                    \Log::info("Configuration personnalisée: {$modulesLargeur}x{$modulesHauteur} pour dalle {$currentLargeurDalle}x{$currentHauteurDalle}");
                                }
                            }
                            
                            // Calculer la capacité de la grille
                            $gridCapacity = $modulesLargeur * $modulesHauteur;
                            
                            // Créer les modules qui peuvent s'adapter à la grille
                            for ($row = 1; $row <= $modulesHauteur && $moduleIndex <= $totalModules; $row++) {
                                for ($col = 1; $col <= $modulesLargeur && $moduleIndex <= $totalModules; $col++) {
                                    // Calculer les dimensions du module en fonction du type de dalle
                                    $currentLargeurModule = $largeurModule;
                                    $currentHauteurModule = $hauteurModule;
                                    
                                    // Si la dalle est de type 500x1000, adapter les modules en conséquence
                                    if ($currentLargeurDalle == 500 && $currentHauteurDalle == 1000) {
                                        // Si on a une configuration personnalisée, l'utiliser pour calculer les dimensions
                                        if ($customModuleConfig) {
                                            $currentLargeurModule = $currentLargeurDalle / $modulesLargeur;
                                            $currentHauteurModule = $currentHauteurDalle / $modulesHauteur;
                                        }
                                        // Sinon, utiliser la disposition standard
                                        else if (preg_match('/^(\d+)x(\d+)$/', $currentDispositionModules, $matches)) {
                                            $modLargeur = intval($matches[1]);
                                            $modHauteur = intval($matches[2]);
                                            
                                            // Calculer les dimensions en fonction de la disposition
                                            $currentLargeurModule = $currentLargeurDalle / $modLargeur;
                                            $currentHauteurModule = $currentHauteurDalle / $modHauteur;
                                        }
                                    }
                                    
                                    $module = Module::create([
                                        'dalle_id' => $dalle->id,
                                        'largeur' => $currentLargeurModule,
                                        'hauteur' => $currentHauteurModule,
                                        'nb_pixels_largeur' => $step2Data['nb_pixels_largeur'] ?? 100,
                                        'nb_pixels_hauteur' => $step2Data['nb_pixels_hauteur'] ?? 100,
                                        'driver' => $step2Data['driver'] ?? null,
                                        'shift_register' => $step2Data['shift_register'] ?? null,
                                        'buffer' => $step2Data['buffer'] ?? null,
                                        'etat' => 'non_commence',
                                        'reference_module' => "FC{$f}-D{$d}-R{$row}C{$col}",
                                        'position_x' => $col,
                                        'position_y' => $row
                                    ]);
                                    
                                    $modulesCreated[] = $module;
                                    $moduleIndex++;
                                }
                            }
                            
                            // Si on a demandé plus de modules que la grille peut contenir, ajouter les modules supplémentaires 
                            while ($moduleIndex <= $totalModules) {
                                $module = Module::create([
                                    'dalle_id' => $dalle->id,
                                    'largeur' => $largeurModule,
                                    'hauteur' => $hauteurModule,
                                    'nb_pixels_largeur' => $step2Data['nb_pixels_largeur'] ?? 100,
                                    'nb_pixels_hauteur' => $step2Data['nb_pixels_hauteur'] ?? 100,
                                    'driver' => $step2Data['driver'] ?? null,
                                    'shift_register' => $step2Data['shift_register'] ?? null,
                                    'buffer' => $step2Data['buffer'] ?? null,
                                    'etat' => 'non_commence',
                                    'reference_module' => "FC{$f}-D{$d}-EXTRA-" . ($moduleIndex - $gridCapacity)
                                ]);
                                
                                $modulesCreated[] = $module;
                                $moduleIndex++;
                            }
                            } else {
                                // Disposition linéaire par défaut
                                for ($m = 1; $m <= $totalModules; $m++) {
                                    $module = Module::create([
                                        'dalle_id' => $dalle->id,
                                        'largeur' => $largeurModule,
                                        'hauteur' => $hauteurModule,
                                        'nb_pixels_largeur' => $step2Data['nb_pixels_largeur'] ?? 100,
                                        'nb_pixels_hauteur' => $step2Data['nb_pixels_hauteur'] ?? 100,
                                        'driver' => $step2Data['driver'] ?? null,
                                        'shift_register' => $step2Data['shift_register'] ?? null,
                                        'buffer' => $step2Data['buffer'] ?? null,
                                        'etat' => 'non_commence',
                                        'reference_module' => "FC{$f}-D{$d}-M{$m}"
                                    ]);
                                    
                                    $modulesCreated[] = $module;
                                }
                            }
                        }
                    }
                }
            }
        
        // Si le mode n'est pas flightcase, c'est le mode individuel
        if ($step3Data['mode'] == 'individuel') {
            // Création de modules individuels - toujours en mode linéaire, sans tenir compte de la disposition
            $dalle = Dalle::create([
                'produit_id' => $produit->id,
                'largeur' => $largeurDalle,
                'hauteur' => $hauteurDalle,
                'nb_modules' => $step3Data['nb_modules_total'],
                'alimentation' => $alimentation,
                'carte_reception' => $step2Data['carte_reception'] ?? null,
                'hub' => $step2Data['hub'] ?? null,
                'reference_dalle' => "INDIVIDUEL"
            ]);
            
            // En mode modules individuels, on crée TOUJOURS tous les modules de façon linéaire
            // sans tenir compte d'une éventuelle disposition définie précédemment
            for ($m = 1; $m <= $step3Data['nb_modules_total']; $m++) {
                // Permettre différentes dimensions en fonction du type de dalle sélectionné
                $currentLargeurModule = $largeurModule;
                $currentHauteurModule = $hauteurModule;
                
                // Si un type de dalle spécifique est mentionné, adapter les dimensions
                if (isset($step3Data['module_dalle_type']) && $step3Data['module_dalle_type'] == 'large') {
                    $currentLargeurModule = $currentLargeurModule * 2;
                    $currentHauteurModule = $currentHauteurModule * 2;
                } else if (isset($step3Data['module_dimensions']) && is_array($step3Data['module_dimensions'])) {
                    // Format personnalisé pour les dimensions de module
                    $currentLargeurModule = $step3Data['module_dimensions']['largeur'] ?? $largeurModule;
                    $currentHauteurModule = $step3Data['module_dimensions']['hauteur'] ?? $hauteurModule;
                }
                
                $module = Module::create([
                    'dalle_id' => $dalle->id,
                    'largeur' => $currentLargeurModule,
                    'hauteur' => $currentHauteurModule, 
                    'nb_pixels_largeur' => $step2Data['nb_pixels_largeur'] ?? 64,
                    'nb_pixels_hauteur' => $step2Data['nb_pixels_hauteur'] ?? 64,
                    'driver' => $step2Data['driver'] ?? null,
                    'shift_register' => $step2Data['shift_register'] ?? null,
                    'buffer' => $step2Data['buffer'] ?? null,
                    'etat' => 'non_commence',
                    'reference_module' => "IND-{$m}"
                ]);
                
                $modulesCreated[] = $module;
            }
            
            // Nous n'avons plus besoin des blocs conditionnels, tout le code nécessaire
            // a été exécuté ci-dessus
        }
        
        // Force disable auto-intervention creation
        $step4Data['planifier_interventions'] = false;
        
        // Si l'utilisateur a choisi de planifier des interventions
        if ($step4Data['planifier_interventions']) {
            $technicienId = $step4Data['technicien_id'] ?? null; // Technicien optionnel
            $dateDebut = $step4Data['date_debut_interventions'];
            
            // Créer des interventions préliminaires pour chaque module
            foreach ($modulesCreated as $module) {
                $intervention = new Intervention();
                $intervention->module_id = $module->id;
                $intervention->technicien_id = $technicienId; // Peut être null
                $intervention->date_debut = $dateDebut;
                $intervention->temps_total = 0;
                $intervention->is_completed = false;
                $intervention->save();
                
                // Créer un diagnostic vide
                $diagnostic = new Diagnostic();
                $diagnostic->intervention_id = $intervention->id;
                $diagnostic->nb_leds_hs = 0;
                $diagnostic->nb_ic_hs = 0;
                $diagnostic->nb_masques_hs = 0;
                $diagnostic->save();
                
                // Créer une réparation vide
                $reparation = new Reparation();
                $reparation->intervention_id = $intervention->id;
                $reparation->nb_leds_remplacees = 0;
                $reparation->nb_ic_remplaces = 0;
                $reparation->nb_masques_remplaces = 0;
                $reparation->save();
            }
        }
        
        // Générer le rapport si demandé
        $generateReport = $request->has('generate_report') && $request->input('generate_report');
        
        // Nettoyer la session
        session()->forget(['chantier_data_step1', 'chantier_data_step2', 'chantier_data_step3', 'chantier_data_step4']);
        
        // Envoyer un email au client pour l'informer de la création du chantier
        try {
            $emailService = new \App\Services\EmailService();
            $emailService->sendChantierCreatedEmail($chantier);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de création de chantier: ' . $e->getMessage());
        }
        
        if ($generateReport) {
            return redirect()->route('rapports.chantier', $chantier->id);
        } else {
            return redirect()->route('chantiers.show', $chantier)
                ->with('success', 'Chantier créé avec succès !');
        }
    }
}