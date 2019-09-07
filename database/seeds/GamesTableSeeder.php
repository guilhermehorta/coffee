<?php

use Illuminate\Database\Seeder;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Game::class)->create([
            'name' => 'Jogo 1',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'status' => 'running',
            'period' => 1,
            'phase' => 'pre',
            'master_id' => 1,
            'rules_id' => 1,
        ]);

        factory(App\Game::class)->create([
            'name' => 'Jogo 2',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'status' => 'accepting',
            'period' => null,
            'phase' => null,
            'master_id' => 1,
            'rules_id' => 1,
        ]);

        factory(App\Game::class)->create([
            'name' => 'Jogo 3',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'status' => 'accepting',
            'period' => null,
            'phase' => null,
            'master_id' => 1,
            'rules_id' => 1,
        ]);

        factory(App\Game::class)->create([
            'name' => 'Jogo 4',
            'status' => 'accepting',
            'period' => null,
            'phase' => null,
            'master_id' => 1,
            'rules_id' => 1,
        ]);

        factory(App\Game::class)->create([
            'name' => 'Jogo 5',
            'status' => 'accepting',
            'period' => null,
            'phase' => null,
            'master_id' => 1,
            'rules_id' => 1,
        ]);

        // Agora vamos preencher a tabela participate (game_user)

        $i=1;
        while ($i<=2):
            DB::table('participate')->insert([
                'game_id' => 1,
                'user_id' => $i,
                'role_id' => $i
            ]);
            $i++;
        endwhile;

        $i=1;
        while ($i<=2):
            DB::table('participate')->insert([
                'game_id' => 2,
                'user_id' => $i
            ]);
            $i++;
        endwhile;

    }
}