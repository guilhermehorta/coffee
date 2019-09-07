<?php

namespace App\Http\Middleware;

use Closure;
use App\Game;

class CheckPhasePeriod
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
        $game = Game::find($request->game_id);
        if ( $request->phase == $game->phase && $request->period == $game->period)
        {
            return $next($request);
        } 
        
        //return redirect('home');

        return redirect()->route('games.play', ['id' => $request->game_id]);
    }
}
