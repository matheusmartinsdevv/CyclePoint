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
    <title>Configurações - CyclePoint</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/table.css">
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

    <?php

    // Verifica se há uma mensagem de feedback
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $class = ($message['type'] === 'success') ? 'success-message-categoria' : 'error-message-categoria';
        
        echo '<div class="' . $class . '" style="background-color:#cdffed; padding: 5px;">'; 
        echo htmlspecialchars($message['text']);
        echo '</div>';
        
        
        unset($_SESSION['message']);
    }
    ?>

    <main>
        <div class="container page-container">
            <h1 class="page-title">Configurações da Conta e do Sistema</h1>
            <p class="form-description">Gerencie informações da empresa, preferências de notificação e integrações.</p>

            <h3 class="second-title">Cadastro de Categoria</h3>
            
            <form action="./app/php/cadastroCategoria.php" method="POST" class="form-content wide-form">
                <p class="form-description">Preencha todos os campos obrigatórios para registrar a nova categoria no sistema.</p>
                
                <div class="form-grid grid-2-columns">

                    <div class="input-group">
                        <label for="modelo">Nome da Categoria*</label>
                        <input type="text" id="nome-categoria" name="nome-categoria" placeholder="Ex: Notebook" required>
                    </div>

                    <div class="input-group">
                        <label for="modelo">Descrição da Categoria*</label>
                        <input type="text" id="descricao-categoria" name="descricao-categoria" required>
                    </div>

                    
                <button type="submit" class="btn btn-primary btn-large">Registrar Categoria</button>
            </form>

            
            
            
        </div>

        <hr>

        <div class="container page-container">
            <h3 class="second-title">Exibir Categorias</h3>


            <div class="form-content wide-form">

                <div class="form-grid grid-2-columns exibir-categoria">

                    <div class="input-group categoria-text">
                        <h4>Nome da Categoria:</h4>
                    </div>

                    <div class="input-group categoria-text">
                        <h4>Descrição da Categoria:</h4>
                    </div>

                </div>

                <hr class="normal-margin">

                <!-- CÓDIGO QUE CONECTA COM BANCO DE DADOS -->

                <div style="display: flex; justify-content: space-between;">
                    <span class="nome_categoria"></span>
                    <span class="descricao_categoria"></span>
                </div>

                <hr class="normal-margin">

                
            </div>
        </div>

        
    </main>


    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 CyclePoint. Gerenciamento de Ativos de TI.</p>
        </div>
    </footer>

    <script>
        
        const nome_categoria = document.querySelector(".nome_categoria");
        const descricao_categoria = document.querySelector(".descricao_categoria");

        


    </script>
</body>
</html>