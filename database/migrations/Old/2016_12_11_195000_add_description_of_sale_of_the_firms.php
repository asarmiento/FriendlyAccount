<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionOfSaleOfTheFirms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_of_the_firms', function (Blueprint $table) {
            //
            $table->mediumText('description')->after('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_of_the_firms', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
