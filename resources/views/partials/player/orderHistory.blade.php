<div id="historicOrder-group" class="section">
	@include('partials.player.orderHistory.controlPanel')
	@foreach ($orderHistory as $key => $historicOrder)
		@include('partials.player.orderHistory.historicOrder')
	@endforeach
</div>