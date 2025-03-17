<?php
// Script de réinitialisation d'urgence pour l'application GMAO
// Ce script réinitialise la configuration de session pour permettre la connexion

// Afficher les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Chemin du fichier .env
$envFile = __DIR__ . '/../.env';

if (file_exists($envFile)) {
    // Lire le contenu du fichier .env
    $envContent = file_get_contents($envFile);
    
    // Modifier la configuration de session de database à file
    $envContent = preg_replace(
        '/SESSION_DRIVER=database/',
        'SESSION_DRIVER=file',
        $envContent
    );
    
    // Écrire le contenu modifié dans le fichier .env
    file_put_contents($envFile, $envContent);
    
    echo '<h1>Configuration réinitialisée</h1>';
    echo '<p>La configuration de session a été modifiée pour utiliser le stockage de fichiers au lieu de la base de données.</p>';
    
    // Vider le dossier de sessions
    $sessionPath = __DIR__ . '/../storage/framework/sessions';
    if (is_dir($sessionPath)) {
        $files = glob($sessionPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo '<p>Le dossier de sessions a été vidé.</p>';
    }
    
    // Créer un utilisateur admin dans la base SQLite
    try {
        $dbPath = __DIR__ . '/../database/database.sqlite';
        
        if (file_exists($dbPath)) {
            $pdo = new PDO('sqlite:' . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Vérifier si la table users existe
            $tableExists = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'")->fetchColumn();
            
            if ($tableExists) {
                // Vérifier si l'utilisateur admin existe déjà
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $stmt->execute(['admin@example.com']);
                $userExists = $stmt->fetchColumn();
                
                if (\!$userExists) {
                    // Créer un utilisateur admin
                    $hashedPassword = password_hash('password123', PASSWORD_BCRYPT);
                    $now = date('Y-m-d H:i:s');
                    
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute(['Admin', 'admin@example.com', $hashedPassword, 'admin', $now, $now]);
                    
                    echo '<p>Utilisateur admin créé avec succès.</p>';
                } else {
                    echo '<p>L\'utilisateur admin existe déjà.</p>';
                }
            } else {
                echo '<p>La table users n\'existe pas.</p>';
            }
        } else {
            echo '<p>La base de données SQLite n\'existe pas.</p>';
        }
    } catch (PDOException $e) {
        echo '<p>Erreur lors de la création de l\'utilisateur admin: ' . $e->getMessage() . '</p>';
    }
    
    echo '<p><a href="/login">Cliquez ici pour vous connecter</a></p>';
    echo '<p>Utilisez les identifiants suivants:</p>';
    echo '<ul>';
    echo '<li>Email: admin@example.com</li>';
    echo '<li>Mot de passe: password123</li>';
    echo '</ul>';
    
} else {
    echo '<h1>Erreur</h1>';
    echo '<p>Le fichier .env n\'a pas été trouvé.</p>';
}
