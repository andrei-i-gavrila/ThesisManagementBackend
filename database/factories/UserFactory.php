<?php

/* @var $factory Factory */


use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;


$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('password'),
        'activated' => 1
    ];
});
