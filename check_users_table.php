<?php

// Connexion à la base de données SQLite
$db = new SQLite3('/var/www/gmao/database/database.sqlite');

// Vérifier la structure de la table users
$query = "PRAGMA table_info(users);";
$result = $db->query($query);

// Afficher les colonnes de la table users
echo "Structure de la table users :\n";
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "Colonne: " . $row['name'] . ", Type: " . $row['type'] . "\n";
}

// Fermeture de la connexion
$db->close();