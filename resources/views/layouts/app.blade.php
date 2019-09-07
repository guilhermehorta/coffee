<!-- resources/views/layouts/app.blade.php -->
@extends('layouts.master')

@prepend('styles')
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Knewave' rel='stylesheet'>
@endprepend

@prepend('scripts')
    <script src="{{ asset('js/jquery-3.4.1.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
@endprepend

@section('app')
<div id="app-container">
    @include('partials.app.header')
    @include('partials.app.navigation')
    <main id="main-container" class="flex-container-column" role="main">
        @include('cookieConsent::index')
        @include('partials.app.main')
    </main>
    <aside>
        @include('partials.app.ads')
    </aside>
    <footer>
        @include('partials.app.footer')
    </footer>
</div>
@endsection



