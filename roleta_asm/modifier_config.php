<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$admin_id = $_SESSION['admin_id'];

if (!file_exists('uploads')) {
    mkdir('uploads', 0755, true);
}

try {
    // Traitement de l’image de fond
    if (isset($_FILES['background']) && $_FILES['background']['error'] === 0) {
        $bgName = 'bg_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['background']['name']));
        $bgPath = "uploads/$bgName";
        if (move_uploaded_file($_FILES['background']['tmp_name'], $bgPath)) {
            $stmt = $pdo->prepare("REPLACE INTO settings (setting_name, setting_value, admin_id) VALUES ('background', ?, ?)");
            $stmt->execute([$bgPath, $admin_id]);
        }
    }

    // Traitement du logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $logoName = 'logo_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['logo']['name']));
        $logoPath = "uploads/$logoName";
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath)) {
            $stmt = $pdo->prepare("REPLACE INTO settings (setting_name, setting_value, admin_id) VALUES ('logo', ?, ?)");
            $stmt->execute([$logoPath, $admin_id]);
        }
    }

    // Couleurs de la roue
    if (isset($_POST['color1']) && isset($_POST['color2'])) {
        $color1 = $_POST['color1'];
        $color2 = $_POST['color2'];

        // On fait deux requêtes REPLACE séparées (pour éviter soucis syntaxiques)
        $stmt = $pdo->prepare("REPLACE INTO settings (setting_name, setting_value, admin_id) VALUES ('color1', ?, ?)");
        $stmt->execute([$color1, $admin_id]);

        $stmt = $pdo->prepare("REPLACE INTO settings (setting_name, setting_value, admin_id) VALUES ('color2', ?, ?)");
        $stmt->execute([$color2, $admin_id]);
    }

    header("Location: admin.php?success=config_updated");
    exit;
} catch (PDOException $e) {
    header("Location: admin.php?error=config_error");
    exit;
}