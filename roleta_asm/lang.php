<?php

$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'pt';


$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + (86400 * 30), "/");


$langFile = __DIR__ . '/menu/' . $lang . '.php';


if (!file_exists($langFile)) {
    $langFile = __DIR__ . '/menu/pt.php';
}

$__menu = include $langFile;

// Fonction pour récupérer la traduction d'une clé
function __menu($key) {
    global $__menu;
    return $__menu[$key] ?? $key;
}
?>