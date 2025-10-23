<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data_coleta = $_POST['dataColeta']; 
    $id_solicitacao_descarte = $_POST['id_solicitacao_descarte'];
    

    $stmt = $conn->prepare("UPDATE solicitacao_descarte SET data_coleta = ? WHERE id_solicitacao_descarte = ?;");

    $stmt->bind_param("si", $data_coleta, $id_solicitacao_descarte);

    if ($stmt->execute()) {

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => '✅ Agendamento da coleta realizado com sucesso'
        ];
        $stmt->close();


    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => '❌ Erro ao agendar a coleta. Tente novamente.'
        ];

    }

    header("Location: ../../solicitacoesRecicladora.php"); 

    

};

?>

