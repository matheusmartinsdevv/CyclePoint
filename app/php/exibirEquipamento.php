<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

// Busca equipamentos ativos (status_equipamento IS NULL)
$stmt = $conn->prepare("SELECT id_equipamento, nome_equipamento, fabricante, modelo, vida_util_meses, status_equipamento FROM equipamento WHERE id_empresa = ? AND status_equipamento IS NULL");
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($dados_equipamento = $result->fetch_assoc()) {
        $id_equipamento = (int)$dados_equipamento['id_equipamento'];
        $nome_equipamento = htmlspecialchars($dados_equipamento['nome_equipamento']);
        $fabricante = htmlspecialchars($dados_equipamento['fabricante']);
        $modelo = htmlspecialchars($dados_equipamento['modelo']);
        $vida_util_meses = htmlspecialchars($dados_equipamento['vida_util_meses']);

        echo '<div class="equipamento">';
        echo '<span class="equipamento">'. $nome_equipamento .'</span>';
        echo '<div style="display:flex;">';
        echo ' <button data-id="'.$id_equipamento.'" class="btn btn-secondary ver-detalhes">Ver detalhes</button>';
        echo ' <button data-id="'.$id_equipamento.'" class="btn btn-secondary editar-equipamento">Editar</button>';
        echo ' <button data-id="'.$id_equipamento.'" class="btn btn-secondary excluir-equipamento">Excluir</button>';
        echo '</div>';
        echo '</div>';
    }
} else {
    // SE NÃO ENCONTROU DADOS
    echo '<div class="message-info" style="text-align: center;font-size: 13px;">';
    echo '<h2>Nenhum equipamento registrado.</h2>';
    echo '</div>';
}

$stmt->close();
$conn->close();

// Modal e scripts para ver/editar equipamento (inseridos aqui porque este arquivo é incluído na aba de equipamentos)
?>

<div id="modalEquipamento" class="modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div class="modal-content" style="background:#fff; padding:20px; width:90%; max-width:600px; border-radius:6px; position:relative;">
        <button id="modalClose" class="btn btn-secondary" style="position:absolute; right:10px; top:10px; padding:6px 10px;">Fechar</button>
    <div id="modalBody">Carregando...</div>
  </div>
</div>

<script>
// Estilo para o botão de excluir
const style = document.createElement('style');
style.textContent = `
    .excluir-equipamento:hover {
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
`;
document.head.appendChild(style);

document.addEventListener('click', function(e){
    // Ver detalhes
    if (e.target && e.target.classList.contains('ver-detalhes')) {
        const id = e.target.getAttribute('data-id');
        fetch('app/php/get_equipamento.php?id=' + encodeURIComponent(id))
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    // mostra erro inline no modal
                    document.getElementById('modalBody').innerHTML = '<div style="color:#D9534F; font-weight:700;">Erro: ' + (data.message || 'Erro ao buscar equipamento') + '</div>';
                    document.getElementById('modalEquipamento').style.display = 'flex';
                    return;
                }
                const eq = data.data;
                const html = `\
                    <h2>Detalhes do equipamento</h2>\
                    <p><strong>Nome:</strong> ${eq.nome_equipamento}</p>\
                    <p><strong>Fabricante:</strong> ${eq.fabricante}</p>\
                    <p><strong>Modelo:</strong> ${eq.modelo}</p>\
                    <p><strong>Vida útil (meses):</strong> ${eq.vida_util_meses}</p>\
                    <p><strong>Status:</strong> ${eq.status_equipamento === null ? 'ativo' : eq.status_equipamento}</p>\
                `;
                document.getElementById('modalBody').innerHTML = html;
                document.getElementById('modalEquipamento').style.display = 'flex';
            }).catch(err=>{console.error(err); document.getElementById('modalBody').innerHTML = '<div style="color:#D9534F; font-weight:700;">Erro de rede ao buscar equipamento.</div>'; document.getElementById('modalEquipamento').style.display = 'flex';});
    }

    // Excluir
    if (e.target && e.target.classList.contains('excluir-equipamento')) {
        const id = e.target.getAttribute('data-id');
        const html = `
            <div style="text-align: center;">
                <h2 style="color: #dc3545;">Confirmar Exclusão</h2>
                <p style="margin: 20px 0;">Tem certeza que deseja excluir este equipamento?</p>
                <p style="color: #666; font-size: 14px;">Esta ação não pode ser desfeita.</p>
                <div style="margin-top: 20px;">
                    <button id="confirmarExclusao" class="btn btn-danger" style="margin-right: 10px;">Excluir</button>
                </div>
            </div>
        `;
        document.getElementById('modalBody').innerHTML = html;
        document.getElementById('modalEquipamento').style.display = 'flex';

        // Handler para confirmar exclusão
        document.getElementById('confirmarExclusao').onclick = function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'app/php/delete_equipamento.php';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id_equipamento';
            input.value = id;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        };

        // Handler para cancelar
        document.getElementById('cancelarExclusao').onclick = function() {
            document.getElementById('modalEquipamento').style.display = 'none';
        };
    }

    // Editar
    if (e.target && e.target.classList.contains('editar-equipamento')) {
        const id = e.target.getAttribute('data-id');
        fetch('app/php/get_equipamento.php?id=' + encodeURIComponent(id))
            .then(r => r.json())
            .then(data => {
                if (!data.success) { document.getElementById('modalBody').innerHTML = '<div style="color:#D9534F; font-weight:700;">Erro: ' + (data.message || 'Erro ao buscar equipamento') + '</div>'; document.getElementById('modalEquipamento').style.display = 'flex'; return; }
                const eq = data.data;
                const html = `\
                    <h2>Editar equipamento</h2>\
                    <form id="formEditarEquipamento">\
                        <input type="hidden" name="id_equipamento" value="${eq.id_equipamento}">\
                        <div><label>Nome<br><input name="nome_equipamento" class="editar-form" value="${eq.nome_equipamento}" required></label></div>\
                        <div><label>Fabricante<br><input name="fabricante" class="editar-form" value="${eq.fabricante}"></label></div>\
                        <div><label>Modelo<br><input name="modelo" class="editar-form" value="${eq.modelo}"></label></div>\
                        <div><label>Vida útil (meses)<br><input name="vida_util_meses" class="editar-form" type="number" value="${eq.vida_util_meses}"></label></div>\
                        <div style="margin-top:10px;"><button type="submit" class="btn btn-primary">Salvar</button></div>\
                    </form>\
                `;
                document.getElementById('modalBody').innerHTML = html;
                document.getElementById('modalEquipamento').style.display = 'flex';
                // attach submit
                const form = document.getElementById('formEditarEquipamento');
                form.addEventListener('submit', function(ev){
                    ev.preventDefault();
                    const fd = new FormData(form);
                    fetch('app/php/update_equipamento.php', { method: 'POST', body: fd })
                        .then(r => r.json())
                        .then(resp => {
                            // O endpoint agora grava mensagens em sessão; apenas recarregamos para que a mensagem seja exibida
                            window.location.reload();
                        }).catch(err=>{console.error(err); document.getElementById('modalBody').innerHTML = '<div style="color:#D9534F; font-weight:700;">Erro de rede ao atualizar equipamento.</div>';});
                });
            }).catch(err=>{console.error(err); alert('Erro ao buscar equipamento');});
    }
});

document.getElementById('modalClose').addEventListener('click', function(){
    document.getElementById('modalEquipamento').style.display = 'none';
});

</script>
<?php


