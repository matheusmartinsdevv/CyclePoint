<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_POST['id_endereco'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: ID do endereço não fornecido"];
    header("Location: ../../gerenciar-enderecos.php");
    exit;
}

$id_endereco = (int)$_POST['id_endereco'];

if (!isset($_SESSION['id_empresa'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: ID da empresa não encontrado na sessão"];
    header("Location: ../../gerenciar-enderecos.php");
    exit;
}

$id_empresa = $_SESSION['id_empresa'];

// Verifica se o endereço pertence à empresa
$stmt = $conn->prepare("SELECT id_endereco_empresa FROM endereco_empresa WHERE id_endereco_empresa = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_endereco, $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Endereço não encontrado ou não pertence à sua empresa"];
    header("Location: ../../gerenciar-enderecos.php");
    exit;
}

// Deleta o endereço
$stmt = $conn->prepare("DELETE FROM endereco_empresa WHERE id_endereco_empresa = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_endereco, $id_empresa);

if ($stmt->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => "Endereço excluído com sucesso"];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro ao excluir endereço"];
}

$stmt->close();
$conn->close();

header("Location: ../../gerenciar-enderecos.php");
exit;
?>