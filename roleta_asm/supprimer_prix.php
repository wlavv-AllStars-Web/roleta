<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$user_id = $_SESSION['user_id'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // Supprimer uniquement si ce lot appartient à l'utilisateur
        $stmt = $pdo->prepare("DELETE FROM roleta_prizes WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $id, 'user_id' => $user_id]);

        header('Location: admin.php?delete=success');
        exit;
    } catch (PDOException $e) {
        header('Location: admin.php?delete=error');
        exit;
    }
} else {
    header('Location: admin.php?delete=invalid_id');
    exit;
}
