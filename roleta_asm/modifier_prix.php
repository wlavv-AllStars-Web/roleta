<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'pt';
$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + (86400 * 30), "/");

include 'lang.php';
include 'db.php';
include 'config.php';

$admin_id = $_SESSION['admin_id']; // Correction ici

if (!isset($_GET['id'])) {
    die(__menu('missing_id'));
}

$id = intval($_GET['id']);

// Vérifie que l’admin peut modifier ce lot
$stmt = $pdo->prepare("SELECT * FROM roleta_prizes WHERE id = ? AND admin_id = ?");
$stmt->execute([$id, $admin_id]);
$lot = $stmt->fetch();

if (!$lot) {
    die(__menu('prize_not_found'));
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStock = intval($_POST['stock']);
$newName = trim($_POST['name'] ?? '');

if ($newStock <= 0) {
    $error = __menu('error_invalid_stock');
} elseif ($newName === '') {
    $error = __menu('error_empty_name') ?? 'Nom vide';
} else {
    $stmt = $pdo->prepare("UPDATE roleta_prizes SET name = ?, stock = ? WHERE id = ? AND admin_id = ?");
    $stmt->execute([$newName, $newStock, $id, $admin_id]);
    header("Location: admin.php?lang=$lang&success=update_prize");
    exit;
}

}

$settings = [];
$stmt = $pdo->prepare("SELECT setting_name, setting_value FROM settings WHERE admin_id = ?");
$stmt->execute([$admin_id]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $s) {
    $settings[$s['setting_name']] = $s['setting_value'];
}

$background = $settings['background'] ?? ($config['background'] ?? '');
$color1 = $settings['color1'] ?? ($config['colors'][0] ?? '#FF0000');
$color2 = $settings['color2'] ?? ($config['colors'][1] ?? '#FF6347');

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
</style>

<body>
<div class="login-container">
    <h2><?= __menu('edit_prize') ?></h2>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="modifier_prix.php?id=<?= $id ?>&lang=<?= htmlspecialchars($lang) ?>">
        <label for="name"><?= __menu('prize_name') ?>:</label><br>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($lot['name']) ?>" required><br><br>

        <label for="stock"><?= __menu('new_stock') ?>:</label><br>
        <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($lot['stock']) ?>" min="1" required>

        <div class="button-container">
            <input type="submit" class="btn btn-red" value="<?= __menu('update_button') ?>">
            <a href="admin.php?lang=<?= htmlspecialchars($lang) ?>" class="btn btn-green"><?= __menu('back_to_admin') ?></a>
        </div>
    </form>
</div>
</body>
</html>