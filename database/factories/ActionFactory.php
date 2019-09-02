<?php

use Faker\Generator as Faker;
use Kodilab\LaravelBatuta\Models\Resource;

$factory->define(\Kodilab\LaravelBatuta\Models\Action::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'description' => $faker->paragraph,
        'resource_id' => function () {
            return factory(Resource::class)->create()->id;
        }
    ];
});
