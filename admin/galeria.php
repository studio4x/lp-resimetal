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

// 1. Processar Imagens do Sistema
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_system_image'])) {
    $key = $_POST['image_key'];
    if (!empty($_FILES['image']['name'])) {
        $path = uploadImage($_FILES['image']);
        if ($path) {
            $stmt = $conn->prepare("INSERT INTO site_content (section, content_key, content_value) 
                                    VALUES ('images', ?, ?) 
                                    ON DUPLICATE KEY UPDATE content_value = ?");
            $stmt->execute([$key, $path, $path]);
            $msg = "Ativo do sistema atualizado!";
        } else {
            $error = "Erro no upload da imagem do sistema.";
        }
    }
}

// 2. Processar Galeria (Upload em Massa)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_gallery'])) {
    if (!empty($_FILES['images']['name'][0])) {
        $files = $_FILES['images'];
        $countSuccess = 0;
        $countError = 0;

        $stmt = $conn->query("SELECT MAX(sort_order) as max_order FROM gallery_images");
        $max = $stmt->fetch();
        $nextOrder = ($max['max_order'] ?? 0) + 1;

        for ($i = 0; $i < count($files['name']); $i++) {
            $currentFile = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];

            $path = uploadImage($currentFile);
            if ($path) {
                // Ao subir em massa, podemos deixar o Alt Text vazio ou usar um padrão
                $caption = $_POST['caption'] ?? ""; 
                $stmt = $conn->prepare("INSERT INTO gallery_images (image_path, caption, sort_order) VALUES (?, ?, ?)");
                $stmt->execute([$path, $caption, $nextOrder]);
                $nextOrder++;
                $countSuccess++;
            } else {
                $countError++;
            }
        }
        if ($countSuccess > 0) $msg = "$countSuccess imagens adicionadas!";
    }
}

// 3. Processar Atualização de Alt Text (Novo!)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_alt'])) {
    $id = (int)$_POST['image_id'];
    $newAlt = $_POST['alt_text'];
    $stmt = $conn->prepare("UPDATE gallery_images SET caption = ? WHERE id = ?");
    if ($stmt->execute([$newAlt, $id])) {
        $msg = "Texto Alternativo (Alt) atualizado!";
    } else {
        $error = "Erro ao atualizar texto alternativo.";
    }
}

// 4. Processar Exclusão da Galeria
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

// Buscar todas as imagens da galeria ORDENADAS
$stmt = $conn->query("SELECT * FROM gallery_images ORDER BY sort_order ASC, id DESC");
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
    <div class="admin-grid-3" style="margin-top: 20px;">
        
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
    
    <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9rem; color: #1e40af;">
        <i class="ph ph-info" style="font-size: 1.1rem; vertical-align: middle;"></i> 
        <strong>Dicas:</strong> Suba imagens em massa e depois defina o <strong>Alt Text</strong> de cada uma para melhorar o SEO (Google). Reorganize arrastando os cards.
    </div>

    <form method="POST" enctype="multipart/form-data" class="admin-grid-3" style="align-items: flex-end; margin-top: 15px; margin-bottom: 30px; background: #fdfdfd; padding: 20px; border: 1px solid #f0f0f0; border-radius: 12px;">
        <div class="form-group" style="flex: 2; margin-bottom: 0;">
            <label>Adicionar Fotos (Upload em Massa)</label>
            <input type="file" name="images[]" multiple required>
        </div>
        <div class="form-group" style="flex: 2; margin-bottom: 0;">
            <label>Texto Alt / Legenda Base</label>
            <input type="text" name="caption" placeholder="Ex: Operação Resimetal">
        </div>
        <button type="submit" name="upload_gallery" class="btn-save" style="background: #158E12;">Fazer Upload</button>
    </form>

    <div id="sortable-gallery" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px; margin-top: 10px;">
        <?php foreach($images as $img): 
            // Limpar o nome do arquivo para exibição (remove o timestamp prefixado)
            $filename = basename($img['image_path']);
            $displayName = (strpos($filename, '_') !== false) ? substr($filename, strpos($filename, '_') + 1) : $filename;
        ?>
            <div data-id="<?php echo $img['id']; ?>" class="gallery-item-sortable" style="border: 1px solid #eee; border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); position: relative; border-bottom: 3px solid #eee;">
                
                <div style="background: #f8fafc; padding: 8px 12px; font-size: 0.7rem; color: #64748b; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 5px;">
                   <i class="ph ph-image"></i> <?php echo htmlspecialchars($displayName); ?>
                </div>

                <div style="cursor: grab;">
                    <img src="../<?php echo $img['image_path']; ?>" style="width: 100%; height: 140px; object-fit: cover; pointer-events: none;">
                </div>

                <div style="padding: 12px; background: #fff;">
                    <form method="POST" style="display: flex; flex-direction: column; gap: 8px;">
                        <input type="hidden" name="image_id" value="<?php echo $img['id']; ?>">
                        <label style="font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Alt Text / Legenda</label>
                        <div style="display: flex; gap: 5px;">
                            <input type="text" name="alt_text" value="<?php echo htmlspecialchars($img['caption']); ?>" 
                                   style="flex: 1; font-size: 0.8rem; padding: 5px 8px; border: 1px solid #e2e8f0; border-radius: 6px;" 
                                   placeholder="Descreva a foto...">
                            <button type="submit" name="update_alt" title="Salvar Legenda" 
                                    style="background: #3b82f6; color: white; border: none; padding: 5px 8px; border-radius: 6px; cursor: pointer;">
                                <i class="ph ph-floppy-disk"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Botão Deletar -->
                <a href="?delete=<?php echo $img['id']; ?>" 
                   onclick="return confirm('Excluir esta foto da galeria?')"
                   style="position: absolute; top: 110px; right: 10px; background: rgba(220, 38, 38, 0.9); color: white; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; z-index: 10; border: 2px solid #fff;">
                    <i class="ph ph-trash" style="font-size: 0.9rem;"></i>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('sortable-gallery');
    if (el) {
        Sortable.create(el, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            handle: '[style*="cursor: grab"]', // Arrastar apenas pela imagem
            onEnd: function() {
                const order = [];
                document.querySelectorAll('.gallery-item-sortable').forEach((item) => {
                    order.push(item.getAttribute('data-id'));
                });

                fetch('api_update_order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) alert('Erro ao salvar a ordem: ' + data.error);
                });
            }
        });
    }
});
</script>

<style>
.sortable-ghost { opacity: 0.4; border: 2px dashed #3b82f6 !important; }
.btn-save:hover { filter: brightness(1.1); }
input:focus { outline: none; border-color: #3b82f6 !important; box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.1); }
</style>

<?php include('includes/footer.php'); ?>
