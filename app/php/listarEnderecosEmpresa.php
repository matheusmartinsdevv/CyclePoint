<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

$stmt = $conn->prepare("SELECT logradouro, numero, cidade FROM endereco_empresa WHERE id_empresa=".$id_empresa);
$stmt->execute();
$result = $stmt->get_result();

while ($dados_endereco_empresa = $result->fetch_assoc()) {
    $logradouro = $dados_endereco_empresa['logradouro'];
    $numero = $dados_endereco_empresa['numero'];
    $cidade = $dados_endereco_empresa['cidade'];

    echo '<option value="' . $logradouro . '">' . $logradouro . ', '. $numero . ' - ' . $cidade . '</option>';
};

$stmt->close();
$conn->close();