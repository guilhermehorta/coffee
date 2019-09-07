<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RuleSet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'material', 'map', 'vehicle', 'game',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'material' => 'object',
        'map' => 'object',
        'vehicle' => 'object',
        'game' => 'object'
    ];
    

    /**
     * Get the distance between two points
     *
     * @param string $locationA, $locationB
     * $location is a string like "factory.1" or "supplier.water"
     * @return int $distance
     */
    private function distance_between($locationA, $locationB)
    {
    	$pointA = explode('.', $locationA);
        $XA = $this->map->{$pointA[0]}->{$pointA[1]}->xPos;
        $YA = $this->map->{$pointA[0]}->{$pointA[1]}->yPos;

        $pointB = explode('.', $locationB);
        $XB = $this->map->{$pointB[0]}->{$pointB[1]}->xPos;
        $YB = $this->map->{$pointB[0]}->{$pointB[1]}->yPos;

        $distance = abs($XB - $XA) + abs($YB - $YA);
        return round($distance);
    }


    /**
     * Get the length of a route
     *
     * @param array $locations
     * each $location is a string like "factory.1" or "supplier.water"
     * @return int $length
     */
    private function route_length($locations)
    {
        $length = 0;
        $i = 1;
        while ($i < count($locations))
        {
            $length += $this->distance_between($locations[$i-1], $locations[$i]);
            $i++;
        }
        return round($length);
    }


    /**
     * Get the cost of a transport service
     *
     * @param string $vehicle, integer $quantity, array $locations, boolean $roundTrip=false
     * $location is a string like "factory.1" or "supplier.water"
     * $roundTrip indicates if the route should end in its begining
     * @return int $cost
     */
    public function calcTransportCost($vehicle, $quantity, $locations, $roundTrip = false)
    {
    	// Se $roundTrip for verdadeiro, alteramos a rota para que esta termine no seu início
    	if($roundTrip) array_push($locations, $locations[0]);

    	// Agora obtemos a extensão da rota
    	$distance = $this->route_length($locations);

    	// n = number of needed vehicles
        $n = ceil($quantity / $this->vehicle->{$vehicle}->capacity);
        
        $cost = $n * ( $this->vehicle->{$vehicle}->fixedCost + $this->vehicle->{$vehicle}->variableCost * $distance);
        return self::rd2($cost);
    }


    /**
     * Calculate the loan cost
     *
     * @param  int $period
     * @return int 
     */
    public function calcLoanCost($period)
    {
        // The loan cost is zero in first period. On other periods is the extraLoanCost game parameter
        if ($period == 1 ) return 0;
        else return self::rd2($this->game->extraLoanCost);
    }

    /**
     * Calculate the compulsory loan cost
     *
     * @param  int $value
     * @return int 
     */
    public function calcCompulsoryLoanCost($value)
    {
        // The compulsory loan cost is a fixed value plus a rate over the loan value.
        return self::rd2($this->game->forcedLoanCost + ( $this->game->bankOverdraftRate / 100 ) * $value);
    }

    /**
     * Calculate the interest value of a loan
     *
     * @param  int $loan
     * @return int 
     */
    public function calcInterestValue($loan)
    {
        // The interest of a loan is a percentage over the loan value.
        return self::rd2($this->game->interestRate / 100 * $loan);
    }

    /**
     * Get the maximum number of players
     *
     * @param  
     * @return int 
     */
    public function getNumberOfPlayers()
    {
        return (int) $this->game->numberOfPlayers;
    }

    /**
     * Get the number of Tanks
     *
     * @param  
     * @return int 
     */
    public function getNumberOfTanks()
    {
        return (int) $this->game->numberOfTanks;
    }

    /**
     * Get the soda price max limit
     *
     * @param  
     * @return int 
     */
    public function getMaxSodaPrice()
    {
        return self::rd2($this->game->sodaMaxPrice);
    }

    /**
     * Get the unit cost of raw material
     *
     * @param string $material
     * @return int 
     */
    public function getUnitCost($material)
    {
        return self::rd2($this->material->$material->price);
    }

    /**
     * Get the maximum capacity of the water tanks
     *
     * @param
     * @return int 
     */
    public function getMaxCapacity()
    {
        return (int) $this->game->tankMaxCapacity;
    }

    /**
     * Get the number of clients
     *
     * @param
     * @return int 
     */
    public function getNumberOfClients()
    {
        return (int) $this->game->numberOfClients;
    }

    /**
     * Get raw material consumption per soda unit produced
     *
     * @param string $material
     * @return int 
     */
    public function getMaterialConsumption($material)
    {
        return self::rd2($this->material->$material->amount);
    }

    /**
     * Get the recycling cost
     *
     * @param
     * @return int 
     */
    public function getRecyclingCost()
    {
        return self::rd2($this->game->recycleCost);
    }

    /**
     * Get the lost client fine value
     *
     * @param
     * @return int 
     */
    public function getlostClientFine()
    {
        return self::rd2($this->game->lostClientFine);
    }

    /**
     * Get the work force wage per period
     *
     * @param
     * @return int 
     */
    public function getUnitWage()
    {
        return self::rd2($this->game->workForceCost);
    }

    /**
     * Get the bottling rule option
     *
     * @param
     * @return string 
     */
    public function getBottlingRule()
    {
        return $this->game->bottlingRule;
    }

    /**
     * Get the need for bottling orders
     *
     * @param
     * @return boolean 
     */
    public function needsBottlingOrders()
    {
        if ($this->getBottlingRule() == 'strictPlayerOrder') return 1;
        if ($this->getBottlingRule() == 'relaxedPlayerOrder') return 1;

        return 0;
    }

    /**
     * Get the cleaning rule option
     *
     * @param
     * @return string 
     */
    public function getCleaningRule()
    {
        return $this->game->cleaningRule;
    }

    /**
     * Get the quality control option
     *
     * @param
     * @return string 
     */
    public function getQualityControlOption()
    {
        return $this->game->qualityControl;
    }

    /**
     * Get the production rule option
     *
     * @param
     * @return string 
     */
    public function getProductionRule()
    {
        return $this->game->productionRule;
    }

    /**
     * Get the need for production detailed orders
     *
     * @param
     * @return boolean 
     */
    public function needsProductionDetailsOrders()
    {
        if ($this->getProductionRule() == 'strictPlayerOrder') return 1;

        return 0;
    }

    /**
     * Arredonda um número para duas casas decimais(half up)
     *
     * @param float $value
     * @return $redondo
     */
    private static function rd2($value)
    {
        return round($value, 2);
    }

}
