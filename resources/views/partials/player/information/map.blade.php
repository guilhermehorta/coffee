@push('scripts')
	<script type="text/javascript" src="{{asset('js/map/map.js')}}"></script>
@endpush

<script>
	var clients = @json($rules->map->client);
	var factories = @json($rules->map->factory);
	var suppliers = @json($rules->map->supplier);
</script>
<div id="detalhes-map" class="detalhes">
	<p class="section-title"> 
	Mapa
	</p>
	
	<div id="map" class="flex-container-row justify-center">
		<svg id="fullmap"></svg>
	</div>
</div>
