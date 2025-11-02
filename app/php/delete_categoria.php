<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_POST['id_categoria'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: ID da categoria não fornecido"];
    header("Location: ../../configuracoes.php");
    exit;
}

$id_categoria = (int)$_POST['id_categoria'];

if (!isset($_SESSION['id_empresa'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: ID da empresa não encontrado na sessão"];
    header("Location: ../../configuracoes.php");
    exit;
}

$id_empresa = $_SESSION['id_empresa'];

// Verifica se a categoria pertence à empresa
$stmt = $conn->prepare("SELECT id_categoria FROM categoria WHERE id_categoria = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_categoria, $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Categoria não encontrada ou não pertence à sua empresa"];
    header("Location: ../../configuracoes.php");
    exit;
}

// Deleta a categoria
$stmt = $conn->prepare("DELETE FROM categoria WHERE id_categoria = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_categoria, $id_empresa);

if ($stmt->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => "Categoria excluída com sucesso"];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro ao excluir categoria"];
}

$stmt->close();
$conn->close();

header("Location: ../../configuracoes.php");
exit;
?>