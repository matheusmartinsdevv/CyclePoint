<?php
session_start();


$nome_logado_display = isset($_SESSION['nome_logado_display']) ? $_SESSION['nome_logado_display'] : 'Visitante';
$role_logado = isset($_SESSION['role']) ? $_SESSION['role'] : 'deslogado';

$role_text = ($role_logado == 'administrador') ? 'Administrador' : 'Usuário Comum';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipamentos - CyclePoint</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
        <div class="container">
            <img class="logo" src="img/logo.png" alt="CyclePoint Logo">
            <nav>
                <a href="dashboard.php" class="nav-item">Dashboard</a>

                <a href="cadastro-equipamento.php" class="nav-item active">Equipamentos</a>

                <a href="meus-descartes.php" class="nav-item">Descartes</a>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="configuracoes.php" class="nav-item ">Configurações</a><?php endif; ?>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="gerenciar-usuarios.php" class="nav-item">Gerenciar Usuários</a><?php endif; ?>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="gerenciar-enderecos.php" class="nav-item">Gerenciar endereços</a><?php endif; ?>

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
            <h1 class="page-title">Gestão de Equipamentos</h1>
            <hr>

            <h3 class="second-title">Registrar Equipamentos</h3>
            <form action="app/php/cadastroEquipamento.php" method="POST" class="form-content wide-form">
                <p class="form-description">Preencha todos os campos obrigatórios para registrar o novo ativo no sistema.</p>

                <div class="form-grid grid-2-columns">
 
                    <div class="input-group">
                        <label for="categoria">Categoria* (Tipo de Equipamento)</label>
                        <select id="categoria" name="categoria" required>
                            <option value="" disabled selected>Selecione a Categoria</option>
                            
                            <!-- DINÂMICO -->

                            <?php include 'app/php/listarCategorias.php'; ?>


                        </select>
                    </div>

                    <div class="input-group">
                        <label for="modelo">Nome do Equipamento*</label>
                        <input type="text" id="nome-equipamento" name="nome-equipamento" placeholder="Ex: Computador" required>
                    </div>

                    <div class="input-group">
                        <label for="modelo">Nome do Modelo*</label>
                        <input type="text" id="modelo" name="modelo" placeholder="Ex: OptiPlex 3080" required>
                    </div>
                    
                    
                    <div class="input-group">
                        <label for="fabricante">Nome do Fabricante*</label>
                        <input type="text" id="fabricante" name="fabricante" placeholder="Ex: Dell, HP, Samsung" required>
                    </div>

                    <div class="input-group">
                        <label for="aquisicao">Data de Aquisição*</label>
                        <input type="date" id="aquisicao" name="data_aquisicao" required>
                    </div>

                    <div class="input-group">
                        <label for="vida_util">Vida útil (em meses)</label>
                        <input type="number" id="vida_util" name="vida_util_meses" placeholder="Ex: 48">
                    </div>

                    <div class="input-group">
                        <label for="endereco_mac">Endereço MAC</label>
                        <input type="text" id="endereco_mac" name="endereco_mac" placeholder="Ex: 00:1A:2B:3C:4D:5E">
                    </div>
                    
                    
                    <div class="input-group">
                        <label for="endereco">Endereço de alocação*</label>
                        <select id="endereco" name="endereco" required>
                            <option value="" disabled selected>Selecione o Endereço</option>
                            
                            <!-- DINÂMICO -->

                            <?php include 'app/php/listarEnderecosEmpresa.php'; ?>


                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-large">Registrar Equipamento</button>
            </form>

        </div>

        <div class="container page-container">
            <h3 class="second-title">Exibir Equipamentos</h3>


            <div class="form-content wide-form">

                <!-- <div class="form-grid grid-2-columns exibir-categoria" style="justify-content: space-around;">

                    <div class="input-group categoria-text">
                        <h4>Nome do Equipamento:</h4>
                    </div>


                </div>

                <hr class="normal-margin"> -->

                <!-- CÓDIGO QUE CONECTA COM exibirEquipamento.php -->
                <div>

                    <?php include 'app/php/exibirEquipamento.php'; ?>


                </div>

                

                
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 CyclePoint. Gerenciamento de Ativos de TI.</p>
        </div>
    </footer>

    <script src="js/cadastro-equipamento.js"></script>
</body>
</html>