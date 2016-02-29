<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->string('short_url');
            $table->longText('long_url');
            $table->string('ip');
            $table->string('creator');
            $table->string('clicks')->default(0);
            $table->string('secret_key');

            $table->boolean('is_disabled')->default(0);
            $table->boolean('is_custom')->default(0);
            $table->boolean('is_api')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('links');
    }
}
