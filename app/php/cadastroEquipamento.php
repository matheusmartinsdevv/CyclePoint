<?php

$conn = mysqli_connect("localhost:3307", "root", "", "cyclepoint_database");

// CONECTA COM FORMULARIO DE CADASTRO DE EQUIPAMENTO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $razao_social = $_POST['razao_social']; 
    $nome_fantasia = $_POST['nome_fantasia']; 
    $cnpj = $_POST['cnpj'];
    $telefone = $_POST['telefone']; 
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $pais = $_POST['pais'];


    $stmt = $conn->prepare("INSERT INTO empresa (razao_social, nome_fantasia, cnpj, telefone, email, senha) values (?,?,?,?,?,?);");

    $stmt->bind_param("ssssss", $razao_social, $nome_fantasia, $cnpj, $telefone, $email, $senha);

    if ($stmt->execute()) {
        $id_empresa_inserido = mysqli_insert_id($conn);


        // CADASTRO DE ENDERECO EMPRESA
        $stmt_endereco = $conn->prepare("INSERT INTO endereco_empresa (id_empresa, numero, logradouro, bairro, cidade, estado, pais) values (?,?,?,?,?,?,?);");

        $stmt_endereco->bind_param("iisssss", $id_empresa_inserido, $numero, $logradouro, $bairro, $cidade, $estado, $pais);

        if ($stmt_endereco->execute()) {

            $_SESSION['message'] = [
                'type' => 'success',
                'text' => '✅ Cadastro da empresa ' . $nome_fantasia . ' realizado com sucesso! Você já pode criar seu usuário.'
            ];
            $stmt_endereco->close();
            
             
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => '❌ Erro ao cadastrar o endereço. Tente novamente.'
            ];
            
        }
    } else {
        $_SESSION['message'] = [
                'type' => 'error',
                'text' => '❌ Erro ao cadastrar a empresa. Tente novamente.'
            ];
    }

    header("Location: ../../login.php"); 

    

};

?>

