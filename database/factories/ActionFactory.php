<?php

use Faker\Generator as Faker;
use Kodilab\LaravelBatuta\Models\Action;
use Kodilab\LaravelBatuta\Models\Resource;

$factory->define(Action::class, function (Faker $faker) {
    return [
        'verb'          => $faker->unique()->word,
        'resource'      => $faker->unique()->word,
        'description'   => $faker->paragraph
    ];
});
