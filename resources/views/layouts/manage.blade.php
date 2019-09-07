@extends('layouts.app')

@push('scripts')
	<script type="text/javascript" src="{{asset('js/d3.js')}}"></script>

    <script type="text/javascript" src="{{asset('js/status/showAndHide.js')}}"></script>
@endpush

@section('content')

	@include('partials.manager.gameHeader')
	@include('partials.manager.playersList')
	@include('partials.manager.process')
	@if(in_array($game->status, ['running', 'finished']) && $game->phase !== 'pre')
		@include('partials.manager.information')
		@include('partials.player.reports')
		@include('partials.manager.statusAndOrders')
	@endif

@endsection
