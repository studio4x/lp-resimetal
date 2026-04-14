<?php
include('includes/header.php');

$msg = "";
$error = "";

// Helper para buscar valor atual (Imagens do Sistema)
function getImgPath($key) {
    global $conn;
    $stmt = $conn->prepare("SELECT content_value FROM site_content WHERE section = 'images' AND content_key = ?");
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    return $row ? $row['content_value'] : "";
}

// 1. Processar Imagens do Sistema (Logo, Hero, Favicon)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_system_image'])) {
    $key = $_POST['image_key'];
    if (!empty($_FILES['image']['name'])) {
        $path = uploadImage($_FILES['image']);
        if ($path) {
            // Se já existia uma imagem customizada, poderíamos apagar a antiga aqui (opcional)
            $stmt = $conn->prepare("INSERT INTO site_content (section, content_key, content_value) 
                                    VALUES ('images', ?, ?) 
                                    ON DUPLICATE KEY UPDATE content_value = ?");
            $stmt->execute([$key, $path, $path]);
            $msg = "Imagem do sistema atualizada com sucesso!";
        } else {
            $error = "Erro no upload da imagem do sistema.";
        }
    }
}

// 2. Processar Galeria (Carrossel)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_gallery'])) {
    if (!empty($_FILES['image']['name'])) {
        $path = uploadImage($_FILES['image']);
        if ($path) {
            $caption = $_POST['caption'] ?? "";
            $stmt = $conn->prepare("INSERT INTO gallery_images (image_path, caption) VALUES (?, ?)");
            $stmt->execute([$path, $caption]);
            $msg = "Imagem adicionada à galeria!";
        } else {
            $error = "Erro no upload para a galeria.";
        }
    }
}

// 3. Processar Exclusão da Galeria
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("SELECT image_path FROM gallery_images WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetch();
    if ($img) {
        $fullPath = "../" . $img['image_path'];
        if (file_exists($fullPath) && strpos($img['image_path'], 'uploads/') !== false) unlink($fullPath);
        $stmt = $conn->prepare("DELETE FROM gallery_images WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "Imagem removida da galeria.";
    }
}

// Buscar todas as imagens da galeria
$stmt = $conn->query("SELECT * FROM gallery_images ORDER BY id DESC");
$images = $stmt->fetchAll();
?>

<div class="header-page">
    <h1>Gerenciar Imagens</h1>
    <p>Substitua o logotipo, banners e gerencie a galeria do carrossel.</p>
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

<!-- SEÇÃO: IMAGENS DO SISTEMA -->
<div class="card-admin" style="margin-bottom: 40px;">
    <h2>Imagens do Sistema (Logo e Banner)</h2>
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 30px; margin-top: 20px;">
        
        <!-- LOGOTIPO -->
        <div style="background: #f9f9f9; padding: 20px; border-radius: 12px; border: 1px solid #eee;">
            <h3>Logotipo</h3>
            <div style="height: 80px; display: flex; align-items: center; justify-content: center; background: #eee; margin: 15px 0; border-radius: 8px; overflow: hidden;">
                <?php $logo = getImgPath('logo_path'); ?>
                <img src="../<?php echo $logo ?: 'assets/logotipo-resimetal-transparente-otimizado.webp'; ?>" style="max-height: 60px;">
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="image_key" value="logo_path">
                <input type="file" name="image" required style="font-size: 0.8rem; margin-bottom: 15px;">
                <button type="submit" name="save_system_image" class="btn-save" style="width: 100%; font-size: 0.9rem; padding: 10px;">Substituir Logo</button>
            </form>
        </div>

        <!-- HERO BANNER -->
        <div style="background: #f9f9f9; padding: 20px; border-radius: 12px; border: 1px solid #eee;">
            <h3>Banner Hero (Fundo)</h3>
            <div style="height: 80px; display: flex; align-items: center; justify-content: center; background: #eee; margin: 15px 0; border-radius: 8px; overflow: hidden; color: #999;">
                <?php $hero = getImgPath('hero_bg_path'); ?>
                <?php if($hero): ?>
                    <img src="../<?php echo $hero; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <i class="ph ph-image" style="font-size: 2rem;"></i> <span style="font-size: 0.7rem; margin-left: 5px;">(Usando Gradiente)</span>
                <?php endif; ?>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="image_key" value="hero_bg_path">
                <input type="file" name="image" required style="font-size: 0.8rem; margin-bottom: 15px;">
                <button type="submit" name="save_system_image" class="btn-save" style="width: 100%; font-size: 0.9rem; padding: 10px; background: #3b82f6;">Trocar Fundo Hero</button>
            </form>
        </div>

        <!-- FAVICON -->
        <div style="background: #f9f9f9; padding: 20px; border-radius: 12px; border: 1px solid #eee;">
            <h3>Favicon</h3>
            <div style="height: 80px; display: flex; align-items: center; justify-content: center; background: #eee; margin: 15px 0; border-radius: 8px;">
                <?php $favicon = getImgPath('favicon_path'); ?>
                <img src="../<?php echo $favicon ?: 'assets/favicon.ico'; ?>" style="width: 32px; height: 32px;">
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="image_key" value="favicon_path">
                <input type="file" name="image" required style="font-size: 0.8rem; margin-bottom: 15px;">
                <button type="submit" name="save_system_image" class="btn-save" style="width: 100%; font-size: 0.9rem; padding: 10px; background: #6b7280;">Atualizar Ícone</button>
            </form>
        </div>

    </div>
</div>

<!-- SEÇÃO: GALERIA CARROSSEL -->
<div class="card-admin" style="margin-bottom: 40px;">
    <h2>Galeria do Carrossel (Operação)</h2>
    <form method="POST" enctype="multipart/form-data" style="display: flex; gap: 20px; align-items: flex-end; margin-top: 15px;">
        <div class="form-group" style="flex: 2; margin-bottom: 0;">
            <label>Adicionar Foto à Galeria</label>
            <input type="file" name="image" required>
        </div>
        <div class="form-group" style="flex: 2; margin-bottom: 0;">
            <label>Legenda</label>
            <input type="text" name="caption" placeholder="Opcional">
        </div>
        <button type="submit" name="upload_gallery" class="btn-save">Fazer Upload</button>
    </form>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; margin-top: 30px;">
        <?php foreach($images as $img): ?>
            <div style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; background: #fafafa; position: relative;">
                <img src="../<?php echo $img['image_path']; ?>" style="width: 100%; height: 120px; object-fit: cover;">
                <div style="padding: 10px; font-size: 0.75rem; color: #666; height: 40px; overflow: hidden;">
                    <?php echo htmlspecialchars($img['caption']); ?>
                </div>
                <a href="?delete=<?php echo $img['id']; ?>" 
                   onclick="return confirm('Excluir esta foto da galeria?')"
                   style="position: absolute; top: 5px; right: 5px; background: rgba(220, 38, 38, 0.8); color: white; padding: 4px; border-radius: 4px; text-decoration: none;">
                    <i class="ph ph-trash"></i>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
