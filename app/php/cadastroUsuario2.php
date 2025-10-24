<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

// CONECTA COM FORMULARIO DE CADASTRO DE USUARIO VIA USUARIO ADMIN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome']; 
    $cargo = $_POST['cargo']; 
    $email = $_POST['email'];
    $senha = $_POST['senha'];


        

        $stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha, cargo, id_empresa) values (?,?,?,?,?);");

        $stmt->bind_param("ssssi", $nome, $email, $senha, $cargo, $id_empresa);

        if ($stmt->execute()) {

            $_SESSION['message'] = [
                'type' => 'success',
                'text' => '✅ Cadastro do usuário ' . $nome . ' realizado com sucesso!'
            ];
            $stmt->close();
            
             
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => '❌ Erro ao cadastrar o usuário. Tente novamente.'
            ];
            
        }
    }

    header("Location: ../../gerenciar-usuarios.php"); 



?>

