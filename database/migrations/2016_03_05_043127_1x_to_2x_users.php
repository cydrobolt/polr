<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class 1xTo2xUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('auth', 'users');

        Schema::table('users', function (Blueprint $table) {
            // Rename needed columns
            $table->renameColumn('rkey', 'recovery_key');
            $table->renameColumn('valid', 'active');
            $table->renameColumn('uid', 'id');

            // Drop unused columns
            $table->dropColumn('theme');

            // Create new columns
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
