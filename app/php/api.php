<?php
$conn = mysqli_connect("localhost:3307", "root", "", "db_cyclepoint");


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $query = mysqli_query($conn, "SELECT * FROM usuario;");

    while ($response = mysqli_fetch_assoc($query)) {
        echo $response["nome"] . "<br>";
    };

};

// CONECTAR COM FORMULARIO DE CADASTRO DE USUARIO/EMPRESA
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $nome = $_POST['sabor']; 
    // $valor = $_POST['valor'];
    // $cor =  $_POST['cor'];

    // $stmt = $conn->prepare("INSERT INTO sabor (nome, valor, cor) values (?,?,?);");
    // $stmt->bind_param("sds", $nome, $valor, $cor);

    // if ($stmt->execute()) {
    //     echo 'sabor cadastrado com sucesso';
    // }
}

?>