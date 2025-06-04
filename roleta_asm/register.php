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

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username && $password && $confirmPassword) {
        if ($password !== $confirmPassword) {
            $error = __menu('password_mismatch');
        } else {
            // Check if username exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetchColumn() > 0) {
                $error = __menu('username_taken');
            } else {
                // New accounts are inactive by default (active = 0)
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO admins (username, password, active, is_superadmin) VALUES (?, ?, 0, 0)");
                $stmt->execute([$username, $hashedPassword]);
                $success = __menu('account_pending_approval');
            }
        }
    } else {
        $error = __menu('fill_all_fields');
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
    <h2><?= __menu('create_account') ?></h2>

    <?php if ($success): ?>
        <p style="color: lightgreen;"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="register.php?lang=<?= htmlspecialchars($lang) ?>">
        <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>">
        <input type="text" name="username" placeholder="<?= __menu('username') ?>" required><br><br>
        <input type="password" name="password" placeholder="<?= __menu('password') ?>" required><br><br>
        <input type="password" name="confirm_password" placeholder="<?= __menu('confirm_password') ?>" required><br><br>
        <button type="submit" class="btn-green"><?= __menu('register') ?></button>
    </form>

    <div style="margin-top: 20px;">
        <a href="login.php?lang=<?= htmlspecialchars($lang) ?>">
            <button class="btn-red"><?= __menu('back_to_login') ?></button>
        </a>
    </div>
</div>
</body>
</html>