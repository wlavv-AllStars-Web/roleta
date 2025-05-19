<?php
header('Content-Type: application/json');
date_default_timezone_set('Europe/Lisbon');
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$prizeName = $data['prize_name'] ?? null;

if (!$prizeName) {
    echo json_encode(['success' => false, 'error' => 'Nom du prix manquant']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM roleta_prizes WHERE name = ? LIMIT 1");
    $stmt->execute([$prizeName]);
    $prize = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prize) {
        echo json_encode(['success' => false, 'error' => 'Prix non trouvé']);
        exit;
    }

    $prizeId = $prize['id'];

    $stmt = $pdo->prepare("INSERT INTO roleta_results (prize_id, played_at) VALUES (?, ?)");
    $stmt->execute([$prizeId, date('Y-m-d H:i:s')]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}