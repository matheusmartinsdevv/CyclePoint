<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_POST['id_usuario'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: ID do usuário não fornecido"];
    header("Location: ../../gerenciar-usuarios.php");
    exit;
}

$id_usuario = (int)$_POST['id_usuario'];

if (!isset($_SESSION['id_empresa'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: ID da empresa não encontrado na sessão"];
    header("Location: ../../gerenciar-usuarios.php");
    exit;
}

$id_empresa = $_SESSION['id_empresa'];

// Não permitir excluir o próprio usuário logado
if (isset($_SESSION['id_usuario']) && (int)$_SESSION['id_usuario'] === $id_usuario) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Não é possível excluir o usuário atualmente logado"];
    header("Location: ../../gerenciar-usuarios.php");
    exit;
}

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

// Deleta o usuário
$stmt = $conn->prepare("DELETE FROM usuario WHERE id_usuario = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_usuario, $id_empresa);

if ($stmt->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => "Usuário excluído com sucesso"];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro ao excluir usuário"];
}

$stmt->close();
$conn->close();

header("Location: ../../gerenciar-usuarios.php");
exit;
?>