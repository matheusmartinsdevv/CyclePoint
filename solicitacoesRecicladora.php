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
    <title>Solicitações de descarte - CyclePoint</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/recicladora.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
        <div class="container">
            <img class="logo" src="img/logo.png" alt="CyclePoint Logo">
            <nav>
                <a href="paginaRecicladora.php" class="nav-item">Coletas</a>

                <a href="solicitacoesRecicladora.php" class="nav-item active">Solicitações de Descarte</a>

                <a href="itens-que-coleto.php" class="nav-item">Itens que coleto</a>

                <button><img src="img/notificacao.png" alt=""></button>

                <div class="user-info">
                    <span class="user-role"><?php echo $nome_logado_display; ?></span> 
                </div>

                <a href="login.php">Sair</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container page-container">
            <h1 class="page-title">Visão das solicitações de descarte</h1>
            <p class="form-description">Aqui você pode gerenciar o status de cada solicitação.</p>



            <h3 class="second-title">Solicitações de descarte</h3>

            <div class="form-content wide-form">


                <div class="">

                    <?php include 'app/php/exibirSolicitacoesDescarteRecicladora.php'; ?>

                
                
                </div>
                


            </div>



        </div>
            
    </main>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 CyclePoint. Gerenciamento de Ativos de TI.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Seleciona todos os botões com a classe 'aceitar-solicitacao' ou 'recusar-solicitacao'
            const botoesAcao = document.querySelectorAll('.aceitar-solicitacao, .recusar-solicitacao');

            botoesAcao.forEach(botao => {
                botao.addEventListener('click', function() {
                    // 1. Obtém o ID da solicitação e a ação (Aceito ou Recusado) dos atributos data-
                    const idSolicitacao = this.getAttribute('data-id');
                    const acao = this.getAttribute('data-acao'); // 'Aceito' ou 'Recusado'
                    
                    if (!confirm(`Tem certeza que deseja ${acao === 'Aceito' ? 'ACEITAR' : 'RECUSAR'} a solicitação ID ${idSolicitacao}?`)) {
                        return; // Cancela se o usuário clicar em "Não"
                    }

                    // 2. Define o script PHP que irá processar a requisição
                    const url = 'app/php/processar_retorno_solicitacao.php'; // Crie este novo arquivo no passo 3

                    // Dados a serem enviados via POST
                    const dados = new FormData();
                    dados.append('id_solicitacao_descarte', idSolicitacao);
                    dados.append('acao', acao); // Envia 'Aceito' ou 'Recusado'

                    // 3. Usa Fetch API para enviar a requisição
                    fetch(url, {
                        method: 'POST',
                        body: dados
                    })
                    .then(response => response.json()) // Espera uma resposta JSON do PHP
                    .then(data => {
                        console.log('Resposta do Servidor:', data);
                        if (data.success) {
                            alert(`Solicitação ${idSolicitacao} foi ${acao} com sucesso!`);
                            // 4. Recarrega a página para atualizar a lista
                            window.location.reload(); 
                        } else {
                            alert(`Erro ao processar a solicitação ${idSolicitacao}: ${data.message}`);
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                        alert('Erro de comunicação com o servidor.');
                    });
                });
            });
        });
    </script>


    
    
</body>
</html>