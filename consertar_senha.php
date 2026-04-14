<?php
/**
 * Script para resetar a senha do administrador se o acesso for perdido
 */
require_once('includes/config.php');
require_once('includes/functions.php');

$username = 'admin';
$password = 'admin123'; // Senha que você quer definir
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Tenta atualizar a senha para o hash correto
    $stmt = $conn->prepare("UPDATE admin_users SET password = ? WHERE username = ?");
    $stmt->execute([$hashed_password, $username]);

    if ($stmt->rowCount() > 0) {
        echo "<h2>Senha do usuário 'admin' resetada com sucesso!</h2>";
        echo "<p>Agora você já pode tentar entrar em: <a href='admin/login.php'>resimetalbeneficiamentos.com.br/admin</a></p>";
        echo "<p><strong>Usuário:</strong> admin</p>";
        echo "<p><strong>Senha:</strong> admin123</p>";
    } else {
        // Se não houver o usuário 'admin', vamos criá-lo
        $stmt = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashed_password]);
        echo "<h2>Usuário 'admin' não existia e foi criado com a senha 'admin123'!</h2>";
        echo "<p>Tente entrar em: <a href='admin/login.php'>resimetalbeneficiamentos.com.br/admin</a></p>";
    }
} catch (PDOException $e) {
    echo "<h2>Erro ao atualizar a senha:</h2>";
    echo $e->getMessage();
}

echo "<hr><p style='color:red;'><strong>AVISO:</strong> Apague este arquivo após conseguir entrar para garantir a segurança do seu site.</p>";
?>
