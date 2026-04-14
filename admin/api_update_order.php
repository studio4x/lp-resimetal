<?php
require_once('../includes/config.php');
require_once('../includes/functions.php');

// Garantir que apenas admins acessem
checkAuth();

// Aceitar apenas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido.']);
    exit();
}

// Receber o corpo da requisição JSON
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['order'])) {
    try {
        $conn->beginTransaction();
        
        $sql = "UPDATE gallery_images SET sort_order = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        foreach ($data['order'] as $position => $id) {
            $stmt->execute([(int)$position, (int)$id]); // Cast para int — previne injection
        }
        
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        echo json_encode(['success' => false, 'error' => 'Erro interno.']); // Não expor detalhes
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Nenhum dado recebido.']);
}
?>
