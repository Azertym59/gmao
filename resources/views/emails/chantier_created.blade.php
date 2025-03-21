<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Votre chantier de réparation a été créé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #3a3f44;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair.png" alt="TecaLED" class="logo" />
        <h1>Votre chantier de réparation a été créé</h1>
    </div>
    
    <div class="content">
        <p>Bonjour {{ $client->prenom }} {{ $client->nom }},</p>
        
        <p>Nous vous informons que votre chantier de réparation a été créé dans notre système de gestion avec la référence <strong>{{ $chantier->reference }}</strong>.</p>
        
        <h2>Détails du chantier</h2>
        <p><strong>Nom :</strong> {{ $chantier->nom }}<br>
        <strong>Date de réception :</strong> {{ $chantier->date_reception->format('d/m/Y') }}<br>
        <strong>Date d'échéance :</strong> {{ $chantier->date_butoir->format('d/m/Y') }}</p>
        
        <h2>Produits enregistrés pour réparation</h2>
        @foreach($produits as $produit)
            <table>
                <tr>
                    <th colspan="2">{{ $produit->marque }} {{ $produit->modele }}</th>
                </tr>
                <tr>
                    <td><strong>Type d'utilisation</strong></td>
                    <td>{{ $produit->utilisation == 'indoor' ? 'Intérieur' : 'Extérieur' }}</td>
                </tr>
                <tr>
                    <td><strong>Pitch</strong></td>
                    <td>{{ $produit->pitch }} mm</td>
                </tr>
                <tr>
                    <td><strong>Electronique</strong></td>
                    <td>{{ $produit->electronique }}{{ $produit->electronique_detail ? ' ('.$produit->electronique_detail.')' : '' }}</td>
                </tr>
                <tr>
                    <td><strong>Nombre de dalles</strong></td>
                    <td>{{ $produit->dalles->count() }}</td>
                </tr>
                <tr>
                    <td><strong>Nombre total de modules</strong></td>
                    <td>{{ $totalModules }}</td>
                </tr>
            </table>
        @endforeach
        
        <p>Nous allons procéder à l'analyse de vos équipements et vous tiendrons informé de l'avancement des réparations. Vous recevrez des notifications par email lorsque les interventions débuteront et lorsque le chantier sera terminé.</p>
        
        <p>Si vous avez des questions concernant ce chantier, n'hésitez pas à nous contacter.</p>
        
        <p>Cordialement,<br>
        L'équipe technique</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été généré automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>