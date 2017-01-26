<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clicks', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('ip');
            $table->string('country')->nullable();
            $table->string('referer')->nullable();
            $table->string('referer_host')->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('link_id')->unsigned();

            $table->index('ip');
            $table->index('referer_host');
            $table->index('link_id');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');

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
        Schema::drop('clicks');
    }
}
