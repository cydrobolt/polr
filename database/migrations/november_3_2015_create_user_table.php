<?php

Schema::create('users', function(Blueprint $table)
{
    $table->increments('id');

    $table->string('username');
    $table->string('password');
    $table->string('email');

    $table->string('recovery_key');
    $table->string('role');
    $table->string('active');

    $table->string('api_key');
    $table->boolean('api_active');
    $table->string('api_quota');

    $table->timestamps();
});
