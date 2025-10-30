<?php
session_start();
header('Content-Type: application/json');

// --- 1. IDENTIFICA O DESTINATÁRIO LOGADO ---
$id_entidade = null;
$tipo_entidade = null;

// Preferir `role` quando disponível para evitar ambiguidade se múltiplas ids
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
    if ($role === 'recicladora' && isset($_SESSION['id_recicladora'])) {
        $id_entidade = $_SESSION['id_recicladora'];
        $tipo_entidade = 'recicladora';
    } else {
        // Para administrador/usuário comum, usamos a entidade empresa
        if (isset($_SESSION['id_empresa'])) {
            $id_entidade = $_SESSION['id_empresa'];
            $tipo_entidade = 'empresa';
        }
    }

} else {
    // Fallback: comportamento antigo (compatibilidade)
    if (isset($_SESSION['id_recicladora']) && !isset($_SESSION['id_empresa'])) {
        $id_entidade = $_SESSION['id_recicladora'];
        $tipo_entidade = 'recicladora';
    } elseif (isset($_SESSION['id_empresa'])) {
        $id_entidade = $_SESSION['id_empresa'];
        $tipo_entidade = 'empresa';
    }
}

if (!$id_entidade) {
    echo json_encode(['success' => false, 'count' => 0, 'data' => []]);
    exit;
}
// ---------------------------------------------

// Conexão com o Banco de Dados (Ajuste)
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// 2. Consulta ÚNICA, usando id_entidade E tipo_entidade
$sql = "SELECT id, mensagem, link, lida, data_criacao 
        FROM notificacoes 
        WHERE id_entidade = ? AND tipo_entidade = ? 
        ORDER BY data_criacao DESC LIMIT 10"; // Buscar as 10 mais recentes

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id_entidade, $tipo_entidade);
$stmt->execute();
$result = $stmt->get_result();

$count = 0;
$notificacoes = [];

while ($row = $result->fetch_assoc()) {
    // Se não estiver lida, incrementa o contador
    if ($row['lida'] == 0) {
        $count++;
    }
    $row['data_criacao_formatada'] = date('d/m/Y H:i', strtotime($row['data_criacao']));
    $notificacoes[] = $row;
}
$stmt->close();
$conn->close();

// Retorna os dados como JSON
echo json_encode([
    'success' => true,
    'count' => $count,
    'data' => $notificacoes
]);
?>