<?php
// Script de test de l'API pour l'autocomplétion des marques

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CORS headers pour permettre les requêtes 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// Récupérer le terme de recherche de l'URL
$term = $_GET['term'] ?? '';

// Connexion à la base de données SQLite
$dbPath = __DIR__ . '/../database/database.sqlite';

// Vérifier si le fichier existe
if (!file_exists($dbPath)) {
    echo json_encode(['error' => 'Database file not found', 'path' => $dbPath]);
    exit();
}

try {
    $db = new PDO("sqlite:$dbPath");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Rechercher les marques distinctes
    $sql = "SELECT DISTINCT marque FROM produits_catalogue WHERE marque LIKE :term ORDER BY marque LIMIT 10";
            
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':term', "%$term%", PDO::PARAM_STR);
    $stmt->execute();
    
    $marques = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $marques[] = [
            'id' => $row['marque'], 
            'text' => $row['marque']
        ];
    }
    
    // Retourner les résultats au format JSON
    echo json_encode($marques);
    
} catch (PDOException $e) {
    // Retourner l'erreur au format JSON
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage(),
        'path' => $dbPath
    ]);
}