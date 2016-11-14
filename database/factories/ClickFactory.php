<?php

$factory->define(\App\Models\Click::class, function ($faker) {
    $ip = $faker->ipv4;
    $referer = microtime(true) % 2 ? $faker->url : null;

    return [
        'link_id' => 1,
        'date' => $faker->dateTimeThisMonth,
        'ip' => $ip,
        'country' => \App\Helpers\ClickHelper::getCountry($ip),
        'referer' => $referer,
        'referer_host' => \App\Helpers\ClickHelper::getHost($referer),
        'user_agent' => $faker->userAgent,
    ];
});