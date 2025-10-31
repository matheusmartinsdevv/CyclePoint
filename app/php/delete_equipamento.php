<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_POST['id_equipamento'])) {
    $_SESSION['feedback_message'] = "Erro: ID do equipamento não fornecido";
    $_SESSION['feedback_type'] = "error";
    header("Location: ../../dashboard.php");
    exit;
}

$id_equipamento = (int)$_POST['id_equipamento'];

if (isset($_SESSION['id_empresa'])) {
    $id_empresa = $_SESSION['id_empresa'];
} else {
    $_SESSION['feedback_message'] = "Erro: ID da empresa não encontrado na sessão";
    $_SESSION['feedback_type'] = "error";
    header("Location: ../../dashboard.php");
    exit;
}

// Verifica se o equipamento pertence à empresa
$stmt = $conn->prepare("SELECT id_equipamento FROM equipamento WHERE id_equipamento = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_equipamento, $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['feedback_message'] = "Erro: Equipamento não encontrado ou não pertence à sua empresa";
    $_SESSION['feedback_type'] = "error";
    header("Location: ../../dashboard.php");
    exit;
}

// Deleta o equipamento
$stmt = $conn->prepare("DELETE FROM equipamento WHERE id_equipamento = ? AND id_empresa = ?");
$stmt->bind_param("ii", $id_equipamento, $id_empresa);

if ($stmt->execute()) {
    $_SESSION['feedback_message'] = "Equipamento excluído com sucesso";
    $_SESSION['feedback_type'] = "success";
} else {
    $_SESSION['feedback_message'] = "Erro ao excluir equipamento";
    $_SESSION['feedback_type'] = "error";
}

$stmt->close();
$conn->close();

header("Location: ../../dashboard.php");
exit;
?>