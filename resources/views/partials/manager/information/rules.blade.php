<div id="detalhes-rules" class="detalhes">
	<p class="section-title"> 
        Parâmetros
    </p>
	<div class="flex-container-row">	
		<div class="grid-table ruleParameter-list" style="width: 30%">
			<div class="top-element">Parâmetro</div>
			<div class="top-element">Valor</div>
			@foreach ($rules->game as $key => $value)
				<div>{{ $key }}</div>
				<div>{{ $value }}</div>
			@endforeach
		</div>

		<div style="width: 70%">

			<p>
				<span class="destaque">productionRule:</span> define a estratégia para a produção de refresco. Aceita os seguintes valores:<br> optimized - o jogador só tem que definir qual(ais) o depósito(s) onde deseja produzir. A produção é feita de acordo com a especificação, por ordem crescente dos números dos depósitos, desde que existam as matérias primas necessárias para tal. O refresco obtido é sempre utilizável.<br> strictPlayerOrder - o jogador tem que definir quais os depósitos onde vai produzir e quais as quantidades de café e açucar que vai usar. A produção é feita de acordo com as quantidades definidas pelo jogador, por ordem crescente dos números dos depósitos, desde que existam as matérias primas necessárias para tal. O refresco obtido poderá não cumprir a especificação e não ser utilizável.
			</p>

			<p>
				<span class="destaque">bottlingRule:</span> define a estratégia para engarrafamento. Aceita os seguintes valores:<br> optimized - o engarrafamento é feito de forma automática sem necessidade de cada jogador colocar ordens. O engarrafamento é feito primeiro a partir de tanques que contenham refresco atrasado e começando pelo depósito que contenha menor quantidade. Terminando os depósitos com refresco atrasado passa para os que tenham refresco feito no período, mais uma vez ordenados por ordem crescente de quantidade.<br> lazy - também não exige ordens dos jogadores. O enchimento é feito simplesmente por ordem crescente do número dos depósitos.<br> strictPlayerOrder - o enchimento é exclusivamente feito de acordo com a ordenação que o jogador faz dos depósitos. Se o jogador não der ordens suficientes para encher todas as garrafas necessárias estas não serão cheias.<br> relaxedPlayerOrder - funciona como a versão anterior, mas se após usar os depósitos definidos pelo jogador ainda houver garrafas para encher e depósitos contendo refresco, as restantes garrafas são cheias usando uma estratégia "lazy".
			</p>

			<p>
				<span class="destaque">cleaningRule:</span> define a estratégia para limpeza dos depósitos. Aceita os seguintes valores:<br> strictPlayerOrder - é feito o esvaziamento e limpeza apenas dos tanques selecionados pelo jogador.<br> optimized - é feito o esvaziamento e limpeza dos tanques selecionados pelo jogador e ainda de todos os tanques que contenham refresco fora de prazo ou estragado.
			</p>

			<p>
				<span class="destaque">waitForMaster:</span> define o ritmo do jogo. Aceita os seguintes valores:<br> 1 - jogo entra em pausa após o processamento de cada fase e só é desbloqueado com intervenção do Master. Esta pausa tem por propósito permitir ao Master interagir com os jogadores.<br> 0 - jogo passa para a fase seguinte imediatamente após o processamento de cada fase.
			</p>

		</div>
	</div>
</div>