<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

use App\RuleSet;

class Participate extends Pivot
{

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'inventory' => 'object',
        'oldInventory' => 'object'
    ];


    /**
     * The rules for the participation.
     */
    public function rules()
    {
        return RuleSet::find(Game::find($this->game_id)->rules_id);
    }

    /**
     * Create a new inventory
     *
     * @param  
     * @return array
     */
    public static function new_inventory($numberOfTanks)
    {
        // Criar tanques vazios
        for ($i=1; $i <= $numberOfTanks; $i++)
        { 
        	$keys = [
        		'contains',
        		'quantity'
        	];
        	$values = [
        		'water',
        		0
        	];
        	$tanks[$i] = array_combine($keys, $values);
        }

        // Criar lista vazia de clientes perdidos
        $lostClients = [];

        // Criar lista vazia de valores de inventário
        $keys = [
            'debt',
            'cash',
            'coffee',
            'sugar',
            'bottles',
            'tanks',
            'lostClientList'
        ];
        $values = [
            0,
            0,
            0,
            0,
            0,
            $tanks,
            $lostClients
        ];
        $value = array_combine($keys, $values);

        return $value;
    }

    /**
     * Get a new loan
     *
     * @param int $value, int $period
     * @return the cost of the loan
     */
    public function getLoan($value, $period)
    {
        $inventory = $this->inventory;
        $cost = 0;
        if ($value > 0) $cost = $this->rules()->calcLoanCost($period);
        $inventory->debt += $value;
        $inventory->cash += $value;       
        $inventory->cash -= $cost;
        $this->inventory = $inventory;
        return $cost;
    }

    /**
     * Get a forced loan
     *
     * @param 
     * @return Compulsory loan total value
     */
    public function getCompulsoryLoan()
    {
        $inventory = $this->inventory;
        // Verificar se é necessário o compulsoryLoan
        if ($inventory->cash >= 0) return 0;
            
        $value = -$inventory->cash;
        $compulsoryCost = $this->rules()->calcCompulsoryLoanCost($value);
        $inventory->debt += $value;
        $inventory->debt += $compulsoryCost;
        $inventory->cash = 0;       
        
        $this->inventory = $inventory;
        return ($value + $compulsoryCost);
    }

    /**
     * Pay the loan
     *
     * @param 
     * @return void
     */
    public function payLoan()
    {
        $inventory = $this->inventory;
        
        $interest = $this->rules()->calcInterestValue($inventory->debt);
        $inventory->cash -= $interest;
        $inventory->cash -= $inventory->debt;
        $inventory->debt = 0;
        if ($inventory->cash < 0)
        {
            $inventory->debt = -$inventory->cash;
            $inventory->cash = 0;
        }
        
        $this->inventory = $inventory;
    }

    /**
     * Buy raw material
     *
     * @param string $material, int $quantity
     * @return cost
     */
    public function buyRawMaterial($material, $quantity)
    {
        $inventory = $this->inventory;
        $unitCost = $this->rules()->getUnitCost($material);
        $cost = self::rd2($unitCost * $quantity);
        $inventory->$material += $quantity;
        $inventory->cash -= $cost;
        $this->inventory = $inventory;
        return $cost;
    }

    /**
     * Buy water
     *
     * @param array $quantities
     * @return totalCost
     */
    public function buyWater($quantities)
    {
        $inventory = $this->inventory;
        $totalCost = 0;
        $unitCost = $this->rules()->getUnitCost('water');
        $maxCapacity = $this->rules()->getMaxCapacity();
        foreach ($quantities as $key => $value)
        {
            if ($inventory->tanks->$key->contains == 'water')
            {
                $inventory->tanks->$key->quantity += $value;
                $inventory->tanks->$key->quantity = min($inventory->tanks->$key->quantity, $maxCapacity);
            }
            if ($inventory->tanks->$key->contains !== 'water' && $value > 0)
            {
                $inventory->tanks->$key->contains = "damagedSoda";
            }
            $inventory->cash -= self::rd2($unitCost * $value);
            $totalCost += self::rd2($unitCost * $value);
        }
        $this->inventory = $inventory;
        return $totalCost;
    }

    /**
     * Pay the transport cost
     *
     * @param string $vehicle, int $quantity, array $route [, boolean $round]
     * @return void
     */
    public function payTransport($vehicle, $quantity, $route, $round = false)
    {
        $inventory = $this->inventory;
        $transportCost = $this->rules()->calcTransportCost($vehicle, $quantity, $route, $round);
        $inventory->cash -= $transportCost;
        $this->inventory = $inventory;
        return $transportCost;
    }

    /**
     * Calculate (but not pay) the transport cost
     * This method is provided for estimation of tranport costs
     *
     * @param string $vehicle, int $quantity, array $route [, boolean $round]
     * @return void
     */
    public function calculateTransport($vehicle, $quantity, $route, $round = false)
    {
        $transportCost = $this->rules()->calcTransportCost($vehicle, $quantity, $route, $round);
        return $transportCost;
    }

    /**
     * Pay the workforce wages
     *
     * @param int $period
     * @return void
     */
    public function payWages($period)
    {
        $inventory = $this->inventory;
        $unitWage = $this->rules()->getUnitWage();
        $inventory->cash -= self::rd2($unitWage * $period);
        $this->inventory = $inventory;
    }

    /**
     * Produce the fresh soda
     *
     * @param array $productionOrder
     * @return void
     */
    public function produceFreshSoda($productionOrder)
    {
        $option = $this->rules()->getProductionRule();
        $inventory = $this->inventory;
        $report = "";

        if ($option == 'optimized')
        {
            foreach ($productionOrder as $order)
            {
                $key = $order->number;
                $report .= "Depósito $key :";
                if ($inventory->tanks->$key->contains =='water') // temos água
                {
                    $qttToProduce = self::rd0($inventory->tanks->$key->quantity / $this->rules()->getMaterialConsumption('water'));
                    $neededSugar = self::rd0($qttToProduce * $this->rules()->getMaterialConsumption('sugar'));
                    $neededCoffee = self::rd0($qttToProduce * $this->rules()->getMaterialConsumption('coffee'));
                    if ($inventory->sugar >= $neededSugar && $inventory->coffee >= $neededCoffee) // temos café e açucar
                    {
                        // Vamos produzir
                        $report .= "Produzidos $qttToProduce unidades";
                        $inventory->tanks->$key->contains = 'freshSoda';
                        $inventory->tanks->$key->quantity = $qttToProduce;
                        $inventory->sugar -= $neededSugar;
                        $inventory->coffee -= $neededCoffee;
                    }
                    else
                    {
                        $report .= "Falta matéria prima";
                    }
                }
                else
                {
                    $report .= "Não contém água";
                }
            }
        }


        if ($option == 'strictPlayerOrder')
        {
            foreach ($productionOrder as $order)
            {
                $key = $order->number;
                $report .= "Depósito $key :";

                // Verificar se foram especificadas as MP
                if ($order->sugar == 0 && $order->coffee == 0)
                {
                    //Não foi especificado a quantidade de materias primas a usar. Relatamos e saltamos para próxima ordem
                    $report .= "Falta especificar matéria prima; ";
                    continue;
                }

                // Verificar existência de MP
                if ($order->sugar > $inventory->sugar || $order->coffee > $inventory->coffee)
                {
                    //Não temos MP suficientes para produzir. Relatamos e saltamos para próxima ordem
                    $report .= "Falta matéria prima; ";
                    continue;
                }
                
                // Calcular os valores de fórmula
                $qttToProduce = self::rd0($inventory->tanks->$key->quantity / $this->rules()->getMaterialConsumption('water'));
                $formulaSugar = self::rd0($qttToProduce * $this->rules()->getMaterialConsumption('sugar'));
                $formulaCoffee = self::rd0($qttToProduce * $this->rules()->getMaterialConsumption('coffee'));

                // Verificamos formulação e conteúdo do depósito
                if($inventory->tanks->$key->contains != 'water' || $order->sugar != $formulaSugar || $order->coffee != $formulaCoffee)
                {
                    // Ordem não obedece a formulação ou depósito não contém água
                    // Produzimos refresco defeituoso, relatamos e saltamos para próxima ordem
                    if ($inventory->tanks->$key->contains == 'water') $inventory->tanks->$key->quantity = $qttToProduce;
                    $inventory->sugar -= $order->sugar;
                    $inventory->coffee -= $order->coffee;
                    $inventory->tanks->$key->contains = 'damagedSoda';
                    $report .= "Formulação errada; ";
                    continue;
                }

                // Produzimos refresco em conformidade com a formulação e relatamos
                $inventory->tanks->$key->quantity = $qttToProduce;
                $inventory->sugar -= $order->sugar;
                $inventory->coffee -= $order->coffee;
                $inventory->tanks->$key->contains = 'freshSoda';
                $report .= "Produção OK; ";
            }

        }
        $this->inventory = $inventory;
        return $report;
    }

    /**
     * Choose a deposit to fill the bottles from
     *
     * @param array $order
     * @return int (the number of the deposit where the next bottle should be filled from)
     */
    public function fillFromDeposit($order = [])
    {
        $option = $this->rules()->getBottlingRule();
        $inventory = $this->inventory;
        $sodaDeposits = collect($inventory->tanks)->filter(function($value, $key) {
            return (($value->contains == 'freshSoda' || $value->contains == 'warmSoda') && $value->quantity >= 1);
        });
        if($sodaDeposits->isEmpty()) return 0;
        $deposit = 0;
        
        if ($option == 'optimized')
        {
            $warmDeposits = $sodaDeposits->filter(function ($value, $key) {
                return ($value->contains == 'warmSoda');
            });
            if ($warmDeposits->isNotEmpty())
            {
                $deposit = $warmDeposits->sortBy('quantity')->keys()->first();
            }
            else
            {
                $freshDeposits = $sodaDeposits->filter(function ($value, $key) {
                    return ($value->contains == 'freshSoda');
                });
                if ($freshDeposits->isNotEmpty())
                {
                    $deposit = $freshDeposits->sortBy('quantity')->keys()->first();
                }
            }  
        }
        if ($option == 'strictPlayerOrder')
        {
            foreach ($order as $value)
            {
                if ($sodaDeposits->has($value))
                {
                    $deposit = $value;
                    break;
                }
            }           
        }
        if ($option == 'relaxedPlayerOrder')
        {
            foreach ($order as $value)
            {
                if ($sodaDeposits->has($value))
                {
                    $deposit = $value;
                    break;
                }
            }
            if ($deposit == 0) $option = 'lazy';
        }
        if ($option == 'lazy')
        {
            if ($sodaDeposits->isNotEmpty()) $deposit = $sodaDeposits->keys()->first();
        }
        return $deposit;
    }

    /**
     * Bottle and sell the soda
     *
     * @param int $deposit, int $price
     * @return void
     */
    public function sellSoda($deposit, $price)
    {
        $inventory = $this->inventory;
        $inventory->bottles -= 1;
        $inventory->tanks->$deposit->quantity -= 1;
        $inventory->cash += $price;
        $recyclingCost = $this->rules()->getRecyclingCost();
        $inventory->cash -= $recyclingCost;
        $this->inventory = $inventory;
        return $recyclingCost;
    }

    /**
     * Add clients to the lost clients list
     *
     * @param array $clients
     * @return void
     */
    public function addLostClients($clients)
    {
        $inventory = $this->inventory;
        
        {
            foreach ($clients as $value)
            {
                array_push($inventory->lostClientList, $value);
            }
            $lostClientsCost = self::rd2($this->rules()->getlostClientFine() * count($clients));
            $inventory->cash -= $lostClientsCost;
        }
        $this->inventory = $inventory;
        return $lostClientsCost;
    }

    /**
     * Clean the production tanks
     *
     * @param array $tanks
     * @return void
     */
    public function cleanTanks($list=[])
    {
        $option = $this->rules()->getCleaningRule();
        $inventory = $this->inventory;

        $warmDeposits = collect($inventory->tanks)->each(function($item, $key) use ($list, $option){
            if (in_array($key, $list) || ($option == 'optimized' && ($item->contains == 'warmSoda' || $item->contains == 'damagedSoda')) || $item->quantity == 0)
            {
                $item->contains = 'water';
                $item->quantity = 0;
            } 
            return;
        });

        $this->inventory = $inventory;
    }

    /**
     * Ageing the soda
     *
     * @param 
     * @return void
     */
    public function sodaAgeing()
    {
        $inventory = $this->inventory;

        // Envelhecer o conteúdo de todos os tanques que contenham warmSoda
        $warmDeposits = collect($inventory->tanks)->each(function($item, $key) {
            if ($item->contains == 'warmSoda') $item->contains = 'damagedSoda';
            if ($item->contains == 'freshSoda') $item->contains = 'warmSoda';
            return;
        });

        $this->inventory = $inventory;
    }

    /**
     * Backing up the inventory
     *
     * @param 
     * @return void
     */
    public function backUp()
    {
        $inventory = $this->inventory;
        $this->oldInventory = $inventory;
        return;
    }

    /**
     * Arredonda um número para duas casas decimais(half up)
     *
     * @param float $value
     * @return $redondo
     */
    public function rd2($value)
    {
        return round($value, 2);
    }

    /**
     * Arredonda um número para zero casas decimais(half up)
     *
     * @param float $value
     * @return $redondo
     */
    public function rd0($value)
    {
        return round($value);
    }

    /**
     * List of all the lost clients for a game in one array.
     * This is useful for the comercial reports.
     * The array has the form $LCLArray['factory'][arrayOfLCL]
     */
    public static function allLCL($id)
    {
        $collection = self::where([
                'game_id' => $id
        ])->get(['role_id', 'inventory']);

        // O allLCL é um array de todas os clientes perdidos
        $LCLArray = [];
        foreach ($collection as $value) {
            $LCLArray[$value->role_id] = $value->inventory->lostClientList;
        }

        return $LCLArray;
    }

}