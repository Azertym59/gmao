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
        <div style="background-color: #ffe7e6; padding: 15px; border-radius: 5px; margin: 0 0 20px 0; border-left: 4px solid #ff9999;">
            <strong>⚠️ Information importante</strong><br>
            Notre système de gestion est actuellement en cours de développement. Certains bugs peuvent exister.
            Nous vous remercions pour votre compréhension.
        </div>

        <p>Bonjour <?php echo e($client->civilite ?? 'M.'); ?> <?php echo e($client->prenom); ?> <?php echo e(strtoupper($client->nom)); ?>,</p>
        
        <p>Nous vous informons que votre chantier de réparation a été créé dans notre système de gestion avec la référence <strong><?php echo e($chantier->reference); ?></strong>.</p>
        
        <h2>Détails du chantier</h2>
        <p><strong>Nom :</strong> <?php echo e($chantier->nom); ?><br>
        <strong>Date de réception :</strong> <?php echo e($chantier->date_reception->format('d/m/Y')); ?><br>
        <strong>Date d'échéance :</strong> <?php echo e($chantier->date_butoir->format('d/m/Y')); ?></p>
        
        <h2>Produits enregistrés pour réparation</h2>
        <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <table>
                <tr>
                    <th colspan="2"><?php echo e($produit->marque); ?> <?php echo e($produit->modele); ?></th>
                </tr>
                <tr>
                    <td><strong>Type d'utilisation</strong></td>
                    <td><?php echo e($produit->utilisation == 'indoor' ? 'Intérieur' : 'Extérieur'); ?></td>
                </tr>
                <tr>
                    <td><strong>Pitch</strong></td>
                    <td><?php echo e($produit->pitch); ?> mm</td>
                </tr>
                <tr>
                    <td><strong>Electronique</strong></td>
                    <td><?php echo e($produit->electronique); ?><?php echo e($produit->electronique_detail ? ' ('.$produit->electronique_detail.')' : ''); ?></td>
                </tr>
                <tr>
                    <td><strong>Nombre de dalles</strong></td>
                    <td><?php echo e($produit->dalles->count()); ?></td>
                </tr>
                <tr>
                    <td><strong>Nombre total de modules</strong></td>
                    <td><?php echo e($totalModules); ?></td>
                </tr>
            </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        <p>Nous allons procéder à l'analyse de vos équipements et vous tiendrons informé de l'avancement des réparations. Vous recevrez des notifications par email lorsque les interventions débuteront et lorsque le chantier sera terminé.</p>
        
        <p style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Vous pouvez suivre l'avancement de votre chantier en temps réel avec ce lien :</strong><br>
            <a href="<?php echo e($lienSuivi); ?>" style="display: inline-block; background-color: #3a3f44; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 10px;">Suivre mon chantier</a>
            <br><small style="display: block; margin-top: 10px;">Ce lien est unique à votre chantier et vous permet de suivre en temps réel l'état des réparations.</small>
        </p>
        
        <p style="background-color: #ebf5ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Accédez à l'historique de tous vos projets !</strong><br>
            Créez un compte client pour consulter facilement l'ensemble de vos chantiers de réparation en un seul endroit.
            <a href="<?php echo e($lienInscription); ?>" style="display: inline-block; background-color: #4299e1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 10px;">Créer mon compte</a>
        </p>
        
        <p>Si vous avez des questions concernant ce chantier, n'hésitez pas à nous contacter.</p>
        
        <p>Cordialement,<br>
        L'équipe technique</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été généré automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html><?php /**PATH /var/www/gmao/resources/views/emails/chantier_created.blade.php ENDPATH**/ ?>