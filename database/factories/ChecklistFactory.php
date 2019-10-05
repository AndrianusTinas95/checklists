<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Checklist::class, function (Faker\Generator $faker) {
    return [
        'domain'        => $faker->word,
        'description'   => $faker->paragraph(),
        'is_complated'  => rand(0,1),
        'completed_at'  => $faker->dateTime(),
        'updated_by'    => $faker->randomDigit,
        'due'           => $faker->dateTime(),
        'urgency'       => rand(1,5)
    ];
});
