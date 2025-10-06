<?php
session_start();
// $id_logado = $_SESSION['id_usuario'];
// $nome_logado = $_SESSION['nome_usuario'];

$nome_logado_display = isset($_SESSION['nome_logado_display']) ? $_SESSION['nome_logado_display'] : 'Visitante';
$role_logado = isset($_SESSION['role']) ? $_SESSION['role'] : 'deslogado';

$role_text = ($role_logado == 'administrador') ? 'Administrador' : 'Usuário Comum';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Descartes - CyclePoint</title>
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

                <a href="meus-descartes.php" class="nav-item active">Meus Descartes</a>

                <a href="configuracoes.php" class="nav-item">Configurações</a>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="gerenciar-usuarios.php" class="nav-item">Gerenciar Usuários</a><?php endif; ?>

                <div class="user-info">
                    <span class="user-role"><?php echo $nome_logado_display; ?></span> 
                </div>

                <a href="login.php">Sair</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container page-container">
            <h1 class="page-title">Histórico e Agendamentos de Descartes</h1>
            <p class="form-description">Aqui você pode acompanhar o status de seus ativos de TI em processo de descarte e agendar novas coletas.</p>
        </div>
    </main>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 CyclePoint. Gerenciamento de Ativos de TI.</p>
        </div>
    </footer>

</body>
</html>