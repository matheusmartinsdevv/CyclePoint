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
    <link rel="stylesheet" href="css/notificacao.css">
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
            <h1 class="page-title">Visão das solicitações de descarte</h1>
            <p class="form-description">Aqui você pode gerenciar o status de cada solicitação.</p>



            <h3 class="second-title">Solicitações de descarte</h3>

            <div class="form-content wide-form">


                <div class="">

                    <?php include 'app/php/exibirSolicitacoesDescarteRecicladora.php'; ?>

                
                
                </div>
                


            </div>

            <h3 class="second-title" style="margin-top:20px;">Definir datas das coletas</h3>

            <div class="form-content wide-form">


                <div class="">

                    <?php include 'app/php/exibirSolicitacoesData.php'; ?>

                
                
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
            const botoesAcao = document.querySelectorAll('.aceitar-solicitacao, .recusar-solicitacao');
            const url = 'app/php/processar_retorno_solicitacao.php'; 

            botoesAcao.forEach(botao => {
                botao.addEventListener('click', function() {
                    const idSolicitacao = this.getAttribute('data-id');
                    const acao = this.getAttribute('data-acao'); // 'Aceito' ou 'Recusado'
                    const linhaTabela = this.closest('tr'); // Assumindo que o botão está em uma <tr>

                    // 1. Prepara a visualização imediata
                    if (linhaTabela) {
                        // Opcional: Desabilita temporariamente os botões e indica que está processando
                        this.disabled = true;
                        linhaTabela.style.opacity = '0.6';
                        linhaTabela.title = 'Processando...';
                    }
                    
                    // Não há confirm() aqui, a ação é imediata

                    const dados = new FormData();
                    dados.append('id_solicitacao_descarte', idSolicitacao);
                    dados.append('acao', acao); 

                    fetch(url, {
                        method: 'POST',
                        body: dados
                    })
                    .then(response => response.json()) 
                    .then(data => {
                        console.log('Resposta do Servidor:', data);
                        
                        // 2. Não usa alert(), mas checa o resultado
                        if (data.success) {
                            // Sucesso: Recarrega a página imediatamente para atualizar a lista.
                            window.location.reload(); 
                        } else {
                            // Erro: Se não foi sucesso, mostra um erro no console e/ou tenta reabilitar
                            console.error(`Erro ao processar a solicitação ${idSolicitacao}: ${data.message}`);
                            
                            // Reverte a visualização em caso de erro
                            if (linhaTabela) {
                                this.disabled = false;
                                linhaTabela.style.opacity = '1';
                                linhaTabela.title = `Erro: ${data.message}`;
                            }
                        }
                    })
                    .catch(error => {
                        // Erro de comunicação (rede)
                        console.error('Erro de comunicação com o servidor:', error);
                        if (linhaTabela) {
                            this.disabled = false;
                            linhaTabela.style.opacity = '1';
                            linhaTabela.title = 'Erro de Rede.';
                        }
                    });
                });
            });
        });
    </script>

    <!-- Notificações -->
    <script src="js/notificacao.js"></script>
    
    
</body>
</html>