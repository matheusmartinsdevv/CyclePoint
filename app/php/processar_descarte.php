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

        // --- PREPARAÇÃO PARA NOTIFICAÇÃO (Buscar nome da Empresa) ---
        $nome_empresa = "Empresa Desconhecida"; // Valor padrão de fallback
        if ($solicitante_empresa_id) {
            $stmt_empresa = $conn->prepare("SELECT razao_social FROM empresa WHERE id_empresa = ?");
            $stmt_empresa->bind_param("i", $solicitante_empresa_id);
            $stmt_empresa->execute();
            $result_empresa = $stmt_empresa->get_result();
            if ($row = $result_empresa->fetch_assoc()) {
                $nome_empresa = $row['razao_social'];
            }
            $stmt_empresa->close();
        }
        
        
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

            // --- INSERÇÃO DA NOTIFICAÇÃO (PARA A RECICLADORA) ---
                
                $id_solicitacao_inserida = $conn->insert_id;

                $stmt_nome_eq = $conn->prepare("SELECT nome_equipamento FROM equipamento WHERE id_equipamento = ?");
                $stmt_nome_eq->bind_param("i", $id_equipamento);
                $stmt_nome_eq->execute();
                $result_nome_eq = $stmt_nome_eq->get_result();
                if ($row = $result_nome_eq->fetch_assoc()) {
                    $nome_equipamento = $row['nome_equipamento'];
                }
                
                $mensagem = "Nova Solicitação de Descarte: $nome_equipamento - Empresa: $nome_empresa.";
                // Link absoluto para a página de solicitações da recicladora
                $link = "http://localhost/CyclePoint/solicitacoesRecicladora.php";
                
                $stmt_notif = $conn->prepare("INSERT INTO notificacoes (id_entidade, tipo_entidade, id_solicitacao_descarte, mensagem, link) VALUES (?, 'recicladora', ?, ?, ?)");
                
                $stmt_notif->bind_param("iiss", $id_recicladora, $id_solicitacao_inserida, $mensagem, $link);
                $stmt_notif->execute();
                $stmt_notif->close();
                
                
                $stmt->close();
                $stmt_solicitacao->close(); 
                
            } else {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => '❌ Erro ao solicitar o descarte. Tente novamente.'
                ];
                $stmt->close();
                $stmt_solicitacao->close();
                break; 
            }
        }

        // Se a execução chegou até o fim do loop (ou deu erro, mas a mensagem já foi setada)
        // Define a mensagem de sucesso (se nenhum erro ocorreu)
        if (!isset($_SESSION['message']) || $_SESSION['message']['type'] !== 'error') {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => '✅ Solicitação de descarte realizada com sucesso!.'
            ];
        }

    }

    header('Location: ../../solicitar-descarte.php'); 


} else {
    header('Location: seus-descartes.php'); 
    exit;
}
?>