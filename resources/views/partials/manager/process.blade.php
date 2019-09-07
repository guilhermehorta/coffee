<div class="section">
	<h4 class="center">
	@isset($game->usersMissing)
		Faltam {{ $game->usersMissing }} jogadores
	@endisset
	@isset($game->ordersMissing)
		Faltam {{ $game->ordersMissing }} ordens
	@endisset
	</h4>
	@isset($game->processable)
	 	<a class="btn btn-primary" href="/games/process/{{$game->id}}" role="button">	Processar fase</a>
	@endisset
	@isset($game->endable)
		<a class="btn btn-primary" href="/games/finish/{{$game->id}}" role="button">Terminar jogo</a>
	@endisset
</div>
