<div id="detalhes-periodo-{{ $i }}" class="detalhes">
	<div class="report">
		<div class="client-label">Cliente</div>
		<div class="factory-label column-span-{{ $game->numberOfPlayers }}">Fábrica</div>
		<div class="media-label column-start-{{ $game->numberOfPlayers + 2 }}">Média</div>

		@for ($player = 1; $player <= $game->numberOfPlayers; $player++)
			<div>{{ $player }}</div>
		@endfor
	
		@for ($client = 1; $client <= $game->numberOfClients; $client++)
			<div class="column-start-1">{{ $client }}</div>
			@for ($player = 1; $player <= $game->numberOfPlayers; $player++)
				<div class="column-{{$player + 1}}
				@if (in_array($client, $lost[$player])) lost @endif @if (isset($winner[$i]) && $winner[$i][$client]['role'] == $player) winner @endif"
				>
					{{ $comercial[$i][$player][$client] }}
				</div>
			@endfor
			<div> {{ $comercial[$i]['average'][$client] }}</div>
		@endfor

		<div class="column-start-1 left">Ganhas</div>
		@for ($player = 1; $player <= $game->numberOfPlayers; $player++)
			<div>
				{{ $winner[$i]['counter'][$player] ?? 0 }}
			</div>
		@endfor
		
		<div class="column-start-1 left">Valor</div>
		@for ($player = 1; $player <= $game->numberOfPlayers; $player++)
			<div>
				{{ $winner[$i]['sum'][$player] ?? 0 }}
			</div>
		@endfor

	</div>
</div>
