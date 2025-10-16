<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

$stmt = $conn->prepare("SELECT nome_categoria FROM categoria WHERE id_empresa = ?;");
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

while ($dados_categoria = $result->fetch_assoc()) {
    $nome_categoria = $dados_categoria['nome_categoria'];

    echo '<option value="' . $nome_categoria . '">' . $nome_categoria . '</option>';
};

$stmt->close();
$conn->close();