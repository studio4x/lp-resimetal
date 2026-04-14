<?php
require_once('includes/config.php');

$data = [
    ['images', 'logo_path', 'assets/logotipo-resimetal-transparente-otimizado.webp'],
    ['images', 'hero_bg_path', ''], // Atualmente é gradiente, deixamos vazio
    ['images', 'favicon_path', 'assets/favicon.ico'],
];

try {
    $stmt = $conn->prepare("INSERT INTO site_content (section, content_key, content_value) 
                            VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE content_value = ?");
                            
    foreach ($data as $item) {
        $stmt->execute([$item[0], $item[1], $item[2], $item[2]]);
    }
    
    echo "<h1>Mapeamento de Imagens Concluído!</h1>";
    echo "<p>Caminhos do Logotipo e Favicon foram registrados no banco.</p>";
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
