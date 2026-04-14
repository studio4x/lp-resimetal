<?php
include('includes/header.php');

// Buscar métricas reais
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime("-1 day"));
$currentMonth = date('Y-m');

// Hoje
$stmt = $conn->prepare("SELECT page_views, unique_visitors FROM site_metrics WHERE metric_date = ?");
$stmt->execute([$today]);
$mToday = $stmt->fetch() ?: ['page_views' => 0, 'unique_visitors' => 0];

// Ontem
$stmt = $conn->prepare("SELECT page_views, unique_visitors FROM site_metrics WHERE metric_date = ?");
$stmt->execute([$yesterday]);
$mYesterday = $stmt->fetch() ?: ['page_views' => 0, 'unique_visitors' => 0];

// Mês (Soma)
$stmt = $conn->prepare("SELECT SUM(page_views) as pv, SUM(unique_visitors) as uv FROM site_metrics WHERE metric_date LIKE ?");
$stmt->execute([$currentMonth."%"]);
$mMonth = $stmt->fetch() ?: ['pv' => 0, 'uv' => 0];
?>

<div class="header-page">
    <h1>Dashboard de Métricas</h1>
    <div>Bem-vindo, <strong>Admin</strong></div>
</div>

<div class="metrics-grid">
    <div class="metric-card">
        <span>Hoje (Visualizações)</span>
        <strong><?php echo $mToday['page_views']; ?></strong>
    </div>
    <div class="metric-card">
        <span>Hoje (Usuários Únicos)</span>
        <strong><?php echo $mToday['unique_visitors']; ?></strong>
    </div>
    <div class="metric-card">
        <span>Ontem (Visualizações)</span>
        <strong><?php echo $mYesterday['page_views']; ?></strong>
    </div>
    <div class="metric-card">
        <span>Total Mensal (Visualizações)</span>
        <strong><?php echo (int)$mMonth['pv']; ?></strong>
    </div>
</div>

<div class="card-admin">
    <h3>Ações Rápidas</h3>
    <p style="color: #6B7280; margin-bottom: 20px;">Utilize o menu lateral para gerenciar as seções do site, as fotos da galeria ou configurar tags de marketing.</p>
    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
        <a href="conteudo.php" class="btn-save" style="text-decoration: none;">Editar Conteúdo</a>
        <a href="galeria.php" class="btn-save" style="text-decoration: none; background: #3b82f6;">Gerenciar Galeria</a>
    </div>
</div>

<?php include('includes/footer.php'); ?>
