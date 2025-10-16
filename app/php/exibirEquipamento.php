<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

$stmt = $conn->prepare("SELECT nome_equipamento, fabricante, modelo, vida_util_meses FROM equipamento WHERE id_empresa = ?");
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

while ($dados_equipamento = $result->fetch_assoc()) {
    $nome_equipamento = $dados_equipamento['nome_equipamento'];
    $fabricante = $dados_equipamento['fabricante'];
    $modelo = $dados_equipamento['modelo'];
    $vida_util_meses = $dados_equipamento['vida_util_meses'];

    echo '<div class="equipamento">';
    echo '<span class="equipamento">'. $nome_equipamento .'</span>';
    echo '<button class="btn btn-primary">Ver detalhes</button>';
    echo '</div>';
};

$stmt->close();
$conn->close();


