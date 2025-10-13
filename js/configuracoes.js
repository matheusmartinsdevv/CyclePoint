
document.addEventListener('DOMContentLoaded', function() {
            fetch('../app/php/exibirCategoria.php')
                .then(response => response.json()) // Converte a resposta para JSON
                .then(data => {
                    document.getElementById('.exibir_categorias').innerHTML = `
                        <span class="nome_categoria">${data.nome}</span>
                        <span class="descricao_categoria">${data.descricao}</span>
                        <hr class="normal-margin">
                    `;
                })
                .catch(error => console.error('Erro:', error));
        });