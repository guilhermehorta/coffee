<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User;
use App\Game;
use App\Role;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    return [
        'username' => $faker->unique()->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'is_admin' => $faker->boolean,
        'conditions_accepted_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'description' => $faker->word,
    ];
});

$factory->define(Game::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'password' => $faker->word,
        'status' => $faker->randomElement(array('accepting', 'running', 'finished', 'cancelled')),
        'period' => $faker->randomDigit,
        'phase' => $faker->randomElement(array('pre', 'inbound', 'production', 'outbound', 'cleanup', 'post')),
        'master_id' => $faker->randomDigit,
        'rules_id' => $faker->randomDigit,
    ];
});

$factory->define(App\RuleSet::class, function (Faker $faker) {
    $list = [];
    return [
        'name' => $faker->name,
        'material' => json_encode($list),
        'map' => json_encode($list),
        'vehicle' => json_encode($list),
        'game' => json_encode($list),
    ];
});
