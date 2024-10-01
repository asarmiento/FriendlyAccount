<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRawProductIdChangeRawMaterialIdOfInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // cambio de nombre del campo
        $sql = "ALTER TABLE `raw_material_inventories` CHANGE `raw_product_id` `raw_material_id` INT(10) UNSIGNED NOT NULL;";
            DB::connection()->getPdo()->exec($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // retornando cambio de nombre del campo
        $sql = "ALTER TABLE `raw_material_inventories` CHANGE `raw_material_id` `raw_product_id` INT(10) UNSIGNED NOT NULL;";
            DB::connection()->getPdo()->exec($sql);

    }
}
