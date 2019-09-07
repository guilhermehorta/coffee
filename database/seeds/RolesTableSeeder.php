<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Role::class)->create([
            'name' => 'Player 1',
            'description' => 'Manages Factory 1',
            ]);

        factory(App\Role::class)->create([
            'name' => 'Player 2',
            'description' => 'Manages Factory 2',
            ]);

        factory(App\Role::class)->create([
            'name' => 'Player 3',
            'description' => 'Manages Factory 3',
            ]);

        factory(App\Role::class)->create([
            'name' => 'Player 4',
            'description' => 'Manages Factory 4',
            ]);
    }
}