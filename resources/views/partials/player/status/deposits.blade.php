<div id="deposits" class="flex-container-row justify-around subsection">
@foreach ($inventory->tanks as $key => $tank)
    <div id="deposit{{$key}}" class="flex-container-column">

        <h5 class="center"> Depósito {{ $key }} <h5>
        <svg id="fillgauge{{$key}}" class="tank item"></svg>
        
        
        @if($game->phase == 'production')
            @if ($tank->contains == 'water' && $tank->quantity > 0)

                @component('components.form.checkboxArray', [
                    'index' => $key,
                    'name' => 'produceSoda',
                    'label' => 'Produzir Refresco ',
                    'object' => $order->order,
                ])@endcomponent

                @if($game->needsProductionDetailsElement)
                    @component('components.form.numberArray', [
                        'index' => $key,
                        'name' => 'sugar',
                        'label' => 'Açucar ',
                        'object' => $order->order,
                        'attributes' => 'min="0" step="1" decimals="0" ',
                    ])@endcomponent
    
                    @component('components.form.numberArray', [
                        'index' => $key,
                        'name' => 'coffee',
                        'label' => 'Café ',
                        'object' => $order->order,
                        'attributes' => 'min="0" step="1" decimals="0" ',
                    ])@endcomponent
                @endif

            @endif
        @endif

        @if($game->phase == 'outbound')

            @if($game->needsBottlingElement == 1)
                @component('components.form.numberArray', [
                    'index' => $key,
                    'name' => 'newBottling',
                    'label' => 'Ordem de engarrafamento ',
                    'object' => $order->order,
                    'attributes' => ' min="0" step="1" decimals="0" '
                ])@endcomponent
            @endif

        @endif

        @if($game->phase == 'cleanup')
            @if ($tank->quantity > 0)
                @component('components.form.checkboxArray', [
                    'index' => $key,
                    'name' => 'cleanTank',
                    'label' => 'Limpar o depósito ',
                    'object' => $order->order,
                ])@endcomponent
            @endif
        @endif

        @if($game->phase == 'inbound')
             @component('components.form.numberArray', [
                'index' => $key,
                'name' => 'buyWater',
                'label' => '',
                'object' => $order->order,
                'attributes' => 'min="0" step="1" decimals="0"',
            ])@endcomponent
        @endif
        
    </div>
@endforeach
</div>