<div class="subsection">
    <h5 class="center"> 
        {{ __('Actual status') }}
    </h5>

    <div class="grid-table master-status">
        <div class="row-span-2 column-span-2 parameter-label">{{ __('Parameter') }}</div>
        <div class="factory-label column-start-3 column-span-{{ $game->numberOfPlayers }}">{{ __('Factory') }}</div>
        @foreach($users as $player)
            <div>{{ $player->role->id }}</div>
        @endforeach

        <div class="parameter column-start-1 column-span-2">{{ __('Debt') }}</div>
        @foreach($users as $player)
            <div>{{ $player->inventory->debt }}</div>
        @endforeach

        <div class="parameter column-start-1 column-span-2">{{ __('Cash') }}</div>
        @foreach($users as $player)
            <div>{{ $player->inventory->cash }}</div>
        @endforeach

        <div class="parameter column-start-1 column-span-2">{{ __('Sugar') }}</div>
        @foreach($users as $player)
            <div>{{ $player->inventory->sugar }}</div>
        @endforeach

        <div class="parameter column-start-1 column-span-2">{{ __('Coffee') }}</div>
        @foreach($users as $player)
            <div>{{ $player->inventory->coffee }}</div>
        @endforeach

        <div class="parameter column-start-1 column-span-2">{{ __('Bottles') }}</div>
        @foreach($users as $player)
            <div>{{ $player->inventory->bottles }}</div>
        @endforeach

        @for ($i = 1; $i <= $game->numberOfTanks; $i++)
            <div class="parameter column-start-1 row-start-{{ $i + 7 }} row-span-2">{{ __('Tank') }}</div>
            <div class="column-start-2 row-start-{{ $i + 7 }} row-span-2">{{ $i }}</div>
            @foreach($users as $player)
                <div>{{ __($player->inventory->tanks->$i->contains) }}</div>
            @endforeach
            @foreach($users as $player)
                <div>{{ $player->inventory->tanks->$i->quantity }}</div>
            @endforeach
        @endfor
    </div>

</div>