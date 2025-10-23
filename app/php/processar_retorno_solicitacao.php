<?php
session_start();
// O script deve responder em JSON
header('Content-Type: application/json');

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// Verifica a conexão
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados.']);
    exit;
}

// Verifica se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Valida os dados recebidos do JavaScript/Fetch
    $acao = isset($_POST['acao']) ? $_POST['acao'] : ''; 
    $id_solicitacao_descarte = isset($_POST['id_solicitacao_descarte']) ? (int)$_POST['id_solicitacao_descarte'] : 0;
    
    // A variável $acao virá do JavaScript como 'Aceito' ou 'Recusado', que é o status que você quer no banco.
    if ($acao !== 'Aceito' && $acao !== 'Recusado' || $id_solicitacao_descarte <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos recebidos.']);
        $conn->close();
        exit;
    }

    // Prepara a query de UPDATE
    $stmt = $conn->prepare("UPDATE solicitacao_descarte SET status_solicitacao = ? WHERE id_solicitacao_descarte = ?;");

    // Vincula os parâmetros: 's' para string ($acao) e 'i' para integer ($id_solicitacao_descarte)
    $stmt->bind_param("si", $acao, $id_solicitacao_descarte);

    if ($stmt->execute()) {
        // Sucesso na execução do UPDATE
        // Não use session messages ou header('Location') em scripts AJAX.
        echo json_encode(['success' => true, 'message' => "Solicitação $id_solicitacao_descarte atualizada para $acao."]);
    } else {
        // Erro na execução
        echo json_encode(['success' => false, 'message' => 'Erro ao executar o UPDATE: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();

} else {
    // Método não permitido
    echo json_encode(['success' => false, 'message' => 'Método de requisição não permitido.']);
}
?>