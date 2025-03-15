<?php
// Script de contournement d'urgence pour Laravel GMAO
// Ce script permet de créer un admin et de contourner les problèmes d'authentification

// Pour la sécurité, ce script nécessite un code d'accès
$accessCode = 'tecaled2025'; // Code d'accès pour utiliser ce script

$message = '';
$success = false;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier le code d'accès
    if (isset($_POST['access_code']) && $_POST['access_code'] === $accessCode) {
        
        try {
            // Utiliser un chemin différent pour la base de données SQLite
            $dbPath = __DIR__ . '/temp_admin.sqlite';
            
            // Informer l'utilisateur de ce changement
            $message .= " (Utilisation d'une base de données temporaire à " . $dbPath . ")";
            
            // Vérifier si le fichier existe
            if (!file_exists($dbPath)) {
                // Créer le fichier SQLite s'il n'existe pas
                touch($dbPath);
                chmod($dbPath, 0777);
            }
            
            // Connexion à la base de données
            $db = new PDO("sqlite:$dbPath");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Créer la table users si elle n'existe pas
            $db->exec("
                CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT,
                    email TEXT UNIQUE,
                    role TEXT DEFAULT 'technicien',
                    email_verified_at TIMESTAMP NULL,
                    password TEXT,
                    remember_token TEXT,
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP
                )
            ");
            
            // Informations de l'admin à créer
            $name = $_POST['name'] ?? 'Admin';
            $email = $_POST['email'] ?? 'admin@example.com';
            $password = password_hash($_POST['password'] ?? 'password123', PASSWORD_BCRYPT);
            $role = 'admin';
            $now = date('Y-m-d H:i:s');
            
            // Vérifier si l'utilisateur existe déjà
            $checkStmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $checkStmt->execute([$email]);
            
            if ($checkStmt->fetchColumn() > 0) {
                // Mettre à jour l'utilisateur existant
                $updateStmt = $db->prepare("
                    UPDATE users 
                    SET name = ?, password = ?, role = ?, updated_at = ? 
                    WHERE email = ?
                ");
                
                $updateStmt->execute([$name, $password, $role, $now, $email]);
                $message = "Utilisateur '$email' mis à jour avec succès";
            } else {
                // Créer un nouvel utilisateur
                $insertStmt = $db->prepare("
                    INSERT INTO users (name, email, password, role, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                $insertStmt->execute([$name, $email, $password, $role, $now, $now]);
                $message = "Utilisateur '$email' créé avec succès";
            }
            
            $success = true;
            
        } catch (Exception $e) {
            $message = "Erreur: " . $e->getMessage();
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
    <title>Outil d'admin GMAO d'urgence</title>
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
            color: #3B82F6;
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
            background-color: #3B82F6;
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
            background-color: #2563eb;
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
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }
        .instructions h2 {
            margin-top: 0;
            color: #3B82F6;
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
        <h1>Outil d'admin GMAO d'urgence</h1>
        
        <div class="warning">
            ATTENTION : Cet outil est à utiliser uniquement en cas d'urgence.
            Supprimez ce fichier après utilisation.
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
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
            
            <button type="submit">Créer / Mettre à jour l'administrateur</button>
        </form>
        
        <?php if ($success): ?>
            <div class="instructions">
                <h2>Instructions</h2>
                <p>Utilisateur créé avec succès. Vous pouvez maintenant :</p>
                <ul>
                    <li>Vous connecter à l'application avec l'email et le mot de passe que vous avez fournis</li>
                    <li>IMPORTANT : Supprimer ce fichier bypass.php après utilisation</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>