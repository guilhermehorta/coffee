<div id="detalhes-historicOrder-{{ $key }}" class="detalhes">
	<div class="grid-table historicOrder">
		<div class="parameter-label">Parâmetro</div>
		<div class="value-label">Valor</div>

		@foreach ($historicOrder->order as $parameter => $value)
			@if ($parameter != 'offer')
				<div>{{ __($parameter) }}</div>
				@if(is_object($value))
				{{--<div>{{ json_encode($value) }}</div>--}}
				<div>@foreach ($value as $key => $item)
					{{ ' | ' }} {{ $key }} {{ ' => ' }}{{ $item }}{{ ' |' }}
					@endforeach
				</div>
				@else
				<div>{{ $value }}</div>
				@endif
			@endif
		@endforeach

	</div>
</div>
