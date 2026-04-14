<?php
include('includes/header.php');

$msg = "";
$error = "";

// 1. Processar Salvamento de Ativos de SEO (Imagem OG)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_og_image'])) {
    if (!empty($_FILES['og_image']['name'])) {
        $path = uploadImage($_FILES['og_image']);
        if ($path) {
            $stmt = $conn->prepare("INSERT INTO site_settings (name, value) VALUES ('og_image_path', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$path, $path]);
            $msg = "Imagem de compartilhamento atualizada!";
        } else {
            $error = "Erro no upload da imagem social.";
        }
    }
}

// 2. Processar Salvamento de Configurações de Texto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_seo_settings'])) {
    foreach ($_POST['settings'] as $name => $value) {
        $stmt = $conn->prepare("INSERT INTO site_settings (name, value) 
                                VALUES (?, ?) 
                                ON DUPLICATE KEY UPDATE value = ?");
        $stmt->execute([$name, $value, $value]);
    }
    $msg = "Configurações de SEO atualizadas com sucesso!";
}

// Helper para buscar valor
function getSett($name, $default = "") {
    global $conn;
    $stmt = $conn->prepare("SELECT value FROM site_settings WHERE name = ?");
    $stmt->execute([$name]);
    $row = $stmt->fetch();
    return $row ? $row['value'] : $default;
}
?>

<div class="header-page">
    <h1>Central de SEO & Marketing</h1>
    <p>Ajuste os parâmetros de como o seu site aparece no Google e nas redes sociais.</p>
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

<form method="POST">
    
    <!-- SEÇÃO 1: SEO BASE (GOOGLE) -->
    <div class="card-admin" style="margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <i class="ph-fill ph-google-logo" style="font-size: 1.5rem; color: #4285F4;"></i>
            <h2 style="margin: 0;">Google Search (SEO Base)</h2>
        </div>
        
        <div class="form-group">
            <label>Título do Site (Meta Title)</label>
            <input type="text" name="settings[site_title]" value="<?php echo htmlspecialchars(getSett('site_title')); ?>" placeholder="Ex: Resimetal | Moagem e Fundição de Resíduos de Zinco">
            <small style="color: #6B7280;">Aparece na aba do navegador e no título do Google. Ideal: entre 50 e 60 caracteres.</small>
        </div>

        <div class="form-group">
            <label>Descrição do Site (Meta Description)</label>
            <textarea name="settings[site_description]" rows="3" placeholder="Ex: Especialistas em moagem, peneiramento e fundição de resíduos de zinco há mais de 20 anos..."><?php echo htmlspecialchars(getSett('site_description')); ?></textarea>
            <small style="color: #6B7280;">O resumo que aparece abaixo do título no Google. Ideal: entre 150 e 160 caracteres.</small>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Palavras-Chave (Keywords)</label>
                <input type="text" name="settings[site_keywords]" value="<?php echo htmlspecialchars(getSett('site_keywords')); ?>" placeholder="zinco, moagem, resíduos industriais">
            </div>
            <div class="form-group">
                <label>URL Canônica</label>
                <input type="text" name="settings[canonical_url]" value="<?php echo htmlspecialchars(getSett('canonical_url', 'https://resimetalbeneficiamentos.com.br/')); ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Google Site Verification (Código)</label>
            <input type="text" name="settings[google_verification]" value="<?php echo htmlspecialchars(getSett('google_verification')); ?>" placeholder="Ex: HSfLrgBat8BteoEb4mX7tRDnE2SwyJ9XvcZi96ebkik">
            <small style="color: #6B7280;">Código fornecido pelo Google Search Console para validar a propriedade do site.</small>
        </div>
    </div>

    <!-- SEÇÃO 2: SOCIAL SEO (OPEN GRAPH) -->
    <div class="card-admin" style="margin-bottom: 30px; border-left: 4px solid #1877F2;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <i class="ph-fill ph-share-network" style="font-size: 1.5rem; color: #1877F2;"></i>
            <h2 style="margin: 0;">Social SEO (WhatsApp / Facebook)</h2>
        </div>

        <div class="form-group">
            <label>Título de Compartilhamento (OG Title)</label>
            <input type="text" name="settings[og_title]" value="<?php echo htmlspecialchars(getSett('og_title')); ?>" placeholder="Como o link aparece ao ser enviado no WhatsApp">
        </div>

        <div class="form-group">
            <label>Descrição de Compartilhamento (OG Description)</label>
            <textarea name="settings[og_description]" rows="2"><?php echo htmlspecialchars(getSett('og_description')); ?></textarea>
        </div>
        
        <button type="submit" name="save_seo_settings" class="btn-save">Salvar Parâmetros de Texto</button>
    </div>
</form>

<!-- SEÇÃO 3: IMAGEM SOCIAL (UPLOAD SEPARADO) -->
<div class="card-admin" style="margin-bottom: 30px;">
    <h2 style="margin-bottom: 15px;">Imagem de Compartilhamento (OG Image)</h2>
    <p style="color: #6B7280; font-size: 0.9rem; margin-bottom: 20px;">Esta é a imagem que aparece quando você envia o link do site no WhatsApp. Recomendado: 1200x630px.</p>
    
    <div style="display: flex; gap: 30px; align-items: center; background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <div style="width: 250px; height: 130px; background: #e5e7eb; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
            <?php $ogImg = getSett('og_image_path'); ?>
            <?php if($ogImg): ?>
                <img src="../<?php echo $ogImg; ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <span style="color: #94a3b8; font-size: 0.8rem;">Nenhuma imagem configurada</span>
            <?php endif; ?>
        </div>
        <form method="POST" enctype="multipart/form-data" style="flex: 1;">
            <div class="form-group">
                <label>Selecionar Nova Imagem Social</label>
                <input type="file" name="og_image" required>
            </div>
            <button type="submit" name="upload_og_image" class="btn-save" style="background: #1877F2;">Fazer Upload da Imagem</button>
        </form>
    </div>
</div>

<!-- SEÇÃO 4: SCRIPTS DE RASTREAMENTO -->
<form method="POST">
    <div class="card-admin">
        <h2 style="margin-bottom: 15px;">Scripts & Tracking (Avançado)</h2>
        <div class="form-group">
            <label>Analytics / Head Scripts</label>
            <textarea name="settings[head_scripts]" rows="5" placeholder="Coloque aqui o G-XXXXXXXX do Google Analytics"><?php echo htmlspecialchars(getSett('head_scripts')); ?></textarea>
        </div>
        <div class="form-group">
            <label>Pixel / Footer Scripts</label>
            <textarea name="settings[footer_scripts]" rows="5" placeholder="Pixel do Facebook, Chat, etc."><?php echo htmlspecialchars(getSett('footer_scripts')); ?></textarea>
        </div>
        <button type="submit" name="save_seo_settings" class="btn-save" style="background: #10B981;">Salvar Todos os Dados</button>
    </div>
</form>

<?php include('includes/footer.php'); ?>
