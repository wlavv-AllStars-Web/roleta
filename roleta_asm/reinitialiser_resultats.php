<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM roleta_results WHERE user_id = ?");
    $stmt->execute([$user_id]);

    header('Location: admin.php?reset=success');
    exit;
} catch (PDOException $e) {
    header('Location: admin.php?reset=error');
    exit;
}
