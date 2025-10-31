<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_POST['id_categoria']) || !isset($_POST['nome_categoria']) || !isset($_POST['descricao'])) {
    $_SESSION['feedback_message'] = "Erro: Todos os campos são obrigatórios";
    $_SESSION['feedback_type'] = "error";
    header("Location: ../../configuracoes.php");
    exit;
}

if (!isset($_SESSION['id_empresa'])) {
    $_SESSION['feedback_message'] = "Erro: ID da empresa não encontrado na sessão";
    $_SESSION['feedback_type'] = "error";
    header("Location: ../../configuracoes.php");
    exit;
}

$id_categoria = (int)$_POST['id_categoria'];
$nome_categoria = trim($_POST['nome_categoria']);
$descricao = trim($_POST['descricao']);
$id_empresa = $_SESSION['id_empresa'];

// Verifica se a categoria pertence à empresa
$stmt = $conn->prepare("SELECT id_categoria FROM categoria WHERE id_categoria = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_categoria, $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['feedback_message'] = "Erro: Categoria não encontrada ou não pertence à sua empresa";
    $_SESSION['feedback_type'] = "error";
    header("Location: ../../configuracoes.php");
    exit;
}

// Atualiza a categoria
$stmt = $conn->prepare("UPDATE categoria SET nome_categoria = ?, descricao = ? WHERE id_categoria = ? AND id_empresa = ?");
$stmt->bind_param("ssii", $nome_categoria, $descricao, $id_categoria, $id_empresa);

if ($stmt->execute()) {
    $_SESSION['feedback_message'] = "Categoria atualizada com sucesso";
    $_SESSION['feedback_type'] = "success";
} else {
    $_SESSION['feedback_message'] = "Erro ao atualizar categoria";
    $_SESSION['feedback_type'] = "error";
}

$stmt->close();
$conn->close();

header("Location: ../../configuracoes.php");
exit;
?>