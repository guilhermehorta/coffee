@push('scripts')
    <script type="text/javascript" src="{{asset('js/status/Sortable.min.js')}}"></script>
@endpush

<h5 class="center"> 
Clientes a abastecer
</h5>

<div id="rota_0" class="grid-table won-client-list">
	<div class="top-row">Cliente</div>
{{--	<div class="bottom-row">Preço</div> --}}
	
	@foreach($clientOrders as $key => $value)
		<div class="top-row route-item">{{ $key }}</div>
{{--		<div class="bottom-row">{{ $value }}</div> --}}
	@endforeach
</div>

<h5 class="center"> 
    {{ __('Enter distribuition routes') }}
</h5>


<div id="rotas">
</div>

<div class="flex-container-row justify-around" style="display:none">
	@component('components.form.text', [
	    'name' => 'routes',
	    'label' => 'Rotas',
	    'object' => $order->order,
	])@endcomponent	
</div>
<div>
	Custo estimado:
	<span id="transport-cost-estimate">
	0
	</span>
</div>
