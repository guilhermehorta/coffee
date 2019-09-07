<div class="grid-table players-list">
	<div class="player-label first-column">{{ __('Player') }}</div>
	@if ($game->status === 'running' || $game->status === 'finished')
		<div class="factory-label second-column">{{ __('Factory') }}</div>
	@endif
	@if (!in_array($game->phase, ['pre', 'postInbound', 'postProduction', 'postOutbound', 'postCleanup']) && ($game->status ==='running'))
		<div class="order-label third-column">{{ __('Orders') }}</div>
	@endif
	
	@foreach($users as $user)
		<div class="first-column">{{ $user->username }}</div>
		@if ($game->status === 'running' || $game->status === 'finished')
			<div class="second-column">{{ $user->role->id }}</div>
		@endif
		@if (!in_array($game->phase, ['pre', 'postInbound', 'postProduction', 'postOutbound', 'postCleanup']) && ($game->status ==='running'))
			<div class="third-column">@if($user->hasOrders) {{ __('Ready') }} @else {{ __('Not Ready') }} @endif</div>
		@endif
	@endforeach
</div>
