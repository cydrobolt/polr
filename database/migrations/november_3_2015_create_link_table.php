<?php

Schema::create('links', function(Blueprint $table)
{
    $table->increments('id');

    $table->string('long_url');
    $table->string('ip');
    $table->string('creator');
    $table->string('clicks');
    $table->string('secret_key');
    $table->string('disabled_previous_link');

    $table->boolean('is_custom');

    $table->timestamps();
});
