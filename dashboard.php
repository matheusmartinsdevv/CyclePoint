<?php
session_start();
// $id_logado = $_SESSION['id_usuario'];

$nome_logado_display = isset($_SESSION['nome_logado_display']) ? $_SESSION['nome_logado_display'] : 'Visitante';
$role_logado = isset($_SESSION['role']) ? $_SESSION['role'] : 'deslogado';

$role_text = ($role_logado == 'administrador') ? 'Administrador' : 'Usuário Comum';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CyclePoint</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
        <div class="container">
            <img class="logo" src="img/logo.png" alt="CyclePoint Logo">
            <nav>
                <a href="dashboard.php" class="nav-item active">Dashboard</a>

                <a href="cadastro-equipamento.php" class="nav-item">Cadastrar Equipamento</a>

                <a href="meus-descartes.php" class="nav-item">Meus Descartes</a>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="configuracoes.php" class="nav-item active">Configurações</a><?php endif; ?>

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
            <h1 class="page-title">Visão Geral do Sistema</h1>
            
            <div class="card-grid">
                
                <div class="card data-card">
                    <div class="card-title">Equipamentos Cadastrados</div>
                    <div class="card-value"></div>
                    <p class="card-detail">Total de ativos gerenciados.</p>
                </div>

                <div class="card data-card">
                    <div class="card-title">Descarte Pendente</div>
                    <div class="card-value value-danger"></div>
                    <p class="card-detail">Ativos com vida útil expirada.</p>
                </div>

                <div class="card data-card">
                    <div class="card-title">Fim de Vida Útil (30 dias)</div>
                    <div class="card-value value-warning"></div>
                    <p class="card-detail">Equipamentos a serem descartados em breve.</p>
                </div>

                <div class="card data-card">
                    <div class="card-title">Coletas Agendadas</div>
                    <div class="card-value value-success"></div>
                    <p class="card-detail">Próximo agendamento:.</p>
                </div>
            </div>

            <h2 class="section-title">Ações do Sistema</h2>
            
            <div class="card action-card">
                <h3 class="card-title">Novo Cadastro</h3>
                <p>Registre um novo equipamento de TI ou gere um relatório.</p>
                <a href="cadastro-equipamento.php" class="btn btn-primary btn-small">Cadastrar Novo Ativo</a>
            </div>
        </div>
    </main>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 CyclePoint. Gerenciamento de Ativos de TI.</p>
        </div>
    </footer>

</body>
</html>