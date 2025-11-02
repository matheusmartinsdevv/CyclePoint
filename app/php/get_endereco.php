<?php
session_start();
header('Content-Type: application/json');

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID do endereço não fornecido']);
    exit;
}

if (!isset($_SESSION['id_empresa'])) {
    echo json_encode(['success' => false, 'message' => 'ID da empresa não encontrado na sessão']);
    exit;
}

$id_endereco = (int)$_GET['id'];
$id_empresa = $_SESSION['id_empresa'];

$stmt = $conn->prepare("SELECT id_endereco_empresa, logradouro, numero, bairro, cidade, estado, pais FROM endereco_empresa WHERE id_endereco_empresa = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_endereco, $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Endereço não encontrado']);
    exit;
}

$end = $result->fetch_assoc();

echo json_encode(['success' => true, 'data' => $end]);

$stmt->close();
$conn->close();
?>