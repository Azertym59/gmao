<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Les réparations ont commencé sur votre chantier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #2c88d9;
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
    </style>
</head>
<body>
    <div class="header">
        <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair.png" alt="TecaLED" class="logo" />
        <h1>Les réparations ont commencé</h1>
    </div>
    
    <div class="content">
        <p>Bonjour {{ $client->prenom }} {{ $client->nom }},</p>
        
        <p>Nous vous informons que les réparations ont commencé sur votre chantier <strong>{{ $chantier->reference }}</strong> - {{ $chantier->nom }}.</p>
        
        <p>Nos techniciens travaillent actuellement sur la réparation de vos équipements. Le travail de diagnostic et de réparation a débuté sur {{ $totalModules }} modules.</p>
        
        <p>Vous serez notifié par email lorsque l'ensemble des réparations sera terminé.</p>
        
        <p>La date d'échéance prévue pour ce chantier est le <strong>{{ $chantier->date_butoir->format('d/m/Y') }}</strong>.</p>
        
        <p style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Vous pouvez suivre l'avancement de votre chantier en temps réel avec ce lien :</strong><br>
            <a href="{{ $lienSuivi }}" style="display: inline-block; background-color: #2c88d9; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 10px;">Suivre mon chantier</a>
            <br><small style="display: block; margin-top: 10px;">Ce lien est unique à votre chantier et vous permet de suivre en temps réel l'état des réparations.</small>
        </p>
        
        <p>Si vous avez des questions concernant l'avancement des réparations, n'hésitez pas à nous contacter.</p>
        
        <p>Cordialement,<br>
        L'équipe technique</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été généré automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>