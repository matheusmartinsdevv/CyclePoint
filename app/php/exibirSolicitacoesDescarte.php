
<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

$stmt = $conn->prepare("SELECT * FROM solicitacao_descarte WHERE id_empresa = ?");
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    while ($dados_solicitacao = $result->fetch_assoc()) {
        $id_solicitacao_descarte = $dados_solicitacao['id_solicitacao_descarte'];

        // fazer join com outros IDs

        $data_solicitacao = $dados_solicitacao['data_solicitacao'];
        $status_solicitacao = $dados_solicitacao['status_solicitacao'];

        echo '<div class="solicitacao-descarte">';
        echo '<span class="solicitacao-descarte">COLOCAR NUMERO E INFO DA SOLICITACAO</span>';
        echo '<div style="display: flex ;align-items: center;">';
        echo '<button class="btn btn-primary">Ver detalhes</button>';
        echo '</div>';
        echo '</div>';

    };
} else {
    // SE NÃO ENCONTROU DADOS
    echo '<div class="message-info" style="text-align: center;font-size: 13px;">';
    echo '<h2>Nenhuma solicitação de descarte registrada.</h2>';
    echo '</div>';
    
}

$stmt->close();
$conn->close();


