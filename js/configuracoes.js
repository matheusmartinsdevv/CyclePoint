// const nome_categoria = document.querySelector(".nome_categoria");
// const descricao_categoria = document.querySelector(".descricao_categoria");

// nome_categoria.innerHTML = "Novo Conteúdo";
// descricao_categoria.innerHTML = "Descricaooao aoaooao oao"

// ------------------------------------------------------

document.addEventListener('DOMContentLoaded', function() {
            fetch('../app/php/exibirCategoria.php') // Envia a requisição para o PHP
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