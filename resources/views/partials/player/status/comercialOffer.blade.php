<h5 class="center">
Apresentação de propostas comerciais
</h5>
<div class="grid-table comercial-offer">
    @for ($i = 1; $i <= $game->numberOfClients ; $i++)
        <div class="flex-container-column">
            @if (!in_array($i,$inventory->lostClientList))
                <div class="client"> Cliente {{ $i }} </div>
                <div>
                    @component('components.form.numberArray', [
                        'index' => $i,
                        'name' => 'offer',
                        'showIndex' => true,
                        'object' => $order->order,
                        'attributes' => 'min="0" step="0.01" decimals="2" ',
                    ])@endcomponent
                </div>            
            @endif
        </div>
    @endfor
</div>