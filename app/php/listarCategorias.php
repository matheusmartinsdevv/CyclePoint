<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

$stmt = $conn->prepare("SELECT nome_categoria FROM categoria");
$stmt->execute();
$result = $stmt->get_result();

while ($dados_categoria = $result->fetch_assoc()) {
    $nome_categoria = $dados_categoria['nome_categoria'];

    echo '<option value="' . $nome_categoria . '">' . $nome_categoria . '</option>';
};

$stmt->close();
$conn->close();