<?php
// Solution temporaire pour afficher les détails d'un module sans erreur

// Initialisation de Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

// Obtenir l'ID du module depuis l'URL
$id = $_GET['id'] ?? null;

if (!$id) {
    die('ID du module non spécifié');
}

// Utiliser l'application Laravel
$moduleModel = app('App\Models\Module');
$module = $moduleModel->with([
    'dalle.produit.chantier.client', 
    'interventions.diagnostic', 
    'interventions.reparation', 
    'interventions.technicien'
])->find($id);

if (!$module) {
    die('Module non trouvé');
}

// Vue simplifiée pour afficher les informations du module
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Module #<?php echo $module->id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <div class="max-w-6xl mx-auto my-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Détails du Module #<?php echo $module->id; ?></h1>
        
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Informations générales</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p><strong>Référence:</strong> <?php echo $module->reference_module ?: 'Non spécifiée'; ?></p>
                    <p><strong>Dimensions:</strong> <?php echo $module->largeur; ?> × <?php echo $module->hauteur; ?> mm</p>
                    <p><strong>Pixels:</strong> <?php echo $module->nb_pixels_largeur; ?> × <?php echo $module->nb_pixels_hauteur; ?></p>
                </div>
                <div>
                    <p><strong>État:</strong> <?php 
                        switch($module->etat) {
                            case 'non_commence': echo 'Non commencé'; break;
                            case 'en_cours': echo 'En cours'; break;
                            case 'defaillant': echo 'Défaillant'; break;
                            case 'termine': echo 'Terminé'; break;
                            default: echo $module->etat;
                        }
                    ?></p>
                    <p><strong>Driver:</strong> <?php echo $module->driver ?: 'Non spécifié'; ?></p>
                    <p><strong>Shift Register:</strong> <?php echo $module->shift_register ?: 'Non spécifié'; ?></p>
                </div>
                <div>
                    <p><strong>Dalle:</strong> Dalle #<?php echo $module->dalle_id; ?></p>
                    <p><strong>Produit:</strong> <?php echo $module->dalle->produit->marque; ?> <?php echo $module->dalle->produit->modele; ?></p>
                    <p><strong>Chantier:</strong> <?php echo $module->dalle->produit->chantier->nom; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Interventions</h2>
            
            <?php if(count($module->interventions) > 0): ?>
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-700">
                            <th class="py-3 px-4 text-left">Date</th>
                            <th class="py-3 px-4 text-left">Technicien</th>
                            <th class="py-3 px-4 text-left">Durée</th>
                            <th class="py-3 px-4 text-left">Diagnostic</th>
                            <th class="py-3 px-4 text-left">Réparation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($module->interventions as $intervention): ?>
                            <tr class="border-t border-gray-700">
                                <td class="py-3 px-4">
                                    <?php echo $intervention->date_debut ? $intervention->date_debut->format('d/m/Y H:i') : '-'; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php echo $intervention->technicien ? $intervention->technicien->name : 'Non assigné'; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php 
                                        $heures = floor($intervention->temps_total / 3600);
                                        $minutes = floor(($intervention->temps_total % 3600) / 60);
                                        $secondes = $intervention->temps_total % 60;
                                        echo sprintf('%02d:%02d:%02d', $heures, $minutes, $secondes);
                                    ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if($intervention->diagnostic): ?>
                                        <?php echo $intervention->diagnostic->nb_leds_hs ?? 0; ?> LEDs HS,
                                        <?php echo $intervention->diagnostic->nb_ic_hs ?? 0; ?> ICs HS
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if($intervention->reparation): ?>
                                        <?php echo $intervention->reparation->nb_leds_remplacees ?? 0; ?> LEDs,
                                        <?php echo $intervention->reparation->nb_ic_remplaces ?? 0; ?> ICs
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-400">Aucune intervention enregistrée pour ce module.</p>
            <?php endif; ?>
        </div>
        
        <div class="mt-6">
            <a href="javascript:history.back()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Retour</a>
        </div>
    </div>
</body>
</html>