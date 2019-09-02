<?php

use Faker\Generator as Faker;

$factory->define(\Kodilab\LaravelBatuta\Models\Role::class, function (Faker $faker) {
    return [
        'name'      => $faker->unique()->name,
        'god'       => false,
        'default'   => false
    ];
});
