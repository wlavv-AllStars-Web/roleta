<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$admin_id = $_SESSION['admin_id'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // Supprimer uniquement si ce lot appartient à l'utilisateur
        $stmt = $pdo->prepare("DELETE FROM roleta_prizes WHERE id = :id AND admin_id = :admin_id");
        $stmt->execute(['id' => $id, 'admin_id' => $admin_id]);

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