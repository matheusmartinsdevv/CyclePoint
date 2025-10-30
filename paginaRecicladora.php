<?php
session_start();
// $id_logado = $_SESSION['id_usuario'];

$nome_logado_display = isset($_SESSION['nome_logado_display']) ? $_SESSION['nome_logado_display'] : 'Visitante';
// $role_logado = isset($_SESSION['role']) ? $_SESSION['role'] : 'deslogado';

// $role_text = ($role_logado == 'administrador') ? 'Administrador' : 'Usuário Comum';
if (!isset($_SESSION['id_recicladora'])) {
    header("refresh:0.5;url=/CyclePoint/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CyclePoint</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/notificacao.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
        <div class="container">
            <img class="logo" src="img/logo.png" alt="CyclePoint Logo">
            <nav>
                <a href="paginaRecicladora.php" class="nav-item active">Coletas</a>

                <a href="solicitacoesRecicladora.php" class="nav-item">Solicitações de Descarte</a>

                <a href="itens-que-coleto.php" class="nav-item">Itens que coleto</a>

                <div class="notificacao-container">
                    <button id="btnNotificacao" class="nav-item notificacao-toggle">
                        <img src="img/notificacao.png" alt="Notificações">
                        <span id="notificacaoBadge" class="notificacao-badge hidden">0</span>
                    </button>
                    
                    <div id="notificacaoDropdown" class="notificacao-dropdown hidden">
                        <div class="dropdown-header">
                            <h4>Notificações</h4>
                            <a href="#" id="marcarTodasLidas" class="marcar-lidas-link">Marcar todas como lidas</a>
                        </div>
                        <ul id="notificacaoLista" class="dropdown-body">
                            <li style="text-align: center; color: #7f8c8d;">Buscando notificações...</li>
                        </ul>
                    </div>
                </div>

                <div class="user-info">
                    <span class="user-role"><?php echo $nome_logado_display; ?></span> 
                </div>

                <a href="login.php">Sair</a>
            </nav>
        </div>
    </header>

    <main>

        <div class="container page-container">
            <h1 class="page-title">Histórico das Coletas</h1>
            <p class="form-description"></p>



            <h3 class="second-title">Próximas coletas</h3>

            <div class="form-content wide-form">


                <div class="">

                    <?php include 'app/php/exibirProximasColetas.php'; ?>

                
                
                </div>
                


            </div><br>

            <h3 class="second-title">Consultar histórico</h3>

            <div class="form-content wide-form">


                <div class="">

                    <?php include 'app/php/exibirHistoricoColetas.php'; ?>

                
                
                </div>
                


            </div>



        </div>
            
    </main>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 CyclePoint. Gerenciamento de Ativos de TI.</p>
        </div>
    </footer>

    <!-- Notificações -->
    <script src="js/notificacao.js"></script>
    
</body>
</html>