document.addEventListener('DOMContentLoaded', function () {
            const dropdown = document.getElementById('notificacaoDropdown');
            const btnNotificacao = document.getElementById('btnNotificacao');
            const badge = document.getElementById('notificacaoBadge');
            const lista = document.getElementById('notificacaoLista');
            const btnMarcarTodasLidas = document.getElementById('marcarTodasLidas');
            
            // Suas URLs de API
            const API_URL = 'app/php/get_notificacoes.php';
            const MARK_AS_READ_URL = 'app/php/marcar_como_lida.php';

            // --- FUNÇÕES DE INTERAÇÃO ---

            // 1. Alterna o dropdown e dispara a marcação de lidas
            btnNotificacao.addEventListener('click', function (event) {
                event.stopPropagation();
                const isHidden = dropdown.classList.contains('hidden');
                dropdown.classList.toggle('hidden');
                
                // Se o dropdown for aberto (antes estava hidden), renderiza a lista e marca como lida
                if (isHidden) {
                    // Renderiza imediatamente (para o usuário ver a lista antes de sumir o destaque)
                    buscarNotificacoes(true); 
                    // Marca como lida após um pequeno delay para feedback visual
                    setTimeout(marcarTodasComoLidas, 500); 
                }
            });

            // 2. Fecha o dropdown ao clicar em qualquer lugar fora dele
            document.addEventListener('click', function (event) {
                if (!dropdown.classList.contains('hidden') && 
                    !btnNotificacao.contains(event.target) && 
                    !dropdown.contains(event.target)) {
                    
                    dropdown.classList.add('hidden');
                }
            });

            // 3. Marca todas como lidas ao clicar no link
            btnMarcarTodasLidas.addEventListener('click', function (event) {
                event.preventDefault();
                marcarTodasComoLidas();
                dropdown.classList.add('hidden');
            });

            // --- FUNÇÕES AJAX (FETCH) ---

            // Função para renderizar as notificações
            function renderNotificacoes(data) {
                lista.innerHTML = '';
                if (data.length === 0) {
                    lista.innerHTML = '<li style="text-align: center; color: #7f8c8d;">Nenhuma notificação recente.</li>';
                    return;
                }

                data.forEach(notif => {
                    const li = document.createElement('li');
                    // Checa o status 'lida' do banco (0 = não lida)
                    const isNotLida = notif.lida == 0; 
                    
                    li.className = isNotLida ? 'notificacao-item notificacao-nao-lida' : 'notificacao-item';
                    
                    let content = `
                        <p style="margin: 0; font-size: 13px;">${notif.mensagem}</p>
                        <small style="color: #95a5a6;">${notif.data_criacao_formatada}</small>
                    `;
                    
                    // Cria um link se a notificação tiver um 'link' definido
                    if (notif.link) {
                        // Normaliza o link para garantir redirecionamento correto
                        let fullLink = '';
                        try {
                            const raw = notif.link.toString();
                            if (raw.startsWith('http://') || raw.startsWith('https://') || raw.startsWith('//')) {
                                fullLink = raw;
                            } else if (raw.startsWith('/')) {
                                // caminho absoluto no servidor -> juntamos com origin
                                fullLink = window.location.origin + raw;
                            } else {
                                // caminho relativo dentro do projeto CyclePoint
                                // garante que /CyclePoint/ seja prefixado
                                fullLink = window.location.origin + '/CyclePoint/' + raw.replace(/^\/+/, '');
                            }
                        } catch (e) {
                            // fallback simples
                            fullLink = notif.link;
                        }

                        li.innerHTML = `<a href="${fullLink}" style="text-decoration: none; color: inherit; display: block;">${content}</a>`;
                    } else {
                        li.innerHTML = content;
                    }
                    
                    lista.appendChild(li);
                });
            }
            
            // Função principal de busca de notificações
            async function buscarNotificacoes(forceRender = false) {
                try {
                    const response = await fetch(API_URL);
                    const data = await response.json();
                    
                    if (data.success) {
                        // Atualiza o Badge (Contador)
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                        
                        // Renderiza a lista se o dropdown estiver aberto OU se for forçado
                        if (forceRender || !dropdown.classList.contains('hidden')) {
                            renderNotificacoes(data.data);
                        }
                    }
                } catch (error) {
                    console.error('Erro ao buscar notificações:', error);
                }
            }

            // Função para marcar todas como lidas
            async function marcarTodasComoLidas() {
                try {
                    await fetch(MARK_AS_READ_URL, {
                        method: 'POST', // O arquivo PHP espera um POST
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'acao=marcar_todas' 
                    });
                    
                    // Atualiza a interface: remove o badge e recarrega a lista sem notif não lidas
                    badge.classList.add('hidden');
                    
                    // Dispara uma nova busca forçada para atualizar o conteúdo do dropdown
                    buscarNotificacoes(true); 

                } catch (error) {
                    console.error('Erro ao marcar como lida:', error);
                }
            }

            // --- EXECUÇÃO E POLLING ---

            // 1. Executa a busca imediatamente ao carregar
            buscarNotificacoes();

            // 2. Configura a busca recorrente (Polling) a cada 10 segundos
            setInterval(buscarNotificacoes, 10000); 
        }); 