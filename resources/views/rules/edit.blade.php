@extends('layouts.app')

@section('content')

@php
var_dump($material);
@endphp
	<h1>Rules</h1>
	Id: {{ $id }}
	<br>
	Nome: {{ $name }}
	<ul>
		@foreach($material as $key => $value)
		<li>
			@php
			var_dump($key);
			var_dump($value);
			@endphp

			@component('components.form.number', [
                            'name' => $key.'.price',
                            'label' => 'Price',
                            'object' => $material,
                            'attributes' => 'min="0" step=".01" required',
                        ])@endcomponent
			
		</li>
	@endforeach
		
	
	</ul>

	
@endsection