<?php
require_once('../includes/config.php');
require_once('../includes/functions.php');
checkAuth();

// Headers de Segurança HTTP
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Validação CSRF global para todos os POST no admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        die('<div style="text-align:center;margin-top:50px;font-family:sans-serif;">
            <h2 style="color:#b91c1c;">⚠️ Requisição Inválida</h2>
            <p>Token de segurança expirado. <a href="javascript:history.back()">Voltar</a></p></div>');
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin | Resimetal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="css/admin-style.css?v=<?php echo BUILD_VERSION; ?>">
</head>
<body>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <button class="mobile-toggle" id="mobileToggle">
        <i class="ph ph-list"></i>
    </button>
    
    <aside class="sidebar" id="sidebar">
        <h3>Resimetal Admin</h3>
        <nav class="nav-admin">
            <li class="<?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">
                <a href="index.php"><i class="ph ph-chart-line-up"></i> <span>Dashboard</span></a>
            </li>
            <li class="<?php echo $currentPage == 'conteudo.php' ? 'active' : ''; ?>">
                <a href="conteudo.php"><i class="ph ph-pencil-circle"></i> <span>Conteúdo do Site</span></a>
            </li>
            <li class="<?php echo $currentPage == 'galeria.php' ? 'active' : ''; ?>">
                <a href="galeria.php"><i class="ph ph-image"></i> <span>Galeria</span></a>
            </li>
            <li class="<?php echo $currentPage == 'seo.php' ? 'active' : ''; ?>">
                <a href="seo.php"><i class="ph ph-google-logo"></i> <span>Gerenciar SEO</span></a>
            </li>
            <li class="<?php echo $currentPage == 'usuarios.php' ? 'active' : ''; ?>">
                <a href="usuarios.php"><i class="ph ph-user-gear"></i> <span>Usuários</span></a>
            </li>
        </nav>
        <a href="logout.php" class="btn-logout"><i class="ph ph-sign-out"></i> Sair</a>
    </aside>
    <main class="main-content">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobileOverlay');

    if(mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });
    }

    if(overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    // Injetar CSRF Token em TODOS os formulários POST automaticamente
    const csrfToken = '<?php echo generateCsrfToken(); ?>';
    document.querySelectorAll('form[method="POST"], form[method="post"]').forEach(function(form) {
        if (!form.querySelector('input[name="csrf_token"]')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'csrf_token';
            input.value = csrfToken;
            form.appendChild(input);
        }
    });
});
</script>

