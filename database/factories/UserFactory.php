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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name'      => $faker->name,
        'email'     => $faker->email,
        'password'  => '$2y$12$ReZfhJ7rvwCzfQbTAvGI6Or2nGDqKO5UiWw0owk.F78OY9.NbQQb6',//rahasia,
        'api_token' => '$2y$12$T87mz2ryZRYUJ1xvaBVx3eRbojPv.Ww8E0mcxPNO7Fk3MNrfAgIU',
    ];
});
