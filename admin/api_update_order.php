<?php
require_once('../includes/config.php');
require_once('../includes/functions.php');

// Garantir que apenas admins acessem
checkAuth();

// Receber o corpo da requisição JSON
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['order'])) {
    try {
        $conn->beginTransaction();
        
        $sql = "UPDATE gallery_images SET sort_order = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        foreach ($data['order'] as $position => $id) {
            $stmt->execute([$position, $id]);
        }
        
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Nenhum dado recebido.']);
}
?>
