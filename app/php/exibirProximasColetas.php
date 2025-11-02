
<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_recicladora'])) {
        $id_recicladora = $_SESSION['id_recicladora'];
    } else {
        die("Erro: ID da recicladora não encontrado na sessão."); 
    }

$stmt = $conn->prepare("
    SELECT
        s.id_solicitacao_descarte, 
        s.data_solicitacao, 
        s.status_solicitacao, 
        s.data_coleta,
        e.nome_equipamento, 
        e.modelo, 
        m.razao_social AS nome_empresa
    FROM
        solicitacao_descarte s
    INNER JOIN
        equipamento e ON s.id_equipamento = e.id_equipamento
    INNER JOIN
        empresa m ON s.id_empresa = m.id_empresa
    WHERE
        s.id_recicladora = ?
    ORDER BY
        s.id_solicitacao_descarte ASC
");
$stmt->bind_param("i", $id_recicladora);
$stmt->execute();
$result = $stmt->get_result();

$count = 0;

if ($result->num_rows > 0) {

    while ($dados_solicitacao = $result->fetch_assoc()) {
        $id_solicitacao_descarte = $dados_solicitacao['id_solicitacao_descarte'];

        $nome_equipamento = $dados_solicitacao['nome_equipamento'];
        $modelo = $dados_solicitacao['modelo'];
        $nome_empresa = $dados_solicitacao['nome_empresa'];

        $data_solicitacao_display = date('d/m/Y', strtotime($dados_solicitacao['data_solicitacao']));
        $status_solicitacao = $dados_solicitacao['status_solicitacao'];

        $data_coleta_comparacao = $dados_solicitacao['data_coleta']; 
    
        $data_coleta_display = date('d/m/Y', strtotime($dados_solicitacao['data_coleta']));

        $data_atual_comparacao = date('Y-m-d');


        if ($status_solicitacao === 'Aceito' && $data_coleta_comparacao >= $data_atual_comparacao) {

            echo '<div class="solicitacao-descarte">';
            echo '<span id="id_solicitacao_descarte">'.$id_solicitacao_descarte.'</span>';
            echo '<h3 class="solicitacao-descarte equipamento">Equipamento:'. $nome_equipamento  .'</h3>';
            echo '<span>Modelo: '.$modelo.'</span>';
            echo '<h4 class="solicitacao-descarte recicladora">Empresa solicitante: '. $nome_empresa  .'</h4>';
            echo '<span>Data da solicitação: '.$data_solicitacao_display.'</span>';
            echo '<span style="float: right;">Data prevista da coleta: '.$data_coleta_display.'</span><br>';
            echo '</div><hr>';

            $count += 1;

        };

        

    };

    if ($count === 0) {
            echo '<h4 style="text-align: center">Nenhuma coleta agendada.</h4>';

        };

} else {
    // SE NÃO ENCONTROU DADOS
    echo '<div class="message-info" style="text-align: center;font-size: 13px;">';
    echo '<h2>Nenhuma coleta agendada.</h2>';
    echo '</div>';
    
}

$stmt->close();
$conn->close();


