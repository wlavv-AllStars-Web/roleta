<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'pt';
$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + (86400 * 30), "/");

include 'lang.php';
include 'db.php';
include 'config.php';

$user_id = $_SESSION['user_id'];

// Charger les paramètres utilisateur depuis la base
$settings = [];
$stmt = $pdo->prepare("SELECT setting_name, setting_value FROM settings WHERE user_id = ?");
$stmt->execute([$user_id]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $s) {
    $settings[$s['setting_name']] = $s['setting_value'];
}

$background = $settings['background'] ?? $config['background'] ?? 'uploads/bg_1747646083_(Customize background with admin account).jpg';
$logo = $settings['logo'] ?? $config['logo'] ?? 'assets/default-logo.png';
$color1 = $settings['color1'] ?? $config['colors'][0] ?? '#FF0000';
$color2 = $settings['color2'] ?? $config['colors'][1] ?? '#FF6347';

include 'head.php';

// Historique des résultats
$stmt = $pdo->prepare("
    SELECT r.played_at, p.name AS prize_name
    FROM roleta_results r
    JOIN roleta_prizes p ON r.prize_id = p.id
    WHERE p.user_id = ?
    ORDER BY r.played_at DESC
");
$stmt->execute([$user_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lots
$stmt = $pdo->prepare("
    SELECT id, name, stock AS total_stock, active
    FROM roleta_prizes
    WHERE user_id = ?
    ORDER BY name
");
$stmt->execute([$user_id]);
$prizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
button i {
    margin-right: 6px;
}
</style>

<body>
<div class="login-container">
    <h2><?= __menu('admin_title') ?></h2>

    <form method="GET" id="lang-form" style="margin-bottom:20px;">
        <div class="lang-select-container">
            <select name="lang" onchange="this.form.submit()" class="styled-select">
                <option value="fr" <?= $lang === 'fr' ? 'selected' : '' ?>>🇫🇷 Français</option>
                <option value="pt" <?= $lang === 'pt' ? 'selected' : '' ?>>🇵🇹 Português</option>
                <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
                <option value="es" <?= $lang === 'es' ? 'selected' : '' ?>>🇪🇸 Español</option>
            </select>
        </div>
    </form>

    <?php if (isset($_GET['success'])): ?>
        <div style="color: green; margin-bottom: 10px;">
            <?= __menu('success_' . htmlspecialchars($_GET['success'])) ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div style="color: red; margin-bottom: 10px;">
            <?= __menu('error_' . htmlspecialchars($_GET['error'])) ?>
        </div>
    <?php endif; ?>

    <h3><?= __menu('result_history') ?></h3>
    <div class="history-scroll">
        <table>
            <thead>
                <tr>
                    <th><?= __menu('prize') ?></th>
                    <th><?= __menu('date') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['prize_name']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['played_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="reset-container" style="margin-top: 20px;">
        <a href="reinitialiser_resultats.php?lang=<?= $lang ?>" onclick="return confirm('<?= __menu('confirm_reset') ?>');">
            <button class="btn-red"><i class="fas fa-rotate-left"></i> <?= __menu('reset_results') ?></button>
        </a>
    </div>

    <h3><?= __menu('prize_list') ?></h3>
    <table>
        <thead>
            <tr>
                <th><?= __menu('prize_name') ?></th>
                <th><?= __menu('stock_quantity') ?></th>
                <th><?= __menu('action') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prizes as $prize): ?>
                <tr>
                    <td><?= htmlspecialchars($prize['name']) ?></td>
                    <td><?= (int)$prize['total_stock'] ?></td>
                    <td>
                        <a href="modifier_prix.php?id=<?= $prize['id'] ?>&lang=<?= $lang ?>">
                            <button class="btn-red"><i class="fas fa-pen"></i> <?= __menu('edit') ?></button>
                        </a>
                        <a href="supprimer_prix.php?id=<?= $prize['id'] ?>&lang=<?= $lang ?>" onclick="return confirm('<?= __menu('confirm_delete') ?>');">
                            <button class="btn-red"><i class="fas fa-trash"></i> <?= __menu('delete') ?></button>
                        </a>
                        <a href="toggle_active.php?id=<?= $prize['id'] ?>&lang=<?= $lang ?>">
                            <button class="<?= $prize['active'] ? 'btn-red' : 'btn-green' ?>">
                                <i class="fas fa-toggle-<?= $prize['active'] ? 'off' : 'on' ?>"></i>
                                <?= $prize['active'] ? __menu('deactivate') : __menu('activate') ?>
                            </button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="add-prize-container" style="margin-top: 20px;">
        <a href="ajouter_prix.php?lang=<?= $lang ?>">
            <button class="btn-red"><i class="fas fa-plus"></i> <?= __menu('add_prize') ?></button>
        </a>
    </div>

    <h3><?= __menu('customize_wheel') ?></h3>
    <form action="modifier_config.php" method="post" enctype="multipart/form-data" style="margin-bottom: 40px;">
        <div class="form-row">
            <div>
                <label><?= __menu('background_image') ?> :</label>
                <input type="file" name="background">
            </div>
            <div>
                <label><?= __menu('logo_image') ?> :</label>
                <input type="file" name="logo">
            </div>
        </div>
        <div class="form-row">
            <div>
                <label><?= __menu('slice_color1') ?> :</label>
                <input type="color" name="color1" value="<?= htmlspecialchars($color1) ?>">
            </div>
            <div>
                <label><?= __menu('slice_color2') ?> :</label>
                <input type="color" name="color2" value="<?= htmlspecialchars($color2) ?>">
            </div>
        </div>
        <button type="submit" class="btn-red"><i class="fas fa-save"></i> <?= __menu('save_changes') ?></button>
    </form>

    <div class="admin-buttons">
        <a href="logout.php?lang=<?= $lang ?>"><button class="btn-red"><i class="fas fa-sign-out-alt"></i> <?= __menu('logout') ?></button></a>
        <a href="roleta.php?lang=<?= $lang ?>"><button class="btn-red"><i class="fas fa-circle-notch"></i> <?= __menu('wheel') ?></button></a>
    </div>
</div>
</body>
</html>
