<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPriceOfProcessedProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql ="ALTER TABLE `processed_products` CHANGE `price` `price` DECIMAL(20,6) NOT NULL;";
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql ="ALTER TABLE `processed_products` CHANGE `price` `price` DECIMAL(20,4) NOT NULL;";
        DB::connection()->getPdo()->exec($sql);
    }
}
