<?php
include('includes/header.php');

$msg = "";
$error = "";

// Processar Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_image'])) {
    if (!empty($_FILES['image']['name'])) {
        $path = uploadImage($_FILES['image']);
        if ($path) {
            $caption = $_POST['caption'] ?? "";
            $stmt = $conn->prepare("INSERT INTO gallery_images (image_path, caption) VALUES (?, ?)");
            $stmt->execute([$path, $caption]);
            $msg = "Imagem enviada com sucesso!";
        } else {
            $error = "Erro no upload. Verifique o formato do arquivo.";
        }
    }
}

// Processar Exclusão
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Buscar caminho para apagar o arquivo físico
    $stmt = $conn->prepare("SELECT image_path FROM gallery_images WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetch();
    
    if ($img) {
        $fullPath = "../" . $img['image_path'];
        if (file_exists($fullPath)) unlink($fullPath);
        
        $stmt = $conn->prepare("DELETE FROM gallery_images WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "Imagem removida com sucesso!";
    }
}

// Buscar todas as imagens
$stmt = $conn->query("SELECT * FROM gallery_images ORDER BY id DESC");
$images = $stmt->fetchAll();
?>

<div class="header-page">
    <h1>Gerenciar Galeria</h1>
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

<div class="card-admin" style="margin-bottom: 40px;">
    <h2>Adicionar Nova Foto</h2>
    <form method="POST" enctype="multipart/form-data" style="display: flex; gap: 20px; align-items: flex-end;">
        <div class="form-group" style="flex: 2; margin-bottom: 0;">
            <label>Selecionar Imagem</label>
            <input type="file" name="image" required>
        </div>
        <div class="form-group" style="flex: 2; margin-bottom: 0;">
            <label>Legenda (Alt)</label>
            <input type="text" name="caption" placeholder="Ex: Lingotes de Zinco">
        </div>
        <button type="submit" name="upload_image" class="btn-save">Fazer Upload</button>
    </form>
</div>

<div class="card-admin">
    <h2>Fotos Atuais</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; margin-top: 20px;">
        <?php foreach($images as $img): ?>
            <div style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; background: #fafafa; position: relative;">
                <img src="../<?php echo $img['image_path']; ?>" style="width: 100%; h-eight: 140px; object-fit: cover;">
                <div style="padding: 10px; font-size: 0.8rem; color: #666;">
                    <?php echo htmlspecialchars($img['caption']); ?>
                </div>
                <a href="?delete=<?php echo $img['id']; ?>" 
                   onclick="return confirm('Tem certeza que deseja excluir esta foto?')"
                   style="position: absolute; top: 5px; right: 5px; background: rgba(220, 38, 38, 0.8); color: white; padding: 5px; border-radius: 4px; text-decoration: none;">
                    <i class="ph ph-trash"></i>
                </a>
            </div>
        <?php endforeach; ?>
        
        <?php if(empty($images)): ?>
            <p style="color: #6B7280;">Nenhuma imagem cadastrada na galeria.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
