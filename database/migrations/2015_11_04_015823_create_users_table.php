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

            $table->string('username')->unique();
            $table->string('password');
            $table->string('email');
            $table->text('ip');

            $table->string('recovery_key');
            $table->string('role');
            $table->string('active');

            $table->string('api_key')->nullable();
            $table->boolean('api_active')->default(0);
            $table->string('api_quota')->default(60);

            $table->timestamps();
            $table->softDeletes();
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
