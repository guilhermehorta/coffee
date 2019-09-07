<div id="detalhes-vehicles" class="detalhes">
	<p class="section-title"> 
		Veículos: Capacidade e custo de transporte
	</p>
	<div class="grid-table vehicle-list">
		<div class="top-element">Veículo</div>
		<div class="top-element">Capacidade</div>
		<div class="top-element">Custo Fixo</div>
		<div class="top-element">Custo por Km</div>
		@foreach ($rules->vehicle as $key => $value)
			<div>{{ __($key) }}</div>
			<div>
				{{ $value->capacity }}
				@if ($key == 'truck') {{ 'unidades de café e açucar' }} @endif
				@if ($key == 'tuctuc') {{ 'garrafas' }} @endif
				@if ($key == 'tanker') {{ 'unidades de água' }} @endif
			</div>
			<div>{{ $value->fixedCost }}</div>
			<div>{{ $value->variableCost }}</div>
		@endforeach
	</div>
</div>