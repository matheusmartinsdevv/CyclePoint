<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Status dos Equipamentos (Exemplo)</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    
    <style>
        /* Estilos básicos para simular a aparência do dashboard */
        body { font-family: 'Roboto', sans-serif; background-color: #ecf0f1; padding: 20px; margin: 0; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        h1, h2 { color: #2c3e50; margin-bottom: 20px; }
        h1 { font-size: 2.2em; }
        h2 { font-size: 1.6em; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-top: 40px;}
        
        /* Estilos dos Cards (KPIs) */
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .data-card { padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card-title { font-size: 14px; color: #7f8c8d; margin-bottom: 5px; }
        .card-value { font-size: 36px; font-weight: 700; margin-bottom: 5px; }
        .card-detail { font-size: 12px; color: #95a5a6; }

        /* Estilos de Cor para os valores dos Cards */
        .value-danger { color: #e74c3c; }
        .value-warning { color: #f39c12; }
        .value-success { color: #2ecc71; }
        .value-default { color: #3498db; }

        /* Estilo da Seção do Gráfico */
        .chart-section { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-top: 30px; }
        .chart-title-icon { display: inline-block; vertical-align: middle; margin-right: 10px; color: #3498db; }
        .chart-title-icon svg { width: 24px; height: 24px; } /* Ajuste o tamanho do SVG se necessário */

        /* Ações do Sistema */
        .action-card { padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-top: 20px; text-align: center;}
        .action-card .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .action-card .btn:hover { background-color: #2980b9; }

        /* Responsividade básica */
        @media (max-width: 768px) {
            .card-grid { grid-template-columns: 1fr; }
            .container { padding: 10px; }
            .chart-section { padding: 15px; }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Dashboard - CyclePoint</h1>
            
        <div class="card-grid">
            <div class="card data-card">
                <div class="card-title">Equipamentos Cadastrados</div>
                <div class="card-value value-default">505</div> 
                <p class="card-detail">Total de ativos gerenciados.</p>
            </div>

            <div class="card data-card">
                <div class="card-title">Descarte Pendente</div>
                <div class="card-value value-danger">35</div>
                <p class="card-detail">Ativos com vida útil expirada.</p>
            </div>

            <div class="card data-card">
                <div class="card-title">Fim de Vida Útil (30 dias)</div>
                <div class="card-value value-warning">12</div>
                <p class="card-detail">Equipamentos a serem descartados em breve.</p>
            </div>

            <div class="card data-card">
                <div class="card-title">Coletas Agendadas</div>
                <div class="card-value value-success">2</div>
                <p class="card-detail">Próximo agendamento: 25/11/2025</p>
            </div>
        </div>

        
        <div class="chart-section">
            <h2 style="margin-top: 0;">
                <span class="chart-title-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11V3H8v8H2v10h20V11h-6zm-6 0V5h4v6h-4zm-4 8v-6h2v6H6zm12 0h-2v-6h2v6z"/></svg>
                </span>
                Status dos Equipamentos por Tipo
            </h2>
            
            <div style="width: 90%; margin: 20px auto;">
                <canvas id="graficoStatusEquipamentos"></canvas>
            </div>
        </div>

        
        <h2 class="section-title">Ações do Sistema</h2>
        <div class="card action-card">
            <h3 class="card-title">Novo Cadastro</h3>
            <p>Registre um novo equipamento de TI ou gere um relatório.</p>
            <a href="#" class="btn btn-primary btn-small">Cadastrar Novo Ativo</a>
        </div>

    </div>
 <script>
        // Simulação de dados (estes viriam do seu api_status_equipamentos.php)
        const dadosSimulados = {
            labels: ["Monitores", "CPUs", "Impressoras", "Notebooks", "Servidores"],
            em_uso: [150, 90, 45, 120, 20], 
            a_descartar: [15, 8, 3, 10, 2]   
        };

        // Script para o Gráfico de Status dos Equipamentos
        // Na sua aplicação real, este bloco estaria dentro de um fetch().then() 
        // que chamaria a API 'api_status_equipamentos.php'.
        
        // Simulação do fetch bem-sucedido:
        const dados = dadosSimulados;

        const ctxStatus = document.getElementById('graficoStatusEquipamentos').getContext('2d');
        
        new Chart(ctxStatus, {
            type: 'bar',
            data: {
                labels: dados.labels,
                datasets: [
                    {
                        label: 'Em Uso (Ativo)',
                        data: dados.em_uso, 
                        backgroundColor: 'rgba(52, 152, 219, 0.9)', // Azul (Ativo)
                        borderColor: 'rgba(41, 128, 185, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'A Descartar (Pendente)',
                        data: dados.a_descartar, // Valores para A Descartar
                        backgroundColor: 'rgba(231, 76, 60, 0.9)', // Vermelho (Perigo)
                        borderColor: 'rgba(192, 57, 43, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
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
                        stacked: false, 
                        ticks: { font: { weight: 'bold' } },
                        // ***** REMOVE AS LINHAS VERTICAIS *****
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Contagem de Unidades'
                        },
                        ticks: {
                            callback: function(value) {
                                if (value % 1 === 0) {
                                    return value;
                                }
                            }
                        },
                        // ***** REMOVE AS LINHAS HORIZONTAIS *****
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>