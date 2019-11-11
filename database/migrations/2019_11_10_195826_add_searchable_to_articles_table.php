<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddSearchableToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Config::get('database.default')=='pgsql') {
            DB::statement('ALTER TABLE articles ADD searchable tsvector NULL');
            DB::statement('CREATE INDEX articles_searchable_index ON articles USING GIN (searchable)');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Config::get('database.default')=='pgsql') {
            Schema::table('articles',function (Blueprint $table){
                $table->dropColumn('searchable');
            });
        }
    }
}
