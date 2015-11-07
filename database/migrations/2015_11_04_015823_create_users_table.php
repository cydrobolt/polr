<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('username');
            $table->string('password');
            $table->string('email');
            $table->text('ip');

            $table->string('recovery_key');
            $table->string('role');
            $table->string('active');

            $table->string('api_key');
            $table->boolean('api_active');
            $table->string('api_quota');

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
        Schema::drop('users');
    }
}
