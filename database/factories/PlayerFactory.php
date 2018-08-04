<?php

use Faker\Generator as Faker;

$factory->define(App\Player::class, function (Faker $faker) {
    return [
        'credential' => 0,
        'gold' => 0,
        'cash' => 0,
        'detail' => $faker->text,
    ];
});
