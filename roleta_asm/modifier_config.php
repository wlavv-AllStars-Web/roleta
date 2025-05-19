<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Activer l'affichage des erreurs en développement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$configPath = 'roleta_config.json';
$config = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];
if (!is_array($config)) $config = [];

if (!file_exists('uploads')) {
    mkdir('uploads', 0755, true);
}

// Traitement du fond
if (isset($_FILES['background']) && $_FILES['background']['error'] === 0) {
    $bgName = 'bg_' . time() . '_' . basename($_FILES['background']['name']);
    $bgPath = "uploads/$bgName";
    if (move_uploaded_file($_FILES['background']['tmp_name'], $bgPath)) {
        $config['background'] = $bgPath;
    }
}

// Traitement du logo
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
    $logoName = 'logo_' . time() . '_' . basename($_FILES['logo']['name']);
    $logoPath = "uploads/$logoName";
    if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath)) {
        $config['logo'] = $logoPath;
    }
}

// Couleurs
if (isset($_POST['color1']) && isset($_POST['color2'])) {
    $config['colors'] = [$_POST['color1'], $_POST['color2']];
}

// Sauvegarde
file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT));

// Redirection propre
header("Location: admin.php?success=config_updated");
exit;
?>