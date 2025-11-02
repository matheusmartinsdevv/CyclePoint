<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_SESSION['id_recicladora'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Não autorizado"];
    header("Location: ../../itens-que-coleto.php");
    exit;
}

if (!isset($_POST['id_item_coletado'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: ID não fornecido"];
    header("Location: ../../itens-que-coleto.php");
    exit;
}

$id_item_coletado = (int)$_POST['id_item_coletado'];
$id_recicladora = $_SESSION['id_recicladora'];

// Verifica se o item pertence à recicladora
$stmt = $conn->prepare("SELECT id_item_coletado FROM item_coletado WHERE id_item_coletado = ? AND id_recicladora = ?");
$stmt->bind_param("ii", $id_item_coletado, $id_recicladora);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Item não encontrado ou não pertence à sua recicladora"];
    header("Location: ../../itens-que-coleto.php");
    exit;
}

// Deleta o item
$stmt = $conn->prepare("DELETE FROM item_coletado WHERE id_item_coletado = ? AND id_recicladora = ?");
$stmt->bind_param("ii", $id_item_coletado, $id_recicladora);

if ($stmt->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => "Item removido com sucesso"];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro ao remover item"];
}

$stmt->close();
$conn->close();

header("Location: ../../itens-que-coleto.php");
exit;
?>