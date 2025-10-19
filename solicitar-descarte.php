<?php
session_start();
// $id_logado = $_SESSION['id_usuario'];
// $nome_logado = $_SESSION['nome_usuario'];

$nome_logado_display = isset($_SESSION['nome_logado_display']) ? $_SESSION['nome_logado_display'] : 'Visitante';
$role_logado = isset($_SESSION['role']) ? $_SESSION['role'] : 'deslogado';

$role_text = ($role_logado == 'administrador') ? 'Administrador' : 'Usuário Comum';

if (isset($_GET['id_recicladora'])) {
    $_SESSION['id_recicladora'] = $_GET['id_recicladora'];
}
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
                <a href="meus-descartes.php" class="nav-item"><button class="btn btn-primary">Voltar</button></a>

                <div class="user-info">
                    <span class="user-role"><?php echo $nome_logado_display; ?></span> 
                </div>

                <a href="login.php">Sair</a>
            </nav>
        </div>
    </header>

    <?php
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
            <h1 class="page-title">Descarte de equipamentos</h1>
            <p class="form-description">Selecione o(s) esquipamento(s) para descarte.</p>

            <h3 class="second-title"></h3>
            
            

            <div class="container page-container">


            <form action="app/php/processar_descarte.php" method="POST"> 
        
                <div class="form-content wide-form">
                    <div>
                        <?php include 'app/php/exibirEquipamentoDescarte.php'; ?>   
                    </div>
                </div>

                <button type="submit" class="btn btn-primary ver-detalhes">Enviar solicitação</button>
            </form>


        </div>
        
    </main>


    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 CyclePoint. Gerenciamento de Ativos de TI.</p>
        </div>
    </footer>


</body>
</html>