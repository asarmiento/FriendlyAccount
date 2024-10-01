<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeOfMenuRestaurant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_restaurants', function (Blueprint $table) {
            //
            $table->boolean('type')->after('money');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_restaurants', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
