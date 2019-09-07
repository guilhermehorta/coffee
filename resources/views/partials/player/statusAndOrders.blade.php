@push('scripts')
    <script type="text/javascript" src="{{asset('js/status/d3-liquidFillGauge.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/status/status.js')}}"></script>
@endpush

<script>
    var tanks = @json($inventory->tanks);
    var rules = @json($rules->game);
    var material = @json($rules->material);
    var game_id = @json($game->id);
    var role_id = @json($game->role_id);
    var phase = @json($game->phase);
</script>

<div class="flex-container-row justify-start panel-row panel-name">
    <span> Estado do Jogo</span>
</div>

@if ($game->isOrderable)
    <form method="POST" action="/games/{{$game->phase}}Order" id='order'>
        @csrf
        {{ Form::hidden('game_id', $game->id) }}
        {{ Form::hidden('period', $game->period) }}
        {{ Form::hidden('role_id', $game->role_id) }}
        {{ Form::hidden('phase', $game->phase) }}
        @isset($order->id)
            {{ Form::hidden('order_id', $order->id) }}
        @endisset
    <h4 class="center"> 
        Situação e Ordens
    </h4>
@else
    <h4 class="center"> 
        Situação
    </h4>
@endif

@include('partials.player.status.accountAndStock')
@include('partials.player.status.deposits')

@if($game->phase == 'production')
    @include('partials.player.status.comercialOffer')
@endif

@if($game->phase == 'outbound')
    @include('partials.player.status.clientOrders')
@endif
             
@if ($game->isOrderable)
    @if($game->isAlreadyOrdered)        
        @component('components.form.button', [
            'name' => 'Alterar Ordens',
        ])@endcomponent
    @else
        @component('components.form.button', [
            'name' => 'Entregar Ordens',
        ])@endcomponent
    @endif
    </form>
@endif
