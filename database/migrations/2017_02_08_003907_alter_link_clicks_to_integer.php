<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterLinkClicksToInteger extends Migration
{
    const PGSQL_DB_CONN = "pgsql";

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
            if ($this->isUsingPgsqlConnection()) {
                $dropDefaultStatement = "ALTER TABLE links ALTER clicks DROP DEFAULT;";
                $alterColumnTypeStatement = "ALTER TABLE links ALTER clicks TYPE INT using clicks::integer;";
                $setDefaultStatement = "ALTER TABLE links ALTER clicks SET DEFAULT 0;";
                DB::statement($dropDefaultStatement);
                DB::statement($alterColumnTypeStatement);
                DB::statement($setDefaultStatement);
            } else {
                $table->integer('clicks')->change();
            }
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
            if ($this->isUsingPgsqlConnection()) {
                $alterColumnTypeStatement = "ALTER TABLE links ALTER clicks TYPE character varying(255);";
                DB::statement($alterColumnTypeStatement);
            } else {
                $table->string('clicks')->change();
            }
        });
    }
    
    private function getDatabaseConnection() {
        return config("database")['default'];
    }
    
    private function isUsingPgsqlConnection() {
        return ($this->getDatabaseConnection() == self::PGSQL_DB_CONN);
    }
}
