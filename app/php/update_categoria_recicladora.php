<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_SESSION['id_recicladora'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Não autorizado"];
    header("Location: ../../itens-que-coleto.php");
    exit;
}

// Espera: id_recicladora_categoria, nome_recicladora_categoria, descricao
if (!isset($_POST['id_recicladora_categoria']) || !isset($_POST['nome_recicladora_categoria'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Dados incompletos"];
    header("Location: ../../itens-que-coleto.php");
    exit;
}

$id_recicladora_categoria = (int)$_POST['id_recicladora_categoria'];
$nome = trim($_POST['nome_recicladora_categoria']);
$descricao = isset($_POST['descricao_recicladora']) ? trim($_POST['descricao_recicladora']) : null;
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

// Atualiza
$stmt = $conn->prepare("UPDATE recicladora_categorias SET nome_recicladora_categoria = ?, descricao = ? WHERE id_recicladora_categoria = ? AND id_recicladora = ?");
$stmt->bind_param("ssii", $nome, $descricao, $id_recicladora_categoria, $id_recicladora);

if ($stmt->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => "Categoria atualizada com sucesso"];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro ao atualizar categoria"];
}

$stmt->close();
$conn->close();

header("Location: ../../itens-que-coleto.php");
exit;
?>