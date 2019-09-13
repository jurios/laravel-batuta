<?php

use Faker\Generator as Faker;
use Kodilab\LaravelBatuta\Models\Resource;

$factory->define(\Kodilab\LaravelBatuta\Models\Action::class, function (Faker $faker) {
    return [
        'verb'          => $faker->unique()->word,
        'resource'      => $faker->unique()->word,
        'description'   => $faker->paragraph
    ];
});
