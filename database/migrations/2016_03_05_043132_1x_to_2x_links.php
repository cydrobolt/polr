<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class 1xTo2xLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rename users table from "auth" to "users"
        Schema::rename('redirinfo', 'links');

        Schema::table('links', function (Blueprint $table) {
            // Rename needed columns
            $table->renameColumn('rurl', 'long_url');
            $table->renameColumn('baseval', 'short_url');
            $table->renameColumn('iscustom', 'is_custom');
            $table->renameColumn('user', 'creator');
            $table->renameColumn('rurl', 'long_url');
            $table->renameColumn('rid', 'id');
            $table->renameColumn('date', 'CREATED_AT');
            $table->renameColumn('lkey', 'secret_key');

            // Drop unused columns
            $table->dropColumn('pw');
            $table->dropColumn('etc');
            $table->dropColumn('etc2');
            $table->dropColumn('country');

            // Create new columns
            $table->boolean('is_disabled')->default(0);
            $table->boolean('is_api')->default(0);

            // Modify needed columns
            $table->string('clicks')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            //
        });
    }
}
