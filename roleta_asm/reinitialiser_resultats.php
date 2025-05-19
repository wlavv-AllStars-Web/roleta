<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

try {
    $pdo->exec("DELETE FROM roleta_results");
    header('Location: admin.php?reset=success');
    exit;
} catch (PDOException $e) {
    header('Location: admin.php?reset=error');
    exit;
}