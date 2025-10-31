<?php
session_start();
// O script deve responder em JSON
header('Content-Type: application/json');

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// Verifica a conexão
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados.']);
    exit;
}

// Verifica se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Valida os dados recebidos do JavaScript/Fetch
    $acao = isset($_POST['acao']) ? $_POST['acao'] : ''; 
    $id_solicitacao_descarte = isset($_POST['id_solicitacao_descarte']) ? (int)$_POST['id_solicitacao_descarte'] : 0;
    
    // A variável $acao virá do JavaScript como 'Aceito' ou 'Recusado', que é o status que você quer no banco.
    if ($acao !== 'Aceito' && $acao !== 'Recusado' || $id_solicitacao_descarte <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos recebidos.']);
        $conn->close();
        exit;
    }

    // Vamos buscar o id_equipamento e id_empresa associados para atualizar status e notificar a empresa se necessário
    $stmt_get = $conn->prepare("SELECT id_equipamento, id_empresa FROM solicitacao_descarte WHERE id_solicitacao_descarte = ?");
    $stmt_get->bind_param("i", $id_solicitacao_descarte);
    $stmt_get->execute();
    $res_get = $stmt_get->get_result();
    $id_equipamento = null;
    $id_empresa_destinataria = null;
    if ($rowg = $res_get->fetch_assoc()) {
        $id_equipamento = (int)$rowg['id_equipamento'];
        $id_empresa_destinataria = isset($rowg['id_empresa']) ? (int)$rowg['id_empresa'] : null;
    }
    $stmt_get->close();

    // Inicia transação para manter consistência entre solicitacao_descarte e equipamento
    $conn->begin_transaction();
    try {
        // Prepara a query de UPDATE da solicitação
        $stmt = $conn->prepare("UPDATE solicitacao_descarte SET status_solicitacao = ? WHERE id_solicitacao_descarte = ?;");
        $stmt->bind_param("si", $acao, $id_solicitacao_descarte);

        if (!$stmt->execute()) {
            throw new Exception('Erro ao atualizar solicitação: ' . $stmt->error);
        }
        $stmt->close();

        // Atualiza o status do equipamento conforme a ação
        if ($id_equipamento) {
            if ($acao === 'Aceito') {
                // Equipamento está agendado para coleta pela recicladora
                $novo_status_equip = 'agendado';
            } else if ($acao === 'Recusado') {
                // Recusado: devolve o equipamento ao estado ativo (NULL)
                $novo_status_equip = null;
            } else {
                $novo_status_equip = null;
            }

            if ($novo_status_equip === null) {
                $stmt_eq = $conn->prepare("UPDATE equipamento SET status_equipamento = NULL WHERE id_equipamento = ?");
                $stmt_eq->bind_param("i", $id_equipamento);
            } else {
                $stmt_eq = $conn->prepare("UPDATE equipamento SET status_equipamento = ? WHERE id_equipamento = ?");
                $stmt_eq->bind_param("si", $novo_status_equip, $id_equipamento);
            }

            if (!$stmt_eq->execute()) {
                throw new Exception('Erro ao atualizar equipamento: ' . $stmt_eq->error);
            }
            $stmt_eq->close();
        }

        // Se a ação for 'Recusado', notifica a empresa solicitante sobre a recusa
        if ($acao === 'Recusado' && $id_empresa_destinataria) {
            // Monta mensagem (usar nome da recicladora se disponível na sessão)
            $nome_recicladora = isset($_SESSION['nome_logado_display']) ? $_SESSION['nome_logado_display'] : 'a recicladora';
            $mensagem_notif = "Sua solicitação (ID $id_solicitacao_descarte) foi recusada por $nome_recicladora.";
            $link_notif = "http://localhost/CyclePoint/meus-descartes.php";

            $stmt_notif = $conn->prepare("INSERT INTO notificacoes (id_entidade, tipo_entidade, id_solicitacao_descarte, mensagem, link) VALUES (?, 'empresa', ?, ?, ?)");
            if ($stmt_notif) {
                $stmt_notif->bind_param("iiss", $id_empresa_destinataria, $id_solicitacao_descarte, $mensagem_notif, $link_notif);
                $stmt_notif->execute();
                $stmt_notif->close();
            }
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => "Solicitação $id_solicitacao_descarte atualizada para $acao."]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    $conn->close();

} else {
    // Método não permitido
    echo json_encode(['success' => false, 'message' => 'Método de requisição não permitido.']);
}
?>