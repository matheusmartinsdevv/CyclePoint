<?php

$conn = mysqli_connect("localhost:3307", "root", "", "cyclepoint_database");

// CONECTA COM FORMULARIO DE CADASTRO DE USUARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome']; 
    $cargo = $_POST['cargo']; 
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $cnpj = $_POST['cnpj'];

    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM empresa WHERE cnpj = ?");
    $stmt_check->bind_param("s", $cnpj);
    $stmt_check->execute();


    $result = $stmt_check->get_result();
    $row = $result->fetch_array(MYSQLI_NUM);
    $cnpj_count = $row[0];


// 2. CNPJ existe
    if ($cnpj_count == 0) {
        
        echo "Erro: Empresa não cadastrada. Verifique o CNPJ ou realize o cadastro completo.";
        
    } else {
        
        $stmt_id = $conn->prepare("SELECT id_empresa FROM empresa WHERE cnpj = ?");
        $stmt_id->bind_param("s", $cnpj);
        $stmt_id->execute();
        $result_id = $stmt_id->get_result();
        $empresa = $result_id->fetch_assoc();
        $id_empresa = $empresa['id_empresa'];
        

        $stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha, cargo, id_empresa) values (?,?,?,?,?);");

        $stmt->bind_param("ssssi", $nome, $email, $senha, $cargo, $id_empresa);

        if ($stmt->execute()) {
                
            header("Location: ../../login.html"); 
                
            exit(); 
        }
    }

};

?>

