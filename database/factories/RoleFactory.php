<?php

use Faker\Generator as Faker;
use Kodilab\LaravelBatuta\Models\Role;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'god' => false,
        'default' => false
    ];
});
