<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// --- CORREÇÃO DE LÓGICA DE SOLICITANTE ---

$solicitante_empresa_id = NULL;
$solicitante_usuario_id = NULL;

if (isset($_SESSION['id_usuario'])) {
    $solicitante_usuario_id = $_SESSION['id_usuario'];
    
    if (isset($_SESSION['id_empresa'])) {
        $solicitante_empresa_id = $_SESSION['id_empresa'];
    } 
    
} elseif (isset($_SESSION['id_empresa'])) {
    $solicitante_empresa_id = $_SESSION['id_empresa'];

} else {
    die("Erro: Não foi possível identificar o solicitante (empresa ou usuário) na sessão.");
}



if (!isset($_SESSION['id_recicladora'])) {
    die("Erro: ID da recicladora não encontrado na sessão.");
}
$id_recicladora = $_SESSION['id_recicladora'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['equipamentos']) && is_array($_POST['equipamentos'])) {
        
        $ids_equipamentos_selecionados = $_POST['equipamentos'];
        $data_solicitacao = date("Y-m-d");
        $status_solicitacao = 'pendente';
        
        
        foreach ($ids_equipamentos_selecionados as $id_equipamento) {

            $stmt = $conn->prepare("UPDATE equipamento SET status_equipamento = 'aguardando_descarte' WHERE id_equipamento = ?");
            $stmt->bind_param("i", $id_equipamento);
            

            $stmt_solicitacao = $conn->prepare("INSERT INTO solicitacao_descarte (id_equipamento, id_usuario, id_empresa, id_recicladora, data_solicitacao, status_solicitacao) VALUES (?,?,?,?,?,?);");

            $stmt_solicitacao->bind_param("iiisss", 
                $id_equipamento, 
                $solicitante_usuario_id, 
                $solicitante_empresa_id, 
                $id_recicladora, 
                $data_solicitacao, 
                $status_solicitacao
            );

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