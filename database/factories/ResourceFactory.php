<?php

use Faker\Generator as Faker;

$factory->define(\Kodilab\LaravelBatuta\Models\Resource::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
    ];
});
