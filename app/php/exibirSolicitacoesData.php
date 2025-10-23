
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

        $data_solicitacao = date('d/m/Y', strtotime($dados_solicitacao['data_solicitacao']));
        $status_solicitacao = $dados_solicitacao['status_solicitacao'];
        $data_coleta = date('d/m/Y', strtotime($dados_solicitacao['data_coleta']));


        if ($status_solicitacao === 'Aceito' && $data_coleta === null) {

            echo '<div class="solicitacao-descarte">';
            echo '<span id="id_solicitacao_descarte">'.$id_solicitacao_descarte.'</span>';
            echo '<h3 class="solicitacao-descarte equipamento">Equipamento:'. $nome_equipamento  .'</h3>';
            echo '<span>Modelo: '.$modelo.'</span>';
            echo '<h4 class="solicitacao-descarte recicladora">Empresa solicitante: '. $nome_empresa  .'</h4>';
            echo '<span>Data da solicitação: '.$data_solicitacao.'</span><br>';
            // echo '<div style="display: flex ;align-items: center;">';
            // echo '<button class="btn btn-primary">Ver detalhes</button>';
            // echo '</div>';
            // echo '<br><div class="botoes-solicitacao" style="display: flex; justify-content: flex-end;">';
            // echo '<button data-id="'.$id_solicitacao_descarte.'" data-acao="Aceito" class="btn btn-primary aceitar-solicitacao">Aceitar</button>';
            // echo '<button data-id="'.$id_solicitacao_descarte.'" data-acao="Recusado" class="btn btn-primary recusar-solicitacao">Recusar</button>';
            echo '<form action="app/php/processar_data.php" method="POST" class="form-content wide-form dataColeta">';
            echo '<div class="input-group">
                        <input type="hidden" name="id_solicitacao_descarte" value="' . htmlspecialchars($id_solicitacao_descarte) . '">                
                        <label for="dataColeta">Data da Coleta</label>
                        <div style="display:flex;">
                            <input type="date" id="dataColeta" name="dataColeta" required>
                            <button type="submit" class="btn btn-primary">Agendar coleta</button>
                        </div>

                    </div>';
            echo '</form>';
            // echo '</div>';
            echo '</div><hr>';

            $count += 1;

        };

        

    };

    if ($count === 0) {
            echo '<h4 style="text-align: center">Nenhum agendamento pendente.</h4>';

        };

} else {
    // SE NÃO ENCONTROU DADOS
    echo '<div class="message-info" style="text-align: center;font-size: 13px;">';
    echo '<h2>Nenhuma solicitação de descarte registrada.</h2>';
    echo '</div>';
    
}

$stmt->close();
$conn->close();


