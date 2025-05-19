<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$id = $_GET['id'] ?? null;
$lang = $_GET['lang'] ?? 'pt';

if (!$id) {
    header("Location: admin.php?lang=$lang&error=missing_id");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT name FROM roleta_prizes WHERE id = ?");
    $stmt->execute([$id]);
    $prize = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prize) {
        header("Location: admin.php?lang=$lang&error=not_found");
        exit;
    }

    $name = $prize['name'];

    // Vérifie le statut actuel
    $stmt = $pdo->prepare("SELECT MAX(active) FROM roleta_prizes WHERE name = ?");
    $stmt->execute([$name]);
    $currentStatus = $stmt->fetchColumn();

    $newStatus = $currentStatus ? 0 : 1;

    $update = $pdo->prepare("UPDATE roleta_prizes SET active = ? WHERE name = ?");
    $update->execute([$newStatus, $name]);

    header("Location: admin.php?lang=$lang&success=status_updated");
    exit;

} catch (PDOException $e) {
    // Debug optionnel
    // echo "Erreur : " . $e->getMessage();
    header("Location: admin.php?lang=$lang&error=db_error");
    exit;
}