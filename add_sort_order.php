<?php
require_once('includes/config.php');

try {
    // Adiciona a coluna para armazenar a ordem
    $conn->exec("ALTER TABLE gallery_images ADD COLUMN sort_order INT DEFAULT 0");
    echo "<h1>Sucesso!</h1>";
    echo "<p>Coluna 'sort_order' adicionada à tabela 'gallery_images'.</p>";
} catch (PDOException $e) {
    echo "<h1>Aviso ou Erro:</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
