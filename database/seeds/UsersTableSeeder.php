<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'username' => 'Guilherme',
            'email' => 'guilherme.horta@netcabo.pt',
            'is_admin' => true,
        ]);

        factory(App\User::class)->create([
            'username' => 'Jogador 1',
            'email' => 'jogador1@test.org',
            'is_admin' => false,
        ]);

        factory(App\User::class)->create([
            'username' => 'Jogador 2',
            'email' => 'jogador2@test.org',
            'is_admin' => false,
        ]);

        factory(App\User::class)->create([
            'username' => 'Jogador 3',
            'email' => 'jogador3@test.org',
            'is_admin' => false,
        ]);

        factory(App\User::class)->create([
            'username' => 'Jogador 4',
            'email' => 'jogador4@test.org',
            'is_admin' => false,
        ]);

        factory(App\User::class)->create([
            'username' => 'Jogador 5',
            'email' => 'jogador5@test.org',
            'is_admin' => false,
        ]);

    }
}