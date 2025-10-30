<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data_coleta = $_POST['dataColeta']; 
    $id_solicitacao_descarte = $_POST['id_solicitacao_descarte'];
    

    $stmt = $conn->prepare("UPDATE solicitacao_descarte SET data_coleta = ? WHERE id_solicitacao_descarte = ?;");

    $stmt->bind_param("si", $data_coleta, $id_solicitacao_descarte);

    if ($stmt->execute()) {

        $id_empresa_destinataria = null;
        $stmt_get_empresa = $conn->prepare("SELECT id_empresa FROM solicitacao_descarte WHERE id_solicitacao_descarte = ?");
        $stmt_get_empresa->bind_param("i", $id_solicitacao_descarte);
        $stmt_get_empresa->execute();
        $result_empresa = $stmt_get_empresa->get_result();
        
        if ($row = $result_empresa->fetch_assoc()) {
            $id_empresa_destinataria = $row['id_empresa'];
        }
        $stmt_get_empresa->close();

        if ($id_empresa_destinataria) {
            
            $data_coleta_formatada = date('d/m/Y', strtotime($data_coleta));
            
            $mensagem = "Sua coleta (ID $id_solicitacao_descarte) foi agendada para: $data_coleta_formatada.";
            $link = "/CyclePoint/meus-descartes.php"; // Link para o detalhe do descarte
            
            $stmt_notif = $conn->prepare("INSERT INTO notificacoes (id_entidade, tipo_entidade, id_solicitacao_descarte, mensagem, link) VALUES (?, 'empresa', ?, ?, ?)");
            $stmt_notif->bind_param("iiss", $id_empresa_destinataria, $id_solicitacao_descarte, $mensagem, $link);
            
            if (!$stmt_notif->execute()) {
                error_log("Erro ao criar notificação de agendamento: " . $conn->error);
            }
            $stmt_notif->close();
            
        } else {
            error_log("Aviso: ID da empresa destinatária não encontrado para a solicitação $id_solicitacao_descarte.");
        }

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => '✅ Agendamento da coleta realizado com sucesso'
        ];
        $stmt->close();


    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => '❌ Erro ao agendar a coleta. Tente novamente.'
        ];

    }

    header("Location: ../../solicitacoesRecicladora.php"); 

    

};

?>

