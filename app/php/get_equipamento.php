<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id_empresa'])) {
    echo json_encode(['success' => false, 'message' => 'Não autorizado.']);
    exit;
}

$id_empresa = $_SESSION['id_empresa'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido.']);
    exit;
}

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão.']);
    exit;
}

$stmt = $conn->prepare("SELECT id_equipamento, nome_equipamento, fabricante, modelo, vida_util_meses, status_equipamento, id_empresa FROM equipamento WHERE id_equipamento = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$row = $res->fetch_assoc()) {
    echo json_encode(['success' => false, 'message' => 'Equipamento não encontrado.']);
    $stmt->close(); $conn->close();
    exit;
}

// verifica propriedade
if ((int)$row['id_empresa'] !== (int)$id_empresa) {
    echo json_encode(['success' => false, 'message' => 'Permissão negada.']);
    $stmt->close(); $conn->close();
    exit;
}

// prepara retorno
$data = [
    'id_equipamento' => (int)$row['id_equipamento'],
    'nome_equipamento' => $row['nome_equipamento'],
    'fabricante' => $row['fabricante'],
    'modelo' => $row['modelo'],
    'vida_util_meses' => $row['vida_util_meses'],
    'status_equipamento' => $row['status_equipamento']
];

echo json_encode(['success' => true, 'data' => $data]);

$stmt->close();
$conn->close();

?>
