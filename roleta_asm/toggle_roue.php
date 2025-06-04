<?php
session_start();
require_once 'db.php';

$admin_id = (int)($_POST['admin_id'] ?? 0);
$new_state = (int)($_POST['new_state'] ?? 1);

$stmt = $pdo->prepare("UPDATE admins SET wheel_active = ? WHERE admin_id = ?");
$stmt->execute([$new_state, $admin_id]);

header("Location: admin.php?success=toggle");
exit;

$admin_id = (int) $_POST['admin_id'];
$new_state = (int) $_POST['new_state'];

// Assurez-vous que l'admin actuel ne modifie que son propre statut
if ($_SESSION['admin_id'] !== $admin_id) {
    header("Location: admin.php?error=unauthorized");
    exit;
}

// Met à jour l'état actif
$stmt = $pdo->prepare("UPDATE admins SET active = ? WHERE admin_id = ?");
$success = $stmt->execute([$new_state, $admin_id]);

if ($success) {
    header("Location: admin.php?success=wheel_state_updated");
} else {
    header("Location: admin.php?error=update_failed");
}
exit;