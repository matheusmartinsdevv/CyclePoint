<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// CONECTA COM GERENCIAR ENDEREÇOS
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }


    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $pais = $_POST['pais']; 
    

    // CADASTRO DE ENDERECO EMPRESA
    $stmt_endereco = $conn->prepare("INSERT INTO endereco_empresa (id_empresa, numero, logradouro, bairro, cidade, estado, pais) values (?,?,?,?,?,?,?);");

    $stmt_endereco->bind_param("iisssss", $id_empresa, $numero, $logradouro, $bairro, $cidade, $estado, $pais);

    if ($stmt_endereco->execute()) {

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => '✅ Cadastro do endereço realizado com sucesso!'
        ];
        $stmt_endereco->close();
            
             
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => '❌ Erro ao cadastrar o endereço. Tente novamente.'
        ];
            
    }

    header("Location: ../../gerenciar-enderecos.php"); 

    

};

?>