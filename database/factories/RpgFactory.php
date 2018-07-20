<?php

use Faker\Generator as Faker;

$factory->define(App\Rpg::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3, true),
        'is_public' => true,
        'gold_starter' => rand(100, 1000),
        'cash_starter' => rand(10, 100),
    ];
});
