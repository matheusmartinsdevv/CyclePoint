<?php
session_start();
$id_logado = $_SESSION['id_usuario'];
$nome_logado = $_SESSION['nome_usuario'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - CyclePoint</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
        <div class="container">
            <img class="logo" src="img/logo.png" alt="CyclePoint Logo">
            <nav>

                <a href="dashboard.php" class="nav-item">Dashboard</a>
                <a href="cadastro-equipamento.php" class="nav-item">Cadastrar Equipamento</a>
                <a href="meus-descartes.php" class="nav-item">Meus Descartes</a>
                <a href="gerenciar-usuarios.php" class="nav-item">Gerenciar Usuários</a>
                <a href="configuracoes.php" class="nav-item active">Configurações</a>
                
                <div class="user-info">
                    <span class="user-role">Administrador</span>
                    <span>| Empresa Teste</span>
                </div>
                
                <a href="login.html">Sair</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container page-container">
            <h1 class="page-title">Configurações da Conta e do Sistema</h1>
            <p class="form-description">Gerencie informações da empresa, preferências de notificação e integrações.</p>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 CyclePoint. Gerenciamento de Ativos de TI.</p>
        </div>
    </footer>

</body>
</html>