<?php
include('includes/header.php');

$msg = "";
$error = "";

// Pegar ID do usuário logado
$meId = $_SESSION[ADMIN_SESSION_NAME];

// 1. Processar Cadastro de Novo Usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $newUser = trim($_POST['new_username']);
    $newPass = $_POST['new_password'];

    if (!empty($newUser) && !empty($newPass)) {
        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
            $stmt->execute([$newUser, $hash]);
            $msg = "Novo administrador cadastrado com sucesso!";
        } catch (PDOException $e) {
            $error = "Erro: Este usuário já existe ou ocorreu um erro no banco.";
        }
    } else {
        $error = "Preencha todos os campos do novo usuário.";
    }
}

// 2. Processar Alteração de Senha Própria
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_pass'])) {
    $currentPass = $_POST['current_password'];
    $newPass = $_POST['new_password_self'];
    $confirmPass = $_POST['confirm_password_self'];

    // Verificar senha atual
    $stmt = $conn->prepare("SELECT password_hash FROM admin_users WHERE id = ?");
    $stmt->execute([$meId]);
    $me = $stmt->fetch();

    if (password_verify($currentPass, $me['password_hash'])) {
        if ($newPass === $confirmPass && !empty($newPass)) {
            $newHash = password_hash($newPass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
            $stmt->execute([$newHash, $meId]);
            $msg = "Sua senha foi alterada com sucesso!";
        } else {
            $error = "As novas senhas não coincidem ou estão vazias.";
        }
    } else {
        $error = "A senha atual informada está incorreta.";
    }
}

// 3. Processar Exclusão de Usuário
if (isset($_GET['delete'])) {
    $idDel = (int)$_GET['delete'];
    if ($idDel === $meId) {
        $error = "Você não pode excluir a si mesmo.";
    } else {
        $stmt = $conn->prepare("DELETE FROM admin_users WHERE id = ?");
        $stmt->execute([$idDel]);
        $msg = "Usuário removido com sucesso!";
    }
}

// Buscar todos os usuários
$stmt = $conn->query("SELECT id, username, created_at FROM admin_users ORDER BY id ASC");
$users = $stmt->fetchAll();
?>

<div class="header-page">
    <h1>Usuários e Segurança</h1>
</div>

<?php if($msg): ?>
    <div style="background: #dcfce7; color: #15803d; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <i class="ph ph-check-circle"></i> <?php echo $msg; ?>
    </div>
<?php endif; ?>

<?php if($error): ?>
    <div style="background: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <i class="ph ph-warning-circle"></i> <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="admin-grid-field">
    <!-- Alterar Senha Própria -->
    <div class="card-admin">
        <h2>Alterar Minha Senha</h2>
        <form method="POST">
            <div class="form-group">
                <label>Senha Atual</label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label>Nova Senha</label>
                <input type="password" name="new_password_self" required>
            </div>
            <div class="form-group">
                <label>Confirmar Nova Senha</label>
                <input type="password" name="confirm_password_self" required>
            </div>
            <button type="submit" name="change_pass" class="btn-save">Atualizar Minha Senha</button>
        </form>
    </div>

    <!-- Cadastrar Novo Admin -->
    <div class="card-admin">
        <h2>Adicionar Novo Administrador</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nome de Usuário (Login)</label>
                <input type="text" name="new_username" required>
            </div>
            <div class="form-group">
                <label>Senha Inicial</label>
                <input type="password" name="new_password" required>
            </div>
            <button type="submit" name="add_user" class="btn-save" style="background: #3b82f6;">Cadastrar Usuário</button>
        </form>
    </div>
</div>

<div class="card-admin" style="margin-top: 30px;">
    <h2>Administradores Cadastrados</h2>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="text-align: left; border-bottom: 2px solid #eee;">
                <th style="padding: 12px;">Usuário</th>
                <th style="padding: 12px;">Data Criação</th>
                <th style="padding: 12px; text-align: right;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $u): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;">
                        <strong><?php echo htmlspecialchars($u['username']); ?></strong>
                        <?php if($u['id'] === $meId): ?> (Você) <?php endif; ?>
                    </td>
                    <td style="padding: 12px; color: #666; font-size: 0.9rem;">
                        <?php echo date('d/m/Y H:i', strtotime($u['created_at'])); ?>
                    </td>
                    <td style="padding: 12px; text-align: right;">
                        <?php if($u['id'] !== $meId): ?>
                            <a href="?delete=<?php echo $u['id']; ?>" 
                               onclick="return confirm('Tem certeza que deseja remover este administrador?')"
                               style="color: #ef4444; text-decoration: none; font-size: 1.2rem;">
                                <i class="ph ph-trash"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
