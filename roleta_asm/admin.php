<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Gestion de la langue
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'pt';
$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + (86400 * 30), "/");

include 'lang.php';
include 'db.php';
include 'head.php';

// Chargement de la configuration
$config = json_decode(file_get_contents('roleta_config.json'), true);
$background = $config['background'] ?? 'default-bg.jpg';
$color1 = $config['colors'][0] ?? '#FF0000';
$color2 = $config['colors'][1] ?? '#FF6347';

// Sécurité
$results = [];
$prizes = [];

try {
    $stmt = $pdo->query("SELECT r.played_at, p.name AS prize_name FROM roleta_results r JOIN roleta_prizes p ON r.prize_id = p.id ORDER BY r.played_at DESC");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT MIN(id) AS id, name, SUM(stock) AS total_stock, MAX(active) AS active FROM roleta_prizes GROUP BY name ORDER BY name");
    $prizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erreur SQL : " . $e->getMessage() . "</p>";
}
?>

<style>
    :root {
        --color1: <?= htmlspecialchars($color1) ?>;
        --color2: <?= htmlspecialchars($color2) ?>;
    }

    body {
        background: 
            url('<?= htmlspecialchars($background) ?>') no-repeat center center,
            radial-gradient(var(--color1), black);
        background-size: cover;
    }
</style>

<body>
<div class="login-container">
    <h2><?= __menu('admin_title') ?></h2>

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
            <button class="btn-red"><?= __menu('reset_results') ?></button>
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
                    <td><?= $prize['total_stock'] ?></td>
                    <td>
    <a href="modifier_prix.php?id=<?= $prize['id'] ?>&lang=<?= $lang ?>">
        <button class="btn-red"><?= __menu('edit') ?></button>
    </a>
    <a href="supprimer_prix.php?id=<?= $prize['id'] ?>&lang=<?= $lang ?>" onclick="return confirm('<?= __menu('confirm_delete') ?>');">
        <button class="btn-red"><?= __menu('delete') ?></button>
    </a>
    <a href="toggle_active.php?id=<?= $prize['id'] ?>&lang=<?= $lang ?>">
        <button class="<?= $prize['active'] ? 'btn-red' : 'btn-green' ?>">
            <?= $prize['active'] ? __menu('deactivate') : __menu('activate') ?>
        </button>
    </a>
</td>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="add-prize-container" style="margin-top: 20px;">
        <a href="ajouter_prix.php?lang=<?= $lang ?>">
            <button class="btn-red"><?= __menu('add_prize') ?></button>
        </a>
    </div>

    <h3><?= __menu('customize_wheel') ?? "Personnaliser la roue" ?></h3>
    <form action="modifier_config.php" method="post" enctype="multipart/form-data" style="margin-bottom: 40px;">
        <div>
            <label><?= __menu('background_image') ?> :</label><br>
            <input type="file" name="background">
        </div>
        <div>
            <label><?= __menu('logo_image') ?> :</label><br>
            <input type="file" name="logo">
        </div>
        <div>
            <label><?= __menu('slice_color1') ?> :</label><br>
            <input type="color" name="color1" value="<?= htmlspecialchars($color1) ?>">
        </div>
        <div>
            <label><?= __menu('slice_color2') ?> :</label><br>
            <input type="color" name="color2" value="<?= htmlspecialchars($color2) ?>">
        </div>
        <button type="submit" class="btn-red"><?= __menu('save_changes') ?></button>
    </form>

    <div class="admin-buttons">
        <a href="login.php?lang=<?= $lang ?>"><button class="btn-red"><?= __menu('logout') ?></button></a>
        <a href="roleta.php?lang=<?= $lang ?>"><button class="btn-red"><?= __menu('wheel') ?></button></a>
    </div>
</div>
</body>
</html>