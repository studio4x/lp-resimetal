<?php
include('includes/header.php');

$msg = "";

// Processar salvamento
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_content'])) {
    foreach ($_POST['content'] as $key => $value) {
        $section = $_POST['section'];
        $stmt = $conn->prepare("INSERT INTO site_content (section, content_key, content_value) 
                                VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE content_value = ?");
        $stmt->execute([$section, $key, $value, $value]);
    }
    $msg = "Conteúdo atualizado com sucesso!";
}

// Helper para buscar valor atual
function getVal($section, $key) {
    global $conn;
    $stmt = $conn->prepare("SELECT content_value FROM site_content WHERE section = ? AND content_key = ?");
    $stmt->execute([$section, $key]);
    $row = $stmt->fetch();
    return $row ? $row['content_value'] : "";
}
?>

<div class="header-page">
    <h1>Gerenciar Conteúdo do Site</h1>
</div>

<?php if($msg): ?>
    <div style="background: #dcfce7; color: #15803d; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <i class="ph ph-check-circle"></i> <?php echo $msg; ?>
    </div>
<?php endif; ?>

<div class="card-admin" style="margin-bottom: 30px;">
    <h2>Seção Hero (Início)</h2>
    <form method="POST">
        <input type="hidden" name="section" value="hero">
        <div class="form-group">
            <label>Título Principal</label>
            <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('hero', 'title')); ?>" required>
        </div>
        <div class="form-group">
            <label>Subtítulo / Descrição</label>
            <textarea name="content[subtitle]" rows="3"><?php echo htmlspecialchars(getVal('hero', 'subtitle')); ?></textarea>
        </div>
        <button type="submit" name="save_content" class="btn-save">Salvar Alterações</button>
    </form>
</div>

<div class="card-admin" style="margin-bottom: 30px;">
    <h2>Seção Quem Somos</h2>
    <form method="POST">
        <input type="hidden" name="section" value="quem_somos">
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('quem_somos', 'title')); ?>">
        </div>
        <div class="form-group">
            <label>Chamada (Destaque)</label>
            <input type="text" name="content[subtitle]" value="<?php echo htmlspecialchars(getVal('quem_somos', 'subtitle')); ?>">
        </div>
        <div class="form-group">
            <label>Texto Institucional</label>
            <textarea name="content[body]" rows="5"><?php echo htmlspecialchars(getVal('quem_somos', 'body')); ?></textarea>
        </div>
        <button type="submit" name="save_content" class="btn-save">Salvar Alterações</button>
    </form>
</div>

<div class="card-admin" style="margin-bottom: 30px;">
    <h2>Seção Importância (Estratégico)</h2>
    <form method="POST">
        <input type="hidden" name="section" value="importancia">
        <div class="form-group">
            <label>Título da Seção</label>
            <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('importancia', 'title')); ?>">
        </div>
        <div class="form-group">
            <label>Subtítulo / Descrição</label>
            <textarea name="content[subtitle]" rows="3"><?php echo htmlspecialchars(getVal('importancia', 'subtitle')); ?></textarea>
        </div>
        <button type="submit" name="save_content" class="btn-save">Salvar Alterações</button>
    </form>
</div>

<div class="card-admin" style="margin-bottom: 30px;">
    <h2>Seção Sustentabilidade (Meio Ambiente)</h2>
    <form method="POST">
        <input type="hidden" name="section" value="sustentabilidade">
        <div class="form-group">
            <label>Título da Seção</label>
            <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('sustentabilidade', 'title')); ?>">
        </div>
        <div class="form-group">
            <label>Texto Principal</label>
            <textarea name="content[body]" rows="4"><?php echo htmlspecialchars(getVal('sustentabilidade', 'body')); ?></textarea>
        </div>
        <button type="submit" name="save_content" class="btn-save">Salvar Alterações</button>
    </form>
</div>

<div class="card-admin">
    <h2>Seção Nossos Serviços (Cabeçalho)</h2>
    <form method="POST">
        <input type="hidden" name="section" value="servicos">
        <div class="form-group">
            <label>Título da Seção</label>
            <input type="text" name="content[title]" value="<?php echo htmlspecialchars(getVal('servicos', 'title')); ?>">
        </div>
        <div class="form-group">
            <label>Subtítulo da Seção</label>
            <input type="text" name="content[subtitle]" value="<?php echo htmlspecialchars(getVal('servicos', 'subtitle')); ?>">
        </div>
        <button type="submit" name="save_content" class="btn-save">Salvar Alterações</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
