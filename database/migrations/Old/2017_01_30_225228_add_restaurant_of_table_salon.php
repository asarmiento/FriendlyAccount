<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRestaurantOfTableSalon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table_salons', function (Blueprint $table) {
            $table->enum('restaurant',['no','si'])->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_salons', function (Blueprint $table) {
            $table->dropColumn('restaurant');
        });
    }
}
