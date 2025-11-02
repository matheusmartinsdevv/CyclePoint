<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

$required = ['id_endereco','logradouro','numero','bairro','cidade','estado','pais'];
foreach ($required as $f) {
    if (!isset($_POST[$f])) {
        $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: Todos os campos são obrigatórios"];
        header("Location: ../../gerenciar-enderecos.php");
        exit;
    }
}

if (!isset($_SESSION['id_empresa'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro: ID da empresa não encontrado na sessão"];
    header("Location: ../../gerenciar-enderecos.php");
    exit;
}

$id_endereco = (int)$_POST['id_endereco'];
$logradouro = trim($_POST['logradouro']);
$numero = (int)$_POST['numero'];
$bairro = trim($_POST['bairro']);
$cidade = trim($_POST['cidade']);
$estado = trim($_POST['estado']);
$pais = trim($_POST['pais']);
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

// Atualiza
$stmt = $conn->prepare("UPDATE endereco_empresa SET logradouro = ?, numero = ?, bairro = ?, cidade = ?, estado = ?, pais = ? WHERE id_endereco_empresa = ? AND id_empresa = ?");
// Tipagem: logradouro(s), numero(i), bairro(s), cidade(s), estado(s), pais(s), id_endereco(i), id_empresa(i)
$stmt->bind_param("sissssii", $logradouro, $numero, $bairro, $cidade, $estado, $pais, $id_endereco, $id_empresa);

if ($stmt->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => "Endereço atualizado com sucesso"];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => "Erro ao atualizar endereço"];
}

$stmt->close();
$conn->close();

header("Location: ../../gerenciar-enderecos.php");
exit;
?>