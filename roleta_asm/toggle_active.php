<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$id = $_GET['id'] ?? null;
$lang = $_GET['lang'] ?? 'pt';
$user_id = $_SESSION['user_id'];

if (!$id || !is_numeric($id)) {
    header("Location: admin.php?lang=$lang&error=missing_id");
    exit;
}

try {
    // Vérifie que ce lot appartient bien à l'utilisateur connecté
    $stmt = $pdo->prepare("SELECT active FROM roleta_prizes WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    $prize = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prize) {
        header("Location: admin.php?lang=$lang&error=not_found");
        exit;
    }

    $newStatus = $prize['active'] ? 0 : 1;

    $update = $pdo->prepare("UPDATE roleta_prizes SET active = ? WHERE id = ? AND user_id = ?");
    $update->execute([$newStatus, $id, $user_id]);

    header("Location: admin.php?lang=$lang&success=status_updated");
    exit;

} catch (PDOException $e) {
    header("Location: admin.php?lang=$lang&error=db_error");
    exit;
}
