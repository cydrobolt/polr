<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clicks', function(Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->timestamp('date');
            $table->string('ip');
            $table->string('country')->nullable();
            $table->string('referer')->nullable();
            $table->string('referer_host')->nullable();
            $table->text('user_agent')->nullable();
            $table->unsignedInteger('link_id');

            $table->index('ip');
            $table->index('referer');

            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('clicks');
    }
}
