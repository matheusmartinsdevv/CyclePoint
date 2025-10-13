<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

$stmt = $conn->prepare("SELECT logradouro, numero, bairro, cidade, estado, pais FROM endereco_empresa WHERE id_empresa=". $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

while ($dados_endereco_empresa = $result->fetch_assoc()) {
    $logradouro = $dados_endereco_empresa['logradouro'];
    $numero = $dados_endereco_empresa['numero'];
    $bairro = $dados_endereco_empresa['bairro'];
    $cidade = $dados_endereco_empresa['cidade'];
    $estado = $dados_endereco_empresa['estado'];
    $pais = $dados_endereco_empresa['pais'];


    echo '<div class="endereco">';
    echo '<span class="local">'. $logradouro .', '. $numero . ', '. $bairro . ' - ' . $cidade. ', '. $estado . ' - '. $pais . '</span>';
    echo '<button class="btn btn-primary">Editar</button>';
    echo '</div>';
};

$stmt->close();
$conn->close();
