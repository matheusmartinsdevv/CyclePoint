<?php
if (!isset($_SESSION['id_empresa'])) {
    $dashboard_data = [
        'total_equipamentos' => 0,
        'equipamentos_ativos' => 0,
        'solicitacoes_pendentes' => 0,
        'coletas_futuras' => 0,
        'grafico_categorias' => []
    ];
    return; 
}


$id_empresa = $_SESSION['id_empresa'];
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

$dashboard_data = [];

$stmt1 = $conn->prepare("SELECT COUNT(*) AS total FROM equipamento WHERE id_empresa = ?");
$stmt1->bind_param("i", $id_empresa);
$stmt1->execute();
$result1 = $stmt1->get_result();
$dashboard_data['total_equipamentos'] = $result1->fetch_assoc()['total'];
$stmt1->close();
     

$stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM equipamento WHERE id_empresa = ? AND status_equipamento IS NULL");
$stmt2->bind_param("i", $id_empresa);
$stmt2->execute();
$result2 = $stmt2->get_result();
$dashboard_data['equipamentos_ativos'] = $result2->fetch_assoc()['total'];
$stmt2->close();


$stmt3 = $conn->prepare("
    SELECT COUNT(DISTINCT s.id_solicitacao_descarte) AS total
    FROM solicitacao_descarte s
    INNER JOIN equipamento e ON s.id_equipamento = e.id_equipamento
    WHERE e.id_empresa = ? AND s.status_solicitacao IS NULL
");
$stmt3->bind_param("i", $id_empresa);
$stmt3->execute();
$result3 = $stmt3->get_result();
$dashboard_data['solicitacoes_pendentes'] = $result3->fetch_assoc()['total'];
$stmt3->close();

$stmt4 = $conn->prepare("
    SELECT COUNT(DISTINCT s.id_solicitacao_descarte) AS total
    FROM solicitacao_descarte s
    INNER JOIN equipamento e ON s.id_equipamento = e.id_equipamento
    WHERE e.id_empresa = ? AND s.status_solicitacao = 'Aceito' AND s.data_coleta >= CURDATE()
");
$stmt4->bind_param("i", $id_empresa);
$stmt4->execute();
$result4 = $stmt4->get_result();
$dashboard_data['coletas_futuras'] = $result4->fetch_assoc()['total'];
$stmt4->close();

$stmt5 = $conn->prepare("
    SELECT c.nome_categoria, COUNT(e.id_equipamento) AS total
    FROM equipamento e 
    JOIN categoria c ON e.id_categoria = c.id_categoria
    WHERE e.id_empresa = ?
    GROUP BY c.nome_categoria
    ORDER BY total DESC
");
$stmt5->bind_param("i", $id_empresa);
$stmt5->execute();
$result5 = $stmt5->get_result();

$categorias = [];
while ($row = $result5->fetch_assoc()) {
    $categorias[] = $row;
}
$dashboard_data['grafico_categorias'] = $categorias;
$stmt5->close();
?>