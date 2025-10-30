<?php
session_start();
header('Content-Type: application/json');

// --- 1. IDENTIFICA O DESTINATÁRIO LOGADO ---
$id_entidade = null;
$tipo_entidade = null;

// Priorizar `role` quando informado na sessão para evitar ambiguidades
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
    if ($role === 'recicladora' && isset($_SESSION['id_recicladora'])) {
        $id_entidade = $_SESSION['id_recicladora'];
        $tipo_entidade = 'recicladora';
    } else {
        if (isset($_SESSION['id_empresa'])) {
            $id_entidade = $_SESSION['id_empresa'];
            $tipo_entidade = 'empresa';
        }
    }
} else {
    // Fallback para compatibilidade com sessões antigas
    if (isset($_SESSION['id_recicladora']) && !isset($_SESSION['id_empresa'])) {
        $id_entidade = $_SESSION['id_recicladora'];
        $tipo_entidade = 'recicladora';
    } elseif (isset($_SESSION['id_empresa'])) {
        $id_entidade = $_SESSION['id_empresa'];
        $tipo_entidade = 'empresa';
    }
}

if (!$id_entidade || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
    exit;
}
// ---------------------------------------------

// Conexão com o Banco de Dados
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

// Marcar TODAS as notificações não lidas
$sql = "UPDATE notificacoes SET lida = TRUE WHERE id_entidade = ? AND tipo_entidade = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id_entidade, $tipo_entidade);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Notificação(ões) marcada(s) como lida(s).']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o banco.']);
}

$stmt->close();
$conn->close();
?>