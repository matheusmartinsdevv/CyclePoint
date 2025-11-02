<?php
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_SESSION['id_recicladora'])) {
    die("Erro: ID da recicladora não encontrado na sessão.");
}

$id_recicladora = $_SESSION['id_recicladora'];

$stmt = $conn->prepare("SELECT ic.id_item_coletado, c.nome_categoria, c.id_categoria 
                       FROM item_coletado ic 
                       JOIN categoria c ON ic.id_categoria = c.id_categoria 
                       WHERE ic.id_recicladora = ?");
$stmt->bind_param("i", $id_recicladora);
$stmt->execute();
$result = $stmt->get_result();

while ($item = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>'.htmlspecialchars($item['nome_categoria']).'</td>';
    echo '<td style="width: 150px">';
    echo '<button data-id="'.$item['id_item_coletado'].'" class="btn btn-secondary editar-item" style="margin-right:5px;">Editar</button>';
    echo '<button data-id="'.$item['id_item_coletado'].'" class="btn btn-secondary excluir-item">Excluir</button>';
    echo '</td>';
    echo '</tr>';
}

if ($result->num_rows === 0) {
    echo '<tr><td colspan="2" class="text-center">Nenhum item cadastrado</td></tr>';
}

$stmt->close();
?>