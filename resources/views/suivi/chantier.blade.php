<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de chantier - {{ $chantier->reference }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        :root {
            color-scheme: dark;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #121212;
            color: #F3F4F6;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.5rem;
        }
        
        .glassmorphism {
            background: rgba(30, 30, 30, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .header-logo {
            height: 2.5rem;
        }
        
        .card {
            background: rgba(30, 30, 30, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background-color: rgba(26, 32, 44, 0.4);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1rem;
        }
        
        @media (min-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            
            .grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        
        .progress-container {
            width: 100%;
            background-color: rgba(55, 65, 81, 0.5);
            border-radius: 0.5rem;
            margin: 1rem 0;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 20px;
            background: linear-gradient(90deg, #3B82F6, #10B981);
            border-radius: 0.5rem;
            text-align: center;
            color: white;
            font-weight: bold;
            line-height: 20px;
            transition: width 0.5s ease-in-out;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-non_commence {
            background-color: rgba(75, 85, 99, 0.2);
            color: #9CA3AF;
            border: 1px solid rgba(75, 85, 99, 0.3);
        }
        
        .status-en_cours {
            background-color: rgba(59, 130, 246, 0.2);
            color: #60A5FA;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        .status-termine {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34D399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        
        .status-defaillant {
            background-color: rgba(239, 68, 68, 0.2);
            color: #F87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .stat-box {
            background-color: rgba(55, 65, 81, 0.3);
            border-radius: 0.5rem;
            padding: 1rem;
            text-align: center;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 1rem;
        }
        
        table th {
            background-color: rgba(55, 65, 81, 0.8);
            color: #F3F4F6;
            font-weight: 600;
            text-align: left;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #F3F4F6;
        }
        
        table tr:hover td {
            background-color: rgba(55, 65, 81, 0.3);
        }
        
        .flex {
            display: flex;
        }
        
        .flex-col {
            flex-direction: column;
        }
        
        .justify-between {
            justify-content: space-between;
        }
        
        .items-center {
            align-items: center;
        }
        
        .mt-2 {
            margin-top: 0.5rem;
        }
        
        .mt-4 {
            margin-top: 1rem;
        }
        
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        
        .mb-4 {
            margin-bottom: 1rem;
        }
        
        .text-sm {
            font-size: 0.875rem;
        }
        
        .text-lg {
            font-size: 1.125rem;
        }
        
        .text-xl {
            font-size: 1.25rem;
        }
        
        .text-2xl {
            font-size: 1.5rem;
        }
        
        .font-medium {
            font-weight: 500;
        }
        
        .font-semibold {
            font-weight: 600;
        }
        
        .text-gray-400 {
            color: #9CA3AF;
        }
        
        .border-t {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .pt-4 {
            padding-top: 1rem;
        }
        
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .py-8 {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .overflow-x-auto {
            overflow-x: auto;
        }

        .footer {
            text-align: center;
            padding: 1.5rem;
            color: #9CA3AF;
            font-size: 0.875rem;
            margin-top: 2rem;
        }

        /* Animation for gradient backgrounds */
        .animated-bg {
            background: linear-gradient(45deg, #3B82F6, #8B5CF6, #EC4899);
            background-size: 600% 600%;
            animation: gradientAnimation 12s ease infinite;
        }
        
        @keyframes gradientAnimation {
            0% { background-position: 0% 50% }
            50% { background-position: 100% 50% }
            100% { background-position: 0% 50% }
        }
    </style>
</head>
<body>
    <div class="header glassmorphism">
        <div>
            <img src="{{ asset('images/logo-repair.png') }}" alt="TecaLED" class="header-logo">
        </div>
        <div>
            <h1 class="text-2xl font-semibold mb-2">Suivi de chantier</h1>
            <p class="text-gray-400">Référence: {{ $chantier->reference }}</p>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Informations du chantier</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2">
                    <div>
                        <p class="text-gray-400">Nom</p>
                        <p class="font-medium">{{ $chantier->nom }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Client</p>
                        <p class="font-medium">{{ $chantier->client->nom }} {{ $chantier->client->prenom }}</p>
                        @if($chantier->client->societe)
                            <p class="text-sm text-gray-400">{{ $chantier->client->societe }}</p>
                        @endif
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400">Date de réception</p>
                        <p class="font-medium">{{ $chantier->date_reception->format('d/m/Y') }}</p>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400">Date d'échéance</p>
                        <p class="font-medium">{{ $chantier->date_butoir->format('d/m/Y') }}</p>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400">État actuel</p>
                        <p>
                            <span class="status-badge status-{{ $chantier->etat }}">
                                @if($chantier->etat == 'non_commence')
                                    Non commencé
                                @elseif($chantier->etat == 'en_cours')
                                    En cours
                                @elseif($chantier->etat == 'termine')
                                    Terminé
                                @endif
                            </span>
                        </p>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400">Dernière mise à jour</p>
                        <p class="font-medium">{{ $chantier->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Progression du chantier</h2>
            </div>
            <div class="card-body">
                <div class="flex justify-between mb-2">
                    <p class="font-medium">Modules terminés: {{ $modulesTermines }} / {{ $totalModules }}</p>
                    <p class="font-medium">{{ $pourcentageTermines }}% complet</p>
                </div>
                <div class="progress-container">
                    <div class="progress-bar" style="width: {{ $pourcentageTermines }}%">
                        {{ $pourcentageTermines }}%
                    </div>
                </div>
                <div class="grid grid-cols-3 mt-4">
                    <div class="stat-box">
                        <p class="text-gray-400">Temps total de réparation</p>
                        <p class="stat-value">{{ $tempsFormate }}</p>
                    </div>
                    <div class="stat-box">
                        <p class="text-gray-400">Interventions en cours</p>
                        <p class="stat-value">{{ $interventionsEnCours }}</p>
                    </div>
                    <div class="stat-box">
                        <p class="text-gray-400">Date estimée de fin</p>
                        <p class="stat-value">{{ $chantier->date_butoir->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        @foreach($chantier->produits as $produit)
        <div class="card">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Produit: {{ $produit->marque }} {{ $produit->modele }}</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 mb-4">
                    <div>
                        <p class="text-gray-400">Type d'utilisation</p>
                        <p class="font-medium">{{ $produit->utilisation == 'indoor' ? 'Intérieur' : 'Extérieur' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Pitch</p>
                        <p class="font-medium">{{ $produit->pitch }} mm</p>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400">Électronique</p>
                        <p class="font-medium">{{ $produit->electronique }}{{ $produit->electronique_detail ? ' ('.$produit->electronique_detail.')' : '' }}</p>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400">Nombre de dalles</p>
                        <p class="font-medium">{{ $produit->dalles->count() }}</p>
                    </div>
                </div>
                
                @foreach($produit->dalles as $dalle)
                <div class="mt-4 border-t pt-4">
                    <h3 class="text-lg font-medium mb-2">Dalle: {{ $dalle->reference_dalle }}</h3>
                    
                    <div class="overflow-x-auto">
                        <table>
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>État</th>
                                    <th>Dernière intervention</th>
                                    <th>Technicien</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dalle->modules as $module)
                                <tr>
                                    <td>
                                        <a href="{{ route('suivi.module', ['token' => $chantier->token_suivi, 'moduleId' => $module->id]) }}" class="text-blue-400 hover:underline">
                                            {{ $module->reference_module }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $module->etat }}">
                                            @if($module->etat == 'non_commence')
                                                Non commencé
                                            @elseif($module->etat == 'en_cours')
                                                En cours
                                            @elseif($module->etat == 'termine')
                                                Terminé
                                            @elseif($module->etat == 'defaillant')
                                                Défaillant
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($module->interventions->count() > 0)
                                            {{ $module->interventions->sortByDesc('updated_at')->first()->updated_at->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($module->interventions->count() > 0 && $module->interventions->sortByDesc('updated_at')->first()->technicien)
                                            {{ $module->interventions->sortByDesc('updated_at')->first()->technicien->name }}
                                        @else
                                            Non assigné
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        
        <div class="footer glassmorphism">
            <p>Ce suivi est mis à jour en temps réel. Dernière consultation: {{ now()->format('d/m/Y H:i:s') }}</p>
            <p class="mt-2">Pour toute question sur votre chantier, veuillez nous contacter.</p>
            <p class="mt-4">&copy; {{ date('Y') }} TecaLED - GMAO. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>