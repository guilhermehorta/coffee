<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Game;
use App\Order;

class ProductionOrderDeliver extends Controller
{
    /**
     * Este controlador recebe, valida e persiste as ordens Production.
     *
     */

     /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Este controlador só é acessível a utilizadores registados e verificados
        $this->middleware('auth');
        $this->middleware('verified');

        // Este controlador só é acessível se a fase/periodo ainda não tiver sido processada
        $this->middleware('phaseperiod');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $maxOffer = Game::find($request->game_id)->maxSodaPrice();
        $validatedData = $request->validate([
            'produceSoda' => 'array|min:0',
            'produceSoda.*' => 'boolean',
            'sugar' => 'array|min:0',
            'sugar.*' => 'nullable|integer|min:0',
            'coffee' => 'array|min:0',
            'coffee.*' => 'nullable|integer|min:0',
            'offer' => 'array|min:0',
            'offer.*' => 'nullable|numeric|min:0|max:'.$maxOffer,
        ]);

        $order = $request->only([
            'produceSoda',
            'offer',
            'sugar',
            'coffee'
        ]);

        isset($request->order_id) ? Order::find($request->order_id)->update([
                'order' => $order
            ]) : Order::create([
            'game_id' => $request->game_id,
            'role_id' => $request->role_id,
            'period' => $request->period,
            'phase' => $request->phase,
            'order' => $order
        ]);

        return redirect()->route('games.play', $request->game_id);

    }
}
