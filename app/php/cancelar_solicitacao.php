<?php
session_start();
header('Content-Type: application/json');

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
    exit;
}

$id_solicitacao = isset($_POST['id_solicitacao_descarte']) ? (int)$_POST['id_solicitacao_descarte'] : 0;
if ($id_solicitacao <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de solicitação inválido.']);
    exit;
}

// Verifica se o usuário logado é o solicitante (empresa ou usuário)
$is_owner = false;
if (isset($_SESSION['id_empresa'])) {
    $id_empresa = $_SESSION['id_empresa'];
    // checa se a solicitação pertence à empresa
    $stmt = $conn->prepare("SELECT id_equipamento, id_empresa, status_solicitacao FROM solicitacao_descarte WHERE id_solicitacao_descarte = ?");
    $stmt->bind_param("i", $id_solicitacao);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if ((int)$row['id_empresa'] === (int)$id_empresa) {
            $is_owner = true;
            $id_equipamento = (int)$row['id_equipamento'];
            $status_atual = $row['status_solicitacao'];
        }
    }
    $stmt->close();
} elseif (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $stmt = $conn->prepare("SELECT id_equipamento, id_usuario, status_solicitacao FROM solicitacao_descarte WHERE id_solicitacao_descarte = ?");
    $stmt->bind_param("i", $id_solicitacao);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if ((int)$row['id_usuario'] === (int)$id_usuario) {
            $is_owner = true;
            $id_equipamento = (int)$row['id_equipamento'];
            $status_atual = $row['status_solicitacao'];
        }
    }
    $stmt->close();
}

if (!$is_owner) {
    echo json_encode(['success' => false, 'message' => 'Permissão negada.']);
    $conn->close();
    exit;
}

// Só permite cancelar se estiver pendente
if (strtolower($status_atual) !== 'pendente') {
    echo json_encode(['success' => false, 'message' => 'Somente solicitações pendentes podem ser canceladas.']);
    $conn->close();
    exit;
}

// Inicia transação para atualizar solicitacao e equipamento
$conn->begin_transaction();
try {
    // Atualiza status da solicitação
    $novo_status = 'Cancelado';
    $stmt_up = $conn->prepare("UPDATE solicitacao_descarte SET status_solicitacao = ? WHERE id_solicitacao_descarte = ?");
    $stmt_up->bind_param("si", $novo_status, $id_solicitacao);
    if (!$stmt_up->execute()) throw new Exception($stmt_up->error);
    $stmt_up->close();

    // Restaura o equipamento para o estado 'ativo' (NULL no campo status_equipamento)
    $stmt_eq = $conn->prepare("UPDATE equipamento SET status_equipamento = NULL WHERE id_equipamento = ?");
    $stmt_eq->bind_param("i", $id_equipamento);
    if (!$stmt_eq->execute()) throw new Exception($stmt_eq->error);
    $stmt_eq->close();

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Solicitação cancelada com sucesso.']);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Erro ao cancelar solicitação: '.$e->getMessage()]);
}

$conn->close();

?>
