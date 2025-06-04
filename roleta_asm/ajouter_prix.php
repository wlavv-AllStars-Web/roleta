<?php

require_once 'db.php';
require_once 'lang.php';
require_once 'config.php';

// Vérification admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Gestion persistante de la langue
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'pt';
$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + (86400 * 30), "/");

// Récupération des préférences d'apparence admin
$settings = [];
$stmt = $pdo->prepare("SELECT setting_name, setting_value FROM settings WHERE admin_id = ?");
$stmt->execute([$admin_id]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $s) {
    $settings[$s['setting_name']] = $s['setting_value'];
}

$background = $settings['background'] ?? $config['background'] ?? 'uploads/bg_default.jpg';
$logo = $settings['logo'] ?? $config['logo'] ?? 'assets/default-logo.png';
$color1 = $settings['color1'] ?? $config['colors'][0] ?? '#FF0000';
$color2 = $settings['color2'] ?? $config['colors'][1] ?? '#FF0000';

$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    if (empty($name) || $stock === false) {
        $error = __menu('error_invalid_data');
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO roleta_prizes (name, stock, admin_id, active) VALUES (?, ?, ?, 1)");
            $stmt->execute([$name, $stock, $admin_id]);
            header("Location: admin.php?lang=$lang&success=prize_added");
            exit;
        } catch (PDOException $e) {
            $error = "Erreur SQL : " . htmlspecialchars($e->getMessage());
        }
    }
}

include 'head.php';
?>

<style>
:root {
    --color1: <?= htmlspecialchars($color1) ?>;
    --color2: <?= htmlspecialchars($color2) ?>;
}
body {
    background: url('<?= htmlspecialchars($background) ?>') no-repeat center center,
                radial-gradient(var(--color1), black);
    background-size: cover;
}
button {
    cursor: pointer;
}
</style>

<body>
<div class="login-container">
    <h2><?= __menu('add_prize') ?></h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="ajouter_prix.php?lang=<?= htmlspecialchars($lang) ?>">
        <label for="name"><?= __menu('prize_name') ?> :</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="stock"><?= __menu('stock_quantity') ?> :</label>
        <input type="number" id="stock" name="stock" min="1" required>
        <br>
        <button type="submit" class="btn-red"><i class="fas fa-plus"></i> <?= __menu('add') ?></button>
    </form>

    <div class="admin-buttons" style="margin-top: 20px;">
        <a href="admin.php?lang=<?= htmlspecialchars($lang) ?>"><button class="btn-green"><i class="fas fa-arrow-left"></i> <?= __menu('back_to_admin') ?></button></a>
    </div>
</div>
</body>
</html>