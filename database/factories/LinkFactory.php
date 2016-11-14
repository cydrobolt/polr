<?php

$factory->define(\App\Models\Link::class, function ($faker) {
    $user = \App\Models\User::find(1);
    return [
        'creator' => $user->username,
        'short_url' => $faker->md5,
        'long_url' => $faker->url,
        'ip' => $faker->ipv4,
        'clicks' => rand(0, 500),
    ];
});
