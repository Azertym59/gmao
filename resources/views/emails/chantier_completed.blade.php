<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Votre chantier est terminé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #4CAF50;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .logo {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .stats {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .highlight {
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair.png" alt="TecaLED" class="logo" />
        <h1>Votre chantier est terminé</h1>
    </div>
    
    <div class="content">
        <p>Bonjour {{ $client->prenom }} {{ $client->nom }},</p>
        
        <p>Nous sommes heureux de vous informer que votre chantier de réparation <strong>{{ $chantier->reference }}</strong> - {{ $chantier->nom }} est maintenant terminé.</p>
        
        <div class="stats">
            <h2>Récapitulatif des réparations</h2>
            <p><strong>Nombre total de modules traités :</strong> {{ $totalModules }}</p>
            <p><strong>Modules réparés avec succès :</strong> <span class="highlight">{{ $modulesTermines }}</span> ({{ $totalModules ? round(($modulesTermines / $totalModules) * 100) : 0 }}%)</p>
            <p><strong>Temps total consacré aux réparations :</strong> {{ $tempsFormate }}</p>
        </div>
        
        <p>Vous trouverez ci-joint un rapport détaillé de toutes les réparations effectuées sur vos équipements. Ce document récapitule les diagnostics, les interventions réalisées et l'état final de chaque module.</p>
        
        <p>Nous vous remercions pour votre confiance et restons à votre disposition pour toute information complémentaire concernant ces réparations ou pour tout autre besoin futur.</p>
        
        <p>Cordialement,<br>
        L'équipe technique</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été généré automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>