<?php
/**
 * Funções Centrais - Painel Admin Resimetal
 */

// Função para verificar se o administrador está logado
function checkAuth() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION[ADMIN_SESSION_NAME])) {
        header("Location: login.php");
        exit();
    }
}

// Retorna um conteúdo específico do banco ou um padrão se não existir
function getContent($section, $key, $default = "") {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT content_value FROM site_content WHERE section = ? AND content_key = ?");
        $stmt->execute([$section, $key]);
        $row = $stmt->fetch();
        return $row ? $row['content_value'] : $default;
    } catch(Exception $e) {
        return $default;
    }
}

// Retorna as configurações de marketing (scripts)
function getSetting($name, $default = "") {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT value FROM site_settings WHERE name = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch();
        return $row ? $row['value'] : $default;
    } catch(Exception $e) {
        return $default;
    }
}

// Lógica de Contagem de Métricas (Visualizações e Únicos)
function logVisitor() {
    global $conn;
    $ip = $_SERVER['REMOTE_ADDR'];
    $today = date('Y-m-d');

    try {
        // 1. Incrementar Total de Visualizações (Page Views)
        $stmt = $conn->prepare("INSERT INTO site_metrics (metric_date, page_views, unique_visitors) 
                                VALUES (?, 1, 1) 
                                ON DUPLICATE KEY UPDATE page_views = page_views + 1");
        $stmt->execute([$today]);

        // 2. Verificar se este IP já visitou hoje (Usuário Único)
        $stmt = $conn->prepare("SELECT id FROM visitor_log WHERE ip_address = ? AND visit_date = ?");
        $stmt->execute([$ip, $today]);
        if (!$stmt->fetch()) {
            // Nova visita única hoje
            $stmt = $conn->prepare("INSERT INTO visitor_log (ip_address, visit_date) VALUES (?, ?)");
            $stmt->execute([$ip, $today]);

            // Atualizar contador de únicos na tabela de métricas
            $stmt = $conn->prepare("UPDATE site_metrics SET unique_visitors = unique_visitors + 1 WHERE metric_date = ?");
            $stmt->execute([$today]);
        }
    } catch(Exception $e) {
        // Erro silencioso em logs de produção
    }
}

// Função para upload de imagem
function uploadImage($file, $targetDir = "../assets/uploads/") {
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    
    $fileName = time() . "_" . basename($file["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowTypes = array('jpg','png','jpeg','gif','webp');
    if(in_array(strtolower($fileType), $allowTypes)){
        if(move_uploaded_file($file["tmp_name"], $targetFilePath)){
            return "assets/uploads/" . $fileName;
        }
    }
    return false;
}
?>
