<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$admin_id = $_SESSION['admin_id'];
$id = $_GET['id'] ?? null;
$lang = $_GET['lang'] ?? 'pt';

if (!$id || !is_numeric($id)) {
    header("Location: admin.php?lang=$lang&error=missing_id");
    exit;
}

try {
    // Vérifie que ce lot appartient bien à l'utilisateur connecté
    $stmt = $pdo->prepare("SELECT active FROM roleta_prizes WHERE id = ? AND admin_id = ?");
    $stmt->execute([$id, $admin_id]);
    $prize = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prize) {
        header("Location: admin.php?lang=$lang&error=not_found");
        exit;
    }

    $newStatus = $prize['active'] ? 0 : 1;

    $update = $pdo->prepare("UPDATE roleta_prizes SET active = ? WHERE id = ? AND admin_id = ?");
    $update->execute([$newStatus, $id, $admin_id]);

    header("Location: admin.php?lang=$lang&success=status_updated");
    exit;

} catch (PDOException $e) {
    header("Location: admin.php?lang=$lang&error=db_error");
    exit;
}