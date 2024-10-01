<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCookedProductsChangeProcessedProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('cooked_products', 'processed_products');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "RENAME TABLE `processed_products` TO `cooked_products`;";
        DB::connection()->getPdo()->exec($sql);

    }
}
