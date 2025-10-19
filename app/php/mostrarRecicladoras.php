<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!$conn) {
    die("Erro de Conexão com o Banco de Dados: " . mysqli_connect_error());
}


$stmt = $conn->prepare("
    SELECT 
        r.id_recicladora, r.razao_social, r.email, r.telefone, r.descricao,
        e.numero, e.logradouro, e.bairro, e.cidade, e.estado, e.pais
    FROM 
        recicladora r
    INNER JOIN 
        endereco_recicladora e ON r.id_recicladora = e.id_recicladora;
");

if (!$stmt->execute()) {
    die("Erro ao executar a consulta: " . $stmt->error);
}
$result = $stmt->get_result();


while ($dados = $result->fetch_assoc()) {
    
    $id_recicladora = $dados['id_recicladora'];
    $razao_social = htmlspecialchars($dados['razao_social']);
    $email = htmlspecialchars($dados['email']);
    $telefone = htmlspecialchars($dados['telefone']);
    $descricao = htmlspecialchars($dados['descricao']);
    $logradouro = htmlspecialchars($dados['logradouro']);
    $numero = htmlspecialchars($dados['numero']);
    $bairro = htmlspecialchars($dados['bairro']);
    $cidade = htmlspecialchars($dados['cidade']);
    $estado = htmlspecialchars($dados['estado']);
    $pais = htmlspecialchars($dados['pais']);

    echo '<div class="recicladora">';
    echo '<h4>' . $razao_social . '</h4>';
    echo '<p>' . $descricao . '</p>';
    echo '<span>' . $logradouro . ', ' . $numero . ' - ' . $bairro . '</span><br>';
    echo '<span>' . $cidade . ', ' . $estado . ' - ' . $pais . '</span>';
    echo '<p>' . $telefone . '</p>';
    echo '<p>' . $email . '</p>';

    echo '<a href="/CyclePoint/solicitar-descarte.php?id_recicladora=' . $id_recicladora . '"><button type="submit" class="btn btn-primary btn-large">Solicitar descarte</button></a>';
    echo '</div>';
    echo '<br><hr>';
};

$stmt->close();
$conn->close();

?>
