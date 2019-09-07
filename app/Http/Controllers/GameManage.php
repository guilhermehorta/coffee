<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use App\Role;
use App\Order;
use App\Report;
use App\Participate;
use App\ClientOrder;
use App\Http\Traits\AuxFunc;

class GameManage extends Controller
{
    /*
    |---------------------------------------------------
    | GameManage Controller
    |---------------------------------------------------
    |
    | This controller manages the Games.
    |
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
     * Manage a specified game.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {
        // Ler jogo da BD
        $game = Game::find($id);
        $game->lastReportablePeriod = $game->period;
        // Obter os players
        $players = $game->players;
        
        // Get the number of players
        $game->numberOfPlayers = $game->maxPlayers();

        // Get the number of water tanks
        $game->numberOfTanks = $game->numberOfTanks();

        // Get the number of clients
        $game->numberOfClients = $game->numberOfClients();

        // Get the max soda price
        $game->maxSodaPrice = $game->maxSodaPrice();

        // Get the game rules set
        $rules = $game->rules;
        $material = $rules->material;
        foreach ($material as $key => &$value)
        {
            $value->amountRat = AuxFunc::float2rat($value->amount);
        }
        $rules->material = $material;

        // Check for bottling order need
        $game->needsBottlingElement = $game->needsBottling();

        // Check for production detailed order need
        $game->needsProductionDetailsElement = $game->needsProductionDetails();


        // Se o jogo estiver em accepting, verificamos qts jogadores faltam e retornamos a view
        if ($game->status == "accepting")
        {
            // Determinar o número de players em falta
            $game->usersMissing = $game->numberOfPlayers - $players->count();
            // Retornar a view
            return view('layouts.manage')->with([
                'game' => $game,
                'users' => $players,
                'rules' => $rules,
            ]);
        }
        
        if (in_array($game->status, ['running', 'finished']))
        {
            // Get the players roles and participations
            foreach ($players as $player)
            {
                $player->role = Role::find($player->participate->role_id);
                $player->inventory = $game->participateByPlayerId($player->id)->inventory;
                $player->oldInventory = $game->participateByPlayerId($player->id)->oldInventory;
                $player->hasOrders = 0;
                $player->hasReport = 0;
            }

            // sort the players by role
            $players = $players->sortBy('role.id');

            // If in 'pre' phase, we leave here
            if ($game->phase == 'pre')
            {
                $game->processable = 1;                
                // Retornar a view
                return view('layouts.manage')->with([
                    'game' => $game,
                    'users' => $players,
                    'rules' => $rules,
                ]);
            }

            // Get the last active phase
            $game->activePhase = $this->activePhase($game->phase);

            // Get the reports and assign them to the players
            $reports = Report::where([
                    ['game_id', '=', $id],
                    ['period', '=' , $game->period],
                    ['phase', '=', $game->activePhase],
            ])->get();
            foreach ($reports as $report)
            {
                foreach ($players as $player)
                {
                    if($report->role_id == $player->role->id)
                    {
                        $player->hasReport = 1;
                        $player->report = $report->report;
                    }
                }
            }
            $game->masterReportParameterList = $this->masterReportParameterList($game->activePhase);

            // Get the orders, count them and assign them to the players
            $orders = Order::where([
                    ['game_id', '=', $id],
                    ['period', '=' , $game->period],
                    ['phase', '=', $game->activePhase],
            ])->get();
            $game->ordersMissing = $game->numberOfPlayers - $orders->count();
            foreach ($orders as $order)
            {
                foreach ($players as $player)
                {
                    if($order->role_id == $player->role->id)
                    {
                        $player->hasOrders = 1;
                        $player->order = $order->order;
                    }
                }
            }


            /***************************************************
             * CONSTRUIR A GRANDE TABELA DE RELAÇÕES COMERCIAIS
             ***************************************************
             */
            
            $offerArray = Order::allOffers($game->id, $game->period, $game->numberOfPlayers, $game->numberOfClients, $game->maxSodaPrice);
            $orderArray = ClientOrder::allOrders($game->id);
            $LCLArray = Participate::allLCL($game->id);

        }


        // Now, if the game is still running, we check if it is processable and endable
        if ($game->status == 'running')
        {
            switch ($game->phase)
            {   
                case 'postInbound':
                case 'postProduction':
                case 'postOutbound':
                    $game->processable = 1;
                    break;
                case 'postCleanup':
                    $game->processable = 1;
                    $game->endable = 1;    
                    break;
                case 'inbound':
                case 'production':
                case 'outbound':
                case 'cleanup':
                    if ($game->ordersMissing == 0) $game->processable = 1;
                    break;
            }
        }

        // Retornar a view
        return view('layouts.manage')->with([
            'game' => $game,
            'users' => $players,
            'rules' => $rules,
            'comercial' => $offerArray,
            'winner' => $orderArray,
            'lost' => $LCLArray,
        ]);
    }

    private function activePhase($phase)
    {
        switch ($phase)
        {
            case 'pre':
                $lastPhase = "pre";
                break;
            
            case 'inbound':
            case 'postInbound':
                $lastPhase = "inbound";
                break;

            case 'production':
            case 'postProduction':
                $lastPhase = "production";
                break;

            case 'outbound':
            case 'postOutbound':
                $lastPhase = "outbound";
                break;

            case 'cleanup':
            case 'postCleanup':
                $lastPhase = "cleanup";
                break;
        }
        return $lastPhase;
    }

    private function masterReportParameterList($phase)
    {
        switch ($phase)
        {                       
            case 'inbound':
                $parameterList = ['loan', 'loanCost', 'sugarCost', 'coffeeCost', 'bottlesCost', 'waterCost', 'tankerCost', 'truckCost', 'tuctucCost', 'compulsoryLoan'];
                break;

            case 'production':
                $parameterList = ['produção'];
                break;

            case 'outbound':
                $parameterList = ['income', 'lostClientsFine', 'recyclingCost', 'tuctucCost', 'compulsoryLoan'];
                break;

            case 'cleanup':
                $parameterList = [];
                break;
        }
        return $parameterList;
    }

}