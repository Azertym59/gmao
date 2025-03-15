<?php

// Chemin vers votre base de données SQLite
$dbPath = __DIR__ . '/database/database.sqlite';

try {
    // Vérifier si le fichier SQLite existe
    if (!file_exists($dbPath)) {
        echo "Le fichier de base de données n'existe pas. Création d'un nouveau fichier...\n";
        touch($dbPath);
        chmod($dbPath, 0666);
    }

    // Connexion à la base de données SQLite
    $db = new PDO("sqlite:" . $dbPath);
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
    
    echo "Table users vérifiée ou créée avec succès\n";
    
    // Vérifier si un utilisateur existe déjà avec cet email
    $checkStmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $checkStmt->execute(['admin@example.com']);
    $count = $checkStmt->fetchColumn();
    
    if ($count > 0) {
        echo "Un utilisateur avec l'email admin@example.com existe déjà\n";
    } else {
        // Hasher le mot de passe avec bcrypt
        $password = password_hash('password123', PASSWORD_BCRYPT);
        $now = date('Y-m-d H:i:s');
        
        // Insérer l'utilisateur administrateur
        $insertStmt = $db->prepare("
            INSERT INTO users (name, email, password, role, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $insertStmt->execute([
            'Admin', 
            'admin@example.com', 
            $password, 
            'admin', 
            $now, 
            $now
        ]);
        
        echo "Utilisateur administrateur créé avec succès!\n";
        echo "Email: admin@example.com\n";
        echo "Mot de passe: password123\n";
    }
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}