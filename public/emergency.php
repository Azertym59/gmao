<?php
// Script d'urgence pour créer un administrateur
// Ce script ne dépend pas de Laravel et crée un fichier texte avec les informations d'authentification

// Initialisation
$success = false;
$message = '';
$accessCode = 'tecaled2025';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier le code d'accès
    if (isset($_POST['access_code']) && $_POST['access_code'] === $accessCode) {
        try {
            // Récupérer les informations fournies
            $name = htmlspecialchars($_POST['name'] ?? 'Admin');
            $email = htmlspecialchars($_POST['email'] ?? 'admin@example.com');
            $password = htmlspecialchars($_POST['password'] ?? 'password123');
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            // Créer un fichier avec les informations d'authentification
            $content = "Name: $name\nEmail: $email\nPassword (hashed): $hashedPassword\nPassword (plain): $password\nRole: admin\nCreated: " . date('Y-m-d H:i:s');
            
            // Écrire dans un fichier
            $filename = __DIR__ . '/admin_credentials.txt';
            file_put_contents($filename, $content);
            chmod($filename, 0666);
            
            $success = true;
            $message = "Informations d'administrateur créées avec succès ! Fichier : $filename";
        } catch (Exception $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    } else {
        $message = "Code d'accès invalide";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès d'urgence GMAO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: rgba(30, 30, 30, 0.8);
            border-radius: 10px;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #ff9800;
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #e2e8f0;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #374151;
            background-color: #1f2937;
            color: #fff;
            box-sizing: border-box;
        }
        button {
            background-color: #ff9800;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }
        button:hover {
            background-color: #e68a00;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: rgba(16, 185, 129, 0.2);
            border: 1px solid #10b981;
            color: #10b981;
        }
        .error {
            background-color: rgba(239, 68, 68, 0.2);
            border: 1px solid #ef4444;
            color: #ef4444;
        }
        .warning {
            background-color: #ff9800;
            color: white;
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .instructions {
            background-color: rgba(255, 152, 0, 0.1);
            border: 1px solid rgba(255, 152, 0, 0.3);
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }
        .instructions h2 {
            margin-top: 0;
            color: #ff9800;
            font-size: 18px;
        }
        .instructions p, .instructions ul {
            margin: 10px 0;
            color: #d1d5db;
        }
        .instructions ul {
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Accès d'urgence GMAO</h1>
        
        <div class="warning">
            ATTENTION : Cet outil est à utiliser uniquement en dernier recours.
            Il ne crée pas d'utilisateur dans la base de données.
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="">
            <label for="access_code">Code d'accès</label>
            <input type="password" id="access_code" name="access_code" required>
            
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="Admin" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="admin@example.com" required>
            
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" value="password123" required>
            
            <button type="submit">Créer les informations d'administration</button>
        </form>
        
        <?php if ($success): ?>
            <div class="instructions">
                <h2>Instructions</h2>
                <p>Informations d'administrateur créées avec succès !</p>
                <p>Un fichier texte a été créé avec les informations d'authentification. Pour utiliser ces informations :</p>
                <ul>
                    <li>Ouvrez le fichier créé (admin_credentials.txt) pour récupérer le mot de passe haché</li>
                    <li>Suivez les instructions de votre administrateur système pour insérer ces informations dans la base de données</li>
                    <li>IMPORTANT : Supprimez ce fichier et admin_credentials.txt après utilisation</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>