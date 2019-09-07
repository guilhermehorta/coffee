<div class="subsection">
    <h5 class="center"> 
	    Relatório de processamento
	</h5>

	<div class="grid-table master-report">
		<div class="row-span-2 column-span-2 parameter-label">{{ __('Parameter') }}</div>
        <div class="factory-label column-start-3 column-span-{{ $game->numberOfPlayers }}">{{ __('Factory') }}</div>
        @foreach($users as $player)
            <div>{{ $player->role->id }}</div>
        @endforeach

		@foreach($game->masterReportParameterList as $parameter)
			<div class="column-span-2 @if($game->activePhase == 'production') row-span-4 @endif">{{ __($parameter) }}</div>
			@foreach($users as $player)
				<div class="@if($game->activePhase == 'production') row-span-4 @endif">{{ $player->report->$parameter ?? "" }}</div>
			@endforeach
		@endforeach
	</div>