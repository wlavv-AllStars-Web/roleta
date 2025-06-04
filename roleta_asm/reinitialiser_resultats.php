<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$admin_id = $_SESSION['admin_id'];  // Correction ici, on nomme $admin_id pour être cohérent

try {
    $stmt = $pdo->prepare("DELETE FROM roleta_results WHERE admin_id = ?");
    $stmt->execute([$admin_id]);

    header('Location: admin.php?reset=success');
    exit;
} catch (PDOException $e) {
    header('Location: admin.php?reset=error');
    exit;
}