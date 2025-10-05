<?php
session_start();
$conn = mysqli_connect("localhost:3307", "root", "", "cyclepoint_database");

// CONECTA COM FORMULARIO DE CADASTRO DE RECICLADORA
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


    $stmt = $conn->prepare("INSERT INTO recicladora (razao_social, nome_fantasia, cnpj, email, telefone, senha) values (?,?,?,?,?,?);");

    $stmt->bind_param("ssssss", $razao_social, $nome_fantasia, $cnpj, $email, $telefone, $senha);

    if ($stmt->execute()) {

        $id_recicladora_inserido = mysqli_insert_id($conn);


        // CADASTRO DE ENDERECO RECICLADORA
        $stmt_endereco = $conn->prepare("INSERT INTO endereco_recicladora (id_recicladora, numero, logradouro, bairro, cidade, estado, pais) values (?,?,?,?,?,?,?);");

        $stmt_endereco->bind_param("iisssss", $id_recicladora_inserido, $numero, $logradouro, $bairro, $cidade, $estado, $pais);

        if ($stmt_endereco->execute()) {

            $_SESSION['message'] = [
                'type' => 'success',
                'text' => '✅ Cadastro da recicladora ' . $nome_fantasia . ' realizado com sucesso! Você já pode fazer login.'
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
                'text' => '❌ Erro ao cadastrar a recicladora. Tente novamente.'
            ];
    }

    header("Location: ../../login.php"); 

};

?>

