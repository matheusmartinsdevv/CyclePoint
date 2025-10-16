<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// CONECTA COM FORMULARIO DE CADASTRO DE EQUIPAMENTO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_equipamento = $_POST['nome-equipamento'];
    $fabricante = $_POST['fabricante'];
    $modelo = $_POST['modelo'];
    $data_aquisicao = $_POST['data_aquisicao'];
    $vida_util_meses = $_POST['vida_util_meses'];
    $endereco_mac = $_POST['endereco_mac'];
    $categoria = $_POST['categoria'];
    $endereco = $_POST['endereco'];

    $stmt_categoria = $conn->prepare("SELECT id_categoria FROM categoria WHERE nome_categoria = ?;");
    $stmt_categoria->bind_param("s", $categoria);
    $stmt_categoria->execute();
    $result = $stmt_categoria->get_result();
    if ($row = $result->fetch_assoc()) {
        $id_categoria = $row['id_categoria'];
    }


    $stmt_endereco_empresa = $conn->prepare("SELECT id_endereco_empresa FROM endereco_empresa WHERE logradouro = ?");
    $stmt_endereco_empresa->bind_param("s", $endereco);
    $stmt_endereco_empresa->execute();
    $result = $stmt_endereco_empresa->get_result();
    if ($row = $result->fetch_assoc()) {
        $id_endereco_empresa = $row['id_endereco_empresa'];
    }

    
    

    // CADASTRO DE EQUIPAMENTO
    $stmt = $conn->prepare("INSERT INTO equipamento (nome_equipamento, fabricante, modelo, data_aquisicao, vida_util_meses, id_categoria, id_endereco_empresa, endereco_mac) values (?,?,?,?,?,?,?,?);");

    $stmt->bind_param("ssssiiis", $nome_equipamento, $fabricante, $modelo, $data_aquisicao, $vida_util_meses, $id_categoria, $id_endereco_empresa, $endereco_mac);

    if ($stmt->execute()) {

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => '✅ Cadastro do equipamento ' . $nome_equipamento . ' realizado com sucesso!'
        ];
        $stmt->close();
            
             
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => '❌ Erro ao cadastrar o equipamento. Tente novamente.'
        ];
    }



    header("Location: ../../cadastro-equipamento.php"); 

    

};

?>

