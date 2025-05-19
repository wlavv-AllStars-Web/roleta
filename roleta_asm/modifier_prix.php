<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'pt';
$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + (86400 * 30), "/");

include 'lang.php';
include 'db.php';

// Chargement config perso roue pour le fond et couleurs
$config = json_decode(file_get_contents('roleta_config.json'), true);
$background = $config['background'] ?? 'default-bg.jpg';
$color1 = $config['colors'][0] ?? '#FF0000';
$color2 = $config['colors'][1] ?? '#FF6347';

if (!isset($_GET['id'])) {
    die(__menu('missing_id'));
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStock = intval($_POST['stock']);
    $stmt = $pdo->prepare("UPDATE roleta_prizes SET stock = ? WHERE id = ?");
    $stmt->execute([$newStock, $id]);
    header("Location: admin.php?lang=$lang&success=update_stock");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM roleta_prizes WHERE id = ?");
$stmt->execute([$id]);
$lot = $stmt->fetch();

if (!$lot) {
    die(__menu('prize_not_found'));
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
                    radial-gradient(red, black);
        background-size: cover;
    }
</style>
<body>
<div class="login-container">
    <h2><?= __menu('edit_prize') ?></h2>
    <form method="post" action="modifier_prix.php?id=<?= $id ?>&lang=<?= $lang ?>">
        <label><?= __menu('prize_name') ?>: <?= htmlspecialchars($lot['name']) ?></label>
        <label><?= __menu('new_stock') ?>:</label>
        <input type="number" name="stock" value="<?= $lot['stock'] ?>" required>
        <div class="button-container">
            <input type="submit" class="btn btn-red" value="<?= __menu('update_button') ?>">
            <a href="admin.php?lang=<?= $lang ?>" class="btn btn-green"><?= __menu('back_to_admin') ?></a>
        </div>
    </form>
</div>
</body>
</html>