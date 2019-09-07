<div class="flex-container-row justify-between panel-row">
	<div class="flex-container-row justify-start align-items-center panel-name">
		<span> Histórico de Ordens</span>
	</div>

	<div id="historic-order-list" class="grid-table panel-list">
		@foreach ($orderHistory as $key => $historicOrder)
			<span class="panel-btn" id="btn-detalhes-historicOrder-{{$key}}" data-name="historicOrder-{{$key}}" data-group="historicOrder-group">
				{{$historicOrder->period}}-{{ __($historicOrder->phase) }}
			</span>
		@endforeach
	</div>
</div>