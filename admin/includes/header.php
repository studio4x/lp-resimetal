<?php
require_once('../includes/config.php');
require_once('../includes/functions.php');
checkAuth();
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
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>
    <aside class="sidebar">
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
            <li class="<?php echo $currentPage == 'marketing.php' ? 'active' : ''; ?>">
                <a href="marketing.php"><i class="ph ph-megaphone"></i> <span>Marketing & SEO</span></a>
            </li>
            <li class="<?php echo $currentPage == 'usuarios.php' ? 'active' : ''; ?>">
                <a href="usuarios.php"><i class="ph ph-user-gear"></i> <span>Usuários</span></a>
            </li>
        </nav>
        <a href="logout.php" class="btn-logout"><i class="ph ph-sign-out"></i> Sair</a>
    </aside>
    <main class="main-content">
