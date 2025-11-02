<?php
$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
    $id_empresa = $_SESSION['id_empresa'];
} else {
    die("Erro: ID da empresa não encontrado na sessão.");
}

$stmt = $conn->prepare("SELECT id_usuario, nome, email, cargo FROM usuario WHERE id_empresa = ?");
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($u = $result->fetch_assoc()) {
        $id_usuario = (int)$u['id_usuario'];
        $nome = htmlspecialchars($u['nome']);
        $email = htmlspecialchars($u['email']);
        $cargo = htmlspecialchars($u['cargo']);

        echo '<div class="usuario" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">';
        echo '  <div style="flex:1;">';
        echo '    <span style="font-weight:700; padding-right:10px;">' . $nome . '</span>';
        echo '    <span style="color:#666; padding-right:10px;">' . $email . '</span>';
        echo '    <span style="color:#666;">' . $cargo . '</span>';
        echo '  </div>';
        echo '  <div>';
        echo '    <button data-id="'.$id_usuario.'" class="btn btn-secondary editar-usuario" style="margin-right:5px;">Editar</button>';
        echo '    <button data-id="'.$id_usuario.'" class="btn btn-secondary excluir-usuario">Excluir</button>';
        echo '  </div>';
        echo '</div>';
    }
} else {
    echo '<div class="message-info" style="text-align:center; font-size:13px;">';
    echo '<h2>Nenhum usuário registrado.</h2>';
    echo '</div>';
}

$stmt->close();
$conn->close();
?>