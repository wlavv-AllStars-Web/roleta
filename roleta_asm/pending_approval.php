<?php
session_start();

$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'pt';
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
    <h2><?= __menu('account_pending') ?></h2>
    
    <p><?= __menu('account_pending_message') ?></p>
    
    <div style="margin-top: 20px;">
        <a href="login.php?lang=<?= htmlspecialchars($lang) ?>">
            <button class="btn-red"><?= __menu('back_to_login') ?></button>
        </a>
    </div>
</div>
</body>
</html>