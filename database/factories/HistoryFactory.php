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

$factory->define(App\History::class, function (Faker\Generator $faker) {
    return [
        'loggable_type' => $faker->word,
        'loggable_id'   => $faker->randomDigit,
        'action'        => $faker->word,
        'kwuid'         => $faker->randomDigit,
        'value'         => $faker->randomDigit
    ];
});
