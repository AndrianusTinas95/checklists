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

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'description'   => $faker->paragraph(1),
        'is_completed'  => rand(0,1),
        'completed_at'  => $faker->dateTime(),
        'due'           => $faker->dateTime(),
        'urgency'       => rand(1,5),
        'updated_by'    => $faker->randomDigit,
        'assignee_id'   => $faker->randomDigit,
        'task_id'       => $faker->randomDigit,
    ];
});
