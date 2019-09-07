@extends('layouts.app')

@section('content')

	<h1>Rules</h1>
	<ul>
		<li>
			Id Nome
		</li>
	@foreach($rules as $rule)
		<li>
			<a href="/rules/{{ $rule->id }}/edit">{{ $rule->id }}</a>
			<a href="/rules/{{ $rule->id }}/edit">{{ $rule->name }}</a>
		</li>
	@endforeach
	</ul>

	@if(Auth::user()->is_admin)
	<a href="/rules/create">Criar novo conjunto de regras</a>
	@endif

@endsection