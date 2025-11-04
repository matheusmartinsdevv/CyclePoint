<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

$stmt = $conn->prepare("SELECT id_endereco_empresa, logradouro, numero, bairro, cidade, estado, pais FROM endereco_empresa WHERE id_empresa = ? ORDER BY id_endereco_empresa ASC");
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

$total_enderecos = $result->num_rows;
$primeiro_endereco = null;
if ($total_enderecos > 0) {
    $primeiro_registro = $result->fetch_assoc();
    $primeiro_endereco = $primeiro_registro['id_endereco_empresa'];
    // Volta o ponteiro para o início do resultado
    $result->data_seek(0);
}

while ($dados_endereco_empresa = $result->fetch_assoc()) {
    $id_endereco = (int)$dados_endereco_empresa['id_endereco_empresa'];
    $logradouro = htmlspecialchars($dados_endereco_empresa['logradouro']);
    $numero = htmlspecialchars($dados_endereco_empresa['numero']);
    $bairro = htmlspecialchars($dados_endereco_empresa['bairro']);
    $cidade = htmlspecialchars($dados_endereco_empresa['cidade']);
    $estado = htmlspecialchars($dados_endereco_empresa['estado']);
    $pais = htmlspecialchars($dados_endereco_empresa['pais']);

    echo '<div class="endereco" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">';
    echo '<span class="local">'. $logradouro .', '. $numero . ', '. $bairro . ' - ' . $cidade. ', '. $estado . ' - '. $pais;
    if ($id_endereco === $primeiro_endereco) {
        echo ' <span style="color: #00A693; margin-left: 10px;">(Endereço Principal)</span>';
    }
    echo '</span>';
    echo '<div>';
    echo '<button data-id="'.$id_endereco.'" class="btn btn-secondary editar-endereco" style="margin-right:5px;">Editar</button>';
    if ($total_enderecos > 1 && $id_endereco !== $primeiro_endereco) {
        echo '<button data-id="'.$id_endereco.'" class="btn btn-secondary excluir-endereco">Excluir</button>';
    }
    echo '</div>';
    echo '</div>';
};

$stmt->close();
$conn->close();

?>

<!-- Modal para edição/exclusão de endereços -->
<div id="modalEndereco" class="modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div class="modal-content" style="background:#fff; padding:20px; width:90%; max-width:600px; border-radius:6px; position:relative;">
        <button id="modalEnderecoClose" class="btn btn-secondary" style="position:absolute; right:10px; top:10px; padding:6px 10px;">Fechar</button>
        <div id="modalEnderecoBody">Carregando...</div>
    </div>
</div>

<style>
.excluir-endereco:hover {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: white !important;
}
.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}
.btn-danger:hover {
    background-color: #bb2d3b;
    border-color: #b02a37;
}
</style>

<script>
document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'modalEnderecoClose') {
        document.getElementById('modalEndereco').style.display = 'none';
    }

    if (e.target && e.target.classList.contains('editar-endereco')) {
        const id = e.target.getAttribute('data-id');
        fetch('app/php/get_endereco.php?id=' + encodeURIComponent(id))
            .then(r => r.json())
            .then(data => {
                if (!data.success) { document.getElementById('modalEnderecoBody').innerHTML = '<div style="color:#D9534F; font-weight:700;">Erro: ' + (data.message || 'Erro ao buscar endereço') + '</div>'; document.getElementById('modalEndereco').style.display = 'flex'; return; }
                const d = data.data;
                const html = `
                    <h2>Editar endereço</h2>
                    <form id="formEditarEndereco">
                        <input type="hidden" name="id_endereco" value="${d.id_endereco_empresa}">
                        <div><label>Logradouro<br><input name="logradouro" class="form-control" value="${d.logradouro}" required></label></div>
                        <div><label>Número<br><input name="numero" class="form-control" value="${d.numero}" required></label></div>
                        <div><label>Bairro<br><input name="bairro" class="form-control" value="${d.bairro}"></label></div>
                        <div><label>Cidade<br><input name="cidade" class="form-control" value="${d.cidade}" required></label></div>
                        <div><label>Estado<br><input name="estado" class="form-control" value="${d.estado}" required></label></div>
                        <div><label>País<br><input name="pais" class="form-control" value="${d.pais}" required></label></div>
                        <div style="margin-top:10px;"><button type="submit" class="btn btn-primary">Salvar</button></div>
                    </form>
                `;
                document.getElementById('modalEnderecoBody').innerHTML = html;
                document.getElementById('modalEndereco').style.display = 'flex';

                document.getElementById('formEditarEndereco').onsubmit = function(ev) {
                    ev.preventDefault();
                    this.action = 'app/php/update_endereco.php';
                    this.method = 'POST';
                    this.submit();
                };
            })
            .catch(err => { console.error(err); document.getElementById('modalEnderecoBody').innerHTML = '<div style="color:#D9534F; font-weight:700;">Erro de rede ao buscar endereço.</div>'; document.getElementById('modalEndereco').style.display = 'flex'; });
    }

    if (e.target && e.target.classList.contains('excluir-endereco')) {
        const id = e.target.getAttribute('data-id');
        const html = `
            <div style="text-align: center;">
                <h2 style="color: #dc3545;">Confirmar Exclusão</h2>
                <p style="margin: 20px 0;">Tem certeza que deseja excluir este endereço?</p>
                <p style="color: #666; font-size: 14px;">Esta ação não pode ser desfeita.</p>
                <div style="margin-top: 20px;">
                    <button id="confirmarExclusaoEndereco" class="btn btn-danger" style="margin-right: 10px;">Excluir</button>
                    <button id="cancelarExclusaoEndereco" class="btn btn-secondary">Cancelar</button>
                </div>
            </div>
        `;
        document.getElementById('modalEnderecoBody').innerHTML = html;
        document.getElementById('modalEndereco').style.display = 'flex';

        document.getElementById('confirmarExclusaoEndereco').onclick = function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'app/php/delete_endereco.php';
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = 'id_endereco'; input.value = id;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        };

        document.getElementById('cancelarExclusaoEndereco').onclick = function() {
            document.getElementById('modalEndereco').style.display = 'none';
        };
    }
});
</script>
