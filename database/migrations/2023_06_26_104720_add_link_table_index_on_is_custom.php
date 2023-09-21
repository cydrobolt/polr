<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinkTableIndexOnIsCustom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('links', function (Blueprint $table)
        {
            // This index helps to find quickly the last ending generated
            $table->index('is_custom', 'links_is_custom_index');
        });

    }

    public function down()
    {
        Schema::table('links', function (Blueprint $table)
        {
            $table->dropIndex('is_custom');
        });
    }
}
