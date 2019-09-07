<div class="flex-container-row flex-wrap justify-between auto-size-items section">
    
    <div id="debt" class="flex-container-column align-items-center">
        <div id="new-debt" class="flex-container-row">
            {{ $inventory->debt }}
        </div>
    </div>

    <div id="cash" class="flex-container-column align-items-center">
        <div id="new-cash" class="flex-container-row">
            {{ $inventory->cash }}
        </div>
        
        @if($game->phase == 'inbound')
            @component('components.form.number', [
                'name' => 'loan',
                'label' => '',
                'object' => $order->order,
                'attributes' => 'min="0" step=".01" decimals="2" required',
            ])@endcomponent
        @endif
    </div>

    <div id="coffee" class="flex-container-column align-items-center">
        <div id="new-coffee" class="flex-container-row">
            {{ $inventory->coffee }}
        </div>
        @if($game->phase == 'inbound')
            @component('components.form.number', [
                'name' => 'buyCoffee',
                'label' => '',
                'object' => $order->order,
                'attributes' => 'min="0" step="1" decimals="0" required',
            ])@endcomponent
        @endif
    </div>

    <div id="sugar" class="flex-container-column align-items-center">
        <div id="new-sugar" class="flex-container-row">
            {{ $inventory->sugar }}
        </div>
        @if($game->phase == 'inbound')
            @component('components.form.number', [
                'name' => 'buySugar',
                'label' => '',
                'object' => $order->order,
                'attributes' => 'min="0" step="1" decimals="0" required',
            ])@endcomponent
        @endif
    </div>

    <div id="bottles" class="flex-container-column align-items-center">
        <div id="new-bottles" class="flex-container-row">
            {{ $inventory->bottles }}
        </div>
        @if($game->phase == 'inbound')
            @component('components.form.number', [
                'name' => 'buyBottles',
                'label' => '',
                'object' => $order->order,
                'attributes' => 'min="0" step="1" decimals="0" required',
            ])@endcomponent
        @endif
    </div>

</div>