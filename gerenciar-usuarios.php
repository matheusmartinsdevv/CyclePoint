<?php
session_start();

$nome_logado_display = isset($_SESSION['nome_logado_display']) ? $_SESSION['nome_logado_display'] : 'Visitante';
$role_logado = isset($_SESSION['role']) ? $_SESSION['role'] : 'deslogado';

$role_text = ($role_logado == 'administrador') ? 'Administrador' : 'Usuário Comum';

if (!isset($_SESSION['id_empresa']) && !isset($_SESSION['id_usuario'])) {
    header("refresh:0.5;url=/CyclePoint/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários - CyclePoint</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/notificacao.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
        <div class="container">
            <img class="logo" src="img/logo.png" alt="CyclePoint Logo">
            <nav>
                <a href="dashboard.php" class="nav-item">Dashboard</a>

                <a href="cadastro-equipamento.php" class="nav-item">Equipamentos</a>

                <a href="meus-descartes.php" class="nav-item">Descartes</a>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="configuracoes.php" class="nav-item ">Configurações</a><?php endif; ?>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="gerenciar-usuarios.php" class="nav-item active">Gerenciar Usuários</a><?php endif; ?>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="gerenciar-enderecos.php" class="nav-item">Gerenciar endereços</a><?php endif; ?>

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
            <h1 class="page-title">Gerenciamento de Usuários</h1>
            <p class="form-description">Adicione, edite ou remova usuários com acesso ao sistema CyclePoint da sua empresa.</p>

            <h3 class="second-title">Novo Cadastro</h3>


            <div class="auth-container large-container" style="margin: auto;">

                <!-- CADASTRO DE USUÁRIO -->
                <form id="form-usuario" action="./app/php/cadastroUsuario2.php" method="POST" class="registration-form active">
                    <h3>Dados do Usuário</h3>

                    <div class="form-grid">
                        <div class="input-group"><label>Nome*</label><input type="text" name="nome" required></div>
                        <div class="input-group"><label>Cargo</label><input type="text" name="cargo" required></div>
                        <div class="input-group"><label>E-mail*</label><input type="email" name="email" required></div>
                        <div class="input-group"><label>Defina sua Senha*</label><input type="password" name="senha" required>
                        </div>
                    </div>
                    <hr>


                    <button type="submit" class="btn btn-primary btn-large">Cadastrar Usuário</button>
                </form>

            </div>

            <hr>

            <h3 class="second-title">Exibir usuários</h3>

            <div class="container page-container">
                
                <div class="form-content wide-form">
                    <div>
                        <?php include 'app/php/exibirUsuarios.php'; ?>
                    </div>

                    <!-- Modal para editar/excluir usuário -->
                    <div id="modalUsuario" class="modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
                        <div class="modal-content" style="background:#fff; padding:20px; width:90%; max-width:600px; border-radius:6px; position:relative;">
                            <button id="modalUsuarioClose" class="btn btn-secondary" style="position:absolute; right:10px; top:10px; padding:6px 10px;">Fechar</button>
                            <div id="modalUsuarioBody">Carregando...</div>
                        </div>
                    </div>

                    <style>
                    .excluir-usuario:hover { background-color: #dc3545 !important; border-color: #dc3545 !important; color: white !important; }
                    .btn-danger { background-color: #dc3545; border-color: #dc3545; color: white; }
                    .btn-danger:hover { background-color: #bb2d3b; border-color: #b02a37; }
                    </style>

                    <script>
                    document.addEventListener('click', function(e) {
                        if (e.target && e.target.id === 'modalUsuarioClose') { document.getElementById('modalUsuario').style.display = 'none'; }

                        if (e.target && e.target.classList.contains('editar-usuario')) {
                            const id = e.target.getAttribute('data-id');
                            fetch('app/php/get_usuario.php?id=' + encodeURIComponent(id))
                                .then(r => r.json())
                                .then(data => {
                                    if (!data.success) { document.getElementById('modalUsuarioBody').innerHTML = '<div style="color:#D9534F; font-weight:700;">Erro: ' + (data.message || 'Erro ao buscar usuário') + '</div>'; document.getElementById('modalUsuario').style.display = 'flex'; return; }
                                    const u = data.data;
                                    const html = `
                                        <h2>Editar usuário</h2>
                                        <form id="formEditarUsuario">
                                            <input type="hidden" name="id_usuario" value="${u.id_usuario}">
                                            <div><label>Nome<br><input name="nome" class="form-control" value="${u.nome}" required></label></div>
                                            <div><label>E-mail<br><input name="email" class="form-control" value="${u.email}" required></label></div>
                                            <div><label>Cargo<br><input name="cargo" class="form-control" value="${u.cargo}"></label></div>
                                            <div style="margin-top:10px;"><button type="submit" class="btn btn-primary">Salvar</button></div>
                                        </form>
                                    `;
                                    document.getElementById('modalUsuarioBody').innerHTML = html;
                                    document.getElementById('modalUsuario').style.display = 'flex';

                                    document.getElementById('formEditarUsuario').onsubmit = function(ev){ ev.preventDefault(); this.action='app/php/update_usuario.php'; this.method='POST'; this.submit(); };
                                })
                                .catch(err => { console.error(err); document.getElementById('modalUsuarioBody').innerHTML = '<div style="color:#D9534F; font-weight:700;">Erro de rede ao buscar usuário.</div>'; document.getElementById('modalUsuario').style.display = 'flex'; });
                        }

                        if (e.target && e.target.classList.contains('excluir-usuario')) {
                            const id = e.target.getAttribute('data-id');
                            const html = `
                                <div style="text-align:center;">
                                    <h2 style="color:#dc3545;">Confirmar Exclusão</h2>
                                    <p style="margin:20px 0;">Tem certeza que deseja excluir este usuário?</p>
                                    <p style="color:#666; font-size:14px;">Esta ação não pode ser desfeita.</p>
                                    <div style="margin-top:20px;"><button id="confirmarExclusaoUsuario" class="btn btn-danger" style="margin-right:10px;">Excluir</button><button id="cancelarExclusaoUsuario" class="btn btn-secondary">Cancelar</button></div>
                                </div>
                            `;
                            document.getElementById('modalUsuarioBody').innerHTML = html;
                            document.getElementById('modalUsuario').style.display = 'flex';

                            document.getElementById('confirmarExclusaoUsuario').onclick = function(){ const form=document.createElement('form'); form.method='POST'; form.action='app/php/delete_usuario.php'; const input=document.createElement('input'); input.type='hidden'; input.name='id_usuario'; input.value=id; form.appendChild(input); document.body.appendChild(form); form.submit(); };
                            document.getElementById('cancelarExclusaoUsuario').onclick = function(){ document.getElementById('modalUsuario').style.display = 'none'; };
                        }
                    });
                    </script>
                </div>
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