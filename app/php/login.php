<?php
session_start();
$conn = mysqli_connect("localhost:3307", "root", "", "cyclepoint_database");


// CONECTA COM FORMULARIO LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; 
    $senha_digitada = $_POST['senha'];

    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE email = ? AND senha = ?;");
    $stmt_check->bind_param("ss", $email, $senha_digitada);
    $stmt_check->execute();


    $result = $stmt_check->get_result();
    $row = $result->fetch_array(MYSQLI_NUM);
    $user_count = $row[0];

    $stmt_check->close();
    // $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = ? AND senha = ?;");
    // $stmt->bind_param("ss", $email, $senha_digitada);
    // $stmt->execute();
    // $resultado = $stmt->get_result();


    // VERIFICA SE EXISTE CADASTRO NO BANCO DE DADOS
    if ($user_count == 0) {

        echo "ERRO: Email ou senha incorretos.";  

            
    } else {
        
        $stmt_data = $conn->prepare("SELECT id_usuario, nome, id_empresa FROM usuario WHERE email = ? AND senha = ?;");
        $stmt_data->bind_param("ss", $email, $senha_digitada);
        $stmt_data->execute();
        $resultado = $stmt_data->get_result();
        
        // Como já checamos que existe 1 linha, apenas buscamos os dados
        $usuario = $resultado->fetch_assoc();
        
        // B) INICIAR A SESSÃO DE LOGIN
        $_SESSION['logado'] = true; 
        // Agora, $usuario está definido corretamente!
        $_SESSION['id_usuario'] = $usuario['id_usuario']; 
        $_SESSION['nome_usuario'] = $usuario['nome'];
        $_SESSION['id_empresa'] = $usuario['id_empresa']; // Se precisar
        
        $stmt_data->close();
                
        // REDIRECIONAR O USUÁRIO
        header("Location: dashboard.php");   
    }

}

?>