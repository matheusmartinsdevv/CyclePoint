<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// --- CORREÇÃO DE LÓGICA DE SOLICITANTE ---

// Inicializa ambos como NULL para garantir que o que não for usado vá para o banco como NULL
$solicitante_empresa_id = NULL;
$solicitante_usuario_id = NULL;

// 1. PRIORIDADE: USUÁRIO/FUNCIONÁRIO (Cenário onde ambos IDs devem ser preenchidos)
if (isset($_SESSION['id_usuario'])) {
    $solicitante_usuario_id = $_SESSION['id_usuario'];
    
    // Se o usuário tem um ID de empresa na sessão (Cenário 2), preenche ambos.
    if (isset($_SESSION['id_empresa'])) {
        $solicitante_empresa_id = $_SESSION['id_empresa'];
    } 
    
// 2. EMPRESA DIRETA (Só alcança aqui se NÃO houver id_usuario na sessão)
} elseif (isset($_SESSION['id_empresa'])) {
    // Cenário 1: Empresa Direta Solicitando.
    $solicitante_empresa_id = $_SESSION['id_empresa'];
    // $solicitante_usuario_id permanece NULL (Correto para a Empresa).

// 3. Erro: Ninguém logado.
} else {
    die("Erro: Não foi possível identificar o solicitante (empresa ou usuário) na sessão.");
}

// --- FIM DA CORREÇÃO DE LÓGICA ---


// O restante dos IDs obrigatórios, mantido como estava
if (!isset($_SESSION['id_recicladora'])) {
    die("Erro: ID da recicladora não encontrado na sessão.");
}
$id_recicladora = $_SESSION['id_recicladora'];


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
            

            // Prepara o INSERT
            $stmt_solicitacao = $conn->prepare("INSERT INTO solicitacao_descarte (id_equipamento, id_usuario, id_empresa, id_recicladora, data_solicitacao, status_solicitacao) VALUES (?,?,?,?,?,?);");

            // O BIND USA AS VARIÁVEIS CORRIGIDAS
            $stmt_solicitacao->bind_param("iiisss", 
                $id_equipamento, 
                $solicitante_usuario_id, // Se usuário logado, tem valor; se empresa direta, é NULL.
                $solicitante_empresa_id, // Se usuário logado COM empresa, tem valor; se empresa direta, tem valor.
                $id_recicladora, 
                $data_solicitacao, 
                $status_solicitacao
            );

            // ... (restante do código de execução e tratamento de erro)
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