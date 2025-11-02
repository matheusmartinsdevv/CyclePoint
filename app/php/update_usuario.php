<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_POST['id_usuario']) || !isset($_POST['nome']) || !isset($_POST['email']) || !isset($_POST['cargo'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Todos os campos são obrigatórios"];
    header("Location: ../../gerenciar-usuarios.php");
    exit;
}

if (!isset($_SESSION['id_empresa'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: ID da empresa não encontrado na sessão"];
    header("Location: ../../gerenciar-usuarios.php");
    exit;
}

$id_usuario = (int)$_POST['id_usuario'];
$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$cargo = trim($_POST['cargo']);
$id_empresa = $_SESSION['id_empresa'];

// Verifica se o usuário pertence à empresa
$stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_usuario, $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Usuário não encontrado ou não pertence à sua empresa"];
    header("Location: ../../gerenciar-usuarios.php");
    exit;
}

// Atualiza o usuário (não altera senha aqui)
$stmt = $conn->prepare("UPDATE usuario SET nome = ?, email = ?, cargo = ? WHERE id_usuario = ? AND id_empresa = ?");
$stmt->bind_param("sssii", $nome, $email, $cargo, $id_usuario, $id_empresa);

if ($stmt->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => "Usuário atualizado com sucesso"];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro ao atualizar usuário"];
}

$stmt->close();
$conn->close();

header("Location: ../../gerenciar-usuarios.php");
exit;
?>