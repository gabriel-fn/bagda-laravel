<?php

use Faker\Generator as Faker;

$factory->define(App\Report::class, function (Faker $faker) {
    return [
        'image' => 'default.jpg',
        'name' => $faker->sentence(5, true),
        'detail' => $faker->paragraph(10),
    ];
});
