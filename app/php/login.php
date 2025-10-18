<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");


// CONECTA COM FORMULARIO LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['usuario_login']; 
    $senha_digitada = $_POST['senha_login'];


    $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE nome = ? AND senha = ?;");
    $stmt->bind_param("ss", $nome, $senha);
    $stmt->execute();

    $result = $stmt_check->get_result();
    $row = $result->fetch_array(MYSQLI_NUM);
    $user_count = $row[0];

    $stmt_check->close();


    // VERIFICA SE EXISTE CADASTRO NO BANCO DE DADOS
    if ($resultado->num_rows === 1) {

    $usuario = $resultado->fetch_assoc();
        
    // INICIAR A SESSÃO DE LOGIN
    $_SESSION['logado'] = true; // Flag para indicar que o usuário está logado
    $_SESSION['id_usuario'] = $usuario['id_usuario']; // Armazena o ID do banco
    $_SESSION['nome_usuario'] = $nome; // Opcional: armazena o nome
        
    // REDIRECIONAR O USUÁRIO
    header("Location: paginaLogado.php"); 
    exit();
    
    } else {
    
    echo "ERRO: Nome de usuário ou senha incorretos.";
    
}

}

?>