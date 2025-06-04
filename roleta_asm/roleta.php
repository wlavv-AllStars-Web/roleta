<?php
include 'lang.php';
include 'db.php';
include 'config.php';

$langCode = $_GET['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'fr';
$_SESSION['lang'] = $langCode;
setcookie('lang', $langCode, time() + (86400 * 30), "/");

// Récupération de admin_id depuis l'URL ou session
$admin_id = isset($_GET['admin_id']) && is_numeric($_GET['admin_id'])
    ? (int)$_GET['admin_id']
    : ($_SESSION['admin_id'] ?? null);

// Booléen indiquant si l'admin est connecté ET correspond à l'admin_id de la roue
$isAdminConnected = isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin_id;

// Valeurs par défaut
$background = 'assets/default-bg.jpg';
$logo = 'assets/default-logo.png';
$colors = ['#FF0000', '#FF6347'];
$prizes = [];

if ($admin_id) {
    // Récupérer le nom admin (ou "Utilisateur" par défaut)
    $stmt = $pdo->prepare("SELECT username FROM admins WHERE admin_id = ?");
    $stmt->execute([$admin_id]);
    $admin_username = $stmt->fetchColumn() ?: 'Utilisateur';

    // Vérifier si la roue est active pour cet admin
    $stmt = $pdo->prepare("SELECT active FROM admins WHERE admin_id = ?");
    $stmt->execute([$admin_id]);
    $wheelActive = $stmt->fetchColumn();

    if ((int)$wheelActive !== 1) {
        include 'head.php';
        echo "<body style=\"background: radial-gradient(#111, #000); color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;\">
                <div class='wheel-disabled-message'>
                    <p>🚫 " . __menu('wheel_disabled_by_admin') . "</p>
                    <p><a href='login.php?lang=" . htmlspecialchars($langCode) . "'>" . __menu('login') . "</a></p>
                </div>
              </body></html>";
        exit;
    }

    // Charger paramètres admin (personnalisations)
    $stmt = $pdo->prepare("SELECT setting_name, setting_value FROM settings WHERE admin_id = ?");
    $stmt->execute([$admin_id]);
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $background = $settings['background'] ?? $background;
    $logo = $settings['logo'] ?? $logo;
    $colors = [
        $settings['color1'] ?? '#FF0000',
        $settings['color2'] ?? '#FF6347'
    ];

    // Charger les lots disponibles
    $stmt = $pdo->prepare("SELECT name, stock FROM roleta_prizes WHERE active = 1 AND stock > 0 AND admin_id = ?");
    $stmt->execute([$admin_id]);
    $prizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $admin_username = null; // Pas d'admin_id valide
}

$slices = [];
foreach ($prizes as $prize) {
    for ($i = 0; $i < (int)$prize['stock']; $i++) {
        $slices[] = trim($prize['name']);
    }
}

shuffle($slices);
$slices = array_slice($slices, 0, 22);

if (empty($slices)) {
    $slices = array_fill(0, 12, "–");
    $colors = array_fill(0, 12, "#888888");
}

include 'head.php';

$backgroundCss = $background === 'assets/default-bg.jpg'
    ? "background: url('uploads/bg_1747646083_(Customize background with admin account).jpg') no-repeat center center, radial-gradient(red, black); background-size: cover; filter: blur(0.5px);"
    : "background: url('" . htmlspecialchars($background) . "') no-repeat center center fixed; background-size: cover;";
?>

<style>
:root {
    --color1: <?= htmlspecialchars($colors[0] ?? '#FF0000') ?>;
    --color2: <?= htmlspecialchars($colors[1] ?? '#FF6347') ?>;
}
</style>

<body style="<?= $backgroundCss ?>">

<?php if (!empty($admin_username)): ?>
    <h1 class="username-title">
        <?= __menu("wheel_of") . ' ' . htmlspecialchars($admin_username) ?>
    </h1>
<?php endif; ?>

<?php if (!$isAdminConnected): ?>
    <a href="login.php?lang=<?= $langCode ?>" class="login-button"><?= __menu("login") ?></a>
<?php endif; ?>

<form method="GET" id="lang-form">
    <div class="lang-select-container">
        <select name="lang" onchange="this.form.submit()" class="styled-select">
            <option value="fr" <?= $langCode === 'fr' ? 'selected' : '' ?>>🇫🇷 Français</option>
            <option value="pt" <?= $langCode === 'pt' ? 'selected' : '' ?>>🇵🇹 Português</option>
            <option value="en" <?= $langCode === 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
            <option value="es" <?= $langCode === 'es' ? 'selected' : '' ?>>🇪🇸 Español</option>
        </select>
    </div>
</form>

<audio id="spin-sound" src="assets/spin.mp3" preload="auto"></audio>
<audio id="prize" src="assets/prize.mp3" preload="auto"></audio>
<audio id="ambiance" src="assets/ambiancesound.mp3" preload="auto" loop></audio>

<div class="roleta-container">
    <div class="roleta" id="roleta" onclick="<?= $admin_id ? 'girarRoleta()' : 'void(0)' ?>"></div>
    <div class="logo" style="background-image: url('<?= htmlspecialchars($logo) ?>'); cursor: pointer;"
         onclick="<?= $admin_id ? 'girarRoleta()' : 'void(0)' ?>"></div>
    <div class="ponteiro"></div>
</div>

<div class="resultado" id="resultado" style="display:none;">
    <p id="message"><?= __menu("won") ?> <span id="prize-name"></span>!</p>
    <button class="close-result-button" onclick="closeResult()"><?= __menu("close") ?></button>
</div>

<?php include 'footer.php'; ?>

<script>
const prizes = <?= json_encode($slices); ?>;
const colors = <?= json_encode($colors); ?>;
const totalSlices = prizes.length;
const roleta = document.getElementById('roleta');
let lastRotation = 0;
let spinning = false;

const spinSound = document.getElementById('spin-sound');
const prizeSound = document.getElementById('prize');
const ambiance = document.getElementById('ambiance');

spinSound.volume = 1;
prizeSound.volume = 1;
ambiance.volume = 1;

const sliceAngle = 360 / totalSlices;
const halfAngle = sliceAngle / 2;
const radius = 56;

function calcSectorClipPath(angle) {
    const rad = angle * Math.PI / 180;
    const x1 = 50 + totalSlices * radius * Math.cos(rad);
    const y1 = 50 + totalSlices * radius * Math.sin(rad);
    const x2 = 50 + totalSlices * radius * Math.cos(-rad);
    const y2 = 50 + totalSlices * radius * Math.sin(-rad);
    return `polygon(50% 50%, ${x1}% ${y1}%, ${x2}% ${y2}%)`;
}

for (let i = 0; i < totalSlices; i++) {
    const label = document.createElement('div');
    label.classList.add('label');
    label.style.position = 'absolute';
    label.style.width = '100%';
    label.style.height = '100%';
    label.style.top = '0';
    label.style.left = '0';
    label.style.clipPath = calcSectorClipPath(halfAngle);
    label.style.transform = `rotate(${sliceAngle * i}deg)`;
    label.style.backgroundColor = colors[i % colors.length];

    const span = document.createElement('span');
    span.textContent = prizes[i];
    span.style.position = 'absolute';
    span.style.top = '50%';
    span.style.left = '80%';
    span.style.transform = `translate(-50%, -50%) rotate(2deg)`;
    span.style.whiteSpace = 'nowrap';
    span.style.color = 'white';
    span.style.fontSize = '25px';
    span.style.textShadow = '1px 1px 3px rgba(0,0,0,0.7)';
    span.style.userSelect = 'none';

    label.appendChild(span);
    roleta.appendChild(label);
}

function girarRoleta() {
    if (spinning) return;
    spinning = true;

    spinSound.play().catch(e => console.warn("Spin sound blocked:", e));

    const angle = Math.floor(Math.random() * 360) + 1080;
    lastRotation += angle;
    roleta.style.transform = `rotate(${lastRotation}deg)`;
    roleta.style.transition = 'transform 5.6s ease-out';

    setTimeout(() => {
        const finalAngle = lastRotation % 360;
        const correctedAngle = (360 - finalAngle + 270) % 360;
        const prizeIndex = Math.floor((correctedAngle + sliceAngle / 2) % 360 / sliceAngle);
        const prizeName = prizes[prizeIndex];

        prizeSound.play().catch(e => console.warn("Prize sound blocked:", e));

        document.getElementById('prize-name').textContent = prizeName;
        document.getElementById('resultado').style.display = 'block';

        fetch('enregistrer_resultat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ prize_name: prizeName })
        }).then(r => r.json()).then(data => {
            if (!data.success) console.error("Erreur :", data.error);
        }).catch(console.error);
    }, 6000);
}

function closeResult() {
    document.getElementById('resultado').style.display = 'none';
    spinning = false;
}

window.addEventListener('load', () => {
    ambiance.play().catch(err => {
        console.warn('Lecture automatique de l’ambiance bloquée par le navigateur.', err);
    });
});
</script>

<?php if ($isAdminConnected): ?>
    <a href="admin.php?lang=<?= $langCode ?>" class="admin-button"><?= __menu("admin") ?></a>
<?php endif; ?>

</body>
</html>