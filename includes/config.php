<?php
/**
 * Configurações de Conexão - Painel Admin Resimetal
 */

// Se estiver local, use as configurações abaixo. Na Hostinger, substitua pelos dados criados no painel.
define('DB_HOST', 'localhost');
define('DB_NAME', 'u_resimetal_db'); // Substituir pelo nome real
define('DB_USER', 'u_resimetal_user'); // Substituir pelo usuário real
define('DB_PASS', 'sua_senha_aqui'); // Substituir pela senha real

// Iniciar conexão PDO
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Em produção, silenciamos o erro para não expor dados
    // die("Erro crítico de conexão com o banco de dados.");
}

// Configurações de Segurança e URL
define('ADMIN_SESSION_NAME', 'resimetal_admin_session');
define('BASE_URL', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/");
?>
