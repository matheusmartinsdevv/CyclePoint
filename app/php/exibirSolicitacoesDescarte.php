
<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

$stmt = $conn->prepare("
    SELECT 
        s.id_solicitacao_descarte, 
        s.data_solicitacao, 
        s.status_solicitacao,
        s.data_coleta, 
        e.nome_equipamento, 
        e.modelo, 
        r.razao_social AS nome_recicladora
    FROM 
        solicitacao_descarte s
    INNER JOIN 
        equipamento e ON s.id_equipamento = e.id_equipamento
    INNER JOIN 
        recicladora r ON s.id_recicladora = r.id_recicladora
    WHERE 
        s.id_empresa = ?
    ORDER BY
        s.id_solicitacao_descarte ASC

");
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    while ($dados_solicitacao = $result->fetch_assoc()) {
        $id_solicitacao_descarte = $dados_solicitacao['id_solicitacao_descarte'];

        $nome_equipamento = $dados_solicitacao['nome_equipamento'];
        $modelo = $dados_solicitacao['modelo'];
        $nome_recicladora = $dados_solicitacao['nome_recicladora'];

        $data_solicitacao = date('d/m/Y', strtotime($dados_solicitacao['data_solicitacao']));
        $status_solicitacao = $dados_solicitacao['status_solicitacao'];
        $raw_data_coleta = $dados_solicitacao['data_coleta'];

        if (empty($raw_data_coleta)) {
            $data_coleta_display = 'Data da coleta ainda não definida';
        } else {
            $data_coleta_display = 'Data da coleta: '.date('d/m/Y', strtotime($raw_data_coleta)).'';
        }


        echo '<div class="solicitacao-descarte">';
        echo '<span>ID '.$id_solicitacao_descarte.'</span>';
        echo '<h3 class="solicitacao-descarte equipamento">Equipamento:'. $nome_equipamento  .'</h3>';
        echo '<span>Modelo: '.$modelo.'</span>';
        echo '<h4 class="solicitacao-descarte recicladora">Recicladora:'. $nome_recicladora  .'</h4>';
        echo '<span>Data da solicitação: '.$data_solicitacao.' |</span>';
        echo '<span> Status: '.$status_solicitacao.'</span><br>';
        echo '<span>'.$data_coleta_display.'</span>';
        echo '</div><hr>';

    };
} else {
    // SE NÃO ENCONTROU DADOS
    echo '<div class="message-info" style="text-align: center;font-size: 13px;">';
    echo '<h2>Nenhuma solicitação de descarte registrada.</h2>';
    echo '</div>';
    
}

$stmt->close();
$conn->close();


