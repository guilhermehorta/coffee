<h1 class="center">{{ __('Game Management') }} #{{ $game->id }} - {{ $game->name }}</h1>
<div class="flex-container-row justify-center section top-sticky">
	<a href="/games/manage/{{$game->id}}" class="btn btn-default" role="button">{{ __('Refresh') }}</a>
</div>
<div class="flex-container-row justify-between">
	<h3>{{ __('Status') }}: {{ __($game->status) }}</h3>
	<h3>{{ __('Period') }}: {{ __($game->period) }}</h3>
	@switch($game->phase)
		@case('pre')
			<h3> Pré-jogo </h3>
			@break

		@case('inbound')
			<h3> Compras </h3>
			@break

		@case('postInbound')
			<h3> Compras (Análise) </h3>
			@break

		@case('production')
			<h3> Produção e Apresentação de Preços </h3>
			@break

		@case('postProduction')
			<h3> Produção e Apresentação de Preços (Análise)</h3>
			@break

		@case('outbound')
			<h3> Engarrafamento e Distribuição </h3>
			@break

		@case('postOutbound')
			<h3> Engarrafamento e Distribuição (Análise)</h3>
			@break

		@case('cleanup')
			<h3> Gestão de Existências </h3>
			@break

		@case('postCleanup')
			<h3> Gestão de Existências (Análise) </h3>
			@break

		@default
			<h3> {{ $game->phase }} </h3>
			@break
			
	@endswitch
</div>



