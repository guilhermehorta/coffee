<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Game;

class ClientOrder extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id', 'period', 'value',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'array',
    ];

    /**
     * The game the ClientOrder belongs to.
     */
    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    /**
     * All the client requests for a game in one array.
     * This is useful for the comercial reports.
     * The array has the form $orderArray['period'][arrayOfOrders]
     */
    public static function allOrders($id)
    {
        // Get the game
        $game = Game::find($id);
        // Get the number of players
        $numberOfPlayers = $game->maxPlayers();

        $collection = self::where([
                'game_id' => $id
        ])->get(['period', 'value']);

        // O allOrders é um array de todas as encomendas comerciais
        $orderArray = [];
        foreach ($collection as $value) {
            $orderArray[$value->period] = $value->value;
        }

        //Cálculo dos totais das encomendas ganhas por cada fábrica
        foreach ($orderArray as $year => $orderset)
        {
            $counter = [];
            $sum = [];
            for ($i = 0; $i <= $numberOfPlayers; $i++)
            {
                $counter[$i] = 0;
                $sum[$i] = 0;
            }
            foreach ($orderset as $client => $value)
            {
                $counter[$value['role']] += 1;
                $sum[$value['role']] += $value['value'];
            }
            for ($i = 0; $i <= $numberOfPlayers; $i++)
            {
                $orderArray[$year]['counter'][$i] = $counter[$i];
                $orderArray[$year]['sum'][$i] = $sum[$i];
            }
        }

        return $orderArray;
    }
}
