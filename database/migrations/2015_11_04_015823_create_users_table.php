<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\User;

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
            $table->string('role')->default(User::USER_ROLE);
            $table->string('active');

            $table->string('api_key')->nullable();
            $table->boolean('api_active')->default(FALSE);
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
