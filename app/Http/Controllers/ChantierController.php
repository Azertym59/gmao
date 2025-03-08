<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Client;
use App\Models\ProduitCatalogue; 
use App\Models\Produit;
use App\Models\Dalle;
use App\Models\Module;
use Illuminate\Http\Request;
class ChantierController extends Controller
{
    /**
     * Afficher la liste des chantiers
     */
    public function index()
    {
        $chantiers = Chantier::with('client')->get();
        return view('chantiers.index', compact('chantiers'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $clients = Client::all();
        $client_id = $request->input('client_id');
        $client = null;
        
        if ($client_id) {
            $client = Client::find($client_id);
        }
        
        return view('chantiers.create', compact('clients', 'client'));
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
    
    // Mettre à jour les autres champs
    $chantier->client_id = $validated['client_id'];
    $chantier->description = $validated['description'];
    $chantier->date_reception = $validated['date_reception'];
    $chantier->date_butoir = $validated['date_butoir'];
    $chantier->etat = $validated['etat'];
    
    $chantier->save();
    
    return redirect()->route('chantiers.show', $chantier)
        ->with('success', 'Chantier mis à jour avec succès.');
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
 * Étape 1 : Afficher le formulaire d'informations client/chantier
 */
public function createStep1()
{
    $clients = Client::all();
    return view('chantiers.create_step1', compact('clients'));
}

/**
 * Traiter l'étape 1 et passer à l'étape 2
 */
public function storeStep1(Request $request)
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
 * Étape 2 : Sélectionner/créer le produit
 */
public function createStep2()
{
    // Vérifier si l'étape 1 a été complétée
    if (!session()->has('chantier_data_step1')) {
        return redirect()->route('chantiers.create.step1');
    }

    $produitsCatalogue = ProduitCatalogue::all();
    return view('chantiers.create_step2', compact('produitsCatalogue'));
}

/**
 * Traiter l'étape 2 et passer à l'étape 3
 */
public function storeStep2(Request $request)
{
    // Validation des données
    $validated = $request->validate([
        'marque' => 'required|string|max:255',
        'modele' => 'required|string|max:255',
        'pitch' => 'required|numeric|min:0.1|max:100',
        'utilisation' => 'required|in:indoor,outdoor',
        'electronique' => 'required|in:nova,linsn,dbstar,brompton,autre',
        'electronique_detail' => 'nullable|required_if:electronique,autre|string|max:255',
        'from_catalogue' => 'required|boolean',
        'catalogue_id' => 'nullable|required_if:from_catalogue,1|exists:produits_catalogue,id',
    ]);

    // Récupérer la valeur de add_to_catalogue avec une valeur par défaut de false
    $addToCatalogue = $request->has('add_to_catalogue');

    // Si l'utilisateur veut ajouter le produit au catalogue
    if ($addToCatalogue) {
        try {
            ProduitCatalogue::create([
                'marque' => $validated['marque'],
                'modele' => $validated['modele'],
                'pitch' => $validated['pitch'],
                'utilisation' => $validated['utilisation'],
                'electronique' => $validated['electronique'],
                'electronique_detail' => $validated['electronique_detail'],
            ]);
        } catch (\Exception $e) {
            // Log l'erreur mais continuer le processus
            \Illuminate\Support\Facades\Log::error('Erreur lors de l\'ajout au catalogue: ' . $e->getMessage());
        }
    }

    // Stocker les données avec le nom correct et ajouter un champ de type de sélection
    $chantier_data_step2 = [
        'marque' => $validated['marque'],
        'modele' => $validated['modele'],
        'pitch' => $validated['pitch'],
        'utilisation' => $validated['utilisation'],
        'electronique' => $validated['electronique'],
        'electronique_detail' => $validated['electronique_detail'],
        // Ajouter ces champs importants
        'selection_type' => $validated['from_catalogue'] ? 'existant' : 'nouveau',
        'produit_catalogue_id' => $validated['from_catalogue'] ? $validated['catalogue_id'] : null,
    ];

    // Utiliser le nom correct pour la variable de session
    session(['chantier_data_step2' => $chantier_data_step2]);

    return redirect()->route('chantiers.create.step3');
}

/**
 * Étape 3 : Configuration des dalles et modules
 */
public function createStep3()
{
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
 * Traiter l'étape 3 et finaliser la création
 */
public function storeStep3(Request $request)
{
    $validated = $request->validate([
        'mode' => 'required|in:flightcase,individuel',
        'nb_flightcases' => 'required_if:mode,flightcase|integer|min:1',
        'nb_dalles_par_flightcase' => 'required_if:mode,flightcase|integer|min:1',
        'nb_modules_par_dalle' => 'required_if:mode,flightcase|integer|min:1',
        'nb_modules_total' => 'required_if:mode,individuel|integer|min:1',
        // Caractéristiques des dalles
        'largeur_dalle' => 'required|numeric|min:1',
        'hauteur_dalle' => 'required|numeric|min:1',
        'alimentation_dalle' => 'required|string',
        'carte_reception' => 'nullable|string',
        'hub' => 'nullable|string',
        // Caractéristiques des modules
        'largeur_module' => 'required|numeric|min:1',
        'hauteur_module' => 'required|numeric|min:1',
        'nb_pixels_largeur' => 'required|integer|min:1',
        'nb_pixels_hauteur' => 'required|integer|min:1',
        'driver' => 'nullable|string',
        'shift_register' => 'nullable|string',
        'buffer' => 'nullable|string',
    ]);

    // Récupérer les données des étapes précédentes
    $step1Data = session('chantier_data_step1');
    $step2Data = session('chantier_data_step2');

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
        $produit = Produit::create([
            'chantier_id' => $chantier->id,
            'marque' => $produitRef->marque,
            'modele' => $produitRef->modele,
            'pitch' => $produitRef->pitch,
            'utilisation' => $produitRef->utilisation,
            'electronique' => $produitRef->electronique,
            'electronique_detail' => $produitRef->electronique_detail,
        ]);
    } else {
        $produit = Produit::create([
            'chantier_id' => $chantier->id,
            'marque' => $step2Data['marque'],
            'modele' => $step2Data['modele'],
            'pitch' => $step2Data['pitch'],
            'utilisation' => $step2Data['utilisation'],
            'electronique' => $step2Data['electronique'],
            'electronique_detail' => $step2Data['electronique_detail'],
        ]);
    }

    // Créer les dalles et modules
    if ($validated['mode'] == 'flightcase') {
        for ($f = 1; $f <= $validated['nb_flightcases']; $f++) {
            for ($d = 1; $d <= $validated['nb_dalles_par_flightcase']; $d++) {
                // Créer la dalle
                $dalle = Dalle::create([
                    'produit_id' => $produit->id,
                    'largeur' => $validated['largeur_dalle'],
                    'hauteur' => $validated['hauteur_dalle'],
                    'nb_modules' => $validated['nb_modules_par_dalle'],
                    'alimentation' => $validated['alimentation_dalle'],
                    'carte_reception' => $validated['carte_reception'],
                    'hub' => $validated['hub'],
                    'reference_dalle' => "FC{$f}-D{$d}"
                ]);

                // Créer les modules
                for ($m = 1; $m <= $validated['nb_modules_par_dalle']; $m++) {
                    Module::create([
                        'dalle_id' => $dalle->id,
                        'largeur' => $validated['largeur_module'],
                        'hauteur' => $validated['hauteur_module'],
                        'nb_pixels_largeur' => $validated['nb_pixels_largeur'],
                        'nb_pixels_hauteur' => $validated['nb_pixels_hauteur'],
                        'driver' => $validated['driver'],
                        'shift_register' => $validated['shift_register'],
                        'buffer' => $validated['buffer'],
                        'etat' => 'non_commence',
                        'reference_module' => "FC{$f}-D{$d}-M{$m}"
                    ]);
                }
            }
        }
    } else {
        // Création de modules individuels
        $dalle = Dalle::create([
            'produit_id' => $produit->id,
            'largeur' => $validated['largeur_dalle'],
            'hauteur' => $validated['hauteur_dalle'],
            'nb_modules' => $validated['nb_modules_total'],
            'alimentation' => $validated['alimentation_dalle'],
            'carte_reception' => $validated['carte_reception'],
            'hub' => $validated['hub'],
            'reference_dalle' => "INDIVIDUEL"
        ]);

        for ($m = 1; $m <= $validated['nb_modules_total']; $m++) {
            Module::create([
                'dalle_id' => $dalle->id,
                'largeur' => $validated['largeur_module'],
                'hauteur' => $validated['hauteur_module'],
                'nb_pixels_largeur' => $validated['nb_pixels_largeur'],
                'nb_pixels_hauteur' => $validated['nb_pixels_hauteur'],
                'driver' => $validated['driver'],
                'shift_register' => $validated['shift_register'],
                'buffer' => $validated['buffer'],
                'etat' => 'non_commence',
                'reference_module' => "IND-{$m}"
            ]);
        }
    }

    // Nettoyer la session
    session()->forget(['chantier_data_step1', 'chantier_data_step2']);

    return redirect()->route('chantiers.show', $chantier)
        ->with('success', 'Chantier créé avec succès !');
}
}