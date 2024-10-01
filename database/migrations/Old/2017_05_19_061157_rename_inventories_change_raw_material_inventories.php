<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameInventoriesChangeRawMaterialInventories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "RENAME TABLE `inventories` TO `raw_material_inventories`;";
        DB::connection()->getPdo()->exec($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "RENAME TABLE `raw_material_inventories` TO `inventories`;";
        DB::connection()->getPdo()->exec($sql);

    }
}
