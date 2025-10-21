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

        // VERIFICA SE É ADMINISTRADOR (EMAIL E SENHA DA EMPRESA)

        $stmt_adm = $conn->prepare("SELECT COUNT(*) FROM empresa WHERE email = ? AND senha = ?;");
        $stmt_adm->bind_param("ss", $email, $senha_digitada);
        $stmt_adm->execute();

        $result = $stmt_adm->get_result();
        $row = $result->fetch_array(MYSQLI_NUM);
        $adm_user_count = $row[0];

        $stmt_adm->close();

        if ($adm_user_count == 0) {
            
            // VERIFICA SE É RECICLADORA (EMAIL E SENHA DA RECICLADORA)

            $stmt_recicladora = $conn->prepare("SELECT COUNT(*) FROM recicladora WHERE email = ? AND senha = ?;");
            $stmt_recicladora->bind_param("ss", $email, $senha_digitada);
            $stmt_recicladora->execute();

            $result = $stmt_recicladora->get_result();
            $row = $result->fetch_array(MYSQLI_NUM);
            $recicladora_user_count = $row[0];

            $stmt_recicladora->close();

            if ($recicladora_user_count == 0) {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => '❌ Erro: Email ou senha incorretos. Tente novamente.'
                ];
                header("Location: ../../login.php");

            } else {
                $stmt_data = $conn->prepare("SELECT id_recicladora,nome_fantasia FROM recicladora WHERE email = ? AND senha = ?;");
                $stmt_data->bind_param("ss", $email, $senha_digitada);
                $stmt_data->execute();
                $resultado = $stmt_data->get_result();
                
                
                $recicladora = $resultado->fetch_assoc();
                
                // INICIAR A SESSÃO DE LOGIN
                $_SESSION['logado'] = true; 
                $_SESSION['id_recicladora'] = $recicladora['id_recicladora']; 
                $_SESSION['nome_logado_display'] = $recicladora['nome_fantasia'];
                $_SESSION['role'] = 'recicladora';

                
                $stmt_data->close();
                        
                // REDIRECIONAR A RECICLADORA
                header("Location: ../../paginaRecicladora.php");
            }


        } else {

            $stmt_data = $conn->prepare("SELECT id_empresa, nome_fantasia FROM empresa WHERE email = ? AND senha = ?;");
            $stmt_data->bind_param("ss", $email, $senha_digitada);
            $stmt_data->execute();
            $resultado = $stmt_data->get_result();
            
            
            $empresa = $resultado->fetch_assoc();
            
            // INICIAR A SESSÃO DE LOGIN
            $_SESSION['logado'] = true; 
            $_SESSION['id_empresa'] = $empresa['id_empresa']; 
            $_SESSION['nome_logado_display'] = $empresa['nome_fantasia'];
            $_SESSION['role'] = 'administrador';

            
            $stmt_data->close();
                    
            // REDIRECIONAR O USUÁRIO ADMINISTRADOR
            header("Location: ../../dashboard.php");

        }

            
    } else {
    
    echo "ERRO: Nome de usuário ou senha incorretos.";
    
}

}

?>