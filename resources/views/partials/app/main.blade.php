<!-- resources/views/partials/app/main.blade.php -->
@auth
    @include('partials.main.top_button_group')
@endauth

@yield('content')
