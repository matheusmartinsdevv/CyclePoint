<!-- NÃO FINALIZADO -->

<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

$stmt = $conn->prepare("SELECT id_equipamento, nome_equipamento, fabricante, modelo, vida_util_meses FROM equipamento WHERE id_empresa = ? AND status_equipamento = ?");
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    while ($dados_equipamento = $result->fetch_assoc()) {
        $id_equipamento = $dados_equipamento['id_equipamento'];
        $nome_equipamento = $dados_equipamento['nome_equipamento'];
        $fabricante = $dados_equipamento['fabricante'];
        $modelo = $dados_equipamento['modelo'];
        $vida_util_meses = $dados_equipamento['vida_util_meses'];

        echo '<div class="equipamento">';
        echo '<span class="equipamento">'. $nome_equipamento .'</span>';
        echo '<div style="display: flex ;align-items: center;">';
        echo '<button class="btn btn-primary">Ver detalhes</button>';
        echo '<input type="checkbox" name="equipamentos[]" value="' . htmlspecialchars($id_equipamento) . '">';    
        echo '</div>';
        echo '</div>';

    };
} else {
    // SE NÃO ENCONTROU DADOS
    echo '<div class="message-info" style="text-align: center;font-size: 13px;">';
    echo '<h2>Nenhum equipamento encontrado para descarte</h2>';
    echo '<p>Todos os seus equipamentos já foram descartados ou não há registros com o status compatível com descarte.</p>';
    echo '</div>';
    
}

$stmt->close();
$conn->close();


