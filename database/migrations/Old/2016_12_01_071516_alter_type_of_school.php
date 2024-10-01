<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypeOfSchool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('schools', function (Blueprint $table) {
            $table->enum('type',['education','comercio','bufete','acuaductos','super','tienda','restaurant'])->after('town');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->enum('type',['educacion','comercio'])->after('town');
        });
    }
}
