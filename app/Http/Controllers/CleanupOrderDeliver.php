<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Order;

class CleanupOrderDeliver extends Controller
{
    /**
     * Este controlador recebe, valida e persiste as ordens Cleanup.
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
        $validatedData = $request->validate([
            'cleanTank' => 'array|min:0',
            'cleanTank.*' => 'required|boolean',
        ]);

        $order = $request->only([
            'cleanTank'
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
