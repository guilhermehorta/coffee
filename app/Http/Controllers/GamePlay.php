<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Game;
//use App\User;
use App\Participate;
use App\Order;
use App\ClientOrder;
use App\Http\Traits\AuxFunc;

class GamePlay extends Controller
{
    /**
     * Este controlador prepara a página de jogo para o jogador.
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

        // Este controlador só é acessível a jogadores do jogo
        $this->middleware('player');
    }

    /**
     * Handle the incoming request.
     * This controller returns the game page for the player
     *
     * @param  int $id, the id of the game
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {
        // Get the game
        $game = Game::find($id);

        // Get the player id
        $player = Auth::user();

        // Get the Participate model for (game_id, player_id)
        $participate = $game->participateByPlayer($player);

        // Get the Lost Clients List
        $LCL = $participate->inventory->lostClientList;

        // Get the player's role_id
        $game->role_id = $game->roleIdByPlayerid($player->id);

        // Get the game rules set
        $rules = $game->filteredRules($LCL, $game->role_id);
        $material = $rules->material;
        foreach ($material as $key => &$value)
        {
            $value->amountRat = AuxFunc::float2rat($value->amount);
        }
        $rules->material = $material;

        // Get the number of water tanks
        $game->numberOfTanks = $game->numberOfTanks();

        // Get the number of clients
        $game->numberOfClients = $game->numberOfClients();

        // Get the max soda price
        $game->maxSodaPrice = $game->maxSodaPrice();

        // Get the number of players
        $game->numberOfPlayers = $game->maxPlayers();

        // Check for bottling order need
        $game->needsBottlingElement = $game->needsBottling();

        // Check for production detailed order need
        $game->needsProductionDetailsElement = $game->needsProductionDetails();
        
        // Check if the game is orderable and processable
        if ($game->status == 'running')
        {
            // Count the orders already saved in for the phase
            $ordersCount = Order::where([
                'game_id' => $game->id,
                'period' => $game->period,
                'phase' => $game->phase
            ])->count();

            if (in_array($game->phase, ['pre', 'postInbound', 'postProduction', 'postOutbound', 'postCleanup']))
            {
                $game->isProcessable = 1;
            }
            else
            {
                $game->isOrderable = 1;
                if ($ordersCount == $game->numberOfPlayers) $game->isProcessable = 1;
            }
        }

        // Obter o histórico de ordens
        $orderHistory = Order::where([
                'game_id' => $game->id,
                'role_id' => $game->role_id
        ])->get();

        // Recuperar inventário e verificar se já foram dadas ordens
        // Recuperar as encomendas dos clientes (fase outbound)

        $inventory = $participate->inventory;

        $order = $orderHistory->filter(function($value, $key) use($game){
            return ($value->phase == $game->phase && $value->period == $game->period);
        })->first();

        if ($order === null)
        {
            $order = new Order;
        }
        else
        {
            $game->isAlreadyOrdered = 1;
        }
        
        // O array myOrders vai conter as encomendas ganhas pelo jogador.
        // É vazio em todas as fases exceto em outbound
        $myOrders = [];
        if ($game->phase == 'outbound')
        {
            $clientOrders = ClientOrder::where([
                ['game_id', '=', $id],
                ['period', '=' , $game->period]
            ])->first()->value;

            foreach ($clientOrders as $key => $value)
            {
                if (isset($value['role']) && $value['role'] == $game->role_id)
                {
                    $myOrders[$key] = $value['value'];
                }
            }
        }


        /***************************************************
         * CONSTRUIR A GRANDE TABELA DE RELAÇÕES COMERCIAIS
         ***************************************************
         */

        // Ofertas
        //$offerArray = Order::allOffers($game->id, $game->period, $game->numberOfPlayers, $game->numberOfClients);
        $offerArray = Order::allOffers($game->id, $game->period, $game->numberOfPlayers, $game->numberOfClients, $game->maxSodaPrice);

        // Encomendas
        $orderArray = ClientOrder::allOrders($game->id);

        // Clientes perdidos
        $LCLArray = Participate::allLCL($game->id);

        // Check if the current period is reportable
        $game->lastReportablePeriod = $game->period;
        if (in_array($game->phase, ['pre', 'inbound', 'postInbound', 'production']))
        {
            $game->lastReportablePeriod -= 1; 
        }

         /***************************************************
         * FIM DA GRANDE TABELA DE RELAÇÕES COMERCIAIS
         ***************************************************
         */

        // Retornar a view
        return view('layouts.play')->with([
            'game' => $game,
            'inventory' => $inventory,
            'order' => $order,
            'orderHistory' => $orderHistory,
            'clientOrders' => $myOrders,
            'rules' => $rules,
            'comercial' => $offerArray,
            'winner' => $orderArray,
            'lost' => $LCLArray
        ]);     
    }

}
