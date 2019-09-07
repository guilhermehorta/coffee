<div class="subsection">
    <h5 class="center"> 
        @if ($game->phase == $game->activePhase) 
            {{ __('Actual orders') }}
        @else
            {{ __('Processed orders') }}
        @endif
    </h5>


    <div class="grid-table master-orders">
        <div class="row-span-2 column-span-2 parameter-label">{{ __('Parameter') }}</div>
        <div class="factory-label column-start-3 column-span-{{ $game->numberOfPlayers * 4 }}">{{ __('Factory') }}</div>
        @foreach($users as $player)
            <div class="column-span-4">{{ $player->role->id }}</div>
        @endforeach

        @if($game->activePhase == 'inbound')
            <div class="parameter column-start-1 column-span-2">{{ __('Ask for Loan') }}</div>
            @foreach($users as $player)
                <div class="column-span-4">{{ $player->order->loan ?? 0}}</div>
            @endforeach

            <div class="parameter column-start-1 column-span-2">{{ __('Buy sugar') }}</div>
            @foreach($users as $player)
                <div class="column-span-4">{{ $player->order->buySugar ?? 0}}</div>
            @endforeach

            <div class="parameter column-start-1 column-span-2">{{ __('Buy coffee') }}</div>
            @foreach($users as $player)
                <div class="column-span-4">{{ $player->order->buyCoffee ?? 0}}</div>
            @endforeach

            <div class="parameter column-start-1 column-span-2">{{ __('Buy bottles') }}</div>
            @foreach($users as $player)
                <div class="column-span-4">{{ $player->order->buyBottles ?? 0}}</div>
            @endforeach
        @endif

        @for ($i = 1; $i <= $game->numberOfTanks; $i++)
            <div class="parameter column-start-1 row-span-3">{{ __('Tank') }}</div>
            <div class="column-start-2 row-span-3">{{ $i }}</div>
            @if($game->activePhase == 'inbound')
                @foreach($users as $player)
                    <div class="row-span-3 column-span-3">{{ __('Buy water') }}</div>
                    <div class="row-span-3 column-span-1">{{ $player->order->buyWater->$i ?? 0}}</div> 
                @endforeach
            @endif

            @if ($game->activePhase == 'production')
                @foreach ($users as $player)
                    <div class="column-span-3">{{ __('Produce soda') }}</div>
                    <div class="column-span-1">{{ !empty($player->order->produceSoda->$i) ? __('Yes') : __('No') }}</div>
                @endforeach
                @foreach ($users as $player)
                    <div class="column-span-3">{{ __('Add sugar') }}</div>
                    <div class="column-span-1">{{ $player->order->sugar->$i ?? 0}}</div>
                @endforeach
                @foreach ($users as $player)
                    <div class="column-span-3">{{ __('Add coffee') }}</div>
                    <div class="column-span-1">{{ $player->order->coffee->$i ?? 0}}</div>
                @endforeach
            @endif

            @if ($game->activePhase == 'outbound')
                @foreach ($users as $player)
                    <div class="row-span-3 column-span-3">{{ __('Bottling order') }}</div>
                    <div class="row-span-3 column-span-1">{{ $player->order->newBottling->$i ?? ''}}</div>
                @endforeach
            @endif

            @if ($game->activePhase == 'cleanup')
                @foreach ($users as $player)
                    <div class="row-span-3 column-span-4">@if (!empty($player->order->cleanTank->$i)) {{ __('Clean') }} @else {{ __('Do not clean') }} @endif</div>
                @endforeach
            @endif
        @endfor

        @if($game->activePhase == 'outbound')
            <div class="parameter column-start-1 column-span-2">{{ __('Distribuition Routes') }}</div>
            @foreach($users as $player)
                <div class="column-span-4">{{ $player->order->routes ?? 0}}</div>
            @endforeach
        @endif        
    </div>
</div>