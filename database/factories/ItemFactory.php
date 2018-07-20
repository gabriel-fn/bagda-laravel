<?php

use Faker\Generator as Faker;

$factory->define(App\Item::class, function (Faker $faker) {
    return [
        'image' => 'default.jpg',
        'name' => $faker->sentence(2, true),
        'detail' => $faker->text,
        'gold_price' => rand(100, 1000),
        'cash_price' => rand(10, 100),
        'require_test' => rand(0, 1),
        'make_new' => rand(0, 1),
        'max_units' => rand(0, 1),
    ];
});
