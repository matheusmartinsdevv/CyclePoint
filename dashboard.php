<?php
session_start();
// $id_logado = $_SESSION['id_usuario'];

$nome_logado_display = isset($_SESSION['nome_logado_display']) ? $_SESSION['nome_logado_display'] : 'Visitante';
$role_logado = isset($_SESSION['role']) ? $_SESSION['role'] : 'deslogado';

$role_text = ($role_logado == 'administrador') ? 'Administrador' : 'Usuário Comum';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CyclePoint</title>
    <link rel="stylesheet" href="css/style.css"> 
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    
    <script>
        // Configuração do Tailwind (estendendo cores usando as variáveis CSS)
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'primary': 'var(--color-primary)', 
                        'secondary': 'var(--color-secondary)',
                        'background': 'var(--color-background)',
                        'surface': '#ffffff',
                        'text-primary': 'var(--color-text-primary)',
                        'danger': 'var(--color-danger)',
                        'warning': 'var(--color-warning)',
                        'success': 'var(--color-success)',
                        'info': 'var(--color-info)',
                    }
                }
            }
        }
    </script> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> 
</head>
<body>

    <header class="header">
        <div class="container">
            <img class="logo" src="img/logo.png" alt="CyclePoint Logo">
            <nav>
                <a href="dashboard.php" class="nav-item active">Dashboard</a>

                <a href="cadastro-equipamento.php" class="nav-item">Equipamentos</a>

                <a href="meus-descartes.php" class="nav-item">Descartes</a>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="configuracoes.php" class="nav-item ">Configurações</a><?php endif; ?>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="gerenciar-usuarios.php" class="nav-item">Gerenciar Usuários</a><?php endif; ?>

                <?php if ($role_logado == 'administrador'): ?>
                <a href="gerenciar-enderecos.php" class="nav-item">Gerenciar endereços</a><?php endif; ?>

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
            <h1 class="page-title">Visão Geral do Sistema</h1>
            
            <div class="card-grid">
                
                <div class="card data-card">
                    <div class="card-title">Equipamentos Cadastrados</div>
                    <div class="card-value"></div>
                    <p class="card-detail">Total de ativos gerenciados.</p>
                </div>

                <div class="card data-card">
                    <div class="card-title">Descarte Pendente</div>
                    <div class="card-value value-danger"></div>
                    <p class="card-detail">Ativos com vida útil expirada.</p>
                </div>

                <div class="card data-card">
                    <div class="card-title">Fim de Vida Útil (30 dias)</div>
                    <div class="card-value value-warning"></div>
                    <p class="card-detail">Equipamentos a serem descartados em breve.</p>
                </div>

                <div class="card data-card">
                    <div class="card-title">Coletas Agendadas</div>
                    <div class="card-value value-success"></div>
                    <p class="card-detail">Próximo agendamento:.</p>
                </div>
            </div>
            <!-- Seção do Gráfico -->
            <div class="bg-surface p-6 sm:p-8 rounded-xl shadow-2xl border border-gray-100"> 
                <h2 class="text-2xl font-bold mb-4 flex items-center text-text-primary">
                    <!-- Ícone de Gráfico (classes de cor customizadas) -->
                    <span class="chart-icon w-6 h-6 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20V10"/>
                            <path d="M18 20V4"/>
                            <path d="M6 20v-4"/>
                        </svg>
                    </span>
                    Status dos Equipamentos por Tipo
                </h2>
                
                <div class="w-full h-80">
                    <canvas id="graficoStatusEquipamentos"></canvas>
                </div>
            </div>

            <h2 class="section-title">Ações do Sistema</h2>
            
            <div class="card action-card">
                <h3 class="card-title">Novo Cadastro</h3>
                <p>Registre um novo equipamento de TI ou gere um relatório.</p>
                <a href="cadastro-equipamento.php" class="btn btn-primary btn-small">Cadastrar Novo Ativo</a>
            </div>
        </div>
    </main>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 CyclePoint. Gerenciamento de Ativos de TI.</p>
        </div>
    </footer>
        <!-- JavaScript para o Gráfico Chart.js -->
   <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dados simulados (que viriam de uma API real)
            const dadosSimulados = {
                labels: ["Monitores", "CPUs", "Impressoras", "Notebooks", "Servidores"],
                em_uso: [150, 90, 45, 120, 20], 
                a_descartar: [15, 8, 3, 10, 2]  
            };

            const dados = dadosSimulados;
            const ctxStatus = document.getElementById('graficoStatusEquipamentos').getContext('2d');
            
            // Configuração do Gráfico de Barras
            new Chart(ctxStatus, {
                type: 'bar',
                data: {
                    labels: dados.labels,
                    datasets: [
                        {
                            label: 'Em Uso (Ativo)',
                            data: dados.em_uso, 
                            backgroundColor: 'rgba(52, 152, 219, 0.9)', // Azul
                            borderColor: 'rgba(41, 128, 185, 1)',
                            borderRadius: 6,
                            borderWidth: 1
                        },
                        {
                            label: 'A Descartar (Pendente)',
                            data: dados.a_descartar, 
                            backgroundColor: 'rgba(231, 76, 60, 0.9)', // Vermelho
                            borderColor: 'rgba(192, 57, 43, 1)',
                            borderRadius: 6,
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Permite que o gráfico use o tamanho do container H-80
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) { label += context.parsed.y + ' Unidades'; }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: 'bold' } },
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Contagem de Unidades',
                                color: '#4b5563'
                            },
                            ticks: {
                                precision: 0,
                                callback: function(value) {
                                    if (value % 1 === 0) { return value; }
                                }
                            },
                            // Mantendo a remoção das linhas horizontais para um visual mais limpo
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>w

</body>
</html>