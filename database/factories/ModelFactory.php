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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Game::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->realText(rand(10,20)),
        'steam_id' => $faker->randomNumber(6),
    ];
});

$factory->define(App\Store::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(App\Bundle::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->realText(rand(10,20)),
        'store_id' => $faker->numberBetween(1,5),
    ];
});