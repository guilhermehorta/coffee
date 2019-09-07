<?php

namespace App\Http\Middleware;

use Closure;
use App\Game;
use Illuminate\Support\Facades\Auth;

class GameMaster
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
        
        if (isset($game)) if($game->master->id  == Auth::id())
        {
            return $next($request);
        }

        return redirect()->route('games.index');
    }
}
