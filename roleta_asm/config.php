<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

// Charger la config globale depuis le fichier JSON
$configPath = 'roleta_config.json';
$globalConfig = [];

if (file_exists($configPath)) {
    $jsonContent = file_get_contents($configPath);
    $decoded = json_decode($jsonContent, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $globalConfig = $decoded;
    }
}

// Valeurs par défaut si JSON absent ou invalide
$defaultBackground = 'assets/default-bg.jpg';
$defaultLogo = 'assets/default-logo.png';
$defaultColors = ['#FF0000', '#FF0000'];

$background = $globalConfig['background'] ?? $defaultBackground;
$logo = $globalConfig['logo'] ?? $defaultLogo;
$colors = $globalConfig['colors'] ?? $defaultColors;

// Surcharger avec paramètres admin s'ils sont disponibles
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    try {
        $stmt = $pdo->prepare("SELECT setting_name, setting_value FROM settings WHERE admin_id = ?");
        $stmt->execute([$admin_id]);

        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        if (!empty($settings)) {
            $background = $settings['background'] ?? $background;
            $logo = $settings['logo'] ?? $logo;
            $colors[0] = $settings['color1'] ?? $colors[0];
            $colors[1] = $settings['color2'] ?? $colors[1];
        }
    } catch (PDOException $e) {
        // En production, loguer proprement l'erreur
        error_log("Erreur de chargement des paramètres admin : " . $e->getMessage());
    }
}

// Configuration globale à utiliser partout
$config = [
    'background' => $background,
    'logo' => $logo,
    'colors' => $colors
];