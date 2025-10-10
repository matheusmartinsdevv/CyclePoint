<?php

$conn = mysqli_connect("localhost:3307", "root", "", "cyclepoint_database");

$stmt = $conn->prepare("SELECT nome_categoria, descricao FROM categoria");
$stmt->execute();
$result = $stmt->get_result();

while ($dados_categoria = $result->fetch_assoc()) {
    $nome_categoria = $dados_categoria['nome_categoria'];
    $descricao_categoria = $dados_categoria['descricao'];

    echo '<div class="exibe_categoria" style="display: flex;justify-content: space-between">';
    echo '  <span style="padding: 0px 15px;">' . $nome_categoria . '</span>';
    echo '  <span style="padding: 0px 15px;">' . $descricao_categoria . '</span>';
    echo '</div>';
};

$stmt->close();
$conn->close();

