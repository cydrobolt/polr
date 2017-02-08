<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLinkClicksToInteger extends Migration
{
    /**
     * Run the migrations.
     * Changes the "clicks" field in the link table to
     * an integer field rather than a string field.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('links', function (Blueprint $table)
        {
            $table->integer('clicks')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('links', function (Blueprint $table)
        {
            $table->string('clicks')->change();
        });
    }
}
