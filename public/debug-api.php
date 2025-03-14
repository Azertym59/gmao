<?php
// Script de test de l'API clients/search directement sans passer par Laravel

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CORS headers pour permettre les requ�tes 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// R�cup�rer le terme de recherche de l'URL
$term = $_GET['term'] ?? '';

// Connexion � la base de donn�es SQLite
$dbPath = __DIR__ . '/../database/database.sqlite';

// V�rifier si le fichier existe
if (!file_exists($dbPath)) {
    echo json_encode(['error' => 'Database file not found', 'path' => $dbPath]);
    exit();
}

try {
    $db = new PDO("sqlite:$dbPath");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Recherche dans la table clients
    $sql = "SELECT id, nom, prenom, societe, email, telephone, adresse, code_postal, ville, pays
            FROM clients 
            WHERE nom LIKE :term 
            OR prenom LIKE :term 
            OR societe LIKE :term 
            OR email LIKE :term
            OR ville LIKE :term
            LIMIT 10";
            
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':term', "%$term%", PDO::PARAM_STR);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formater les r�sultats
    foreach ($results as &$client) {
        $client['nom_complet'] = $client['nom'] . ' ' . $client['prenom'];
        $client['display_text'] = $client['nom_complet'];
        
        if (!empty($client['societe'])) {
            $client['display_text'] .= ' (' . $client['societe'] . ')';
        }
    }
    
    // Retourner les r�sultats au format JSON
    echo json_encode($results);
    
} catch (PDOException $e) {
    // Retourner l'erreur au format JSON
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage(),
        'path' => $dbPath
    ]);
}