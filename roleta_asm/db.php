<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=roleta;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion à la base : " . $e->getMessage());
}
?>