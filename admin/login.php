<?php
require_once('../includes/config.php');
require_once('../includes/functions.php');

initSecureSession();

// Se já estiver logado, vai direto pro dash
if (isset($_SESSION[ADMIN_SESSION_NAME])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar CSRF
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $error = "Requisição inválida. Recarregue a página.";
    }
    // Verificar rate limiting
    elseif (!checkLoginRateLimit()) {
        $remaining = ceil(getRemainingLockoutTime() / 60);
        $error = "Muitas tentativas. Aguarde {$remaining} minuto(s).";
    } else {
        $user = trim($_POST['username']);
        $pass = $_POST['password'];

        if (login($user, $pass)) {
            header("Location: index.php");
            exit();
        } else {
            registerFailedLogin();
            $attemptsLeft = MAX_LOGIN_ATTEMPTS - ($_SESSION['login_attempts'] ?? 0);
            if ($attemptsLeft > 0) {
                $error = "Usuário ou senha incorretos. ({$attemptsLeft} tentativa(s) restante(s))";
            } else {
                $remaining = ceil(LOGIN_LOCKOUT_TIME / 60);
                $error = "Conta bloqueada por {$remaining} minutos.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Admin Resimetal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        body {
            background-color: #105576;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Outfit', sans-serif;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-card h2 { color: #105576; margin-bottom: 24px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group input { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            font-size: 1rem;
        }
        .btn-login {
            background: #158E12;
            color: white;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            font-size: 1rem;
            transition: 0.3s;
        }
        .btn-login:hover { background: #69AF44; transform: translateY(-2px); }
        .error { color: #d32f2f; margin-bottom: 15px; font-size: 0.9rem; background: #fee2e2; padding: 10px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="../assets/logotipo-resimetal-transparente-otimizado.webp" alt="Resimetal" style="height: 50px; margin-bottom: 20px; filter: brightness(0) saturate(100%) invert(26%) sepia(26%) saturate(1478%) hue-rotate(162deg) brightness(91%) contrast(92%);">
        <h2>Acesso Administrativo</h2>
        
        <?php if($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <?php echo csrfField(); ?>
            <div class="form-group">
                <label>Usuário</label>
                <input type="text" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Entrar no Painel</button>
        </form>
    </div>
</body>
</html>
