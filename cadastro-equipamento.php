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
    <title>Cadastro de Equipamento - CyclePoint</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
        <div class="container">
            <img class="logo" src="img/logo.png" alt="CyclePoint Logo">
            <nav>
                <a href="dashboard.php" class="nav-item">Dashboard</a>
                <a href="cadastro-equipamento.php" class="nav-item active">Cadastrar Equipamento</a>
                <a href="meus-descartes.php" class="nav-item">Meus Descartes</a>
                <a href="gerenciar-usuarios.php" class="nav-item">Gerenciar Usuários</a>
                <a href="configuracoes.php" class="nav-item">Configurações</a>
                
                <div class="user-info">
                    <span class="user-role"><?php echo $nome_logado ?></span>
                </div>
                
                <a href="login.php">Sair</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container page-container">
            <h1 class="page-title">Cadastro de Equipamento de TI</h1>
            
            <form action="/api/cadastro-equipamento" method="POST" class="form-content wide-form">
                <p class="form-description">Preencha todos os campos obrigatórios para registrar o novo ativo no sistema.</p>
                
                <div class="form-grid grid-2-columns">
                    
                    <div class="input-group">
                        <label for="ip">Endereço de IP</label>
                        <input type="text" id="ip" name="ip" placeholder="Ex: 192.168.1.100" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="fabricante">Nome do Fabricante</label>
                        <input type="text" id="fabricante" name="fabricante" placeholder="Ex: Dell, HP, Samsung" required>
                    </div>

                    <div class="input-group">
                        <label for="aquisicao">Data de Aquisição</label>
                        <input type="date" id="aquisicao" name="data_aquisicao" required>
                    </div>

                    <div class="input-group">
                        <label for="categoria">Categoria (Tipo de Equipamento)</label>
                        <select id="categoria" name="categoria" required>
                            <option value="" disabled selected>Selecione a Categoria</option>
                            <option value="desktop">Desktop</option>
                            <option value="notebook">Notebook</option>
                            <option value="monitor">Monitor</option>
                            <option value="servidor">Servidor</option>
                            <option value="impressora">Impressora</option>
                            <option value="celular">Celular Corporativo</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label for="modelo">Nome do Modelo</label>
                        <input type="text" id="modelo" name="modelo" placeholder="Ex: OptiPlex 3080" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="local">Local de Alocação</label>
                        <input type="text" id="local" name="local_alocacao" placeholder="Ex: Sala 301, Térreo/TI" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-large">Registrar Equipamento</button>
            </form>

        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 CyclePoint. Gerenciamento de Ativos de TI.</p>
        </div>
    </footer>

</body>
</html>