<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_SESSION['id_recicladora'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID não fornecido']);
    exit;
}

$id = (int)$_GET['id'];
$id_recicladora = $_SESSION['id_recicladora'];

$stmt = $conn->prepare("SELECT id_recicladora_categoria, nome_recicladora_categoria, descricao 
                       FROM recicladora_categorias 
                       WHERE id_recicladora_categoria = ? AND id_recicladora = ?");
$stmt->bind_param("ii", $id, $id_recicladora);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Categoria não encontrada']);
    exit;
}

$data = $result->fetch_assoc();
echo json_encode($data);

$stmt->close();
$conn->close();
?>