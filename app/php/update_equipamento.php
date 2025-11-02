<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
    exit;
}

if (!isset($_SESSION['id_empresa'])) {
    echo json_encode(['success' => false, 'message' => 'Não autorizado.']);
    exit;
}

$id_empresa = $_SESSION['id_empresa'];

$id = isset($_POST['id_equipamento']) ? (int)$_POST['id_equipamento'] : 0;
$nome = isset($_POST['nome_equipamento']) ? trim($_POST['nome_equipamento']) : '';
$fabricante = isset($_POST['fabricante']) ? trim($_POST['fabricante']) : '';
$modelo = isset($_POST['modelo']) ? trim($_POST['modelo']) : '';
$vida = isset($_POST['vida_util_meses']) && is_numeric($_POST['vida_util_meses']) ? (int)$_POST['vida_util_meses'] : null;

if ($id <= 0 || $nome === '') {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    exit;
}

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão.']);
    exit;
}

// Verifica propriedade do equipamento
$stmt_check = $conn->prepare("SELECT id_empresa FROM equipamento WHERE id_equipamento = ? LIMIT 1");
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$res_check = $stmt_check->get_result();
if (!$rowc = $res_check->fetch_assoc()) {
    echo json_encode(['success' => false, 'message' => 'Equipamento não encontrado.']);
    $stmt_check->close(); $conn->close();
    exit;
}
if ((int)$rowc['id_empresa'] !== (int)$id_empresa) {
    echo json_encode(['success' => false, 'message' => 'Permissão negada.']);
    $stmt_check->close(); $conn->close();
    exit;
}
$stmt_check->close();

// Atualiza campos permitidos
$stmt_up = $conn->prepare("UPDATE equipamento SET nome_equipamento = ?, fabricante = ?, modelo = ?, vida_util_meses = ? WHERE id_equipamento = ?");
$stmt_up->bind_param("sssii", $nome, $fabricante, $modelo, $vida, $id);


if ($stmt_up->execute()) {
    $_SESSION['message'] = [
        'type' => 'success',
        'text' => '✅ Equipamento atualizado com sucesso.'
    ];
    echo json_encode(['success' => true, 'message' => 'Equipamento atualizado.']);
} else {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => '❌ Erro ao atualizar equipamento: ' . $stmt_up->error
    ];
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar: ' . $stmt_up->error]);
}

$stmt_up->close();
$conn->close();

?>
