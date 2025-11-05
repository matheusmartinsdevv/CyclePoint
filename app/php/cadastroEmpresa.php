<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// CONECTA COM FORMULARIO DE CADASTRO DE EMPRESA
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $razao_social = $_POST['razao_social']; 
    $nome_fantasia = $_POST['nome_fantasia']; 
    $cnpj = $_POST['cnpj'];
    $telefone = $_POST['telefone']; 
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $data_cadastro = date("Y-m-d");

    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $pais = $_POST['pais'];


    // Verificação de CNPJ 
    $stmt_cnpj = $conn->prepare("SELECT cnpj FROM empresa");
    $stmt_cnpj->execute();
    $result = $stmt_cnpj->get_result();

    if ($result->num_rows > 0) {
        while ($dados = $result->fetch_assoc()) {
            $cnpj_banco = $dados['cnpj'];

            if ($cnpj_banco == $cnpj) {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => '❌ Empresa já cadastrada.'
                ];

                header("Location: ../../cadastro.php"); 
            }
        }
        
    } 





    $stmt = $conn->prepare("INSERT INTO empresa (razao_social, nome_fantasia, cnpj, telefone, email, senha, data_cadastro) values (?,?,?,?,?,?,?);");

    $stmt->bind_param("sssssss", $razao_social, $nome_fantasia, $cnpj, $telefone, $email, $senha, $data_cadastro);

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

