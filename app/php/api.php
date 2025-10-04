<?php
session_start();
$conn = mysqli_connect("localhost:3307", "root", "", "cyclepoint_database");

// EXIBE TODOS OS USUÁRIOS CADASTRADOS 
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $query = mysqli_query($conn, "SELECT * FROM usuario;");

    while ($response = mysqli_fetch_assoc($query)) {
        echo $response["nome"] . ", Senha: " . $response["senha"] .  "<br>";
    };

};

// CONECTA COM FORMULARIO DE CADASTRO DE USUARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['usuario']; 
    $email = $_POST['email']; 
    $senha = $_POST['senha'];
    $cargo = $_POST['cargo']; 
    $id_empresa = $_POST['id_empresa']; 


    $stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha, cargo, id_empresa) values (?,?,?,?,?);");
    $stmt->bind_param("ssssi", $nome, $email, $senha, $cargo, $id_empresa);

    if ($stmt->execute()) {
        echo 'usuario cadastrado com sucesso';
    }
}

?>