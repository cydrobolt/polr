<?php

$factory->define(\App\Models\User::class, function ($faker) {
    return [
        'username' => $faker->name,
        'email' => $faker->email,
        'password' => str_random(10),
        'recovery_key' => str_random(10),
    ];
});
