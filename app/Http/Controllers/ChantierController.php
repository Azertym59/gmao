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
    public function index()
    {
        $chantiers = Chantier::with('client')->get();
        return view('chantiers.index', compact('chantiers'));
    }

    /**
     * Afficher le formulaire de création (ancienne méthode, maintenant redirige vers étape 1)
     */
    public function create(Request $request)
    {
        return redirect()->route('chantiers.create.step1');
    }

    /**
     * Stocker un nouveau chantier
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'date_reception' => 'required|date',
            'date_butoir' => 'required|date|after_or_equal:date_reception',
            'etat' => 'required|in:non_commence,en_cours,termine',
        ]);
        
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
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'date_reception' => 'required|date',
            'date_butoir' => 'required|date|after_or_equal:date_reception',
            'etat' => 'required|in:non_commence,en_cours,termine',
        ]);
        
        // Si le client a changé, mettre à jour le nom du chantier
        if ($chantier->client_id != $validated['client_id']) {
            $client = Client::find($validated['client_id']);
            $societe = $client->societe ? $client->societe : $client->nom_complet;
            $date = date('d/m/Y');
            $chantier->nom = "Réparation écran - {$societe} - {$date}";
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
        // Récupérer tous les clients pour la liste déroulante
        $clients = Client::all();
        
        return view('chantiers.create_step1', compact('clients'));
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
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
            'date_reception' => 'required|date',
            'date_butoir' => 'required|date|after_or_equal:date_reception',
            'etat' => 'required|in:non_commence,en_cours,termine',
        ]);

        // Stocker les données en session
        session(['chantier_data_step1' => $validated]);

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
        
        return view('chantiers.create_step2_product', compact('produitsCatalogue', 'client'));
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
            'led_size' => 'nullable|string',
            'led_pads' => 'nullable|string',
            'led_rotation' => 'nullable|string',
            'led_datasheet_name' => 'nullable|string',
            'led_datasheet_image' => 'nullable|string',
        ]);

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
                'largeur_dalle' => $validated['largeur_dalle'],
                'hauteur_dalle' => $validated['hauteur_dalle'],
                'largeur_module' => $validated['largeur_module'],
                'hauteur_module' => $validated['hauteur_module'],
                'disposition_modules' => $validated['disposition_modules'],
            ]);

            // Si la disposition est personnalisée, ajouter les dimensions personnalisées
            // Accepter 'personnalise' ou 'custom' pour la compatibilité
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

        $produitData = session('chantier_data_step2');
        $produitRef = null;

        if ($produitData['selection_type'] == 'existant' && isset($produitData['produit_catalogue_id'])) {
            $produitRef = ProduitCatalogue::find($produitData['produit_catalogue_id']);
        }

        return view('chantiers.create_step3', compact('produitRef', 'produitData'));
    }

    /**
     * Traiter l'étape 3 et passer à l'étape 4 (interventions)
     */
    public function storeStep3(Request $request)
    {
        $validated = $request->validate([
            'mode' => 'required|in:flightcase,individuel',
            'nb_flightcases' => 'required_if:mode,flightcase|integer|min:1',
            'nb_dalles_par_flightcase' => 'required_if:mode,flightcase|integer|min:1',
            'nb_modules_par_dalle' => 'required_if:mode,flightcase|integer|min:1',
            'nb_modules_total' => 'required_if:mode,individuel|integer|min:1',
        ]);

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
        
        $chantier = Chantier::create([
            'client_id' => $step1Data['client_id'],
            'nom' => "Réparation écran - {$societe} - {$date}",
            'description' => $step1Data['description'],
            'date_reception' => $step1Data['date_reception'],
            'date_butoir' => $step1Data['date_butoir'],
            'etat' => $step1Data['etat'],
            'reference' => Chantier::genererReference(),
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
            $produit = Produit::create([
                'chantier_id' => $chantier->id,
                'marque' => $produitRef->marque ?? 'INCONNU',
                'modele' => $produitRef->modele ?? 'INCONNU',
                'pitch' => $produitRef->pitch ?? 3.0,
                'utilisation' => $produitRef->utilisation ?? 'indoor',
                'electronique' => $produitRef->electronique ?? 'autre',
                'electronique_detail' => $produitRef->electronique_detail ?? 'Non spécifié',
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
            $produit = Produit::create([
                'chantier_id' => $chantier->id,
                'marque' => $step2Data['marque'] ?? 'INCONNU',
                'modele' => $step2Data['modele'] ?? 'INCONNU',
                'pitch' => $step2Data['pitch'] ?? 3.0,
                'utilisation' => $step2Data['utilisation'] ?? 'indoor',
                'electronique' => $step2Data['electronique'] ?? 'autre',
                'electronique_detail' => $step2Data['electronique_detail'] ?? 'Non spécifié',
            ]);
            
            // Mettre à jour les champs problématiques avec une requête SQL brute
            if (isset($step2Data['bain_couleur']) && !empty($step2Data['bain_couleur'])) {
                try {
                    \DB::statement("UPDATE produits SET bain_couleur = ? WHERE id = ?", [
                        $step2Data['bain_couleur'], 
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
            for ($f = 1; $f <= $step3Data['nb_flightcases']; $f++) {
                for ($d = 1; $d <= $step3Data['nb_dalles_par_flightcase']; $d++) {
                    // Créer la dalle sans le champ disposition_modules problématique
                    $dalle = Dalle::create([
                        'produit_id' => $produit->id,
                        'largeur' => $largeurDalle,
                        'hauteur' => $hauteurDalle,
                        'nb_modules' => $step3Data['nb_modules_par_dalle'],
                        'alimentation' => $alimentation,
                        'carte_reception' => $step3Data['carte_reception'] ?? $step2Data['carte_reception'] ?? null,
                        'hub' => $step3Data['hub'] ?? $step2Data['hub'] ?? null,
                        'reference_dalle' => "FC{$f}-D{$d}"
                    ]);
                    
                    // Stocker la disposition des modules comme métadonnées dans la session
                    // pour utilisation ultérieure si nécessaire
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
                        // Calculer le nombre de modules en fonction de la disposition
                        $totalModules = $modulesLargeur * $modulesHauteur;
                        
                        for ($row = 1; $row <= $modulesHauteur; $row++) {
                            for ($col = 1; $col <= $modulesLargeur; $col++) {
                                if ($moduleIndex <= $totalModules) {
                                    $module = Module::create([
                                        'dalle_id' => $dalle->id,
                                        'largeur' => $largeurModule,
                                        'hauteur' => $hauteurModule,
                                        'nb_pixels_largeur' => $step2Data['nb_pixels_largeur'] ?? 64,
                                        'nb_pixels_hauteur' => $step2Data['nb_pixels_hauteur'] ?? 64,
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
                        }
                    } else {
                        // Disposition linéaire par défaut
                        for ($m = 1; $m <= $totalModules; $m++) {
                            $module = Module::create([
                                'dalle_id' => $dalle->id,
                                'largeur' => $largeurModule,
                                'hauteur' => $hauteurModule,
                                'nb_pixels_largeur' => $step2Data['nb_pixels_largeur'] ?? 64,
                                'nb_pixels_hauteur' => $step2Data['nb_pixels_hauteur'] ?? 64,
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
        } else {
            // Création de modules individuels sans le champ disposition_modules problématique
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
            
            // Stocker la disposition des modules comme métadonnées dans la session
            // pour utilisation ultérieure si nécessaire
            if (!empty($step2Data['disposition_modules'])) {
                session([
                    'disposition_modules_dalle_' . $dalle->id => $step2Data['disposition_modules']
                ]);
            }

            // Si la disposition est définie, on organise les modules selon cette disposition
            if (isset($step2Data['disposition_modules'])) {
                $moduleIndex = 1;
                $totalModules = $modulesLargeur * $modulesHauteur;
                
                for ($row = 1; $row <= $modulesHauteur; $row++) {
                    for ($col = 1; $col <= $modulesLargeur; $col++) {
                        if ($moduleIndex <= $totalModules && $moduleIndex <= $step3Data['nb_modules_total']) {
                            $module = Module::create([
                                'dalle_id' => $dalle->id,
                                'largeur' => $largeurModule,
                                'hauteur' => $hauteurModule,
                                'nb_pixels_largeur' => $step2Data['nb_pixels_largeur'] ?? 64,
                                'nb_pixels_hauteur' => $step2Data['nb_pixels_hauteur'] ?? 64,
                                'driver' => $step2Data['driver'] ?? null,
                                'shift_register' => $step2Data['shift_register'] ?? null,
                                'buffer' => $step2Data['buffer'] ?? null,
                                'etat' => 'non_commence',
                                'reference_module' => "IND-R{$row}C{$col}",
                                'position_x' => $col,
                                'position_y' => $row
                            ]);
                            
                            $modulesCreated[] = $module;
                            $moduleIndex++;
                        }
                    }
                }
            } else {
                // Disposition linéaire par défaut
                for ($m = 1; $m <= $step3Data['nb_modules_total']; $m++) {
                    $module = Module::create([
                        'dalle_id' => $dalle->id,
                        'largeur' => $largeurModule,
                        'hauteur' => $hauteurModule,
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
            }
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