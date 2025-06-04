<?php
session_start();

$lang = $_GET['lang'] ?? $_POST['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'pt';
$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + (86400 * 30), "/");

include 'lang.php';
include 'db.php';

$configPath = 'roleta_config.json';
$config = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];
if (!is_array($config)) $config = [];

$background = $config['background'] ?? '';
$color1 = $config['colors'][0] ?? '#FF0000';
$color2 = $config['colors'][1] ?? '#FF6347';

$backgroundCss = $background
    ? "background: url('" . htmlspecialchars($background) . "') no-repeat center center fixed; background-size: cover;"
    : "background: url('https://www.all-stars-motorsport.com/img/app_icons/back_roleta.png') no-repeat center center,
       radial-gradient(red, black); background-size: cover; filter: blur(0.5px);";

$error = '';
$admin = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        if ($admin['active'] == 0) {
            // Account exists but not yet approved
            header("Location: pending_approval.php?lang=$lang");
            exit;
        } elseif (!$admin['active']) {
            $error = __menu('error_account_disabled');
        } else {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['is_superadmin'] = !empty($admin['is_superadmin']) ? true : false;

            if ($_SESSION['is_superadmin']) {
                header("Location: super_admin.php?lang=$lang");
            } else {
                header("Location: admin.php?lang=$lang");
            }
            exit;
        }
    } else {
        $error = __menu('login_error');
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
    <?= $backgroundCss ?>
}
</style>

<body>
<div class="login-container">
    <h2><?= __menu('admin_login') ?></h2>

    <form method="POST" action="login.php?lang=<?= htmlspecialchars($lang) ?>">
        <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>">
        <input type="text" name="username" placeholder="<?= __menu('username') ?>" required><br><br>
        <input type="password" name="password" placeholder="<?= __menu('password') ?>" required><br><br>
        <button type="submit" class="btn-red"><?= __menu('login_button') ?></button>
    </form>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div style="margin-top: 20px;">
        <a href="register.php?lang=<?= htmlspecialchars($lang) ?>">
            <button class="btn-green"><?= __menu('create_account') ?></button>
        </a>
    </div>
</div>
</body>
</html>