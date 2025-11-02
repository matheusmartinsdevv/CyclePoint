<?php
session_start();
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (!isset($_SESSION['id_recicladora'])) {
    die("Erro: ID da recicladora não encontrado na sessão.");
}

$id_recicladora = $_SESSION['id_recicladora'];

$stmt = $conn->prepare("SELECT id_recicladora_categoria, nome_recicladora_categoria, descricao 
                       FROM recicladora_categorias 
                       WHERE id_recicladora = ?");
$stmt->bind_param("i", $id_recicladora);
$stmt->execute();
$result = $stmt->get_result();

while ($categoria = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>'.htmlspecialchars($categoria['nome_recicladora_categoria']).'</td>';
    echo '<td>'.htmlspecialchars($categoria['descricao']).'</td>';
    echo '<td style="width: 150px">';
    echo '<button data-id="'.$categoria['id_recicladora_categoria'].'" class="btn btn-secondary editar-categoria" style="margin-right:5px;">Editar</button>';
    echo '<button data-id="'.$categoria['id_recicladora_categoria'].'" class="btn btn-secondary excluir-categoria">Excluir</button>';
    echo '</td>';
    echo '</tr>';
}

if ($result->num_rows === 0) {
    echo '<tr><td colspan="3" class="text-center">Nenhuma categoria cadastrada</td></tr>';
}

$stmt->close();
?>