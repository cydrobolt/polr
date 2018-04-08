<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinkTableIndexes extends Migration
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
            // Add long_url hashes
            $table->unique('short_url');
            $table->string('long_url_hash', 10)->nullable();
            $table->index('long_url_hash', 'links_long_url_index');
        });

        // MySQL only statement
        // DB::statement("UPDATE links SET long_url_hash = crc32(long_url);");

        DB::table('links')->select(['id', 'long_url_hash', 'long_url'])
            ->chunk(100, function($links) {
                foreach ($links as $link) {
                    DB::table('links')
                        ->where('id', $link->id)
                        ->update([
                            'long_url_hash' => sprintf('%u', crc32($link->long_url))
                        ]);
                }
        });
    }

    public function down()
    {
        Schema::table('links', function (Blueprint $table)
        {
            $table->dropUnique('links_short_url_unique');
            $table->dropIndex('links_long_url_index');
            $table->dropColumn('long_url_hash');
        });
    }
}
