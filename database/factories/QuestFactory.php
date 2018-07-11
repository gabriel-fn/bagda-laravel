<?php

use Faker\Generator as Faker;

$factory->define(App\Quest::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3, true),
        'detail' => $faker->text,
        'gold' => rand(100, 1000),
        'cash' => rand(10, 100),
    ];
});
