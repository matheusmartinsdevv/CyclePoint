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
    <title>Itens que Coleto - CyclePoint</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/notificacao.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
        <div class="container">
            <img class="logo" src="img/logo.png" alt="CyclePoint Logo">
            <nav>
                <a href="paginaRecicladora.php" class="nav-item">Coletas</a>

                <a href="solicitacoesRecicladora.php" class="nav-item">Solicitações de Descarte</a>


                <a href="itens-que-coleto.php" class="nav-item active">Itens que coleto</a>

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
            <h1 class="page-title">Configuração dos itens coletados pela recicladora</h1>
            <p class="form-description">Gerencie as categorias de itens coletados por sua recicladora.</p>

            <hr>

            <h3 class="second-title">Cadastro de Item que Coleto</h3>
            
            <form action="./app/php/cadastroItemQueColeto.php" method="POST" class="form-content wide-form">
                <p class="form-description">Preencha todos os campos obrigatórios para registrar a nova categoria no sistema.</p>
                
                <div class="form-grid grid-2-columns">

                        <div class="input-group">
                            <label for="nome_recicladora_categoria">Nome do Item*</label>
                            <input type="text" id="nome_recicladora_categoria" name="nome_recicladora_categoria" placeholder="Ex: Notebook" required>
                        </div>

                    <div class="input-group">
                        <label for="modelo">Descrição do Item*</label>
                            <input type="text" id="descricao_recicladora" name="descricao_recicladora" required>
                    </div>

                    
                <button type="submit" class="btn btn-primary btn-large">Registrar Categoria</button>
            </form>

            
            
            
        </div>

        <div class="container page-container">
            <h3 class="second-title">Exibir Itens que Coleto</h3>


            <div class="form-content wide-form">



                <div class="exibir_categorias_recicladora" style="display: flex; justify-content: space-around; flex-direction: column">

                    <?php include 'app/php/exibirItensQueColeto.php'; ?>


                </div>

                

                
            </div>
        </div>

        
    </main>

    <script>
    $(document).ready(function() {
        // Manipulador para o botão de editar
        $('.editar-categoria').click(function() {
            var id = $(this).data('id');
            $.getJSON('app/php/get_categoria_recicladora.php?id=' + id, function(data) {
                $('#editId').val(data.id_recicladora_categoria);
                $('#editNome').val(data.nome_recicladora_categoria);
                $('#editDescricao').val(data.descricao);
                $('#editarModal').modal('show');
            });
        });

        // Manipulador para o botão de excluir
        $('.excluir-categoria').click(function() {
            var id = $(this).data('id');
            $('#deleteId').val(id);
            $('#excluirModal').modal('show');
        });
    });
    </script>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 CyclePoint. Gerenciamento de Ativos de TI.</p>
        </div>
    </footer>

    <!-- Notificações -->
    <script src="js/notificacao.js"></script>

</body>
</html>