<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Verifica se o array 'equipamentos' foi enviado (ou seja, se algum foi marcado)
    if (isset($_POST['equipamentos']) && is_array($_POST['equipamentos'])) {
        
        $ids_equipamentos_selecionados = $_POST['equipamentos'];
        
        
        foreach ($ids_equipamentos_selecionados as $id_equipamento) {

            

            $stmt = $conn->prepare("UPDATE equipamento SET status_equipamento = 'aguardando_descarte' WHERE id_equipamento = ?");
            $stmt->bind_param("i", $id_equipamento);
            if ($stmt->execute()) {

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

            $stmt_solicitacao = $conn->prepare("INSERT INTO solicitacao_descarte VALUES (?,?,?,?,?);");
            $stmt_solicitacao->bind_param("i,i,i,s,s", )
        }

        

    }

    header('Location: ../../solicitar-descarte.php'); 


} else {
    header('Location: seus-descartes.php'); 
    exit;
}
?>