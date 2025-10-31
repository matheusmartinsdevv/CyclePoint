<?php

$conn = mysqli_connect("localhost:3306", "root", "", "banco_cyclepoint");

if (isset($_SESSION['id_empresa'])) {
        $id_empresa = $_SESSION['id_empresa'];
    } else {
        die("Erro: ID da empresa não encontrado na sessão."); 
    }

$stmt = $conn->prepare("SELECT id_categoria, nome_categoria, descricao FROM categoria WHERE id_empresa = ?");
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($dados_categoria = $result->fetch_assoc()) {
        $id_categoria = (int)$dados_categoria['id_categoria'];
        $nome_categoria = htmlspecialchars($dados_categoria['nome_categoria']);
        $descricao_categoria = htmlspecialchars($dados_categoria['descricao']);

        echo '<div class="exibe_categoria" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">';
        echo '  <div style="flex: 1;">';
        echo '    <span style="padding: 0px 15px; font-weight: bold;">' . $nome_categoria . '</span>';
        echo '    <span style="padding: 0px 15px; color: #666;">' . $descricao_categoria . '</span>';
        echo '  </div>';
        echo '  <div>';
        echo '    <button data-id="'.$id_categoria.'" class="btn btn-secondary editar-categoria" style="margin-right: 5px;">Editar</button>';
        echo '    <button data-id="'.$id_categoria.'" class="btn btn-secondary excluir-categoria">Excluir</button>';
        echo '  </div>';
        echo '</div>';
    };
} else {
    echo '<div class="message-info" style="text-align: center;font-size: 13px;">';
    echo '<h2>Nenhuma categoria registrada.</h2>';
    echo '</div>';
}

$stmt->close();
$conn->close();

// Modal para edição/exclusão
?>
<div id="modalCategoria" class="modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div class="modal-content" style="background:#fff; padding:20px; width:90%; max-width:600px; border-radius:6px; position:relative;">
        <button id="modalClose" class="btn btn-secondary" style="position:absolute; right:10px; top:10px; padding:6px 10px;">Fechar</button>
        <div id="modalBody">Carregando...</div>
    </div>
</div>

<style>
.excluir-categoria:hover {
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
    // Fechar modal
    if (e.target.id === 'modalClose') {
        document.getElementById('modalCategoria').style.display = 'none';
    }

    // Editar categoria
    if (e.target.classList.contains('editar-categoria')) {
        const id = e.target.getAttribute('data-id');
        fetch('app/php/get_categoria.php?id=' + encodeURIComponent(id))
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    document.getElementById('modalBody').innerHTML = '<div style="color:#D9534F; font-weight:700;">Erro: ' + (data.message || 'Erro ao buscar categoria') + '</div>';
                    document.getElementById('modalCategoria').style.display = 'flex';
                    return;
                }
                const cat = data.data;
                const html = `
                    <h2>Editar categoria</h2>
                    <form id="formEditarCategoria">
                        <input type="hidden" name="id_categoria" value="${cat.id_categoria}">
                        <div><label>Nome<br><input name="nome_categoria" class="form-control editar-form" value="${cat.nome_categoria}" required></label></div>
                        <div><label>Descrição<br><input name="descricao" class="form-control editar-form" value="${cat.descricao}" required></label></div>
                        <div style="margin-top:10px;"><button type="submit" class="btn btn-primary">Salvar</button></div>
                    </form>
                `;
                document.getElementById('modalBody').innerHTML = html;
                document.getElementById('modalCategoria').style.display = 'flex';

                // Handler do formulário
                document.getElementById('formEditarCategoria').onsubmit = function(e) {
                    e.preventDefault();
                    this.action = 'app/php/update_categoria.php';
                    this.method = 'POST';
                    this.submit();
                };
            })
            .catch(err => {
                console.error(err);
                document.getElementById('modalBody').innerHTML = '<div style="color:#D9534F; font-weight:700;">Erro de rede ao buscar categoria.</div>';
                document.getElementById('modalCategoria').style.display = 'flex';
            });
    }

    // Excluir categoria
    if (e.target.classList.contains('excluir-categoria')) {
        const id = e.target.getAttribute('data-id');
        const html = `
            <div style="text-align: center;">
                <h2 style="color: #dc3545;">Confirmar Exclusão</h2>
                <p style="margin: 20px 0;">Tem certeza que deseja excluir esta categoria?</p>
                <p style="color: #666; font-size: 14px;">Esta ação não pode ser desfeita.</p>
                <div style="margin-top: 20px;">
                    <button id="confirmarExclusao" class="btn btn-danger" style="margin-right: 10px;">Excluir</button>
                    <button id="cancelarExclusao" class="btn btn-secondary">Cancelar</button>
                </div>
            </div>
        `;
        document.getElementById('modalBody').innerHTML = html;
        document.getElementById('modalCategoria').style.display = 'flex';

        // Handler para confirmar exclusão
        document.getElementById('confirmarExclusao').onclick = function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'app/php/delete_categoria.php';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id_categoria';
            input.value = id;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        };

        // Handler para cancelar
        document.getElementById('cancelarExclusao').onclick = function() {
            document.getElementById('modalCategoria').style.display = 'none';
        };
    }
});
</script>

