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
include 'head.php';

// Chargement config perso roue pour le fond et couleurs
$config = json_decode(file_get_contents('roleta_config.json'), true);
$background = $config['background'] ?? 'default-bg.jpg';
$color1 = $config['colors'][0] ?? '#FF0000';
$color2 = $config['colors'][1] ?? '#FF6347';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $name = trim($_POST['name'] ?? '');
    $stock = intval($_POST['stock'] ?? 0);

    if (empty($name) || $stock <= 0) {
        $error = __menu('error_invalid_data');
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO roleta_prizes (name, stock) VALUES (:name, :stock)");
            $stmt->execute(['name' => $name, 'stock' => $stock]);
            header('Location: admin.php?lang=' . $lang . '&message=' . urlencode(__menu('prize_added')));
            exit;
        } catch (PDOException $e) {
            $error = "Erreur SQL : " . $e->getMessage();
        }
    }
}
?>

<style>
    body {
        background: url('<?= htmlspecialchars($background) ?>') no-repeat center center,
                    radial-gradient(red, black);
        background-size: cover;
    }

    .btn-red {
        background-color: <?= htmlspecialchars($color1) ?>;
        color: white;
    }

    .btn-red:hover {
        background-color: <?= htmlspecialchars($color1) ?>cc;
    }

    .btn-green {
        background-color: <?= htmlspecialchars($color2) ?>;
        color: white;
    }

    .btn-green:hover {
        background-color: <?= htmlspecialchars($color2) ?>cc;
    }
</style>

<body>
<div class="login-container">
    <h2><?= __menu('add_prize') ?></h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="ajouter_prix.php?lang=<?= $lang ?>">
        <label for="name"><?= __menu('prize_name') ?> :</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="stock"><?= __menu('stock_quantity') ?> :</label>
        <input type="number" id="stock" name="stock" min="1" required>
        <br>
        <button type="submit" class="btn-red"><?= __menu('add') ?></button>
    </form>

    <div class="admin-buttons" style="margin-top: 20px;">
        <a href="admin.php?lang=<?= $lang ?>"><button class="btn-green"><?= __menu('back_to_admin') ?></button></a>
    </div>
</div>
</body>
</html>