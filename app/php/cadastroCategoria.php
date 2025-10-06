<?php
session_start();
$conn = mysqli_connect("localhost:3307", "root", "", "cyclepoint_database");

// CONECTA COM FORMULARIO DE CADASTRO DE CATEGORIA
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_categoria = $_POST['nome-categoria']; 
    $descricao = $_POST['descricao-categoria']; 
    

    $stmt = $conn->prepare("INSERT INTO categoria (nome_categoria, descricao, id_empresa) values (?,?,?);");

    $stmt->bind_param("ssi", $nome_categoria, $descricao, $_SESSION['id_empresa']);

    if ($stmt->execute()) {

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => '✅ Cadastro da categoria ' . $nome_categoria . ' realizado com sucesso!'
        ];
        $stmt->close();


    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => '❌ Erro ao cadastrar a categoria. Tente novamente.'
        ];

    }

    header("Location: ../../configuracoes.php"); 

    

};

?>

