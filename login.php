
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CyclePoint</title>
    <link rel="stylesheet" href="css/login.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="login-body">

     <?php
    session_start();

    // Verifica se há uma mensagem de feedback
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $class = ($message['type'] === 'success') ? 'success-message' : 'error-message';
        
        echo '<div class="' . $class . '" style="background-color:#cdffed;">'; 
        echo htmlspecialchars($message['text']);
        echo '</div>';
        
        
        unset($_SESSION['message']);
    }
    ?>

    <div class="login-mascote" style="display: flex; flex-direction:row;">
        <img src="img/mascote.png" alt="" style="width: 30em;height: 30em;margin-top: 50px;">


        <div class="auth-container">
            <div class="auth-container">
    
    <div class="login-header">
        <h2 class="title-primary">Acesso CyclePoint</h2>
        <p class="role-hint">Entre com suas credenciais de usuário.</p>
    </div>
    
    <form action="app/php/login.php" method="POST" class="form-content">
        <div class="input-group">
            <label for="login-email">E-mail</label>
            <input type="email" id="login-email" name="email" required>
        </div>
        <div class="input-group">
            <label for="login-senha">Senha</label>
            <input type="password" id="login-senha" name="senha" required>
        </div>
        
        <div class="login-action-box">
            <a href="#" class="forgot-link small-link">Esqueceu a senha?</a>
            <button type="submit" class="btn btn-primary btn-full-width">Entrar</button>
        </div>
        
    </form>

    <p class="separator">Não tem conta?</p>
    
    <div class="registration-options">
        <a href="cadastro.html" class="btn btn-secondary btn-full-width">
            <span class="icon"></span> Cadastre sua Empresa
        </a>
        <a href="cadastro-usuario.html" class="btn btn-secondary btn-full-width">
           <span class="icon"></span> Cadastre um Usuário
        </a>
    </div>

    <a href="index.html" class="forgot-link back-to-home">
        &#x2190; Voltar para a Página Inicial
    </a>
</div>
        </div>
    </div>

</body>
</html>