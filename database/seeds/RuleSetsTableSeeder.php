<?php

use Illuminate\Database\Seeder;

class RuleSetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $game = [
			'numberOfPlayers' => 4, // not counting the GameMaster
			'numberOfClients' => 35,
			'lostClientFine' => 5.00,
			'workForceCost' => 5.00, // cost per period
			'interestRate' => 5, // % payable once at the end of the game
			'recycleCost' => .3, // per bottle
			'extraLoanCost' => 5.00,
			'forcedLoanCost' => 20.00,
			'bankOverdraftRate' => 10, // % of the bank overdraft
			'numberOfTanks' => 2,
			'tankMaxCapacity' => 2, // capacidade máxima dos tanques de produção de refresco
			'sodaMaxPrice' => 12.00, // preço máximo a que o refresco pode ser vendido
			'bottlingRule' => 'strictPlayerOrder', // define a ordem de utilização dos depósitos para bottling
			'waitForMaster' => 1, // define se o jogo para após as fases de inbound, production e outbound para intervenção do gamemaster (Intervenção pedagógica; análise dos relatórios; etc...).
			'productionRule' => 'strictPlayerOrder', // define o modo de execução da ordem de produção
			'cleaningRule' => 'strictPlayerOrder', // define o modo de execução da limpeza dos depósitos ('strictPlayerOrder' ou 'optimized')
		];

		$material = [
			'coffee' => [
				'price' => 1,
				'amount' => .1667,
				],
			'sugar' => [
				'price' => 1,
				'amount' => .3333,
				],
			'water' => [
				'price' => 10,
				'amount' => .1667,
				],
			'bottles' => [
				'price' => 1,
				'amount' => 1,
				],
		];

		$map = [
			'supplier' => [
				'water' => [
					'xPos' => 1,
					'yPos' => 3,
				],
				'wholesaler' => [
					'xPos' => 5,
					'yPos' => 3,
				],
				'recyclingPlant' => [
					'xPos' => 3,
					'yPos' => 2,
				],
			],
			'factory' => [
				'1' => [
					'xPos' => 1,
					'yPos' => 4,
				],
				'2' => [
					'xPos' => 5,
					'yPos' => 4,
				],
				'3' => [
					'xPos' => 5,
					'yPos' => 1,
				],
				'4' => [
					'xPos' => 1,
					'yPos' => 1,
				],
			],
			'client' => [
				'1' => [
					'order' => 1,
					'xPos' => 2,
					'yPos' => 4,
				],
				'2' => [
					'order' => 2,
					'xPos' => 2,
					'yPos' => 4,
				],
				'3' => [
					'order' => 1,
					'xPos' => 3,
					'yPos' => 4,
				],
				'4' => [
					'order' => 2,
					'xPos' => 3,
					'yPos' => 4,
				],
				'5' => [
					'order' => 3,
					'xPos' => 3,
					'yPos' => 4,
				],
				'6' => [
					'order' => 1,
					'xPos' => 4,
					'yPos' => 4,
				],
				'7' => [
					'order' => 2,
					'xPos' => 4,
					'yPos' => 4,
				],
				'8' => [
					'order' => 1,
					'xPos' => 5,
					'yPos' => 4,
				],
				'9' => [
					'order' => 1,
					'xPos' => 5,
					'yPos' => 1,
				],
				'10' => [
					'order' => 1,
					'xPos' => 4,
					'yPos' => 1,
				],
				'11' => [
					'order' => 2,
					'xPos' => 4,
					'yPos' => 1,
				],
				'12' => [
					'order' => 1,
					'xPos' => 3,
					'yPos' => 1,
				],
				'13' => [
					'order' => 2,
					'xPos' => 3,
					'yPos' => 1,
				],
				'14' => [
					'order' => 3,
					'xPos' => 3,
					'yPos' => 1,
				],
				'15' => [
					'order' => 1,
					'xPos' => 2,
					'yPos' => 1,
				],
				'16' => [
					'order' => 2,
					'xPos' => 2,
					'yPos' => 1,
				],
				'17' => [
					'order' => 1,
					'xPos' => 1,
					'yPos' => 1,
				],
				'18' => [
					'order' => 1,
					'xPos' => 1,
					'yPos' => 2,
				],
				'19' => [
					'order' => 3,
					'xPos' => 2,
					'yPos' => 4,
				],
				'20' => [
					'order' => 4,
					'xPos' => 2,
					'yPos' => 4,
				],
				'21' => [
					'order' => 4,
					'xPos' => 3,
					'yPos' => 4,
				],
				'22' => [
					'order' => 2,
					'xPos' => 1,
					'yPos' => 2,
				],
				'23' => [
					'order' => 5,
					'xPos' => 3,
					'yPos' => 4,
				],
				'24' => [
					'order' => 3,
					'xPos' => 4,
					'yPos' => 4,
				],
				'25' => [
					'order' => 4,
					'xPos' => 4,
					'yPos' => 4,
				],
				'26' => [
					'order' => 1,
					'xPos' => 5,
					'yPos' => 2,
				],
				'27' => [
					'order' => 2,
					'xPos' => 5,
					'yPos' => 2,
				],
				'28' => [
					'order' => 3,
					'xPos' => 4,
					'yPos' => 1,
				],
				'29' => [
					'order' => 4,
					'xPos' => 4,
					'yPos' => 1,
				],
				'30' => [
					'order' => 4,
					'xPos' => 3,
					'yPos' => 1,
				],
				'31' => [
					'order' => 3,
					'xPos' => 1,
					'yPos' => 2,
				],
				'32' => [
					'order' => 5,
					'xPos' => 3,
					'yPos' => 1,
				],
				'33' => [
					'order' => 3,
					'xPos' => 2,
					'yPos' => 1,
				],
				'34' => [
					'order' => 4,
					'xPos' => 2,
					'yPos' => 1,
				],
				'35' => [
					'order' => 1,
					'xPos' => 1,
					'yPos' => 4,
				],
			],
		];

		$vehicle = [
			'tuctuc' => [
				'capacity' => 8,
				'fixedCost' => 0,
				'variableCost' => .4,
			],
			'truck' => [
				'capacity' => 8,
				'fixedCost' => 0,
				'variableCost' => .3,
			],
			'tanker' => [
				'capacity' => 3,
				'fixedCost' => 0,
				'variableCost' => .5,
			],
		];
		
        factory(App\RuleSet::class)->create([
            'name' => 'Regras MO',
            'material' => $material,
            'map' => $map,
            'vehicle' => $vehicle,
            'game' => $game,
        ]);


        $game = [
			'numberOfPlayers' => 2, // not counting the GameMaster
			'numberOfClients' => 6,
			'lostClientFine' => 5.00,
			'workForceCost' => 5.00, // cost per period
			'interestRate' => 5, // % payable once at the end of the game
			'recycleCost' => .3, // per bottle
			'extraLoanCost' => 5.00,
			'forcedLoanCost' => 20.00,
			'bankOverdraftRate' => 10, // % of the bank overdraft
			'numberOfTanks' => 2,
			'tankMaxCapacity' => 2, // capacidade máxima dos tanques de produção de refresco
			'sodaMaxPrice' => 10.00, // preço máximo a que o refresco pode ser vendido
			'bottlingRule' => 'strictPlayerOrder', // define a ordem de utilização dos depósitos para bottling
			'waitForMaster' => 1, // define se o jogo para após as fases de inbound, production e outbound para intervenção do gamemaster (Intervenção pedagógica; análise dos relatórios; etc...).
			'productionRule' => 'strictPlayerOrder', // define o modo de execução da ordem de produção
		];

		factory(App\RuleSet::class)->create([
            'name' => 'Regras MO - versão reduzida',
            'material' => $material,
            'map' => $map,
            'vehicle' => $vehicle,
            'game' => $game,
        ]);
    }
}
