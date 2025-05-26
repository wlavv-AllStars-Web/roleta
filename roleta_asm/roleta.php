<?php
session_start();
include 'lang.php';
include 'db.php';
include 'config.php';

// Détecter la langue
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'fr';
$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + (86400 * 30), "/");

// Paramètres par défaut
$background = 'assets/default-bg.jpg';
$logo = 'uploads/uploads/logo_1747642844_9954828-logo-chat-rond-modele-silhouette-chat-illustrationle-vectoriel.jpg';
$colors = ['#FF0000', '#FF6347'];
$prizes = [];

// Si l'utilisateur est connecté : charger ses paramètres et ses lots
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Charger les paramètres
    $stmt = $pdo->prepare("SELECT setting_name, setting_value FROM settings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $background = $settings['background'] ?? $background;
    $logo = $settings['logo'] ?? $logo;
    $colors = [
        $settings['color1'] ?? $colors[0],
        $settings['color2'] ?? $colors[1]
    ];

    // Charger les lots actifs avec du stock
    $stmt = $pdo->prepare("SELECT name, stock FROM roleta_prizes WHERE active = 1 AND stock > 0 AND user_id = ?");
    $stmt->execute([$user_id]);
    $prizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$slices = [];
foreach ($prizes as $prize) {
    $count = (int)$prize['stock'];
    for ($i = 0; $i < $count; $i++) {
        $slices[] = trim($prize['name']);
    }
}

shuffle($slices);
$slices = array_slice($slices, 0, 22);

// **Modification ici : Si aucun lot, créer une roue neutre**
if (empty($slices)) {
    $defaultSliceCount = 12;
    $slices = array_fill(0, $defaultSliceCount, "–");  // texte neutre
    $colors = array_fill(0, $defaultSliceCount, "#888888"); // gris neutre
}

include 'head.php';

// Style de fond
$backgroundCss = $background === 'assets/default-bg.jpg'
    ? "background: url('uploads/bg_1747646083_(Customize background with admin account).jpg') no-repeat center center, radial-gradient(red, black); background-size: cover; filter: blur(0.5px);"
    : "background: url('" . htmlspecialchars($background) . "') no-repeat center center fixed; background-size: cover;";
?>

<?php if (!isset($_SESSION['user_id'])): ?>
    <a href="login.php?lang=<?= $lang ?>" class="login-button"><?= __menu("login") ?></a>
<?php endif; ?>

<body style="<?= $backgroundCss ?>">

<form method="GET" id="lang-form">
    <div class="lang-select-container">
        <select name="lang" onchange="this.form.submit()" class="styled-select">
            <option value="fr" <?= $lang === 'fr' ? 'selected' : '' ?>>🇫🇷 Français</option>
            <option value="pt" <?= $lang === 'pt' ? 'selected' : '' ?>>🇵🇹 Português</option>
            <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
            <option value="es" <?= $lang === 'es' ? 'selected' : '' ?>>🇪🇸 Español</option>
        </select>
    </div>
</form>

<?php if (!isset($_SESSION['user_id'])): ?>
    <a href="login.php?lang=<?= $lang ?>" class="login-button"><?= __menu("login") ?></a>
<?php endif; ?>

<audio id="spin-sound" src="assets/spin.mp3" preload="auto"></audio>
<audio id="prize" src="assets/prise.mp3" preload="auto"></audio>
<audio id="ambiance" src="assets/ambiance.mp3" preload="auto" loop></audio>

<div class="roleta-container">
    <div class="roleta" id="roleta" onclick="<?= empty($_SESSION['user_id']) ? 'void(0)' : 'girarRoleta()' ?>"></div>
    <div class="logo" style="background-image: url('<?= htmlspecialchars($logo) ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
    <div class="ponteiro"></div>
</div>

<div class="resultado" id="resultado" style="display:none;">
    <p id="message"><?= __menu("won") ?> <span id="prize-name"></span>!</p>
    <button onclick="closeResult()"><?= __menu("close") ?></button>
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
ambiance.volume = 0.2;
ambiance.play();

const sliceAngle = 360 / totalSlices;
const halfAngle = sliceAngle / 2;
const radius = 56;

function calcSectorClipPath(angle) {
    const rad = angle * Math.PI / 180;
    const x1 = 50 + totalSlices*radius * Math.cos(rad);
    const y1 = 50 + totalSlices*radius * Math.sin(rad);
    const x2 = 50 + totalSlices*radius * Math.cos(-rad);
    const y2 = 50 + totalSlices*radius * Math.sin(-rad);
    return `polygon(50% 50%, ${x1}% ${y1}%, ${x2}% ${y2}%)`;
}

for(let i = 0; i < totalSlices; i++) {
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

// Bloquer la rotation si utilisateur non connecté (pas de lots)
function girarRoleta() {
    if (spinning) return;
    spinning = true;

    const angle = Math.floor(Math.random() * 360) + 1080;
    lastRotation += angle;
    roleta.style.transform = `rotate(${lastRotation}deg)`;
    roleta.style.transition = 'transform 5.6s ease-out';
    spinSound.play();
    setTimeout(() => prizeSound.play(), 3400);

    setTimeout(() => {
        const finalAngle = lastRotation % 360;
        const correctedAngle = (360 - finalAngle + 270) % 360;
        const prizeIndex = Math.floor((correctedAngle + sliceAngle / 2) % 360 / sliceAngle);
        const prizeName = prizes[prizeIndex];

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
</script>
<?php if (isset($_SESSION['user_id'])): ?>
    <a href="admin.php?lang=<?= $lang ?>" class="admin-button"><?= __menu("admin") ?></a>
<?php endif; ?>
</body>
</html>
