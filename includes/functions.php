<?php
/**
 * Funções Centrais - Painel Admin Resimetal
 * Inclui: Auth, CSRF, Rate Limiting, Upload Seguro
 */

// ==========================================
// SEGURANÇA DE SESSÃO
// ==========================================
function initSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        session_set_cookie_params([
            'lifetime' => 3600,        // 1 hora de sessão
            'path' => '/',
            'domain' => '',
            'secure' => $isSecure,     // Apenas HTTPS em produção
            'httponly' => true,        // Bloqueia acesso via JavaScript
            'samesite' => 'Strict'     // Proteção contra CSRF externo
        ]);
        session_start();
    }
}

// ==========================================
// PROTEÇÃO CSRF
// ==========================================
function generateCsrfToken() {
    initSecureSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token) {
    initSecureSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCsrfToken() . '">';
}

// ==========================================
// RATE LIMITING (Login)
// ==========================================
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutos

function checkLoginRateLimit() {
    initSecureSession();
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['login_first_attempt'] = time();
    }
    
    // Reset se passou o tempo de lockout
    if (time() - $_SESSION['login_first_attempt'] > LOGIN_LOCKOUT_TIME) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['login_first_attempt'] = time();
    }
    
    return $_SESSION['login_attempts'] < MAX_LOGIN_ATTEMPTS;
}

function registerFailedLogin() {
    initSecureSession();
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    if ($_SESSION['login_attempts'] === 1) {
        $_SESSION['login_first_attempt'] = time();
    }
}

function resetLoginAttempts() {
    initSecureSession();
    $_SESSION['login_attempts'] = 0;
}

function getRemainingLockoutTime() {
    initSecureSession();
    $elapsed = time() - ($_SESSION['login_first_attempt'] ?? time());
    $remaining = LOGIN_LOCKOUT_TIME - $elapsed;
    return max(0, $remaining);
}

// ==========================================
// AUTENTICAÇÃO
// ==========================================
function checkAuth() {
    initSecureSession();
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

// Função para upload de imagem (com validação MIME real)
function uploadImage($file, $targetDir = "../assets/uploads/") {
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    
    // Validar erro de upload
    if ($file['error'] !== UPLOAD_ERR_OK) return false;
    
    // Validar tamanho (máx 5MB)
    if ($file['size'] > 5 * 1024 * 1024) return false;
    
    // Validar extensão
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowTypes = ['jpg', 'png', 'jpeg', 'gif', 'webp', 'ico'];
    if (!in_array($fileType, $allowTypes)) return false;
    
    // Validar MIME type real (não confiar apenas na extensão)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon'];
    if (!in_array($mimeType, $allowedMimes)) return false;
    
    // Validar que é uma imagem real (previne upload de PHP disfarçado)
    if (!in_array($fileType, ['ico']) && !getimagesize($file['tmp_name'])) return false;
    
    // Gerar nome seguro (remove caracteres perigosos)
    $safeName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', basename($file['name']));
    $fileName = time() . "_" . $safeName;
    $targetFilePath = $targetDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return "assets/uploads/" . $fileName;
    }
    return false;
}

// Função para validar login
function login($username, $password) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT id, password_hash FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            initSecureSession();
            session_regenerate_id(true); // Previne Session Fixation
            $_SESSION[ADMIN_SESSION_NAME] = $user['id'];
            resetLoginAttempts();
            return true;
        }
    } catch(Exception $e) {
        return false;
    }
    return false;
}
?>

