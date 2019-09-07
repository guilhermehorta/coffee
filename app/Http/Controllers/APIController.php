<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Participate;

class APIController extends Controller
{
    /**
     * Este controlador gere os pedidos à API
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
        //$this->middleware('auth');
    }


     /**
     * Compute the transportation cost.
     *
     * @param integer $gameID, string $routesString
     * @return \Illuminate\Http\Response
     */
    public function getTransportCost(Request $request)
    {
        // get the parameters
        $game = $request->game;
        $role = $request->role;
        $routesString = $request->routesString;

        // Get the game
        $participate = Participate::where('game_id', $game)->first();


        try
        {
        	// Process the routes
        	$totalCost = 0;
        	$routes = explode(';', $request->routesString);
        	foreach ($routes as $values)
        	{
        		$route = explode(',', $values);
        		foreach ($route as &$element)
        	    {
        	        $element = 'client.'.$element;
        	    }
        	    unset($element);
        	    array_unshift($route, 'factory.'.($role));
        	    array_push($route, 'supplier.recyclingPlant');
        	    $quantity = array_push($route, 'factory.'.($role)) - 3; // Adicionamos a localização da fabrica
        	    // ao array route e, em simultâneo, calculamos o número de localizações a visitar
        	    $totalCost += $participate->calculateTransport('tuctuc', $quantity, $route);
        	}
        	// A resposta
        	$response['success'] = true;
        	$response['cost'] = $totalCost;
    	}
    	catch (\Exception $e)
    	{
    		// A resposta
    		$response['success'] = false;
    	}
  
        echo json_encode($response);
        return;
    }

}
