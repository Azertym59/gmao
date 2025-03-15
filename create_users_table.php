<?php

// Connexion à la base de données SQLite
$db = new SQLite3('/var/www/gmao/database/database.sqlite');

// Création de la table users
$query = "
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
);";

$result = $db->exec($query);
if ($result) {
    echo "Table users créée avec succès\n";
} else {
    echo "Erreur lors de la création de la table users\n";
}

// Fermeture de la connexion
$db->close();