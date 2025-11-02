<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_SESSION['id_recicladora'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Não autorizado"];
    header("Location: ../../itens-que-coleto.php");
    exit;
}

if (!isset($_POST['id_recicladora_categoria'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: ID não fornecido"];
    header("Location: ../../itens-que-coleto.php");
    exit;
}

$id_recicladora_categoria = (int)$_POST['id_recicladora_categoria'];
$id_recicladora = $_SESSION['id_recicladora'];

// Verifica se a categoria pertence à recicladora
$stmt = $conn->prepare("SELECT id_recicladora_categoria FROM recicladora_categorias WHERE id_recicladora_categoria = ? AND id_recicladora = ?");
$stmt->bind_param("ii", $id_recicladora_categoria, $id_recicladora);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Categoria não encontrada ou não pertence à sua recicladora"];
    header("Location: ../../itens-que-coleto.php");
    exit;
}

// Deleta a categoria
$stmt = $conn->prepare("DELETE FROM recicladora_categorias WHERE id_recicladora_categoria = ? AND id_recicladora = ?");
$stmt->bind_param("ii", $id_recicladora_categoria, $id_recicladora);

if ($stmt->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => "Categoria removida com sucesso"];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro ao remover categoria"];
}

$stmt->close();
$conn->close();

header("Location: ../../itens-que-coleto.php");
exit;
?>