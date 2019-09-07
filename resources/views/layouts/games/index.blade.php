@extends('layouts.app')

@section('content')

	<h1 class="center">JOGOS</h1>

	<div id="games-index" class="grid-table">
		<div class="top-element justify-start">{{ __('Name') }}</div>
		<div class="top-element justify-start">{{ __('Master') }}</div>
		<div class="top-element justify-start">{{ __('Status') }}</div>
		<div class="top-element">{{ __('Period') }} - {{ __('Phase') }}</div>
		<div class="top-element">{{ __('Action') }}</div>

		@foreach($games as $game)
			<div class="justify-start">{{ __($game->name) }}</div>
			<div class="justify-start">{{ __($game->gestor) }}</div>
			<div class="justify-start">{{ __($game->status) }}</div>
			<div>{{ __($game->period) }} - {{ __($game->phase) }}</div>
			<div style="background-color: inherit">
				@isset($game->joinable)
					<a href="/games/join/{{$game->id}}" class="button joined-button center">{{ __('Join') }}</a>
				@endisset
				@isset($game->leaveable)
					<a href="/games/leave/{{$game->id}}" class="button joined-button center">{{ __('Leave') }}</a>
				@endisset
				@if($game->playable)
					<a href="/games/play/{{$game->id}}" class="button joined-button center">{{ __('Play') }}</a>
				@endif
				@isset($game->manageable)
					<a href="/games/manage/{{$game->id}}" class="button joined-button center">{{ __('Manage') }}</a>
				@endisset
			</div>
		@endforeach

		@if(Auth::user()->is_admin)			
			<a href="/games/create" class="button center">{{ __('New Game') }}</a>
		@endif

	</div>

@endsection