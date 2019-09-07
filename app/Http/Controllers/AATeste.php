<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Game;
use App\User;
use App\Participate;
use App\RuleSet;
use App\Order;

class AATeste extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $game = Game::find(15);
        $player = User::find(1);
        $roleId = $game->roleIdByPlayerId($player->id);
        $participate = $game->participateByPlayerid($player->id);
        $order = new Order;

        $rota[] = 'factory.'.($roleId);
        $rota[] = 'supplier.wholesaler';


        $order->buyBottles = 8;

        $cost = $participate->payTransport('tuctuc', $order->buyBottles, $rota, true);
        return $cost;
    }

    
}
