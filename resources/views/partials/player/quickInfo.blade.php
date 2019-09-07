<div class="quick-info">

    @switch($game->phase)
        @case('inbound')
            <p class="section-title">
                Instruções rápidas
            </p>
            <p>
                Nesta fase pode pedir dinheiro ao banco para fazer face às despesas inerentes ao funcionamento da empresa. O valor necessário deve ser colocado na caixa correspondente.<br> Nesta fase também deve comprar todos os materiais necessários. As quantidades a comprar são colocadas nas caixas respetivas.<br> Quando tiver selecionado todos os valores carregue no botão "Entregar Ordens."
            </p>
            @break

        @case('production')
           <p class="section-title">
                Instruções rápidas
            </p>
             <p>
                Para produzir refresco num depósito, marque a caixa que se encontra por baixo desse depósito. Indique depois as quantidades de café e açucar a usar nessa produção.<br> Se pretender apresentar uma proposta a um cliente terá que preencher a caixa identificada com o número do cliente com o preço pretendido. <span class="destaque"> Ganha o cliente quem apresentar o preço mais baixo.</span><br> Quando tiver selecionado todos os valores carregue no botão "Entregar Ordens." <br> Código de cores dos depósitos:
            </p>
            <ul>
                <li> Azul: Contém água.</li>
                <li> Castanho com rebordo verde: Contém refresco acabado de fazer.</li>
                <li> Castanho com rebordo laranja: Contém refresco feito em período anterior mas ainda dentro de validade.</li>
                <li> Preto com rebordo vermelho: Contém refresco fora de validade ou estragado.</li>
            </ul> 
            @break

        @case('outbound')
            <p class="section-title">
                Instruções rápidas
            </p>
            <p>
                Defina a ordem pela qual vão ser utilizados os depósitos para o enchimento, indicando um valor na caixa por baixo de cada depósito. Colocar o número 1 num depósito significa que esse vai ser o primeiro depósito a ser utilizado. Colocar um 2, significa que esse depósito só será usado após o esvaziamento do primeiro.<br> Defina as rotas para entrega do refresco na caixa identificada como "Rotas". Ao construir uma rota é necessário indicar quais os clientes incluidos nessa rotas e qual a ordem pela qual são visitados.<br> Exemplo: para definir duas rotas, uma que visita o cliente 1, seguido do 4 e depois do 10, e uma outra que visita primeiro o cliente 9 e depois o cliente 3 deve escrever: " 1, 4, 10 ; 9, 3". Note que vírgula separa clientes dentro de uma rota e ponto e vírgula separa uma rota de outra.<br> Quando tiver selecionado todos os valores carregue no botão "Entregar Ordens."
            </p>
            @break

        @case('cleanup')
            <p class="section-title">
                Instruções rápidas
            </p>
            <p>
                Indique, marcando a caixa respetiva, se cada depósito deve ser esvaziado e limpo.<br> Quando tiver selecionado todos os valores carregue no botão "Entregar Ordens."
            </p>
            @break

        @default
            <p>
                
            </p>

    @endswitch

</div>


