<h1 class="center">{{ $game->name }}</h1>
<div class="flex-container-row justify-center top-sticky">
	<a href="/games/play/{{$game->id}}" class="btn btn-default" role="button">{{ __('Refresh') }}</a>
</div>
<div class="flex-container-row justify-between">
	<h3> Período: {{ $game->period }}</h3>
	<h3> Fábrica: {{ $game->role_id }}</h3>
	@switch($game->phase)
		@case('pre')
		@case('inbound')
			<h3> Compras </h3>
			@break

		@case('postInbound')
		@case('production')
			<h3> Produção e Apresentação de Preços </h3>
			@break

		@case('postProduction')
		@case('outbound')
			<h3> Engarrafamento e Distribuição </h3>
			@break

		@case('postOutbound')
		@case('cleanup')
		@case('postCleanup')
			<h3> Gestão de Existências </h3>
			@break

		@default
			<h3> {{ $game->phase }} </h3>
			
	@endswitch
</div>

@if($game->isProcessable)
    @include('partials.player.gameHeader.waitForMasterMessage')
@endif

@if($game->isAlreadyOrdered)
    @include('partials.player.gameHeader.alreadyOrderedMessage')
@endif