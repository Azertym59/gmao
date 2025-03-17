<?php
// Script de test de l'API pour l'autocomplétion des modèles

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CORS headers pour permettre les requêtes
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// Récupérer le terme de recherche et la marque de l'URL
$term = $_GET['term'] ?? '';
$marque = $_GET['marque'] ?? '';

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
    
    // Rechercher les modèles distincts pour une marque donnée
    $sql = "SELECT DISTINCT modele, pitch, utilisation FROM produits_catalogue 
            WHERE modele LIKE :term 
            AND marque = :marque 
            ORDER BY modele LIMIT 10";
            
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':term', "%$term%", PDO::PARAM_STR);
    $stmt->bindValue(':marque', $marque, PDO::PARAM_STR);
    $stmt->execute();
    
    $modeles = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $modeles[] = [
            'id' => $row['modele'],
            'text' => $row['modele'],
            'pitch' => $row['pitch'],
            'utilisation' => $row['utilisation']
        ];
    }
    
    // Retourner les résultats au format JSON
    echo json_encode($modeles);
    
} catch (PDOException $e) {
    // Retourner l'erreur au format JSON
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage(),
        'path' => $dbPath
    ]);
}