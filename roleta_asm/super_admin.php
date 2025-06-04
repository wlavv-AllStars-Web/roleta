<?php
session_start();

// Redirection si non connecté ou pas super admin
if (!isset($_SESSION['admin_id']) || ((int)($_SESSION['is_superadmin'] ?? 0) !== 1)) {
    $lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'pt';
    header("Location: admin.php?lang=$lang");
    exit;
}

// Langue
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'pt';
$_SESSION['lang'] = $lang;
setcookie('lang', $lang, time() + (86400 * 30), "/");

include 'lang.php';
include 'db.php';
include 'head.php';

// Traitement des actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_admin'])) {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $is_superadmin = isset($_POST['is_superadmin']) ? 1 : 0;

        if ($username !== '' && $password !== '') {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetchColumn() == 0) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO admins (username, password, is_superadmin, active) VALUES (?, ?, ?, 1)");
                $stmt->execute([$username, $hashed, $is_superadmin]);
            }
        }
    }

    if (isset($_POST['delete_admin'])) {
        $id = (int)$_POST['delete_admin'];
        if ($id !== (int)$_SESSION['admin_id']) {
            $stmt = $pdo->prepare("DELETE FROM admins WHERE admin_id = ?");
            $stmt->execute([$id]);
        }
    }

    if (isset($_POST['toggle_active'])) {
        $id = (int)$_POST['toggle_active'];
        if ($id !== (int)$_SESSION['admin_id']) {
            $stmt = $pdo->prepare("UPDATE admins SET active = 1 - active WHERE admin_id = ?");
            $stmt->execute([$id]);
        }
    }

    if (isset($_POST['approve_admin'])) {
        $id = (int)$_POST['approve_admin'];
        $stmt = $pdo->prepare("UPDATE admins SET active = 1 WHERE admin_id = ?");
        $stmt->execute([$id]);
    }

    if (isset($_POST['reject_admin'])) {
        $id = (int)$_POST['reject_admin'];
        $stmt = $pdo->prepare("DELETE FROM admins WHERE admin_id = ? AND active = 0");
        $stmt->execute([$id]);
    }

    header("Location: super_admin.php?lang=$lang");
    exit;
}

// Séparation des comptes
$stmt = $pdo->query("SELECT admin_id, username, is_superadmin FROM admins WHERE active = 0 ORDER BY username");
$pendingAdmins = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT admin_id, username, is_superadmin, active FROM admins WHERE active = 1 ORDER BY username");
$activeAdmins = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Config couleurs
$configPath = 'roleta_config.json';
$config = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];
$color1 = $config['colors'][0] ?? '#FF0000';
$color2 = $config['colors'][1] ?? '#FF0000';

echo "<style>:root { --color1: $color1; --color2: $color2; }</style>";
?>

<body>
<div class="login-container">
    <h2>All Stars Panel</h2>

    <h3><?= __menu('Lista dos admins :') ?? 'Liste des admins' ?></h3>

    <input type="text" id="searchInput" placeholder="🔍 Procurar um administrador"
           style="width:40%; padding:6px 10px; border-radius:2px; margin-bottom:12px; display:block; margin-left:0;">

    <table id="adminTable">
        <thead>
            <tr>
                <th onclick="sortTable(0)">ID</th>
                <th onclick="sortTable(1)"><?= __menu('Identificado') ?></th>
                <th onclick="sortTable(2)"><?= __menu('Estado') ?></th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activeAdmins as $admin): ?>
                <tr>
                    <td><?= (int)$admin['admin_id'] ?></td>
                    <td><?= htmlspecialchars($admin['username']) ?></td>
                    <td><?= $admin['active'] ? 'Ativo' : 'Inativo' ?><?= $admin['is_superadmin'] ? ' (All Stars)' : '' ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="toggle_active" value="<?= (int)$admin['admin_id'] ?>">
                            <button class="btn-green" type="submit" <?= ((int)$admin['admin_id'] === (int)$_SESSION['admin_id']) ? 'disabled title="Não pode ativar/desativar você mesmo"' : '' ?>>
                                <?= $admin['active'] ? 'Desativar' : 'Ativar' ?>
                            </button>
                        </form>
                        <?php if ((int)$admin['admin_id'] !== (int)$_SESSION['admin_id']): ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="delete_admin" value="<?= (int)$admin['admin_id'] ?>">
                                <button class="btn-red" type="submit" onclick="return confirm('Remover este admin ?')">Remover</button>
                            </form>
                        <?php else: ?>
                            <button class="btn-red" disabled title="Não pode remover você mesmo">Remover</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (count($pendingAdmins) > 0): ?>
        <h3><?= __menu('pending_approval') ?? 'En attente de validation' ?></h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?= __menu('Identificado') ?></th>
                    <th>All Stars?</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingAdmins as $admin): ?>
                    <tr>
                        <td><?= (int)$admin['admin_id'] ?></td>
                        <td><?= htmlspecialchars($admin['username']) ?></td>
                        <td><?= $admin['is_superadmin'] ? '✅' : '❌' ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="approve_admin" value="<?= (int)$admin['admin_id'] ?>">
                                <button class="btn-green" type="submit">✅ <?= __menu('approve') ?? 'Approuver' ?></button>
                            </form>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="reject_admin" value="<?= (int)$admin['admin_id'] ?>">
                                <button class="btn-red" type="submit" onclick="return confirm('Rejeitar este admin?')">❌ <?= __menu('reject') ?? 'Rejeter' ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h3>➕ Adicionar um admin</h3>
    <form method="POST">
        <input type="hidden" name="add_admin" value="1">
        <input type="text" name="username" placeholder="<?= __menu('Identificado') ?>" required>
        <input type="password" name="password" placeholder="<?= __menu('Senha') ?>" required>
        <label><input type="checkbox" name="is_superadmin"> All Stars</label>
        <button type="submit" class="btn-green">Criar</button>
    </form>

    <div class="admin-buttons">
        <a href="admin.php?lang=<?= $lang ?>" class="btn-red"><?= __menu('Volta') ?></a>
        <a href="logout.php?lang=<?= $lang ?>" class="btn-red"><?= __menu('Desconexão') ?></a>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('adminTable');
    searchInput.addEventListener('input', function () {
        const term = this.value.toLowerCase();
        Array.from(table.tBodies[0].rows).forEach(row => {
            const visible = Array.from(row.cells).some(cell => cell.textContent.toLowerCase().includes(term));
            row.style.display = visible ? '' : 'none';
        });
    });

    function sortTable(colIndex) {
        const tbody = table.tBodies[0];
        const rows = Array.from(tbody.rows);
        const isNumeric = !isNaN(rows[0].cells[colIndex].textContent);
        const ascending = table.dataset.sortDir !== 'asc';
        rows.sort((a, b) => {
            const A = a.cells[colIndex].textContent.trim();
            const B = b.cells[colIndex].textContent.trim();
            return (isNumeric ? A - B : A.localeCompare(B)) * (ascending ? 1 : -1);
        });
        rows.forEach(row => tbody.appendChild(row));
        table.dataset.sortDir = ascending ? 'asc' : 'desc';
    }
</script>
</body>
</html>