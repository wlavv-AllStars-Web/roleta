<?php
session_start();
header('Content-Type: application/json');
date_default_timezone_set('Europe/Lisbon');

require_once 'db.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté']);
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Lecture des données JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Vérification du JSON
if (!is_array($data)) {
    echo json_encode(['success' => false, 'error' => 'Données JSON invalides']);
    exit;
}

$prizeName = trim($data['prize_name'] ?? '');

if (empty($prizeName)) {
    echo json_encode(['success' => false, 'error' => 'Nom du prix manquant']);
    exit;
}

try {
    // Vérifier si le prix existe pour cet admin
    $stmt = $pdo->prepare("SELECT id FROM roleta_prizes WHERE name = ? AND admin_id = ? LIMIT 1");
    $stmt->execute([$prizeName, $admin_id]);
    $prize = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prize) {
        echo json_encode(['success' => false, 'error' => 'Prix non trouvé']);
        exit;
    }

    $prizeId = $prize['id'];
    $playedAt = date('Y-m-d H:i:s');

    // Enregistrer le résultat
    $stmt = $pdo->prepare("INSERT INTO roleta_results (prize_id, admin_id, played_at) VALUES (?, ?, ?)");
    $stmt->execute([$prizeId, $admin_id, $playedAt]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Ne jamais exposer le message SQL complet en production
    echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'enregistrement du résultat.']);
    error_log("Erreur SQL (enregistrer_resultat.php) : " . $e->getMessage());
}