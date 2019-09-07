<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'status', 'period', 'phase', 'master_id', 'rules_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The players engaged in the game.
     */
    public function players()
    {
        return $this->belongsToMany('App\User', 'participate')->using('App\Participate')->as('participate')->withPivot(['role_id', 'inventory', 'oldInventory']);
    }

    /**
     * The user that manages the game (the GameMaster).
     */
    public function master()
    {
        return $this->belongsTo('App\User');
    }


    /**
     * The clientOrders for the game.
     */
    public function clientOrders()
    {
        return $this->hasMany('App\ClientOrder');
    }

    /**
     * The rules for the game.
     */
    public function rules()
    {
        return $this->belongsTo('App\RuleSet');
    }

    /**
     * The number of tanks.
     */
    public function numberOfTanks()
    {
        return $this->rules->getNumberOfTanks();
    }

    /**
     * The number of clients.
     */
    public function numberOfClients()
    {
        return $this->rules->getNumberOfClients();
    }

    /**
     * The predicted number of players.
     */
    public function maxPlayers()
    {
        return $this->rules->getNumberOfPlayers();
    }

    /**
     * The maximum soda price.
     */
    public function maxSodaPrice()
    {
        return $this->rules->getMaxSodaPrice();
    }

    /**
     * The orders for the game.
     */
    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    /**
     * The Participate Model for one user.
     */
    public function participateByPlayer(User $player)
    {
        return $this->players->find($player)->participate;
    }

    /**
     * The Participate Model for one player.
     */
    public function participateByPlayerid($id)
    {
        return $this->players->find($id)->participate;
    }

    /**
     * The role_id for one player.
     */
    public function roleIdByPlayerid($id)
    {
        return $this->players->find($id)->participate->role_id;
    }

    /**
     * Check the need for bottling orders.
     */
    public function needsBottling()
    {
        return $this->rules->needsBottlingOrders();
    }

    /**
     * Check the need for detailed production orders.
     */
    public function needsProductionDetails()
    {
        return $this->rules->needsProductionDetailsOrders();
    }

    /**
     * Return a set of rules with a filtered map.
     */
    public function filteredRules($list, $factory)
    {
        $numberOfClients = $this->numberOfClients();
        $numberOfFactories = $this->maxPlayers();
        $filteredRules = $this->rules;
        $map = $filteredRules->map;

        $reducedClient = collect($map->client)->filter(function($item, $key) use ($numberOfClients) {
            return $key <= $numberOfClients;
        })->map(function($item, $key) use ($list) {
            if(in_array($key, $list)) $item->lost = 1;
            else $item->lost = 0;
            return $item;
        })->toArray();
        
        $map->client = $reducedClient;

        $reducedFactory = collect($map->factory)->filter(function($item, $key) use ($numberOfFactories) {
            return $key <= $numberOfFactories;
        })->map(function($item, $key) use ($factory) {
            if($key == $factory) $item->own = 1;
            else $item->own = 0;
            return $item;
        })->toArray();
        $map->factory = $reducedFactory;

        $filteredRules->map = $map;

        return $filteredRules;
    }

    /**
     * Advance the game for the next phase.
     */
    public function advanceGame()
    {
        switch ($this->phase) {
            
            case 'pre':
                $this->update(['phase' => 'inbound']);
                break;

            case 'inbound':
                $this->waitForMaster() ? $this->update(['phase' => 'postInbound']) : $this->update(['phase' => 'production']);
                break;

            case 'postInbound':
                $this->update(['phase' => 'production']);
                break;

            case 'production':
                $this->waitForMaster() ? $this->update(['phase' => 'postProduction']): $this->update(['phase' => 'outbound']);
                break;

            case 'postProduction':
                $this->update(['phase' => 'outbound']);
                break;

            case 'outbound':
                $this->waitForMaster() ? $this->update(['phase' => 'postOutbound']): $this->update(['phase' => 'cleanup']);
                break;

            case 'postOutbound':
                $this->update(['phase' => 'cleanup']);
                break;

            case 'cleanup':
                $this->update(['phase' => 'postCleanup']);
                break;

            case 'postCleanup':
                $this->update([
                    'phase' => 'inbound',
                    'period' => $this->period + 1
                ]);
                break;
            
            default:
                # code...
                break;
        }

        return;
    }

    /**
     * Check if the game should wait for the master (by the rules).
     */
    public function waitForMaster()
    {
        
        return $this->rules->game->waitForMaster;
    }
}