<?php
// Script de débogage pour afficher plus d'informations

// Afficher les erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Informations système
echo "<h1>Débogage connexion</h1>";
echo "<strong>PHP Version:</strong> " . phpversion() . "<br>";
echo "<strong>Serveur:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "<strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "<hr>";

// Informations de configuration Laravel
echo "<h2>Configuration Laravel</h2>";
echo "<pre>";
// Vérifier si le fichier .env est accessible
$env_file = file_exists('../.env') ? '../.env' : '/var/www/gmao/.env';
if (file_exists($env_file)) {
    echo "Fichier .env existe à $env_file\n";
    $env_content = file_get_contents($env_file);
    // Masquer les mots de passe
    $env_content = preg_replace('/PASSWORD=(.*)/', 'PASSWORD=********', $env_content);
    echo htmlentities($env_content);
} else {
    echo "Fichier .env introuvable\n";
}
echo "</pre>";

// Tester la connexion à la base de données SQLite
echo "<h2>Test connexion SQLite</h2>";
try {
    $sqlite_path = '/var/www/gmao/database/database.sqlite';
    echo "Chemin base SQLite: $sqlite_path<br>";
    
    if (file_exists($sqlite_path)) {
        echo "Fichier SQLite existe.<br>";
        $pdo = new PDO('sqlite:' . $sqlite_path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "Connexion SQLite OK<br>";
        
        // Vérifier les tables
        $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table';")->fetchAll(PDO::FETCH_COLUMN);
        echo "<strong>Tables dans SQLite:</strong><br>";
        echo "<pre>" . print_r($tables, true) . "</pre>";
        
        // Vérifier les utilisateurs
        if (in_array('users', $tables)) {
            $users = $pdo->query("SELECT id, name, email, role FROM users;")->fetchAll(PDO::FETCH_ASSOC);
            echo "<strong>Utilisateurs:</strong><br>";
            echo "<pre>" . print_r($users, true) . "</pre>";
        }
    } else {
        echo "Fichier SQLite n'existe pas.<br>";
    }
} catch (PDOException $e) {
    echo "Erreur SQLite: " . $e->getMessage();
}

// Tester la connexion MySQL si configurée
echo "<h2>Test connexion MySQL</h2>";
try {
    $mysql_host = '127.0.0.1';
    $mysql_db = 'gmao_tecaled';
    $mysql_user = 'root';
    $mysql_pass = 'Karting!411'; // Attention: ceci ne devrait pas être exposé en production
    
    $mysql = new PDO("mysql:host=$mysql_host;dbname=$mysql_db", $mysql_user, $mysql_pass);
    $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion MySQL OK<br>";
    
    // Lister les tables MySQL
    $tables = $mysql->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<strong>Tables dans MySQL:</strong><br>";
    echo "<pre>" . print_r($tables, true) . "</pre>";
    
    // Vérifier les utilisateurs MySQL si la table existe
    if (in_array('users', $tables)) {
        $users = $mysql->query("SELECT id, name, email, role FROM users")->fetchAll(PDO::FETCH_ASSOC);
        echo "<strong>Utilisateurs MySQL:</strong><br>";
        echo "<pre>" . print_r($users, true) . "</pre>";
    }
} catch (PDOException $e) {
    echo "Erreur MySQL: " . $e->getMessage();
}

// Afficher les sessions et cookies
echo "<h2>Sessions et Cookies</h2>";
echo "<strong>Session ID:</strong> " . session_id() . "<br>";
echo "<strong>Cookies:</strong><br>";
echo "<pre>" . print_r($_COOKIE, true) . "</pre>";

// Formulaire de login de test
echo "<h2>Formulaire de connexion de test</h2>";
echo '<form method="post" action="/temp-login">
<input type="hidden" name="_token" value="' . md5(time()) . '">
<div>
    <label>Email: <input type="email" name="email" value="admin@example.com"></label>
</div>
<div>
    <label>Mot de passe: <input type="password" name="password" value="password123"></label>
</div>
<div>
    <button type="submit">Se connecter</button>
</div>
</form>';