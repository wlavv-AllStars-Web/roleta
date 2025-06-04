<?php

$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'pt';

// Normaliser le code langue (ex: éviter des caractères bizarres)
$lang = preg_replace('/[^a-z]{2}/i', '', strtolower($lang));

// Sauvegarder la langue choisie en session et cookie
$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + (86400 * 30), "/");

// Charger le fichier de langue correspondant
$langFile = __DIR__ . '/menu/' . $lang . '.php';

// Si fichier langue introuvable, fallback vers pt (portugais)
if (!file_exists($langFile)) {
    $langFile = __DIR__ . '/menu/pt.php';
}

// Inclure le tableau de traductions
$__menu = include $langFile;

// Fonction pour récupérer la traduction d'une clé
function __menu(string $key): string {
    global $__menu;
    return $__menu[$key] ?? $key;
}