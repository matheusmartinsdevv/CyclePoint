<?php
session_start();
// $id_logado = $_SESSION['id_usuario'];

$nome_logado_display = isset($_SESSION['nome_logado_display']) ? $_SESSION['nome_logado_display'] : 'Visitante';
$role_logado = isset($_SESSION['role']) ? $_SESSION['role'] : 'deslogado';

$role_text = ($role_logado == 'administrador') ? 'Administrador' : 'Usuário Comum';


$dashboard_data = [
    'total_equipamentos' => 55,
    'equipamentos_ativos' => 40,
    'solicitacoes_pendentes' => 3,
    'coletas_futuras' => 2,
    'proxima_coleta_display' => '20/11/2025',
    'grafico_categorias' => [
        ['nome_categoria' => 'Desktops', 'total' => 30],
        ['nome_categoria' => 'Notebooks', 'total' => 15],
        ['nome_categoria' => 'Monitores', 'total' => 8],
        ['nome_categoria' => 'Impressoras', 'total' => 2],
    ]
];
if (!isset($_SESSION['id_empresa']) && !isset($_SESSION['id_usuario'])) {
    header("refresh:0.5;url=/CyclePoint/login.php");
    exit;
}
include 'app/php/get_dashboard_data.php';

$chart_labels = [];
$chart_data = [];
// Percorrer o array de dados do gráfico
foreach ($dashboard_data['grafico_categorias'] as $item) {
    $chart_labels[] = $item['nome_categoria'];
    $chart_data[] = $item['total'];
}

// Converter para JSON para injetar no JavaScript
$js_labels = json_encode($chart_labels);
$js_data = json_encode($chart_data);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CyclePoint</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            <div class="container main-content-dashboard">
                <div class="card-grid">

                    <div class="data-card bg-primary-light">
                        <div class="card-icon"><i class="fas fa-boxes"></i></div>
                        <div class="card-content">
                            <p class="card-metric-value">
                                <?php echo number_format($dashboard_data['total_equipamentos'], 0, ',', '.'); ?></p>
                            <p class="card-metric-label">Equipamentos no Inventário</p>
                        </div>
                    </div>

                    <div class="data-card bg-success-light">
                        <div class="card-icon"><i class="fas fa-desktop"></i></div>
                        <div class="card-content">
                            <p class="card-metric-value">
                                <?php echo number_format($dashboard_data['equipamentos_ativos'], 0, ',', '.'); ?></p>
                            <p class="card-metric-label">Equipamentos Ativos</p>
                        </div>
                    </div>

                    <div class="data-card bg-warning-light">
                        <div class="card-icon"><i class="fas fa-clock"></i></div>
                        <div class="card-content">
                            <p class="card-metric-value">
                                <?php echo number_format($dashboard_data['solicitacoes_pendentes'], 0, ',', '.'); ?></p>
                            <p class="card-metric-label">Aguardando Aceite da Recicladora</p>
                        </div>
                    </div>

                    <div class="data-card bg-info-light">
                        <div class="card-icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="card-content">
                            <p class="card-metric-value">
                                <?php echo number_format($dashboard_data['coletas_futuras'], 0, ',', '.'); ?></p>
                            <p class="card-metric-label">Próximas Coletas Agendadas</p>
                        </div>
                    </div>

                </div>
                <!-- Seção do Gráfico -->
                <div class="bg-surface p-6 sm:p-8 rounded-xl shadow-2xl border border-gray-100">
                    <h2 class="text-2xl font-bold mb-4 flex items-center text-text-primary">
                        <!-- Ícone de Gráfico (classes de cor customizadas) -->
                        <span class="chart-icon w-6 h-6 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 20V10" />
                                <path d="M18 20V4" />
                                <path d="M6 20v-4" />
                            </svg>
                        </span>
                        Status dos Equipamentos por Tipo
                    </h2>

                    <div class="content-section">
                        <h3 class="second-title">Distribuição de Equipamentos por Categoria</h3>
                        <canvas id="inventoryChart" style="max-height: 400px;"></canvas>
                    </div>
                </div>

                <h2 class="section-title">Ações do Sistema</h2>

                <div class="card action-card">
                    <h3 class="card-title">Novo Cadastro</h3>
                    <p>Registre um novo equipamento de TI.</p>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const chartLabels = <?php echo isset($js_labels) ? $js_labels : '[]'; ?>;
            const chartData = <?php echo isset($js_data) ? $js_data : '[]'; ?>;

            const ctx = document.getElementById('inventoryChart');

            if (ctx && chartData.length > 0) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Unidades por Categoria',
                            data: chartData,
                            backgroundColor: [
                                '#3498DB', 
                                '#2ECC71',
                                '#F1C40F', 
                                '#1ABC9C', 
                                '#9B59B6', 
                                '#E74C3C'  
                            ],
                            borderColor: 'rgba(44, 62, 80, 0.7)', 
                            borderWidth: 1.5,
                            borderRadius: 5 
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, 
                        plugins: {
                            legend: {
                                display: false 
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
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
                                stacked: false,
                                ticks: { font: { weight: 'bold' } },
                                grid: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Contagem de Unidades',
                                    color: '#7f8c8d' 
                                },
                                ticks: {
                                    precision: 0, 
                                    callback: function (value) {
                                        if (value % 1 === 0) { return value; }
                                    }
                                },
                                grid: { display: false } 
                            }
                        }
                    }
                });
            }
        });
    </script>

</body>

</html>