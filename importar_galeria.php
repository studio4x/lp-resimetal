<?php
/**
 * Script Temporário para Importar Imagens Existentes para o Banco de Dados
 */
require_once('includes/config.php');
require_once('includes/functions.php');

echo "<h2>Iniciando Importação de Galeria...</h2>";

$imagens = [
    ['path' => 'assets/galeria/01 - terras de zinco.jpg', 'caption' => 'Terras de zinco para beneficiamento'],
    ['path' => 'assets/galeria/02 - terras de zinco.jpg', 'caption' => 'Terras de zinco processo industrial'],
    ['path' => 'assets/galeria/03 - terras de zinco.jpg', 'caption' => 'Processamento de terras de zinco'],
    ['path' => 'assets/galeria/04 - terras de zinco.jpg', 'caption' => 'Separação de terras de zinco'],
    ['path' => 'assets/galeria/05 - terras de zinco.jpg', 'caption' => 'Operação de moagem de terras de zinco'],
    ['path' => 'assets/galeria/06 - terras de zinco.jpg', 'caption' => 'Beneficiamento de resíduos industriais'],
    ['path' => 'assets/galeria/07 - terras de zinco.jpg', 'caption' => 'Fábrica de moagem e peneiramento'],
    ['path' => 'assets/galeria/08 - terras de zinco.jpg', 'caption' => 'Resíduos de zinco prontos para fundição'],
    ['path' => 'assets/galeria/09 - terras de zinco.jpg', 'caption' => 'Terras de zinco estocadas'],
    ['path' => 'assets/galeria/10 - lingote de zinco.jpg', 'caption' => 'Lingotes de zinco prontos para aplicação']
];

$count = 0;
foreach ($imagens as $img) {
    // Verificar se já existe
    $stmt = $conn->prepare("SELECT id FROM gallery_images WHERE image_path = ?");
    $stmt->execute([$img['path']]);
    if (!$stmt->fetch()) {
        $stmt = $conn->prepare("INSERT INTO gallery_images (image_path, caption) VALUES (?, ?)");
        $stmt->execute([$img['path'], $img['caption']]);
        echo "Importado: " . $img['path'] . "<br>";
        $count++;
    }
}

echo "<h3>Sucesso! $count imagens foram re-inseridas.</h3>";
echo "<p><a href='index.php'>Clique aqui para voltar ao site</a> ou vá para o <a href='admin/galeria.php'>Painel Admin</a>.</p>";
?>
