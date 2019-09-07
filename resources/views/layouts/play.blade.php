@extends('layouts.app')

@push('scripts')
	<script type="text/javascript" src="{{asset('js/d3.js')}}"></script>

    <script type="text/javascript" src="{{asset('js/status/showAndHide.js')}}"></script>
@endpush

@section('content')

	@include('partials.player.gameHeader')
	@include('partials.player.quickInfo')
	@include('partials.player.information')
	@if ($game->lastReportablePeriod > 0)
		@include('partials.player.reports')
	@endif
	@if (!$orderHistory->isEmpty())
		@include('partials.player.orderHistory')
	@endif
	@include('partials.player.statusAndOrders')
	
@endsection