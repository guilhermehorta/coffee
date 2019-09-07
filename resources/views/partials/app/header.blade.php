<!-- resources/views/partials/app/header.blade.php -->
<header id="header" class="flex-container-row justify-center">
    <span class="invisible">
        @guest        
            <a href="{{ route('login') }}">
                <button type="button" class="btn btn-default btn-sm">
                    {{ __('Login') }}
                </button>
            </a>
            @if (Route::has('register'))        
                <a href="{{ route('register') }}">
                    <button type="button" class="btn btn-default btn-sm">
                        {{ __('Register') }}
                    </button>
                </a>
            @endif
        @else                
            <a href="{{ route('logout') }}"
            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <button type="button" class="btn btn-default btn-sm">
                    {{ __('Logout') }}
                </button>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
                 
        @endguest
    </span>
    <span class="invisible grow-1">
        @auth
            {{ Auth::user()->name }}
        @endauth
    </span>
    <div>
    </div>
    <div id="logo">
        Jogo do Refresco
    </div>

    <span class="grow-1 right">
        @auth
            {{ Auth::user()->username }}
        @endauth
    </span>

    <span>
        @guest        
            <a href="{{ route('login') }}">
                <button type="button" class="btn btn-default btn-sm">
                    {{ __('Login') }}
                </button>
            </a>
            @if (Route::has('register'))        
                <a href="{{ route('register') }}">
                    <button type="button" class="btn btn-default btn-sm">
                        {{ __('Register') }}
                    </button>
                </a>
            @endif
        @else                
            <a href="{{ route('logout') }}"
            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <button type="button" class="btn btn-default btn-sm">
                    {{ __('Logout') }}
                </button>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
                 
        @endguest
    </span>
</header>
