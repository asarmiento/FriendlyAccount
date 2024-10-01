<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameRawProductsChangeRawMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "RENAME TABLE `raw_products` TO `raw_materials`;";
        DB::connection()->getPdo()->exec($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "RENAME TABLE `raw_materials` TO `raw_products`;";
        DB::connection()->getPdo()->exec($sql);

    }
}
