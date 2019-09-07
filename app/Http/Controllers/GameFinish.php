<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;

class GameFinish extends Controller
{
     /**
     * Este controlador controla o processamento final do jogo.
     *
     */

     /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Este controlador só é acessível a utilizadores registados
        $this->middleware('auth');

        // Este controlador só deve ser acessível ao GameMaster
        $this->middleware('master');
    }

     /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {
        // Get the game
        $game = Game::find($id);
        // Get the players that are not GameMasters
        $players = $game->players;

        foreach ($players as $player)
        {
            // O roleId do player e o seu participate Model
            $roleId = $game->roleIdByPlayerId($player->id);
            $participate = $game->participateByPlayerid($player->id);

            // Pagar salários
            $participate->payWages($game->period);

            // Pagar empréstimo
            $participate->payLoan();

            //Gravar em BD
            $participate->save();
        }

        $game->update(['status' => 'finished']);

        return redirect()->route('games.manage', $id);
    }
}