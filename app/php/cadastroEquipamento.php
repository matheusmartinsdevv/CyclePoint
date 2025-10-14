<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// CONECTA COM FORMULARIO DE CADASTRO DE EQUIPAMENTO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_equipamento = $_POST['nome-equipamento'];
    $endereco_ip = $_POST['ip'];
    $fabricante = $_POST['fabricante'];
    $modelo = $_POST['modelo'];
    $data_aquisicao = $_POST['data_aquisicao'];
    $vida_util_meses = $_POST['vida_util_meses'];
    $status_equipamento = ;
    
    

    // ADAPTAR PARA CADASTRO DE EQUIPAMENTO
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

