<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostToRawProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_products', function (Blueprint $table) {
            //
            $table->decimal('cost',20,2)->after('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_products', function (Blueprint $table) {
            $table->dropColumn('cost');
        });
    }
}
