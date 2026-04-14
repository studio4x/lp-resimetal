<?php
/**
 * MASTER TEMPLATE CONFIG - Studio 4x
 * Este arquivo centraliza a identidade do projeto para fácil replicação.
 */

// 1. DADOS DO CLIENTE (Mudar aqui para cada novo projeto)
define('SITE_NAME', 'Resimetal Beneficiamentos');
define('CLIENT_NAME', 'Resimetal');
define('PRIMARY_COLOR', '#105576'); // Cor principal do projeto
define('SECONDARY_COLOR', '#158E12'); // Cor de destaque/CTA

// 2. CONFIGURAÇÕES DE SERVIDOR (Banco de Dados)
define('DB_HOST', 'localhost');
define('DB_NAME', 'u206345391_resimetal'); 
define('DB_USER', 'u206345391_resimetal'); 
define('DB_PASS', 'u5G$wFC2Nvn'); 

// 3. CONEXÃO PDO
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Silencioso em produção
}

// 4. SEGURANÇA E VERSIONAMENTO
define('ADMIN_SESSION_NAME', 'studio4x_admin_session'); // Único por projeto para evitar conflito de aba
define('BASE_URL', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/");
define('BUILD_VERSION', '1.4.0');
?>