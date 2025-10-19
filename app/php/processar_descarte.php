<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

if (!isset($_SESSION['id_recicladora'])) {
        die("Erro: ID da recicladora não encontrado na sessão.");
    }
    $id_recicladora = $_SESSION['id_recicladora'];

if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
    } else {
        die("Erro: ID do usuario não encontrado na sessão."); 
    }

// if (isset($_SESSION['id_usuario_empresa'])) {
//         $id_empresa_usuario = $_SESSION['id_usuario_empresa'];
//     } else {
//         die("Erro: ID da empresa do usuario não encontrada na sessão."); 
//     }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Verifica se o array 'equipamentos' foi enviado (ou seja, se algum foi marcado)
    if (isset($_POST['equipamentos']) && is_array($_POST['equipamentos'])) {
        
        $ids_equipamentos_selecionados = $_POST['equipamentos'];
        $data_solicitacao = date("Y-m-d");
        $status_solicitacao = 'pendente';
        
        
        foreach ($ids_equipamentos_selecionados as $id_equipamento) {

            

            // Atualiza o status do equipamento e associa à recicladora
            $stmt = $conn->prepare("UPDATE equipamento SET status_equipamento = 'aguardando_descarte' WHERE id_equipamento = ?");
            $stmt->bind_param("i", $id_equipamento);
            

            $stmt_solicitacao = $conn->prepare("INSERT INTO solicitacao_descarte (id_equipamento, id_usuario, id_recicladora, data_solicitacao, status_solicitacao) VALUES (?,?,?,?,?);");

            $stmt_solicitacao->bind_param("iiiss", $id_equipamento, $id_usuario, $id_recicladora, $data_solicitacao, $status_solicitacao);


            if ($stmt->execute() && $stmt_solicitacao->execute()) {

            $_SESSION['message'] = [
                'type' => 'success',
                'text' => '✅ Solicitação de descarte realizada com sucesso!'
            ];
            $stmt->close();
            
             
            } else {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => '❌ Erro ao solicitar o descarte. Tente novamente.'
                ];
                
            }
        }

        

    }

    header('Location: ../../solicitar-descarte.php'); 


} else {
    header('Location: seus-descartes.php'); 
    exit;
}
?>