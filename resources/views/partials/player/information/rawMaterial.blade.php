<div id="detalhes-materials" class="detalhes">
	<p class="section-title"> 
		Matéria prima: Custo e consumo por unidade de refresco
	</p>
	<div class="grid-table material-list">
		<div class="top-element">Material</div>
		<div class="top-element">Preço</div>
		<div class="top-element">Quantidade por unidade de refresco</div>
		@foreach ($rules->material as $key => $value)
			<div>{{ __($key) }}</div>
			<div>{{ $value->price }}</div>
			<div>{{ $value->amountRat }}</div>
		@endforeach
	</div>
</div>