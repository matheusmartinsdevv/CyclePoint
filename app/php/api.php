<?php
// session_start();
// $conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// // EXIBE TODOS OS USUÁRIOS CADASTRADOS 
// if ($_SERVER["REQUEST_METHOD"] == "GET") {
//     $query = mysqli_query($conn, "SELECT nome, cargo, id_empresa FROM usuario;");
    
//     // ATENÇÃO: Evitei exibir a coluna 'senha' aqui, mesmo que fosse o hash, por segurança.
//     while ($response = mysqli_fetch_assoc($query)) {
//         echo $response["nome"] . ", Cargo: " . $response["cargo"] .  "<br>";
//     };

// };

// // CONECTA COM FORMULARIO DE CADASTRO DE USUARIO
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $nome = $_POST['usuario']; 
//     $email = $_POST['email']; 
//     $senha_texto_plano = $_POST['senha']; // Senha enviada pelo formulário
//     $cargo = $_POST['cargo']; 
//     $id_empresa = $_POST['id_empresa']; 


//   // 1. GERAÇÃO DO HASH SEGURO
//     // Usa PASSWORD_DEFAULT para um algoritmo atualizado (atualmente, bcrypt)
//     $senha_hash = password_hash($senha_texto_plano, PASSWORD_DEFAULT);


//     $stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha, cargo, id_empresa) values (?,?,?,?,?);");
    
//     // Usa o HASH ($senha_hash) no bind_param, não a senha em texto simples.
//     $stmt->bind_param("ssssi", $nome, $email, $senha_hash, $cargo, $id_empresa);

//     if ($stmt->execute()) {
//         echo 'usuario cadastrado com sucesso';
//     } else {
//         echo 'ERRO ao cadastrar usuário: ' . $stmt->error;
//     }
//     $stmt->close();
// }

// ?>