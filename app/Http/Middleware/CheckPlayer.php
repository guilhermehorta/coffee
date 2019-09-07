<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Game;

class CheckPlayer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = $request->route('id');
        $game = Game::find($id);
        
        if (isset($game)) if ($game->players->contains(Auth::user()))
        {
            return $next($request);
        }

        return redirect()->route('games.index');
    }
}