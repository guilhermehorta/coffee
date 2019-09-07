<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Game;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id', 'role_id', 'period', 'phase', 'order',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'order' => 'object',
    ];

    /**
     * The game the order belongs to.
     */
    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    /**
     * The role the order belongs to.
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * All the comercial offers for a game in one array.
     * This is useful for the comercial reports.
     * The array has the form $offerArray['period']['factory']['client']
     */
    //public static function allOffers($id, $nPeriod, $nFactory, $nClient)
    public static function allOffers($id, $nPeriod, $nFactory, $nClient, $maxPrice)
    {
        $collection = self::where([
                    'game_id' => $id,
                    'phase' => 'production',
        ])->get(['role_id', 'period', 'order']);
    
        $allOffers = $collection->map(function ($item, $key){
            $newItem=[];
            $newItem['period'] = $item['period'];
            $newItem['role_id'] = $item['role_id'];
            if (isset($item['order']->offer))
            $newItem['offer'] = $item['order']->offer;
            return $newItem;
        });
    
        // O allOffers é um array de todas as ofertas comerciais.
        $offerArray = [];
        for ($period = 1; $period <= $nPeriod; $period++)
        {
            $offerArray[$period] = [];
            for ($factory = 1; $factory <= $nFactory; $factory++)
            {
                $offerArray[$period][$factory] = [];
                $playerOffer = $allOffers->filter(function ($value, $key) use($period, $factory) {
                    return ($value['period'] == $period && $value['role_id'] == $factory);
                })->first();
                for ($client = 1; $client <= $nClient; $client++)
                {
                    if (isset($playerOffer['offer']->$client))
                    {
                        $offerArray[$period][$factory][$client] = $playerOffer['offer']->$client;
                    }
                    else $offerArray[$period][$factory][$client] = 0;
                } 
            }
        }


        //Cálculo dos médias das ofertas efetuadas a cada cliente
        foreach ($offerArray as $year => $yearofferset)
        {
            $counter = [];
            $sum = [];
            for ($i = 1; $i <= $nClient; $i++)
            {
                $counter[$i] = 0;
                $sum[$i] = 0;
            }
            
            foreach ($yearofferset as $factory => $offerset)
            {
                for ($i = 1; $i <= $nClient; $i++)
                {
                    //if ($offerset[$i] > 0 && $offerset[$i] <= 10)
                    if ($offerset[$i] > 0 && $offerset[$i] <= $maxPrice)
                    {
                        $counter[$i] += 1;
                        $sum[$i] += $offerset[$i];
                    }
                }   
            }
            for ($i = 1; $i <= $nClient; $i++)
            {
                $offerArray[$year]['average'][$i] = 0;
                if($counter[$i] > 0)
                {
                    $offerArray[$year]['average'][$i] = round($sum[$i] / $counter[$i], 2);
                }
            }
        }

        return $offerArray;
    }
}
