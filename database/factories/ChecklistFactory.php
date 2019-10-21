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

use Carbon\Carbon;

$factory->define(App\Checklist::class, function (Faker\Generator $faker) {
    return [
        'object_domain' => $faker->word,
        'description'   => $faker->sentence(1),
        'is_completed'  => rand(0,1),
        'completed_at'  => $faker->dateTime(),
        'updated_by'    => $faker->randomDigit,
        'due'           => $faker->dateTime(),
        'urgency'       => rand(1,5),
        // 'object_id'     => $faker->randomDigit
    ];
});
