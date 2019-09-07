@switch($game->phase)
	@case('inbound')
		<h3> Encomendas efetuadas </h3>
		@break

	@case('production')
		<h3> Produção efetuada e propostas entregues </h3>
		@break

	@case('outbound')
		<h3> Entregas efetuadas </h3>
		@break

	@case('cleanup')
		<h3> Limpeza efetuada </h3>
		@break

	@default
		<h3> Pronto para a próxima fase </h3>
		
@endswitch