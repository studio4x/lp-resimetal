<?php
include('includes/header.php');

$msg = "";

// Processar Salvamento
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_settings'])) {
    foreach ($_POST['settings'] as $name => $value) {
        $stmt = $conn->prepare("INSERT INTO site_settings (name, value) 
                                VALUES (?, ?) 
                                ON DUPLICATE KEY UPDATE value = ?");
        $stmt->execute([$name, $value, $value]);
    }
    $msg = "Configurações de marketing atualizadas!";
}

// Helper para buscar valor
function getSett($name) {
    global $conn;
    $stmt = $conn->prepare("SELECT value FROM site_settings WHERE name = ?");
    $stmt->execute([$name]);
    $row = $stmt->fetch();
    return $row ? $row['value'] : "";
}
?>

<div class="header-page">
    <h1>Marketing & SEO</h1>
</div>

<?php if($msg): ?>
    <div style="background: #dcfce7; color: #15803d; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <i class="ph ph-check-circle"></i> <?php echo $msg; ?>
    </div>
<?php endif; ?>

<div class="card-admin">
    <h2>Scripts de Rastreamento</h2>
    <p style="color: #6B7280; margin-bottom: 20px;">Cole abaixo os códigos fornecidos pelo Google Ads, Meta Pixel ou Google Analytics.</p>
    
    <form method="POST">
        <div class="form-group">
            <label>Scripts no Cabeçalho (Dentro da tag &lt;head&gt;)</label>
            <textarea name="settings[head_scripts]" rows="8" placeholder="Ex: <!-- Global site tag (gtag.js) - Google Analytics -->"><?php echo htmlspecialchars(getSett('head_scripts')); ?></textarea>
            <small style="color: #9ca3af;">Ideal para Google Analytics e Verificações de Site.</small>
        </div>
        
        <div class="form-group">
            <label>Scripts no Rodapé (Antes do fechamento do &lt;/body&gt;)</label>
            <textarea name="settings[footer_scripts]" rows="8" placeholder="Ex: <!-- Meta Pixel Code -->"><?php echo htmlspecialchars(getSett('footer_scripts')); ?></textarea>
            <small style="color: #9ca3af;">Ideal para Pixel de Conversão e Chat Flutuante.</small>
        </div>
        
        <button type="submit" name="save_settings" class="btn-save">Salvar Configurações</button>
    </form>
</div>

<div class="card-admin" style="margin-top: 30px;">
    <h2>SEO Básico</h2>
    <form method="POST">
        <div class="form-group">
            <label>Título da Página (Meta Title)</label>
            <input type="text" name="settings[site_title]" value="<?php echo htmlspecialchars(getSett('site_title')); ?>" placeholder="Ex: Resimetal | Moagem e Fundição">
        </div>
        <div class="form-group">
            <label>Descrição do Site (Meta Description)</label>
            <textarea name="settings[site_description]" rows="3"><?php echo htmlspecialchars(getSett('site_description')); ?></textarea>
        </div>
        <button type="submit" name="save_settings" class="btn-save">Salvar SEO</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
