<?php
session_start();
header('Content-Type: application/json');

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID da categoria não fornecido']);
    exit;
}

if (!isset($_SESSION['id_empresa'])) {
    echo json_encode(['success' => false, 'message' => 'ID da empresa não encontrado na sessão']);
    exit;
}

$id_categoria = (int)$_GET['id'];
$id_empresa = $_SESSION['id_empresa'];

$stmt = $conn->prepare("SELECT id_categoria, nome_categoria, descricao FROM categoria WHERE id_categoria = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_categoria, $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Categoria não encontrada']);
    exit;
}

$categoria = $result->fetch_assoc();
echo json_encode(['success' => true, 'data' => $categoria]);

$stmt->close();
$conn->close();
?>