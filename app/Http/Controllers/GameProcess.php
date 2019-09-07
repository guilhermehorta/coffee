<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use App\Order;
use App\ClientOrder;
use App\Report;

class GameProcess extends Controller
{
    /**
     * Este controlador controla o processamento das várias fases do jogo.
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
     * @param  int $id, the id of the game
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {   
        // Get the game
        $game = Game::find($id);

        // Get the players 
        $players = $game->players;
        $playersCount = $players->count();

        //Count the orders
        $ordersCount = Order::where([
            ['game_id', '=', $id],
            ['phase', '=', $game->phase],
            ['period', '=' , $game->period]
        ])->count();

        // O processamento vai depender da fase        
        switch ($game->phase) {
            
            case 'pre':
            
            break;

            
            case 'inbound':
            if (!$this->checkOrders($ordersCount, $playersCount, $game->phase)) break;

            foreach ($players as $player)
            {
                            
                // O roleId do player
                $roleId = $game->roleIdByPlayerId($player->id);
                // Obter as ordens dadas e o participate Model do player 
                $order = Order::where([
                    ['game_id', '=', $id],
                    ['phase', '=', $game->phase],
                    ['period', '=' , $game->period],
                    ['role_id', '=', $roleId]
                ])->first()->order;

                // Criar o report para o player
                $report = $this->createNewReport($id, $game->period, $game->phase, $roleId);
                $facts = [];

                $participate = $game->participateByPlayerid($player->id);
                // Definir o oldInventory
                $participate->backUp();
                
                
                // Gerir empréstimo
                $cost = $participate->getLoan($order->loan, $game->period);
                $facts['loan'] = $order->loan;
                $facts['loanCost'] = $cost;
                
                // Comprar café
                $cost = $participate->buyRawMaterial('coffee', $order->buyCoffee);
                $facts['coffeeCost'] = $cost;
                // Comprar açucar
                $cost = $participate->buyRawMaterial('sugar', $order->buySugar);
                $facts['sugarCost'] = $cost;
                // Pagar transporte do café e do açucar
                // Construir rota
                unset ($rota);
                $rota[] = 'factory.'.($roleId);
                $rota[] = 'supplier.wholesaler';
                $cost = $participate->payTransport('truck', $order->buyCoffee + $order->buySugar, $rota, true);
                $facts['truckCost'] = $cost;

                // Comprar garrafas
                $cost = $participate->buyRawMaterial('bottles', $order->buyBottles);
                $facts['bottlesCost'] = $cost;
                // Pagar transporte das garrafas
                // Construir rota
                unset ($rota);
                $rota[] = 'factory.'.($roleId);
                $rota[] = 'supplier.wholesaler';
                $cost = $participate->payTransport('tuctuc', $order->buyBottles, $rota, true);
                $facts['tuctucCost'] = $cost;

                // Comprar água 
                $cost = $participate->buyWater($order->buyWater); // Array de unidades de água a comprar para cada tanque
                $facts['waterCost'] = $cost;
                // Pagar transporte da água
                // Construir rota
                unset ($rota);
                $rota[] = 'factory.'.($roleId);
                $rota[] = 'supplier.water';
                $cost = $participate->payTransport('tanker', array_sum((array) $order->buyWater), $rota, true);
                $facts['tankerCost'] = $cost;
                
                // Se não houver dinheiro, há um empréstimo compulsório
                $cost = $participate->getCompulsoryLoan();
                $facts['compulsoryLoan'] = $cost;

                //Gravar em BD
                $participate->save();
                $report->report = $facts;
                $report->save();
                
            }

            break;

            case 'production':
            if (!$this->checkOrders($ordersCount, $playersCount, $game->phase)) break;

            // Inicializar array para registar as melhores ofertas para cada cliente.
            for ( $i = 1; $i <= $game->numberOfClients(); $i++)
            {
                $bestOffer[$i] = ['value' => 1000000, 'time' => now(), 'role' => 0];
            }

            foreach ($players as $player)
            {

                // O roleId do player
                $roleId = $game->roleIdByPlayerid($player->id);
                // Obter as ordens dadas 
                $order = Order::where([
                    ['game_id', '=', $id],
                    ['phase', '=', $game->phase],
                    ['period', '=' , $game->period],
                    ['role_id', '=', $roleId]
                ])->first();

                // Criar o report para o player
                $report = $this->createNewReport($id, $game->period, $game->phase, $roleId);
                $facts = [];
                // O participate Model do player
                $participate = $game->participateByPlayerid($player->id);
                // Definir o oldInventory
                $participate->backUp();

                $tanks = [];
                if (isset($order->order->produceSoda))
                foreach ($order->order->produceSoda as $key => $value)
                {
                    if ($value == 1)
                    {
                        $tank = (object) [];
                        $tank->number = $key;
                        $tank->sugar = $order->order->sugar->{$key} ?? 0;
                        $tank->coffee = $order->order->coffee->{$key} ?? 0;
                        $tanks[] = $tank;
                    }
                }

                // Produzir e obter relatório de produção
                $facts['produção'] = $participate->produceFreshSoda($tanks);//($order->order->produceSoda)

                //Gravar em BD
                $participate->save();
                $report->report = $facts;
                $report->save();

                // Agora verificamos se, eventualmente, as ofertas feitas aos clientes são ganhadoras. Se uma oferta for melhor do que a guardada em bestOffer, irá substituí-la.
                if (isset($order->order->offer))
                {
                    foreach ($order->order->offer as $clientNumber => $price)
                    {
                        if (($price < $bestOffer[$clientNumber]['value'] && $price > 0) || ( $price == $bestOffer[$clientNumber]['value'] && $order->updated_at < $bestOffer[$clientNumber] ['time'] ))
                        {
                            // substituir valor em bestoffer
                            $bestOffer[$clientNumber]['value'] = $price;
                            $bestOffer[$clientNumber]['time'] = $order->updated_at;
                            $bestOffer[$clientNumber]['role'] = $participate->role_id;
                        }
                    }
                }

            }
   
            // Retirar tempos do array bestOffer.
            foreach ($bestOffer as $key => &$value)
            {
                unset($value['time']);
            }
            unset($value);


            // Persistir clientOrder na BD
            $clientOrder = ClientOrder::create([
                'game_id' => $id,
                'period' => $game->period,
                'value' => $bestOffer
            ]);
            
            break;

            case 'outbound':
            if (!$this->checkOrders($ordersCount, $playersCount, $game->phase)) break;

            // Obter o array das ordens dos clientes
            $clientOrders = ClientOrder::where([
                'game_id' => $id,
                'period' => $game->period
            ])->first()->value;
            
            foreach ($players as $player)
            {
                            
                // O roleId do player
                $roleId = $game->roleIdByPlayerid($player->id);

                // Obter as ordens dadas e o participate Model do player 
                $order = Order::where([
                    ['game_id', '=', $id],
                    ['phase', '=', $game->phase],
                    ['period', '=' , $game->period],
                    ['role_id', '=', $roleId]
                ])->first()->order;

                // Preparação do array para bottling
                $bottlingOrder = $this->prepareBottlingArray($order->newBottling);

                // Criar o report para o player
                $report = $this->createNewReport($id, $game->period, $game->phase, $roleId);
                $facts = [];

                // O status do player no jogo
                $participate = $game->participateByPlayerid($player->id);
                // Definir o oldInventory
                $participate->backUp();

                // Obter a lista de clientes a servir
                $myOrders = []; // clientNumber => price                
                foreach ($clientOrders as $key => $value)
                {
                    //if (isset($value['role']) && $value['role'] == $roleId)
                    if ($value['role'] == $roleId)
                    {
                        $myOrders[$key] = $value['value'];
                    }
                }

                // Obter as rotas, limpá-las e processar o enchimento.
                $totalCost = 0;
                $totalIncome = 0;
                $routes = explode(';', $order->routes);
                $cleanRoutes = [];
                foreach ($routes as $values)
                {
                    $route = explode(',', $values);
                    foreach ($route as $key => $clientNumber)
                    {
                        // Obter o nº do depósito a utilizar      
                        $deposit = $participate->fillFromDeposit($bottlingOrder);
                        if ($deposit != 0 && isset($myOrders) && array_key_exists($clientNumber, $myOrders) && $participate->inventory->bottles > 0)
                        {
                            // Processar esta venda
                            $totalCost += $participate->sellSoda($deposit, $myOrders[$clientNumber]);
                            $totalIncome += $myOrders[$clientNumber];
                            unset($myOrders[$clientNumber]); //Retira a ordem para evitar processamento em duplicado
                        }
                        else
                            // Anular esta venda
                            unset($route[$key]);
                    }
                    $cleanRoutes[] = $route;   
                }
                $facts['recyclingCost'] = $totalCost;
                $facts['income'] = $totalIncome;
            
                // Já processei as vendas e limpei as rotas. Agora é necessário processar as rotas
                $totalCost = 0;
                foreach ($cleanRoutes as $route)
                {
                    foreach ($route as &$element)
                    {
                        $element = 'client.'.$element;
                    }
                    unset($element);
                    array_unshift($route, 'factory.'.($roleId));
                    array_push($route, 'supplier.recyclingPlant');
                    $quantity = array_push($route, 'factory.'.($roleId)) - 3; // Adicionamos a localização da fabrica
                    // ao array route e, em simultâneo, calculamos o número de elementos a entregar

                    $totalCost += $participate->payTransport('tuctuc', $quantity, $route);
                    $facts['tuctucCost'] = $totalCost;
                }


                // Para terminar, atualizar o array de clientes perdidos
                // Vou enviar a lista de chaves que sobraram em $myOrders para serem acrescentadas a lista de clientes perdidos e para pagar a multa por cada cliente não servido
                $cost = $participate->addLostClients(array_keys($myOrders));
                $facts['lostClientsFine'] = $cost;

                // Se não houver dinheiro, há um empréstimo compulsório
                $cost = $participate->getCompulsoryLoan();
                $facts['compulsoryLoan'] = $cost;

                //Gravar em BD
                $participate->save();
                $report->report = $facts;
                $report->save();
                
            }

            break;

            case 'cleanup':
            if (!$this->checkOrders($ordersCount, $playersCount, $game->phase)) break;

            foreach ($players as $player) {

                // O roleId do player
                $roleId = $game->roleIdByPlayerid($player->id);
                // Obter as ordens dadas e o participate Model do player 
                $order = Order::where([
                    ['game_id', '=', $id],
                    ['phase', '=', $game->phase],
                    ['period', '=' , $game->period],
                    ['role_id', '=', $roleId]
                ])->first()->order;

                // O status do player no jogo
                $participate = $game->participateByPlayerid($player->id);
                // Definir o oldInventory
                $participate->backUp();
                            
                // Limpar tanques
                // Preparar array de tanques a limpar
                $tanks = [];
                if(isset($order->cleanTank))
                {
                    foreach ($order->cleanTank as $key => $value)
                    {
                        if ($value == 1) array_push($tanks, $key);
                    }
                }
                $participate->cleanTanks($tanks);

                //Gravar em BD
                $participate->save();
            }
            
            break;
            
            case 'postCleanup':

            foreach ($players as $player)
            {

                // O roleId do player
                $roleId = $game->roleIdByPlayerid($player->id);
                // Obter o participate Model do player 
                $participate = $game->participateByPlayerid($player->id);
                            
                // Envelhecer a soda
                $participate->sodaAgeing();

                //Gravar em BD
                $participate->save();
            }

            break;

            default:
                # code...
                break;
        }
        // Avançar a fase se não faltarem ordens
        if ($this->checkOrders($ordersCount, $playersCount, $game->phase)) $game->advanceGame();
        //Por fim retornar para a página de gestão de jogos
        return redirect()->route('games.manage', $id);
    }

    private function createNewReport($game, $period, $phase, $role)
    {
        $report = new Report;
        $report->game_id = $game;
        $report->role_id = $role;
        $report->period = $period;
        $report->phase = $phase;
        return $report;
    }

    private function checkOrders($ordersCount, $playersCount, $phase)
    {
        $doNotNeedOrders = array('pre', 'postInbound', 'postProduction', 'postOutbound', 'postCleanup');
        if ($ordersCount == $playersCount || in_array($phase, $doNotNeedOrders)) return 1;
        return 0;
    }


    /**
     * Prepare the bottling array.
     *
     * @param  array $order, the input from the player
     * @return array, ready to be used in Participate->fillFromDeposit
     */
    private function prepareBottlingArray($order)
    {
        $array = (array)$order;
        // Eliminar valores zero e não definidos        
        $filteredArray = array_filter($array);

        if (!empty($filteredArray))
        {
            asort($filteredArray);
            $bottlingArray = array_flip($filteredArray);
        }
        else $bottlingArray = [];

        return $bottlingArray;
    }

}