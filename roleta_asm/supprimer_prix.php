<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
    
        $stmt = $pdo->prepare("DELETE FROM roleta_prizes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

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
?>