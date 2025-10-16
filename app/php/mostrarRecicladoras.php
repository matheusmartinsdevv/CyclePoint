

<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// 1. Verificar a conexão
if (!$conn) {
    die("Erro de Conexão com o Banco de Dados: " . mysqli_connect_error());
}

// O trecho de código da sessão está comentado, mas é bom tê-lo em mente para a produção.
// if (isset($_SESSION['id_empresa'])) {
//     $id_empresa = $_SESSION['id_empresa'];
// } else {
//     die("Erro: ID da empresa não encontrado na sessão."); 
// }


// 2. Consulta SQL OTIMIZADA com INNER JOIN:
//    Busca dados da recicladora (r) e seu endereço (e) em uma única operação,
//    garantindo que os dados correspondam através do id_recicladora.
$stmt = $conn->prepare("
    SELECT 
        r.razao_social, r.email, r.telefone, r.descricao,
        e.numero, e.logradouro, e.bairro, e.cidade, e.estado, e.pais
    FROM 
        recicladora r
    INNER JOIN 
        endereco_recicladora e ON r.id_recicladora = e.id_recicladora;
");

// 3. Executar e obter resultados
if (!$stmt->execute()) {
    die("Erro ao executar a consulta: " . $stmt->error);
}
$result = $stmt->get_result();


// 4. Iterar sobre os resultados (apenas um loop é necessário!)
while ($dados = $result->fetch_assoc()) {
    
    // Atribuir as variáveis e aplicar htmlspecialchars() para segurança
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

    // HTML gerado com os dados dinâmicos do banco
    echo '<div class="recicladora">';
    echo '<h4>' . $razao_social . '</h4>';
    echo '<p>' . $descricao . '</p>';
    echo '<span>' . $logradouro . ', ' . $numero . ' - ' . $bairro . '</span><br>';
    echo '<span>' . $cidade . ', ' . $estado . ' - ' . $pais . '</span>';
    echo '<p>' . $telefone . '</p>';
    echo '<p>' . $email . '</p>';

    echo '<button type="submit" class="btn btn-primary btn-large">Solicitar descarte</button>';
    echo '</div>';
    echo '<br><hr>';
};

// 5. Fechar o statement e a conexão
$stmt->close();
$conn->close();

?>
