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
    <link rel="stylesheet" href="css/endereco.css">
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
                <a href="configuracoes.php" class="nav-item ">Configurações</a><?php endif; ?>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="gerenciar-usuarios.php" class="nav-item">Gerenciar Usuários</a><?php endif; ?>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="gerenciar-enderecos.php" class="nav-item active" >Gerenciar endereços</a><?php endif; ?>

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
            <h1 class="page-title">Gerenciamento de endereços</h1>
            <p class="form-description">Gerencie endereços da empresa, adicionando, editando ou removendo.</p>

            <h3 class="second-title">Cadastro de Endereço</h3>
            
            <form action="./app/php/cadastroEnderecoEmpresa.php" method="POST" class="form-content wide-form">
                <p class="form-description">Preencha todos os campos para registrar o novo endereço no sistema.</p>
                

                <div class="input-group">
                    <div class="form-grid address-grid">
                        <div class="input-group"><input type="text" placeholder="Logradouro" name="logradouro" required></div>
                        <div class="input-group"><input type="number" placeholder="Nº" name="numero" required class="input-small"></div>
                        <div class="input-group"><input type="text" placeholder="Bairro" name="bairro"></div>
                        <div class="input-group"><input type="text" placeholder="Cidade" name="cidade" required></div>
                        <div class="input-group"><input type="text" placeholder="Estado" name="estado" required></div>
                        <div class="input-group"><input type="text" placeholder="País" name="pais" value="Brasil" required>
                    </div>
                </div>

                    
                <button type="submit" class="btn btn-primary btn-large">Cadastrar</button>
            </form>
            
            
        </div>

        <hr>

        <div class="container page-container">
            <h3 class="second-title">Exibir endereços</h3>


            <div class="form-content wide-form">

                <div>

                    <?php include 'app/php/exibirEnderecosEmpresa.php'; ?>


                </div>

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