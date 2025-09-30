<?php
session_start();
$conn = mysqli_connect("localhost:3307", "root", "", "db_cyclepoint");

// EM CASO DE FALHA NA CONEXAO COM BANCO DE DADOS
if ($conn->connect_error) {
    die("Falha na Conexão: " . $conn->connect_error);
}

// CONECTA COM FORMULARIO LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['usuario_login']; 
    $senha = $_POST['senha_login'];


    $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE nome = ? AND senha = ?;");
    $stmt->bind_param("ss", $nome, $senha);
    $stmt->execute();

    $resultado = $stmt->get_result();


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