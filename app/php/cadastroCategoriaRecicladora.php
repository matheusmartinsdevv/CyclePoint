<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// Cadastro de categoria específica da recicladora
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // nomes de campo exclusivos para recicladora
    $nome_categoria = isset($_POST['nome_recicladora_categoria']) ? trim($_POST['nome_recicladora_categoria']) : '';
    $descricao = isset($_POST['descricao_recicladora']) ? trim($_POST['descricao_recicladora']) : null;

    if (empty($nome_categoria)) {
        $_SESSION['message'] = ['type' => 'error', 'text' => '❌ Nome da categoria é obrigatório.'];
        header("Location: ../../itens-que-coleto.php");
        exit;
    }

    if (!isset($_SESSION['id_recicladora'])) {
        $_SESSION['message'] = ['type' => 'error', 'text' => '❌ Sessão de recicladora não encontrada.'];
        header("Location: ../../itens-que-coleto.php");
        exit;
    }

    $id_recicladora = $_SESSION['id_recicladora'];

    $stmt = $conn->prepare("INSERT INTO recicladora_categorias (nome_recicladora_categoria, descricao, id_recicladora) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $nome_categoria, $descricao, $id_recicladora);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => '✅ Categoria cadastrada com sucesso.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => '❌ Erro ao cadastrar categoria.'];
    }

    $stmt->close();
    $conn->close();

    header("Location: ../../itens-que-coleto.php");
    exit;
}
?>