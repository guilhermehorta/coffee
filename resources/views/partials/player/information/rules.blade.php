<div id="detalhes-rules" class="detalhes">
	<p class="section-title"> 
		Regras
	</p>


	<p>
		Fase 1 - Financiamento e Abastecimentos.<br> Para a obtenção da liquidez necessária para o funcionamento da empresa pode ser solicitado um empréstimo ao banco. Não há limites ao valor dos empréstimos solicitados e estes são sempre concedidos. Empréstimos solicitados em períodos que não o primeiro estão sujeitos a uma taxa de processamento no valor de {{ $rules->game->extraLoanCost }}€. Todos os empréstimos concedidos pagam juros a uma taxa de {{ $rules->game->interestRate }}%. Este juro é devido uma só vez no fim do jogo.<br> As matérias primas podem ser adquiridas nesta fase. As ordens de aquisição têm que ser dadas em unidades inteiras. Não há restrições às quantidades de café, açucar e garrafas a adquirir. Já a quantidade de água que pode ser armazenada é limitada à capacidade dos depósitos existentes. É de notar que a água só pode ser colocada em depósitos vazios ou que já contenham água. <span class="destaque">Se for ordenada a compra de água em excesso face à capacidade de armazenagem, esta será perdida.</span><br> As matérias primas não têm prazo de validade, podem ser armazenadas indefinidamente.<br> Todos os materiais são pagos a pronto. <br> Ver o separador <span class="destaque"> Materiais </span> para mais informações sobre preços e consumos de materiais.
	</p>

	<p>
		Fase 2 - Produção e Apresentação de Preços.<br> Nesta fase pode ser ordenada a produção de refresco em qualquer depósito que contenha água.<br> A produção de refresco é feita por adição de {{ round($rules->material->sugar->amount/$rules->material->water->amount) }} unidades de açucar e {{ round($rules->material->coffee->amount/$rules->material->water->amount) }} unidades de café por cada unidade de água presente no depósito. Esta mistura irá dar origem a {{ round(1/$rules->material->water->amount) }} unidades de refresco. Não se podem misturar duas produções de refresco nem acrescentar água a um lote já produzido.<br> Lotes de refresco com dois ou mais períodos de existência, ou que tenham sido produzidos com adição de quantidades erradas de açucar ou café serão rejeitados pelo Controlo de Qualidade.<br> Em simultâneo com a ordem de produção têm que ser entregues as propostas de preços aos clientes. <span class="destaque"> NOTA: O refresco é produzido antes de serem abertas as propostas de preços. </span> A entrega de propostas não é obrigatória. O valor de cada proposta não pode exceder {{ $rules->game->sodaMaxPrice }}€. O valor mínimo é de 0.01€. A entrega de uma proposta de 0.00€ é equivalente à não entrega de proposta.
	</p>

	<p>
		Abertura de propostas.<br> Depois de todos os jogadores entregarem as suas propostas, estas são abertas e analisadas. Cada cliente irá comprar uma dose de refresco ao jogador que tiver oferecido o melhor preço. Em caso de igualdade de preços, ganhará a encomenda o jogador que tiver entregue a sua proposta mais cedo.<br>
	</p> 

	<p>
		Fase 3 - Engarrafamento e Distribuição.<br> <span class="destaque"> O refresco só é engarrafado depois de vendido.</span> Nesta fase é necessário definir a ordem pela qual serão utilizados os vários lotes disponíveis de refresco para venda. O Controlo de Qualidade não permite o engarrafamento de lotes de refresco defeituosos ou que ultrapassaram o prazo de validade.<br> É também necessário definir as rotas que serão usadas para o transporte do refresco.<br> Por cada entrega falhada será devida uma multa no valor de {{ $rules->game->lostClientFine }}€. Adicionalmente, não será permitido em futuros períodos apresentar propostas de preço aos clientes afetados.<br> O valor referente à venda do refresco é recebido a pronto.
	</p>

	<p>
		Fase 4 - Limpeza e Gestão de Quebras.<br> Nesta fase é necessário definir, depósito a depósito, se este deve ser esvaziado e limpo. A operação não tem custos mas o eventual conteúdo do tanque é perdido. <span class="destaque"> Não esquecer que só é possível adicionar água a depósitos que estejam vazios ou parcialmente cheios de água, e que só é possível produzir refresco em depósitos com água. </span>
	</p> 

	<p>
		Reciclagem.<br> As garrafas só podem ser utilizadas uma vez e terão de ser deixadas no centro de recolha de resíduos, sendo devido o pagamento de {{ $rules->game->recycleCost }}€ por cada garrafa entregue.
	</p>

	<p>
		Transporte.<br>	Os custos de transporte são todos assumidos pelas empresas produtoras de refresco, tanto na compra de materiais como nas entregas de produto aos clientes.<br> Todos os serviços de transporte são pagos a pronto.<br> Ver o separador <span class="destaque"> Veículos </span> para mais informações sobre transportes.
	</p>

	<p>
		Liquidez.<br> Se, em qualquer momento, uma empresa não tiver dinheiro disponível para as despesas assumidas, existe um mecanismo de garantia de pagamentos (destinado a proteger o credores) que é automaticamento ativado. Nessa situação, o banco liberta um empréstimo de emergência para a empresa honrar os seus compromissos. Esse empréstimo tem custos muito penalizadores para as empresas pelo que estas devem fazer tudo para os evitar.<br> A ativação deste mecanismo tem um custo fixo de {{ $rules->game->forcedLoanCost }}€ acrescido de um custo variável correspondente a {{ $rules->game->bankOverdraftRate }}% do valor necessário. <span class="destaque"> O valor deste empréstimo, acrescido das taxas fixa e variável referidas é adicionado ao valor em débito junto do banco. Estes valores estão, ainda, sujeitos ao pagamento de juros no final do último período do jogo.</span>
	</p> 

	<p>
		Final de jogo.<br> No final do exercício, os pagamentos são efetuados pela seguinte ordem. Primeiro aos trabalhadores, {{ $rules->game->workForceCost }}€ por período; de seguida ao banco , total em dívida mais juros. O resultado do jogo é o valor que sobrar (ou que estiver em falta) após estes pagamentos finais. <span class="destaque"> O valor das existências não é considerado para o apuramento final dos resultados.</span>
	</p>

</div>