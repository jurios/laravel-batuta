<?php

use Faker\Generator as Faker;
use Kodilab\LaravelBatuta\Models\Resource;

$factory->define(\Kodilab\LaravelBatuta\Models\Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'god' => false,
        'default' => false
    ];
});
